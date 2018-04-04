<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAgen extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Agen Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('konsumen', 'MLG');
  }

  /**
   * Function - Sign Up
   *
   * @param no params, accept POST value from client
   * 
   * @return JSON
   */
  public function signUp ()
  {
    // Prepare request variable
    $nama = $this->input->post('nama');
    $notelp = $this->input->post('notelp');
    $email = $this->input->post('email');
    $kode_kota = $this->input->post('kode_kota');
    $kode_kecamatan = $this->input->post('kode_kecamatan');
    $kode_kelurahan = $this->input->post('kode_kelurahan');
    $alamat = $this->input->post('alamat');

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
      // Check if email is already registered
      $ifEmailRegistered = $this->ModelSystem->checkEmailExistent ('agen',
        $email);

      if ($ifEmailRegistered == TRUE) {
        $data['messages'] = "Your email is already registered.";
      } else {
        // Generating new kode_agen
        $kode_agen = $this->ModelSystem->userCodeGenerator(
          'agen',
          $kode_kota
          );

        if ($kode_agen == FALSE) {
          $data['messages'] = "Cannot generate new code.";
        } else {
          // Retrieve kodepos by kode_kelurahan
          $kodepos = $this->ModelAgenSystem->retrieveKodePost ($kode_kelurahan);

          if ($kodepos == FALSE) {
            $kodepos = "error";
          } elseif ($kodepos == "EMPTY") {
            $kodepos = NULL;
          } else {
            $kodepos = $kodepos;
          }
          
          // Creating new agen
          $result = $this->ModelAgen->createAgen (
            $kode_agen,
            $nama,
            $notelp,
            $email,
            $kode_kota,
            $kode_kecamatan,
            $kode_kelurahan,
            $alamat,
            $kodepos
            );

          if ($result == FALSE) {
            $data['messages'] = "Your request cannot be done, please wait for maintenance";
          } else {
            // Check if any branch on the city
            $ifAnyBranch = $this->ModelAgenSystem->checkIfAnyBranch ($kode_kota);

            if ($ifAnyBranch == FALSE) {
              $data['messages'] = "Gagal melakukan pengecekan cabang di kota anda, mohon tunggu perbaikan sistem.";
            } elseif ($ifAnyBranch == "EMPTY") {
              $data['messages'] = "Pendaftaran berhasil, saat ini anda berada pada waiting list di sistem validasi kami. Kami akan memberikan pengumuman validasi pada email anda. Terima kasih atas kepercayaan yang diberikan";
            } else {
              $data['messages'] = "Pendaftaran berhasil, mohon tunggu kedatangan tim kami untuk melakukan validasi laundry anda. Terima kasih atas kepercayaan yang diberikan.";
            }

            $data['success'] = TRUE;
          }
        }
      }  
    }
    
    echo json_encode($data);  
  }

  /**
   * Function - Assign kurir to transaksi_antar
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * @param  String $kode_kurir         Kode kurir
   * 
   * @return JSON
   */
  public function updateKurirToTransaksiAntar ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $kode_kurir = $this->input->post('kode_kurir');

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
      // Check transaction
      $result = $this->ModelAgen->updateKurirToTransaksiAntar (
        $kode_transaksi,
        $kode_kurir
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while assigning kurir, please wait for maintenance.";
      } else {
        // Retrieve token kurir
        $resultToken = $this->ModelSystem->retrieveTokenAgen (
          "kurir",
          $kode_kurir
          );

          if ($resultToken == FALSE) {
            $data['messages'] = "Error while retrieving token, kurir may not receive any notification. (errTok01)";
          } elseif ($resultToken == "EMPTY") {
            $data['messages'] = "Kurir token is unavailable, kurir may not receive any notification. (errTok02)";
          } else {
            $token = $resultToken[0]['token'];

            // Prepare payload
            $notifPayload = array ("kode_transaksi" => $kode_transaksi);
            $encodedPayload = json_encode ($notifPayload);

            $this->load->library('envelope');
            $this->load->library('firebase');

            $this->envelope->setTitle ("Request Pengantaran Transaksi");
            $this->envelope->setMessage ("Hi! Agen membutuhkan anda untuk mengantarkan transaksi yang sudah selesai!");
            $this->envelope->setData (
                array (
                    "title" => $this->envelope->getTitle(),
                    "message" => $this->envelope->getMessage(),
                    "timestamp" => date("Y-m-d H:i:s"),
                    "type" => "antar_transaksi",
                    "data" => $encodedPayload
                  )
            );

            $jasPayload = $this->envelope->getData ();

            $resultNotif = $this->firebase->sendDataSingle (
                'https://fcm.googleapis.com/fcm/send',
                $token,
                $jasPayload
              );

            $data['messages'] = "Request accepted by system, please wait for Kurir confirmation.";
          }
        }

        $data['success'] = TRUE;
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Create new qr_code for absen
   * 
   * @param  String $kode_agen        Kode agen
   * @param  String $kode_checker     Kode checker
   * 
   * @return JSON
   */
  public function createQRAbsen ()
  {
    // Call qrcode libray
    $this->load->library ('qrcode');

    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_checker = $this->input->post('kode_checker');
    $enkripsi = $this->qrcode->setEncrypt (
      $kode_agen 
      . $kode_checker
      );

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
      $result = $this->ModelAgen->createQRAbsen (
        $kode_agen,
        $kode_checker,
        $enkripsi
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while inserting new qrcode, please wait for maintenance.";
      } else {
        $text = "jastrik/" . $enkripsi; 
        $this->qrcode->setText ($text);
        $this->qrcode->setSize (140);
        $this->qrcode->setPadding (0);
        $qrURI = $this->qrcode->getUri ();

        $data['success'] = TRUE;
        $data['messages'] = "Create qrcode is success.";
        $data['row'][] = array (
          "uri" => $qrURI,
          "kode_agen_temp_kode_absen" => $result
          );

        echo json_encode($data, JSON_UNESCAPED_SLASHES);
      }
      
    }
  }

}