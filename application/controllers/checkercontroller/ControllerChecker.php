<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerChecker extends CI_Controller {

  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index ()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Checker Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('checker', 'MLG');
  }

  /**
   * Function - Sign Up
   *
   * @param no params, accept POST value from client
   * 
   * @return JSON
   */
  public function signUp ()
  {
    // prepare flag variable
    $errorFlag = FALSE;

    // declare POST variable
    $nama_kota = $this->input->post('nama_kota');
    $kode_pshcabang = $this->input->post('kode_pshcabang');
    $nama = $this->input->post('nama');
    $alamat = $this->input->post('alamat');
    $notelp = $this->input->post('notelp');
    $tanggal_lahir = $this->input->post('tanggal_lahir');
    $email = $this->input->post('email');
    $fcm = $this->input->post('fcm');
    $file_ktp = $this->input->post('file_ktp');
    $file_kk = $this->input->post('file_kk');
    $kodepos = $this->input->post('kodepos');
    $foto = $this->input->post('foto');

    // prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // check if request variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Check if email is already registered
      $ifEmailRegistered = $this->ModelSystem->checkEmailExistent ('checker',
        $email);

      // Check if fcm is already registered
      $ifFCMRegistered = $this->ModelSystem->checkFCMExistent (
        'checker',
        $fcm
        );

      if ($ifEmailRegistered == TRUE OR $ifFCMRegistered == TRUE) {
        $data['messages'] = "Your email has been registered.";
      } else {
        // Retrieve kode_kota
        $kode_kota = $this->ModelSystemRetriever->retrieveKodeKota ($nama_kota);
        $kode_kota = $kode_kota[0]['kode_kota'];

        if ($kode_kota == FALSE) {
          $data['messages'] = "Error while retrieving Kode Kota.";
        } else {
          // generate new kode checker
          $kode_checker = $this->ModelSystem->userCodeGenerator('checker', $kode_kota);

          if ($kode_checker == FALSE) {
            $data['messages'] = "Cannot generate new code.";
          } else {
            // Create new Checker
            $result = $this->ModelChecker->createChecker 
              (
                $kode_checker,
                $kode_pshcabang,
                $nama,
                $alamat,
                $notelp,
                $tanggal_lahir,
                $email,
                $fcm,
                $file_ktp,
                $file_kk,
                $kodepos,
                $foto
              );

            if ($result == FALSE) {
              $data['messages'] = "Cannot create new Checker, please wait for the maintenance";
            } else {
              $errorFlag = TRUE;
              $data['success'] = TRUE;
              $data['messages'] = "Create Checker success, yay!";
            }
          }
        }
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Create deposit withdrawal request
   * 
   * @param  String $kode_agen                    Kode agen
   * @param  String $kode_checker                 Kode checker
   * @param  Float $nominal_agen_setoran_dana     Nominal setoran yang akan ditagih
   * 
   * @return JSON
   */
  public function requestDepositWithdrawal ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_checker = $this->input->post('kode_checker');
    $nominal_agen_setoran_dana = $this->input->post('nominal_agen_setoran_dana');

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
      // Generating request code
      $resultCode = $this->ModelCheckerSystem->codeGenerator ('rsda');
      $kode_agen_setoran_dana = $resultCode;

      // Create deposit withdrawal request
      $result = $this->ModelChecker->requestDepositWithdrawal (
        $kode_agen_setoran_dana,
        $kode_agen,
        $kode_checker,
        $nominal_agen_setoran_dana
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while requesting, please wait for maintenance.";
      } else {
        // Retrieve agen's token
        $resultToken = $this->ModelSystem->retrieveTokenAgen (
          "agen",
          $kode_agen
          );

          if ($resultToken == FALSE) {
            $data['messages'] = "Error while retrieving token, agen may not receive any notification. (errTok01)";
          } elseif ($resultToken == "EMPTY") {
            $data['messages'] = "Agen token is unavailable. (errTok02)";
          } else {
            $token = $resultToken[0]['token'];

            // Prepare payload
            $notifPayload = array ("kode_agen_setoran_dana" => $kode_agen_setoran_dana);
            $encodedPayload = json_encode ($notifPayload);

            $this->load->library('envelope');
            $this->load->library('firebase');

            $this->envelope->setTitle ("Request Penarikan Dana");
            $this->envelope->setMessage ("Hi! Checker akan mendatangi anda untuk penarikan dana hari ini!");
            $this->envelope->setData (
                array (
                    "title" => $this->envelope->getTitle(),
                    "message" => $this->envelope->getMessage(),
                    "timestamp" => date("Y-m-d H:i:s"),
                    "type" => "penarikan_dana_agen",
                    "data" => $encodedPayload
                  )
            );

            $jasPayload = $this->envelope->getData ();

            $resultNotif = $this->firebase->sendDataSingle (
                'https://fcm.googleapis.com/fcm/send',
                $token,
                $jasPayload
              );

          $data['messages'] = "Request accepted by system, please wait for Agen confirmation.";
        }
      }  
      
      $data['success'] = TRUE;
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Check transaction / Update checked status
   * 
   * @param  String $kode_transaksi     Kode transaksi
   * 
   * @return JSON
   */
  public function updateCheckTransaksi ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');

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
      // Check transaction
      $catatan = $this->input->post('catatan');

      if (empty($catatan)) {
        $catatan = NULL;
      }
      
      $result = $this->ModelChecker->updateCheckTransaksi (
        $kode_transaksi,
        $catatan
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while updating data, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Update is complete.";
      }  
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Create order invetory
   * 
   * @param  String $kode_checker                 Kode checker
   * @param  String $kode_agen                    Kode agen
   * @param  Float  $total                        Total tagihan order
   * 
   * @return Boolean
   */
  public function createOrderInventory ()
  {
    // Prepare request variable
    $kode_checker = $this->input->post('kode_checker');
    $kode_agen = $this->input->post('kode_agen');
    $order = $this->input->post('order');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Prepare order JSON to pass to model
    $order_model = array ('order' => NULL);

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Prepare array for detil order
      $array_order = array ();
      $orderTotalHarga = 0;

      // Decompose JSON order
      $decodeOrder = json_decode($order);

      foreach ($decodeOrder->order as $orderItem) {
        $resultHargaInv = $this->ModelCheckerSystem->retrieveInventoryPrice ($orderItem->kode_inventory_harga);
        $orderTotalHarga += $orderItem->jumlah * $resultHargaInv[0]['harga_inventory'];
      }

      // Compose order JSON
      $order_model['order'] = $array_order;
      $finalized_order = json_encode($order_model);

      // Generating request code
      $resultCode = $this->ModelCheckerSystem->codeGenerator ('koi');
      $kode_checker_order_inventory = $resultCode;

      // Create order inventory
      $result = $this->ModelChecker->createOrderInventory (
        $kode_checker_order_inventory,
        $kode_checker,
        $kode_agen,
        $orderTotalHarga
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while creating order, please wait for maintenance.";
      } else {
        foreach ($decodeOrder->order as $orderItem) {
          $resultHargaInv = $this->ModelCheckerSystem->retrieveInventoryPrice ($orderItem->kode_inventory_harga);
          $resultItem = $this->ModelChecker->createOrderInventoryItem (
            $kode_checker_order_inventory,
            $orderItem->kode_inventory_harga,
            $resultHargaInv[0]['harga_inventory'],
            $orderItem->jumlah
            );

          if ($resultItem == FALSE) {
            $data['messages'] = "Error while creating item list, please wait for maintenance.";
            $resultUndo = $this->ModelChecker->undoOrderInventory ($kode_checker_order_inventory);
            goto end;
          } else {
            $data['success'] = TRUE;
            $data['messages'] = "Order recorded into the system, please wait for JASTRIK confirmation.";
          }
        }
      }
    }

    end:
    echo json_encode($data);
  }

  /**
   * Function - Update agen's inventory stock
   * 
   * @param  String $kode_agen                Kode agen
   * @param  String $kode_checker             Kode checker
   * @param  JSON   $inventory_item           JSON inventory item
   * 
   * @return JSON
   */
  public function updateAgenInventoryStock ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_checker = $this->input->post('kode_checker');
    $inventory_item = $this->input->post('inventory_item');

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
      // Decoding inventory item JSON
      $decodedItem = json_decode($inventory_item);

      foreach ($decodedItem->inventory_item as $invItem) {
        // Generating inventory history code
        $kode_history_inventory = $this->ModelCheckerSystem->codeGenerator ('ahi');

        // Preparing variable
        $kode_inventory = $invItem->kode_inventory;
        $stok_keluar = $invItem->jumlah;

        // Update inventory stock
        $result = $this->ModelChecker->updateAgenInventoryStock (
          $kode_history_inventory,
          $kode_agen,
          $kode_inventory,
          $kode_checker,
          $stok_keluar
          );

        if ($result == FALSE) {
          $data['messages'] = "Error while updating data, please wait for maintenance.";
          break;
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Inventory stock is updated.";
        }
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Scanning qr_code visit / absen
   * 
   * @param  String  $enkripsi               Enkripsi
   * @param  String  $kode_agen              kode agen
   * @param  String  $kode_checker           Kode checker
   * 
   * @return JSON
   */
  public function scanQRAbsen ()
  {
    // Prepare request variable
    $enkripsi = $this->input->post('enkripsi');
    $kode_agen = $this->input->post('kode_agen');
    $kode_checker = $this->input->post('kode_checker');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check request variable
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      $resultTM = $this->ModelCheckerRetriever->retrieveJumlahTransaksiMasukAbs ($kode_agen);
      $resultOM = $this->ModelCheckerRetriever->retrieveOmzetMasukAbs ($kode_agen);
      $resultTK = $this->ModelCheckerRetriever->retrieveJumlahTransaksiDikerjakanAbs ($kode_agen);
      $resultTS = $this->ModelCheckerRetriever->retrieveJumlahTransaksiSelesaiAbs ($kode_agen);
      $resultSC = $this->ModelCheckerRetriever->retrieveJumlahSolvedComplainAbs ($kode_agen);

      if ($resultTM == FALSE
        || $resultOM == FALSE
        || $resultTK == FALSE
        || $resultTS == FALSE
        || $resultSC == FALSE) {
        $data['messages'] = "Error while calculating performance, please wait for maintenance.";
      } elseif ($resultTM == "EMPTY"
        || $resultOM == "EMPTY"
        || $resultTK == "EMPTY"
        || $resultTS == "EMPTY"
        || $resultSC == "EMPTY") {
        $data['messages'] = "Performance data is unavailable.";
      } else {
        // Generating code
        $kode_agen_absen = $this->ModelCheckerSystem->codeGenerator ('vis');

        // Prepare performance variable
        $transaksi_masuk = $resultTM[0]['jml_transaksi_masuk'];
        $omzet_masuk = $resultOM[0]['omzet_masuk'];
        $transaksi_dikerjakan = $resultTK[0]['jml_transaksi_dikerjakan'];
        $transaksi_selesai = $resultTS[0]['jml_transaksi_selesai'];
        $pengaduan_tuntas = $resultSC[0]['jml_pengaduan_tuntas'];

        // Insert Agen Absen
        $result = $this->ModelChecker->scanQRAbsen (
          $enkripsi,
          $kode_agen_absen,
          $kode_agen,
          $kode_checker,
          $transaksi_masuk,
          $omzet_masuk,
          $transaksi_dikerjakan,
          $transaksi_selesai,
          $pengaduan_tuntas
          );

        if ($result == FALSE) {
          $data['messages'] = "Error while adding visit data, please wait for maintenance.";
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Visit data added.";
        }
      }
    }
  }

}