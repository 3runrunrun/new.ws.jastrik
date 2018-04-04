<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAgenProfile extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Update Agen's Profile
   *
   * @param string $fcm          fcm / UID firebase
   * @param string $fieldname    column name
   * @param string $newvalue     new data
   * 
   * @return FALSE / TRUE
   */
  public function updateAgenProfile (
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
    $this->db->update ('agen', $values);
    // $this->db->get_compiled_update();

    // Check if query is success
    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Change address
   * 
   * @param  String $kode_agen        Kode agen
   * @param  String $new_address      Alamat baru
   * @param  String $new_latitude     Latitude baru
   * @param  String $new_longitude    Longitude baru
   * 
   * @return Boolean
   */
  public function updateAddress (
    $kode_agen,
    $new_address,
    $new_latitude,
    $new_longitude
    )
  {
    // Query preparation
    $sql = "UPDATE agen_alamat
      SET alamat = ?,
        latitude = ?,
        longitude = ?
      WHERE kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $new_address,
      $new_latitude,
      $new_longitude,
      $kode_agen,
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
}