<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAfiliasiProfile extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Afiliasi's Profile Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
  }

  /**
   * Function - Retrieve afiliasi rekening
   * 
   * @param  String $kode_afiliasi    Kode afiliasi
   * 
   * @return JSON
   */
  public function retrieveAfiliasiRekening ()
  {
    // Prepare request variable
    $kode_afiliasi = $this->input->post('kode_afiliasi');

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
      // Retrieve account number (rekening)
      $result = $this->ModelAfiliasiProfile->retrieveAfiliasiRekening ($kode_afiliasi);

      if ($result == FALSE) {
        $data['messages'] = "Error whiler retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['row'] = $result;
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Update Afiliasi Profile
   *
   * @param no params, accept POST value from client
   * 
   * @return JSON
   */
  public function updateAfiliasiProfile ()
  {
    // prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = 'Please fill your form completely.';
    } else {      
      // Retrieve variable name as a field name
      $varName = $this->ModelSystem->retrieveVariableName ($this->input->post());

      if ($varName == FALSE) {
        $data['messages'] = 'Cannot retrieve variable name. Please wait for maintenance';
      } else {
        
        // Prepare request variable
        $fcm = $this->input->post('fcm');
        $fieldname = $varName;
        $newvalue = $this->input->post($varName);

        $result = $this->ModelAfiliasiProfile->updateAfiliasiProfile (
          $fcm,
          $fieldname,
          $newvalue
          );

        // Check if update is success
        if ($result == FALSE) {
          $data['messages'] = 'Error while updating data. Please wait for maintenance';
        } else {
          $data['success'] = TRUE;
          $data['messages'] = 'Update data success.';
        }
      }
    }

    echo json_encode($data);
  }
}