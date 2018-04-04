<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAgenRetriever extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - menampilkan data transaksi yang belum di accept oleh agen
   * 
   * @param  [String] $kode_agen            [Kode agen]
   * @param  [String] $status_transaksi     [Status Transaksi]
   * 
   * @return [Boolean/String/Array]         [FALSE/"EMPTY"/Result]
   */
  public function retrieveTransactionMilestone (
    $kode_agen,
    $status_transaksi
    )
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_transaksi,
        konsumen.nama,
        transaksi.tanggal_terima,
        transaksi.status_transaksi,
        CASE 
          WHEN transaksi.jenis_antar = '0' && transaksi.jenis_jemput = '1' THEN 'jemput'
          WHEN transaksi.jenis_antar = '1' && transaksi.jenis_jemput = '0' THEN 'antar'
          WHEN transaksi.jenis_antar = '1' && transaksi.jenis_jemput = '1' THEN 'antar jemput'    
          WHEN transaksi.jenis_antar = '0' && transaksi.jenis_jemput = '0' THEN 'ambil sendiri'
        END AS tipe_antar_jemput
      FROM transaksi
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      WHERE transaksi.kode_agen = ?
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?
      ORDER BY transaksi.tanggal_terima DESC";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      } 
    }
  } 

  /**
   * Function - menampilkan transaksi yang sudah selesai
   * 
   * @param  [String] $kode_agen            [Kode agen]
   * 
   * @return [Boolean/String/Array]         [FALSE/"EMPTY"/Result]
   */
  public function retrieveTransactionComplete ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_transaksi,
        konsumen.nama,
        transaksi.tanggal_terima,
        transaksi.tanggal_selesai,
        transaksi.total
      FROM transaksi
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      WHERE transaksi.kode_agen = ?
        AND transaksi.status_transaksi IN ?
        AND transaksi.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      // array ("1"),
      array ("11"),
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
   * Function - Menampilkan daftar kurir yang terasosiasi dengan agen
   * 
   * @param  String $kode_agen              Kode Agen
   * 
   * @return [Boolean / String / Array]     [FALSE / "EMPTY" / Result]
   */
  public function retrieveKurirAgen ($kode_agen)
  {
    // SQL preparation
    $sql = "SELECT 
        kurir_agen.kode_kurir,
        kurir.nama,
        kurir.latitude,
        kurir.longitude,
        kurir.foto,
        kurir.rating 
      FROM kurir_agen
      JOIN kurir
        ON kurir_agen.kode_kurir = kurir.kode_kurir
      WHERE kurir_agen.kode_agen = ?
        AND kurir_agen.hapus = ?
        AND kurir.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      } 
    } 
  }

  /**
   * Function - Retrieve all item data
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveItem ()
  {
    // Query preparation
    $sql = "SELECT 
      kode_item,
      nama_item
      FROM item
      WHERE hapus = ?";

    // Parameter binding
    $bind_param = array ("0");

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
   * Function - Retrieve all item data by name
   * 
   * @param  String $nama_item        [description]
   * 
   * @return Boolean/String/Result            [description]
   */
  /*public function retrieveItemByName ($nama_item)
  {
    // Query preparation
    $sql = "SELECT
      kode_item,
      nama_item
      FROM item
      WHERE nama_item LIKE ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      "%$nama_item%",
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
  }*/

  /**
   * Function - Retrieve kode dana and kode agen by kode transaksi
   * 
   * @param  String $kode_transaksi       Kode transaksi
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveTotalAndKodeAgen ($kode_transaksi)
  {
    // Query preparation
    $sql = "SELECT 
        kode_agen,
        total
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
   * Function - Retrieve dana agen
   * 
   * @param  String $kode_agen           Kode agen
   * 
   * @return Boolean/String/Array        FALSE/"EMPTY"/Result
   */
  public function retrieveDanaAgen ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT dana_agen
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

  /////////////////
  // Fee Summary //
  /////////////////

  /**
   * Function - Retrieve income summary
   * 
   * @param  String  $kode_agen     Kode agen
   * @param  String  $sort          Tipe sorting
   * @param  Integer $from          Dari
   * @param  Integer $to            Ke
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveRekapFeeAgen (
    $kode_agen,
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
            SUM(tl.harga * COALESCE(lh.persentase_fee_agen, 0.1)) AS pendapatan
          FROM transaksi_layanan tl
          JOIN transaksi t
            ON t.kode_transaksi = tl.kode_transaksi
          JOIN layanan_harga lh
            ON tl.kode_harga_layanan = lh.kode_harga_layanan
          WHERE t.status_transaksi = ?
            AND t.kode_agen = ?
            AND YEAR(t.tanggal_selesai) BETWEEN ? AND ?
            AND t.hapus = ?
          GROUP BY YEAR(t.tanggal_selesai)
          ORDER BY t.tanggal_selesai DESC";
        break;
      
      case 'bulan':
        // Query preparation
        $sql = "SELECT
            DATE_FORMAT(t.tanggal_selesai, '%M %Y') AS bulan,
            SUM(tl.harga * COALESCE(lh.persentase_fee_agen, 0.1)) AS pendapatan
          FROM transaksi_layanan tl
          JOIN transaksi t
            ON t.kode_transaksi = tl.kode_transaksi
          JOIN layanan_harga lh
            ON tl.kode_harga_layanan = lh.kode_harga_layanan
          WHERE t.status_transaksi =?
            AND t.kode_agen = ?
            AND DATE_FORMAT(t.tanggal_selesai, '%Y-%m')  BETWEEN ? AND ?
            AND t.hapus = ?
          GROUP BY MONTHNAME(t.tanggal_selesai)
          ORDER BY t.tanggal_selesai DESC";
        break;

      case 'hari':
        // Query preparation
        $sql = "SELECT 
            t.tanggal_selesai AS tanggal,
            SUM(tl.harga * COALESCE(lh.persentase_fee_agen, 0.1)) AS pendapatan
          FROM transaksi_layanan tl
          JOIN transaksi t
            ON t.kode_transaksi = tl.kode_transaksi
          JOIN layanan_harga lh
            ON tl.kode_harga_layanan = lh.kode_harga_layanan
          WHERE t.status_transaksi = ?
            AND t.kode_agen = ?
            AND t.tanggal_selesai BETWEEN ? AND ?
            AND t.hapus = ?
          GROUP BY t.tanggal_selesai
          ORDER BY t.tanggal_selesai DESC";
        break;

      default:
        # code...
        break;
    }

    // Parameter binding
    $bind_param = array (
      "11",
      $kode_agen,
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

  /////////////////////////////
  // Setoran dana ke checker //
  /////////////////////////////

  /**
   * Function - Retrieve last deposit request
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveLastDepositRequest ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT 
        kode_agen_setoran_dana,
        asd.kode_checker,
        c.nama,
        asd.tanggal_agen_setoran_dana
      FROM agen_setoran_dana asd
      JOIN checker c
        ON c.kode_checker = asd.kode_checker
      WHERE asd.kode_agen = ?
        AND asd.status_agen_setoran_dana = ?
        AND asd.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
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
   * Function - Retrieve history setoran dana agen
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveHistorySetoranDana ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT
        asd.kode_agen_setoran_dana,
        asd.kode_checker,
        c.kode_checker,
        c.nama,
        asd.tanggal_agen_setoran_dana,
        asd.nominal_agen_setoran_dana,
        asd.status_agen_setoran_dana
      FROM agen_setoran_dana asd
      JOIN checker c
        ON asd.kode_checker = c.kode_checker
      WHERE asd.kode_agen = ?
        AND asd.hapus = ?";

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

  ///////////////////////////////
  // Summary Pendapatan Agen   //
  ///////////////////////////////

  /**
   * Function - Retrieve all total fee since beginning of membership
   * 
   * @param  String $kode_agen      Kode agen
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveTotalFee ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT 
        COALESCE(SUM(tl.harga * COALESCE(lh.persentase_fee_agen, 0.1))) AS pendapatan
      FROM transaksi_layanan tl
      JOIN transaksi t
        ON t.kode_transaksi = tl.kode_transaksi
      JOIN layanan_harga lh
        ON tl.kode_harga_layanan = lh.kode_harga_layanan
      WHERE t.status_transaksi = ?
        AND t.kode_agen = ?
        AND t.hapus = ?";

    // Parameter binding
    $bind_param = array (
      "11",
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
   * Function - Retrieve total payed off fee
   * 
   * @param  String $kode_agen      Kode agen
   * 
   * @return Boolean/String/Array   FALSE/"EMPTY"/Result
   */
  public function retrieveTotalPayedOffFee ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT
        COALESCE(SUM(apf.nominal), 0) AS nominal
      FROM agen_pencairan_fee apf
      WHERE kode_agen = ?
        AND status_agen_pencairan_fee = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
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
   * Function - Check if any withdrawal request
   * 
   * @param  String $kode_agen      Kode agen
   * 
   * @return Boolean/String/Array
   */
  public function retrieveLastWithdrawal ($kode_agen)
  {
    // Query preparation
    $sql = "SELECT 
        apf.kode_agen_pencairan_fee
        apf.tanggal_request,
        apf.nominal
      FROM agen_pencairan_fee apf
      WHERE apf.kode_agen = ?
        AND apf.status_agen_pencairan_fee NOT IN ?
        AND apf.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_agen,
      array('2', '3'),
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

  ///////////////////////////////
  // Detail transaksi konsumen //
  ///////////////////////////////

  /**
   * Function - menampilkan informasi dasar untuk detil transaksi
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]           [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveRingkasanPesanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
       transaksi.kode_transaksi,
       transaksi.tanggal_terima,
       transaksi.tanggal_selesai,
       transaksi.status_transaksi,
       transaksi.total,
       transaksi.status_bayar
      FROM transaksi
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan identitas konsumen
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveIdentitasPesanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.kode_konsumen,
        konsumen.nama AS nama_konsumen,
        transaksi.kode_agen,
        agen.nama AS nama_agen,
        kurir_jemput.nama AS nama_kurir_jemput,
        kurir_antar.nama AS nama_kurir_antar
      FROM transaksi
      JOIN konsumen
        ON transaksi.kode_konsumen = konsumen.kode_konsumen
      JOIN agen 
        ON transaksi.kode_agen = agen.kode_agen
      LEFT JOIN transaksi_jemput
        ON transaksi.kode_transaksi = transaksi_jemput.kode_transaksi
      LEFT JOIN kurir AS kurir_jemput
        ON transaksi_jemput.kode_kurir = kurir_jemput.kode_kurir
      LEFT JOIN transaksi_antar
        ON transaksi.kode_transaksi = transaksi_antar.kode_transaksi
      LEFT JOIN kurir AS kurir_antar
        ON transaksi_jemput.kode_kurir = kurir_antar.kode_kurir
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?
        AND konsumen.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan identitas kurir penjemput
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveStatusPesanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi.status_transaksi
      FROM transaksi
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        $status_pesanan = $query->result_array();
        return $status_pesanan[0]['status_transaksi'];
      }
    }
  }

  /**
   * Function - menampilkan layanan
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveLayanan ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi_layanan.kode_transaksi_layanan,
        layanan.nama_layanan
      FROM transaksi_layanan
      JOIN transaksi
        ON transaksi_layanan.kode_transaksi = transaksi.kode_transaksi
      JOIN layanan_harga
        ON transaksi_layanan.kode_harga_layanan = layanan_harga.kode_harga_layanan
      JOIN layanan
        ON layanan.kode_layanan = layanan_harga.kode_layanan
      WHERE transaksi_layanan.kode_transaksi = ?
        AND transaksi_layanan.hapus = ?
        AND transaksi.hapus = ?
        AND layanan_harga.hapus = ?
        AND layanan.hapus = ?";

    // Parameter binding
    $bind_param = array (
      $kode_transaksi,
      "0",
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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan item
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveItemDetailTransaksi ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        transaksi_layanan.kode_transaksi_layanan,
        item.nama_item,
        transaksi_item.jumlah
      FROM transaksi_item
      JOIN transaksi_layanan
        ON transaksi_item.kode_transaksi_layanan = transaksi_layanan.kode_transaksi_layanan
      JOIN item
        ON item.kode_item = transaksi_item.kode_item
      WHERE transaksi_layanan.kode_transaksi = ?
        AND transaksi_item.hapus = ?
        AND transaksi_layanan.hapus = ?
        AND item.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  /**
   * Function - menampilkan informasi pembayaran
   * 
   * @param  [String] $kode_transaksi   [Kode transaksi]
   * 
   * @return [Boolean/Array]            [FALSE/"EMPTY"/Result Array]
   */
  public function retrieveInformasiPembayaran ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        COALESCE(transaksi.jenis_bayar, 0) AS jenis_bayar,
        COALESCE(transaksi.subtotal, 0) AS subtotal,
        COALESCE(transaksi.diskon, 0) AS diskon,
        COALESCE(transaksi.biaya_antar, 0) AS biaya_antar,
        COALESCE(transaksi.pajak, 0) AS pajak,
        COALESCE(transaksi.total, 0) AS total,
        COALESCE(transaksi.bayar, 0) AS bayar,
        COALESCE(transaksi.kembalian, 0) AS kembalian,
        COALESCE(transaksi.status_bayar, 0) AS status_bayar
      FROM transaksi
      WHERE transaksi.kode_transaksi = ?
        AND transaksi.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }

  public function retrieveStatusKonfirmasiTransfer ($kode_transaksi)
  {
    // SQL preparation
    $sql = "SELECT
        jenis_bayar, 
        status_bayar,
        COALESCE(tbt.foto, 0) AS foto,
        COALESCE(tbt.status_transaksi_bayar_transfer, 'tbd') AS status_transaksi_bayar_transfer
      FROM transaksi t
      LEFT JOIN transaksi_bayar_transfer tbt
        ON t.kode_transaksi = tbt.kode_transaksi
      WHERE t.kode_transaksi = ?
        AND t.hapus = ?";

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
    } else {
      if ($query->num_rows() < 1) {
        return "EMPTY";
      } else {
        return $query->result_array();
      }
    }
  }
}