<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAfiliasiRetriever extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Retrieve owned agen by afiliasi
   * 
   * @param  String $kode_afiliasi      Kode afiliasi
   * @param  String $status_agen        Status agen
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveOwnedAgen (
    $kode_afiliasi,
    $status_agen)
  {
    // Query preparation
    $sql = "SELECT * 
      FROM agen
      WHERE kode_afiliasi = ?
        AND status_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_afiliasi,
      $status_agen,
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
   * Function - Count and retrieve total of owned agen by afiliator of each status
   * 
   * @param  String $kode_afiliasi      Kode afiliasi
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveTotalOwnedAgen($kode_afiliasi)
  {
    // Query preparation
    $sql = "SELECT 
        status_agen,
        COUNT(kode_agen) AS jumlah_agen
      FROM agen
      WHERE kode_afiliasi = ?
        AND hapus = ?
      GROUP BY status_agen";

    // Parameter binding
    $bind_param = array (
      $kode_afiliasi,
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
   * Function - Retrieve income summary (afiliasi)
   * 
   * @param  String  $kode_afiliasi     Kode afiliasi
   * @param  String  $sort              Tipe sorting
   * @param  Integer $from              Dari
   * @param  Integer $to                Ke
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveRekapFeeAfiliasi (
    $kode_afiliasi,
    $sort,
    $from,
    $to
    )
  {
    switch ($sort) {
      case 'tahun':
        // Query preparation
        $sql = "SELECT 
            YEAR(t.tanggal_selesai) AS tahun,
            SUM(tl.harga * COALESCE(lh.persentase_fee_afiliasi, 0.1)) AS pendapatan
          FROM transaksi_layanan tl
          JOIN transaksi t
            ON t.kode_transaksi = tl.kode_transaksi
          JOIN layanan_harga lh
            ON tl.kode_harga_layanan = lh.kode_harga_layanan
          JOIN agen a
            ON t.kode_agen = a.kode_agen
          WHERE t.status_transaksi = ?
            AND a.kode_afiliasi = ?
            AND YEAR(t.tanggal_selesai) BETWEEN ? AND ?
            AND t.hapus = ?
          GROUP BY YEAR(t.tanggal_selesai)
          ORDER BY t.tanggal_selesai DESC";
        break;
      
      case 'bulan':
        // Query preparation
        $sql = "SELECT 
            DATE_FORMAT(t.tanggal_selesai, '%M %Y') AS bulan,
            SUM(tl.harga * COALESCE(lh.persentase_fee_afiliasi, 0.1)) AS pendapatan
          FROM transaksi_layanan tl
          JOIN transaksi t
            ON t.kode_transaksi = tl.kode_transaksi
          JOIN layanan_harga lh
            ON tl.kode_harga_layanan = lh.kode_harga_layanan
          JOIN agen a
            ON t.kode_agen = a.kode_agen
          WHERE t.status_transaksi = ?
            AND a.kode_afiliasi = ?
            AND DATE_FORMAT(t.tanggal_selesai, '%Y-%m')  BETWEEN ? AND ?
            AND t.hapus = ?
          GROUP BY YEAR(t.tanggal_selesai)
          ORDER BY t.tanggal_selesai DESC";
        break;

      case 'hari':
        // Query preparation
        $sql = "SELECT 
            t.tanggal_selesai AS tanggal,
            SUM(tl.harga * COALESCE(lh.persentase_fee_afiliasi, 0.1)) AS pendapatan
          FROM transaksi_layanan tl
          JOIN transaksi t
            ON t.kode_transaksi = tl.kode_transaksi
          JOIN layanan_harga lh
            ON tl.kode_harga_layanan = lh.kode_harga_layanan
          JOIN agen a
            ON t.kode_agen = a.kode_agen
          WHERE t.status_transaksi = ?
            AND a.kode_afiliasi = ?
            AND t.tanggal_selesai BETWEEN ? AND ?
            AND t.hapus = ?
          GROUP BY YEAR(t.tanggal_selesai)
          ORDER BY t.tanggal_selesai DESC";
        break;

      default:
        # code...
        break;
    }

    // Parameter binding
    $bind_param = array (
      "11",
      $kode_afiliasi,
      $from,
      $to,
      "0"
      );

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
   * Pencairan Data
   */


  /**
   * Function - Retrieve last requested pencairan fee
   * 
   * @param  String $kode_afiliasi  Kode afiliasi
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveLastRequestPencairan ($kode_afiliasi)
  {
    // Query preparation
    $sql = "SELECT
      kode_afiliasi_pencairan_saldo,
        tanggal_request,
        status_afiliasi_pencairan_saldo,
        nominal
      FROM afiliasi_pencairan_saldo
      WHERE kode_afiliasi = ?
        AND (status_afiliasi_pencairan_saldo = ? OR status_afiliasi_pencairan_saldo = ?)
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_afiliasi,
      "0",
      "1",
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
   * Function - Retrieve history pencairan fee
   * 
   * @param  String $kode_afiliasi  Kode afiliasi
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveHistoryPencairanFee ($kode_afiliasi)
  {
    // Query preparation
    $sql = "SELECT
        kode_afiliasi_pencairan_saldo,
        tanggal_request,
        tanggal_terima,
        status_afiliasi_pencairan_saldo,
        nominal
      FROM afiliasi_pencairan_saldo
      WHERE kode_afiliasi = ?
        AND (status_afiliasi_pencairan_saldo = ? OR status_afiliasi_pencairan_saldo = ?)
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_afiliasi,
      "2",
      "3",
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
   * Function - Retrieve all income since registered
   * 
   * @param  String $kode_afiliasi  Kode afiliasi
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveAllIncome ($kode_afiliasi)
  {
    // Query preparation
    $sql = "SELECT 
        a.pendapatan - b.cair AS total_pendapatan
      FROM
        (SELECT 
          SUM(tl.harga * COALESCE(lh.persentase_fee_afiliasi, 0.1)) AS pendapatan
        FROM transaksi_layanan tl
        JOIN transaksi t
          ON t.kode_transaksi = tl.kode_transaksi
        JOIN layanan_harga lh
          ON tl.kode_harga_layanan = lh.kode_harga_layanan
        JOIN agen a
          ON t.kode_agen = a.kode_agen
        WHERE t.status_transaksi = ?
          AND a.kode_afiliasi = ?
          AND t.hapus = ?) a,
        (SELECT
          COALESCE(SUM(aps.nominal), 0) AS cair
        FROM afiliasi_pencairan_saldo aps
        WHERE aps.status_afiliasi_pencairan_saldo = ?
          AND aps.hapus = ?) b";

    // Parameter binding
    $bind_param = array (
      "11",
      $kode_afiliasi,
      "0",
      "2",
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
   * Function - Retrieve agen detail
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return Boolean/String/Array
   */
  public function retrieveAgenDetail ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT *
      FROM agen a
      JOIN agen_alamat aa
        ON a.kode_agen = aa.kode_agen
      WHERE a.kode_agen = ?
        AND a.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
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
}