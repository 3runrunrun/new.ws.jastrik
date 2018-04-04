<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKurirRetriever extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Retrieve new transaction milestone
   * 
   * @param  String $kode_kurir       Kode kurir
   * @param  String $status_transaksi Status transaksi
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveTransactionMilestoneBaru (
    $kode_kurir,
    $status_transaksi
    )
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_transaksi,
        agen.nama AS nama_agen,
        konsumen.nama AS nama_konsumen,
        transaksi.status_transaksi,
        CASE transaksi.status_transaksi
          WHEN '1' THEN 'jemput'
          WHEN '8' THEN 'antar'
        END AS tipe_antar_jemput,
        CASE transaksi.status_transaksi
          WHEN '1' THEN transaksi_jemput.tanggal_transaksi_jemput
          WHEN '8' THEN transaksi_antar.tanggal_transaksi_antar
        END AS tanggal_antar_jemput,
        CASE transaksi.status_transaksi
          WHEN '1' THEN transaksi_jemput.status_transaksi_jemput
          WHEN '8' THEN transaksi_antar.status_transaksi_antar
        END AS status_antar_jemput,
        CASE transaksi.status_transaksi
          WHEN '1' THEN transaksi_jemput.latitude
          WHEN '8' THEN transaksi_antar.latitude
        END AS latitude_konsumen,
        CASE transaksi.status_transaksi
          WHEN '1' THEN transaksi_jemput.longitude
          WHEN '8' THEN transaksi_antar.longitude
        END AS longitude_konsumen,
        agen_alamat.latitude AS latitude_agen,
        agen_alamat.longitude AS longitude_agen
      FROM transaksi
      JOIN agen
        ON transaksi.kode_agen = agen.kode_agen
      JOIN agen_alamat
        ON agen_alamat.kode_agen = agen.kode_agen
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      LEFT JOIN transaksi_jemput
        ON transaksi.kode_transaksi = transaksi_jemput.kode_transaksi
      LEFT JOIN transaksi_antar
        ON transaksi.kode_transaksi = transaksi_antar.kode_transaksi
      WHERE 
        (transaksi_jemput.kode_kurir = ?
        OR transaksi_antar.kode_kurir = ?)
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?
      ORDER BY tanggal_antar_jemput DESC";

    // Parameter binding
    $bind_param = array (
      $kode_kurir,
      $kode_kurir,
      $status_transaksi,
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
   * Function - Retrieve transaction milestone (jemput)
   * 
   * @param  String $kode_kurir       Kode kurir
   * @param  String $status_transaksi Status transaksi
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveTransactionMilestoneJemput (
    $kode_kurir,
    $status_transaksi
    )
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_transaksi,
        agen.nama AS nama_agen,
        konsumen.nama AS nama_konsumen,
        transaksi.status_transaksi,
        transaksi_jemput.tanggal_transaksi_jemput,
        transaksi_jemput.status_transaksi_jemput,
        transaksi_jemput.latitude AS latitude_konsumen,
        transaksi_jemput.longitude AS longitude_konsumen,
        agen_alamat.latitude AS latitude_agen,
        agen_alamat.longitude AS longitude_agen
      FROM transaksi
      JOIN agen
        ON transaksi.kode_agen = agen.kode_agen
      JOIN agen_alamat
        ON agen_alamat.kode_agen = agen.kode_agen
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      JOIN transaksi_jemput
        ON transaksi.kode_transaksi = transaksi_jemput.kode_transaksi
      WHERE transaksi_jemput.kode_kurir = ?
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?
      ORDER BY transaksi_jemput.tanggal_transaksi_jemput DESC";

    // Parameter binding
    $bind_param = array (
      $kode_kurir,
      $status_transaksi,
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
   * Function - Retrieve transaction milestone (antar)
   * 
   * @param  String $kode_kurir       Kode kurir
   * @param  String $status_transaksi Status transaksi
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveTransactionMilestoneAntar (
    $kode_kurir,
    $status_transaksi
    )
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_transaksi,
        agen.nama AS nama_agen,
        konsumen.nama AS nama_konsumen,
        transaksi.status_transaksi,
        transaksi_antar.tanggal_transaksi_antar,
        transaksi_antar.status_transaksi_antar,
        transaksi_antar.latitude AS latitude_konsumen,
        transaksi_antar.longitude AS longitude_konsumen,
        agen_alamat.latitude AS latitude_agen,
        agen_alamat.longitude AS longitude_agen
      FROM transaksi
      JOIN agen
        ON transaksi.kode_agen = agen.kode_agen
      JOIN agen_alamat
        ON agen_alamat.kode_agen = agen.kode_agen
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      JOIN transaksi_antar
        ON transaksi.kode_transaksi = transaksi_antar.kode_transaksi
      WHERE transaksi_antar.kode_kurir = ?
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?
      ORDER BY transaksi_antar.tanggal_transaksi_antar DESC";

    // Parameter binding
    $bind_param = array (
      $kode_kurir,
      $status_transaksi,
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
   * Transaction detail data
   */

  /**
   * Function - Retrieve basic of detail transaction
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveBasicDetailTransaksi ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        k.kode_konsumen,
        k.nama,
        tj.notelp as notelp_jemput,
        ta.notelp as notelp_antar,
        t.subtotal,
        t.diskon,
        t.biaya_antar,
        t.pajak,
        t.total,
        t.jenis_bayar
      FROM konsumen k
      JOIN transaksi t
        ON k.kode_konsumen = t.kode_konsumen
      LEFT JOIN transaksi_jemput tj
        ON t.kode_transaksi = tj.kode_transaksi
      LEFT JOIN transaksi_antar ta
        ON t.kode_transaksi = ta.kode_transaksi
      WHERE t.kode_transaksi = ?
        AND t.hapus = ?
        AND k.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      "0",
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
   * Function - Retrieve list of layanan of detail transaction
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveLayananDetailTransaksi ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT 
        tl.kode_transaksi_layanan,
        jk.kode_jenis_layanan,
        l.nama_layanan,
        tl.jumlah,
        tl.jumlah_helai,
        tl.panjang,
        tl.lebar,
        tl.harga
      FROM layanan l
      JOIN layanan_harga lh
        ON l.kode_layanan = lh.kode_layanan
      JOIN jenis_layanan jk
        ON jk.kode_jenis_layanan = l.kode_jenis_layanan
      JOIN transaksi_layanan tl
        ON tl.kode_harga_layanan = lh.kode_harga_layanan
      WHERE tl.kode_transaksi = ?
        AND tl.hapus = ?
        AND l.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      "0",
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
   * Function - Retrieve history transaksi, accepted / rejected
   * 
   * @param  String $kode_kurir         Kode kurir
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveHistoryTransaksi ($kode_kurir)
  {
    $sql = "SELECT
        transaksi.kode_transaksi,
        agen.nama AS nama_agen,
        konsumen.nama AS nama_konsumen,
        transaksi.status_transaksi,
        transaksi.tanggal_terima,
        CASE transaksi.jenis_jemput
        WHEN '0' THEN 'jemput'
        WHEN '1' THEN 'jemput'
        END AS tipe_antar_jemput
      FROM transaksi
      JOIN agen
        ON transaksi.kode_agen = agen.kode_agen
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      JOIN transaksi_jemput
        ON transaksi.kode_transaksi = transaksi_jemput.kode_transaksi
      WHERE transaksi_jemput.kode_kurir = ? 
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?
      UNION ALL
      SELECT
        transaksi.kode_transaksi,
        agen.nama AS nama_agen,
        konsumen.nama AS nama_konsumen,
        transaksi.status_transaksi,
        transaksi.tanggal_terima,
        CASE transaksi.jenis_antar
        WHEN '0' THEN 'antar'
        WHEN '1' THEN 'antar'
        END AS tipe_antar_jemput
      FROM transaksi
      JOIN agen
        ON transaksi.kode_agen = agen.kode_agen
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      JOIN transaksi_antar
        ON transaksi.kode_transaksi = transaksi_antar.kode_transaksi
      WHERE transaksi_antar.kode_kurir = ?
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?
      ORDER BY tanggal_terima DESC";

    $bind_param = array (
      $kode_kurir,
      array("5", "21"),
      "0",
      $kode_kurir,
      array ("11", "23"),
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

  /////////////////
  // Summary fee //
  /////////////////

  /**
   * Function - Retrieve income summary
   * 
   * @param  String  $kode_kurir    Kode kurir
   * @param  String  $sort          Tipe sorting
   * @param  Integer $from          Dari
   * @param  Integer $to            Ke
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveRekapFeeKurir (
    $kode_kurir,
    $sort,
    $from,
    $to
    )
  {
    switch ($sort) {
      case 'tahun':
        // Query preparation
        $sql = "SELECT 
          YEAR(a.tanggal_transaksi_jemput) AS tahun,
          SUM(a.pendapatan) AS pendapatan
        FROM 
        (SELECT
           t.kode_transaksi,
            t.biaya_antar,
            t.jenis_jemput,
            t.jenis_antar,
            tj.kode_kurir AS kurir_jemput,
            ta.kode_kurir AS kurir_antar,
            ta.auto_antar,
            tj.tanggal_transaksi_jemput,
            ta.tanggal_transaksi_antar,
            (CASE
              WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '0')
              THEN t.biaya_antar
              WHEN (t.jenis_jemput = '0' AND t.jenis_antar = '1')
              THEN t.biaya_antar
              WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '1')
              THEN 
                (CASE 
                WHEN (tj.kode_kurir = ta.kode_kurir) THEN t.biaya_antar 
                WHEN (tj.kode_kurir <> ta.kode_kurir) THEN t.biaya_antar/2 
              END)
           END) AS pendapatan
        FROM transaksi t
        LEFT JOIN transaksi_jemput tj
          ON t.kode_transaksi = tj.kode_transaksi
        LEFT JOIN transaksi_antar ta
          ON t.kode_transaksi = ta.kode_transaksi
        WHERE (tj.kode_kurir = ? OR ta.kode_kurir = ?)
          AND (YEAR(tj.tanggal_transaksi_jemput) BETWEEN ? AND ?
            OR YEAR(ta.tanggal_transaksi_antar) BETWEEN ? AND ?)
            AND t.hapus = ?) a
        GROUP BY YEAR(a.tanggal_transaksi_jemput)
        ORDER BY YEAR(a.tanggal_transaksi_jemput) DESC";
        break;
      
      case 'bulan':
        // Query preparation
        $sql = "SELECT 
          DATE_FORMAT(a.tanggal_transaksi_jemput, '%M-%Y') AS bulan,
          SUM(a.pendapatan) AS pendapatan
        FROM 
        (SELECT
           t.kode_transaksi,
            t.biaya_antar,
            t.jenis_jemput,
            t.jenis_antar,
            tj.kode_kurir AS kurir_jemput,
            ta.kode_kurir AS kurir_antar,
            ta.auto_antar,
            tj.tanggal_transaksi_jemput,
            ta.tanggal_transaksi_antar,
            (CASE
              WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '0')
              THEN t.biaya_antar
              WHEN (t.jenis_jemput = '0' AND t.jenis_antar = '1')
              THEN t.biaya_antar
              WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '1')
              THEN 
                (CASE 
                WHEN (tj.kode_kurir = ta.kode_kurir) THEN t.biaya_antar 
                WHEN (tj.kode_kurir <> ta.kode_kurir) THEN t.biaya_antar/2 
              END)
           END) AS pendapatan
        FROM transaksi t
        LEFT JOIN transaksi_jemput tj
          ON t.kode_transaksi = tj.kode_transaksi
        LEFT JOIN transaksi_antar ta
          ON t.kode_transaksi = ta.kode_transaksi
        WHERE (tj.kode_kurir = ? OR ta.kode_kurir = ?)
          AND (DATE_FORMAT(tj.tanggal_transaksi_jemput, '%Y-%m') BETWEEN ? AND ?
            OR DATE_FORMAT(ta.tanggal_transaksi_antar, '%Y-%m') BETWEEN ? AND ?)
            AND t.hapus = ?) a
        GROUP BY MONTHNAME(a.tanggal_transaksi_jemput)
        ORDER BY a.tanggal_transaksi_jemput DESC";
        break;

      case 'hari':
        // Query preparation
        $sql = "SELECT 
          a.tanggal_transaksi_jemput AS tanggal_jemput,
          a.tanggal_transaksi_antar AS tanggal_antar,
          SUM(a.pendapatan) AS pendapatan
        FROM 
        (SELECT
           t.kode_transaksi,
            t.biaya_antar,
            t.jenis_jemput,
            t.jenis_antar,
            tj.kode_kurir AS kurir_jemput,
            ta.kode_kurir AS kurir_antar,
            ta.auto_antar,
            tj.tanggal_transaksi_jemput,
            ta.tanggal_transaksi_antar,
            (CASE
              WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '0')
              THEN t.biaya_antar
              WHEN (t.jenis_jemput = '0' AND t.jenis_antar = '1')
              THEN t.biaya_antar
              WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '1')
              THEN 
                (CASE 
                WHEN (tj.kode_kurir = ta.kode_kurir) THEN t.biaya_antar 
                WHEN (tj.kode_kurir <> ta.kode_kurir) THEN t.biaya_antar/2 
              END)
           END) AS pendapatan
        FROM transaksi t
        LEFT JOIN transaksi_jemput tj
          ON t.kode_transaksi = tj.kode_transaksi
        LEFT JOIN transaksi_antar ta
          ON t.kode_transaksi = ta.kode_transaksi
        WHERE (tj.kode_kurir = ? OR ta.kode_kurir = ?)
          AND (tj.tanggal_transaksi_jemput BETWEEN ? AND ?
            OR ta.tanggal_transaksi_antar BETWEEN ? AND ?)
          AND t.hapus = ?) a
        GROUP BY a.tanggal_transaksi_jemput, a.tanggal_transaksi_antar
        ORDER BY a.tanggal_transaksi_jemput DESC";
        break;

      default:
        # code...
        break;
    }

    // Parameter binding
    $bind_param = array (
      $kode_kurir,
      $kode_kurir,
      $from,
      $to,
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

  ///////////////////////////////////////////////
  // Summary Pendapatan Kurir Since Registered //
  ///////////////////////////////////////////////

  /**
   * Function - Retrieve Fee Summary
   *
   * @param String $kode_kurir   Kode kurir
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveFeeSummary ($kode_kurir)
  {
    $sql = "SELECT 
      SUM(a.pendapatan) summary
    FROM
    (SELECT
        t.biaya_antar,
        t.jenis_jemput,
        t.jenis_antar,
        tj.kode_kurir AS kurir_jemput,
        ta.kode_kurir AS kurir_antar,
        ta.auto_antar,
        (CASE
          WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '0')
          THEN t.biaya_antar
          WHEN (t.jenis_jemput = '0' AND t.jenis_antar = '1')
          THEN t.biaya_antar
          WHEN (t.jenis_jemput = '1' AND t.jenis_antar = '1')
          THEN 
            (CASE 
            WHEN (tj.kode_kurir = ta.kode_kurir) THEN t.biaya_antar 
            WHEN (tj.kode_kurir <> ta.kode_kurir) THEN t.biaya_antar/2 
          END)
       END) AS pendapatan
    FROM transaksi t
    LEFT JOIN transaksi_jemput tj
      ON t.kode_transaksi = tj.kode_transaksi
    LEFT JOIN transaksi_antar ta
      ON t.kode_transaksi = ta.kode_transaksi
    WHERE (tj.kode_kurir = ? OR ta.kode_kurir = ?)
      AND t.hapus = ?) a";

    $bind_param = array (
      $kode_kurir,
      $kode_kurir,
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
}