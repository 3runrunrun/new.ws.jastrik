<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKonsumenSystem extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Generating code for transaction
   *
   * @param string $transactiontype    Tipe transaksi
   * 
   * @return FALSE / result array
   */
  public function codeGenerator ($transactiontype)
  {
    $uniquenumber = date("ymdHis");
    $microtime = substr(microtime(),2,6);

    switch ($transactiontype) {
      case 'bld':
        $generatedCode = "BLD/" . $uniquenumber . $microtime;
        break;

      case 'bbd':
        $generatedCode = "BBD/" . $uniquenumber . $microtime;
        break;

      case 'transaksi':
        $generatedCode = "INV/" . $uniquenumber . $microtime;
        break;
      
      default:
        # code...
        break;
    }

    return strtoupper($generatedCode);
  }

  /**
   * Function - Check if Konsumen's Alamat is exist
   *
   * @param string $kode_konsumen    kode konsumen yang sudah digenerate
   * 
   * @return TRUE / FALSE
   */
  public function checkIfAlamatExist ($kode_konsumen)
  {
    $sql = "SELECT ? 
      FROM konsumen_alamat 
      WHERE kode_konsumen = ?";

    $bind_param = array (
      'kode_konsumen_alamat',
      $kode_konsumen
      );

    $query = $this->db->query($sql, $bind_param);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Check if konsumen had an transaction arrears 
   * (tunggakan)
   *
   * @param string $kode_konsumen    kode konsumen 
   * 
   * @return FALSE / TRUE
   */
  public function checkIfAnyArrears ($kode_konsumen)
  {
    // SQL preparing
    $sql = "SELECT kode_konsumen_beli_dompet
      FROM konsumen_beli_dompet
      WHERE (status_konsumen_beli_dompet = ?
        OR status_konsumen_beli_dompet = ?)
        AND hapus = ?";

    // Paremeter binding
    $bind_param = array (
      "0",
      "1",
      "0");

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    } 
  }

  /**
   * Function - Retrieve arrears
   *
   * @param string $kode_konsumen    kode konsumen 
   * 
   * @return FALSE / Result Array
   */
  public function retrieveArrears ($kode_konsumen)
  {
    // SQL preparing
    $sql = "SELECT 
        konsumen_beli_dompet.kode_konsumen_beli_dompet,
        konsumen_bayar_beli_dompet.foto,
        konsumen_beli_dompet.harga_transfer,
        konsumen_beli_dompet.status_konsumen_beli_dompet
      FROM konsumen_beli_dompet
      LEFT JOIN konsumen_bayar_beli_dompet 
        ON konsumen_bayar_beli_dompet.kode_konsumen_beli_dompet = konsumen_beli_dompet.kode_konsumen_beli_dompet
      WHERE konsumen_beli_dompet.kode_konsumen = ?
        AND (status_konsumen_beli_dompet = ?
        OR status_konsumen_beli_dompet = ?)
        AND konsumen_beli_dompet.hapus = ?";

    // Paremeter binding
    $bind_param = array (
      $kode_konsumen,
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
   * Function - Mengecek apakah enkripsi belum di scan
   * 
   * @param  [Strin] $enkripsi      [kode enkripsi]
   * 
   * @return [Boolean / String]     [EMPTY]
   */
  public function checkQrStatus (
    $transaction_event,
    $enkripsi
    )
  {
    switch ($transaction_event) {
      case 'OT': // Offline Transaction
        // SQL preparation
        $sql = "SELECT 
            COUNT(qr_transaksi.kode_qr_transaksi) AS jml_scan
          FROM qr_transaksi
          WHERE qr_transaksi.enkripsi = ?
           AND qr_transaksi.scan = ?";

        // Parameter binding
        $bind_param = Array (
          $enkripsi,
          "0"
          );
        break;

      case 'CT': // Checkout Transaction
        // SQL preparation
        $sql = "SELECT 
            COUNT(qr_transaksi_kurir.kode_qr_transaksi_kurir) AS jml_scan
          FROM qr_transaksi_kurir
          WHERE enkripsi = ?
           AND scan = ?";

        // Parameter binding
        $bind_param = Array (
          $enkripsi,
          "0"
          );
        break;
      
      default:
        # code...
        break;
    }
    

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
   * Function - Menampilkan harga layanan
   * 
   * @param  String $kode_harga_layanan     Kode harga layanan
   * 
   * @return Boolean/String/Array           FALSE/EMPTY/Result
   */
  public function retrieveHargaLayanan ($kode_harga_layanan)
  {
    // SQL preparation
    $sql = "SELECT harga_layanan
      FROM layanan_harga
      WHERE kode_harga_layanan = ?
      AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_harga_layanan,
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

  public function retrieveDurasiLayanan ($kode_harga_layanan)
  {
    // SQL preparation
    $sql = "SELECT COALESCE(durasi_layanan, 0) AS durasi_layanan
      FROM layanan l
      JOIN layanan_harga lh
        ON l.kode_layanan = lh.kode_layanan
      WHERE lh.kode_harga_layanan = ?
        AND lh.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_harga_layanan,
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
   * Cek transaksi
   */
  
  /**
   * Function - Cek apakah transaksi sudah diterima oleh agen
   * 
   * @param  String $kode_transaksi       kode transaksi
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function checkIfTransactionAccepted ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT 
        kode_transaksi,
        kode_konsumen,
        kode_agen,
        jenis_bayar,
        total,
        jenis_antar,
        jenis_jemput,
        status_transaksi
      FROM transaksi
      WHERE kode_transaksi = ?
      AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
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
   * Function - Check if any invoice / transaction with dompet payment is unpaid
   * 
   * @param  String $kode_konsumen         Kode konsumen
   * 
   * @return String/Boolean                "ERROR"/TRUE/FALSE
   */
  public function checkIfAnyDompetArrears ($kode_konsumen)
  {
    // Query preparation
    $sql = "SELECT t.kode_transaksi FROM transaksi t
      WHERE t.kode_konsumen = ?
        AND t.status_transaksi IN ?
        AND t.jenis_bayar = ?
        AND t.hapus  = ?";

    // Parameter binding
    $bind_param = array(
      $kode_konsumen,
      array ("0","1","2"),
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
      return "ERROR";
    } elseif ($query->num_rows() < 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * Function - Checking is transaction pay with dompet
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return String/Boolean
   */
  public function checkIsPayWithDompet ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT COALESCE(jenis_bayar, 0) AS jenis_bayar
      FROM transaksi
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array(
      $kode_transaksi,
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error();
      return "ERROR";
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      $jenis_bayar = $query->result_array();
      if ($jenis_bayar[0]['jenis_bayar'] != "2") {
        return FALSE;
      } else {
        return TRUE;
      }
    }
  }

}