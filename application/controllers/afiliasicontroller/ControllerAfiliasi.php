<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAfiliasi extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index ()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Afiliasi Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
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
    // Flag variable
    $errorFlag = FALSE;

    // Declare all POST variables
    $nama_kota = $this->input->post('nama_kota');
    $kode_pshcabang = $this->input->post('kode_pshcabang');
    $nama = $this->input->post('nama');
    $alamat = $this->input->post('alamat');
    $notelp = $this->input->post('notelp');
    $tanggal_lahir = $this->input->post('tanggal_lahir');
    $email = $this->input->post('email');
    $fcm = $this->input->post('fcm');
    $file_ktp = $this->input->post('file_ktp');
    $file_kk = $this->input->post('file_kk');
    $kodepos = $this->input->post('kodepos');
    $foto = $this->input->post('foto');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Check if email is already registered
      $ifEmailRegistered = $this->ModelSystem->checkEmailExistent ('afiliasi',
        $email);

      // Check if fcm is already registered
      $ifFCMRegistered = $this->ModelSystem->checkFCMExistent (
        'afiliasi',
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
          // Generating new kode_afiliasi
          $kode_afiliasi = $this->ModelSystem->userCodeGenerator('afiliasi', $kode_kota);

          if ($kode_afiliasi ==  FALSE) {
            $data['messages'] = "Error while generating code, please wait for maintenance.";
          } else {
            // Call function to create new Afiliasi
            $result = $this->ModelAfiliasi->createAfiliasi (
              $kode_afiliasi,
              $kode_pshcabang,
              $nama,
              $alamat,
              $notelp,
              $tanggal_lahir,
              $email,
              $fcm,
              $file_ktp,
              $file_kk,
              $kodepos,
              $foto);

            // Check if create new Afiliasi is success
            if ($result == FALSE) {
              $data['messages'] = "Cannot create new Afiliasi, please wait for the maintenance";
            } else {
              $errorFlag = TRUE;
              $data['success'] = TRUE;
              $data['messages'] = "Create Afiliasi success, yay!";
            }
          }
        }
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Requesting pencairan fee
   * 
   * @param  String $kode_afiliasi                    Kode afiliasi
   * @param  String $kode_afiliasi_bank               Kode bank afiliasi
   * @param  String $nominal                          Nominal
   * 
   * @return JSON
   */
  public function requestPencairanFee ()
  {
    // Prepare request variable
    $kode_afiliasi = $this->input->post('kode_afiliasi');
    $kode_afiliasi_bank = $this->input->post('kode_afiliasi_bank');
    $nominal = $this->input->post('nominal');

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
      // Generating request code
      $resultCode = $this->ModelAfiliasiSystem->codeGenerator ("rpf");
      $kode_afiliasi_pencairan_saldo = $resultCode;

      // Request result
      $result = $this->ModelAfiliasi->createRequestPencairanFee (
        $kode_afiliasi_pencairan_saldo,
        $kode_afiliasi,
        $kode_afiliasi_bank,
        $nominal
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while requesting, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Your request accepted, please wait for Jastrik response.";
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Registering Agen
   *
   * @param String $kode_afiliasi    Kode afiliasi   
   * @param String $nama             Nama agen
   * @param String $notelp           No telepon agen
   * @param String $kode_kota        Kode kota
   * @param String $kode_kecamatan   Kode kecamatan
   * @param String $kode_kelurahan   Kode kelurahan
   * @param String $alamat           Alamat agen
   * @param String $longitude        Longitude agen
   * @param String $latitude         Latitude agen
   * 
   * @return JSON
   */
  public function signUpAgen ()
  {
    // Prepare request variable
    $kode_afiliasi = $this->input->post('kode_afiliasi');
    $nama = $this->input->post('nama');
    $notelp = $this->input->post('notelp');
    $kode_kota = $this->input->post('kode_kota');
    $kode_kecamatan = $this->input->post('kode_kecamatan');
    $kode_kelurahan = $this->input->post('kode_kelurahan');
    $alamat = $this->input->post('alamat');
    $bayar = $this->input->post('bayar');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      $email = $this->input->post('email');

      if (!empty($email)) {
        $ifEmailRegistered = $this->ModelSystem->checkEmailExistent (
          'agen',
          $email
          );

        if ($ifEmailRegistered == TRUE) {
          $data['messages'] = "Your email is already registered.";
          goto end;
        }
      } else {
        $email = NULL;
      }

      // Generating new kode_agen And kode_pendaftaran_agen
      $kode_agen = $this->ModelSystem->userCodeGenerator(
        'agen',
        $kode_kota
        );
      $kode_pendaftaran_agen = $this->ModelAfiliasiSystem->codeGenerator ("rega");

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

        // Registering Agen
        $result = $this->ModelAfiliasi->signUpAgen (
          $kode_pendaftaran_agen,
          $kode_agen,
          $kode_afiliasi,
          $nama,
          $notelp,
          $kode_kota,
          $kode_kecamatan,
          $kode_kelurahan,
          $alamat,
          $kodepos,
          $bayar,
          $email
          );

        if ($result == FALSE) {
          $data['messages'] = "Error while signing up, please wait for maintenance.";
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Your request accepted, please wait for confirmation form JASTRIK. Thank you for trusting us!";
          $data['row'] = $result;
        }
      }
    }

    end:
    echo json_encode($data);
  }

}