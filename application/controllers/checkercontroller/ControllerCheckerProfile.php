<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerCheckerProfile extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Checker's Profile Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
  }

  /**
   * Function - Update Checker Profile
   *
   * @param no params, accept POST value from client
   * 
   * @return JSON
   */
  public function updateCheckerProfile ()
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

        $result = $this->ModelCheckerProfile->updateCheckerProfile (
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