<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAfiliasiRetriever extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Afiliasi's Retriever Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
  }

  /**
   * Function - Retrieve owned agen by afiliasi
   * 
   * @param  String $kode_afiliasi      Kode afiliasi
   * @param  String $status_agen        Status agen
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveOwnedAgen ()
  {
   // Prepare request variable
   $kode_afiliasi = $this->input->post('kode_afiliasi');
   $status_agen = $this->input->post('status_agen');

   // Prepare JSON data
   $data = array (
    'success' => FALSE,
    'messages' => NULL
    );

   // Check if request parameter is empty
   $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

   if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
   } else {
      // Retrieve owned agen
      $result = $this->ModelAfiliasiRetriever->retrieveOwnedAgen (
        $kode_afiliasi,
        $status_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'] = $result;
      }
   }
   
   echo json_encode($data);
  }

  /**
   * Function - Count and retrieve total of owned agen by afiliator
   * 
   * @param  String $kode_afiliasi      Kode afiliasi
   * 
   * @return JSON
   */
  public function retrieveTotalOwnedAgen()
  {
    // Prepare request variable
    $kode_afiliasi = $this->input->post('kode_afiliasi');

    // Prepare JSON data
    $data = array (
     'success' => FALSE,
     'messages' => NULL
     );

    // Check if request parameter is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
       $data['messages'] = "Please fill your form completely.";
    } else {
       // Retrieve owned agen
       $result = $this->ModelAfiliasiRetriever->retrieveTotalOwnedAgen($kode_afiliasi);

       if ($result == FALSE) {
         $data['messages'] = "Error while retrieving data, please wait for maintenance.";
       } elseif ($result == "EMPTY") {
         $data['messages'] = "Data is unavailable.";
       } else {
         $data['success'] = TRUE;
         $data['messages'] = "Retrieve data is success.";
         $data['data'] = $result;
       }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Retrieve income summary (afiliasi)
   * 
   * @param  String  $kode_afiliasi     Kode afiliasi
   * @param  String  $sort              Tipe sorting
   * @param  Integer $from              Dari
   * @param  Integer $to                Ke
   * 
   * @return Boolean/String/Array       FALSE/"EMPTY"/Result
   */
  public function retrieveRekapFeeAfiliasi ()
  {
    // Prepare request variable
    $kode_afiliasi = $this->input->post('kode_afiliasi');
    $sort = $this->input->post('sort');
    $from = $this->input->post('from');
    $to = $this->input->post('to');

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
      // Retrieve income summary
      $result = $this->ModelAfiliasiRetriever->retrieveRekapFeeAfiliasi (
        $kode_afiliasi,
        $sort,
        $from,
        $to
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve last requested pencairan fee
   * 
   * @param  String $kode_afiliasi  Kode afiliasi
   * 
   * @return JSON
   */
  public function retrieveLastRequestPencairan ()
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
      // Retrieve last requested pencairan fee
      $result = $this->ModelAfiliasiRetriever->retrieveLastRequestPencairan ($kode_afiliasi);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
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
   * Function - Retrieve last requested pencairan fee
   * 
   * @param  String $kode_afiliasi  Kode afiliasi
   * 
   * @return JSON
   */
  public function retrieveHistoryPencairanFee ()
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
      // Retrieve last requested pencairan fee
      $result = $this->ModelAfiliasiRetriever->retrieveHistoryPencairanFee ($kode_afiliasi);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Retrieve all income since registered
   * 
   * @param  String $kode_afiliasi  Kode afiliasi
   * 
   * @return JSON
   */
  public function retrieveAllIncome ()
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
      // Retrieve last requested pencairan fee
      $result = $this->ModelAfiliasiRetriever->retrieveAllIncome ($kode_afiliasi);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
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
   * Function - Retrieve agen detail
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return JSON
   */
  public function retrieveAgenDetail ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request parameter is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve owned agen
      $result = $this->ModelAfiliasiRetriever->retrieveAgenDetail ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['row'] = $result;
      }
    }

    echo json_encode($data);
  }

}