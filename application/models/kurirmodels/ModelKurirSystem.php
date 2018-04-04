<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKurirSystem extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Check if qr_transaksi_kurir has been scanned
   * 
   * @param  String $kode_qr_transaksi_kurir      qr_transaksi_kurir
   * 
   * @return Boolean/String/Array
   */
  public function checkIfScanned ($kode_qr_transaksi_kurir)
  {
    // Query preparation
    $sql = "SELECT scan
      FROM qr_transaksi_kurir
      WHERE kode_qr_transaksi_kurir = ?";

    // Parameter binding
    $bind_param = array ($kode_qr_transaksi_kurir);

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
  
}