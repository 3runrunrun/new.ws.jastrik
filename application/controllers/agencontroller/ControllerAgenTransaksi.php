<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAgenTransaksi extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Agen's Transaksi Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Transaksi Offline
   */
  
  /**
   * Function - Memasukkan data qrcode transaksi baru
   * 
   * @param  [String] $kode_agen         [Kode Agen]
   * @param  [String] $kode_konsumen     [Kode Konsumen]
   * 
   * @return [QRCODE]                    [Sukses/Tidak]
   */
  public function createQrCodeTransaksi ()
  {
    // Call qrcode libray
    $this->load->library ('qrcode');

    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $enkripsi = $this->qrcode->setEncrypt (
      $kode_agen 
      . $kode_konsumen
      );

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
      // Create new QrCode Transaksi
      $result = $this->ModelAgenTransaksi->createQrCodeTransaksi (
        $kode_agen,
        $kode_konsumen,
        $enkripsi
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while inserting new qrcode, please wait for maintenance.";
      } else {
        $text = "jastrik/" . $enkripsi; 
        $this->qrcode->setText ($text);
        $this->qrcode->setSize (140);
        $this->qrcode->setPadding (0);
        $qrURI = $this->qrcode->getUri ();

        $data['success'] = TRUE;
        $data['messages'] = "Create qrcode is success.";
        $data['row'][] = array (
          "uri" => $qrURI,
          "kode_qr_transaksi" => $result
          );

        echo json_encode($data, JSON_UNESCAPED_SLASHES);
      }
    }
  }

  /** 
   * Function - Create offline transaction
   * 
   * @param  String  $kode_konsumen     Kode konsumen
   * @param  String  $kode_agen         Kode agen
   * @param  String  $kode_kota         Kode kota
   * @param  JSON    $order             Order JSON
   * 
   * @return Boolean
   */
  public function createOfflineTransaksi ()
  {
    // Prepare kode_transaksi
    $kode_transaksi = "";

    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');
    $kode_agen = $this->input->post('kode_agen');
    $kode_kota = $this->input->post('kode_kota');
    $order = $this->input->post('order');

    // Prepare quota variable helper
    $accumulatedKilos = 0;
    $accumulatedPieces = 0;
    $accumulatedLength = 0;
    $accumulatedWidth = 0;
    $accumulatedWide = 0;

    // Set flag variable for transaction
    $isSuccess = FALSE;

    // JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
      goto end;
    } else {
      // Decoding order
      $decodedOrder = json_decode($order);

      // Delivery
      $tipe_antar = $decodedOrder->delivery->tipe_antar;
      $biaya_antar = 0;

      // Bayar
      $jenis_bayar = $decodedOrder->bayar->jenis_bayar;
      $bayar = $decodedOrder->bayar->bayar;

      // Check is antar-jemput
      if ($tipe_antar == "1") {
        $isAntarJemput = 2;
      } else {
        $isAntarJemput = 0;
      }
      
      // Calculate SUBTOTAL, longest service DURATION
      $subtotal = 0;
      $longestDurArr = array ();
      $longestDur = 0;

      // Calculate subtotal and stack duration of each service
      foreach ($decodedOrder->detail as $detailItem) {
        $resultHarga = $this->ModelKonsumenSystem->retrieveHargaLayanan($detailItem->kode_harga_layanan);
        $resultDurasi = $this->ModelKonsumenSystem->retrieveDurasiLayanan($detailItem->kode_harga_layanan);

        $tipe_layanan = strtolower($detailItem->tipe_layanan);
        $harga_layanan = $resultHarga[0]['harga_layanan'];
        $durasi_layanan = $resultDurasi[0]['durasi_layanan'];
        
        array_push($longestDurArr, $durasi_layanan);

        // echo $harga_layanan . "\n";

        switch ($tipe_layanan) {
          case 'kiloan':
            $kilos = $detailItem->jumlah;
            $subtotal += ($harga_layanan * $kilos);
            
            // Calculate KILOS qouta WILL be USED
            $accumulatedKilos += $kilos;
            break;

          case 'satuan':
            $pieces = $detailItem->jumlah_helai;
            $subtotal += ($harga_layanan * $pieces);

            // Calculate PIECES qouta WILL be USED
            $accumulatedPieces += $pieces;
            break;

          case 'luas':
            $length = $detailItem->panjang;
            $width = $detailItem->lebar;
            $wide = $length * $width / 100;
            
            if ($wide < 1) {
              $wide = 1;
            }

            $subtotal += ($harga_layanan * $wide);

            // Calculate WIDE quota WILL be USED
            $accumulatedWide += $wide;
            break;
          
          default:
            # code...
            break;
        }
      }

      // Calculate longest service duration
      $longestDur = max($longestDurArr);

      // Get available quota
      $piecesAvailable = $this->ModelSystemRetriever->retrieveKuota (
        $kode_agen,
        1);
      $kilosAvailable = $this->ModelSystemRetriever->retrieveKuota (
        $kode_agen,
        2);
      $wideAvailable = $this->ModelSystemRetriever->retrieveKuota (
        $kode_agen,
        3);

      // Get each quota USED
      $piecesUsed = $this->ModelSystemRetriever->retrieveKuotaTerpakai (
        $kode_agen,
        1
        );
      $kilosUsed = $this->ModelSystemRetriever->retrieveKuotaTerpakai (
        $kode_agen,
        2
        );
      $wideUsed = $this->ModelSystemRetriever->retrieveKuotaTerpakai (
        $kode_agen,
        3
        );

      if ($kilosUsed == FALSE
        || $piecesUsed == FALSE
        || $wideUsed == FALSE
        || $piecesAvailable == FALSE
        || $kilosAvailable == FALSE
        || $wideAvailable == FALSE) {
        $data['messages'] = "Error while retrieving quota used";
        goto end;
      } elseif ($kilosUsed == "EMPTY"
        || $piecesUsed == "EMPTY"
        || $wideUsed == "EMPTY"
        || $piecesAvailable == "EMPTY"
        || $kilosAvailable == "EMPTY"
        || $wideAvailable == "EMPTY") {
        $data['messages'] = "Quota used data is unavailable";
        goto end;
      } else {
        // Calculate available quota
        $piecesAvailable = $piecesAvailable[0]['kuota'];
        $kilosAvailable = $kilosAvailable[0]['kuota'];
        $wideAvailable = $wideAvailable[0]['kuota'];

        // Calculate total quota will be used
        $totalKilosUsed = $accumulatedKilos + $kilosUsed[0]['kuota_terpakai'];
        $totalPiecesUsed = $accumulatedPieces + $piecesUsed[0]['kuota_terpakai'];
        $totalWideUsed = $accumulatedWide + $wideUsed[0]['kuota_terpakai'];

        // Debugging Quota
        // echo "berat kuota_terpakai: " . $kilosUsed[0]['kuota_terpakai'] . "\n";
        // echo "helai kuota_terpakai: " . $piecesUsed[0]['kuota_terpakai'] . "\n";
        // echo "luas kuota_terpakai: " . $wideUsed[0]['kuota_terpakai'] . "\n";
        
        // Check if quota is sufficient
        if ($piecesAvailable < $totalPiecesUsed || $kilosAvailable < $totalKilosUsed || $wideAvailable < $totalWideUsed) {
          $data['messages'] = "Sorry, Agen quota is insufficient. Please try another agen service.";
          goto end;
        } else {
          // Which jenis_bayar do konsumen use
          switch ($jenis_bayar) {
            case 'cash':
              $saldo_dompet = 0;
              break;

            case 'transfer':
              $saldo_dompet = 0;
              break;

            case 'dompet':
              $resultSaldo = $this->ModelKonsumenDompet->retrieveSaldoDompet ($kode_konsumen);
              if ($resultSaldo == FALSE) {
                $data['messages'] = "Error while retrieving Saldo Dompet.";
                goto end;
              } else {
                // Prepare saldo_dompet
                $saldo_dompet = $resultSaldo[0]['saldo_dompet'];
              }
              break;
            
            default:
              # code...
              break;
          }

          // Create kode_transaksi
          $resultCode = $this->ModelKonsumenSystem->codeGenerator ('transaksi');
          $kode_transaksi = "INV/" . substr_replace($resultCode, "$kode_kota/", 0, 4);

          // Prepare tanggal_terima transaksi
          $tanggal_terima = date("Y-m-d H:i:s");

          // Create transaction
          $result = $this->ModelAgenTransaksi->createOfflineTransaction (
            $kode_transaksi,
            $kode_konsumen,
            $kode_agen,
            $jenis_bayar, 
            $subtotal,
            $biaya_antar,
            $saldo_dompet,
            $isAntarJemput,
            $longestDur,
            $tanggal_terima,
            $bayar
            );

          if ($result == FALSE) {
            $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err01)";
            goto end;
          } else {
            // Create transaction on transaksi_layanan
            foreach ($decodedOrder->detail as $detailItem) {
              $resultHarga = $this->ModelKonsumenSystem->retrieveHargaLayanan($detailItem->kode_harga_layanan);

              $tipe_layanan = strtolower($detailItem->tipe_layanan);
              $harga_layanan = $resultHarga[0]['harga_layanan'];

              switch ($tipe_layanan) {
                case 'kiloan':
                  $kilos = $detailItem->jumlah;
                  $pieces = $detailItem->jumlah_helai;
                  $harga = $harga_layanan * $kilos;

                  $resultTransLayanan = $this->ModelAgenTransaksi->createTransactionOnLayanan (
                    $detailItem->kode_harga_layanan,
                    $kode_transaksi,
                    $kilos,
                    $pieces,
                    0,
                    0,
                    $harga
                    );
                  
                  /*echo $detailItem->kode_harga_layanan . "\n";
                  echo $kode_transaksi . "\n";
                  echo $kilos . "\n";
                  echo $pieces . "\n";
                  echo $harga . "\n";
                  echo $resultTransLayanan . "\n";*/


                  if ($resultTransLayanan == FALSE) {
                    $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#01)";
                    $isSuccess = FALSE;
                    break;
                  } else {
                    foreach ($detailItem->item as $itemList) {
                      $resultItemLayanan = $this->ModelAgenTransaksi->createTransactionOnItem (
                        $resultTransLayanan,
                        $kode_transaksi,
                        $itemList->kode_item,
                        $itemList->jumlah
                        );
                      if ($resultItemLayanan == FALSE) {
                        $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#02)";
                        $isSuccess = FALSE;
                        break;
                      } else {
                        $isSuccess = TRUE;
                      }
                    }
                  }
                  break;

                case 'satuan':
                  $pieces = $detailItem->jumlah_helai;
                  $harga = $harga_layanan * $pieces;

                  $resultTransLayanan = $this->ModelAgenTransaksi->createTransactionOnLayanan (
                      $detailItem->kode_harga_layanan,
                      $kode_transaksi,
                      0,
                      $pieces,
                      0,
                      0,
                      $harga);
                  if ($resultTransLayanan == FALSE) {
                    $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#03)";
                    $isSuccess = FALSE;
                    break;
                  } else {
                    $isSuccess = TRUE;
                  }
                  break;

                case 'luas':
                  $length = $detailItem->panjang;
                  $width = $detailItem->lebar;
                  $wide = $length * $width / 100;

                  if ($wide < 1) { $wide = 1; }
                  
                  $harga = $harga_layanan * $wide;

                  $resultTransLayanan = $this->ModelAgenTransaksi->createTransactionOnLayanan (
                    $detailItem->kode_harga_layanan,
                    $kode_transaksi,
                    0,
                    0,
                    $length,
                    $width,
                    $harga
                    );

                  if ($resultTransLayanan == FALSE) {
                    $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#04)";
                    $isSuccess = FALSE;
                    goto end;
                  }
                  $isSuccess = TRUE;
                  break;

                default:
                  # code...
                  break;
              }
            }
          }
        }
      }

      if ($isSuccess == FALSE) {
        $resultDeleteTransaction = $this->ModelAgenTransaksi->deleteOfflineTransaction ($kode_transaksi);
        if ($resultDeleteTransaction == FALSE) {
          $data['messages'] = "Error while undone transaksi, please wait for maintenance.";
          goto end;
        } else {
          $data['messages'] = $data['messages'] . "Error while creating new transaction, please wait for maintenance. (err02#05)";
          goto end;
        }
      } else {
        // Retrieve konsumen's token
        $resultToken = $this->ModelSystem->retrieveTokenAgen (
          "konsumen",
          $kode_konsumen
          );

        if ($resultToken == FALSE) {
          $data['messages'] = "Error while retrieving token, konsumen may not receive any notification. (errTok01)";
        } elseif ($resultToken == "EMPTY") {
          $data['messages'] = "Agen token is unavailable. (errTok02)";
        } else {
          $token = $resultToken[0]['token'];

          // Prepare payload
          $notifPayload = array (
            "kode_transaksi" => $kode_transaksi,
            "tanggal_terima" => $tanggal_terima
            );
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Transaksi Offline Berhasil");
          $this->envelope->setMessage ("Hi! Transaksi offline anda berhasil kami simpan!");
          $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "transaksi_offline",
                  "data" => $encodedPayload
                )
          );

          $jasPayload = $this->envelope->getData ();

          $result = $this->firebase->sendDataSingle (
              'https://fcm.googleapis.com/fcm/send',
              $token,
              $jasPayload
            );

          $data['success'] = TRUE;
          $data['messages'] = "Congratulations, you complete your transaction. Thank you!";
          $data['row'][] = array ('kode_transaksi' => $kode_transaksi);
        }
      }
    }
    
    end:
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Endof - Transaksi Offline
   */
  
  /**
   * Function - Menampilkan detail transaksi yang akan diterima atau ditolak
   * 
   * @param  String $kode_transaksi         Kode Transaksi
   * 
   * @return [JSON]
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
      // Retrieve basic detail of transaction
      $resultBasic = $this->ModelAgenTransaksi->retrieveBasicDetailTransaksi ($kode_transaksi);
      
      // Retrieve layanan detail of transaction
      $resultLayanan = $this->ModelAgenTransaksi->retrieveLayananDetailTransaksi ($kode_transaksi);

      if ($resultBasic == FALSE
        || $resultLayanan == FALSE) {
        $data['messages'] = "Error while retrieving transaction detail, please wait for maintenance.";
      } elseif ($resultBasic == "EMPTY"
        || $resultLayanan == "EMPTY") {
        $data['messages'] = "Transaction data are unavailable.";
      } else {
        // Prepare list layanan
        $layananArr = array();

        // Set the layanan list
        foreach ($resultLayanan as $itemLayanan) {
          array_push($layananArr, $itemLayanan['nama_layanan']);
        } 
        
        $layanan = implode(",", $layananArr);

        $resultBasic[0]['layanan'] = $layanan;

        $data['success'] = TRUE;
        $data['messages'] = "Retrieve data is success.";
        $data['row'] = $resultBasic;
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Respond on transaction
   * 
   * @param  String $kode_transaksi         Kode transaksi
   * @param  String $kode_konsumen          Kode konsumen
   * @param  String $respon                 Respon transaksi ("0" / "1")
   * @param  String $kode_kurir             Kode kurir (opsional)
   * 
   * @return Boolean
   */
  public function createRespondTransaction ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $respon = $this->input->post('respon');

    // Prepare Konsumen's Notification Data
    $notifTitle = "";
    $notifMessage = "";

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod === FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve waiting list date
      $resultDate = $this->ModelAgenSystem->retrieveWaitingListDate ($kode_transaksi);

      if ($resultDate == FALSE) {
        $data['messages'] = "Error while retrieving date, please wait for maintenance.";
      } elseif ($resultDate == "EMPTY") {
        $data['messages'] = "The date is unavailable.";
      } else {
        $curdateTimeFormat = strtotime(date("Y-m-d H:i:s"));
        $tanggal_terima = strtotime($resultDate[0]['tanggal_terima']);
        $tanggal_expired = strtotime(date(
            "Y-m-d H:i:s", 
            strtotime(
              "+5 minutes", 
              $tanggal_terima
              )
            )
          );

        if ($curdateTimeFormat > $tanggal_expired) {
          $data['messages'] = "The transaction is expired, you cannot respond to this one.";
        } else {
          // Respond to transaction
          if ($respon == "0") {
            $result = $this->ModelAgenTransaksi->createRespondTransaction (
              $kode_transaksi,
              "20"
              );

            // Set konsumen's notification data
            $notifTitle = "Rejected Transaction";
            $notifMessage = "Sorry, your transaction rejected by agen. Please try agen, thank you.";

            // Prepare notification payload
            $resultCanceledLayanan = $this->ModelAgenSystem->retrieveCanceledLayanan ($kode_transaksi);
            $layanan['detail'] = $resultCanceledLayanan;
            $notifPayload = $layanan['detail'];

            // Prepare JSON message
            $data['messages'] = "Transaction rejected.";
          } elseif ($respon == "1") {
            // Prepare kode kurir
            $kode_kurir = $this->input->post('kode_kurir');

            // Set konsumen's notification data
            $notifTitle = "Transaction Accepted";
            $notifMessage = "Hi! Agen accepted your transaction, please wait for next process.";

            // Prepare notification payload
            $notifPayload = array ("kode_transaksi" => $kode_transaksi);

            // Retrieve information of antar-jemput transaksi
            $resultDelivery = $this->ModelAgenSystem->retrieveAntarJemputData ($kode_transaksi);

            if ($resultDelivery == FALSE) {
              $data['messages'] = "Error while retrieving antar-jemput data, please wait for maintenance.";
            } elseif ($resultDelivery == "EMPTY") {
              $data['messages'] = "Konsumen didn't request antar-jemput.";
            } else {
              // Prepare payload
              $payload['delivery'] = $resultDelivery;

              foreach ($payload as $itemDelivery) {
                $kode_alamat_jemput = $itemDelivery->kode_alamat_jemput;
                $kode_alamat_antar = $itemDelivery->kode_alamat_antar;
              }

              /*echo $kode_alamat_jemput . "\n";
              echo $kode_alamat_antar . "\n";*/

              if ($kode_alamat_antar == NULL
                && $kode_alamat_jemput != "") {
                $jenis_jemput = "1";
                $jenis_antar = "0";
              } elseif ($kode_alamat_antar != NULL
                && $kode_alamat_jemput == "") {
                $jenis_jemput = "0";
                $jenis_antar = "1";
              } elseif ($kode_alamat_antar != NULL
                && $kode_alamat_jemput != "") {
                $jenis_jemput = "1";
                $jenis_antar = "1";
              }

              /*echo $kode_kurir . "\n";
              echo $jenis_antar . "\n";
              echo $jenis_jemput . "\n";*/

              // Assign kurir to transaction
              $result = $this->ModelAgenTransaksi->createRespondTransaction (
                $kode_transaksi,
                "1",
                $kode_kurir,
                $jenis_antar,
                $jenis_jemput
                );

              if ($result == FALSE) {
                $data['messages'] = "Error while assigning kurir to transaction, please wait for maintenance.";
              } else {
                /** 
                 * Notifikasi kurir
                 */

                // Get token kurir
                $resultTokenKurir = $this->ModelSystem->retrieveTokenAgen (
                  "kurir",
                  $kode_kurir
                  );

                if ($resultTokenKurir == FALSE) {
                  $data['messages'] = "Kurir successfuly assigned to a transaction, but kurir won't notified, because error while retrieving token.";
                } elseif ($resultTokenKurir == "EMPTY") {
                  $data['messages'] = "Kurir successfuly assigned to a transaction, but kurir won't notified, because token is unavailable.";
                } else {
                  $token = $resultTokenKurir[0]['token'];

                  // Prepare payload
                  // $notifPayloadKurir = $payload['delivery'];
                  $encodedPayload = json_encode ($payload);

                  // echo json_encode($notifPayloadKurir);

                  $this->load->library('envelope');
                  $this->load->library('firebase');

                  $this->envelope->setTitle ("Request Jemput");
                  $this->envelope->setMessage ("Hi! Agen memberikan anda transaksi untuk dijemput.");
                  $this->envelope->setData (
                      array (
                          "title" => $this->envelope->getTitle(),
                          "message" => $this->envelope->getMessage(),
                          "timestamp" => date("Y-m-d H:i:s"),
                          "type" => "assign_kurir",
                          "data" => $encodedPayload
                        )
                    );

                  $jasPayload = $this->envelope->getData ();

                  $resultFirebase = $this->firebase->sendDataSingle (
                      'https://fcm.googleapis.com/fcm/send',
                      $token,
                      $jasPayload
                    );

                  $data['success'] = TRUE;
                  $data['messages'] = "Transaction accepted.";
                }
              }
            }
          }

          /**
           * Notifikasi Konsumen
           */

          // Retrieve konsumen's token
          $resultTokenKonsumen = $this->ModelSystem->retrieveTokenAgen (
            "konsumen",
            $kode_konsumen
            );

          if ($resultTokenKonsumen == FALSE) {
            $data['messages'] = $data['messages'] . " Konsumen won't notified. (errKon#1)";
          } elseif ($resultTokenKonsumen == "EMPTY") {
            $data['messages'] = $data['messages'] . " Konsumen won't notified. (errKon#1)";
          } else {
            $token = $resultTokenKonsumen[0]['token'];

            // print_r($notifPayload);
            // Prepare payload
            $encodedPayload = json_encode ($notifPayload);

            $this->load->library('envelope');
            $this->load->library('firebase');

            $this->envelope->setTitle ($notifTitle);
            $this->envelope->setMessage ($notifMessage);
            $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "update_transaksi",
                  "data" => $encodedPayload
                )
            );

            $jasPayload = $this->envelope->getData ();

            $result = $this->firebase->sendDataSingle (
                'https://fcm.googleapis.com/fcm/send',
                $token,
                $jasPayload
              );
          }
        }
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Update transaction's status
   * 
   * @param  String $kode_transaksi           Kode transaksi
   * @param  String $status_transaksi_lama    Status transaksi sebelumnya
   * @param  String $status_transaksi_baru    Status transaksi baru
   * 
   * @return JSON
   */
  public function updateStatusTransaksi ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $status_transaksi_lama = $this->input->post('status_transaksi_lama');
    $status_transaksi_baru = $this->input->post('status_transaksi_baru');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      if ($status_transaksi_baru == "5") {
        $resultKodeDana = $this->ModelAgenRetriever->retrieveTotalAndKodeAgen ($kode_transaksi);

        if ($resultKodeDana == FALSE) {
          $data['messages'] = "Error while retrieving Kode Agen, please wait for maintenance.";
          goto end;
        } elseif ($resultKodeDana == "EMPTY") {
          $data['messages'] = "Kode Agen is unavailable.";
          goto end;
        } else {
          // Retrieve jenis_bayar
          $resultJenisBayar = $this->ModelAgenSystem->retrieveJenisBayar ($kode_transaksi);

          if ($resultJenisBayar == FALSE) {
            $data['messages'] = "Error while retrieving jenis bayar, please wait for maintenance.";
            goto end;
          } elseif ($resultJenisBayar == "EMPTY") {
            $data['messages'] = "Jenis bayar unavailable.";
            goto end;
          } else {
            $jenis_bayar = $resultJenisBayar[0]['jenis_bayar'];
          }
          
          $kode_agen = $resultKodeDana[0]['kode_agen'];
          $total = $resultKodeDana[0]['total'];

          // Update transaction's status
          $result = $this->ModelAgenTransaksi->updateStatusTransaksi (
            $kode_transaksi,
            $status_transaksi_lama,
            $status_transaksi_baru,
            $kode_agen,
            $total,
            $jenis_bayar
            );
        }
      } else {
        // Update transaction's status
        $result = $this->ModelAgenTransaksi->updateStatusTransaksi (
          $kode_transaksi,
          $status_transaksi_lama,
          $status_transaksi_baru
          );
      }

      if ($result == FALSE) {
        $data['messages'] = "Error while updating status, please wait for maintenance.";
      } else {
        $resultTokenKonsumen = $this->ModelSystem->retrieveTokenByTransaksi (
          "konsumen",
          $kode_transaksi
          );

        if ($resultTokenKonsumen == FALSE) {
          $data['messages'] = "Status updated, but konsumen won't notified. (#err01)";
        } elseif ($resultTokenKonsumen == "FALSE") {
          $data['messages'] = "Status updated, but konsumen won't notified. (#err02)";
        } else {
          $token = $resultTokenKonsumen[0]['token'];

          // Prepare payload
          $notifPayload = array (
            'kode_transaksi' => $kode_transaksi,
            'status_transaksi' => $status_transaksi_baru
            );
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Update Status Transaksi");
          $this->envelope->setMessage ("Hi! Transaksi anda telah diproses ke tahap selanjutnya. Terima kasih telah mempercayakan pelayanan anda kepada kami.");
          $this->envelope->setData (
            array (
              "title" => $this->envelope->getTitle(),
              "message" => $this->envelope->getMessage(),
              "timestamp" => date("Y-m-d H:i:s"),
              "type" => "update_status_transaksi",
              "data" => $encodedPayload
              )
            );

          $jasPayload = $this->envelope->getData ();

          $result = $this->firebase->sendDataSingle (
            'https://fcm.googleapis.com/fcm/send',
            $token,
            $jasPayload
            );

          $data['messages'] = "Status update is success.";
        }

        $data['success'] = TRUE;
      }
    }
    
    end:
    echo json_encode($data);
  }

  /**
   * Function - Accept deposit request from checker
   * 
   * @param  String $kode_agen_setoran_dana     Kode request setoran dana
   * @param  String $kode_agen                  Kode agen
   * @param  Float  $nominal                    Nominal setoran
   * @param  String  $kode_checker              Kode checker
   * 
   * @return JSON
   */
  public function createRequestDepositResponse ()
  {
    // Prepare request variable
    $kode_agen_setoran_dana = $this->input->post('kode_agen_setoran_dana');
    $kode_agen = $this->input->post('kode_agen');
    $nominal = $this->input->post('nominal');
    $kode_checker = $this->input->post('kode_checker');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = 'Please fill your form completely.';
    } else {
      // Accept deposit request from checker
      $result = $this->ModelAgenTransaksi->createRequestDepositResponse (
        $kode_agen_setoran_dana,
        $kode_agen,
        $nominal
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while accepting request, please wait for maintenance.";
      } else {
        // Retrieve konsumen's token
        $resultToken = $this->ModelSystem->retrieveTokenAgen (
          "checker",
          $kode_checker
          );

        if ($resultToken == FALSE) {
          $data['success'] = TRUE;
          $data['messages'] = "Error while retrieving token, checker may not receive any notification. (errTok01)";
        } elseif ($resultToken == "EMPTY") {
          $data['success'] = TRUE;
          $data['messages'] = "Checker token is unavailable, checker may not receive any notification. (errTok02)";
        } else {
          $token = $resultToken[0]['token'];

          // Prepare payload
          $notifPayload = array (
            "kode_agen_setoran_dana" => $kode_agen_setoran_dana,
            "status" => "success"
            );
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Request Setoran Dana Agen");
          $this->envelope->setMessage ("Hi! Request setoran anda diterima oleh agen!");
          $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "accept_request_penarikan",
                  "data" => $encodedPayload
                )
          );

          $jasPayload = $this->envelope->getData ();

          $result = $this->firebase->sendDataSingle (
              'https://fcm.googleapis.com/fcm/send',
              $token,
              $jasPayload
            );

          $data['success'] = TRUE;
          $data['messages'] = "You accepted deposit request";
          $data['data'][] = array (
            "kode_agen_setoran_dana" => $kode_agen_setoran_dana,
            "status" => "success"
            );
        }
      }
    }

    echo json_encode($data);
  }

}