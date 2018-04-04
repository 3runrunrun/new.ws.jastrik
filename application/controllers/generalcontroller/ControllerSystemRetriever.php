<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerSystemRetriever extends CI_Controller {

  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Retriever System Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Retrieve all Central's Bank Account
   *
   * @param No params
   * 
   * @return JSON data
   */
  public function retrieveBankPusat ()
  {
    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $result = $this->ModelSystemRetriever->retrieveBankPusat ();

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving data, please wait for maintenance.";
    } elseif ($result == "EMPTY") {
      $data['messages'] = "The data is empty.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieving data is success.";
      $data['data'] = $result;
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Review Agen data
   */

  /**
   * Function - Menampilkan review masing-masing agen
   * 
   * @param string $kode_agen     Kode agen
   *
   * @return JSON data
   */
  public function retrieveReviewAgen ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      // Retrieve agen review
      $result = $this->ModelSystemRetriever->retrieveReviewAgen ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving review data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Review data is empty.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieving review data is success.";
        $data['data'] = $result;
      } 
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Menampilkan jumlah  review berdasarkan kode_Agen
   * 
   * @param string $kode_agen  Kode agen
   *
   * @return JSON data
   */
  public function retrieveJumlahReview ()
  {
    // Prepare request variable 
    $kode_agen = $this->input->post('kode_agen');

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
      // Retrieve jumlah balasan review
      $result = $this->ModelSystemRetriever->retrieveJumlahReview ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving jumlah balasan review, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve jumlah review success.";
        $data['row'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Menampilkan detail review 
   * 
   * @param string $kode_transaksi_review     Kode review pada tabel transaksi_review
   *
   * @return JSON data
   */
  public function retrieveReviewAgenDetail ()
  {
    // Prepare request variable
    $kode_transaksi_review = $this->input->post('kode_transaksi_review');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if result array is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve detil review agen
      $singleReview = $this->ModelSystemRetriever->retrieveSingleReviewAgen ($kode_transaksi_review);

      if ($singleReview == FALSE) {
        $data['messages'] = "Error while retrieving review detail (Ex01), please wait for maintenance.";
      } elseif ($singleReview == "EMPTY") {
        $data['messages'] = "Review is empty.";
      } else {
        // Retrieve balasan review agen
        $result = $this->ModelSystemRetriever->retrieveBalasanReview ($kode_transaksi_review);

        if ($result == FALSE) {
          $data['messages'] = "Error while retrieving review detail (Ex02), please wait for maintenance.";
        } elseif ($result == "EMPTY") {
          $data['success'] = TRUE;
          $data['messages'] = "Review is empty.";
          $data['row'] = $singleReview;
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Retrieving review detail is success.";
          $data['row'] = $singleReview;
          $data['data'] = $result;
        }

      }
      
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Diskusi Agen data
   */

  /**
   * Function - Menampilkan daftar diskusi masing-masing agen
   * 
   * @param string $kode_agen     Kode agen
   *
   * @return JSON data
   */
  public function retrieveDiskusiAgen ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      // Retrieve diskusi agen
      $result = $this->ModelSystemRetriever->retrieveDiskusiAgen ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Diskusi Agen, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Diskusi Agen is empty.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Diskusi Agen is success.";
        $data['data'] = $result;
      }
      
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Menampilkan jumlah diskusi berdasarkan kode_agen
   * 
   * @param string $kode_agen  Kode agen
   *
   * @return JSON data
   */
  public function retrieveJumlahDiskusi ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

    // Prepare data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve jumlah balasan diskusi
      $result = $this->ModelSystemRetriever->retrieveJumlahDiskusi ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving jumlah balasan diskusi, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve jumlah diskusi success.";
        $data['row'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Menampilkan detail diskusi agen 
   * 
   * @param string $kode_agen_diskusi     Kode diskusi pada tabel agen_diskusi
   *
   * @return JSON data
   */
  public function retrieveDiskusiAgenDetail ()
  {
    // Prepare request variable
    $kode_agen_diskusi = $this->input->post('kode_agen_diskusi');

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
      // Retrieve detil diskusi agen
      $singleDiskusi = $this->ModelSystemRetriever->retrieveSingleDiskusi ($kode_agen_diskusi);

      if ($singleDiskusi == FALSE) {
        $data['messages'] = "Error while retrieving diskusi agen detail (Ex01), please wait for maintenance.";
      } elseif ($singleDiskusi == "EMPTY") {
        $data['messages'] = "Diskusi Agen is empty.";
      } else {
        // Retrieve balasan diskusi agen
        $result = $this->ModelSystemRetriever->retrieveBalasanDiskusi ($kode_agen_diskusi);

        if ($result == FALSE) {
          $data['messages'] = "Error while retrieving diskusi agen detail (Ex03), please wait for maintenance.";
        } elseif ($result == "EMPTY") {
          $data['success'] = TRUE;
          $data['messages'] = "Error while retrieving diskusi agen detail (Ex02), please wait for maintenance.";
          $data['row'] = $singleDiskusi;
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Retrieve diskusi agen detail success.";
          $data['row'] = $singleDiskusi;
          $data['data'] = $result;
        }
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Layanan Data
   */
  
  /**
   * Function - Menampilkan layanan yang tersedia berdasarkan kode_agen
   *
   * @param String $kode_agen     Kode Agen
   * 
   * @return [JSON] 
   */
  public function retrieveLayanan ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');

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
      // Retrieve layanan
      $result = $this->ModelSystemRetriever->retrieveLayanan ($kode_agen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving data, please wait for maintenance.";  
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Layanan data for this agen is unavailable, please wait for new layanan to come.";
      } else {

        $data['success'] = TRUE;
        $data['messages'] = "Retrieve layanan is success.";
        $data['data'] = array (
            array(
              'satuan' => array (),
              'kiloan' => array (),
              'luas' => array ()
            )
          );
        $layanan_satuan = array ();
        
        foreach ($result as $row_layanan) {
          $layanan_satuan[$row_layanan['kode_layanan_grup']][] = $row_layanan;
        }
 
        foreach ($result as $key => $value) {
          if ($value['kode_jenis_layanan'] == 1) {
            if ($value['kode_layanan_grup'] != 0) {
              /*array_push(
                  $data['data']['satuan'],
                  array ($value['nama_grup'] => $layanan_satuan[$value['kode_layanan_grup']])
                );*/
              $data['data'][0]['satuan'][0][$value['nama_grup']] = $layanan_satuan[$value['kode_layanan_grup']];
            }
          } elseif ($value['kode_jenis_layanan'] == 2) {
            $data['data'][0]['kiloan'][0] = $value;
            // array_push($data['data']['kiloan'], $value);
          } else {
            $data['data'][0]['luas'][0] = $value;
            // array_push($data['data']['luas'], $value);
          }
        }
      }
    }

    echo json_encode ($data);
  }

  /**
   * Dompet data
   */
  
  /**
   * Function - Menampilkan daftar paket dompet
   * 
   * @return [JSON]
   */
  public function retrievePaketDompet ()
  {
    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Retrieve paket dompet
    $result = $this->ModelSystemRetriever->retrievePaketDompet ();

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving Paket Dompet, please wait for maintenance.";
    } elseif ($result == "EMPTY") {
      $data['messages'] = "Paket is unavailable.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve paket dompet is success.";
      $data['data'] = $result;
    }

    echo json_encode($data);    
  }

  /**
   * Kurir data
   */
  
  public function retrieveNearestKurir ()
  {
    // Prepare request variable
    $kode_pshcabang = $this->input->post('kode_pshcabang');
    $lat = $this->input->post('lat');
    $lng = $this->input->post('lng');

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
      // Retrieve nearest kurir
      $result = $this->ModelSystemRetriever->retrieveNearestKurir (
        $kode_pshcabang,
        $lat,
        $lng
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving kurir data, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Kurir currently unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve kurir is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }
  
  /**
   * Function - Mencari kurir berdasarkan nama
   * 
   * @param  String $nama                     Nama Kurir
   * 
   * @return [JSON]
   */
  public function retrieveKurirByName ()
  {
    // Prepare request variable
    $nama = $this->input->post('nama');

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
      // Retrieve detail kurir
      $result = $this->ModelSystemRetriever->retrieveKurirByName ($nama);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Kurir, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Kurir data is empty.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Kurir is success.";
        $data['data'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Menampilkan detil kurir
   * 
   * @param  String $kode_kurir                Kode Kurir
   * 
   * @return [JSON]
   */
  public function retrieveDetailKurir ()
  {
    // Prepare request variable
    $kode_kurir = $this->input->post('kode_kurir');

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
      // Retrieve detail kurir
      $result = $this->ModelSystemRetriever->retrieveDetailKurir ($kode_kurir);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Kurir's detail, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Kurir's data is empty.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Kurir's detail is success.";
        $data['row'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Parfum data
   */
  
  /**
   * Fun`ction - Retrieve parfum
   * 
   * @return JSON
   */
  public function retrieveParfum()
  {
    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Retrieve parfum
    $result = $this->ModelSystemRetriever->retrieveParfum ();

    if ($result == FALSE) {
      $data['messages'] = "Error while retrieving data, please wait for maintenance.";
    } elseif ($result == "EMPTY") {
      $data['messages'] = "Parfum data is empty.";
    } else {
      $data['success'] = TRUE;
      $data['messages'] = "Retrieve data is success.";
      $data['data'] = $result;
    }
    
    echo json_encode($data);
  }

  /**
   * Transaksi Data
   */
  
  /**
   * Function - Retrieve all transaction that not have paid yet
   * 
   * @param  String $kode_konsumen      Kode konsumen
   * 
   * @return JSON
   */
  public function retrieveUnpaidTransaksi ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

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
      // Retrieve unpaid transaction
      $result = $this->ModelSystemRetriever->retrieveUnpaidTransaksi ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving transaction, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Transactions data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve transaction is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode ($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Retrieve all transactions with transfer payment
   * 
   * @param  String $kode_konsumen    Kode konsumen
   * 
   * @return JSON
   */
  public function retrieveTransaksiWithTransfer ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

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
      // Retrieve unpaid transaction
      $result = $this->ModelSystemRetriever->retrieveTransaksiWithTransfer ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving transaction, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Transactions data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve transaction is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode ($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Complaint data
   */
  
  /**
   * Function - Retrieve all complaint by usertype and their code
   * 
   * @param  String $userdata         Tipe user
   * @param  String $kode_user        Kode user
   * 
   * @return JSON
   */
  public function retrieveComplaint ()
  {
    // Prepare request variable
    $usertype = $this->input->post('usertype');
    $kode_user = $this->input->post('kode_user');

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
      switch ($usertype) {
        case 'konsumen':
          $result = $this->ModelSystemRetriever->retrieveComplaint (
            "konsumen",
            $kode_user
            );
          break;
        
        case 'agen':
          $result = $this->ModelSystemRetriever->retrieveComplaint (
            "agen",
            $kode_user
            );
          break;

        case 'checker':
          $result = $this->ModelSystemRetriever->retrieveComplaint (
            "checker",
            $kode_user
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
   * Function - Retrieve complaint detail
   * 
   * @param  String $kode_transaksi_pengaduan     Kode pengaduan
   *
   * @return JSON
   */
  public function retrieveComplaintAgenDetail ()
  {
    // Prepare request variable
    $kode_transaksi_pengaduan = $this->input->post('kode_transaksi_pengaduan');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if result array is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve detil review agen
      $singleComplaint = $this->ModelSystemRetriever->retrieveSingleComplaintOnAgen ($kode_transaksi_pengaduan);

      if ($singleComplaint == FALSE) {
        $data['messages'] = "Error while retrieving review detail (Ex01), please wait for maintenance.";
      } elseif ($singleComplaint == "EMPTY") {
        $data['messages'] = "Review is empty.";
      } else {
        // Retrieve balasan review agen
        $result = $this->ModelSystemRetriever->retrieveBalasanComplaintOnAgen ($kode_transaksi_pengaduan);

        if ($result == FALSE) {
          $data['messages'] = "Error while retrieving review detail (Ex02), please wait for maintenance.";
        } elseif ($result == "EMPTY") {
          $data['success'] = TRUE;
          $data['messages'] = "Review is empty.";
          $data['row'] = $singleComplaint;
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Retrieving review detail is success.";
          $data['row'] = $singleComplaint;
          $data['data'] = $result;
        }
      }
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

}