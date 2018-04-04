<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAfiliasiSystem extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Generating code for afiliasi controller
   *
   * @param string $transactiontype    Tipe transaksi
   * 
   * @return FALSE / result array
   */
  public function codeGenerator ($transactiontype)
  {
    $uniquenumber = date("ymdHis");
    $microtime = substr(microtime(),2,6);

    switch ($transactiontype) {
      // Request Pencairan Fee
      case 'rpf':
        $generatedCode = "RPF/" . $uniquenumber . $microtime;
        break;

      // New agen registration
      case 'rega':
        $generatedCode = "REGA/" . $uniquenumber . $microtime;
        break;

      default:
        # code...
        break;
    }

    return strtoupper($generatedCode);
  }

  /**
   * Function - Retrive Afiliasi's Bank Code
   * 
   * @param  String $kode_afiliasi    Kode_afiliasi
   * 
   * @return Boolean/String/Array
   */
  public function retrieveBankCodeAfiliasi ($kode_afiliasi)
  {
    // Query preparation
    $sql = "SELECT kode_afiliasi_bank
      FROM afiliasi_bank
      WHERE afiliasi_kode_afiliasi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_afiliasi,
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

}