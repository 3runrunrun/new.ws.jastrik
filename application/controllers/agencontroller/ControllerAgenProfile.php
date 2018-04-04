<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAgenProfile extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Agen Profile Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Update Agen Profile
   *
   * @param no params, accept POST value from client
   * 
   * @return JSON
   */
  public function updateAgenProfile ()
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

        $result = $this->ModelAgenProfile->updateAgenProfile (
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

  /**
   * Function - Change address
   * 
   * @param  String $kode_agen        Kode agen
   * @param  String $new_address      Alamat baru
   * @param  String $new_latitude     Latitude baru
   * @param  String $new_longitude    Longitude baru
   * 
   * @return Boolean
   */
  public function updateAddress ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $new_address = $this->input->post('new_address');
    $new_latitude = $this->input->post('new_latitude');
    $new_longitude = $this->input->post('new_longitude');

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
      // Change address
      $result = $this->ModelAgenProfile->updateAddress (
        $kode_agen,
        $new_address,
        $new_latitude,
        $new_longitude
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while changing address, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Address changed successfuly.";
      }
    }

    echo json_encode($data);
  }

}