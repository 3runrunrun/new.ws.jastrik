<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelCheckerProfile extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Retrieve Checker's profile
   *
   * @param string $email     email agen
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return FALSE / Array of query result
   */
  public function retrieveCheckerProfile (
    $email,
    $fcm
    )
  {
    // Query preparation
    $sql = "SELECT * 
      FROM checker
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
   * Function - Update Checker's Profile
   *
   * @param string $fcm          fcm / UID firebase
   * @param string $fieldname    column name
   * @param string $newvalue     new data
   * 
   * @return FALSE / TRUE
   */
  public function updateCheckerProfile (
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
    $this->db->update ('checker', $values);
    // $this->db->get_compiled_update();

    // Check if query is success
    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

}