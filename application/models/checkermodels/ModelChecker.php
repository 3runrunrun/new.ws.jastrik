<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelChecker extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Create new Checker
   *
   * @param string $kode_checker     kode checker yang sudah digenerate
   * @param string $kode_pshcabang   kode cabang tempat checker mendaftar
   * @param string $nama             nama checker
   * @param string $alamat           alamat checker
   * @param string $notelp           nomor telepon checker 
   * @param string $tanggal_lahir    tanggal lahir checker
   * @param string $email            email checker
   * @param string $fcm              fcm / UID firebase
   * @param string $tanggal_daftar   tanggal daftar checker
   * @param string $file_ktp         URL file ktp checker
   * @param string $file_kk          URL file kk checker
   * @param string $kodepos          kodepos checker
   * @param string $foto             URL foto checker
   * 
   * @return TRUE / FALSE
   */
  public function createChecker (
    $kode_checker,
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
    $values = array (
      'kode_checker' => $kode_checker,
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
      'foto' =>$foto
      );

    $this->db->insert('checker', $values);

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Retrieve Checker's profile
   *
   * @param string $email     email checker
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return FALSE / Array of query result
   */
  public function retrieveCheckerProfile ($email,
    $fcm)
  {
    $predicate = array(
      'email' => $email,
      'fcm' => $fcm
      );

    $this->db->where($predicate);
    $query = $this->db->get('checker');

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }  
  }

  /**
   * Function - Create deposit withdrawal request
   * 
   * @param  String $kode_agen_setoran_dana       Kode setoran dana agen
   * @param  String $kode_agen                    Kode agen
   * @param  String $kode_checker                 Kode checker
   * @param  Float $nominal_agen_setoran_dana    Nominal setoran yang akan ditagih
   * 
   * @return Boolean
   */
  public function requestDepositWithdrawal (
    $kode_agen_setoran_dana,
    $kode_agen,
    $kode_checker,
    $nominal_agen_setoran_dana
    )
  {
    // Query preparation
    $sql = "INSERT INTO agen_setoran_dana
      (kode_agen_setoran_dana,
      kode_agen,
      kode_checker,
      nominal_agen_setoran_dana,
      tanggal_agen_setoran_dana,
      status_agen_setoran_dana,
      hapus) VALUES (?,?,?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_agen_setoran_dana,
      $kode_agen,
      $kode_checker,
      $nominal_agen_setoran_dana,
      date("Y-m-d H:i:s"),
      "0",
      "0"
      );

    // Transaction begining
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
   * Function - Check transaction / Update checked status
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * @param  String $catatan            Catatan jika diperlukan
   * 
   * @return Boolean
   */
  public function updateCheckTransaksi (
    $kode_transaksi,
    $catatan = NULL
    )
  {
    // Query preparation
    $sql = "UPDATE transaksi
      SET checked = ?,
        catatan = ?
      WHERE kode_transaksi = ?
        AND hapus = ?";

    // Parameter binding
    $bind_param = array (
      "1",
      $catatan,
      $kode_transaksi,
      "0"
      );

    // Transaction begining
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

  ////////////////////
  // Inventory agen //
  ////////////////////

  /**
   * Function - Create order invetory
   * 
   * @param  String $kode_checker_order_inventory Kode order
   * @param  String $kode_checker                 Kode checker
   * @param  String $kode_agen                    Kode agen
   * @param  Float  $total                        Total tagihan order
   * 
   * @return Boolean
   */
  public function createOrderInventory (
    $kode_checker_order_inventory,
    $kode_checker,
    $kode_agen,
    $total
    )
  {
    // Query preparation
    $sql = "INSERT INTO checker_order_inventory
      (kode_checker_order_inventory,
      kode_checker,
      kode_agen,
      tanggal_checker_order_inventory,
      status_checker_order_inventory,
      hapus,
      total) VALUES (?,?,?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_checker_order_inventory,
      $kode_checker,
      $kode_agen,
      date("Y-m-d H:i:s"),
      "0",
      "0",
      $total
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
   * Function - Create item list of order inventory
   * 
   * @param  String $kode_checker_order_inventory Kode order
   * @param  String $kode_inventory_harga         Kode harga inv
   * @param  Float  $harga                        Harga inv
   * @param  Float  $jumlah                       Jumlah inv dipesan
   * 
   * @return Boolean
   */
  public function createOrderInventoryItem (
    $kode_checker_order_inventory,
    $kode_inventory_harga,
    $harga,
    $jumlah
    )
  {
    // Query preparation
    $sql = "INSERT INTO detail_checker_order_inventory
      VALUES (?,?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_checker_order_inventory,
      $kode_inventory_harga,
      $harga,
      $jumlah,
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
   * Function - Rollbacking order inventory
   * 
   * @param  String $kode_checker_order_inventory Kode pemesanan inv
   * 
   * @return Boolean
   */
  public function undoOrderInventory ($kode_checker_order_inventory)
  {
    // Query preparation
    $sql = "DELETE checker_order_inventory
      WHERE kode_checker_order_inventory = ?
      AND hapus = ?";

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

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Update agen's inventory stock
   * 
   * @param  String $kode_history_inventory   Kode history inv
   * @param  String $kode_agen                Kode agen
   * @param  String $kode_inventory           Kode inventory
   * @param  String $kode_checker             Kode checker
   * @param  Double $stok_keluar              Stok keluar
   * 
   * @return Boolean
   */
  public function updateAgenInventoryStock (
    $kode_history_inventory,
    $kode_agen,
    $kode_inventory,
    $kode_checker,
    $stok_keluar
    )
  {
    // Query A preparation
    $sqlA = "INSERT INTO agen_history_inventory
      (kode_history_inventory,
      kode_agen,
      kode_inventory,
      kode_checker,
      stok_keluar,
      tanggal_history_inventory,
      hapus) VALUES (?,?,?,?,?,?,?)";
    $bind_paramA = array (
      $kode_history_inventory,
      $kode_agen,
      $kode_inventory,
      $kode_checker,
      $stok_keluar,
      date("Y-m-d H:i:s"),
      "0"
      );

    // Query B preparation
    $sqlB = "UPDATE agen_inventory
      SET jml_stok = jml_stok - ?
      WHERE kode_agen = ?
        AND kode_inventory = ?
        AND hapus = ?";
    $bind_paramB = array (
      $stok_keluar,
      $kode_agen,
      $kode_inventory,
      "0"
      );

    // Query execution
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
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
   * Function - Scanning qr_code visit / absen
   * 
   * @param  String  $enkripsi               Enkripsi
   * @param  String  $kode_agen_absen        Kode agen absen
   * @param  String  $kode_agen              kode agen
   * @param  String  $kode_checker           Kode checker
   * @param  Integer $transaksi_masuk        transaksi masuk
   * @param  Integer $transaksi_dikerjakan   transaksi dikerjakan
   * @param  Integer $transaksi_selesai      transaksi selesai
   * @param  Integer $pengaduan_tuntas       pengaduan tuntas
   * 
   * @return Boolean
   */
  public function scanQRAbsen (
    $enkripsi,
    $kode_agen_absen,
    $kode_agen,
    $kode_checker,
    $transaksi_masuk,
    $omzet_masuk,
    $transaksi_dikerjakan,
    $transaksi_selesai,
    $pengaduan_tuntas
    )
  {
    // Query A preparation
    $sqlA = "UPDATE agen_temp_kode_absen
      SET scan = ?
      WHERE enkripsi = ?";
    $bind_paramA = array (
      "1",
      $enkripsi
      );

    // Query B preparation
    $sqlB = "INSERT INTO agen_absen
      (kode_agen_absen,
      kode_agen,
      kode_checker,
      tanggal_agen_absen,
      transaksi_masuk,
      omzet_masuk,
      transaksi_dikerjakan,
      transaksi_selesai,
      pengaduan_tuntas,
      hapus) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $bind_paramB = array (
      $kode_agen_absen,
      $kode_agen,
      $kode_checker,
      date("Y-m-d H:i:s"),
      $transaksi_masuk,
      $omzet_masuk,
      $transaksi_dikerjakan,
      $transaksi_selesai,
      $pengaduan_tuntas,
      "0"
      );

    // Query execution
    $this->db->trans_begin();

    $this->db->query(
      $sqlA,
      $bind_paramA
      );
    $this->db->query(
      $sqlB,
      $bind_paramB
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