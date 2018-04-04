<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSystemUserManagement extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Add new token for a user
   *
   * @param string $usertype    tipe user (konsumen, agen, etc.)
   * @param string $kode_user   Kode user (berdasarkan usertype)
   * @param string $token       Nilai token
   * 
   * @return Boolean
   */
  public function updateToken (
    $usertype,
    $kode_user,
    $token
    )
  {
    switch ($usertype) {
      case 'konsumen':
        // SQL preparation
        $sql = "UPDATE konsumen
          SET token = ?
          WHERE kode_konsumen = ?
            AND hapus = ?";

        // Parameter binding
        $bind_param = array (
          $token,
          $kode_user,
          "0"
          );
        break;

      case 'agen':
        // SQL preparation
        $sql = "UPDATE agen
          SET token = ?
          WHERE kode_agen = ?
            AND hapus = ?";

        // Parameter binding
        $bind_param = array (
          $token,
          $kode_user,
          "0"
          );
        break;

      case 'kurir':
        // SQL preparation
        $sql = "UPDATE kurir
          SET token = ?
          WHERE kode_kurir = ?
            AND hapus = ?";

        // Parameter binding
        $bind_param = array (
          $token,
          $kode_user,
          "0"
          );
        break;

      case 'afiliasi':
        // SQL preparation
        $sql = "UPDATE afiliasi
          SET token = ?
          WHERE kode_afiliasi = ?
            AND hapus = ?";

        // Parameter binding
        $bind_param = array (
          $token,
          $kode_user,
          "0"
          );
        break;

      case 'checker':
        // SQL preparation
        $sql = "UPDATE checker
          SET token = ?
          WHERE kode_checker = ?
            AND hapus = ?";

        // Parameter binding
        $bind_param = array (
          $token,
          $kode_user,
          "0"
          );
        break;
      
      default:
        # code...
        break;
    }

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

}