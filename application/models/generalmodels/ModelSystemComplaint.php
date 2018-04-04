<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSystemComplaint extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Create new pengaduan of a transaksi
   * 
   * @param  String $kode_transaksi               Kode transaksi
   * @param  String $isi_transaksi_pengaduan      Isi pengaduan
   * 
   * @return Boolean
   */
  public function createComplaint (
    $kode_transaksi,
    $isi_transaksi_pengaduan
    )
  {
    // Query preparation
    $sql = "INSERT INTO transaksi_pengaduan (
      kode_transaksi,
      isi_transaksi_pengaduan,
      tanggal_transaksi_pengaduan,
      hapus) VALUES (?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      $isi_transaksi_pengaduan,
      date("Y-m-d H:i:s"),
      "0"
      );

    // Begin the transaction
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
   * Function - Reply complain
   * 
   * @param  String $kode_agen                          Kode agen
   * @param  String $kode_konsumen                      Kode konsumen
   * @param  String $kode_checker                       Kode checker
   * @param  String $kode_transaksi_pengaduan           Kode pengaduan
   * @param  String $isi_transaksi_pengaduan_balas      Kode transaksi pengaduan balas
   * 
   * @return Boolean/Array
   */
  public function createBalasanComplain (
    $kode_agen, 
    $kode_konsumen, 
    $kode_checker,
    $kode_transaksi_pengaduan, 
    $isi_transaksi_pengaduan_balas
    )
  {
    // SQL preparation
    $sql = "INSERT INTO transaksi_pengaduan_balas
      (
        kode_agen, 
        kode_konsumen, 
        kode_checker,
        kode_transaksi_pengaduan, 
        isi_transaksi_pengaduan_balas, 
        waktu_transaksi_pengaduan_balas, 
        hapus
      ) VALUES
      (?,?,?,?,?,?,?)";

    // Check if kode_agen or kode_konsumen is zero
    if ($kode_agen == "n") {
      $kode_agen = NULL;
    } 

    if ($kode_konsumen == "n") {
      $kode_konsumen = NULL;
    } 

    if ($kode_checker == "n") {
      $kode_checker = NULL;
    } 
 
    // Create tanggal_agen_diskusi_komentar
    $tgl = date("Y-m-d H:i:s");
    
    // Parameter binding
    $bind_param = array (
      $kode_agen, 
      $kode_konsumen, 
      $kode_checker,
      $kode_transaksi_pengaduan, 
      $isi_transaksi_pengaduan_balas,
      $tgl,
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
    } elseif ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      $insert_id = $this->db->insert_id ();
      return array (
        'kode_transaksi_pengaduan_balas' => $insert_id,
        'waktu_transaksi_pengaduan_balas' => $tgl
        );
    }
  }

  /** 
   * Function - Delete complain's comment
   * 
   * @param  Integer $kode_transaksi_pengaduan_balas   Kode balasan pengaduan
   * 
   * @return Boolean
   */
  public function deleteComplainComment ($kode_transaksi_pengaduan_balas)
  {
    // Query preparation
    $sql = "UPDATE transaksi_pengaduan_balas
      SET hapus = ?
      WHERE kode_transaksi_pengaduan_balas = ?";

    // Parameter binding
    $bind_param = array (
      "1",
      $kode_transaksi_pengaduan_balas
      );

    // Begin query execution
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
   * Function - Set complain as solved
   * 
   * @param  String $kode_transaksi_pengaduan   Kode pengaduan
   * 
   * @return Boolean
   */
  public function updateStatusComplain ($kode_transaksi_pengaduan)
  {
    // Query preparation
    $sql = "UPDATE transaksi_pengaduan
      SET status_transaksi_pengaduan = ?
      WHERE kode_transaksi_pengaduan = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      "1",
      $kode_transaksi_pengaduan,
      "0"
      );

    // Begin query execution
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
  
}