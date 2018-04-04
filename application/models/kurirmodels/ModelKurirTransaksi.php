<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKurirTransaksi extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /** 
   * Function - Update transaction status due the acceptance of kurir
   * 
   * @param  String $kode_transaksi Kode transaksi
   * 
   * @return Boolean
   */
  public function createRespondTransaction (
    $kode_transaksi,
    $status_transaksi
    )
  {
    // Query preparation
    $sql = "UPDATE transaksi
      SET status_transaksi = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $status_transaksi,
      $kode_transaksi,
      "0"
      );

    // Query execution
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
   * Function - Memasukkan data qrcode transaksi baru
   * 
   * @param  String $kode_kurir        Kode kurir
   * @param  String $kode_konsumen     Kode konsumen
   * @param  String $enkripsi          Informasi enkripsi
   * 
   * @return Boolean
   */
  public function createQrCodeTransaksi (
    $kode_kurir,
    $kode_konsumen,
    $enkripsi
    )
  {
    // SQL preparation
    $sql = "INSERT INTO qr_transaksi_kurir
      (kode_qr_transaksi_kurir,
      kode_kurir,
      kode_konsumen,
      tanggal_pembuatan,
      enkripsi,
      scan) VALUES
      (?,?,?,?,?,?)";

    // Set tanggal_pembuatan kode
    $kode_qr_transaksi_kurir = date("ymdhis");
    $tanggal_pembuatan = date("Y-m-d H:i:s");
    
    // Parameter binding
    $bind_param = array (
      $kode_qr_transaksi_kurir,
      $kode_kurir,
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
      return $kode_qr_transaksi_kurir;
    }
  }

  ///////////////////////////////
  // checkout pesanan by Kurir //
  ///////////////////////////////
   

  public function deleteLayananTransaksi ($kode_transaksi_layanan)
  {
    // Query preparation
    $sql = "UDPATE transaki_layanan
      SET hapus = ?
      WHERE kode_transaksi_layanan IN ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      "1",
      $kode_transaksi_layanan,
      "0"
      );

    // Transaction begin
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
   * Function - Update biaya transaksi
   * 
   * @param  String $kode_transaksi   Kode transaksi
   * @param  String $kode_konsumen    Kode konsumen
   * @param  Float  $subtotal         subtotal
   * @param  Float  $diskon           diskon
   * @param  Float  $biaya_antar      biaya antar
   * @param  Float  $pajak            pajak
   * @param  Float  $total            total
   * @param  Float  $bayar            bayar
   * @param  Float  $kembalian        kembalian
   * @param  String $jenis_bayar      jenis pembayaran
   * 
   * @return Boolean
   */
  public function updateBiayaTransaksi (
    $kode_transaksi,
    $kode_konsumen,
    $subtotal,
    $diskon,
    $biaya_antar,
    $pajak,
    $total,
    $bayar,
    $kembalian,
    $jenis_bayar
    )
  {
    // Query preparation updating transaksi
    $sql = "UPDATE transaksi
      SET subtotal = ?,
        diskon = ?,
        biaya_antar = ?,
        pajak = ?,
        total = ?,
        bayar = ?,
        kembalian = ?,
        status_bayar = ?,
        status_transaksi = ?,
        catatan = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $subtotal,
      $diskon,
      $biaya_antar,
      $pajak,
      $total,
      $bayar,
      $kembalian,
      "1",
      "3",
      "LUNAS",
      $kode_transaksi,
      "0"
      );

    // Decrease balance
    $sqlB = "UPDATE konsumen 
      SET saldo_dompet = saldo_dompet - ?
      WHERE kode_konsumen = ?
        AND hapus = ?";
    $bind_paramB = array (
      $total,
      $kode_konsumen,
      "0"
      );

    // Insert history dompet
    $sqlC = "INSERT INTO konsumen_history_dompet 
      (
      kode_konsumen,
      nominal_masuk,
      nominal_keluar,
      tanggal,
      ref_konsumen_history_dompet,
      hapus
      )
      VALUES (?,?,?,?,?,?)";
    $bind_paramC = array (
      $kode_konsumen,
      0,
      $total,
      date("Y-m-d H:i:s"),
      $kode_transaksi,
      "0"
      );

    // Transaction begin
    $this->db->trans_begin();

    $this->db->query(
      $sql,
      $bind_param
      );

    if ($jenis_bayar == "2") {
      $this->db->query(
        $sqlB,
        $bind_paramB
        );
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
   * Function - Renewing (Deleting) layanan ordered by user
   * 
   * @param  String $kode_transaksi_layanan     Kode transaksi layanan
   * 
   * @return Boolean
   */
  public function updateLayananTransaksi (
    $kode_transaksi_layanan,
    $jumlah,
    $jumlah_helai,
    $panjang,
    $lebar,
    $harga
    )
  {
    // Query preparation updating transaksi_layanan
    $sql = "UPDATE transaksi_layanan
      SET jumlah = ?,
        jumlah_helai = ?,
        panjang = ?,
        lebar = ?,
        harga = ?
      WHERE kode_transaksi_layanan = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $jumlah,
      $jumlah_helai,
      $panjang,
      $lebar,
      $harga,
      $kode_transaksi_layanan,
      "0"
      );

    // Transaction begin
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
   * Function - Insert item for each layanan
   * 
   * @param  String $kode_transaksi_layanan     Kode transaksi layanan
   * @param  String $kode_transaksi             Kode transaksi
   * @param  String $kode_item                  Kode item
   * @param  Float  $jumlah                     Jumlah item
   * 
   * @return Boolean
   */
  public function createItemLayanan (
    $kode_transaksi_layanan,
    $kode_transaksi,
    $kode_item,
    $jumlah
    )
  {
    // Query preparation
    $sql = "INSERT INTO transaksi_item (
      kode_transaksi_layanan,
      kode_transaksi,
      kode_item,
      jumlah,
      hapus) VALUES (?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi_layanan,
      $kode_transaksi,
      $kode_item,
      $jumlah,
      "0"
      );

    // Transaction begin
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

  ////////////////////////////////////////
  // end of - checkout pesanan by Kurir //
  ////////////////////////////////////////

  
}