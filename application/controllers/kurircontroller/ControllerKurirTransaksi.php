<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKurirTransaksi extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Transaksi Kurir Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('Kurir', 'MLG');
  }

  /** 
   * Function - Update transaction status due the acceptance of kurir
   * 
   * @param  String $kode_transaksi       Kode transaksi
   * @param  String $tipe_antar_jemput    tipe antar jemput (antar / jemput)
   * @param  String $respon               0 (ditolak) / 1 (diterima)
   * 
   * @return JSON
   */
  public function createRespondTransaction ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $tipe_antar_jemput = $this->input->post('tipe_antar_jemput');
    $respon = $this->input->post('respon');

    // Prepare notification title & message
    $notifTitle = NULL;
    $notifMessage = NULL;

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
      // Check respon value
      if ($respon == "0") {
        $notifTitle = "Kurir Menolak Permintaan Antar-Jemput";
        $notifMessage = "Hi! Kurir menolak permintaan $tipe_antar_jemput untuk transaksi $kode_transaksi. Mohon coba kurir yang lain.";
        $data['messages'] = "Kurir rejected request.";

        if ($tipe_antar_jemput == "jemput") {
          $status_transaksi = "21";
        } elseif ($tipe_antar_jemput == "antar") {
          $status_transaksi = "23";
        }
      } else {
        $notifTitle = "Kurir Menerima Permintaan Antar-Jemput";
        $notifMessage = "Hi! Kurir menerima permintaan $tipe_antar_jemput untuk transaksi $kode_transaksi. Terima kasih.";

        if ($tipe_antar_jemput == "jemput") {
          $status_transaksi = "2";
        } elseif ($tipe_antar_jemput == "antar") {
          $status_transaksi = "9";
        }

        // Create respon transaction
        $result = $this->ModelKurirTransaksi->createRespondTransaction (
          $kode_transaksi,
          $status_transaksi
          );

        if ($result == FALSE) {
          $data['messages'] = "Error while creating respond, please wait for maintenance.";
        } else {
          // Prepare konsumen token
          $resultTokenKonsumen = $this->ModelSystem->retrieveTokenByTransaksi (
            "konsumen",
            $kode_transaksi
            );

          if ($resultTokenKonsumen == FALSE) {
            $data['messages'] = "Respond created successfuly, but konsumen won't notified. (#err01)";
          } elseif ($resultTokenKonsumen == "EMPTY") {
            $data['messages'] = "Respond created successfuly, but konsumen won't notified. (#err02)";
          } else {
            $token = $resultTokenKonsumen[0]['token'];

            $this->load->library('envelope');
            $this->load->library('firebase');

            $this->envelope->setTitle ("Permintaan Antar-Jemput Diterima");
            $this->envelope->setMessage ("Hi! Permintaan $tipe_antar_jemput transaksi anda diterima oleh kurir, kurir akan datang sesuai dengan jam dan tanggal yang anda isi.");
            $this->envelope->setData (
              array (
                "title" => $this->envelope->getTitle(),
                "message" => $this->envelope->getMessage(),
                "timestamp" => date("Y-m-d H:i:s"),
                "type" => "respon_kurir"
                )
              );

            $jasPayload = $this->envelope->getData ();

            $result = $this->firebase->sendDataSingle (
              'https://fcm.googleapis.com/fcm/send',
              $token,
              $jasPayload
              );

            $data['success'] = TRUE;
            $data['messages'] = "Respond created, konsumen notified.";
          }
        }
      }

      // Prepare agen token
      $resultTokenAgen = $this->ModelSystem->retrieveTokenByTransaksi (
        "agen",
        $kode_transaksi
        );

      if ($resultTokenAgen == FALSE) {
        $data['messages'] = $data['messages'] . " Agen won't notified (#err03)";
      } elseif ($resultTokenAgen == "EMPTY") {
        $data['messages'] = $data['messages'] . " Agen won't notified (#err04)";
      } else {
        $token = $resultTokenAgen[0]['token'];

        // Prepare payload
        $notifPayload = array ('kode_transaksi' => $kode_transaksi);
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
            "type" => "respon_kurir",
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
    
    echo json_encode($data);
  }

  /**
   * Function - Memasukkan data qrcode transaksi baru
   * 
   * @param  String $kode_kurir        Kode kurir
   * @param  String $kode_konsumen     Kode konsumen
   * 
   * @return Base64
   */
  public function createQrCodeTransaksi ()
  {
    // Call qrcode libray
    $this->load->library ('qrcode');

    // Prepare request variable
    $kode_kurir = $this->input->post('kode_kurir');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $enkripsi = $this->qrcode->setEncryptKurir (
      $kode_kurir 
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
      $result = $this->ModelKurirTransaksi->createQrCodeTransaksi (
        $kode_kurir,
        $kode_konsumen,
        $enkripsi
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while inserting new qrcode, please wait for maintenance.";
      } else {
        $text = "jaswallet/" . $enkripsi; 
        $this->qrcode->setText ($text);
        $this->qrcode->setSize (140);
        $this->qrcode->setPadding (0);
        $qrURI = $this->qrcode->getUri ();

        $data['success'] = TRUE;
        $data['messages'] = "Create qrcode is success.";
        $data['row'][] = array (
          "uri" => $qrURI,
          "kode_qr_transaksi_kurir" => $result
          );

        echo json_encode($data, JSON_UNESCAPED_SLASHES);
      }
    }
  }

  /**
   * Function - Update biaya transaksi
   * 
   * @param  String $kode_transaksi   Kode transaksi
   * @param  String $kode_konsumen    Kode konsumen
   * @param  String $isHapus          Indikator adakah layanan yang dihapus
   * @param  JSON   $order            detil pesanan
   * 
   * @return JSON
   */
  public function checkoutTransaction ()
  {
    // Prepare request variable
    $kode_transaksi = $this->input->post('kode_transaksi');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $isHapus = $this->input->post('isHapus');
    $order = $this->input->post('order');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      if ($isHapus == "1") {
        $resultDeleteLayanan = $this->ModelKurirTransaksi->deleteLayananTransaksi ($kode_transaksi_layanan);
      }

      // Prepare subtotal, total, kembalian 
      $subtotal = 0;
      $total = 0;
      $kembalian = 0;

      // Decoding order
      $decodedOrder = json_decode($order);

      // Parsing "bill" key
      $diskon = $decodedOrder->bill->diskon;
      $biaya_antar = $decodedOrder->bill->biaya_antar;
      $pajak = $decodedOrder->bill->pajak;
      $jenis_bayar = $decodedOrder->bill->jenis_bayar;
      $nominal_bayar = $decodedOrder->bill->nominal_bayar;

      /*echo "subtotal: " . $subtotal . "\n";
      echo "diskon: " . $diskon . "\n";
      echo "biaya_antar: " . $biaya_antar . "\n";
      echo "pajak: " . $pajak . "\n";
      echo "total: " . $total . "\n";*/

      // Parsing "detail" key
      foreach ($decodedOrder->detail as $detailItem) {
        $totalJumlahKilo = 0;

        if ($detailItem->jumlah != "0" // Kiloan
          || $detailItem->jumlah != 0) {
          // Parsing "item" key
          foreach ($detailItem->item as $itemList) {
            $resultCreateItem = $this->ModelKurirTransaksi->createItemLayanan (
              $detailItem->kode_transaksi_layanan,
              $kode_transaksi,
              $itemList->kode_item,
              $itemList->jumlah
              );
            if ($resultCreateItem == FALSE) {
              $data['messages'] = "Error while adding item, please wait for maintenance";
              goto end;
              break;
            }
            // $totalJumlahKilo += $itemList->jumlah; // This is bug
          }

          $totalJumlahKilo = $detailItem->jumlah; // This is bug

          // Calculate subtotal
          $subtotal += $totalJumlahKilo * $detailItem->harga;
        } elseif ($detailItem->panjang != "0" // Luas
          && $detailItem->lebar != "0") {
          // Calculate subtotal
          $subtotal += ($detailItem->panjang * $detailItem->lebar / 100) * $detailItem->harga;
        } elseif ($detailItem->jumlah_helai != "0" // Satuan
          && $detailItem->jumlah == "0") {
          // Calculate subtotal
          $subtotal += $detailItem->jumlah_helai * $detailItem->harga;
        }
        
        // Updating layanan
        $resultUpdateLayanan = $this->ModelKurirTransaksi->updateLayananTransaksi (
          $detailItem->kode_transaksi_layanan,
          $totalJumlahKilo,
          $detailItem->jumlah_helai,
          $detailItem->panjang,
          $detailItem->lebar,
          $subtotal
          );
        if ($resultUpdateLayanan == FALSE) {
          $data['messages'] = "Error while updating layanan transaksi, please wait for maintenance";
          goto end;
          break;
        }
      }

      // Calculate 
      $total = ($subtotal * (1 - $diskon) + $biaya_antar) * (1 + $pajak);

      /*echo "subtotal: " . $subtotal . "\n";
      echo "diskon: " . $diskon . "\n";
      echo "biaya_antar: " . $biaya_antar . "\n";
      echo "pajak: " . $pajak . "\n";
      echo "total: " . $total . "\n";*/

      if ($jenis_bayar == "0") {
        $kembalian = $nominal_bayar - $total;
      } elseif ($jenis_bayar == "1") {
        $nominal_bayar = 0;
        $kembalian = 0;
      } else {
        $nominal_bayar = $total;
        $kembalian = 0;
      }

      $resultUpdateLayanan = $this->ModelKurirTransaksi->updateBiayaTransaksi (
        $kode_transaksi,
        $kode_konsumen,
        $subtotal,
        $diskon,
        $biaya_antar,
        $pajak,
        $total,
        $nominal_bayar,
        $kembalian,
        $jenis_bayar
        );

      if ($resultUpdateLayanan == FALSE) {
        $data['messages'] = "Error while updating biaya transaksi, please wait for maintenance";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Checkout success.";
      }
    }

    end:
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
      // Update transaction status
      $result = $this->ModelAgenTransaksi->updateStatusTransaksi (
        $kode_transaksi,
        $status_transaksi_lama,
        $status_transaksi_baru
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while updating transaction status, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Transaction status updated.";
      }
    }

    echo json_encode($data);
  }

}