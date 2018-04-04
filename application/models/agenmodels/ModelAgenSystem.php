<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAgenSystem extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Mengecek apakah sudah ada
   *
   * @param $kode_kota        Kode kota lokasi agen
   * 
   * @return Boolean
   */
  public function checkIfAnyBranch ($kode_kota)
  {
    // SQL preparation
    $sql = "SELECT
      COUNT(pshcabang.kode_pshcabang) AS jml_cabang
      FROM pshcabang
      WHERE pshcabang.kode_kota = ?
        AND pshcabang.hapus = ?";

    // Parameter binding
    $bind_param = array(
      $kode_kota,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } else {
      $jml_cabang = $query->result_array();

      if ($jml_cabang[0]['jml_cabang'] < 1) {
        return "EMPTY";
      } else {
        return $jml_cabang;
      }
    }
  }

  /**
   * Function - Menampilkan kodepos berdasarkan kode_kelurahan
   * 
   * @param String $kode_kelurahan    Kode kelurahan
   * 
   * @return FALSE / "EMPTY" / kode_pos
   */
  public function retrieveKodePost ($kode_kelurahan)
  {
    // SQL preparation
    $sql = "SELECT 
      kelurahan.kode_pos
      FROM kelurahan
      WHERE kelurahan.kode_kelurahan = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_kelurahan,
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
      $kodepos = $query->result_array();
      return $kodepos[0]['kode_pos'];
    }
  }

  /**
   * Function - Retrieve daftar konsumen berdasarkan nama atau notelp
   * 
   * @param Strin $myParam    Berisi nilai "nama" atau "notelp"
   * 
   * @return FALSE / "EMPTY" / Result Array
   */
  public function retrieveKonsumen ($myParam)
  {
    // SQL preparation
    $sql = "SELECT 
      konsumen.kode_konsumen,
      konsumen.nama,
      konsumen.notelp
      FROM konsumen
      WHERE 
        konsumen.nama LIKE ?
        OR konsumen.notelp LIKE ?
        AND konsumen.hapus = ?";

    // Parameter binding
    $bind_param = array (
      "%$myParam%",
      "%$myParam%",
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  } 

  /**
   * Function - Mengambil kode cabang berdasarkan kode_agen
   * 
   * @param  String $kode_agen            Kode agen
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveKodeCabang ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT kode_pshcabang
      FROM agen
      WHERE kode_agen = ?
        AND hapus = ?";

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

  /**
   * Function - Check if qr_transaksi has been scanned
   * 
   * @param  String $kode_qr_transaksi      kode_qr_transaksi
   * 
   * @return Boolean/String/Array           Result
   */
  public function checkIfScanned ($kode_qr_transaksi)
  {
    // Query preparation
    $sql = "SELECT scan
      FROM qr_transaksi
      WHERE kode_qr_transaksi = ?";

    // Parameter binding
    $bind_param = array ($kode_qr_transaksi);

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
   * Function - Check if qr absen scanned 
   * 
   * @param  String $kode_agen_temp_kode_absen    kode qr_absen
   * 
   * @return Boolean/String/Array
   */
  public function checkIfQRAbsenScanned ($kode_agen_temp_kode_absen)
  {
    // Query preparation
    $sql = "SELECT scan
      FROM agen_temp_kode_absen
      WHERE kode_agen_temp_kode_absen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen_temp_kode_absen,
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
   * Function - Retrieve transaction on waiting list
   * 
   * @param  String $kode_transaksi       Kode transaksi
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveWaitingListDate ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT tanggal_terima
      FROM transaksi
      WHERE kode_transaksi = ?
        AND status_transaksi = ?
        AND hapus = ?";

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
   * Function - Retrieve service list of canceled transaction
   * 
   * @param  String $kode_transaksi       Kode transaksi
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveCanceledLayanan ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT 
      CASE l.kode_jenis_layanan WHEN 1 THEN 'satuan'
        WHEN 2 THEN 'kiloan'
        WHEN 3 THEN 'luas' END 
        AS tipe_layanan,
      tl.kode_harga_layanan,
      tl.jumlah,
      tl.jumlah_helai,
      tl.panjang,
      tl.lebar
      FROM transaksi_layanan tl
      JOIN layanan_harga lh
        ON tl.kode_harga_layanan = lh.kode_harga_layanan
      JOIN layanan l
        ON lh.kode_layanan = l.kode_layanan
      WHERE tl.kode_transaksi = ?
        AND tl.hapus = ?
        AND lh.hapus = ?
        AND l.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      "0",
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
   * Function - Retrieve information of antar-jemput transaksi
   * 
   * @param  String $kode_transaksi       Kode transaksi
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveAntarJemputData ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT
        tj.notelp,
        tj.kode_konsumen_alamat AS kode_alamat_jemput,
        tj.tanggal_transaksi_jemput,
        tj.latitude AS latitude_jemput,
        tj.longitude AS longitude_jemput,
        tj.catatan AS catatan_jemput,
        ta.kode_konsumen_alamat AS kode_alamat_antar,
        t.biaya_antar AS estimasi_biaya_antar_jemput
      FROM transaksi_jemput tj
      LEFT JOIN transaksi t
        ON tj.kode_transaksi = t.kode_transaksi
      LEFT JOIN transaksi_antar ta
        ON tj.kode_transaksi = ta.kode_transaksi
      WHERE tj.kode_transaksi = ?
        AND tj.hapus = ?";

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
      return $query->row();
    }
  }

  /**
   * Function - Edit agen's nama
   * 
   * @param  String $kode_agen    Kode agen
   * @param  String $nama         Nama agen
   * 
   * @return Boolean
   */
  public function updateNama (
    $kode_agen,
    $nama
    )
  {
    // Query preparation
    $sql = "UPDATE agen
      SET nama = ?
      WHERE kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $nama,
      $kode_agen,
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
   * Function - Edit agen's slogan
   * 
   * @param  String $kode_agen    Kode agen
   * @param  String $slogan       Slogan agen
   * 
   * @return Boolean
   */
  public function updateSlogan (
    $kode_agen,
    $slogan
    )
  {
    // Query preparation
    $sql = "UPDATE agen
      SET slogan = ?
      WHERE kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $slogan,
      $kode_agen,
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
   * For calculating pendapatan
   */
  
  /**
   * Function - Retrieve persentase fee agen
   * 
   * @param  String $kode_harga_layanan       Kode harga layanan
   * 
   * @return Boolean/String/Array             FALSE/"EMPTY"/Result
   */
  public function retrievePersentaseFeeAgen ($kode_harga_layanan)
  {
    // Query preparation
    $sql = "SELECT 
        COALESCE(lh.persentase_fee_agen, 0.1) AS persentase_fee_agen
      FROM layanan_harga lh
      JOIN layanan l
        ON lh.kode_layanan = l.kode_layanan
      WHERE lh.kode_harga_layanan = ?
        AND l.hapus = ?";

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
      $error = $this->db->erorr();
      return FALSE;
    } elseif ($query->num_rows()) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }
  
  /**
   * Jenis bayar
   */
  
  /**
   * Function - Retrieve jenis bayar
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveJenisBayar ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT jenis_bayar
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
   * Function - Update file kk
   * 
   * @param  String $kode_agen    Kode agen
   * @param  String $url          URL file foto kk
   * 
   * @return Boolean
   */
  public function updateFileKK (
    $kode_agen,
    $url
    )
  {
    // Query preparation
    $sql = "UPDATE agen
      SET file_kk = ?
      WHERE kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array(
      $url,
      $kode_agen,
      "0"
      );

    // Query transaction
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
   * Function - Update file ktp
   * 
   * @param  String $kode_agen    Kode agen
   * @param  String $url          URL file foto ktp
   * 
   * @return Boolean
   */
  public function updateFileKTP (
    $kode_agen,
    $url
    )
  {
    // Query preparation
    $sql = "UPDATE agen
      SET file_ktp = ?
      WHERE kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array(
      $url,
      $kode_agen,
      "0"
      );

    // Query transaction
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
}