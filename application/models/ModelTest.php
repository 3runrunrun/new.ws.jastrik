<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelTest extends CI_Model {

  public function __construct()
  {
    parent::__construct();
  }

  public function selectDual()
  {
    $query = $this->db->get('agen');

    if ($query->num_rows() < 1) {
      return false;
    } else {
      return $query->num_rows();
    }
  }

  //  Dari tabel pendaftaran_agen
  public function retrieveKodeAgen ()
  {
    $sql = "SELECT kode_agen FROM pendaftaran_agen";
    $query = $this->db->query($sql);
    return $query->result_array();
  }


}