<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKonsumen extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Create new Konsumen
   *
   * @param string $kode_konsumen    kode konsumen yang sudah digenerate
   * @param string $nama             nama konsumen
   * @param string $notelp           nomor telepon konsumen 
   * @param string $jk               jenis kelamin konsumen
   * @param string $fcm              fcm / UID firebase
   * @param string $email            email konsumen
   * @param string $tanggal_lahir    tanggal lahir konsumen
   * 
   * @return TRUE / FALSE
   */
  public function createKonsumen (
    $kode_konsumen,
    $nama,
    $notelp,
    $jk,
    $fcm,
    $email,
    $tanggal_lahir
    )
  {
    $values = array (
      'kode_konsumen' => $kode_konsumen,
      'nama' => $nama,
      'notelp' => $notelp,
      'jk' => $jk,
      'fcm' => $fcm,
      'email' => $email,
      'tanggal_lahir' => $tanggal_lahir,
      'tanggal_daftar' => date('Y-m-d H:i:s')
      );

    $this->db->insert('konsumen', $values);

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }
  
}