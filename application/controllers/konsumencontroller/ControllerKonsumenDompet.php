<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKonsumenDompet extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Konsumen Dompet Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('konsumen', 'MLG');
  }

  /**
   * Transaksi pembelian saldo dompet
   */

  /**
   * Function - Request pembelian dompet
   *
   * @param string $kode_konsumen           Kode konsumen
   * @param string $kode_paket_dompet       Kode paket saldo dompet 
   * @param string $nominal                 Nominal saldo
   * @param string $harga                   Harga paket saldo dompet
   * @param string $email                   Kode dari tabel bank_pusat
   * 
   * @return JSON
   */
  public function createPembelianDompet ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');
    $kode_paket_dompet = $this->input->post('kode_paket_dompet');
    $nominal = $this->input->post('nominal');
    $harga = $this->input->post('harga');
    $email = $this->input->post('email');

    // Prepare harga transfer
    $kode_unik_transfer = substr(microtime(),5,3);
    $harga_transfer = $harga + intval($kode_unik_transfer);

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
      // Check if any arrears (Tunggakan Pembayaran Beli Dompet)
      $checkIfAnyArrears = $this->ModelKonsumenSystem->checkIfAnyArrears ($kode_konsumen);

      if ($checkIfAnyArrears == TRUE) {
        $data['messages'] = "Sorry, you have unpaid transaction. Please pay off your transaction.";
      } else {
        $kode_bld = $this->ModelKonsumenSystem->codeGenerator ("bld");

        // Transaction execution
        $result = $this->ModelKonsumenDompet->createPembelianDompet (
          $kode_bld,
          $kode_konsumen,
          $kode_paket_dompet,
          $nominal,
          $harga,
          $harga_transfer
          );

        if ($result == FALSE) {
          $data['messages'] = "Transaction fail, please wait for maintenance.";
        } else {
          $this->load->library('JastrikMailer');

          $mailer = new JastrikMailer();

          $mailer->setSender (
            'grc.jastrik@gmail.com', 
            'Golden River Corporation'
            );
          $mailer->setRecipientMail ($email);

          $mailer->setSubject ('Pembelian Saldo Dompet Berhasil');
          $mailer->setMailBody('Pembelian saldo dompet berhasil, mohon lakukan pembayaran dan konfirmasi pembayaran kepada kami.');
          $mailer->setAlternateBody('Pembelian saldo dompet berhasil, mohon lakukan pembayaran dan konfirmasi pembayaran kepada kami.');

          $mailerResult = $mailer->kirimEmail();

          if ($mailerResult == FALSE) {
            $data['success'] = TRUE;
            $data['messages'] = "Transaction success, customer may not receive email notification, please wait for the maintenance.";
          } else {
            $data['success'] = TRUE;
            $data['messages'] = "Transaction success, please complete your payment.";
            $data['row'] = array ('harga_transfer' => $harga_transfer);
          }
        }
      }
    }
    
    echo json_encode($data);
  }

  /**
    * @param  String  $kode_bld               Kode Pembelian Dompet
    * @param  String  $kode_konsumen_bank     Kode bank konsumen
    * @param  String  $harga_transfer         Nominal transfer (include kode unik)
    * @param  String  $kode_bank_pusat        Kode bank pusat (Tujuan transfer)
    * @param  String  $tanggal_transfer       Tanggal transfer
    * 
    * @return Boolean
   */
  public function createKonfirmasiPembayaranDompet ()
  {
    // Prepare request variable 
    $kode_bld = $this->input->post('kode_bld');
    $kode_konsumen_bank = $this->input->post('kode_konsumen_bank');
    $email = $this->input->post('email'); // masukkan ke email
    $harga_transfer = $this->input->post('harga_transfer'); 
    $kode_bank_pusat = $this->input->post('kode_bank_pusat');
    $tanggal_transfer = $this->input->post('tanggal_transfer');

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
      $kode_bbd = $this->ModelKonsumenSystem->codeGenerator ("bbd");

      // Konfirmasi pembayaran saldo dompet
      $result = $this->ModelKonsumenDompet->createKonfirmasiPembayaranDompet (
        $kode_bbd,
        $kode_bld,
        $kode_konsumen_bank,
        $kode_bank_pusat,
        $tanggal_transfer
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while sending confirmation, please wait for maintenance.";
      } else {
        $this->load->library('JastrikMailer');

        $mailer = new JastrikMailer();

        $mailer->setSender (
          'grc.jastrik@gmail.com', 
          'Golden River Corporation'
          );
        $mailer->setRecipientMail ($email);

        $mailer->setSubject ('Konfirmasi Pembayaran Berhasil');
        $mailer->setMailBody('Konfirmasi pembayaran berhasil, mohon tunggu pemrosesan dan konfirmasi dari layanan kami.');
        $mailer->setAlternateBody('Konfirmasi pembayaran berhasil, mohon tunggu pemrosesan dan konfirmasi dari layanan kami.');

        $mailerResult = $mailer->kirimEmail();

        if ($mailerResult == FALSE) {
          $data['success'] = TRUE;
          $data['messages'] = "Confirmation is success, customer may not receive email notification, please wait for the maintenance.";
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "Transaction success, please wait for our confirmation.";
        }
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Menambahkan URL foto pada tabel konsumen_bayar_beli_dompet
   *
   * @param string $kode_bld      Kode dari tabel konsumen_beli_dompet
   * @param string $foto          URL foto
   * 
   * @return JSON
   */
  public function uploadFotoPembayaranDompet ()
  {
    // Prepare request variable
    $kode_bld = $this->input->post('kode_bld');
    $foto = $this->input->post('foto');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please insert your transfer invoice.";
    } else {
      // Upload transfer invoice
      $result = $this->ModelKonsumenDompet->uploadFotoPembayaranDompet (
        $kode_bld,
        $foto
        );

      if ($result == FALSE) {
        $data['messages'] = "Sorry, your upload cannot be proceed, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Your transaction invoice has been uploaded, please wait for confirmation.";
      }
    }
    
    echo json_encode($data);
  }

  /**
   * End of - Transaksi pembelian saldo dompet
   */

  /**
   * Function - Menampilkan status pembelian dompet
   *
   * @param string $kode_bld      Kode dari tabel konsumen_beli_dompet
   * 
   * @return JSON
   */
  public function retrieveStatusBeliDompet ()
  {
    // Prepare request variable
    $kode_bld = $this->input->post('kode_bld');

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
      // Retrieve status beli dompet
      $result = $this->ModelKonsumenDompet->retrieveStatusBeliDompet ($kode_bld);
      $status_konsumen_beli_dompet = $result[0]['status_konsumen_beli_dompet'];

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving transaction status.";
      } else {
        switch ($status_konsumen_beli_dompet) {
          case '0':
            $data['messages'] = "Transaction Unconfirmed.";
            break;
          
          case '1':
            $data['messages'] = "Waiting for Confirmation from Jasa Setrika.";
            break;

          case '2':
            $data['messages'] = "Confirmed by Jasa Setrika.";
            break;

          default:
            $data['messages'] = "Transaction Done.";
            break;
        }

        $data['success'] = TRUE;
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Menampilkan daftar pembelian saldo dompet
   *
   * @param string $kode_konsumen      Kode konsumen
   *
   * @return JSON data
   */
  public function retrievePembelianDompet ()
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
      // Retrieve daftar pembelian saldo dompet
      $result = $this->ModelKonsumenDompet->retrievePembelianDompet ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving transaction hitory, please wait for maintenance.";
      } else if ($result == "EMPTY") {
        $data['messages'] = "You didn't buy anything yet.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieving transaction history is success.";
        $data['data'] = $result;
      }
    }
    
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Menampilkan daftar pemakaian saldo dompet
   *
   * @param string $kode_konsumen      Kode konsumen
   *
   * @return JSON data
   */
  public function retrievePemakaianDompet ()
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
      // Retrieving pemakaian dompet
      $result = $this->ModelKonsumenDompet->retrievePemakaianDompet ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Pemakaian Dompet, please wait for maintenance.";
      } elseif ($result ==  "EMPTY") {
        $data['messages'] = "You didn't do any transaction yet.";
      }else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieving Pemakaian Dompet is success.";
        $data['data'] = $result;
      } 
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Function - Menampilkan saldo dompet konsumen
   *
   * @param string $kode_konsumen      Kode konsumen
   * 
   * @return JSON
   */
  public function retrieveSaldoDompet ()
  {
    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkRequestMethod = $this->ModelSystem->checkRequestMethod($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Retrieving Saldo Dompet
      $result = $this->ModelKonsumenDompet->retrieveSaldoDompet ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Saldo Dompet.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieve Saldo Dompet is success.";
        $data['row'] = $result;
      }
    }
   
   echo json_encode($data); 
  }

}