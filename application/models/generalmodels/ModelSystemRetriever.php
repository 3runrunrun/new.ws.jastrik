<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSystemRetriever extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
  }

  /**
   * Function - Retrieving all Province data
   *
   * @param No Params
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveProvinsi ()
  {
    $query = $this->db->get('provinsi');
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Retrieving all Kota data by kode_provinsi
   *
   * @param string $kode_provinsi     Kode dari tabel Provinsi
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveKotaByProvinsi ($kode_provinsi)
  {
    $this->db->where('kode_provinsi', $kode_provinsi);
    $query = $this->db->get('kota');
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Retrieve all City data
   *
   * @param no params
   * 
   * @return FALSE / query result as Array
   */
  public function retrieveKota ()
  {
    $query = $this->db->get('kota');
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Retrieve specific kode kota
   *
   * @param string $nama_kota     Nama kota
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveKodeKota ($nama_kota)
  {
    $this->db->select('kode_kota');
    $this->db->where('nama_kota', $nama_kota);
    $query = $this->db->get('kota');
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Retrieve Kecamatan
   */

  /**
   * Function - Retrieve Kecamatan data by kode kota
   *
   * @param string $kode_kota     Kode dari tabel kota
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveKecamatanByKota ($kode_kota)
  {
    // SQL preparing
    $sql = "SELECT *
      FROM kecamatan
      WHERE kode_kota = ?";

    // Parameter binding
    $bind_param = array ($kode_kota);

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Retrieve Kelurahan
   */

  /**
   * Function - Retrieve Kelurahan data by kode kecamatan
   *
   * @param string $kode_kecamatan     Kode dari tabel kecamatan
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveKelurahanByKecamatan ($kode_kecamatan)
  {
    // SQL preparing
    $sql = "SELECT *
      FROM kelurahan
      WHERE kode_kecamatan = ?";

    // Parameter binding
    $bind_param = array ($kode_kecamatan);

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Retrieve Kodepos
   */

  /**
   * Function - Retrieve Kodepos data by kode kelurahan
   *
   * @param string $kode_kelurahan     Kode dari tabel kelurahan
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveKodeposByKelurahan ($kode_kelurahan)
  {
    // SQL preparing
    $sql = "SELECT kode_pos
      FROM kelurahan
      WHERE kode_kelurahan = ?";

    // Parameter binding
    $bind_param = array ($kode_kelurahan);

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Agen Data
   */

  /**
   * Function - Retrieve all Agen
   *
   * @param float $lat    Latitude
   * @param float $lng    Longitude
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveAgenList (
    $lat, 
    $lng
    )
  {
    $query = NULL;

    // SQL A preparation
    $sqlA = "SELECT
      DISTINCT(agen.kode_agen),
      agen.nama,
      COALESCE(agen.rating_rapi, 0) as rating_rapi,
      COALESCE(agen.rating_cepat, 0) as rating_cepat,
      agen.logo,
      agen.tipe_agen,
      agen_alamat.latitude,
      agen_alamat.longitude,
      111.045 * DEGREES(ACOS(COS(RADIANS(?)) /*lat*/
              * COS(RADIANS(agen_alamat.latitude))
              * COS(RADIANS(agen_alamat.longitude) - RADIANS(?)) /*lng*/
              + SIN(RADIANS(?)) /*lat*/
              * SIN(RADIANS(agen_alamat.latitude)))) AS jarak
      FROM agen
      JOIN agen_alamat
        ON agen.kode_agen = agen_alamat.kode_agen
      LEFT JOIN transaksi
        ON transaksi.kode_agen = agen.kode_agen
      WHERE agen.status_agen = '1'
      ORDER BY jarak ASC";

    // Parameter binding for SQL A
    $bind_paramA = array (
      $lat,
      $lng,
      $lat
      );

    // SQL B preparation
    $sqlB = "SELECT
      DISTINCT(agen.kode_agen),
      agen.nama,
      COALESCE(agen.rating_rapi, 0) as rating_rapi,
      COALESCE(agen.rating_cepat, 0) as rating_cepat,
      agen.logo,
      agen.tipe_agen,
      agen_alamat.latitude,
      agen_alamat.longitude,
      (agen.rating_rapi + agen.rating_cepat) / 2 AS rerata_rating
      FROM agen
      JOIN agen_alamat
        ON agen.kode_agen = agen_alamat.kode_agen
      LEFT JOIN transaksi
        ON transaksi.kode_agen = agen.kode_agen
      WHERE agen.status_agen = '1'
      ORDER BY rerata_rating DESC";

    // Query execution
    if (!isset($lat) && !isset($lng)) {
      $query = $this->db->query($sqlB);
    } else {
      $query = $this->db->query(
        $sqlA, 
        $bind_paramA
        );
    }

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Retrieve rekap order_sukses berdasarkan kode_agen
   *
   * @param String $kode_agen     Kode agen
   * 
   * @return FALSE / EMPTY / Result as an array
   */
  public function retrieveOrderSuksesAgen ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
      COUNT(kode_transaksi) AS order_sukses
      FROM transaksi
      WHERE transaksi.status_transaksi = ?
        AND transaksi.kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      "1", // ganti parameter ini dengan status yang benar
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
    } else {
      $order_sukses = $query->result_array();

      if ($order_sukses[0]['order_sukses'] == 0) {
        return "EMPTY";
      } else {
        return $order_sukses[0]['order_sukses'];
      }
    }
  }

  /**
   * Function - Retrieve rekap order_ditolak berdasarkan kode_agen
   *
   * @param String $kode_agen     Kode agen
   * 
   * @return FALSE / EMPTY / Result as an array
   */
  public function retrieveOrderDitolakAgen ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
      COUNT(kode_transaksi) AS order_ditolak
      FROM transaksi
      WHERE transaksi.status_transaksi = ?
        AND transaksi.kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      "2", // ganti parameter ini dengan status yang benar
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
    } else {
      $order_ditolak = $query->result_array();

      if ($order_ditolak[0]['order_ditolak'] == 0) {
        return "EMPTY";
      } else {
        return $order_ditolak[0]['order_ditolak'];
      }
    }
  }

  /**
   * Function - Retrieve Agen detail
   *
   * @param string $kode_agen   Kode Agen
   * 
   * @return FALSE / Result as an array
   */
  public function retrieveAgenDetil ($kode_agen)
  {
    $field = array (
      'email',
      'notelp',
      'slogan',
      'agen.rating_rapi',
      'agen.rating_cepat',
      'agen_alamat.alamat',
      'agen_alamat.longitude',
      'agen_alamat.latitude'
      );
    $this->db->select ($field);
    $this->db->from ('agen');
    $this->db->join (
      'agen_alamat',
      'agen.kode_agen = agen_alamat.kode_agen'
      );
    $this->db->where ('agen.kode_agen', $kode_agen);
    $query = $this->db->get();
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Review Agen data
   */

  /**
   * Function - Menampilkan review masing-masing agen
   * 
   * @param string $kode_agen     Kode agen
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveReviewAgen ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
      kode_transaksi_review,
      transaksi_review.kode_transaksi,
      konsumen.kode_konsumen,
      konsumen.nama,
      isi_transaksi_review,
      rating_rapi,
      rating_cepat,
      tanggal_transaksi_review
      FROM transaksi_review 
      JOIN transaksi
        ON
          transaksi_review.kode_transaksi = transaksi.kode_transaksi
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      WHERE kode_agen = ? 
        AND transaksi_review.hapus = ?
        AND transaksi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      "0",
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan 1 review berdasarkan kode_transaksi_review
   * 
   * @param string $kode_transaksi_review  Kode review
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveSingleReviewAgen ($kode_transaksi_review)
  {
    // SQL prepare
    $sql = "SELECT 
      transaksi_review.kode_transaksi,
      transaksi.kode_konsumen,
      konsumen.nama,
      isi_transaksi_review,
      rating_rapi,
      rating_cepat,
      tanggal_transaksi_review
      FROM transaksi_review
      JOIN transaksi
        ON transaksi.kode_transaksi = transaksi_review.kode_transaksi
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      WHERE kode_transaksi_review = ?
        AND transaksi_review.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi_review,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan jumlah  review berdasarkan kode_agen
   * 
   * @param string $kode_agen  Kode agen
   *
   * @return FALSE / Result array
   */
  public function retrieveJumlahReview ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
      COUNT(kode_transaksi_review) AS jml_review
      FROM transaksi_review
      JOIN transaksi
        ON transaksi.kode_transaksi = transaksi_review.kode_transaksi
      WHERE kode_agen = ?
        AND transaksi_review.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan daftar balasan dari review
   * 
   * @param string $kode_transaksi_review  Kode review
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveBalasanReview ($kode_transaksi_review)
  {
    // SQL prepare
    $sql = "SELECT 
        transaksi_review_balas.kode_agen,
        agen.nama AS nama_agen,
        transaksi_review_balas.kode_konsumen,
        konsumen.nama AS nama_konsumen,
        isi_transaksi_review_balas,
        waktu_transaksi_review_balas
      FROM transaksi_review_balas
      LEFT JOIN agen
        ON transaksi_review_balas.kode_agen = agen.kode_agen
      LEFT JOIN konsumen
        ON transaksi_review_balas.kode_konsumen = konsumen.kode_konsumen
      WHERE kode_transaksi_review = ?
        AND transaksi_review_balas.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi_review,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Diskusi Agen data
   */

  /**
   * Function - Menampilkan daftar diskusi masing-masing agen
   * 
   * @param string $kode_agen     Kode agen
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveDiskusiAgen ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
      kode_agen_diskusi,
      agen_diskusi.kode_konsumen,
      konsumen.nama,
      isi_agen_diskusi,
      tanggal_agen_diskusi
      FROM agen_diskusi
      JOIN konsumen
        ON agen_diskusi.kode_konsumen = konsumen.kode_konsumen
      WHERE kode_agen = ?
        AND agen_diskusi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";      
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan 1 diskusi berdasarkan kode_agen_diskusi
   * 
   * @param string $kode_agen_diskusi  Kode diskusi
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveSingleDiskusi ($kode_agen_diskusi)
  {
    // SQL preparation
    $sql = "SELECT 
      kode_agen_diskusi,
      agen_diskusi.kode_konsumen,
      konsumen.nama,
      kode_agen,
      isi_agen_diskusi,
      tanggal_agen_diskusi
      FROM agen_diskusi
      JOIN konsumen
        ON agen_diskusi.kode_konsumen = konsumen.kode_konsumen
      WHERE kode_agen_diskusi = ?
        AND agen_diskusi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen_diskusi,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan jumlah diskusi berdasarkan kode_agen
   * 
   * @param string $kode_agen  Kode agen
   *
   * @return FALSE / Result array
   */
  public function retrieveJumlahDiskusi ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
      COUNT(kode_agen_diskusi) AS jml_diskusi
      FROM agen_diskusi
      WHERE kode_agen = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      "0"
      );

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if (!$query) {
      $error = $this->db->error();
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Menampilkan daftar balasan dari diskusi
   * 
   * @param string $kode_agen_diskusi  Kode diskusi
   *
   * @return FALSE / "EMPTY" / Result array
   */
  public function retrieveBalasanDiskusi ($kode_agen_diskusi)
  {
    // SQL preparation
    $sql = "SELECT 
      kode_agen_diskusi_komentar,
      agen_diskusi_komentar.kode_konsumen,
      konsumen.nama AS nama_konsumen,
      agen_diskusi_komentar.kode_agen,
      agen.nama AS nama_agen,
      kode_agen_diskusi,
      isi_agen_diskusi_komentar,
      tanggal_agen_diskusi_komentar
      FROM agen_diskusi_komentar
      LEFT JOIN konsumen
        ON agen_diskusi_komentar.kode_konsumen = konsumen.kode_konsumen
      LEFT JOIN agen
        ON agen_diskusi_komentar.kode_agen = agen.kode_agen
      WHERE kode_agen_diskusi = ?
        AND agen_diskusi_komentar.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen_diskusi,
      "0"
      );

    // Query execution 
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Retrieve Bank data
   */

  /**
   * Function - Retrieve all data from jenis_bank table
   *
   * @param No params
   * 
   * @return FALSE / result array
   */
  public function retrieveJenisBank ()
  {
    // SQL preparing
    $sql = "SELECT 
      kode_jenis_bank,
      nama_jenis_bank
      FROM jenis_bank";

    // Query executing
    $query = $this->db->query($sql);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

  /**
   * Bank Data - Akun pusat
   */

  /**
   * Function - Retrieve all Central's Bank Account
   *
   * @param No params
   * 
   * @return FALSE / "EMPTY" / result array
   */
  public function retrieveBankPusat ()
  {
    // SQL preparation
    $sql = "SELECT 
      kode_bank_pusat,
      nama_jenis_bank,
      norek,
      atas_nama
      FROM bank_pusat
      JOIN jenis_bank
        ON bank_pusat.kode_jenis_bank = jenis_bank.kode_jenis_bank
      WHERE bank_pusat.hapus = ?";

    // Parameter binding
    $bind_param = array ("0");

    // Query execution
    $query = $this->db->query($sql, $bind_param);
    $error = $this->db->error();

    if (!$query) {
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Check if any branch on spesific city
   *
   * @param string $kode_kota     kode kota 3 karakter
   * 
   * @return FALSE / TRUE
   */
  public function checkBranchExistent ($kode_kota)
  {
    $this->db->where('kode_kota', $kode_kota);    
    $query = $this->db->get('pshcabang');

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Layanan Data
   */
  
  /**
   * Function - Menampilkan layanan yang tersedia berdasarkan kode_agen
   *
   * @param String $kode_agen     Kode Agen
   * 
   * @return [Boolean] [FALSE / "EMPTY" / Result Array]
   */
  public function retrieveLayanan ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT  
      layanan.kode_layanan,
      layanan.kode_jenis_layanan,
      layanan.kode_satuan_layanan,
      layanan_harga.kode_harga_layanan,
      COALESCE(layanan.kode_layanan_grup, 0) AS kode_layanan_grup,
      layanan.nama_layanan,
      layanan.durasi_layanan,
      layanan_harga.harga_layanan,
      COALESCE(layanan_grup.nama_grup, 'ungroup') AS nama_grup
    FROM layanan
    JOIN layanan_harga
      ON layanan.kode_layanan = layanan_harga.kode_layanan
    JOIN agen_layanan_harga
      ON layanan_harga.kode_harga_layanan = agen_layanan_harga.kode_harga_layanan
    LEFT JOIN layanan_grup
    ON layanan.kode_layanan_grup = layanan_grup.kode_layanan_grup
    WHERE agen_layanan_harga.kode_agen = ?
      AND layanan.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      "0"
      );

    // Query execution
    $query = $this->db->query (
      $sql, 
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error ();
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array ();
    }
  }

  /**
   * Dompet Data
   */
  
  /**
   * Function - Menampilkan daftar paket dompet
   * 
   * @return [Boolean / Result] [FALSE / "EMPTY" / Result Array]
   */
  public function retrievePaketDompet ()
  {
    // SQL preparation
    $sql = "SELECT * FROM paket_dompet";

    // Query execution
    $query = $this->db->query ($sql);

    if (!$query) {
      $error = $this->db->error ();
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
   * Kurir data
   */
  
  /**
   * Function - Menampilkan daftar kurir terdekat
   * 
   * @param  String $kode_pshcabang     Kode Cabang
   * @param  Float $lat                 Latitude
   * @param  Float $lng                 Longitude
   * 
   * @return FALSE/"EMPTY"/Array        Result
   */
  public function retrieveNearestKurir (
    $kode_pshcabang,
    $lat,
    $lng
    )
  {
    // SQL preparation
    $sql = "SELECT 
        kode_kurir,
        nama, 
        notelp,
        jk,
        rating,
        foto,
        latitude,
        longitude,
        111.045 * DEGREES(ACOS(COS(RADIANS(?)) /*lat*/
        * COS(RADIANS(latitude))
        * COS(RADIANS(longitude) - RADIANS(?)) /*lng*/
        + SIN(RADIANS(?)) /*lat*/
        * SIN(RADIANS(latitude)))) AS jarak 
      FROM kurir
      WHERE kode_pshcabang = ?
        AND hapus = ?
      ORDER BY jarak ASC";

    // Parameter binding
    $bind_param = array (
      $lat,
      $lng,
      $lat,
      $kode_pshcabang,
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
   * Function - Mencari kurir berdasarkan nama
   * 
   * @param  String $nama                     Nama Kurir
   * 
   * @return [Boolean / String / Array]       [FALSE / "EMPTY" / RESULT]
   */
  public function retrieveKurirByName ($nama)
  {
    // SQL preparation
    $sql = "SELECT 
        kode_kurir,
        nama 
      FROM kurir 
      WHERE kurir.nama LIKE ?
      AND kurir.hapus = ?";

    // Parameter binding
    $bind_param = array (
      "%$nama%",
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error ();
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
   * Function - Menampilkan detil kurir
   * 
   * @param  String $kode_kurir                Kode Kurir
   * 
   * @return [Boolean / String / Array]        [FALSE / "EMPTY" / Result]
   */
  public function retrieveDetailKurir ($kode_kurir)
  {
    // SQL preparation
    $sql = "SELECT * 
      FROM kurir 
      WHERE kurir.kode_kurir = ?
      AND kurir.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_kurir,
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error ();
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
   * Parfum data
   */
  
  /**
   * Function - Retrieve parfum
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveParfum ()
  {
    // SQL preparation
    $sql = "SELECT 
        kode_parfum,
        nama_parfum 
      FROM parfum 
      WHERE parfum.hapus = ?";

    // Parameter binding
    $bind_param = array ("0");

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error ();
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
   * Tarif Antar-Jemput data
   */
  
  public function retrieveTarifAntarJemput ($kode_pshcabang)
  {
    // SQL preparation
    $sql = "SELECT 
        tarif_minimal,
        jarak_minimal,
        tarif_per_km
      FROM tarif_antar_jemput
      WHERE kode_pshcabang = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_pshcabang,
      "0"
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    if (!$query) {
      $error = $this->db->error ();
      return FALSE;
    } elseif ($query->num_rows() < 1) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Quota data
   */
  
  /**
   * Function - Retrieve total quota of agen
   * 
   * @param  String $kode_agen            Kode agen
   * @param  String $kode_jenis_layanan   Kode jenis layanan
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveKuota (
    $kode_agen,
    $kode_jenis_layanan)
  {
    // Query preparation
    $sql = "SELECT kuota
      FROM kuota_layanan
      JOIN kuota_layanan_agen
        ON kuota_layanan_agen.kode_kuota_layanan = kuota_layanan.kode_kuota_layanan
      WHERE kuota_layanan_agen.kode_agen = ?
        AND kuota_layanan.kode_jenis_layanan = ?
        AND kuota_layanan.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      $kode_jenis_layanan,
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
   * Function - Retrieve used quota on current day
   * 
   * @param  String $kode_agen              Kode agen
   * @param  String $kode_jenis_transaksi   Kode jenis transaki
   * 
   * @return Boolean/String/Array           FALSE/"EMPTY"/Result
   */
  public function retrieveKuotaTerpakai (
    $kode_agen,
    $kode_jenis_transaksi
    )
  {
    // Prepare jenis kuota
    switch ($kode_jenis_transaksi) {
      case 1: // Satuan
        // Query preparation
        $sql = "SELECT COALESCE(SUM(jumlah_helai), 0) AS kuota_terpakai
          FROM transaksi_layanan
          JOIN transaksi
            ON transaksi_layanan.kode_transaksi = transaksi.kode_transaksi
          WHERE transaksi.kode_agen = ?
            AND transaksi.tanggal_terima LIKE ?
            AND transaksi.hapus = ?";
        break;

      case 2: // kiloan
        // Query preparation
        $sql = "SELECT COALESCE(SUM(jumlah), 0) AS kuota_terpakai
          FROM transaksi_layanan
          JOIN transaksi
            ON transaksi_layanan.kode_transaksi = transaksi.kode_transaksi
          WHERE transaksi.kode_agen = ?
            AND transaksi.tanggal_terima LIKE ?
            AND transaksi.hapus = ?";
        break;

      case 3: // Luas
        // Query preparation
        $sql = "SELECT COALESCE(SUM(panjang * lebar / 100), 0) AS kuota_terpakai
          FROM transaksi_layanan
          JOIN transaksi
            ON transaksi_layanan.kode_transaksi = transaksi.kode_transaksi
          WHERE transaksi.kode_agen = ?
            AND transaksi.tanggal_terima LIKE ?
            AND transaksi.hapus = ?";
        break;
      
      default:
        # code...
        break;
    }

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      date("Y-m-d") . '%',
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
   * Transaksi data
   */
  
  /**
   * Function - Retrieve all transaction that not have paid yet
   * 
   * @param  String $kode_konsumen      Kode konsumen
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveUnpaidTransaksi ($kode_konsumen)
  {
    // Query execution
    $sql = "SELECT 
        kode_transaksi,
        tanggal_terima,
        tanggal_selesai,
        total 
      FROM transaksi
      WHERE kode_konsumen = ?
        AND status_transaksi NOT IN (?,?,?,?,?,?)
        AND jenis_bayar = ?
        AND status_bayar = ?
        AND hapus = ?
      ORDER BY tanggal_terima DESC";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
      "7", "20", "21", "22", "23", "24",
      "1",
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
   * Function - Retrieve all transactions with transfer payment
   * 
   * @param  String $kode_konsumen    Kode konsumen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveTransaksiWithTransfer ($kode_konsumen)
  {
    // Query execution
    $sql = "SELECT 
        kode_transaksi,
        tanggal_terima,
        tanggal_selesai,
        total 
      FROM transaksi
      WHERE kode_konsumen = ?
        AND status_transaksi IN (?,?,?,?,?,?)
        AND jenis_bayar = ?
        AND hapus = ?
      ORDER BY tanggal_terima DESC";

    // Parameter binding
    $bind_param = array (
      $kode_konsumen,
      "7", "20", "21", "22", "23", "24",
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
   *  Complaint data
   */

  /**
   * Function - Retrieve all complaint by usertype and their code
   * 
   * @param  String $userdata         Tipe user
   * @param  String $kode_user        Kode user
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveComplaint (
    $usertype,
    $kode_user
    )
  {
    switch ($usertype) {
      case 'konsumen':
        $sql = "SELECT
            tp.kode_transaksi_pengaduan,
            tp.kode_transaksi,
            tp.isi_transaksi_pengaduan,
            tp.tanggal_transaksi_pengaduan,
            a.nama AS nama_agen
          FROM transaksi_pengaduan tp
          JOIN transaksi t
            ON tp.kode_transaksi = t.kode_transaksi
          JOIN agen a
            ON t.kode_agen = a.kode_agen
          WHERE t.kode_konsumen = ?
            AND tp.hapus = ?
            AND t.hapus = ?";

        $bind_param = array (
          $kode_user,
          "0",
          "0"
          );
        break;

      case 'agen':
        $sql = "SELECT
            tp.kode_transaksi_pengaduan,
            tp.kode_transaksi,
            tp.isi_transaksi_pengaduan,
            tp.tanggal_transaksi_pengaduan,
            k.nama AS nama_konsumen
          FROM transaksi_pengaduan tp
          JOIN transaksi t
            ON tp.kode_transaksi = t.kode_transaksi
          JOIN konsumen k
            ON t.kode_konsumen = k.kode_konsumen
          WHERE t.kode_agen = ?
            AND tp.hapus = ?
            AND t.hapus = ?";

        $bind_param = array (
          $kode_user,
          "0",
          "0"
          );
        break;

      case 'checker':
        $sql = "SELECT
            tp.kode_transaksi_pengaduan,
            tp.kode_transaksi,
            tp.isi_transaksi_pengaduan,
            tp.tanggal_transaksi_pengaduan,
            k.kode_konsumen,
            k.nama AS nama_konsumen,
            a.kode_agen,
            a.nama AS nama_agen
          FROM transaksi_pengaduan tp
          JOIN transaksi t
            ON tp.kode_transaksi = t.kode_transaksi
          JOIN konsumen k
            ON t.kode_konsumen = k.kode_konsumen
          JOIN agen a
            ON t.kode_agen = a.kode_agen
          WHERE t.kode_agen = ?
            AND tp.hapus = ?
            AND t.hapus = ?";

        $bind_param = array (
          $kode_user,
          "0",
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
   * Function - Retrieve 1 pengaduan of konsumen to agen
   * 
   * @param  String $kode_transaksi_pengaduan     Kode pengaduan
   * 
   * @return Boolean/String/Array                 FALSE/"EMPTY"/Result
   */
  public function retrieveSingleComplaintOnAgen ($kode_transaksi_pengaduan)
  {
    // Query preparation
    $sql = "SELECT
        t.kode_transaksi,
        t.kode_konsumen,
        k.nama AS nama_konsumen,
        t.total,
        c.kode_checker,
        c.nama AS nama_checker,
        tp.isi_transaksi_pengaduan,
        tp.tanggal_transaksi_pengaduan,
        COALESCE(tp.status_transaksi_pengaduan, '0') as status_transaksi_pengaduan
      FROM transaksi t
      JOIN transaksi_pengaduan tp
        ON t.kode_transaksi = tp.kode_transaksi
      JOIN agen a 
        ON t.kode_agen = a.kode_agen
      JOIN checker c
        ON a.kode_checker = c.kode_checker
      JOIN konsumen k
        ON k.kode_konsumen = t.kode_konsumen
      WHERE tp.kode_transaksi_pengaduan = ?
        AND t.hapus = ?
        AND tp.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi_pengaduan,
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
   * Function - Retrieve reply of complaint
   * 
   * @param  String $kode_transaksi_pengaduan     Kode pengaduan
   * 
   * @return Boolean/String/Array                 FALSE/"EMPTY"/Result
   */
  public function retrieveBalasanComplaintOnAgen ($kode_transaksi_pengaduan)
  {
    // Query preparation
    $sql = "SELECT
        tpb.kode_transaksi_pengaduan_balas,
        tpb.kode_agen,
        a.nama AS nama_agen,
        tpb.kode_konsumen,
        k.nama AS nama_konsumen,
        tpb.kode_checker,
        c.nama AS nama_checker,
        tpb.isi_transaksi_pengaduan_balas,
        tpb.waktu_transaksi_pengaduan_balas
      FROM transaksi_pengaduan_balas tpb
      LEFT JOIN checker c 
        ON tpb.kode_checker = c.kode_checker
      LEFT JOIN agen a
        ON a.kode_agen = tpb.kode_agen
      LEFT JOIN konsumen k
        ON k.kode_konsumen = tpb.kode_konsumen
      WHERE tpb.kode_transaksi_pengaduan = ?
        AND tpb.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi_pengaduan,
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