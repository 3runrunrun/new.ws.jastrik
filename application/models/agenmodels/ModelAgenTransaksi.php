<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAgenTransaksi extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index ()
  {
    return "yeah";
  }

  /**
   * Transaksi Offline
   */
  
  /**
   * Function - Memasukkan data qrcode transaksi baru
   * 
   * @param  [String] $kode_agen         [Kode Agen]
   * @param  [String] $kode_konsumen     [Kode Konsumen]
   * 
   * @return [Boolean]                   [Sukses/Tidak]
   */
  public function createQrCodeTransaksi (
    $kode_agen,
    $kode_konsumen,
    $enkripsi
    )
  {
    // SQL preparation
    $sql = "INSERT INTO qr_transaksi
      (kode_qr_transaksi,
      kode_agen,
      kode_konsumen,
      tanggal_pembuatan,
      enkripsi,
      scan) VALUES
      (?,?,?,?,?,?)";

    // Set tanggal_pembuatan kode
    $kode_qr_transaksi = date("ymdhis");
    $tanggal_pembuatan = date("Y-m-d H:i:s");
    
    // Parameter binding
    $bind_param = array (
      $kode_qr_transaksi,
      $kode_agen,
      $kode_konsumen,
      $tanggal_pembuatan,
      $enkripsi,
      "0"
      );
    
    // Query execution
    $query = $this->db->query (
      $sql,
      $bind_param
      );

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return $kode_qr_transaksi;
    }
  }
  
  /** 
   * Function - Create offline transaction
   * 
   * @param  String  $kode_transaksi    Kode transaksi
   * @param  String  $kode_konsumen     Kode konsumen
   * @param  String  $kode_agen         Kode agen
   * @param  String  $jenis_bayar       Jenis pembayaran
   * @param  Float   $subtotal          Subtotal
   * @param  Float   $biaya_antar       Estimasi biaya antar
   * @param  Float   $saldo_dompet      Saldo dompet
   * @param  String  $isAntarJemput     Status is antar / jemput / antar jemput
   * @param  String  $catatan_antar     Catatan pengantaran
   * @param  String  $telp              Notelp
   * @param  Float   $latitude_antar    Latitude
   * @param  Float   $longitude_antar   Longitude
   * @param  integer $longestDur        Durasi layanan terlama
   * @param  float   $bayar             Uang bayar
   * @param  integer $diskon            Diskon
   * @param  integer $pajak             Pajak
   * 
   * @return Boolean
   */
  public function createOfflineTransaction (
    $kode_transaksi,
    $kode_konsumen,
    $kode_agen,
    $jenis_bayar, 
    $subtotal,
    $biaya_antar,
    $saldo_dompet,
    $isAntarJemput,
    $longestDur, // Hari terlama layanan
    $tanggal_terima,
    $bayar = NULL,
    $diskon = 0,
    $pajak = 0
    )
  {
    // Prepare $tanggal_terima
    // $tanggal_terima = date("Y-m-d H:i:s");

    // Prepare $tanggal_selesai
    $curdateTimeFormat = strtotime($tanggal_terima);
    $tanggal_selesai = date(
      "Y-m-d H:i:s", 
      strtotime(
        "+" . $longestDur . " day", 
        $curdateTimeFormat
        )
      );

    // Prepare $diskon
    $diskon = $diskon / 100;

    // Prepare $pajak
    $pajak = $pajak / 100;

    // Prepare $total
    $total = ($subtotal * (1 - $diskon) + $biaya_antar) * (1 + $pajak); 

    // Prepare $kembalian
    $kembalian = $bayar - $total;

    // Prepare perhitungan dengan berbagai macam jenis pembayaran
    $isBalanceSufficient = FALSE;
    switch ($jenis_bayar) {
      case 'cash':
        $isBalanceSufficient = TRUE;
        $jenis_bayar = "0";
        $status_bayar = "0";
        break;

      case 'transfer':
        $isBalanceSufficient = TRUE;
        $jenis_bayar = "1";
        $status_bayar = "0";
        break;

      case 'dompet':

        if ($total < $saldo_dompet) {
          $isBalanceSufficient = TRUE;
        }
        
        $jenis_bayar = "2";
        $status_bayar = "1";
        break;

      default:
        # code...
        break;
    }

    /*if ($isBalanceSufficient == TRUE) {
      $a = "betul";
      print_r($a);
    } else {
      $a = "salah";
      print_r($a);
      print_r($total);
      print_r($saldo_dompet);
    }*/

    // Insert new transaction
    $sqlA = "INSERT INTO transaksi 
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $bind_paramA = array (
      $kode_transaksi,
      $kode_konsumen,
      $kode_agen,
      NULL, // kode_parfum
      $tanggal_terima,
      $tanggal_selesai,
      $jenis_bayar,
      $subtotal, //subtotal
      $diskon, // diskon
      $biaya_antar, // biaya_antar
      $pajak, // pajak
      $total, // total
      $bayar, // bayar
      $kembalian, // kembalian
      $status_bayar, // status_bayar
      "0", // jenis_antar
      "0", // jenis_jemput
      "5", // status_transaksi
      NULL,// catatan
      "0" // hapus
      );

    // Insert Transaksi Antar
    $sqlC = "INSERT INTO transaksi_antar
      (kode_konsumen_alamat,
      kode_transaksi,
      catatan,
      notelp,
      status_transaksi_antar,
      latitude,
      longitude,
      hapus) VALUES (?,?,?,?,?,?,?,?)";
    $bind_paramC = array (
      NULL,
      $kode_transaksi,
      NULL,
      NULL,
      "0",
      NULL,
      NULL,
      "0"
      );

    // Decrease balance
    $sqlD = "UPDATE konsumen 
      SET saldo_dompet = saldo_dompet - ?
      WHERE kode_konsumen = ?
        AND hapus = ?";
    $bind_paramD = array (
      $total,
      $kode_konsumen,
      "0"
      );

    // Insert history dompet
    $sqlE = "INSERT INTO konsumen_history_dompet 
      (
      kode_konsumen,
      nominal_masuk,
      nominal_keluar,
      tanggal,
      ref_konsumen_history_dompet,
      hapus
      )
      VALUES (?,?,?,?,?,?)";
    $bind_paramE = array (
      $kode_konsumen,
      0,
      $total,
      $tanggal_terima,
      $kode_transaksi,
      "0"
      );

    // Updating dana agen
    $sqlF = "UPDATE agen
      SET dana_agen = dana_agen + ?
      WHERE kode_agen = ?
        AND hapus = ?";
    $bind_paramF = array (
      $total,
      $kode_agen,
      "0"
      );

    // Begin transaction
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );

    if ($isAntarJemput == 2
      || $isAntarJemput == 3) {
      $this->db->query(
        $sqlC,
        $bind_paramC
        );
    }
    
    if ($jenis_bayar == 2) {
      if ($isBalanceSufficient == FALSE) {
        $this->db->trans_rollback();
        // return $this->db->error();
        return FALSE;
      } else {
        $this->db->query(
          $sqlD,
          $bind_paramD
          );
        $this->db->query(
          $sqlE,
          $bind_paramE
          );
      }
    }

    if ($jenis_bayar == "0") {
      $this->db->query(
        $sqlF,
        $bind_paramF
        );
    }

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Function - Canceling transaction if any error occur
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return Boolean
   */
  public function deleteOfflineTransaction ($kode_transaksi)
  {
    // Query preparation
    $sql = "DELETE FROM transaksi WHERE kode_transaksi = ?";

    // Parameter binding
    $bind_param = array ($kode_transaksi);

    // Starting transaction
    $this->db->trans_begin();

    $this->db->query(
      $sql,
      $bind_param
      );

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Function - Insert every layanan on transaction
   * 
   * @param  String   $kode_harga_layanan  Kode harga layanan
   * @param  String   $kode_transaksi      Kode transaksi
   * @param  Float    $jumlah              Jumlah (kilogram)
   * @param  integer  $jumlah_helai        Jumlah (pcs)
   * @param  Float    $panjang             Jumlah panjang
   * @param  Float    $lebar               Jumlah lebar
   * @param  Float    $harga               Harga
   * 
   * @return Boolean/Integer               FALSE/inserted id
   */
  public function createTransactionOnLayanan (
    $kode_harga_layanan,
    $kode_transaksi,
    $jumlah,
    $jumlah_helai,
    $panjang,
    $lebar,
    $harga)
  {
    // Query A preparation
    $sqlA = "INSERT INTO transaksi_layanan 
      (kode_harga_layanan,
      kode_transaksi,
      jumlah,
      jumlah_helai,
      panjang,
      lebar,
      harga,
      hapus) 
      VALUES (?,?,?,?,?,?,?,?)";

    // Binding parameter for sqlA
    $bind_paramA = array (
      $kode_harga_layanan,
      $kode_transaksi,
      $jumlah,
      $jumlah_helai,
      $panjang,
      $lebar,
      $harga,
      "0");

    $query = $this->db->query(
      $sqlA,
      $bind_paramA
      );

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      $insert_id = $this->db->insert_id();
      return $insert_id;
    }
  }

  /**
   * Function - Insert every item on each kilograms services
   * 
   * @param  integer $kode_transaksi_layanan Kode layanan pada transaksi
   * @param  String  $kode_transaksi         Kode transaksi
   * @param  integer $kode_item              Kode item
   * @param  Float   $jumlah                 Jumlah (Pcs)
   * 
   * @return Boolean
   */
  public function createTransactionOnItem (
    $kode_transaksi_layanan,
    $kode_transaksi,
    $kode_item,
    $jumlah
    )
  {
    $sql = "INSERT INTO transaksi_item
      (kode_transaksi_layanan, 
      kode_transaksi, 
      kode_item, 
      jumlah, 
      hapus)
      VALUES (?,?,?,?,?)";

    $bind_param = array (
      $kode_transaksi_layanan,
      $kode_transaksi,
      $kode_item,
      $jumlah,
      "0"
      );

    $this->db->trans_begin();

    $this->db->query(
      $sql,
      $bind_param
      );

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Endof - Transaksi Offline
   */

  /**
   * Detail transaksi
   */

  public function retrieveBasicDetailTransaksi ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT 
        ts.kode_transaksi,
        ts.kode_konsumen,
        kn.nama,
        ka.alamat,
        tj.latitude,
        tj.longitude,
        ts.tanggal_terima,
        DATE_ADD(ts.tanggal_terima, INTERVAL 5 MINUTE) AS tanggal_expired
      FROM transaksi ts
      JOIN transaksi_jemput tj 
        ON ts.kode_transaksi = tj.kode_transaksi
      JOIN konsumen kn
        ON ts.kode_konsumen = kn.kode_konsumen
      JOIN konsumen_alamat ka
        ON tj.kode_konsumen_alamat = ka.kode_konsumen_alamat
      WHERE ts.kode_transaksi = ? 
        AND ts.hapus = ?
        AND tj.hapus = ?
        AND kn.hapus = ?
        AND ka.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      "0",
      "0",
      "0",
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan detail transaksi yang akan diterima atau ditolak
   * 
   * @param  String $kode_transaksi         Kode Transaksi
   * 
   * @return [Boolean / String / Array]     Result
   */
  public function retrieveLayananDetailTransaksi ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT 
        l.nama_layanan
      FROM transaksi_layanan tl
      JOIN layanan_harga lh
        ON tl.kode_harga_layanan = lh.kode_harga_layanan
      JOIN layanan l
        ON lh.kode_layanan = l.kode_layanan
      WHERE tl.kode_transaksi = ?
        AND tl.hapus = ?
        AND lh.hapus = ?
        AND l.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      "0",
      "0",
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Respond on transaction
   * 
   * @param  String $kode_transaksi         Kode transaksi
   * @param  String $status_transaksi       Status transaksi
   * @param  String $kode_kurir             Kode kurir
   * @param  String $jenis_antar            jenis antar
   * @param  String $jenis_jemput           Jenis Jemput
   * 
   * @return Boolean
   */
  public function createRespondTransaction (
    $kode_transaksi,
    $status_transaksi,
    $kode_kurir = null,
    $jenis_antar = null,
    $jenis_jemput = null
    )
  {
    // SQL for updating transaction status
    $sqlA = "UPDATE transaksi
      SET status_transaksi = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_paramA = array (
      $status_transaksi,
      $kode_transaksi,
      "0"
      );

    // SQL if transaction is rejected
    $sqlB = "INSERT INTO transaksi_ditolak
      VALUES (?,?,?,?,?,?)";

    // Parameter binding
    $bind_paramB = array (
      $kode_transaksi,
      date("Y-m-d H:i:s"),
      strtoupper("ditolak agen pertama"),
      "0",
      "ref",
      "0"
      );

    // Query for assign kode_kurir to transaksi_jemput
    $sqlC = "UPDATE transaksi_jemput
      SET kode_kurir = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_paramC = array (
      $kode_kurir,
      $kode_transaksi,
      "0"
      );

    // Query for assign kode_kurir to transaksi_antar
    $sqlD = "UPDATE transaksi_antar
      SET kode_kurir = ?,
        auto_antar = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_paramD = array (
      $kode_kurir,
      "0",
      $kode_transaksi,
      "0"
      );

    // Query for assign kode_kurir to transaksi_antar (auto_antar)
    $sqlE = "UPDATE transaksi_antar
      SET kode_kurir = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_paramE = array (
      $kode_kurir,
      $kode_transaksi,
      "0"
      );

    // Transaction begin
    $this->db->trans_begin();

    // Query execution
    $this->db->query(
      $sqlA,
      $bind_paramA
      );

    if ($status_transaksi == "1"
      && $jenis_jemput == "1"
      && $jenis_antar == "0") {
      $this->db->query(
        $sqlC,
        $bind_paramC
        );
      $this->db->query(
        $sqlE,
        $bind_paramE
        );
    } elseif ($status_transaksi == "1"
      && $jenis_jemput == "0"
      && $jenis_antar == "1") {
      $this->db->query(
        $sqlD,
        $bind_paramD
        );
    } elseif ($status_transaksi == "1"
      && $jenis_jemput == "1"
      && $jenis_antar == "1") {
      $this->db->query(
        $sqlC,
        $bind_paramC
        );
      $this->db->query(
        $sqlD,
        $bind_paramD
        );
    }
    
    if ($status_transaksi == "20") {
      $this->db->query(
        $sqlB,
        $bind_paramB
        );
    } 
    
    if ($this->db->trans_status() === FALSE) {
      $error = $this->db->error();
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Function - Update transaction's status
   * 
   * @param  String $kode_transaksi           Kode transaksi
   * @param  String $status_transaksi_lama    Status transaksi sebelumnya
   * @param  String $status_transaksi_baru    Status transaksi baru
   * 
   * @return Boolean
   */
  public function updateStatusTransaksi (
    $kode_transaksi,
    $status_transaksi_lama,
    $status_transaksi_baru,
    $kode_agen = NULL,
    $total = NULL,
    $jenis_bayar = NULL
    )
  {
    // Updating status_transaksi
    $sqlA = "UPDATE transaksi
      SET status_transaksi = ?
      WHERE kode_transaksi = ?
        AND status_transaksi = ?
        AND hapus = ?";
    $bind_paramA = array (
      $status_transaksi_baru,
      $kode_transaksi,
      $status_transaksi_lama,
      "0"
      );

    // Updating dana agen
    $sqlB = "UPDATE agen
      SET dana_agen = dana_agen + ?
      WHERE kode_agen = ?
        AND hapus = ?";
    $bind_paramB = array (
      $total,
      $kode_agen,
      "0"
      );

    // Query execution
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );

    if ($status_transaksi_baru == "5") {
      if ($jenis_bayar == "0") {
        $this->db->query(
          $sqlB,
          $bind_paramB
          );
      }      
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Deposit Request
   */
  
  /**
   * Function - Accept deposit request from checker
   * 
   * @param  String $kode_agen_setoran_dana     Kode request setoran dana
   * @param  String $kode_agen                  Kode agen
   * @param  Float  $nominal                    Nominal setoran
   * 
   * @return Boolean
   */
  public function createRequestDepositResponse (
    $kode_agen_setoran_dana,
    $kode_agen,
    $nominal
    )
  {
    // Query A preparation
    $sqlA = "UPDATE agen_setoran_dana
      SET status_agen_setoran_dana = ?
      WHERE kode_agen_setoran_dana = ?
        AND hapus = ?";
    $bind_paramA = array (
      "1",
      $kode_agen_setoran_dana,
      "0"
      );

    // Query B preparation
    $sqlB = "UPDATE agen
      SET dana_agen = dana_agen - ?
      WHERE kode_agen = ?
        AND hapus = ?";
    $bind_paramB = array (
      $nominal,
      $kode_agen,
      "0"
      );


    // Query execution begin
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
      );

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

}