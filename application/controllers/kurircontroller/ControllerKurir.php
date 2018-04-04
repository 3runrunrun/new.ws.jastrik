<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKurir extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Kurir Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
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
    // TEST
    /*$_POST['nama_kota'] = 'malang';
    $_POST['kode_pshcabang'] = 'MLG/CAB/20161013101442616542';
    $_POST['nama'] = 'Kurir 1';
    $_POST['notelp'] = '0341';
    $_POST['jk'] = 'l';
    $_POST['email'] = 'kurir1@mail.com';
    $_POST['fcm'] = 'fcmkurir1';
    $_POST['tanggal_lahir'] = date('Y-m-d');
    $_POST['file_ktp'] = 'urlktp';
    $_POST['file_kk'] = 'urlkk';
    $_POST['kodepos'] = '65141';
    $_POST['jenis_kurir'] = 'jenis';*/

    // Declare error flag
    $errorFlag = FALSE;

    // Declare all POST variable
    $nama_kota = strtoupper($this->input->post('nama_kota'));
    $kode_pshcabang = $this->input->post('kode_pshcabang');
    $nama = $this->input->post('nama');
    $notelp = $this->input->post('notelp');
    $jk = $this->input->post('jk');
    $email = $this->input->post('email');
    $fcm = $this->input->post('fcm');
    $tanggal_lahir = $this->input->post('tanggal_lahir');
    $file_ktp = $this->input->post('file_ktp');
    $file_kk = $this->input->post('file_kk');
    $kodepos = $this->input->post('kodepos');
    $jenis_kurir = $this->input->post('jenis_kurir');

    // Prepare array for json data
    $data = array (
      'success' => FALSE,
      'messages' => null
      );

    // Check if request value are empty 
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Check if email is already registered
      $ifEmailRegistered = $this->ModelSystem->checkEmailExistent ('kurir',
        $email);

      // Check if fcm is already registered
      $ifFCMRegistered = $this->ModelSystem->checkFCMExistent (
        'kurir',
        $fcm
        );

      if ($ifEmailRegistered == TRUE OR $ifFCMRegistered == TRUE) {
        $data['messages'] = "Your email is already registered.";
      } else {
        // Retrieve kode_kota
        $kode_kota = $this->ModelSystemRetriever->retrieveKodeKota ($nama_kota);
        $kode_kota = $kode_kota[0]['kode_kota'];

        if ($kode_kota == FALSE) {
          $data['messages'] = "Error while retrieving Kode Kota.";
        } else {
          // Generating new kode_kurir
          $kode_kurir = $this->ModelSystem->userCodeGenerator('kurir', $kode_kota);

          if ($kode_kurir == FALSE) {
            $data['messages'] = "Cannot generate new code.";
          } else {
            // Call function to create new Kurir
            $result = $this->ModelKurir->createKurir (
              $kode_kurir,
              $kode_pshcabang,
              $nama,
              $notelp,
              $jk,
              $email,
              $fcm,
              $tanggal_lahir,
              $file_ktp,
              $file_kk,
              $kodepos,
              $jenis_kurir);

            // Check return value of called function
            if ($result == FALSE) {
              $data['messages'] = "Cannot create new Kurir, please wait for the maintenance";
            } else {
              $errorFlag = TRUE;
              $data['success'] = TRUE;
              $data['messages'] = "Create Kurir success, yay!";
            }
          }
        }
      }
    }
    
    echo json_encode($data);
  }

}