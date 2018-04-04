<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAgenRetriever extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Agen's Retriever Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - menampilkan data transaksi yang belum di accept oleh agen
   * 
   * @param  [String] $kode_agen            [Kode agen]
   * @param  [String] $status_transaksi     [Status Transaksi]
   * 
   * @return [JSON]
   */
  public function retrieveTransactionMilestone ()
  {
    // Prepare request method
    $kode_agen = $this->input->post('kode_agen');
    $status_transaksi = $this->input->post('status_transaksi');

    // Set character to lower string
    $status_transaksi = strtolower($status_transaksi);

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if requested variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod === FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      switch ($status_transaksi) {
        case 'ba':
          $st_arr = array ("0");
          break;

        case 'ja':
          $st_arr = array ("1","2","3","4");
          break;

        case 'a':
          $st_arr = array ("5");
          break;

        case 'd':
          $st_arr = array ("6");
          break;

        case 's':
          $st_arr = array ("7", "12");
          break;

        case 'aa':
          $st_arr = array ("8", "9", "10", "11");
          break;
        
        default:
          # code...
          break;
      }

      // Retrieve pending transaction
      $result = $this->ModelAgenRetriever->retrieveTransactionMilestone (
        $kode_agen,
        $st_arr
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

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
    * Function - menampilkan transaksi yang sudah selesai
   * 
   * @param  [String] $kode_agen            [Kode agen]
   * 
   * @return [JSON] 
   */
  public function retrieveTransactionComplete ()
  {
    // Prepare request method
    $kode_agen = $this->input->post('kode_agen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if requested variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if (!$checkRequestMethod) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve pending transaction
      $result = $this->ModelAgenRetriever->retrieveTransactionComplete (
        $kode_agen
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

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Menampilkan daftar kurir yang terasosiasi dengan agen
   * 
   * @param  String $kode_agen              Kode Agen
   * 
   * @return [JSON]
   */
  public function retrieveKurirAgen ()
  {
    // Prepare request method
    $kode_agen = $this->input->post('kode_agen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if requested variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if (!$checkRequestMethod) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve pending transaction
      $result = $this->ModelAgenRetriever->retrieveKurirAgen (
        $kode_agen
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving kurir data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Kurir is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Retrieve all item data
   * 
   * @return JSON
   */
  public function retrieveItem ()
  {
    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Retrieve all item
    $result = $this->ModelAgenRetriever->retrieveItem ();

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving data, please wait for maintenance.";
    } elseif ($result == "EMPTY") {
      $data['messages'] = "Item data unavailable.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve data is success.";
      $data['data'] = $result;
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve all item data by name
   * 
   * @param  String $nama_item        [description]
   * 
   * @return JSON
   */
  /*public function retrieveItemByName ()
  {
    // Prepare request variable
    $nama_item = $this->input->post('nama_item');

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
      // Retrieve all item by name
      $result = $this->ModelAgenRetriever->retrieveItemByName ($nama_item);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Item data unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data);
  }*/

  /**
   * Function - Retrieve dana agen
   * 
   * @param  String $kode_agen           Kode agen
   * 
   * @return JSON
   */
  public function retrieveDanaAgen ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      // Retrieve dana agen
      $result = $this->ModelAgenRetriever->retrieveDanaAgen ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Dana Agen, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data Agen is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Dana Agen is success.";
        $data['row'] = $result;
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve income summary
   * 
   * @param  String  $kode_agen     Kode agen
   * @param  String  $sort          Tipe sorting
   * @param  Integer $from          Dari
   * @param  Integer $to            Ke
   * 
   * @return JSON
   */
  public function retrieveRekapFeeAgen ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
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
      $result = $this->ModelAgenRetriever->retrieveRekapFeeAgen (
        $kode_agen,
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
   * Setoran dana ke checker
   */
  
  /**
   * Function - Retrieve last deposit request
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return JSON
   */
  public function retrieveLastDepositRequest ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

    // JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve last deposit request
      $result = $this->ModelAgenRetriever->retrieveLastDepositRequest ($kode_agen);

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
   * Function - Retrieve history setoran dana agen
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveHistorySetoranDana ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      // Retrieve history setoran dana agen
      $result = $this->ModelAgenRetriever->retrieveHistorySetoranDana ($kode_agen);

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
   * @param String $kode_agen   Kode agen
   * 
   * @return JSON
   */
  public function retrieveFeeSummary ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      $resultTotalFee = $this->ModelAgenRetriever->retrieveTotalFee ($kode_agen);
      $resultTotalPayedFee = $this->ModelAgenRetriever->retrieveTotalPayedOffFee ($kode_agen);

      if ($resultTotalFee == FALSE
        || $resultTotalPayedFee == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($resultTotalFee == "EMPTY"
        || $resultTotalPayedFee == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        // Preparing summary data
        $totalFee = $resultTotalFee[0]['pendapatan'];
        $totalPayedFee = $resultTotalPayedFee[0]['nominal'];
        $summary = $totalFee - $totalPayedFee;

        if ($summary < 0) {
          $data['messages'] = "Error while calculating summary, please wait for maintenance.";
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Retrieve data is success.";
          $data['row'] = array(
            'summary' => $summary
            );
        }
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Check if any withdrawal request
   * 
   * @param  String $kode_agen      Kode agen
   * 
   * @return Boolean/String/Array
   */
  public function retrieveLastWithdrawal ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      $result = $this->ModelAgenRetriever->retrieveLastWithdrawal ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result = "EMPTY") {
        $data['success'] = TRUE;
        $data['messages'] = "All request is completed.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "There is uncompleted request.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve detail transaksi by kode_transaksi
   * 
   * POST
   * @param string $kode_transaksi    kode transaksi 
   * 
   * @return JSON data
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
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve transaction detail
      $ringkasanPesanan = $this->ModelAgenRetriever->retrieveRingkasanPesanan ($kode_transaksi);
      $identitasPesanan = $this->ModelAgenRetriever->retrieveIdentitasPesanan ($kode_transaksi);
      $layanan = $this->ModelAgenRetriever->retrieveLayanan ($kode_transaksi);
      $item = $this->ModelAgenRetriever->retrieveItemDetailTransaksi ($kode_transaksi);
      $pembayaran = $this->ModelAgenRetriever->retrieveInformasiPembayaran ($kode_transaksi);
      $status_transfer = $this->ModelAgenRetriever->retrieveStatusKonfirmasiTransfer ($kode_transaksi);

      if (!$ringkasanPesanan
        || !$identitasPesanan
        || !$layanan 
        || !$item 
        || !$pembayaran
        || !$status_transfer) {
        $data['messages'] = "Error while retrieving detail transaksi, please wait for maintenance.";
      } elseif ($ringkasanPesanan == "EMPTY"
        || $identitasPesanan == "EMPTY"
        || $layanan  == "EMPTY"
        || $pembayaran == "EMPTY"
        || $status_transfer == "EMPTY") {
        $data['messages'] = "One of the information is unavailable.";
      } else {
        
        if ($item != "EMPTY") {
          $item_per_layanan=array();

          foreach ($item as $row_item) {
            $item_per_layanan[$row_item['kode_transaksi_layanan']][]=$row_item;
          }

          $layanan_item=array();
          foreach ($layanan as $key=>$row_layanan) {
            $layanan_item[$key]=$row_layanan;
            $layanan_item[$key]["item"]=$item_per_layanan[$row_layanan['kode_transaksi_layanan']];
          }

          $data['data'][] = array (
            'ringkasan_pesanan' => $ringkasanPesanan,
            'identitas_pesanan' => $identitasPesanan,
            'item_pesanan' => $layanan_item,
            'pembayaran' => $pembayaran,
            );
        } else {
          $layanan_item = array();

          foreach ($layanan as $key=>$row_layanan) {
            $layanan_item[$key] = $row_layanan;
          }

          $data['data'][] = array (
            'ringkasan_pesanan' => $ringkasanPesanan,
            'identitas_pesanan' => $identitasPesanan,
            'item_pesanan' => $layanan_item,
            'pembayaran' => $pembayaran,
            );
        }

        if ($status_transfer[0]['jenis_bayar'] == "1"
          && $status_transfer[0]['status_bayar'] == "0"
          && $status_transfer[0]['foto'] == 0
          && $status_transfer[0]['status_transaksi_bayar_transfer'] == "tbd") {
            // Belum konfirmasi
            $data['data'][0]['pembayaran'][0]['status_konfirmasi_transfer'] = "0";
          } elseif ($status_transfer[0]['jenis_bayar'] == "1"
            && $status_transfer[0]['status_bayar'] == "0"
            && $status_transfer[0]['foto'] == 0
            && $status_transfer[0]['status_transaksi_bayar_transfer'] == "1") {
            // Telah konfirmasi tanpa bukti
            $data['data'][0]['pembayaran'][0]['status_konfirmasi_transfer'] = "1";
          } elseif ($status_transfer[0]['jenis_bayar'] == "1"
            && $status_transfer[0]['status_bayar'] == "0"
            && $status_transfer[0]['foto'] != "0"
            && $status_transfer[0]['status_transaksi_bayar_transfer'] == "2") {
            // Telah konfirmasi + bukti
            $data['data'][0]['pembayaran'][0]['status_konfirmasi_transfer'] = "2";
          } elseif ($status_transfer[0]['jenis_bayar'] == "1"
            && $status_transfer[0]['status_bayar'] == "1"
            && $status_transfer[0]['foto'] != "0"
            && $status_transfer[0]['status_transaksi_bayar_transfer'] == "3") {
            // Telah konfirmasi + bukti
            $data['data'][0]['pembayaran'][0]['status_konfirmasi_transfer'] = "3";
          } 

        $data['success'] = TRUE;
        $data['messages'] = "Transaction detail retrieved successfuly.";
      }
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

}