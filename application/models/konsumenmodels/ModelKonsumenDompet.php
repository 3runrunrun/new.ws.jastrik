<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKonsumenDompet extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Transaksi pembelian saldo dompet
   */

  /**
   * Function - Request pembelian saldo dompet
   * 
   * @param  String     $kode_bld             Kode beli dompet
   * @param  String     $kode_konsumen        Kode konsumen
   * @param  Smallint   $kode_paket_dompet    Kode paket dompet
   * @param  Double     $nominal              Nominal
   * @param  Double     $harga                Harga
   * 
   * @return Boolean
   */
  public function createPembelianDompet (
    $kode_bld,
    $kode_konsumen,
    $kode_paket_dompet,
    $nominal,
    $harga,
    $harga_transfer
    )
  {
    // SQL preparing
    $sql = "INSERT INTO konsumen_beli_dompet
     (
       kode_konsumen_beli_dompet,
       kode_konsumen,
       kode_paket_dompet,
       nominal,
       harga,
       harga_transfer,
       tanggal,
       status_konsumen_beli_dompet
     )
     VALUES (?,?,?,?,?,?,?,?)";

    // Parameter Binding for SQL A
    $bind_param = array (
      $kode_bld,
      $kode_konsumen,
      $kode_paket_dompet,
      $nominal,
      $harga,
      $harga_transfer,
      date("Y-m-d H:i:s"),
      "0" // Belum lunas
      );

    // Query execution
    $this->db->query(
      $sql,
      $bind_param
      );

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return $harga_transfer;
    }
  }

  /**
   * Function - Request konfirmasi pembayaran saldo dompet
   * 
   * @param  String     $kode_bbd               Kode bayar beli dompet
   * @param  String     $kode_konsumen_bank     Kode bank konsumen
   * @param  String     $kode_bank_pusat        Kode bank pusat (Tujuan transfer)
   * @param  String     $tanggal_transfer       Tanggal transfer
   * 
   * @return Boolean
   */
  public function createKonfirmasiPembayaranDompet (
    $kode_bbd,
    $kode_bld,
    $kode_konsumen_bank,
    $kode_bank_pusat,
    $tanggal_transfer
    )
  {
    // SQLA preparing 
    $sqlA = "INSERT INTO konsumen_bayar_beli_dompet
      VALUES (?,?,?,?,?,?,?,?,?)";

    // Parameter Binding for SQL A
    $bind_paramA = array (
      $kode_bbd,
      $kode_bld,
      $kode_konsumen_bank,
      $kode_bank_pusat,
      date("Y-m-d H:i:s"),
      NULL,
      strtoupper("Waiting for Confirmation from Jastrik"),
      "0",
      $tanggal_transfer . " " . date("H:i:s")
      );

    // SQLB preparing
    $sqlB = "UPDATE konsumen_beli_dompet
      SET status_konsumen_beli_dompet = ?
      WHERE kode_konsumen_beli_dompet = ?
      AND hapus = ?";

    // Parameter Binding for SQL B
    $bind_paramB = array (
      "1",
      $kode_bld,
      "0"
      );

    // Begin transaction
    $this->db->trans_begin();

    // Query execution
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
  }

  /**
   * Function - Menambahkan URL foto pada tabel konsumen_bayar_beli_dompet
   *
   * @param string $kode_bld      Kode dari tabel konsumen_beli_dompet
   * @param string $foto          URL foto
   *
   * @return FALSE / TRUE
   */
  public function uploadFotoPembayaranDompet (
    $kode_bld,
    $foto
    )
  {
    // SQL preparing
    $sql = "UPDATE konsumen_bayar_beli_dompet
      SET foto = ?
      WHERE kode_konsumen_beli_dompet = ?";

    // Parameter binding
    $bind_param = array (
      $foto,
      $kode_bld
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

  /**
   * End of - Transaksi pembelian saldo dompet
   */

  /**
   * Function - Menampilkan status pembelian dompet
   *
   * @param string $kode_bld      Kode dari tabel konsumen_beli_dompet
   *
   * @return FALSE / TRUE
   */
  public function retrieveStatusBeliDompet ($kode_bld)
  {
    // SQL preparing
    $sql = "SELECT status_konsumen_beli_dompet
      FROM konsumen_beli_dompet
      WHERE 
        status_konsumen_beli_dompet <> ? AND
        kode_konsumen_beli_dompet = ?";

    // Parameter binding
    $bind_param = array (
      "3",
      $kode_bld
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
   * Function - Menampilkan daftar pembelian saldo dompet
   *
   * @param string $kode_konsumen      Kode konsumen
   *
   * @return FALSE / "EMPTY" / result array
   */
  public function retrievePembelianDompet ($kode_konsumen)
  {
    // SQL preparation
    $sql = "SELECT 
      kode_konsumen_beli_dompet,
      nominal,
      tanggal AS tanggal_beli,
      status_konsumen_beli_dompet
      FROM konsumen_beli_dompet
      WHERE kode_konsumen = ?
        AND hapus = ?
        AND status_konsumen_beli_dompet = ?
        OR status_konsumen_beli_dompet = ?";

    // Parameter binding
    $bind_param = array(
      $kode_konsumen,
      "0",
      "2",
      "3"
      ); 

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } else if ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan daftar pemakaiaan saldo dompet
   *
   * @param string $kode_konsumen      Kode konsumen
   *
   * @return FALSE / "EMPTY" / result array
   */
  public function retrievePemakaianDompet ($kode_konsumen)
  {
    // SQL Preparing
    $sql = "SELECT 
      ref_konsumen_history_dompet AS kode_transaksi,
      nominal_keluar,
      tanggal
      FROM konsumen_history_dompet
      WHERE 
        kode_konsumen = ? AND
        nominal_masuk = ?";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
      0
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan saldo dompet konsumen
   *
   * @param string $kode_konsumen      Kode konsumen
   *
   * @return FALSE / TRUE
   */
  public function retrieveSaldoDompet ($kode_konsumen)
  {
    // SQL preparing
    $sql = "SELECT saldo_dompet
      FROM konsumen
      WHERE kode_konsumen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
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
  
}