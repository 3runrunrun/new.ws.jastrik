<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerSystemReview extends CI_Controller {
  
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
      'messages' => "Welcome to Review System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Membuat review
   *
   * @param String $kode_agen               Kode agen
   * @param String $kode_transaksi          Kode transaksi
   * @param String $rating_rapi             Rating rapi
   * @param String $rating_cepat            Rating cepat
   * @param String $isi_transaksi_review    Isi review
   *
   * @return JSON data
   */
  public function createReview ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_transaksi = $this->input->post('kode_transaksi');
    $rating_rapi = $this->input->post('rating_rapi');
    $rating_cepat = $this->input->post('rating_cepat');
    $isi_transaksi_review = $this->input->post('isi_transaksi_review');

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
      // Retrieve current rating from AGEN table
      $currentRating = $this->ModelSystemReview->retrieveRating ($kode_agen);

      if ($currentRating == FALSE) {
        $data['messages'] = "Error while retrieve current rating, please wait for maintenance.";
      } else {
        foreach ($currentRating as $key => $value) {
          $curRating_rapi = $currentRating[$key]['rating_rapi']; 
          $curRating_cepat = $currentRating[$key]['rating_cepat']; 
        }

        // Create new review
        $result = $this->ModelSystemReview->createReview (
          $kode_transaksi, 
          $rating_rapi, 
          $rating_cepat, 
          $isi_transaksi_review
          );

        if ($result == FALSE) {
          $data['messages'] = "Error while adding new review, please wait for maintenance.";
        } else {
          // Updating current rating
          if ($curRating_rapi == 0 || $curRating_cepat == 0) {
            $curRating_rapi = $rating_rapi;
            $curRating_cepat = $rating_cepat;
          } else {
            $curRating_rapi = ($curRating_rapi + $rating_rapi) / 2;
            $curRating_cepat = ($curRating_rapi + $rating_cepat) / 2;
          }

          $updateRating = $this->ModelSystemReview->updateRating (
            $kode_agen,
            $curRating_rapi,
            $curRating_cepat
            );

          if ($updateRating == FALSE) {
            $data['success'] = TRUE;
            $data['messages'] = "Your review added successfuly, rating not updated.";
          } else {
            // Get token
            $resultToken = $this->ModelSystem->retrieveTokenAgen (
              "agen",
              $kode_agen
              );

            if ($resultToken == FALSE) {
              $data['messages'] = "Your review added successfuly, but agen won't notified, because error while retrieving token.";
            } elseif ($resultToken == "EMPTY") {
              $data['messages'] = "Your review added successfuly, but agen won't notified, because token is unavailable.";
            } else {
              $token = $resultToken[0]['token'];

              // Prepare payload
              $notifPayload = array (
                "kode_transaksi" => $kode_transaksi,
                "kode_agen" => $kode_agen,
                "rating_rapi" => $rating_rapi,
                "rating_cepat" => $rating_cepat,
                "isi" => $isi_transaksi_review
                );
              $encodedPayload = json_encode ($notifPayload);

              $this->load->library('envelope');
              $this->load->library('firebase');

              $this->envelope->setTitle ("Anda Mendapatkan Review Baru");
              $this->envelope->setMessage ("Hi! Konsumen memberikan review baru untuk anda.");
              $this->envelope->setData (
                  array (
                      "title" => $this->envelope->getTitle(),
                      "message" => $this->envelope->getMessage(),
                      "timestamp" => date("Y-m-d H:i:s"),
                      "type" => "review",
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
          
          // Mengembalikan kode review yang baru ditambahkan
          $data['row'][] = array('kode_transaksi_review' => $result);
        }
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Membuat balasan review
   *
   * @param String $kode_agen
   * @param String $kode_konsumen
   * @param String $kode_transaksi_review
   * @param String $isi_transaksi_review_balas
   *
   * @return JSON data
   */
  public function createBalasanReview ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $kode_transaksi_review = $this->input->post('kode_transaksi_review');
    $isi_transaksi_review_balas = $this->input->post('isi_transaksi_review_balas');

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
      $result = $this->ModelSystemReview->createBalasanReview ($kode_agen,
        $kode_konsumen,
        $kode_transaksi_review,
        $isi_transaksi_review_balas);

      if ($result == FALSE) {
        $data['messages'] =  "Error while inserting new comment, please wait for maintenance";
      } else {
        $data['success'] =  TRUE;
        $data['messages'] =  "Your comment added successfuly.";
        $data['row'][] = array('waktu_transaksi_review_balas' => $result);
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Menghapus balasan / komentar sebuah review
   *
   * @param Integer $kode_transaksi_review            Kode review
   * @param String $waktu_transaksi_review_balas      Tanggal balas review
   * 
   * @return [JSON]
   */
  public function deleteBalasanReview ()
  {
    // Prepare request variable
    $kode_transaksi_review = $this->input->post('kode_transaksi_review');
    $waktu_transaksi_review_balas = $this->input->post('waktu_transaksi_review_balas');

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
      // Delete balasan review
      $result = $this->ModelSystemReview->deleteBalasanReview (
        $kode_transaksi_review,
        $waktu_transaksi_review_balas
        );

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