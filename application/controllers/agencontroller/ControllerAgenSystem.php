<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAgenSystem extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Agen's System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Retrieve daftar konsumen berdasarkan nama atau notelp
   * 
   * @param Strin $myParam    Berisi nilai "nama" atau "notelp"
   * 
   * @return JSON data
   */
  public function retrieveKonsumen ()
  {
    // Prepare request variable
    $myParam = $this->input->post('myParam');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve konsumen
      $result = $this->ModelAgenSystem->retrieveKonsumen ($myParam);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving konsumen data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Konsumen data are unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['row'] = $result;
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Check if Konsumen's balance (saldo) is sufficient
   *
   * @param $kode_konsumen      Kode konsumen
   * @param $harga_bayar        Harga bayar
   * 
   * @return JSON
   */
  public function checkIfSaldoSufficient ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');
    $harga_bayar = $this->input->post('harga_bayar');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve saldo konsumen
      $result = $this->ModelKonsumenDompet->retrieveSaldoDompet ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Saldo Konsumen, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Saldo data is unavailable.";
      } else {
        $saldo_dompet = $result[0]['saldo_dompet'];

        if ($saldo_dompet < $harga_bayar) {
          $data['success'] = TRUE;
          $data['messages'] = "Saldo konsumen tidak mencukupi.";
          $data['row'][] = array ("info_saldo" => "INSUFFICIENT");
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Saldo konsumen mencukupi.";
          $data['row'][] = array ("info_saldo" => "SUFFICIENT");
        }
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Check if qr_transaksi has been scanned
   * 
   * @param  String $kode_qr_transaksi      kode_qr_transaksi
   * 
   * @return JSON
   */
  public function checkIfScanned ()
  {
    // Prepare request variable
    $kode_qr_transaksi = $this->input->post('kode_qr_transaksi');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Check if qr_transaksi has been scanned
      $result = $this->ModelAgenSystem->checkIfScanned ($kode_qr_transaksi);

      if ($result === FALSE) {
        $data['messages'] = "Error while scanning qrcode, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "The qrcode is unavailable.";
      }else {
        $isScanned = $result[0]['scan'];

        if ($isScanned == 1) {
          $data['row'][] = array ("isScanned" => TRUE);
        } else {
          $data['row'][] = array ("isScanned" => FALSE);
        }

        $data['success'] = TRUE;
        $data['messages'] = "Checking qrcode is success.";
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Check if qr absen scanned 
   * 
   * @param  String $kode_agen_temp_kode_absen    kode qr_absen
   * 
   * @return JSON
   */
  public function checkIfQRAbsenScanned ()
  {
    // Prepare request variable
    $kode_agen_temp_kode_absen = $this->input->post('kode_agen_temp_kode_absen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request method is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Create deposit withdrawal request
      $result = $this->ModelAgenSystem->checkIfQRAbsenScanned ($kode_agen_temp_kode_absen);

      if ($result == FALSE) {
        $data['messages'] = "Error while checking data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['success'] = "Data unavailable.";
      } else {
        $isScanned = $result[0]['scan'];

        if ($isScanned == 1) {
          $data['row'][] = array ("isScanned" => TRUE);
        } else {
          $data['row'][] = array ("isScanned" => FALSE);
        }

        $data['success'] = TRUE;
        $data['messages'] = "Checking qrcode is success.";
      }  
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Edit agen's nama
   * 
   * @param  String $kode_agen    Kode agen
   * @param  String $nama         Nama agen
   * 
   * @return JSON
   */
  public function updateNama ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $nama = $this->input->post('nama');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' =>NULL
      );

    // Check if request variable method is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      $result = $this->ModelAgenSystem->updateNama (
        $kode_agen,
        $nama
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while updating name, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Update success.";
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Edit agen's slogan
   * 
   * @param  String $kode_agen    Kode agen
   * @param  String $slogan       Slogan agen
   * 
   * @return JSON
   */
  public function updateSlogan ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $slogan = $this->input->post('slogan');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' =>NULL
      );

    // Check if request variable method is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      $result = $this->ModelAgenSystem->updateSlogan (
        $kode_agen,
        $slogan
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while updating slogan, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Update success.";
      }
    }

    echo json_encode($data);
  }

}