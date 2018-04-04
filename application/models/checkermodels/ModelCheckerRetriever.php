<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class ModelCheckerRetriever extends CI_Model {



  public function __construct ()

  {

    parent::__construct();

    date_default_timezone_set('Asia/Jakarta');

  }



  /**

   * Function - Retrieve all supervised agen

   * 

   * @param  String $kode_checker          Kode checker

   * 

   * @return Boolean/String/Array          FALSE/"EMPTY"/Result

   */

  public function retrieveAllSupervisedAgen ($kode_checker)

  {

    // Query preparation

    $sql = "SELECT

        a.kode_agen,

        a.nama,

        DATE_FORMAT(CURDATE(), '%Y-%m-%d') AS tanggal,

        COUNT(t.kode_transaksi) AS jml_transaksi_masuk,

        tb.jml_transaksi_dikerjakan,

        COALESCE(tc.jml_transaksi_harus_selesai, 0) AS jml_transaksi_harus_selesai

      FROM agen a

      JOIN transaksi t

        ON t.kode_agen = a.kode_agen

      JOIN (SELECT 

          tt.kode_agen,

          COUNT(tt.kode_transaksi) AS jml_transaksi_dikerjakan

        FROM transaksi tt

        WHERE tt.status_transaksi = '6') tb

        ON tb.kode_agen = t.kode_agen 

      LEFT JOIN (SELECT 

          ttt.kode_agen,

          COUNT(ttt.kode_transaksi) AS jml_transaksi_harus_selesai

        FROM transaksi ttt

        WHERE ttt.tanggal_selesai LIKE CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'), '%')) tc

        ON tc.kode_agen = t.kode_agen

      WHERE a.kode_checker = ?

        AND t.tanggal_terima LIKE ?

        AND t.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker,

      date("Y-m-d") . "%",

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

    } elseif ($query->result_array()[0]['kode_agen'] === NULL) {

      return "EMPTY";

    } else {

      return $query->result_array();

    }

  }



  /**

   * Function - Retrieve visit history

   *  

   * @param  String $kode_checker       Kode checker

   * 

   * @return Booelan/String/Array       FALSE/"EMPTY"/Result

   */

  public function retrieveVisitHistory (

    $kode_checker,

    $kode_agen

    )

  {

    // Query preparation

    $sql = "SELECT 

        aa.kode_agen_absen,

        aa.kode_agen,

        a.nama AS nama_agen,

        aa.tanggal_agen_absen

      FROM agen_absen aa

      JOIN agen a

        ON aa.kode_agen = a.kode_agen

      WHERE aa.kode_checker = ?

        AND aa.kode_agen = ?

        AND aa.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker,

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

   * Function - Retrieve detail of visit history

   * 

   * @param  String $kode_agen_absen      Kode absen / visit

   * 

   * @return Boolean/String/Array         FALSE/"EMPTY"/Result

   */

  public function retrieveVisitHistoryDetail ($kode_agen_absen)

  {

    // Query preparation

    $sql = "SELECT 

        aa.kode_agen_absen,

        aa.kode_agen,

        a.nama AS nama_agen,

        aa.tanggal_agen_absen,

        aa.transaksi_masuk,

        aa.transaksi_dikerjakan,

        aa.transaksi_selesai,

        aa.pengaduan_tuntas

      FROM agen_absen aa

      JOIN agen a

        ON aa.kode_agen = a.kode_agen

      WHERE aa.kode_agen_absen = ?

        AND aa.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_agen_absen,

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



  // Summary agen performance



  public function retrieveJumlahTransaksiMasuk ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT COUNT(kode_transaksi) AS jml_transaksi_masuk

      FROM transaksi

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



  public function retrieveJumlahTransaksiHarusSelesai ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(kode_transaksi) AS jml_transaksi_harus_selesai

      FROM transaksi

      WHERE tanggal_selesai LIKE CONCAT(DATE_FORMAT(CURDATE(), '%Y-%m-%d'), '%')

        AND kode_agen = ?

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



  public function retrieveJumlahTransaksiDikerjakan ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(kode_transaksi) AS jml_transaksi_dikerjakan

      FROM transaksi 

      WHERE status_transaksi = ?

        AND kode_agen = ?

        AND hapus = ?";



    // Parameter binding

    $bind_param = array (

      "6",

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



  public function retrieveJumlahPengaduanMasuk ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(tp.kode_transaksi_pengaduan) AS jumlah_pengaduan

      FROM transaksi_pengaduan tp

      JOIN transaksi t

        ON t.kode_transaksi = tp.kode_transaksi

      WHERE t.kode_agen = ?

        AND t.hapus = ?";



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



  public function retrieveJumlahPengaduanUnsolved ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(tp.kode_transaksi_pengaduan) AS jumlah_pengaduan

      FROM transaksi_pengaduan tp

      JOIN transaksi t

        ON t.kode_transaksi = tp.kode_transaksi

      WHERE tp.status_transaksi_pengaduan = ?

        AND t.kode_agen = ?

        AND t.hapus = ?";



    // Parameter binding

    $bind_param = array (

      "0",

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



  // End of - Summary agen performance





  // Dana Agen

  

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



  // End of - Dana Agen



  /**

   * Function - retrieve uncompleted and uncheceked transaction

   * 

   * @param  String $kode_agen      Kode agen

   * 

   * @return Boolean/String/Array   FALSE/"EMPTY"/Result

   */

  public function retrieveUncheckedTransaction ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

        t.kode_transaksi,

        t.kode_konsumen,

        t.kode_agen,

        a.nama,

        t.tanggal_terima,

        t.tanggal_selesai,

        t.status_transaksi

      FROM transaksi t

      JOIN agen a

        ON t.kode_agen = a.kode_agen

      WHERE t.kode_agen = ?

        AND t.tanggal_selesai LIKE ?

        AND t.checked = ?

        AND t.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_agen,

      date("Y-m-d") . "%",

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

   * Function - Retrieve all inventory per cabang

   * 

   * @param  String $kode_pshcabang     Kode cabang

   * 

   * @return Boolean/String/Result      FALSE/"EMPTY"/Array

   */

  public function retrieveInventory ($kode_pshcabang)

  {

    // Query preparation

    $sql = "SELECT

        i.kode_inventory,

        ih.kode_inventory_harga,

        i.nama_inventory,

        sl.nama_satuan_layanan,

        ih.harga_inventory

      FROM inventory i

      JOIN inventory_harga ih

        ON i.kode_inventory = ih.kode_inventory

      JOIN satuan_layanan sl

        ON i.kode_satuan_layanan = sl.kode_satuan_layanan

      WHERE ih.kode_pshcabang = ?

        AND i.hapus = ?";



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

      $error = $this->db->error();

      return FALSE;

    } elseif ($query->num_rows() < 1) {

      return "EMPTY";

    } else {

      return $query->result_array();

    }

  }



  /**

   * Function - Retrieve all inventory 

   * 

   * @return Boolean/String/Result      FALSE/"EMPTY"/Array

   */

  public function retrieveAllInventory ()

  {

    // Query preparation

    $sql = "SELECT

        i.kode_inventory,

        ih.kode_inventory_harga,

        i.nama_inventory

      FROM inventory i

      JOIN inventory_harga ih

        ON i.kode_inventory = ih.kode_inventory

      WHERE i.hapus = ?";



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

   * Function - Retrieve last order inventory

   * 

   * @param  String $kode_checker     Kode checker

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveLastOrderInventory (

    $kode_checker,

    $kode_agen

    )

  {

    // Query preparation

    $sql = "SELECT

        coi.kode_checker_order_inventory,

        coi.status_checker_order_inventory

      FROM checker_order_inventory coi

      WHERE coi.kode_checker = ?

        AND coi.kode_agen = ?

        AND coi.status_checker_order_inventory NOT IN ?

        AND coi.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker,

      $kode_agen,

      array ('3','11','12','13'),

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

   * Function - Retrieve active order inventory

   * 

   * @param  String $kode_checker     Kode checker

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveActiveOrderInventory ($kode_checker)

  {

    // Query preparation

    $sql = "SELECT

        coh.kode_checker_order_inventory,

        coh.kode_agen,

        a.nama,

        coh.tanggal_checker_order_inventory,

        coh.status_checker_order_inventory

      FROM checker_order_inventory coh

      JOIN agen a

        ON coh.kode_agen = a.kode_agen

      WHERE coh.kode_checker = ?

        AND coh.status_checker_order_inventory NOT IN ?

        AND coh.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker,

      array ('3','11','12','13'),

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

   * Function - Retrieve summary of order inventory

   * 

   * @param  String $kode_checker_order_inventory     Kode order

   * 

   * @return Boolean/String/Array                     FALSE/"EMPTY"/Result

   */

  public function retrieveSummaryOrderInventory ($kode_checker_order_inventory)

  {

    // Query preparation

    $sql = "SELECT

        coi.kode_checker_order_inventory,

        coi.kode_checker,

        coi.kode_agen,

        a.nama,

        coi.tanggal_checker_order_inventory,

        coi.total,

        coi.status_checker_order_inventory

      FROM checker_order_inventory coi

      JOIN agen a 

        ON coi.kode_agen = a.kode_agen

      WHERE coi.kode_checker_order_inventory = ?

        AND coi.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker_order_inventory,

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

   * Function - Retrieve summary of order inventory

   * 

   * @param  String $kode_checker_order_inventory     Kode order

   * 

   * @return Boolean/String/Array                     FALSE/"EMPTY"/Result

   */

  public function retrieveDetailOrderInventory ($kode_checker_order_inventory)

  {

    // Query preparation

    $sql = "SELECT

        dcoi.kode_inventory_harga,

        ih.kode_inventory,

        i.nama_inventory,

        dcoi.harga,

        dcoi.jumlah

      FROM detail_checker_order_inventory dcoi

      JOIN inventory_harga ih

        ON dcoi.kode_inventory_harga = ih.kode_inventory_harga

      JOIN inventory i

        ON ih.kode_inventory = i.kode_inventory

      WHERE dcoi.kode_checker_order_inventory = ?

        AND dcoi.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker_order_inventory,

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

   * Function - Retrieve rekap aktivitas visit checker

   * 

   * @param  String $kode_checker     Kode checker

   *                                  

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveActivityHistory ($kode_checker)

  {

    // Query preparation

    $sql = "SELECT

        aa.kode_agen_absen,

        aa.tanggal_agen_absen,

        aa.kode_agen,

        a.nama

      FROM agen_absen aa

      JOIN agen a

        ON aa.kode_agen = a.kode_agen

      WHERE aa.kode_checker = ?

        AND aa.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_checker,

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

   * Function - Retrieve history of order inventory per agen

   * 

   * @param  String $kode_agen       Kode agen

   * 

   * @return Boolean/String/Array    FALSE/"EMPTY"/Result

   */

  public function retrieveOrderInventoryHistory ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

        coi.kode_checker_order_inventory,

        coi.tanggal_checker_order_inventory

      FROM checker_order_inventory coi

      WHERE coi.kode_agen = ?

        AND coi.hapus = ?";



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



  ////////////////////

  // Inventory agen //

  ////////////////////



  /**

   * Function - Retrieve agen's inventories stock

   * 

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveAgenInventoryStock  ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

        ai.kode_inventory,

        i.nama_inventory,

        ai.jml_stok

      FROM agen_inventory ai

      JOIN inventory i

        ON ai.kode_inventory = i.kode_inventory

      WHERE ai.kode_agen = ?

        AND ai.hapus = ?";



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



  ///////////

  // Absen //

  ///////////



  /**

   * Function - Retrieve jumlah transaksi masuk pada hari visit

   * 

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveJumlahTransaksiMasukAbs ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(t.kode_transaksi) AS jml_transaksi_masuk

      FROM transaksi t

      WHERE (t.tanggal_terima BETWEEN 

        (SELECT

          aa.tanggal_agen_absen

          FROM agen_absen aa

          WHERE aa.kode_agen = ?

          ORDER BY aa.tanggal_agen_absen DESC

          LIMIT 1)

          AND CURDATE()) 

        AND t.kode_agen = ?

        AND t.status_transaksi IN ?

        AND t.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_agen,

      $kode_agen,

      array ('0', '1', '2', '3', '4', '5'),

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

   * Function - Retrieve jumlah omzet masuk pada hari visit

   * 

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */
  public function retrieveOmzetMasukAbs ($kode_agen)
  {

    // Query preparation

    $sql = "SELECT

      SUM(t.total - t.biaya_antar) AS omzet_masuk

      FROM transaksi t

      WHERE (t.tanggal_terima BETWEEN 

        (SELECT

          aa.tanggal_agen_absen

          FROM agen_absen aa

          WHERE aa.kode_agen = ?

          ORDER BY aa.tanggal_agen_absen DESC

          LIMIT 1)

          AND CURDATE()) 

        AND t.kode_agen = ?

        AND t.status_transaksi IN ?

        AND t.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_agen,

      $kode_agen,

      array ('0', '1', '2', '3', '4', '5'),

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

   * Function - Retrieve jumlah transaksi dikerjakan pada hari visit

   * 

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveJumlahTransaksiDikerjakanAbs ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(kode_transaksi) AS jml_transaksi_dikerjakan

      FROM transaksi 

      WHERE status_transaksi = ?

        AND kode_agen = ?

        AND hapus = ?";



    // Parameter binding

    $bind_param = array (

      "6",

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

   * Function - Retrieve jumlah transaksi selesai pada hari visit

   * 

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveJumlahTransaksiSelesaiAbs ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(kode_transaksi) AS jml_transaksi_selesai

      FROM transaksi 

      WHERE status_transaksi IN ?

        AND kode_agen = ?

        AND hapus = ?";



    // Parameter binding

    $bind_param = array (

      array ('7', '8', '9', '10', '11', '12'),

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

   * Function - Retrieve jumlah pengaduan tuntas pada hari visit

   * 

   * @param  String $kode_agen        Kode agen

   * 

   * @return Boolean/String/Array     FALSE/"EMPTY"/Result

   */

  public function retrieveJumlahSolvedComplainAbs ($kode_agen)

  {

    // Query preparation

    $sql = "SELECT

      COUNT(tp.kode_transaksi_pengaduan) AS jml_pengaduan_tuntas

      FROM transaksi_pengaduan tp

      JOIN transaksi t

        ON tp.kode_transaksi = t.kode_transaksi

      WHERE (tp.tanggal_transaksi_pengaduan BETWEEN 

        (SELECT

          aa.tanggal_agen_absen

          FROM agen_absen aa

          WHERE aa.kode_agen = ?

          ORDER BY aa.tanggal_agen_absen DESC

          LIMIT 1)

          AND CURDATE()) 

        AND t.kode_agen = ?

        AND t.hapus = ?";



    // Parameter binding

    $bind_param = array (

      $kode_agen,

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