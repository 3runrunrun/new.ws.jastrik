<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSystemReview extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Membuat review
   *
   * @param String $kode_transaksi
   * @param String $rating_rapi
   * @param String $rating_cepat
   * @param String $isi_transaksi_review
   *
   * @return Boolean
   */
  public function createReview (
    $kode_transaksi, 
    $rating_rapi, 
    $rating_cepat, 
    $isi_transaksi_review
    )
  {
    // SQl preparation
    $sql = "INSERT INTO transaksi_review
      (
        transaksi_review.kode_transaksi, 
        transaksi_review.rating_rapi, 
        transaksi_review.rating_cepat, 
        transaksi_review.isi_transaksi_review, 
        transaksi_review.tanggal_transaksi_review,
        transaksi_review.hapus
      ) VALUES
      (?,?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi, 
      $rating_rapi, 
      $rating_cepat, 
      $isi_transaksi_review,
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
   * Function - Menampilkan rating_rapi dan rating_cepat agen
   *
   * @param String $kode_agen     Kode agen
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveRating ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT
        COALESCE(agen.rating_rapi, 0) AS rating_rapi,
        COALESCE(agen.rating_cepat, 0) AS rating_cepat
      FROM agen
      WHERE agen.kode_agen = ?";

    // Parameter binding
    $bind_param = array ($kode_agen);

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } else {
      return $query->result_array();
    } 
  }

  /**
   * Function - Update rating pada tabel agen
   *
   * @param String $kode_agen     Kode agen
   *
   * @return Boolean
   */
  public function updateRating (
    $kode_agen,
    $rating_rapi,
    $rating_cepat
    )
  {
    // SQL preaparation
    $sql = "UPDATE agen
      SET 
        agen.rating_rapi = ?,
        agen.rating_cepat = ?
      WHERE
        agen.kode_agen = ?";

    // Parameter binding
    $bind_param = array (
      $rating_rapi,
      $rating_cepat,
      $kode_agen
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
      return TRUE;
    }
  }

  /**
   * Function - Mengisi balasan komentar review
   *
   * @param string $kode_agen
   * @param string $kode_konsumen
   * @param string $kode_transaksi_review
   * @param string $isi_transaksi_review_balas
   *
   * @return Boolean

   */
  public function createBalasanReview (
    $kode_agen,
    $kode_konsumen,
    $kode_transaksi_review,
    $isi_transaksi_review_balas
    )
  {
    // SQL preparation
    $sql = "INSERT INTO transaksi_review_balas
      (
        kode_agen, 
        kode_konsumen, 
        kode_transaksi_review, 
        isi_transaksi_review_balas, 
        waktu_transaksi_review_balas, 
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

    // Create waktu_transaksi_review_balas
    $tgl = date("Y-m-d H:i:s");
    
    // Parameter binding
    $bind_param = array (
      $kode_agen,
      $kode_konsumen,
      $kode_transaksi_review,
      $isi_transaksi_review_balas,
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
      return $tgl;
    }
  }

  /**
   * Function - Menghapus balasan / komentar sebuah review
   *
   * @param Integer $kode_transaksi_review            Kode review
   * @param String $waktu_transaksi_review_balas      Tanggal balas review
   * 
   * @return [Boolean] [Menandakan penghapusan berhasil atau tidak]
   */
  public function deleteBalasanReview (
    $kode_transaksi_review,
    $waktu_transaksi_review_balas
    )
  {
    // SQL preparation
    $sql = "UPDATE transaksi_review_balas
      SET hapus = ?
      WHERE transaksi_review_balas.kode_transaksi_review  = ?
        AND transaksi_review_balas.waktu_transaksi_review_balas = ?";

    // Parameter binding
    $bind_param = array (
      "1",
      $kode_transaksi_review,
      $waktu_transaksi_review_balas
      );

    // Query execution
    $query = $this->db->query (
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
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