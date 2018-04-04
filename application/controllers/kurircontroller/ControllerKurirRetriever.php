<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKurirRetriever extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Kurir Retrieve Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
  }

  /**
   * Function - Retrieve history transaksi, accepted / rejected
   * 
   * @param  String $kode_kurir         Kode kurir
   * 
   * @return JSON
   */
  public function retrieveHistoryTransaksi ()
  {
    // Prepare request variable
    $kode_kurir = $this->input->post('kode_kurir');

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
      // Retrieve basic information
      $result = $this->ModelKurirRetriever->retrieveHistoryTransaksi ($kode_kurir);

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

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Retrieve transaction milestone
   * 
   * @param  String $kode_kurir       Kode kurir
   * @param  String $status_transaksi Status transaksi (kode milestone kurir)
   * 
   * @return JSON
   */
  public function retrieveTransactionMilestone ()
  {
    // Prepare request variable
    $kode_kurir = $this->input->post('kode_kurir');
    $status_transaksi = $this->input->post('status_transaksi');

    // Set status transaksi to lower string
    $status_transaksi = strtolower($status_transaksi);

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
      switch ($status_transaksi) {
        case 'bk':
          $st_arr = array ("1", "8");

          // Retrieve transaction
          $result = $this->ModelKurirRetriever->retrieveTransactionMilestoneBaru (
              $kode_kurir,
              $st_arr
            );
          break;

        case 'jk':
          $st_arr = array ("2");

          // Retrieve transaction
          $result = $this->ModelKurirRetriever->retrieveTransactionMilestoneJemput (
              $kode_kurir,
              $st_arr
            );
          break;

        case 'pj':
          $st_arr = array ("3", "4");

          // Retrieve transaction
          $result = $this->ModelKurirRetriever->retrieveTransactionMilestoneJemput (
              $kode_kurir,
              $st_arr
            );
          break;

        case 'ak':
          $st_arr = array ("9");

          // Retrieve transaction
          $result = $this->ModelKurirRetriever->retrieveTransactionMilestoneAntar (
              $kode_kurir,
              $st_arr
            );
          break;

        case 'pa':
          $st_arr = array ("10");

          // Retrieve transaction
          $result = $this->ModelKurirRetriever->retrieveTransactionMilestoneAntar (
              $kode_kurir,
              $st_arr
            );
          break;
        
        default:
          # code...
          break;
      }

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

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Retrieve detail transaction
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return JSON
   */
  public function retrieveDetailTransaksi ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');

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
      // Retrieve basic information
      $resultBasic = $this->ModelKurirRetriever->retrieveBasicDetailTransaksi ($kode_transaksi);

      // Retrieve oredered layanan
      $resultLayanan = $this->ModelKurirRetriever->retrieveLayananDetailTransaksi ($kode_transaksi);


      if ($resultBasic == FALSE
        || $resultLayanan == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($resultBasic == "EMPTY"
        || $resultLayanan == "EMPTY") {
        $data['messages'] = "Data unavailable.";
      } else {
        $resultBasic[0]["layanan"] = $resultLayanan;
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'] = $resultBasic;
      }
    }    

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Retrieve income summary
   * 
   * @param  String  $kode_kurir    Kode kurir
   * @param  String  $sort          Tipe sorting
   * @param  Integer $from          Dari
   * @param  Integer $to            Ke
   * 
   * @return JSON
   */
  public function retrieveRekapFeeKurir ()
  {
    // Prepare request variable
    $kode_kurir = $this->input->post('kode_kurir');
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
      $result = $this->ModelKurirRetriever->retrieveRekapFeeKurir (
        $kode_kurir,
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
   * Function - Retrieve Fee Summary
   *
   * @param String $kode_kurir   Kode kurir
   * 
   * @return JSON
   */
  public function retrieveFeeSummary ()
  {
    // Prepare request variable
    $kode_kurir = $this->input->post('kode_kurir');

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
      // Retrieve total fee and total payed fee
      $result = $this->ModelKurirRetriever->retrieveFeeSummary ($kode_kurir);

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