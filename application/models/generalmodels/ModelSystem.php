<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSystem extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  /*public function tes ()
  {
    echo "<script>window.alert('modelsystem')</script>";
  }*/

  /**
   * Function - Check if Konsumen didn't have alamat yet
   *
   * @param no params
   * 
   * @return FALSE / Result as an array
   */
  public function checkAlamat (
    $email,
    $fcm
    )
  {
    $sql = "SELECT ? 
      FROM konsumen
      RIGHT JOIN konsumen_alamat 
        ON konsumen.kode_konsumen = konsumen_alamat.kode_konsumen
      WHERE konsumen.email = ? 
        AND konsumen.fcm = ?";

    $bind_param = array(
      'alamat', 
      $email,
      $fcm);

    $query = $this->db->query($sql, $bind_param);
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Check if Konsumen didn't have bank account yet
   *
   * @param string $email   Email user
   * @param string $fcm     Firebase UID 
   * 
   * @return FALSE / Result as an array
   */
  public function checkRekening (
    $email,
    $fcm
    )
  {
    $sql = "SELECT ? 
      FROM konsumen
      RIGHT JOIN konsumen_bank 
        ON konsumen.kode_konsumen = konsumen_bank.kode_konsumen
      WHERE konsumen.email = ? 
        AND konsumen.fcm = ?";

    $bind_param = array(
      'norek', 
      $email,
      $fcm);

    $query = $this->db->query($sql, $bind_param);
    
    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Check if any request method is empty
   *
   * @param array $requestmethod  request method array
   * 
   * @return $errorFlag (BOOLEAN)
   */
  public function checkRequestMethod (array $requestmethod)
  {
    $errorFlag = FALSE;
    foreach ($requestmethod as $value) {
      if ($value == 0) {
        $errorFlag = TRUE;
      } elseif (empty($value)) {
        $errorFlag = FALSE;
        break;
      } else {
        $errorFlag = TRUE;
      }
    }

    return $errorFlag;
  }

  /**
   * Function - Generating unique code for new assigned user into system
   *
   * @param string $usertype  type of user (konsumen, agen, etc.)
   * @param string $kode_kota 3 characters unique city code
   * 
   * @return $kode_konsumen
   */
  public function userCodeGenerator (
    $usertype,
    $kode_kota
    )
  {
    $errorFlag = FALSE;
    $usertype = strtolower($usertype);
    $prefix = "";
    $kode_kota = strtoupper($kode_kota);
    $uniquenumber = date("ymdHis");
    $microtime = substr(microtime(),2,6);

    switch ($usertype) {
      case 'konsumen':
        $prefix = "KNS/";
        $errorFlag = TRUE;
        break;

      case 'kurir':
        $prefix = "KUR/";
        $errorFlag = TRUE;
        break;

      case 'agen':
        $prefix = "AGN/";
        $errorFlag = TRUE;
        break;

      case 'checker':
        $prefix = "CHK/";
        $errorFlag = TRUE;
        break;

      case 'afiliasi':
        $prefix = "AFL/";
        $errorFlag = TRUE;
        break;
      
      default:
        $errorFlag = FALSE;
        break;
    }

    if (empty($kode_kota)) {
      $errorFlag = FALSE;
    } else {
      $errorFlag = TRUE;
      $kode_kota = $kode_kota."/";
    }
    
    if ($errorFlag == FALSE) {
      return $errorFlag;
    } else {
      $kode_konsumen = $prefix.$kode_kota.$uniquenumber.$microtime;
      return $kode_konsumen; 
    }
  }

  /**
   * Function - Check User existent
   *
   * @param string $usertype  tipe user (konsumen, agen, etc.)
   * @param string $email     email yang masuk ketika sign in
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return TRUE / FALSE
   */
  public function checkUserExistent (
    $usertype,
    $email, 
    $fcm
    )
  {
    $predicate = array (
      'email' => $email,
      'fcm' => $fcm
      );

    $this->db->where($predicate);
    $query = $this->db->get($usertype);

    if ($query->num_rows() < 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function checkFCMIsNotEmpty (
    $usertype,
    $email
    )
  {
    $sql = "SELECT 
      COALESCE(fcm, 0) AS fcm
      FROM $usertype
      WHERE email = ?
        AND hapus = ?";

    $bind_param = array (
      $email,
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
      return TRUE;
    }
  }

  /**
   * Function - Check Email or FCM existent
   *
   * @param string $usertype  tipe user (konsumen, agen, etc.)
   * @param string $email     email yang dimasukkan ketika sign up
   * 
   * @return TRUE / FALSE
   */
  public function checkEmailExistent (
    $usertype,
    $email
    )
  {
    $this->db->where('email', $email);
    $query = $this->db->get($usertype);

    if ($query->num_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Check Email or FCM existent
   *
   * @param string $usertype  tipe user (konsumen, agen, etc.)
   * @param string $fcm       UID / fcm firebase
   * 
   * @return TRUE / FALSE
   */
  public function checkFCMExistent (
    $usertype,
    $fcm
    )
  {
    $this->db->where('fcm', $fcm);
    $query = $this->db->get($usertype);

    if ($query->num_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Add FCM to user table
   *
   * @param string $usertype    User type
   * @param string $email       Email of user
   * @param string $fcm         UID Firebase
   * 
   * @return FALSE / TRUE
   */
  public function addFCM (
    $usertype,
    $email,
    $fcm
    )
  {
    // prepare values
    $values = array (
      'fcm' => $fcm
      );

    $this->db->where('email', $email);
    $this->db->update($usertype, $values);

    if ($this->db->affected_rows() != 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * Function - Retrieve a variable name
   *
   * @param No params
   * 
   * @return FALSE / TRUE
   */
  public function retrieveVariableName (array $requestmethod)
  {
    $varName = FALSE;
    foreach ($requestmethod as $key => $val) {
      if ($key != "fcm") {
        $varName = $key;
        break;
      }
    }

    return $varName;
  }

  /**
   * Avatar upload function
   */

  /**
   * Function - Updating avatar URL of specific user
   *
   * @param string $usertype    User type
   * @param string $email       Email of user
   * @param string $filename    URL of avatar
   * 
   * @return FALSE / TRUE
   */
  public function updateAvatarName (
    $usertype,
    $fcm,
    $filename
    )
  {
    // Prepare error flag
    $errorFlag = FALSE;

    switch ($usertype) {
      case 'konsumen':
        // SQL prepare
        $sql = "UPDATE konsumen
          SET foto = ?
          WHERE fcm = ?";

        // Parameter binding
        $bind_param = array (
          $filename,
          $fcm
          );

        // Query executing
        $query = $this->db->query($sql, $bind_param);

        if ($this->db->affected_rows() < 0) {
          $errorFlag = FALSE;
        } else {
          $errorFlag = TRUE;
        }
        break;

      case 'agen':
        // SQL prepare
        $sql = "UPDATE agen
          SET logo = ?
          WHERE fcm = ?";

        // Parameter binding
        $bind_param = array (
          $filename,
          $fcm
          );

        // Query executing
        $query = $this->db->query($sql, $bind_param);

        if ($this->db->affected_rows() < 0) {
          $errorFlag = FALSE;
        } else {
          $errorFlag = TRUE;
        }
        break;

      case 'kurir':
        // SQL prepare
        $sql = "UPDATE kurir
          SET foto = ?
          WHERE fcm = ?";

        // Parameter binding
        $bind_param = array (
          $filename,
          $fcm
          );

        // Query executing
        $query = $this->db->query($sql, $bind_param);

        if ($this->db->affected_rows() < 0) {
          $errorFlag = FALSE;
        } else {
          $errorFlag = TRUE;
        }
        break;

      case 'afiliasi':
        // SQL prepare
        $sql = "UPDATE afiliasi
          SET foto = ?
          WHERE fcm = ?";

        // Parameter binding
        $bind_param = array (
          $filename,
          $fcm
          );

        // Query executing
        $query = $this->db->query($sql, $bind_param);

        if ($this->db->affected_rows() < 0) {
          $errorFlag = FALSE;
        } else {
          $errorFlag = TRUE;
        }
        break;

      case 'checker':
        // SQL prepare
        $sql = "UPDATE checker
          SET foto = ?
          WHERE fcm = ?";

        // Parameter binding
        $bind_param = array (
          $filename,
          $fcm
          );

        // Query executing
        $query = $this->db->query($sql, $bind_param);

        if ($this->db->affected_rows() < 0) {
          $errorFlag = FALSE;
        } else {
          $errorFlag = TRUE;
        }
        break;
      
      default:
        # code...
        break;
    }

    return $errorFlag;
  }

  /**
   * Token data
   */
  
  /**
   * Function - Retrieve Agen's token
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveTokenAgen (
    $usertype,
    $kode
    )
  {
    // Query preparation by usertype
    switch ($usertype) {
      case 'agen':
        $sql = "SELECT token
          FROM agen
          WHERE kode_agen = ?
            AND hapus = ?";
        break;

      case 'konsumen':
        $sql = "SELECT token
          FROM konsumen
          WHERE kode_konsumen = ?
            AND hapus = ?";
        break;

      case 'kurir':
        $sql = "SELECT token
          FROM kurir
          WHERE kode_kurir = ?
            AND hapus = ?";
        break;

      case 'checker':
        $sql = "SELECT token
          FROM checker
          WHERE kode_checker = ?
            AND hapus = ?";
        break;
      
      default:
        # code...
        break;
    }
    
    // Binding parameter
    $bind_param = array (
      $kode,
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
    } elseif ($query->result_array()[0]['token'] === NULL) {
      return "EMPTY";
    } else {
      return $query->result_array();
    }
  }

  /**
   * Function - Retrieve konsumen token by transaction code
   * 
   * @param  String $usertype         Tipe user
   * @param  String $kode_transaksi   Kode transaksi
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveTokenByTransaksi (
    $usertype,
    $kode_transaksi
    )
  {
    // Set usertype as a lower string
    $usertype = strtolower($usertype);

    switch ($usertype) {
      case 'agen':
        // Query preparation
        $sql = "SELECT
          token
          FROM agen
          JOIN transaksi
            ON agen.kode_agen = transaksi.kode_agen
          WHERE kode_transaksi = ?
            AND agen.hapus = ?
            AND transaksi.hapus = ?";
        break;

      case 'konsumen':
        // Query preparation
        $sql = "SELECT
          token
          FROM konsumen
          JOIN transaksi
            ON konsumen.kode_konsumen = transaksi.kode_konsumen
          WHERE kode_transaksi = ?
            AND konsumen.hapus = ?
            AND transaksi.hapus = ?";
        break;
      
      default:
        # code...
        break;
    }

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
   * Function - Retrieve token list by kode pengaduan
   * 
   * @param  Integer $kode_transaksi_pengaduan    Kode pengaduan
   * 
   * @return Boolean/String/Array                 FALSE/"EMPTY"/Result
   */
  public function retrieveTokenByPengaduan ($kode_transaksi_pengaduan)
  {
    // Query preparation
    $sql = "SELECT 
        a.token AS token_agen,
        k.token AS token_konsumen
      FROM agen a
      JOIN transaksi t
        ON t.kode_agen = a.kode_agen
      JOIN konsumen k
        ON t.kode_konsumen = k.kode_konsumen
      JOIN transaksi_pengaduan tp
        ON t.kode_transaksi = tp.kode_transaksi
      WHERE tp.kode_transaksi_pengaduan = ?
        AND tp.hapus = ?";

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