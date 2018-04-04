<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerSystem extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Controller System. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Check if Konsumen didn't have alamat yet
   *
   * @param no params
   * 
   * @return JSON data
   */
  public function checkAlamat ()
  {
    // TEST
    // $_POST['email'] = "firmanslash@gmail.com";
    // $_POST['fcm'] = "ZDTRSQwlD6WFd7itaKyKbntXAIb2";

    // Prepare POST data
    $email = $this->input->post('email');
    $fcm = $this->input->post('fcm');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => ""
      );

    // Check if Request Variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Request variable is empty.";
    } else {
      // Check if Alamat Konsumen is available
      $result = $this->ModelSystem->checkAlamat (
        $email,
        $fcm
        );

      if ($result == FALSE) {
        $data['messages'] = "Alamat Konsumen is empty.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Alamat Konsumen is available.";
      } 
    }

    echo json_encode($data);
  }

  /**
   * Function - Check if Konsumen didn't have bank account yet
   *
   * @param string $email   Email user
   * @param string $fcm     Firebase UID 
   * 
   * @return JSON data
   */
  public function checkRekening ()
  {
    // Prepare POST data
    $email = $this->input->post('email');
    $fcm = $this->input->post('fcm');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => ""
      );

    // Check if Request Variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Request variable is empty.";
    } else {
      // Check if Rekening Konsumen is available
      $result = $this->ModelSystem->checkRekening (
        $email,
        $fcm
        );

      if ($result == FALSE) {
        $data['messages'] = "Rekening Konsumen is empty.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Rekening Konsumen is available.";
      } 
    }

    echo json_encode($data);
  }

  /**
   * Province data
   */

  /**
   * Function - Retrieving all Province data
   *
   * @param No Params
   * 
   * @return JSON data
   */
  public function retrieveProvinsi ()
  {
    // Preparing JSON data
    $data = array(
      'success' => FALSE,
      'messages' => NULL
      );

    // Retrieve all Kota data
    $result['data'] = $this->ModelSystemRetriever->retrieveProvinsi ();
    
    if ($result['data'] == FALSE) {
      $data['messages'] = "The table is empty, please wait for the maintenance process.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve Provinsi table is success.";
      $data['data'] = $result['data'];
    }

    // Return value
    echo json_encode($data);
  }

  /**
   * Function - Retrieving all Kota data by kode_provinsi
   *
   * @param string $kode_provinsi     Kode dari tabel Provinsi
   * 
   * @return JSON data
   */
  public function retrieveKotaByProvinsi ()
  {
    // Preparing request variable
    $kode_provinsi = $this->input->post('kode_provinsi');

    // Preparing JSON data
    $data = array(
      'success' => FALSE,
      'messages' => NULL
      );

    // Retrieve all Kota data
    $result['data'] = $this->ModelSystemRetriever->retrieveKotaByProvinsi ($kode_provinsi);
    
    if ($result['data'] == FALSE) {
      $data['messages'] = "The table is empty, please wait for the maintenance process.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve Kota by Provinsi table is success.";
      $data['data'] = $result['data'];
    }

    // Return value
    echo json_encode($data);
  }

  /**
   * Kota data
   */

  /**
   * Function - Retrieve all Kota data
   *
   * @param no params
   * 
   * @return JSON data
   */
  public function retrieveKota ()
  {
    // Preparing JSON data
    $data = array(
      'success' => NULL,
      'messages' => NULL
      );

    // Retrieve all Kota data
    $result['data'] = $this->ModelSystemRetriever->retrieveKota ();
    
    if ($result['data'] == FALSE) {
      $data['success'] = FALSE;
      $data['messages'] = "The table is empty, please wait for the maintenance process.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve Kota table is success.";
      $data['data'] = $result['data'];
    }

    // Return value
    echo json_encode($data);
  }

  /**
   * Kecamatan data
   */

  /**
   * Function - Retrieve Kecamatan data by kode kota
   *
   * @param string $kode_kota     Kode dari tabel kota
   * 
   * @return JSON data
   */
  public function retrieveKecamatanByKota ()
  {
    // Preparing method variable
    $kode_kota = $this->input->post('kode_kota');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request method is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve kecamatan by kode kota
      $result = $this->ModelSystemRetriever->retrieveKecamatanByKota ($kode_kota);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Kecamatan.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Kecamatan is success.";
        $data['data'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Kelurahan data
   */

  /**
   * Function - Retrieve Kelurahan data by kode kecamatan
   *
   * @param string $kode_kecamatan     Kode dari tabel kecamatan
   * 
   * @return JSON data
   */
  public function retrieveKelurahanByKecamatan ()
  {
    // Preparing request variable
    $kode_kecamatan = $this->input->post('kode_kecamatan');

    // Preparing JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve Kelurahan by Kode Kecamatan
      $result = $this->ModelSystemRetriever->retrieveKelurahanByKecamatan ($kode_kecamatan);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Kelurahan.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Kelurahan is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data);
  }

  /**
   * Kodepos data
   */

  /**
   * Function - Retrieve Kodepos data by kode kelurahan
   *
   * @param string $kode_kelurahan     Kode dari tabel kelurahan
   * 
   * @return JSON data
   */
  public function retrieveKodeposByKelurahan ()
  {
    // Preparing request variable
    $kode_kelurahan = $this->input->post('kode_kelurahan');

    // Preparing JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve kodepos by kelurahan
      $result = $this->ModelSystemRetriever->retrieveKodeposByKelurahan ($kode_kelurahan);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Kodepos.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Kodepos is success.";
        $data['row'] = $result;
      }
      
    }
    
    echo json_encode($data);
  }

  /**
   * Agen data
   */

  /**
   * Function - Retrieve all Agen data
   *
   * @param String $lat    Konsumen's latitude
   * @param String $lng    Konsumen's longitude
   * 
   * @return JSON data
   */
  public function retrieveAgenList ()
  {
    // Prepare request variable
    $lat = $this->input->post('lat');
    $lng = $this->input->post('lng');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Retrive all Agen data
    $result = $this->ModelSystemRetriever->retrieveAgenList (
      $lat, 
      $lng
      );

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving agen list, please wait for maintenance";
    } elseif ($result == "EMPTY") {
      $data['messages'] = "Agen list is unavailable yet.";
    } else {
      foreach ($result as $key => $row) {
        $kode_agen = $row['kode_agen'];

        $order_sukses = $this->ModelSystemRetriever->retrieveOrderSuksesAgen ($kode_agen);

        if ($order_sukses == FALSE) {
          $jml_order_sukses = "Error while retrieving order sukses.";
        } elseif ($order_sukses == "EMPTY") {
          $jml_order_sukses = 0;
        } else {
          $jml_order_sukses = $order_sukses;
        }

        $result[$key]['order_sukses'] = $jml_order_sukses;

        // Retrieve order_ditolak
        $order_ditolak = $this->ModelSystemRetriever->retrieveOrderDitolakAgen ($kode_agen);

        if ($order_ditolak == FALSE) {
          $jml_order_ditolak = "Error while retrieving order ditolak.";
        } elseif ($order_ditolak == "EMPTY") {
          $jml_order_ditolak = 0;
        } else {
          $jml_order_ditolak = $order_ditolak;
        }

        $result[$key]['order_ditolak'] = $jml_order_ditolak;
      }

      $data['success'] = TRUE;
      $data['messages'] = "Retrieve agen list success.";
      $data['row'] = $result;
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Retrieve Agen detail
   *
   * @param string $kode_agen   Kode Agen
   * 
   * @return JSON data
   */
  public function retrieveAgenDetail ()
  {
    // Prepare Request variable
    $kode_agen = $this->input->post('kode_agen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      $result['row'] = $this->ModelSystemRetriever->retrieveAgenDetil ($kode_agen);

      if ($result['row'] == FALSE) {
        $data['messages'] = "Agen detail is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Agen detail is available.";
        $data['row'] = $result['row'];
      }      
    }
    
    echo json_encode($data);
  }

  /**
   * Bank data
   */

  /**
   * Function - Retrieve all data from jenis_bank table
   *
   * @param No params
   * 
   * @return JSON data
   */
  public function retrieveJenisBank ()
  {
    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Query executing
    $result = $this->ModelSystemRetriever->retrieveJenisBank ();

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving Bank data.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve Bank data successful.";
      $data['row'] = $result;
    }

    echo json_encode($data);
  }

  /**
   * Branch data
   */

  /**
   * Function - Check if any branch on spesific city
   *
   * @param string $kode_kota     kode kota 3 karakter
   * 
   * @return JSON(data)
   */
  public function checkBranchExistent ()
  {
    // Declare GET variables
    $kode_kota = $this->input->get('kode_kota');

    // Preparing JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if any branch on spesific city
    $result = $this->ModelSystemRetriever->checkBranchExistent ($kode_kota);

    if ($result == FALSE) {
      $data['messages'] = "Sorry, no branch available.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Branch is available.";
    }

    // Return value
    echo json_encode($data);
  }

}