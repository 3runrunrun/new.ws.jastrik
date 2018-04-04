<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKonsumen extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Konsumen Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
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
    // Declare error flag
    $errorFlag = FALSE;

    // Declare all POST variable
    $nama_kota = strtoupper($this->input->post('nama_kota'));
    $nama = $this->input->post('nama');
    $notelp = $this->input->post('notelp');
    $jk = $this->input->post('jk');
    $fcm = $this->input->post('fcm');
    $email = $this->input->post('email');
    $tanggal_lahir = $this->input->post('tanggal_lahir');

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
      $ifEmailRegistered = $this->ModelSystem->checkEmailExistent ('konsumen',
        $email);

      // Check if fcm is already registered
      $ifFCMRegistered = $this->ModelSystem->checkFCMExistent (
        'konsumen',
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
          // Generating new kode_konsumen
          $kode_konsumen = $this->ModelSystem->userCodeGenerator('konsumen', $kode_kota);

          if ($kode_konsumen == FALSE) {
            $data['messages'] = "Cannot generate new code.";
          } else {
            // Call function to create new Konsumen
            $result = $this->ModelKonsumen->createKonsumen (
              $kode_konsumen,
              $nama,
              $notelp,
              $jk,
              $fcm,
              $email,
              $tanggal_lahir
              );

            // Check return value of called function
            if ($result == FALSE) {
              $data['messages'] = "Cannot create new Konsumen, please wait for the maintenance";
            } else {
              // Check if Alamat Konsumen is available
              $result = $this->ModelSystem->checkAlamat (
                $email,
                $fcm
                );

              if ($result == FALSE) {
                $data['isAlamatSet'] = FALSE;
              } else {
                $data['isAlamatSet'] = TRUE;
              }

              // Check if Rekening Konsumen is available
              $result = $this->ModelSystem->checkRekening (
                $email,
                $fcm
                );

              if ($result == FALSE) {
                $data['isRekeningSet'] = FALSE;
              } else {
                $data['isRekeningSet'] = TRUE;
              } 

              // Retrieve konsumen profile
              $data['row'] = $this->ModelKonsumenProfile->retrieveKonsumenProfile (
                $email,
                $fcm
                );

              $errorFlag = TRUE;
              $data['success'] = TRUE;
              $data['messages'] = "Create Konsumen success, yay!";
            }
          }
        }
      }
    }
    
    echo json_encode($data);
  }

}