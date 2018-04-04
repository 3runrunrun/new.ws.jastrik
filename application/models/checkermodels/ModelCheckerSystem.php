<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelCheckerSystem extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Generating code for checker controller
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
      // Request Setoran Dana Agen
      case 'rsda':
        $generatedCode = "RSDA/" . $uniquenumber . $microtime;
        break;

      // Order Inventory
      case 'koi':
        $generatedCode = "KOI/" . $uniquenumber . $microtime;
        break;

      // Update Inventories Stock
      case 'ahi':
        $generatedCode = "AHI/" . $uniquenumber . $microtime;
        break;

      // Absen agen
      case 'vis':
        $generatedCode = "VIS/" . $uniquenumber . $microtime;
        break;

      default:
        # code...
        break;
    }

    return strtoupper($generatedCode);
  }

  /**
   * Function - retrieve status penarikan dana agen
   * 
   * @param  String $kode_checker     kode checker
   * @param  String $kode_agen        kode agen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveStatusDepositWithdrawal (
    $kode_checker,
    $kode_agen
    )
  {
    // Query preparation
    $sql = "SELECT status_agen_setoran_dana
      FROM agen_setoran_dana
      WHERE kode_checker = ?
        AND kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_checker,
      $kode_agen,
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
   * Function - check if agen visited today
   * 
   * @param  String $kode_agen      Kode agen
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function checkIfAgenIsVisited ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT kode_agen
      FROM agen_absen
      WHERE kode_agen = ?
        AND tanggal_agen_absen LIKE ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      date("Y-m-d") . "%",
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
   * Function - Retrieve invetories price
   *  
   * @param  Integer $kode_inventory_harga   Kode harga inventory
   * 
   * @return Boolean/String/Array            FALSE/"EMPTY"/Result
   */
  public function retrieveInventoryPrice ($kode_inventory_harga)
  {
    // Query preparation
    $sql = "SELECT
        COALESCE(harga_inventory, 0) AS harga_inventory
      FROM inventory_harga
      WHERE kode_inventory_harga = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_inventory_harga,
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