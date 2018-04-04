<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAgen extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Create new Agen
   *
   * @param $kode_agen        Kode agen
   * @param $nama             Nama agen
   * @param $notelp           No. telepon agen
   * @param $email            Email agen
   * @param $kode_kota        Kode kota lokasi agen
   * @param $kode_kecamatan   Kode kecamatan
   * @param $kode_kelurahan   Kode kelurahan
   * @param $alamat           Alamat agen
   * @param $kodepos          Kodepos
   * 
   * @return Boolean
   */
  public function createAgen (
    $kode_agen,
    $nama,
    $notelp,
    $email,
    $kode_kota,
    $kode_kecamatan,
    $kode_kelurahan,
    $alamat,
    $kodepos
    )
  {
    // SQL A preparing
    $sqlA = "INSERT INTO agen 
      (
        kode_agen,
        nama,
        notelp,
        email,
        tanggal_daftar,
        status_agen
      ) VALUES (?,?,?,?,?,?)";

    // Parameter binding for SQL A
    $bind_paramA = array (
      $kode_agen,
      $nama,
      $notelp,
      $email,
      date("Y-m-d H:i:s"),
      "0"
      );

    // SQL B preparing
    $sqlB = "INSERT INTO agen_alamat
      (
        kode_agen,
        kode_kota,
        kode_kecamatan,
        kode_kelurahan,
        alamat,
        kodepos
      ) VALUES (?,?,?,?,?,?)";

    // Parameter binding for SQL B
    $bind_paramB = array (
      $kode_agen,
      $kode_kota,
      $kode_kecamatan,
      $kode_kelurahan,
      $alamat,
      $kodepos
      );

    // Begin the query execution
    $this->db->trans_begin();
    $this->db->query($sqlA, $bind_paramA);
    $this->db->query($sqlB, $bind_paramB);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Function - Retrieve Agen's profile
   *
   * @param string $email     email agen
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return FALSE / Array of query result
   */
  public function retrieveAgenProfile (
    $email,
    $fcm
    )
  {
    // Query preparation
    $sql = "SELECT * 
      FROM agen
      JOIN agen_alamat
        ON agen.kode_agen = agen_alamat.kode_agen
      WHERE agen.email = ?
        AND agen.fcm = ?
        AND agen.hapus = ?";

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
   * Function - Assign kurir to transaksi_antar
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * @param  String $kode_kurir         Kode kurir
   * 
   * @return Boolean
   */
  public function updateKurirToTransaksiAntar (
    $kode_transaksi,
    $kode_kurir
    )
  {
    // Query A preparation
    $sqlA = "UPDATE transaksi_antar
      SET kode_kurir = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";
    $bind_paramA = array (
      $kode_kurir,
      $kode_transaksi,
      "0"
      );

    // Query B preparation
    $sqlB = "UPDATE transaksi
      SET status_transaksi = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";
    $bind_paramB = array (
      "8",
      $kode_transaksi,
      "0"
      );    

    // Beginning transaction
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
      );

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Function - Create new qr_code for absen
   * 
   * @param  String $kode_agen        Kode agen
   * @param  String $kode_checker     Kode checker
   * @param  String $enkripsi         enkripsi
   * 
   * @return Boolean/String           FALSE/kode_agen_temp_kode_absen
   */
  public function createQRAbsen (
    $kode_agen,
    $kode_checker,
    $enkripsi
    )
  {
    // Prepare kode_agen_temp_kode_absen, time_generate, & time_expired
    $kode_agen_temp_kode_absen = date("ymdhis");
    $time_generate = date("Y-m-d H:i:s");
    $texp = strtotime("+1 minutes", strtotime($time_generate));
    $time_expired = date("Y-m-d H:i:s", $texp);

    // Query preparation
    $sql = "INSERT INTO agen_temp_kode_absen
      (kode_agen_temp_kode_absen,
      kode_agen,
      kode_checker,
      time_generate,
      time_expired,
      enkripsi,
      scan,
      hapus) VALUES (?,?,?,?,?,?,?,?)";
    
    // Parameter binding
    $bind_param = array (
      $kode_agen_temp_kode_absen,
      $kode_agen,
      $kode_checker,
      $time_generate,
      $time_expired,
      $enkripsi,
      "0",
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return $kode_agen_temp_kode_absen;
    }
  }

}