<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKonsumenTransaksi extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Konsumen Transaksi Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('konsumen', 'MLG');
  }

  /**
   * Function - Retrieve all transaksi data by kode konsumen
   * 
   * POST
   * @param string $kode_konsumen    kode konsumen 
   * 
   * @return JSON data
   */
  public function retrieveTransaksi ()
  {
    // Prepare method variable
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
      // Retrieve history transaksi
      $result = $this->ModelKonsumenTransaksi->retrieveTransaksi ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving transaction history.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve transaction history is successful.";
        $data['row'] = $result;
      }
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
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
   * Function - Retrieve all transaction that's not rejected nor finished
   * 
   * @param  String $kode_konsumen          Kode konsumen
   * 
   * @return JSON
   */
  public function retrieveTransaksiAktif ()
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
      // Retreive all active transaction
      $result = $this->ModelKonsumenTransaksi->retrieveTransaksiAktif ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving transaction, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "Data is unavailable.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieving data is success.";
        $data['data'] = $result;
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Menampilkan transaksi konsumen dengan agen tertentu
   *
   * @param String $kode_konsumen   Kode konsumen
   * @param String $kode_agen       Kode agen
   *
   * @return JSON data
   */
  public function retrieveTransaksiOnAgen ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');
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
      // Check if any transaction
      $result = $this->ModelKonsumenTransaksi->retrieveTransaksiOnAgen(
          $kode_konsumen,
          $kode_agen
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieve transaction, please wait for maintenance.";
      } elseif ($result == "EMPTY") {
        $data['messages'] = "You've not have any transaction with this agen.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve transaction is success.";
        $data['data'] = $result;
      }
    }
        
    echo json_encode($data);
  }

  public function retrieveBiayaAntarJemput ()
  {
    // Prepare request variable
    $kode_agen = $this->input->post('kode_agen');
    $latitudeAgen = $this->input->post('latitudeAgen');
    $longitudeAgen = $this->input->post('longitudeAgen');
    $latitudeKonsumen = $this->input->post('latitudeKonsumen');
    $longitudeKonsumen = $this->input->post('longitudeKonsumen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    /**
     * Cek request variable here
     */
    
    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Prepare kode cabang
      $resultKodeCabang = $this->ModelAgenSystem->retrieveKodeCabang ($kode_agen);

      if ($resultKodeCabang == FALSE) {
        $data['messages'] = "Error while retrieving Kode Cabang, please wait for maintenance.";
      } elseif ($resultKodeCabang == "EMPTY") {
        $data['messages'] = "Kode Cabang is unavailable.";
      } else {
        // Prepare kode cabang
        $kode_pshcabang = $resultKodeCabang[0]['kode_pshcabang'];

        // Prepare distanceCounter class
        $this->load->library('DistanceCounter');
        $distanceCounter = new DistanceCounter();

        // Prepare jarak
        $distanceValues = $distanceCounter->generateDistance(
          $latitudeAgen,
          $longitudeAgen,
          $latitudeKonsumen,
          $longitudeKonsumen
          );
        $distKonsumenToAgen = $distanceValues['distance']; // distance konsumen to agen

        // Retrieve tarif antar-jemput
        $result = $this->ModelSystemRetriever->retrieveTarifAntarJemput ($kode_pshcabang);

        if ($result == FALSE) {
          $data['messages'] = "Error while retrieving tarif minimum, please wait for maintenance.";
        } elseif ($result == "EMPTY") {
          $data['messages'] = "Data is empty.";
        } else {
          $tarif_minimal = $result[0]['tarif_minimal'];
          $jarak_minimal = $result[0]['jarak_minimal'];
          $tarif_per_km = $result[0]['tarif_per_km'];
        }

        // Calculate tariff
        if ($distKonsumenToAgen < $jarak_minimal) {
          $biayaAntarJemput = $tarif_minimal;
        } else {
          $biayaPerKilo = ($distKonsumenToAgen - $jarak_minimal) * $tarif_per_km;
          $biayaAntarJemput = $tarif_minimal + $biayaPerKilo;
        }

        $returnValue = array (
          'distance' => $distKonsumenToAgen,
          'estimasi_biaya_antar_jemput' => $biayaAntarJemput
          );
        
        $data['success'] = TRUE;
        $data['messages'] = "Calculating delivery tariff is success.";
        $data['row'][] = $returnValue;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Transaksi Onsite dengan dompet
   */
  
  /**
   * Function - Scan QrCode, update scan to 1
   *
   * @param [String] $enkripsi    [Kode enkripsi]
   * 
   * @return [JSON]
   */
  public function updateScanQrCode ()
  {
    // Prepare request variable
    $enkripsi = $this->input->post('enkripsi');
    $prefix = $this->input->post('prefix');

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

      switch ($prefix) {
        case 'jastrik':
          // Check if kode enkripsi is scannable
          $scannable = $this->ModelKonsumenSystem->checkQrStatus (
            "OT",
            $enkripsi
            );
          break;

        case 'jaswallet':
          // Check if kode enkripsi is scannable
          $scannable = $this->ModelKonsumenSystem->checkQrStatus (
            "CT",
            $enkripsi
            );
          break;
        
        default:
          # code...
          break;
      }

      if ($scannable === FALSE) {
        $data['messages'] = "Error while scanning code (erx01).";
      } elseif ($scannable == "EMPTY") {
        $data['messages'] = "The code is unavailable, please do transaction one more time.";
      } elseif ($scannable[0]['jml_scan'] == 0) {
        $data['messages'] = "The code is already scanned.";
      } else {
        // Update scan
        $result = $this->ModelKonsumenTransaksi->updateScanQrCode (
          $prefix,
          $enkripsi
          );

        if (!$result) {
          $data['messages'] = "Error while scanning code (erx02).";
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Scanning complete.";
        }
      }
    }

    echo json_encode($data);
  }
  
  /**
   * End Of - Transaksi Onsite dengan dompet
   */
  

  /**
   * Online transaction
   */
  
  public function createOnlineTransaction ()
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
      $telp = $decodedOrder->delivery->telp;
      $kode_alamat_jemput = $decodedOrder->delivery->kode_alamat_jemput;
      $tanggal_transaksi_jemput = $decodedOrder->delivery->tanggal_transaksi_jemput;
      $latitude_jemput = $decodedOrder->delivery->latitude_jemput;
      $longitude_jemput = $decodedOrder->delivery->longitude_jemput;
      $catatan_jemput = $decodedOrder->delivery->catatan_jemput;
      $kode_alamat_antar = $decodedOrder->delivery->kode_alamat_antar;
      $biaya_antar = $decodedOrder->delivery->estimasi_biaya_antar_jemput;

      // Bayar
      $jenis_bayar = $decodedOrder->bayar->jenis_bayar;

      // Check is antar-jemput
      if ($kode_alamat_jemput != "" AND $kode_alamat_antar == "") {
        $isAntarJemput = 1;
      } elseif ($kode_alamat_jemput == "" AND $kode_alamat_antar != "") {
        $isAntarJemput = 2;
      } elseif ($kode_alamat_jemput != "" AND $kode_alamat_antar != "") {
        $isAntarJemput = 3;
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
              $resultArrears = $this->ModelKonsumenSystem->checkIfAnyDompetArrears ($kode_konsumen);

              if ($resultArrears == FALSE) {
                $data['messages'] = "Error while checking arrears.";
                goto end;
              } else {
                $resultSaldo = $this->ModelKonsumenDompet->retrieveSaldoDompet ($kode_konsumen);
                if ($resultSaldo == FALSE) {
                  $data['messages'] = "Error while retrieving Saldo Dompet.";
                  goto end;
                } else {
                  // Prepare saldo_dompet
                  $saldo_dompet = $resultSaldo[0]['saldo_dompet'];
                }
              }
              break;
            
            default:
              # code...
              break;
          }

          // Create kode_transaksi
          $resultCode = $this->ModelKonsumenSystem->codeGenerator ('transaksi');
          $kode_transaksi = "INV/" . substr_replace($resultCode, "$kode_kota/", 0, 4);

          // Create transaction
          $result = $this->ModelKonsumenTransaksi->createOnlineTransaction (
            $kode_transaksi,
            $kode_konsumen,
            $kode_agen,
            $jenis_bayar, 
            $subtotal,
            $biaya_antar,
            $saldo_dompet,
            $isAntarJemput,
            $kode_alamat_jemput,
            $catatan_jemput,
            $telp,
            $tanggal_transaksi_jemput,
            $latitude_jemput,
            $longitude_jemput,
            $kode_alamat_antar,
            $longestDur
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

                  $resultTransLayanan = $this->ModelKonsumenTransaksi->createTransactionOnLayanan (
                      $detailItem->kode_harga_layanan,
                      $kode_transaksi,
                      $kilos,
                      $pieces,
                      0,
                      0,
                      $harga);

                  if ($resultTransLayanan == FALSE) {
                    $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#01)";
                    $isSuccess = FALSE;
                    break;
                  } else {
                    $isSuccess = TRUE;
                  }
                  break;

                case 'satuan':
                  $pieces = $detailItem->jumlah_helai;
                  $harga = $harga_layanan * $pieces;

                  $resultTransLayanan = $this->ModelKonsumenTransaksi->createTransactionOnLayanan (
                      $detailItem->kode_harga_layanan,
                      $kode_transaksi,
                      0,
                      $pieces,
                      0,
                      0,
                      $harga);
                  if ($resultTransLayanan == FALSE) {
                    $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#02)";
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

                  $resultTransLayanan = $this->ModelKonsumenTransaksi->createTransactionOnLayanan (
                    $detailItem->kode_harga_layanan,
                    $kode_transaksi,
                    0,
                    0,
                    $length,
                    $width,
                    $harga
                    );

                  if ($resultTransLayanan == FALSE) {
                    $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#03)";
                    $isSuccess = FALSE;
                    break;
                  } else {
                    $isSuccess = TRUE;
                  }
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
        $resultDeleteTransaction = $this->ModelKonsumenTransaksi->deleteOnlineTransaction ($kode_transaksi);
        if ($resultDeleteTransaction == FALSE) {
          $data['messages'] = "Error while undone transaksi, please wait for maintenance.";
          goto end;
        } else {
          $data['messages'] = "Error while creating new transaction, please wait for maintenance. (err02#04)";
          goto end;
        }
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
          $notifPayload = array ("kode_transaksi" => $kode_transaksi);
          $encodedPayload = json_encode ($notifPayload);

          $this->load->library('envelope');
          $this->load->library('firebase');

          $this->envelope->setTitle ("Transaksi Baru");
          $this->envelope->setMessage ("Hi! Konsumen membutuhkan anda!");
          $this->envelope->setData (
              array (
                  "title" => $this->envelope->getTitle(),
                  "message" => $this->envelope->getMessage(),
                  "timestamp" => date("Y-m-d H:i:s"),
                  "type" => "transaksi_baru",
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
   * End of - Online transaction
   */
  
  /**
   * Request antar transaksi
   */
  
  /**
   * Function - Insert data regard of request finished transaction delivery 
   * 
   * @param  String $respon                     Respon (antar / ambil sendiri)
   * @param  String $kode_konsumen_alamat       Kode alamat konsumen
   * @param  String $kode_transaksi             Kode transaksi
   * @param  String $catatan                    catatan pengantaran
   * @param  String $notelp                     nomor telepon
   * @param  String $tanggal_transaksi_antar    tanggal pengantaran
   * @param  String $latitude                   latitude
   * @param  String $longitude                  longitude
   *
   * @return [type] [description]
   */
  public function createRespondFinishedTransaction ()
  {
    // Prepare request variable
    $respon = $this->input->post('respon');
    $kode_konsumen_alamat = $this->input->post('kode_konsumen_alamat');
    $kode_transaksi = $this->input->post('kode_transaksi');
    $catatan = $this->input->post('catatan');
    $notelp = $this->input->post('notelp');
    $tanggal_transaksi_antar = $this->input->post('tanggal_transaksi_antar');
    $latitude = $this->input->post('latitude');
    $longitude = $this->input->post('longitude');

    // Prepare notification titel and message
    $notifTitle = "Respon Transaksi Konsumen";
    $notifMessage = NULL;

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if reqeuest variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Request transaction delivery
      $result = $this->ModelKonsumenTransaksi->createRespondFinishedTransaction (
        $respon,
        $kode_konsumen_alamat,
        $kode_transaksi,
        $catatan,
        $notelp,
        $tanggal_transaksi_antar,
        $latitude,
        $longitude
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while requesting delivery, please wait for maintenance.";
      } else {
        $resultTokenAgen = $this->ModelSystem->retrieveTokenByTransaksi (
          "agen",
          $kode_transaksi
          );

        if ($resultTokenAgen == FALSE) {
          $data['messages'] = "Request delivered, agen may not received, please wait for maintenance. (#err01)";
        } elseif ($resultTokenAgen == "EMPTY") {
          $data['messages'] = "Request delivered, agen may not received, please wait for maintenance. (#err02)";
        } else {
          // Prepare token
          $token = $resultTokenAgen[0]['token'];

          $this->load->library('envelope');
          $this->load->library('firebase');

          if ($respon == "1") {
            $notifMessage = "Hi! Konsumen membutuhkan bantuan anda untuk memproses pengantaran transaksi, terima kasih.";

            // Prepare payload
            $payload['delivery'] = array (
              "kode_transaksi" => $kode_transaksi,
              "notelp" => $notelp,
              "kode_alamat_jemput" => $kode_konsumen_alamat,
              "tanggal_transaksi_jemput" => $tanggal_transaksi_antar,
              "latitude_jemput" => $latitude,
              "longitude_jemput" => $longitude,
              "catatan_jemput" => $catatan
              );
            $encodedPayload = json_encode ($payload);

            $this->envelope->setData (
              array (
                "title" => $this->envelope->getTitle(),
                "message" => $this->envelope->getMessage(),
                "timestamp" => date("Y-m-d H:i:s"),
                "type" => "respon_konsumen_antar",
                "data" => $encodedPayload
                )
              );
          } else {
            $notifMessage = "Hi! Konsumen akan mengambil sendiri order yang telah selesai.";
            $this->envelope->setData (
              array (
                "title" => $this->envelope->getTitle(),
                "message" => $this->envelope->getMessage(),
                "timestamp" => date("Y-m-d H:i:s"),
                "type" => "respon_konsumen_ambil"
                )
              );
          }

          $this->envelope->setTitle ($notifTitle);
          $this->envelope->setMessage ($notifMessage);
          
          $jasPayload = $this->envelope->getData ();

          $result = $this->firebase->sendDataSingle (
            'https://fcm.googleapis.com/fcm/send',
            $token,
            $jasPayload
            );

          $data['messages'] = "Request delivered.";
        }

        $data['success'] = TRUE;
      }
    }

    echo json_encode($data);
  }

  /**
   * End of - Request antar transaksi
   */
  
  /**
   * Complaint-related data
   */
  
  /**
   * Function - Retrieve uncomplained invoice 
   * 
   * @param  String $kode_konsumen    Kode konsumen
   * 
   * @return JSON
   */
  public function retrieveUncomplainedTransaksi ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieve uncomplained invoice
      $result = $this->ModelKonsumenTransaksi->retrieveUncomplainedTransaksi ($kode_konsumen);

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
   * Konfirmasi transaksi dengan transfer
   */
  
  /**
   * Function - Confirming transaction payment with transfer method
   * 
   * @param  String  $kode_transaksi        Kode transaksi
   * @param  Integer $kode_konsumen_bank    Kode bank konsumen
   * @param  Integer $kode_bank_pusat       Kode bank pusat
   * 
   * @return JSON
   */
  public function createKonfirmasiTransferTransaksi ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $kode_konsumen_bank = $this->input->post('kode_konsumen_bank');
    $kode_bank_pusat = $this->input->post('kode_bank_pusat');

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
      // Confirming transaction payment with transfer method
      $result = $this->ModelKonsumenTransaksi->createKonfirmasiTransferTransaksi (
          $kode_transaksi,
          $kode_konsumen_bank,
          $kode_bank_pusat
          );

      if ($result == FALSE) {
        $data['messages'] = "Error while confirming transaction payment, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Payment is confirmed.";
      }
    }
    
    echo json_encode($data);
  }

}