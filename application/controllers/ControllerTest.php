<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerTest extends CI_Controller {

  public function __construct ()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index()
  {
    // $this->load->view('upload_form');
    $this->load->view('notif_form');
    // $this->ModelSystem->tes();
    /*$curdateTimeFormat = strtotime(date("Y-m-d H:i:s"));
    $tanggal_terima = strtotime("2017-02-01 23:25:46");
    $tanggal_expired = date(
      "Y-m-d H:i:s", 
      strtotime(
        "+5 minutes", 
        $tanggal_terima
        )
      );

    if ($curdateTimeFormat > strtotime($tanggal_expired)) {
      echo $tanggal_terima . "\n";
      echo strtotime($tanggal_expired) . "\n";
      echo $tanggal_expired . "\n";
      echo "The transaction is expired, you cannot respond to this one.";
    }*/
    // Random number for beli dompet
    // $microtime = substr(microtime(),5,3);
    // echo $microtime . "\n";
    
    /* Replace string */
    // $var = 'INV/fasdfafd7077985';
    // echo "Original: $var<hr />\n";

    /* These two examples replace all of $var with 'bob'. */
    // $a = substr_replace($var, 'MLG/', 0, 4);
    // echo "INV/" . $a;
  }

  public function cobaAbse ()
  {
    $kode_agen = 'AGN/KPN/161208131039173921';

    $r1 = $this->ModelCheckerRetriever->retrieveJumlahTransaksiMasukAbs ($kode_agen);
    $r2 = $this->ModelCheckerRetriever->retrieveJumlahTransaksiDikerjakanAbs ($kode_agen);
    $r3 = $this->ModelCheckerRetriever->retrieveJumlahTransaksiSelesaiAbs ($kode_agen);
    $r4 = $this->ModelCheckerRetriever->retrieveJumlahSolvedComplainAbs ($kode_agen);

    if ($r1 == FALSE
      || $r2 == FALSE
      || $r3 == FALSE
      || $r4 == FALSE) {
      return "FALSE";
    } elseif ($r1 == "EMPTY"
      || $r2 == "EMPTY"
      || $r3 == "EMPTY"
      || $r4 == "EMPTY") {
      return "EMPTY";
    } else {
      print_r($r1);
      echo "<br />";
      print_r($r2);
      echo "<br />";
      print_r($r3);
      echo "<br />";
      print_r($r4);
    }
    
  }

  //  Dari tabel pendaftaran_agen
  public function retrieveKodeAgen ()
  {
    $result = $this->ModelTest->retrieveKodeAgen ();
    foreach ($result as $key => $value) {
      // print_r($value);
      echo $value['kode_agen'] . "<br />";
    }
  }

  public function testpost ()
  {
    $email = $this->input->post('email');
    if (!empty($email)) {
      echo "YES";
    } else {
      echo "Oops";  
    }
  }

  public function tokenByPengaduan ()
  {
    $ar = array ();
    $result = $this->ModelSystem->retrieveTokenByPengaduan(4);
    foreach ($result as $key) {
      array_push($ar, $key['token_agen']);
      array_push($ar, $key['token_konsumen']);
    }
    print_r($ar);
  }

  public function singleDevice ()
  {
    $kode_transaksi = $this->input->post('kode_transaksi');
    $token = $this->input->post('token');
    $type = $this->input->post('type');
    $datanya = array (
        "type" => $type,
        "kode_transaksi" => $kode_transaksi
      );

    $enc = json_encode($datanya);

    $this->load->library('envelope');
    $this->load->library('firebase');

    $this->envelope->setTitle ("Coba Kirim Notif Payload");
    $this->envelope->setMessage ("Bisa tidak ya?");
    $this->envelope->setData (
        array (
            "title" => $this->envelope->getTitle(),
            "message" => $this->envelope->getMessage(),
            "timestamp" => date("Y-m-d H:i:s"),
            "type" => $type,
            "kode_transaksi" => $kode_transaksi,
            "data" => $enc
          )
      );

    $jasNotif = $this->envelope->getNotification ();
    $jasPayload = $this->envelope->getData ();

    $result = $this->firebase->sendDataSingle (
      'https://fcm.googleapis.com/fcm/send',
      $token,
      $jasPayload
      );

    echo json_encode($result);
  }

  public function multiDevice ()
  {
    $this->load->library('envelope');
    $this->load->library('firebase');

    $this->envelope->setTitle ('Multi Fathir');
    $this->envelope->setMessage ('Isi multi fathir');
    $this->envelope->setData (
      array (
        'title' => 'Judul data',
        'message' => 'Isi data'
        )
      );

    $letter = $this->envelope->getLetter ();

    $result = $this->firebase->sendToMany (
      'https://android.googleapis.com/gcm/send',
      array (
        '1L6k7RGw3k:APA91bHBZB9H_vdRejtSm6-fVweAaNymiGvRM18KCrn86lubkEQsktFbGPcb34rT8irXXD8vw3kU8VCe2oBNrr_cQ7f9RRKsq2s7PMtBRfXPTWK0lpkTPwIISP7-AlC77RVShTS_0uo3',
        'ctuRZgWJ0U8:APA91bEnR8if5H4zjkbaQYu4uZmy23GY1nmlY5az1KoNf52MnQf-LY4q87SordQkYc6qq5FNEl_xAZ7Zj4D4KM1b4SRAoGJzFXtlM39Tj_eF7e-SefG4VicnXnxlWwH4fKnUcgEVEMsm' // This is client from another apps (MismatchSenderID)
        ),
      $letter
      );

    echo json_encode($result, JSON_UNESCAPED_SLASHES);
  }

  public function createQr (
    $text,
    $size,
    $padding
    )
  {
    $this->load->library ('qrcode');

    $this->qrcode->setText ($text);
    $this->qrcode->setSize ($size);
    $this->qrcode->setPadding ($padding);
    $this->qrcode->render ();
  }

  public function getDistance()
  {
    $this->load->library('DistanceCounter');

    $lat1 = -7.9424412;
    $lng1 = 112.5781896;
    $lat2 = -7.9606108;
    $lng2 = 112.6026616;

    $result = $this->distancecounter->generateDistance (
      $lat1,
      $lng1,
      $lat2,
      $lng2
      );

    echo json_encode($result);
  }

  public function retrieveKuota ()
  {
    $kode_agen = $this->input->post('kode_agen');
    $kode_jenis_layanan = $this->input->post('kode_jenis_layanan');

    $result = $this->ModelSystem->retrieveKuota (
      $kode_agen,
      $kode_jenis_layanan
      );

    if ($result == FALSE) {
      return FALSE;
    } elseif ($result == "EMPTY") {
      return "No quota";
    } else {
      $data['row'] = $result;
      echo json_encode($data);
    }
  }

  public function retrieveKuotaTerpakai ()
  {
    $kode_agen = $this->input->post('kode_agen');

    $result = $this->ModelSystem->retrieveKuotaTerpakai ($kode_agen);

    if ($result == FALSE) {
      return FALSE;
    } elseif ($result == "EMPTY") {
      return "No quota";
    } else {
      $data['row'] = $result;
      echo json_encode($data);
    }
  }

  public function retrieveCanceledLayanan ()
  {
    $kode_transaksi = $this->input->post('kode_transaksi');
    $result = $this->ModelAgenSystem->retrieveCanceledLayanan ($kode_transaksi);
    $data['data'] = $result;
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  public function tesEncoding()
  {
    $kode_konsumen_alamat = "yayayayayay";
    $kode_transaksi = "yayayayayay";
    $catatan = "yayayayayay";
    $notelp = "yayayayayay";
    $tanggal_transaksi_antar = "yayayayayay";
    $latitude = "yayayayayay";
    $longitude = "yayayayayay";
    $arr['ahy'] = array (
        "kode_transaksi" => $kode_transaksi,
        "notelp" => $notelp,
        "kode_alamat_jemput" => $kode_konsumen_alamat,
        "tanggal_transaksi_jemput" => $tanggal_transaksi_antar,
        "latitude_jemput" => $latitude,
        "longitude_jemput" => $longitude,
        "catatan_jemput" => $catatan
      );

    /*$enc = json_encode($arr);
    $payload['delivery'] = $enc;*/
    echo json_encode($arr, JSON_UNESCAPED_SLASHES);
  }

  public function tesSingleReview ()
  {
    $singleComplaint = $this->ModelSystemRetriever->retrieveSingleComplaintOnAgen (1);

    echo $singleComplaint;
  }

  public function testJasaCalculator ()
  {
    $this->load->library("JasCalculator");
    $calc = new JasCalculator();

    echo $calc->test();
  }
}