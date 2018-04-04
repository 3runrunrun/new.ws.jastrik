<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKonsumenProfile extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /**
   * Function - Retrieve Konsumen's profile
   *
   * @param string $email     email
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return FALSE / Array of query result
   */
  public function retrieveKonsumenProfile (
    $email,
    $fcm
    )
  {
    $predicate = array(
      'email' => $email,
      'fcm' => $fcm
      );

    $this->db->where($predicate);
    $query = $this->db->get('konsumen');

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }  
  }

  /**
   * Function - Update Konsumen's Profile
   *
   * @param string $fcm          fcm / UID firebase
   * @param string $fieldname    column name
   * @param string $newvalue     new data
   * 
   * @return FALSE / TRUE
   */
  public function updateKonsumenProfile (
    $fcm,
    $fieldname,
    $newvalue
    )
  {
    // Prepare values
    $values = array (
      $fieldname => $newvalue
      );

    // Where condition and do update
    $this->db->where ('fcm', $fcm);
    $this->db->update ('konsumen', $values);
    // $this->db->get_compiled_update();

    // Check if query is success
    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
  * Konsumen's Alamat management
  */

  /**
   * Function - Create Konsumen's Alamat
   *
   * @param string $kode_konsumen     Kode Konsumen
   * @param string $kode_kota         Kode Kota
   * @param string $alamat            Alamat Konsumen
   * @param string $kelurahan         Nama Kelurahan
   * @param string $kecamatan         Nama Kecamatan
   * @param string $kodepos           Kodepos
   * @param string $def               Default Value (1 or 0)
   * 
   * @return FALSE / Latest Inserted ID
   */
  public function createAlamat (
    $kode_konsumen,
    $kode_kota,
    $alamat,
    $kelurahan,
    $kecamatan,
    $kodepos,
    $def
    )
  {
    $values = array (
      'kode_konsumen' => $kode_konsumen,
      'kode_kota' => $kode_kota,
      'alamat' => $alamat,
      'kelurahan' => $kelurahan,
      'kecamatan' => $kecamatan,
      'kodepos' => $kodepos,
      'def' => $def
      );

    $this->db->insert(
      'konsumen_alamat',
      $values
      );

    $insert_id = $this->db->insert_id();

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return $insert_id;
    }
  }

  /**
   * Function - Update Konsumen's Alamat
   *
   * @param string $kode_konsumen_alamat    Kode alamat konsumen
   * @param string $kode_kota               Kode kota
   * @param string $alamat                  Alamat konsumen
   * @param string $kelurahan               Nama Kelurahan
   * @param string $kecamatan               Nama Kecamatan
   * @param string $kodepos                 Kodepos
   * 
   * @return FALSE / TRUE
   */
  public function updateAlamat (
    $kode_konsumen_alamat,
    $kode_kota,
    $alamat,
    $kelurahan,
    $kecamatan,
    $kodepos
    )
  {
    $predicate = array ('kode_konsumen_alamat' => $kode_konsumen_alamat);
    $values = array (
      'kode_kota' => $kode_kota,
      'alamat' => $alamat,
      'kelurahan' => $kelurahan,
      'kecamatan' => $kecamatan,
      'kodepos' => $kodepos
      );

    $this->db->where($predicate);
    $this->db->update(
      'konsumen_alamat',
      $values);

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Change default address
   * 
   * @param  Int $kode_konsumen_alamat_new     Kode alamat default baru
   * @param  Int $kode_konsumen_alamat_old     Kode alamat default lama (opsional)
   * 
   * @return Boolean
   */
  public function updateDefaultAlamat (
    $kode_konsumen_alamat_new,
    $kode_konsumen_alamat_old = NULL
    )
  {
    // Query preparation
    $sql = "UPDATE konsumen_alamat
      SET def = ?
      WHERE kode_konsumen_alamat = ?
        AND hapus = ?";

    // Parameter binding for new default address
    $bind_paramA = array (
      "1",
      $kode_konsumen_alamat_new,
      "0"
      );

    // Parameter binding for old default address
    $bind_paramB = array (
      "0",
      $kode_konsumen_alamat_old,
      "0"
      );

    // Query execution begin
    $this->db->trans_begin();

    $this->db->query(
      $sql,
      $bind_paramA
      );

    if ($kode_konsumen_alamat_old != NULL) {
      $this->db->query(
        $sql,
        $bind_paramB
        );
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  /**
   * Function - Delete Konsumen's Alamat
   *
   * @param string $kode_konsumen_alamat    Kode alamat konsumen
   * 
   * @return FALSE / TRUE
   */
  public function deleteAlamat ($kode_konsumen_alamat)
  {
    $predicate = array ('kode_konsumen_alamat' => $kode_konsumen_alamat);

    $this->db->where($predicate);
    $this->db->delete('konsumen_alamat');

    if ($this->db->affected_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Retrieve all Konsumen's Alamat data
   *
   * @param string $kode_konsumen   Kode konsumen
   * 
   * @return FALSE / result array
   */
  public function retrieveAlamat ($kode_konsumen)
  {
    // Prepare SQL 
        $sql = "SELECT 
          konsumen_alamat.kode_konsumen_alamat,
          kota.nama_kota,
          konsumen_alamat.alamat,
          konsumen_alamat.kelurahan,
          konsumen_alamat.kecamatan,
          konsumen_alamat.kodepos,
          konsumen_alamat.def
        FROM konsumen_alamat
        JOIN kota
          ON kota.kode_kota = konsumen_alamat.kode_kota
        WHERE kode_konsumen = ?";

        // Parameter binding
        $bind_param = array ($kode_konsumen);

        $query = $this->db->query($sql, $bind_param);
        
        if ($query->num_rows() < 1) {
          return FALSE;
        } else {
          return $query->result_array();
        }
  }

  /**
  * Konsumen's Bank Account management
  */

  /**
   * Function - Insert new bank account for kode konsumen
   *
   * @param string $kode_jenis_bank   Kode jenis bank
   * @param string $kode_konsumen     Kode konsumen
   * @param string $norek             Nomor rekening
   * @param string $atas_nama         Atas nama nomor rekening
   *
   * @return FALSE / TRUE
   */
  public function createRekening (
    $kode_jenis_bank,
    $kode_konsumen,
    $norek,
    $atas_nama
    )
  {
    // Prepare SQL
    $sql = "INSERT INTO konsumen_bank 
      (kode_jenis_bank, 
      kode_konsumen, 
      norek, 
      atas_nama)
      VALUES (?,?,?,?)";

    // Parameter binding
    $bind_param = array (
      $kode_jenis_bank,
      $kode_konsumen,
      $norek,
      $atas_nama
      );

    // Query execution
    $query = $this->db->query(
      $sql,
      $bind_param
      );

    $insert_id = $this->db->insert_id();

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return $insert_id;
    }
  }

  /**
   * Function - Editing bank account of Konsumen
   *
   * @param string $kode_konsumen_bank    Kode dari tabel konsumen_bank
   * @param string $kode_jenis_bank       Kode dari tabel jenis_bank
   * @param string $norek                 Nomor rekening
   * @param string $atas_nama             Atas nama nomor rekening
   *
   * @return FALSE / TRUE
   */
  public function updateRekening (
    $kode_konsumen_bank,
    $kode_jenis_bank,
    $norek,
    $atas_nama
    )
  {
    // Preparing SQL 
    $sql = "UPDATE konsumen_bank
      SET kode_jenis_bank = ?,
      norek = ?,
      atas_nama = ?
      WHERE kode_konsumen_bank = ?";

    // Parameter binding
    $bind_param = array (
      $kode_jenis_bank,
      $norek,
      $atas_nama,
      $kode_konsumen_bank
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
   * Function - Deleting bank account of Konsumen
   *
   * @param string $kode_konsumen_bank    Kode dari tabel konsumen_bank
   *
   * @return FALSE / TRUE
   */
  public function deleteRekening ($kode_konsumen_bank)
  {
    // SQL preparing
    $sql = "DELETE FROM konsumen_bank 
      WHERE kode_konsumen_bank = ?";

    // Parameter binding
    $bind_param = array ($kode_konsumen_bank);

    // Query execution
    $query = $this->db->query($sql, $bind_param);

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Retrieve all Konsumen's Bank Account data
   *
   * @param string $kode_konsumen   Kode konsumen
   * 
   * @return FALSE / result array
   */
  public function retrieveRekening ($kode_konsumen)
  {
    $this->db->from('konsumen_bank');
    $this->db->join('jenis_bank', 
      'konsumen_bank.kode_jenis_bank = jenis_bank.kode_jenis_bank');
    $this->db->where('kode_konsumen', $kode_konsumen);
    $query = $this->db->get();
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return $query->result_array();
    }
  }

}