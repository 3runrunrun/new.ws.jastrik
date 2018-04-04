<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKurirSystem extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Kurir System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
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
    $nominal_bayar = $this->input->post('nominal_bayar');

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

        if ($saldo_dompet < $nominal_bayar) {
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
   * Function - Check if qr_transaksi_kurir has been scanned
   * 
   * @param  String $kode_qr_transaksi_kurir      kode_qr_transaksi_kurir
   * 
   * @return JSON
   */
  public function checkIfScanned ()
  {
    // Prepare request variable
    $kode_qr_transaksi_kurir = $this->input->post('kode_qr_transaksi_kurir');

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
      $result = $this->ModelKurirSystem->checkIfScanned ($kode_qr_transaksi_kurir);

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

}