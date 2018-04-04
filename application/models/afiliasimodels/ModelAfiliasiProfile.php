<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAfiliasiProfile extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Retrieve Afiliasi's profile
   *
   * @param string $email     email agen
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return Boolean/Array
   */
  public function retrieveAfiliasiProfile (
    $email,
    $fcm
    )
  {
    // Query preparation
    $sql = "SELECT * 
      FROM afiliasi
      WHERE email = ?
        AND fcm = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $email,
      $fcm,
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
   * Function - Retrieve afiliasi rekening
   * 
   * @param  String $kode_afiliasi    Kode afiliasi
   * 
   * @return Booelan/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveAfiliasiRekening ($kode_afiliasi)
  {
    // Query preparation
    $sql = "SELECT 
        ab.kode_afiliasi_bank,
        jb.nama_jenis_bank,
        jb.logo_jenis_bank,
        ab.norek_afiliasi_bank,
        ab.atas_nama_afiliasi_bank
      FROM afiliasi_bank ab
      JOIN jenis_bank jb
        ON ab.kode_jenis_bank = jb.kode_jenis_bank
      WHERE ab.afiliasi_kode_afiliasi = ?
        AND ab.hapus = ?";

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

  /**
   * Function - Update Afiliasi's Profile
   *
   * @param string $fcm          fcm / UID firebase
   * @param string $fieldname    column name
   * @param string $newvalue     new data
   * 
   * @return FALSE / TRUE
   */
  public function updateAfiliasiProfile (
    $fcm,
    $fieldname,
    $newvalue
    )
  {
    // Prepare values
    $values = array (
      $fieldname => $newvalue
      );

    // Where condition and do update
    $this->db->where ('fcm', $fcm);
    $this->db->update ('afiliasi', $values);
    // $this->db->get_compiled_update();

    // Check if query is success
    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

}