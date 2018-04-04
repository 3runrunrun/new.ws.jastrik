<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerSystemComplaint extends CI_Controller {

  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Complaint System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Create new pengaduan of a transaksi
   * 
   * @param  String $kode_transaksi               Kode transaksi
   * @param  String $isi_transaksi_pengaduan      Isi pengaduan
   * 
   * @return JSON
   */
  public function createComplaint ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $isi_transaksi_pengaduan = $this->input->post('isi_transaksi_pengaduan');

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
      // Create new complaint
      $result = $this->ModelSystemComplaint->createComplaint (
        $kode_transaksi,
        $isi_transaksi_pengaduan
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while creating complaint, please wait for maintenance.";
      } else {
        // Retrieve agen token by transaksi
        $resultToken = $this->ModelSystem->retrieveTokenByTransaksi (
          "agen",
          $kode_transaksi
          );

        if ($resultToken == FALSE) {
          $data['messages'] = "Complaint created, but agen won't notified. (#errTok1)";
        } elseif ($resultToken == "EMPTY") {
          $data['messages'] = "Complaint created, but agen won't notified. (#errTok2)";
        } else {
          $token = $resultToken[0]['token'];

          // Prepare payload
          $notifPayload = array (
            "kode_transaksi" => $kode_transaksi,
            "isi_transaksi_pengaduan" => $isi_transaksi_pengaduan
            );
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Anda Mendapatkan Pengaduan Baru");
          $this->envelope->setMessage ("Hi! Konsumen memberikan pengaduan terhadap anda. Mohon tanggapi dengan bijak.");
          $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "complaint",
                  "data" => $encodedPayload
                )
            );

          $jasPayload = $this->envelope->getData ();

          $resultFirebase = $this->firebase->sendDataSingle (
              'https://fcm.googleapis.com/fcm/send',
              $token,
              $jasPayload
            );

          $data['messages'] = "Your complaint added successfuly.";
        }

        $data['success'] = TRUE;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Reply complain
   * 
   * @param  String $kode_agen                          Kode agen
   * @param  String $kode_konsumen                      Kode konsumen
   * @param  String $kode_checker                       Kode checker
   * @param  String $kode_transaksi_pengaduan           Kode pengaduan
   * @param  String $isi_transaksi_pengaduan_balas      Kode transaksi pengaduan balas
   * 
   * @return JSON
   */
  public function createBalasanComplain ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen'); 
    $kode_konsumen = $this->input->post('kode_konsumen'); 
    $kode_checker = $this->input->post('kode_checker');
    $kode_transaksi_pengaduan = $this->input->post('kode_transaksi_pengaduan'); 
    $isi_transaksi_pengaduan_balas = $this->input->post('isi_transaksi_pengaduan_balas');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] =  "Please fill your form completely.";
    } else {
      // Create balasan review
      $result = $this->ModelSystemComplaint->createBalasanComplain (
        $kode_agen, 
        $kode_konsumen, 
        $kode_checker,
        $kode_transaksi_pengaduan, 
        $isi_transaksi_pengaduan_balas
        );

      if ($result == FALSE) {
        $data['messages'] =  "Error while inserting new comment, please wait for maintenance";
      } else {
        // Result
        $tokenResult = $this->ModelSystem->retrieveTokenByPengaduan($kode_transaksi_pengaduan);

        if ($tokenResult == FALSE) {
          $data['messages'] = "Error while retrieving token. Commentar replied but user won't notified. (errTok#1)";
        } elseif ($tokenResult == "EMPTY") {
          $data['messages'] = "Token unavailable. Commentar replied but user won't notified. (errTok#2)";
        } else {
          $registration_ids = array();

          foreach ($tokenResult as $key) {
            array_push($registration_ids, $key['token_agen']);
            array_push($registration_ids, $key['token_konsumen']);
          }

          // Prepare payload
          $notifPayload = array (
            "kode_transaksi_pengaduan" => $kode_transaksi_pengaduan,
            "isi_transaksi_pengaduan_balas" => $isi_transaksi_pengaduan_balas
            );
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Balasan Pengaduan");
          $this->envelope->setMessage ($isi_transaksi_pengaduan_balas);
          $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "balasan_pengaduan",
                  "data" => $encodedPayload
                )
          );

          $jasPayload = $this->envelope->getData ();

          $resultNotif = $this->firebase->sendToMany (
              'https://fcm.googleapis.com/fcm/send',
              $registration_ids,
              $jasPayload
            );

          $data['messages'] = "Your comment added successfuly.";
        }
        
        $data['success'] = TRUE;
        $data['row'][] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /** 
   * Function - Delete complain's comment
   * 
   * @param  Integer $kode_transaksi_pengaduan_balas   Kode balasan pengaduan
   * 
   * @return JSON
   */
  public function deleteComplainComment()
  {
    // Prepare request variable
    $kode_transaksi_pengaduan_balas = $this->input->post('kode_transaksi_pengaduan_balas');

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
      // Create new complaint
      $result = $this->ModelSystemComplaint->deleteComplainComment ($kode_transaksi_pengaduan_balas);

      if ($result == FALSE) {
        $data['messages'] = "Error while delete complain's commentar, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Delete comment is success.";
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Set complain as solved
   * 
   * @param  String $kode_transaksi_pengaduan   Kode pengaduan
   * 
   * @return JSON
   */
  public function updateStatusComplain ()
  {
    // Prepare request variable
    $kode_transaksi_pengaduan = $this->input->post('kode_transaksi_pengaduan');

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
      // Create new complaint
      $result = $this->ModelSystemComplaint->updateStatusComplain ($kode_transaksi_pengaduan);

      if ($result == FALSE) {
        $data['messages'] = "Error while solving complain, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Complain solved.";
      }
    }
    
    echo json_encode($data);
  }
}