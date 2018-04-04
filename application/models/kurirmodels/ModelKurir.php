<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKurir extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  public function createKurir (
    $kode_kurir,
    $kode_pshcabang,
    $nama,
    $notelp,
    $jk,
    $email,
    $fcm,
    $tanggal_lahir,
    $file_ktp,
    $file_kk,
    $kodepos,
    $jenis_kurir)
  {
    $values = array (
      'kode_kurir' => $kode_kurir,
      'kode_pshcabang' => $kode_pshcabang,
      'nama' => $nama,
      'notelp' => $notelp,
      'jk' => $jk,
      'email' => $email,
      'fcm' => $fcm,
      'tanggal_lahir' => $tanggal_lahir,
      'tanggal_daftar' =>  date('Y-m-d H:i:s'),
      'file_ktp' => $file_ktp,
      'file_kk' => $file_kk,
      'kodepos' => $kodepos,
      'jenis_kurir' => $jenis_kurir
      );

    $this->db->insert('kurir', $values);

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Retrieve Kurir's profile
   *
   * @param string $email     email kurir
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return FALSE / Array of query result
   */
  public function retrieveKurirProfile ($email,
    $fcm)
  {
    $predicate = array(
      'email' => $email,
      'fcm' => $fcm
      );

    $this->db->where($predicate);
    $query = $this->db->get('kurir');

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }  
  }

}