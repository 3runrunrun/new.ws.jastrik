<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSystemDiskusi extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Menambah diskusi baru
   *
   * @param String $kode_konsumen
   * @param String $kode_agen
   * @param String $isi_agen_diskusi
   *
   * @return Boolean

   */
  public function createDiskusi (
    $kode_konsumen,
    $kode_agen,
    $isi_agen_diskusi
    )
  {
    // SQL preparation
    $sql = "INSERT INTO agen_diskusi
      (
        agen_diskusi.kode_konsumen,
        agen_diskusi.kode_agen,
        agen_diskusi.isi_agen_diskusi,
        agen_diskusi.tanggal_agen_diskusi,
        agen_diskusi.hapus
      ) VALUES
      (?,?,?,?,?)";

    // Parameter binding
    $bind_param = array(
      $kode_konsumen,
      $kode_agen,
      $isi_agen_diskusi,
      date("Y-m-d H:i:s"),
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
    } elseif ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      $insert_id = $this->db->insert_id();
      return $insert_id;
    }
  }

  /**
   * Function - Mengisi balasan komentar diskusi
   *
   * @param string $kode_agen
   * @param string $kode_konsumen
   * @param string $kode_agen_diskusi
   * @param string $isi_agen_diskusi_komentar
   *
   * @return Boolean

   */
  public function createBalasanDiskusi (
    $kode_agen,
    $kode_konsumen,
    $kode_agen_diskusi,
    $isi_agen_diskusi_komentar
    )
  {
    // SQL preparation
    $sql = "INSERT INTO agen_diskusi_komentar
      (
        kode_agen, 
        kode_konsumen, 
        kode_agen_diskusi, 
        isi_agen_diskusi_komentar, 
        tanggal_agen_diskusi_komentar, 
        hapus
      ) VALUES
      (?,?,?,?,?,?)";

    // Check if kode_agen or kode_konsumen is zero
    if ($kode_agen == "n") {
      $kode_agen = NULL;
    } else {
      $kode_agen = $kode_agen;
    }

    if ($kode_konsumen == "n") {
      $kode_konsumen = NULL;
    } else {
      $kode_konsumen = $kode_konsumen;
    }
 
    // Create tanggal_agen_diskusi_komentar
    $tgl = date("Y-m-d H:i:s");
    
    // Parameter binding
    $bind_param = array (
      $kode_agen,
      $kode_konsumen,
      $kode_agen_diskusi,
      $isi_agen_diskusi_komentar,
      $tgl,
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
    } elseif ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      $insert_id = $this->db->insert_id ();
      return array (
        'kode_agen_diskusi_komentar' => $insert_id,
        'tanggal_agen_diskusi_komentar' => $tgl
        );
    }
  }

  /**
   * Function - Menghapus komentar / balasan dari diskusi
   * 
   * @param  [Integer] $kode_agen_diskusi_komentar    [Kode Diskusi]
   * 
   * @return [Boolean] [Menunjukkan apakah penghapusan berhasil]
   */
  public function deleteBalasanDiskusi ($kode_agen_diskusi_komentar)
  {
    // SQL preparation
    $sql = "UPDATE agen_diskusi_komentar
      SET hapus = ?
      WHERE agen_diskusi_komentar.kode_agen_diskusi_komentar = ?";

    // Parameter binding
    $bind_param = array (
      "1",
      $kode_agen_diskusi_komentar
      );

    // Query execution
    $query = $this->db->query (
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error ();
      return FALSE;
    } else {
      if ($this->db->affected_rows () != 1) {
        return FALSE;
      } else {
        return TRUE;
      }
    }
  }

}