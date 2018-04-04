<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAfiliasi extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Create new Afiliasi
   *
   * @param string $kode_afiliasi    kode Afiliasi yang sudah digenerate
   * @param string $kode_pshcabang   kode perusahaan cabang
   * @param string $nama             nama Afiliasi
   * @param string $alamat           alamat Afiliasi
   * @param string $notelp           nomor telepon Afiliasi
   * @param string $tanggal_lahir    tanggal lahir afiliasi
   * @param string $email            email Afiliasi
   * @param string $fcm              fcm / UID Firebase
   * @param string $tanggal_daftar   tanggal pendaftaran
   * @param string $file_ktp         URL file gambar KTP
   * @param string $file_kk          URL file gambar KK
   * @param string $kodepos          kode pos afiliasi
   * @param string $foto             URL foto profil Afiliasi
   * 
   * @return TRUE / FALSE
   */
  public function createAfiliasi (
    $kode_afiliasi,
    $kode_pshcabang,
    $nama,
    $alamat,
    $notelp,
    $tanggal_lahir,
    $email,
    $fcm,
    $file_ktp,
    $file_kk,
    $kodepos,
    $foto)
  {
    // Declare error flag
    $errorFlag = FALSE;

    // Prepare row values
    $values = array (
      'kode_afiliasi' => $kode_afiliasi,
      'kode_pshcabang' => $kode_pshcabang,
      'nama' => $nama,
      'alamat' => $alamat,
      'notelp' => $notelp,
      'tanggal_lahir' => $tanggal_lahir,
      'email' => $email,
      'fcm' => $fcm,
      'tanggal_daftar' => date('Y-m-d H:i:s'),
      'file_ktp' => $file_ktp,
      'file_kk' => $file_kk,
      'kodepos' => $kodepos,
      'foto' => $foto
      );

    // Insert values into table Afiliasi
    $this->db->insert('afiliasi', $values);

    if ($this->db->affected_rows() != 1) {
      $errorFlag = FALSE;
    } else {
      $errorFlag = TRUE;
    }

    return $errorFlag;
  }

  /**
   * Function - Retrieve Afiliasi
   *
   * @param string $email            email Afiliasi
   * @param string $fcm              fcm / UID Firebase
   * 
   * @return FALSE / query result as array
   */
  public function retrieveAfiliasiProfile (
    $email,
    $fcm)
  {
    // Declare predicate value
    $predicate = array (
      'email' => $email,
      'fcm' => $fcm
      );

    // Select data from Afiliasi table
    $this->db->where($predicate);
    $query = $this->db->get('afiliasi');

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Requesting pencairan fee
   * 
   * @param  String $kode_afiliasi_pencairan_saldo    Kode request pencairan
   * @param  String $kode_afiliasi                    Kode afiliasi
   * @param  String $kode_afiliasi_bank               Kode bank afiliasi
   * @param  String $nominal                          Nominal
   * 
   * @return Boolean/String/Array                     FALSE/"EMPTY"/Result
   */
  public function createRequestPencairanFee (
    $kode_afiliasi_pencairan_saldo,
    $kode_afiliasi,
    $kode_afiliasi_bank,
    $nominal
    )
  {
    // Query preparation
    $sql = "INSERT INTO afiliasi_pencairan_saldo
      (kode_afiliasi_pencairan_saldo,
      kode_afiliasi,
      kode_afiliasi_bank,
      tanggal_request,
      nominal,
      status_afiliasi_pencairan_saldo,
      hapus) VALUES (?,?,?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_afiliasi_pencairan_saldo,
      $kode_afiliasi,
      $kode_afiliasi_bank,
      date("Y-m-d H:i:s"),
      $nominal,
      "0",
      "0"
      );

    // Query execution
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

  /**
   * Function - Pendaftaran agen melalui afiliasi
   * 
   * @param  String $kode_agen                  Kode agen (System generated)
   * @param  String $kode_afiliasi              Kode afiliasi
   * @param  String $nama                       Nama
   * @param  String $notelp                     Nomor telepon
   * @param  String $kode_kota                  Kode kota
   * @param  String $kode_kecamatan             Kode kecamatan
   * @param  String $kode_kelurahan             Kode kelurahan
   * @param  String $alamat                     Kode alamat
   * @param  String $kode_pos                   Kodepos (System generated)
   * @param  String $longitude                  Longitude
   * @param  String $latitude                   Latitude
   * @param  String $email                      Email (opsional)
   * @param  String $jumlah_bayar_registrasi    Pembayaran registrasi (opsional)
   * 
   * @return Boolean
   */
  public function signUpAgen (
    $kode_pendaftaran_agen,
    $kode_agen,
    $kode_afiliasi,
    $nama,
    $notelp,
    $kode_kota,
    $kode_kecamatan,
    $kode_kelurahan,
    $alamat,
    $kodepos,
    $bayar,
    $email = NULL,
    $jumlah_bayar_registrasi = NULL
    )
  {
    // Query A preparation
    $sqlA = "INSERT INTO agen 
      (kode_agen,
      kode_afiliasi,
      nama,
      notelp,
      email,
      tanggal_daftar,
      hapus) VALUES (?,?,?,?,?,?,?)";
    $bind_paramA = array (
      $kode_agen,
      $kode_afiliasi,
      $nama,
      $notelp,
      $email,
      date("Y-m-d H:i:s"),
      "0"
      );

    // Query B preparation
    $sqlB = "INSERT INTO agen_alamat
      (kode_agen,
      kode_kota,
      kode_kecamatan,
      kode_kelurahan,
      alamat,
      kodepos,
      hapus) VALUES (?,?,?,?,?,?,?)";
    $bind_paramB = array (
      $kode_agen,
      $kode_kota,
      $kode_kecamatan,
      $kode_kelurahan,
      $alamat,
      $kodepos,
      "0"
      );

    // Query C preparation 
    $sqlC = "INSERT INTO pendaftaran_agen
      (kode_pendaftaran_agen,
      kode_agen,
      kode_afiliasi,
      total_tagihan,
      tanggal_daftar,
      bayar,
      hapus) VALUES (?,?,?,?,?,?,?)";
    $bind_paramC = array(
      $kode_pendaftaran_agen,
      $kode_agen,
      $kode_afiliasi,
      100000,
      date("Y-m-d H:i:s"),
      $bayar,
      "0"
      );

    // Transaction begining
    $this->db->trans_begin();
    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
      );
    $this->db->query(
      $sqlC,
      $bind_paramC
      );

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return $kode_agen;
    }
  }

}