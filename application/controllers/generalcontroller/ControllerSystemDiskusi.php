<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerSystemDiskusi extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Diskusi System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Menambah diskusi baru
   *
   * @param String $kode_konsumen
   * @param String $kode_agen
   * @param String $isi_agen_diskusi
   *
   * @return JSON data

   */
  public function createDiskusi ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');
    $kode_agen = $this->input->post('kode_agen');
    $isi_agen_diskusi = $this->input->post('isi_agen_diskusi');

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
      // Create new diskusi
      $result = $this->ModelSystemDiskusi->createDiskusi (
        $kode_konsumen,
        $kode_agen,
        $isi_agen_diskusi
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while adding new Diskusi, please wait for maintenance.";
      } else {
        // Get token
        $resultToken = $this->ModelSystem->retrieveTokenAgen (
          "agen",
          $kode_agen
          );

        print_r($resultToken);

        if ($resultToken == FALSE) {
          $data['messages'] = "Your diskusi added successfuly, but agen won't notified, because error while retrieving token.";
        } elseif ($resultToken == "EMPTY") {
          $data['messages'] = "Your diskusi added successfuly, but agen won't notified, because token is unavailable.";
        } else {
          $token = $resultToken[0]['token'];

          // Prepare payload
          $notifPayload = array (
            "kode_transaksi" => $kode_transaksi,
            "kode_agen" => $kode_agen,
            "isi_agen_diskusi" => $isi_agen_diskusi
            );
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Diskusi Baru");
          $this->envelope->setMessage ("Hi! Konsumen ingin berdiskusi dengan anda.");
          $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "diskusi",
                  "data" => $encodedPayload
                )
            );

          $jasPayload = $this->envelope->getData ();

          $resultFirebase = $this->firebase->sendDataSingle (
              'https://fcm.googleapis.com/fcm/send',
              $token,
              $jasPayload
            );

          $data['messages'] = "Your review added successfuly.";
        }

        $data['success'] = TRUE;
      }

      // Mengembalikan kode diskusi yang baru saja ditambahkan
      $data['row'][] = array('kode_agen_diskusi' => $result);
    }

    echo json_encode($data);
  }

  /**
   * Function - Mengisi balasan komentar diskusi
   *
   * @param string $kode_agen
   * @param string $kode_konsumen
   * @param string $kode_agen_diskusi
   * @param string $isi_agen_diskusi_komentar
   *
   * @return JSON data

   */
  public function createBalasanDiskusi ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $kode_agen_diskusi = $this->input->post('kode_agen_diskusi');
    $isi_agen_diskusi_komentar = $this->input->post('isi_agen_diskusi_komentar');

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
      $result = $this->ModelSystemDiskusi->createBalasanDiskusi (
        $kode_agen,
        $kode_konsumen,
        $kode_agen_diskusi,
        $isi_agen_diskusi_komentar
        );

      if ($result == FALSE) {
        $data['messages'] =  "Error while inserting new comment, please wait for maintenance";
      } else {
        $data['success'] =  TRUE;
        $data['messages'] =  "Your comment added successfuly.";
        $data['row'][] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Menghapus komentar / balasan dari diskusi
   * 
   * @param  [Integer] $kode_agen_diskusi_komentar    [Kode Diskusi]
   * 
   * @return [JSON]
   */
  public function deleteBalasanDiskusi ()
  {
    // Prepare request variable
    $kode_agen_diskusi_komentar = $this->input->post ('kode_agen_diskusi_komentar');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data ['messages'] = "Please fill your form completely.";
    } else {
      // Delete balasan diskusi
      $result = $this->ModelSystemDiskusi->deleteBalasanDiskusi ($kode_agen_diskusi_komentar);

      if ($result) {
        $data ['success'] = TRUE;
        $data ['messages'] = "Delete comment is success.";
      } else {
        $data ['messages'] = "Error while deleting comment, please wait for maintenance.";
      }
    }
    
    echo json_encode ($data);
  }

}