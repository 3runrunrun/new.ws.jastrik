<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerSystemUserManagement extends CI_Controller {

  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to User Management System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  public function updateToken ()
  {
    // Prepare request variable
    $usertype = $this->input->post('usertype');
    $kode_user = $this->input->post('kode_user');
    $token = $this->input->post('token');

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
      // Updating token
      $result = $this->ModelSystemUserManagement->updateToken (
        $usertype,
        $kode_user,
        $token
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while updating token, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Updating token is success.";
      }
      
    }
    
    echo json_encode($data);
  }

}