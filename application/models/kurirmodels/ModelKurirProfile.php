<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKurirProfile extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Update Kurir's Profile
   *
   * @param string $fcm          fcm / UID firebase
   * @param string $fieldname    column name
   * @param string $newvalue     new data
   * 
   * @return FALSE / TRUE
   */
  public function updateKurirProfile (
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
    $this->db->update ('kurir', $values);
    // $this->db->get_compiled_update();

    // Check if query is success
    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

}