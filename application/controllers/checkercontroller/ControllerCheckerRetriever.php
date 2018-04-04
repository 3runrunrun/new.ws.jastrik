<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerCheckerRetriever extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Checker's Retriever Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
  }

  /**
   * Function - Retrieve all supervised agen
   * 
   * @param  String $kode_checker          Kode checker
   * 
   * @return JSON
   */
  public function retrieveAllSupervisedAgen ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');

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
      // Retrieve supervised agen
      $result = $this->ModelCheckerRetriever->retrieveAllSupervisedAgen ($kode_checker);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data unavailable.";
      } else {
        foreach ($result as $key => $value) {
          $kode_agen = $value['kode_agen'];
          $isVisited = $this->ModelCheckerSystem->checkIfAgenIsVisited ($kode_agen);
          
          if ($isVisited == FALSE) {
            $data['messages'] = "Error while checking visit data, please wait for maintenance.";
          } elseif ($isVisited == "EMPTY") {
            $result[$key]['isVisited'] = "0";
            $data['success'] = TRUE;
            $data['messages'] = "Retrieve data is success.";
          } else {
            $result[$key]['isVisited'] = "1";
            $data['success'] = TRUE;
            $data['messages'] = "Retrieve data is success.";
          }
        }
        $data['data'] = $result;
      } 
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Retrieve visit history
   *  
   * @param  String $kode_checker       Kode checker
   * 
   * @return JSON
   */
  public function retrieveVisitHistory ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');
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
      // Retrieve visit history
      $result = $this->ModelCheckerRetriever->retrieveVisitHistory (
        $kode_checker,
        $kode_agen
        );

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
   * Function - Retrieve detail of visit history
   * 
   * @param  String $kode_agen_absen      Kode absen / visit
   * 
   * @return Boolean/String/Array         FALSE/"EMPTY"/Result
   */
  public function retrieveVisitHistoryDetail ()
  {
    // Prepare request variable
    $kode_agen_absen = $this->input->post('kode_agen_absen');

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
      // Retrieve visit history
      $result = $this->ModelCheckerRetriever->retrieveVisitHistoryDetail ($kode_agen_absen);

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

  public function retrievePerformanceSummary ()
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
      // Request performance summary
      $resultTransMasuk = $this->ModelCheckerRetriever->retrieveJumlahTransaksiMasuk ($kode_agen);
      $resultTransHarusSelesai = $this->ModelCheckerRetriever->retrieveJumlahTransaksiHarusSelesai ($kode_agen);
      $resultTransDikerjakan = $this->ModelCheckerRetriever->retrieveJumlahTransaksiDikerjakan ($kode_agen);
      $resultPengaduanMasuk = $this->ModelCheckerRetriever->retrieveJumlahPengaduanMasuk ($kode_agen);
      $resultPengaduanUnsolved = $this->ModelCheckerRetriever->retrieveJumlahPengaduanUnsolved ($kode_agen);

      if ($resultTransMasuk == FALSE
        || $resultTransHarusSelesai == FALSE
        || $resultTransDikerjakan == FALSE
        || $resultPengaduanMasuk == FALSE
        || $resultPengaduanUnsolved == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($resultTransMasuk == "EMPTY"
        || $resultTransHarusSelesai == "EMPTY"
        || $resultTransDikerjakan == "EMPTY"
        || $resultPengaduanMasuk == "EMPTY"
        || $resultPengaduanUnsolved == "EMPTY") {
        $data['messages'] = "Data unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['row'] = array (
          'jml_transaksi_masuk' => $resultTransMasuk[0]['jml_transaksi_masuk'],
          'jml_transaksi_harus_selesai' => $resultTransHarusSelesai[0]['jml_transaksi_harus_selesai'],
          'jml_transaksi_dikerjakan' => $resultTransDikerjakan[0]['jml_transaksi_dikerjakan'],
          'jumlah_pengaduan' => $resultPengaduanMasuk[0]['jumlah_pengaduan'],
          'jumlah_pengaduanUnsolved' => $resultPengaduanUnsolved[0]['jumlah_pengaduan']
          );
      }
    }
    
    echo json_encode($data);
  }

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
   * Function - retrieve uncompleted and uncheceked transaction
   * 
   * @param  String $kode_agen      Kode agen
   * 
   * @return JSON
   */
  public function retrieveUncheckedTransaction ()
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
      $result = $this->ModelCheckerRetriever->retrieveUncheckedTransaction ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Data is success.";
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
      $ringkasanPesanan = $this->ModelKonsumenTransaksi->retrieveRingkasanPesanan ($kode_transaksi);
      $identitasPesanan = $this->ModelKonsumenTransaksi->retrieveIdentitasPesanan ($kode_transaksi);
      $layanan = $this->ModelKonsumenTransaksi->retrieveLayanan ($kode_transaksi);
      $item = $this->ModelKonsumenTransaksi->retrieveItem ($kode_transaksi);
      $pembayaran = $this->ModelKonsumenTransaksi->retrieveInformasiPembayaran ($kode_transaksi);
      $status_transfer = $this->ModelKonsumenTransaksi->retrieveStatusKonfirmasiTransfer ($kode_transaksi);

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

  /**
   * Function - Retrieve all inventory
   * 
   * @param  String $kode_pshcabang     Kode cabang
   * 
   * @return JSON
   */
  public function retrieveInventory ()
  {
    // Prepare request variable
    $kode_pshcabang = $this->input->post('kode_pshcabang');

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
      $result = $this->ModelCheckerRetriever->retrieveInventory ($kode_pshcabang);

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
   * Function - Retrieve all inventory 
   * 
   * @return Boolean/String/Result      FALSE/"EMPTY"/Array
   */
  public function retrieveAllInventory ()
  {
    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );
    
    // Retrieve dana agen
    $result = $this->ModelCheckerRetriever->retrieveAllInventory ();

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving data, please wait for maintenance.";
    } elseif ($result == "EMPTY") {
      $data['messages'] = "Data is unavailable.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve data is success.";
      $data['data'] = $result;
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve last order inventory
   * 
   * @param  String $kode_checker     Kode checker
   * @param  String $kode_agen        Kode agen
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveLastOrderInventory ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');
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
      // Retrieve last order inventory
      $result = $this->ModelCheckerRetriever->retrieveLastOrderInventory (
        $kode_checker,
        $kode_agen
        );

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

  /**
   * Function - Retrieve active order inventory
   * 
   * @param  String $kode_checker     Kode checker
   * 
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveActiveOrderInventory ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');

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
      $result = $this->ModelCheckerRetriever->retrieveActiveOrderInventory ($kode_checker);

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
   * Function - Retrieve summary of order inventory
   * 
   * @param  String $kode_checker_order_inventory     Kode order
   * 
   * @return JSON
   */
  public function retrieveDetailOrderInventory ()
  {
    // Prepare request variable
    $kode_checker_order_inventory = $this->input->post('kode_checker_order_inventory');

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
      // Retrieve order detail
      $resultSummary = $this->ModelCheckerRetriever->retrieveSummaryOrderInventory ($kode_checker_order_inventory);
      $resultDetail = $this->ModelCheckerRetriever->retrieveDetailOrderInventory ($kode_checker_order_inventory);

      if ($resultSummary == FALSE
        || $resultDetail == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";
      } elseif ($resultSummary == "EMPTY"
        || $resultDetail == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['data'][] = array (
          'ringkasan_order' => $resultSummary,
          'detail_order' => $resultDetail
          );
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve rekap aktivitas visit checker
   * 
   * @param  String $kode_checker     Kode checker
   *                                  
   * @return Boolean/String/Array     FALSE/"EMPTY"/Result
   */
  public function retrieveActivityHistory ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');

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
      // Retrieve activity history
      $result = $this->ModelCheckerRetriever->retrieveActivityHistory ($kode_checker);

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
   * Function - Retrieve history of order inventory per agen
   * 
   * @param  String $kode_agen       Kode agen
   * 
   * @return Boolean/String/Array    FALSE/"EMPTY"/Result
   */
  public function retrieveOrderInventoryHistory ()
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
      // Retrieve activity history
      $result = $this->ModelCheckerRetriever->retrieveOrderInventoryHistory ($kode_agen);

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

  ////////////////////
  // Inventory agen //
  ////////////////////

  /**
   * Function - Retrieve agen's inventories stock
   * 
   * @param  String $kode_agen        Kode agen
   * 
   * @return JSON
   */
  public function retrieveAgenInventoryStock  ()
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
      // Retrieve activity history
      $result = $this->ModelCheckerRetriever->retrieveAgenInventoryStock  ($kode_agen);

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

}