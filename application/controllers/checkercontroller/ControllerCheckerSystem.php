<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerCheckerSystem extends CI_Controller {

  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index ()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Checker System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('checker', 'MLG');
  }

  /**
   * Function - retrieve status penarikan dana agen
   * 
   * @param  String $kode_checker     kode checker
   * @param  String $kode_agen        kode agen
   * 
   * @return JSON
   */
  public function retrieveStatusDepositWithdrawal ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');
    $kode_agen = $this->input->post('kode_agen');

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
      // Create deposit withdrawal request
      $result = $this->ModelCheckerSystem->retrieveStatusDepositWithdrawal (
        $kode_checker,
        $kode_agen
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while requesting, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['success'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['row'] = $result;
      }  
    }
    
    echo json_encode($data);
  }

}