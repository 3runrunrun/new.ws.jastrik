<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKonsumenTransaksi extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Retrieve all transaksi data by kode konsumen
   *
   * @param string $kode_konsumen    kode konsumen 
   * 
   * @return FALSE / result array
   */
  public function retrieveTransaksi ($kode_konsumen)
  {
    // SQL preparing
    $sql = "SELECT 
        kode_transaksi,
        tanggal_terima,
        tanggal_selesai,
        total,
        status_transaksi
      FROM transaksi
      WHERE kode_konsumen = ?
        AND status_transaksi IN ?
        AND jenis_bayar <> ?
        AND hapus = ?
      ORDER BY tanggal_terima DESC";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
      array("11", "20", "21", "22", "23", "24"),
      "1",
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Retrieve all transaction that's not rejected nor finished
   * 
   * @param  String $kode_konsumen          Kode konsumen
   * 
   * @return Boolean/String/Array           FALSE/"EMPTY"/Result
   */
  public function retrieveTransaksiAktif ($kode_konsumen)
  {
    // SQL preparation
    $sql = "SELECT 
        kode_transaksi,
        tanggal_terima,
        tanggal_selesai,
        total,
        COALESCE(status_bayar, '0') AS status_bayar,
        jenis_bayar,
        status_transaksi,
        COALESCE(jenis_antar, '0') AS jenis_antar
      FROM transaksi
      WHERE kode_konsumen = ?
        AND status_transaksi NOT IN ?
        AND hapus = ?
      ORDER BY transaksi.tanggal_terima DESC";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
      array ("11","20","21","22","23","24"),
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
   * Modular function of Detail Transaksi
   */

  /**
   * Function - menampilkan informasi dasar untuk detil transaksi
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]           [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveRingkasanPesanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
       transaksi.kode_transaksi,
       transaksi.tanggal_terima,
       transaksi.tanggal_selesai,
       transaksi.status_transaksi,
       transaksi.total,
       transaksi.status_bayar
      FROM transaksi
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan identitas konsumen
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveIdentitasPesanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_konsumen,
        konsumen.nama AS nama_konsumen,
        transaksi.kode_agen,
        agen.nama AS nama_agen,
        kurir_jemput.nama AS nama_kurir_jemput,
        kurir_antar.nama AS nama_kurir_antar
      FROM transaksi
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      JOIN agen 
        ON transaksi.kode_agen = agen.kode_agen
      LEFT JOIN transaksi_jemput
        ON transaksi.kode_transaksi = transaksi_jemput.kode_transaksi
      LEFT JOIN kurir AS kurir_jemput
        ON transaksi_jemput.kode_kurir = kurir_jemput.kode_kurir
      LEFT JOIN transaksi_antar
        ON transaksi.kode_transaksi = transaksi_antar.kode_transaksi
      LEFT JOIN kurir AS kurir_antar
        ON transaksi_jemput.kode_kurir = kurir_antar.kode_kurir
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?
        AND konsumen.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan identitas kurir penjemput
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveStatusPesanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.status_transaksi
      FROM transaksi
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        $status_pesanan = $query->result_array();
        return $status_pesanan[0]['status_transaksi'];
      }
    }
  }

  /**
   * Function - menampilkan layanan
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveLayanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi_layanan.kode_transaksi_layanan,
        layanan.nama_layanan
      FROM transaksi_layanan
      JOIN transaksi
        ON transaksi_layanan.kode_transaksi = transaksi.kode_transaksi
      JOIN layanan_harga
        ON transaksi_layanan.kode_harga_layanan = layanan_harga.kode_harga_layanan
      JOIN layanan
        ON layanan.kode_layanan = layanan_harga.kode_layanan
      WHERE transaksi_layanan.kode_transaksi = ?
        AND transaksi_layanan.hapus = ?
        AND transaksi.hapus = ?
        AND layanan_harga.hapus = ?
        AND layanan.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan item
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveItem ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi_layanan.kode_transaksi_layanan,
        item.nama_item,
        transaksi_item.jumlah
      FROM transaksi_item
      JOIN transaksi_layanan
        ON transaksi_item.kode_transaksi_layanan = transaksi_layanan.kode_transaksi_layanan
      JOIN item
        ON item.kode_item = transaksi_item.kode_item
      WHERE transaksi_layanan.kode_transaksi = ?
        AND transaksi_item.hapus = ?
        AND transaksi_layanan.hapus = ?
        AND item.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan informasi pembayaran
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveInformasiPembayaran ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        COALESCE(transaksi.jenis_bayar, 0) AS jenis_bayar,
        COALESCE(transaksi.subtotal, 0) AS subtotal,
        COALESCE(transaksi.diskon, 0) AS diskon,
        COALESCE(transaksi.biaya_antar, 0) AS biaya_antar,
        COALESCE(transaksi.pajak, 0) AS pajak,
        COALESCE(transaksi.total, 0) AS total,
        COALESCE(transaksi.bayar, 0) AS bayar,
        COALESCE(transaksi.kembalian, 0) AS kembalian,
        COALESCE(transaksi.status_bayar, 0) AS status_bayar
      FROM transaksi
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  public function retrieveStatusKonfirmasiTransfer ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        jenis_bayar, 
        status_bayar,
        COALESCE(tbt.foto, 0) AS foto,
        COALESCE(tbt.status_transaksi_bayar_transfer, 'tbd') AS status_transaksi_bayar_transfer
      FROM transaksi t
      LEFT JOIN transaksi_bayar_transfer tbt
        ON t.kode_transaksi = tbt.kode_transaksi
      WHERE t.kode_transaksi = ?
        AND t.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * End of - Modular function of Detail Transaksi
   */

  /**
   * Function - Mengecek apakah konsumen pernah melakukan transaksi pada agen tertentu
   *
   * @param String $kode_konsumen   Kode konsumen
   * @param String $kode_agen       Kode agen
   * 
   * @return FALSE / EMPTY / Result array
   */
  public function retrieveTransaksiOnAgen (
    $kode_konsumen,
    $kode_agen
    )
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_transaksi,
        transaksi.total,
        transaksi.tanggal_selesai
      FROM transaksi
      WHERE 
        transaksi.kode_konsumen = ?
        AND transaksi.kode_agen = ?
        AND transaksi.kode_transaksi NOT IN (SELECT tr.kode_transaksi
        FROM transaksi_review tr
        JOIN transaksi t
        ON tr.kode_transaksi = t.kode_transaksi
        WHERE t.kode_konsumen = ?
          AND t.kode_agen = ?
          AND t.hapus = ?)";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
      $kode_agen,
      $kode_konsumen,
      $kode_agen,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);

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
   * Transaksi Onsite dengan dompet
   */
  
  /**
   * Function - Update scan pada kode enkripsi yang dikirimkan
   * 
   * @param  [String] $enkripsi     [Kode enkripsi]
   * 
   * @return [Boolean]              [Is Success]
   */
  public function updateScanQrCode (
    $prefix,
    $enkripsi
    )
  {
    switch ($prefix) {
      case 'jastrik':
        // SQLA preparation
        $sqlA = "UPDATE qr_transaksi
          SET qr_transaksi.scan = ?
          WHERE qr_transaksi.enkripsi = ?";
        break;

      case 'jaswallet':
        // SQLA preparation
        $sqlA = "UPDATE qr_transaksi_kurir
          SET qr_transaksi_kurir.scan = ?
          WHERE qr_transaksi_kurir.enkripsi = ?";
        break;
      
      default:
        # code...
        break;
    }

    // Parameter binding
    $bind_paramA = Array (
      "1",
      $enkripsi
      );

    // Transaction begin
    $this->db->trans_begin();

    // Query execution
    $this->db->query(
      $sqlA,
      $bind_paramA
      );

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
   * Function - melakukan transaksi onsite
   * @param  [type] $kode_transaksi [description]
   * @return [type]                 [description]
   */
  public function createTransaksiOnsite ($kode_transaksi)
  {
    // SQL preparation
    
    // Parameter binding
    // 
    // Query execution
    // Rollback or Commit transaction
  }
  
  /**
   * End of - Transaksi dengan dompet
   */
  
  /**
   * Create Transaksi Online
   */
  
  public function createOnlineTransaction (
    $kode_transaksi,
    $kode_konsumen,
    $kode_agen,
    $jenis_bayar, 
    $subtotal,
    $biaya_antar,
    $saldo_dompet, // Saldo dompet pengguna
    $isAntarJemput,
    $kode_alamat_jemput,
    $catatan_jemput,
    $telp,
    $tanggal_transaksi_jemput,
    $latitude_jemput,
    $longitude_jemput,
    $kode_alamat_antar,
    $longestDur, // Hari terlama layanan
    $bayar = NULL,
    $diskon = 0,
    $pajak = 0
    )
  {
    // Prepare $tanggal_terima
    $tanggal_terima = date("Y-m-d H:i:s");

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
        $status_bayar = "0";
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
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
      NULL, // kembalian
      $status_bayar, // status_bayar
      "0", // jenis_antar
      "1", // jenis_jemput
      "0", // status_transaksi
      NULL,// catatan
      "0",// checked
      "0" // hapus
      );

    // Insert Transaksi Jemput
    $sqlB = "INSERT INTO transaksi_jemput
      (kode_konsumen_alamat,
      kode_transaksi,
      catatan,
      notelp,
      tanggal_transaksi_jemput,
      status_transaksi_jemput,
      latitude,
      longitude,
      hapus) VALUES (?,?,?,?,?,?,?,?,?)";
    $bind_paramB = array (
      $kode_alamat_jemput,
      $kode_transaksi,
      $catatan_jemput,
      $telp,
      $tanggal_transaksi_jemput,
      "0",
      $latitude_jemput,
      $longitude_jemput,
      "0"
      );

    // Insert Transaksi Antar
    $sqlCA = "INSERT INTO transaksi_antar
      (kode_transaksi,
      notelp,
      status_transaksi_antar,
      auto_antar,
      hapus) VALUES (?,?,?,?,?)";
    $bind_paramCA = array ( // Parameted binding dengan auto_jemput
      $kode_transaksi,
      $telp,
      "0",
      "1",
      "0"
      );

    $sqlCB = "INSERT INTO transaksi_antar
      (kode_konsumen_alamat,
      kode_transaksi,
      notelp,
      status_transaksi_antar,
      auto_antar,
      hapus) VALUES (?,?,?,?,?,?)";
    $bind_paramCB = array ( // Parameted binding tanpa auto_jemput
      $kode_alamat_jemput,
      $kode_transaksi,
      $telp,
      "0",
      "0",
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

    // Begin transaction
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );

    if ($isAntarJemput == 1) {
      $this->db->query(
        $sqlB,
        $bind_paramB
        );
      $this->db->query(
        $sqlCA,
        $bind_paramCA
        );
    } elseif ($isAntarJemput == 2) { // Control Flow yang ini ga akan digunakan, karena trans online pasti request jemputan
      $this->db->query(
        $sqlC,
        $bind_paramCB
        );
    } elseif ($isAntarJemput == 3) {
      $this->db->query(
        $sqlB,
        $bind_paramB
        );
      $this->db->query(
        $sqlCB,
        $bind_paramCB
        );
    } 
    
    if ($jenis_bayar == 2) {
      if ($isBalanceSufficient == FALSE) {
        $this->db->trans_rollback();
        // return $this->db->error();
        return FALSE;
      } /*else {
        $this->db->query(
          $sqlD,
          $bind_paramD
          );
        $this->db->query(
          $sqlE,
          $bind_paramE
          );
      }*/
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
    $sqlA = "INSERT INTO transaksi_layanan (kode_harga_layanan,
      kode_transaksi,
      jumlah,
      jumlah_helai,
      panjang,
      lebar,
      harga,
      hapus) VALUES (?,?,?,?,?,?,?,?)";

    // Binding parameter for sqlA
    $bind_paramA = array ($kode_harga_layanan,
      $kode_transaksi,
      $jumlah,
      $jumlah_helai,
      $panjang,
      $lebar,
      $harga,
      "0");

    /*// Query B preparation
    $sqlB = "INSERT INTO transaksi_item
      VALUES (?,?,?,?,?)";

    // Binding parameter for sqlB
    $bind_paramB = array (
      $jumlah,
      $kode_transaksi_layanan,
      $kode_transaksi,
      $kode_item,
      "0");*/

    // Begin the transaction
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  public function deleteOnlineTransaction ($kode_transaksi)
  {
    // Query preparation
    $sql = "DELETE FROM transaksi WHERE kode_transaksi = ?";

    // Binding parameter for sql
    $bind_param = array ($kode_transaksi);

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * End of - Create Transaksi Online
   */
  
  /**
   * Auto cancel - transaksi
   */

  /**
   * Function - Cancelling transaction
   * 
   * @param  String $kode_transaksi       Kode Transaksi
   * @param  String $alasan               The reason of cancelled transaction
   * 
   * @return Boolean
   */
  public function cancelTransaction (
    $kode_transaksi,
    $kode_konsumen,
    $jenis_antar,
    $jenis_jemput,
    $total,
    $jenis_bayar
    )
  {
    // Query for updating transaction status preparation
    $sqlA = "UPDATE transaksi
      SET status_transaksi = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Binding parameter for SQL A
    $bind_paramA = array (
      "20",
      $kode_transaksi,
      "0"
      );

    // Query for inserting data to transaksi_ditolak table Preparation
    $sqlB = "INSERT INTO transaksi_ditolak
      VALUES (?,?,?,?,?,?)";

    // Binding parameter for SQL B
    $bind_paramB = array (
      $kode_transaksi,
      date("Y-m-d H:i:s"),
      strtoupper("agen tidak merespon"),
      "0", // jenis transaksi ditolak
      "ref", // reference
      "0"
      );

    // Query for deleting transaksi_antar preparation
    $sqlC = "UPDATE transaksi_antar
      SET hapus = ?
      WHERE kode_transaksi = ?";

    // Binding parameter for SQL C
    $bind_paramC = array (
      "1",
      $kode_transaksi
      );

    // Query for deleting transaksi_jemput preparation
    $sqlD = "UPDATE transaksi_jemput
      SET hapus = ?
      WHERE kode_transaksi = ?";

    // Binding parameter for SQL D
    $bind_paramD = array (
      "1",
      $kode_transaksi
      );

    // Query to restore saldo_dompet preparation
    $sqlE = "UPDATE konsumen
      SET saldo_dompet = saldo_dompet + ?
      WHERE kode_konsumen = ?
        AND hapus = ?";

    // Binding parameter for SQL E
    $bind_paramE = array (
      $total,
      $kode_konsumen,
      "0"
      );

    // Query for inserting konsumen_history_dompet preparation
    $sqlF = "INSERT INTO konsumen_history_dompet
      VALUES (NULL,?,?,?,?,?,?)";

    // Binding parameter for SQL F
    $bind_paramF = array (
      $kode_konsumen,
      $total,
      0,
      date("Y-m-d H:i:s"),
      $kode_transaksi,
      "0"
      );

    // Query for deleting transaksi_layanan preparation
    $sqlG = "UPDATE transaksi_layanan
      SET hapus = ?
      WHERE kode_transaksi = ?";

    // Binding parameter for SQL g
    $bind_paramG = array (
      "1",
      $kode_transaksi
      );

    // Begin Transaction
    $this->db->trans_begin();    

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
      );
    $this->db->query(
      $sqlG,
      $bind_paramG
      );

    if ($jenis_antar == "0" AND $jenis_jemput == "1") {
      $this->db->query(
        $sqlD,
        $bind_paramD
        );
    } elseif ($jenis_antar == "1" AND $jenis_jemput == "0") {
      $this->db->query(
        $sqlC,
        $bind_paramC
        );
    } elseif ($jenis_antar == "1" AND $jenis_jemput == "1") {
      $this->db->query(
        $sqlD,
        $bind_paramD
        );
      $this->db->query(
        $sqlC,
        $bind_paramC
        );
    }

    if ($jenis_bayar == "2") {
      $this->db->query(
        $sqlE,
        $bind_paramE
        );
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
   * End of - Auto cancel - transaksi
   */

  /**
   * Request antar transaksi
   */
  
  /**
   * Function - Insert data regard of request finished transaction delivery 
   * 
   * @param  String $respon                     Respon (antar / ambil sendiri)
   * @param  String $kode_konsumen_alamat       Kode alamat konsumen
   * @param  String $kode_transaksi             Kode transaksi
   * @param  String $catatan                    catatan pengantaran
   * @param  String $notelp                     nomor telepon
   * @param  String $tanggal_transaksi_antar    tanggal pengantaran
   * @param  String $latitude                   latitude
   * @param  String $longitude                  longitude
   * 
   * @return Boolean
   */
  public function createRespondFinishedTransaction (
    $respon,
    $kode_konsumen_alamat,
    $kode_transaksi,
    $catatan,
    $notelp,
    $tanggal_transaksi_antar,
    $latitude,
    $longitude
    )
  {
    // Query for responding request antar
    $sqlA = "UPDATE transaksi
      SET status_transaksi = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding query A
    $bind_paramA = array (
      "12",
      $kode_transaksi,
      "0"
      );

    // Query for updating jenis_antar
    $sqlB = "UPDATE transaksi
      SET jenis_antar = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding query B
    $bind_paramB = array (
      $respon,
      $kode_transaksi,
      "0"
      );

    // Query for assign kode_kurir to transaksi_antar
    $sqlC = "UPDATE transaksi_antar
      SET kode_konsumen_alamat = ?,
        catatan = ?,
        tanggal_transaksi_antar = ?,
        latitude = ?,
        longitude = ?,
        auto_antar = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";
    $bind_paramC = array (
      $kode_konsumen_alamat,
      $catatan,
      $tanggal_transaksi_antar,
      $latitude,
      $longitude,
      "0",
      $kode_transaksi,
      "0"
      );
    /*$sqlC = "INSERT INTO transaksi_antar
      (kode_konsumen_alamat,
      kode_transaksi,
      catatan,
      notelp,
      tanggal_transaksi_antar,
      status_transaksi_antar,
      latitude,
      longitude,
      hapus)
      VALUES (?,?,?,?,?,?,?,?,?)";

    // Parameter binding query C
    $bind_paramC = array (
      $kode_konsumen_alamat,
      $kode_transaksi,
      $catatan,
      $notelp,
      $tanggal_transaksi_antar,
      "0",
      $latitude,
      $longitude,
      "0"
      );*/

    // Query execution
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
      );
    
    if ($respon == "1") {
      $this->db->query(
        $sqlC,
        $bind_paramC
        );
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
   * End of - Request antar transaksi
   */
  
  /**
   * Function - Retrieve uncomplained invoice 
   * 
   * @param  String $kode_konsumen    Kode konsumen
   * 
   * @return JSON
   */
  public function retrieveUncomplainedTransaksi ($kode_konsumen)
  {
    // Query preparation
    $sql = "SELECT 
        t.kode_transaksi,
        total,
        tanggal_selesai
      FROM transaksi t
      LEFT JOIN transaksi_pengaduan tp
        ON t.kode_transaksi = tp.kode_transaksi
      WHERE tp.kode_transaksi_pengaduan IS NULL
        AND t.kode_konsumen = ?
        AND t.status_transaksi = ?
        AND t.hapus = ?";

    $bind_param = array (
      $kode_konsumen,
      "11",
      "0"
      );

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
   * Function - Delete transaksi antar from cancelled transaction
   * 
   * @param  String $kode_transaksi     kode transaksi
   * 
   * @return Boolean
   */
  /*public function deleteAntar ($kode_transaksi)
  {
    // Query A preparation
    $sql = "UPDATE transaksi_antar
      SET hapus = ?
      WHERE kode_transaksi = ?";

    // Binding parameter for SQL A
    $bind_param = array (
      "1",
      $kode_transaksi
      );

    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } elseif ($query->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }*/

  /**
   * Function - Delete transaksi jemput from cancelled transaction
   * 
   * @param  String $kode_transaksi     kode transaksi
   * 
   * @return Boolean
   */
  /*public function deleteJemput ($kode_transaksi)
  {
    // Query A preparation
    $sql = "UPDATE transaksi_jemput
      SET hapus = ?
      WHERE kode_transaksi = ?";

    // Binding parameter for SQL A
    $bind_param = array (
      "1",
      $kode_transaksi
      );

    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } elseif ($query->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }*/

  /**
   * Function - Canceling transaction payment (wallet)
   * 
   * @param  String $kode_transaksi   Kode transaksi
   * @param  String $kode_konsumen    Kode konsumen
   * @param  String $total            Total biaya transaksi
   * 
   * @return Boolean                  
   */
  /*public function cancelTransactionPayment (
    $kode_transaksi,
    $kode_konsumen,
    $total
    )
  {
    // Query A preparation
    $sqlA = "UPDATE konsumen
      SET saldo_dompet = saldo_dompet + ?
      WHERE kode_konsumen = ?
        AND hapus = ?";

    // Binding parameter for SQL A
    $bind_paramA = array (
      $total,
      $kode_konsumen,
      "0"
      );

    // Query B preparation
    $sqlB = "INSERT INTO konsumen_history_dompet
      VALUES (NULL,?,?,?,?,?,?)";

    // Binding parameter for SQL B
    $bind_paramB = array (
      $kode_konsumen,
      $total,
      0,
      date("Y-m-d H:i:s"),
      $kode_transaksi,
      "0"
      );

    // Transaction begin
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
      );

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }*/

  /**
   * Konfirmasi transaksi dengan transfer
   */
  
  /**
   * Function - Confirming transaction payment with transfer method
   * 
   * @param  String  $kode_transaksi        Kode transaksi
   * @param  Integer $kode_konsumen_bank    Kode bank konsumen
   * @param  Integer $kode_bank_pusat       Kode bank pusat
   * 
   * @return Boolean
   */
  public function createKonfirmasiTransferTransaksi (
    $kode_transaksi,
    $kode_konsumen_bank,
    $kode_bank_pusat
    )
  {
    // Query preparation
    $sql = "INSERT INTO transaksi_bayar_transfer
        (kode_transaksi,
        kode_konsumen_bank,
        kode_bank_pusat,
        tanggal_transaksi_bayar_transfer,
        status_transaksi_bayar_transfer,
        keterangan,
        hapus)
      VALUES (?,?,?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      $kode_konsumen_bank,
      $kode_bank_pusat,
      date("Y-m-d H:i:s"),
      "1",
      "Waiting for Confirmation from Jastrik",
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    } 
  }
  
  /**
   * Function - Menambahkan URL foto pada tabel transaksi_bayar_transfer
   * 
   * @param  String $kode_transaksi Kode transaksi
   * @param  String $foto           URL foto
   * 
   * @return Boolean
   */
  public function uploadFotoTransferTransaksi (
    $kode_transaksi,
    $foto
    )
  {
    // SQL preparing
    $sql = "UPDATE transaksi_bayar_transfer
      SET foto = ?,
        status_transaksi_bayar_transfer = ?
      WHERE kode_transaksi = ?";

    // Parameter binding
    $bind_param = array (
      $foto,
      "2",
      $kode_transaksi
      );

    // Query execution
    $this->db->query(
      $sql, 
      $bind_param
      );

    if ($this->db->affected_rows() != 1) {
     return FALSE;
    } else {
      return TRUE;
    }
  }

}