<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKonsumenSystem extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Konsumen System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Menampilkan tagihan pembelian saldo dompet konsumen
   *
   * @param String $kode_konsumen     Kode konsumen
   * 
   * @return [JSON]
   */     
  public function retrieveArrears ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

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
      // Retrieve Arrears
      $result = $this->ModelKonsumenSystem->retrieveArrears ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving arrears, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "You don't have any arrears.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve arrears is success.";
        $data['row'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  public function checkIfTransactionAccepted ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod === FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Cek apakah transaksi diterima agen
      $resultIfAccepted = $this->ModelKonsumenSystem->checkIfTransactionAccepted ($kode_transaksi);

      if ($resultIfAccepted == FALSE) {
        $data['messages'] = "Error while checking status, please wait for maintenance.";
      } elseif ($resultIfAccepted == "EMPTY") {
        $data['messages'] = "Transaction is unavailable, make sure you did a transaction first.";
      } else {
        foreach ($resultIfAccepted as $item) {
          $kode_transaksi = $item['kode_transaksi'];
          $kode_konsumen = $item['kode_konsumen'];
          $kode_agen = $item['kode_agen'];
          $jenis_bayar = $item['jenis_bayar'];
          $total = $item['total'];
          $jenis_antar = $item['jenis_antar'];
          $jenis_jemput = $item['jenis_jemput'];
          $status_transaksi = $item['status_transaksi'];
        } 

        if ($status_transaksi == "1") {
          $data['success'] = TRUE;
          $data['messages'] = "Transaction accepted by Agen.";
        } else {
          // Cancel transaction
          $resultCancel = $this->ModelKonsumenTransaksi->cancelTransaction (
            $kode_transaksi,
            $kode_konsumen,
            $jenis_antar,
            $jenis_jemput,
            $total,
            $jenis_bayar
            );

          if ($resultCancel == FALSE) {
            $data['messages'] = "Error while canceling transaction, please wait for maintenance.";
          } else {
            $data['messages'] = "Transaction canceled.";

            // Prepare notification payload
            $resultCanceledLayanan = $this->ModelAgenSystem->retrieveCanceledLayanan ($kode_transaksi);
            $layanan['detail'] = $resultCanceledLayanan;
            $notifPayload = $layanan['detail'];

            // Retrieve konsumen's token
            $resultToken = $this->ModelSystem->retrieveTokenAgen (
              "konsumen",
              $kode_konsumen
              );

            if ($resultToken == FALSE) {
              $data['messages'] = $data['messages'] . " Konsumen won't notified. (errTok01)";
            } elseif ($resultToken == "EMPTY") {
              $data['messages'] = $data['messages'] . " Konsumen won't notified. (errTok02)";
            } else {
              $token = $resultToken[0]['token'];

              // Prepare payload
              $encodedPayload = json_encode ($notifPayload);

              $this->load->library('envelope');
              $this->load->library('firebase');

              $this->envelope->setTitle ("Informasi Transaksi");
              $this->envelope->setMessage ("Hi! Transaksi anda telah ditanggapi oleh agen.");
              $this->envelope->setData (
                  array (
                      "title" => $this->envelope->getTitle(),
                      "message" => $this->envelope->getMessage(),
                      "timestamp" => date("Y-m-d H:i:s"),
                      "type" => "expired_transaksi",
                      "data" => $encodedPayload
                    )
              );

              $jasPayload = $this->envelope->getData ();

              $result = $this->firebase->sendDataSingle (
                  'https://fcm.googleapis.com/fcm/send',
                  $token,
                  $jasPayload
                );
            }

            $data['success'] = TRUE;
          }
        }
      }
    }
    
    echo json_encode($data);
  }

}