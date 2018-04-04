<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerKonsumenProfile extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Konsumen Controller Profile. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);

    // $this->ModelSystem->userCodeGenerator('konsumen', 'MLG');
  }

  /**
   * Function - Update Konsumen Profile
   *
   * @param no params, accept POST value from client
   * 
   * @return JSON
   */
  public function updateKonsumenProfile ()
  {
    // TEST
    // $_POST['fcm'] = 'KNS/MLG/20161014214907333930';
    // $_POST['nama'] = 'Fathir';

    // prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if request variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = 'Please fill your form completely.';
    } else {      
      // Retrieve variable name as a field name
      $varName = $this->ModelSystem->retrieveVariableName ($this->input->post());

      if ($varName == FALSE) {
        $data['messages'] = 'Cannot retrieve variable name. Please wait for maintenance';
      } else {
        
        // Prepare request variable
        $fcm = $this->input->post('fcm');
        $fieldname = $varName;
        $newvalue = $this->input->post($varName);

        $result = $this->ModelKonsumenProfile->updateKonsumenProfile (
          $fcm,
          $fieldname,
          $newvalue
          );

        // Check if update is success
        if ($result == FALSE) {
          $data['messages'] = 'Error while updating data. Please wait for maintenance';
        } else {
          $data['success'] = TRUE;
          $data['messages'] = 'Update data success.';
        }

      }

    }

    echo json_encode($data);
  }

  /**
  * Konsumen's Alamat management
  */

  /**
   * Function - Create Konsumen's Alamat
   * 
   * POST
   * @param string $kode_konsumen     Kode Konsumen
   * @param string $kode_kota         Kode Kota
   * @param string $alamat            Alamat
   * @param string $kelurahan         Nama Kelurahan
   * @param string $kecamatan         Nama Kecamatan
   * @param string $kodepos           Kodepos
   * 
   * @return JSON
   */
  public function createAlamat ()
  {
    // TEST
    // $_POST['kode_konsumen'] = "KNS/JKT/20161018123856604725";
    // $_POST['kode_kota'] = "JKT";
    // $_POST['alamat'] = "jakarta";
    // $_POST['kelurahan'] = "kelurahan";
    // $_POST['kecamatan'] = "kecamatan";
    // $_POST['kodepos'] = "65141";

    // Prepare request variable
    $kode_konsumen = $this->input->post('kode_konsumen');
    $kode_kota = $this->input->post('kode_kota');
    $alamat = $this->input->post('alamat');
    $kelurahan = $this->input->post('kelurahan');
    $kecamatan = $this->input->post('kecamatan');
    $kodepos = $this->input->post('kodepos');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check request method
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Check if alamat is exist
      $checkIfAlamatExist = $this->ModelKonsumenSystem->checkIfAlamatExist ($kode_konsumen);

      if ($checkIfAlamatExist == FALSE) {
        $def = "1";
      } else {
        $def = "0";
      }

      // Insert new alamat
      $result = $this->ModelKonsumenProfile->createAlamat (
        $kode_konsumen,
        $kode_kota,
        $alamat,
        $kelurahan,
        $kecamatan,
        $kodepos,
        $def
        ); 

      if ($result == FALSE) {
        $data['messages'] = "Error while inserting alamat. Please wait for maintenance";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Insert alamat success";
        $data['kode_konsumen_alamat'] = $result;
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Update Konsumen's Alamat
   * 
   * POST
   * @param string $kode_konsumen_alamat    Kode Konsumen Alamat
   * @param string $kode_kota               Kode Kota
   * @param string $alamat                  Alamat
   * @param string $kelurahan               Nama Kelurahan
   * @param string $kecamatan               Nama Kecamatan
   * @param string $kodepos                 Kodepos
   * 
   * @return JSON
   */
  public function updateAlamat ()
  {
    // TEST
    // $_POST['kode_konsumen_alamat'] = '2';
    // $_POST['kode_kota'] = 'MLG';
    // $_POST['alamat'] = 'Singosari';
    // $_POST['kelurahan'] = 'Tumapel';
    // $_POST['kecamatan'] = 'Singosari';
    // $_POST['kodepos'] = '65141';

    // Prepare request variable
    $kode_konsumen_alamat = $this->input->post('kode_konsumen_alamat');
    $kode_kota = $this->input->post('kode_kota');
    $alamat = $this->input->post('alamat');
    $kelurahan = $this->input->post('kelurahan');
    $kecamatan = $this->input->post('kecamatan');
    $kodepos = $this->input->post('kodepos');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check request method
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Update alamat
      $result = $this->ModelKonsumenProfile->updateAlamat (
        $kode_konsumen_alamat,
        $kode_kota,
        $alamat,
        $kelurahan,
        $kecamatan,
        $kodepos
        ); 

      if ($result == FALSE) {
        $data['messages'] = "Error while updating alamat. Please wait for maintenance";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Update alamat success";
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Delete Konsumen's Alamat
   * 
   * POST
   * @param string $kode_konsumen_alamat    Kode Konsumen Alamat
   * 
   * @return JSON
   */
  public function deleteAlamat ()
  {
    // TEST
    // $_POST['kode_konsumen_alamat'] = "16";

    // Prepare request variable
    $kode_konsumen_alamat = $this->input->post('kode_konsumen_alamat');

    // Prepare JSON variable
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check request method
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Delete alamat
      $result = $this->ModelKonsumenProfile->deleteAlamat ($kode_konsumen_alamat);

      if ($result == FALSE) {
        $data['messages'] = "Error while deleting Alamat. Please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Deleting alamat success.";
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Change default address
   * 
   * @param  Int $kode_konsumen_alamat_new     Kode alamat default baru
   * @param  Int $kode_konsumen_alamat_old     Kode alamat default lama (opsional)
   * 
   * @return JSON
   */
  public function updateDefaultAlamat ()
  {
    // Prepare request variable
    $kode_konsumen_alamat_new = $this->input->post('kode_konsumen_alamat_new');
    $kode_konsumen_alamat_old = NULL;

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
      $kode_konsumen_alamat_old = $this->input->post('kode_konsumen_alamat_old');

      // Update default address
      if ($kode_konsumen_alamat_old == NULL) {
        $result = $this->ModelKonsumenProfile->updateDefaultAlamat (
          $kode_konsumen_alamat_new
          );
      } else {
        $result = $this->ModelKonsumenProfile->updateDefaultAlamat (
          $kode_konsumen_alamat_new,
          $kode_konsumen_alamat_old
          );
      }

      if ($result == FALSE) {
        $data['messages'] = "Error while changing default address, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Default alamat updated.";
      }
    }

    echo json_encode($data);
  }

  /**
   * Function - Retrieve Konsumen's Alamat
   * 
   * POST
   * @param string $kode_konsumen    Kode Konsumen
   * 
   * @return JSON
   */
  public function retrieveAlamat ()
  {
    // TEST
    // $_POST['kode_konsumen'] = "KNS/JKT/20161016075806869469";

    // Preparing request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

    // Preparing JSON data
    $data = array(
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if Request Variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Request variable is empty.";
    } else {
      // Check if Alamat Konsumen is available
      $result = $this->ModelKonsumenProfile->retrieveAlamat ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Alamat, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieving Alamat success.";
        $data['row'] = $result;
      } 
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  /**
  * Konsumen's Bank Account management
  */

  /**
   * Function - Create new Bank Account for Konsumen
   *
   * @param string $kode_jenis_bank   Kode Jenis Bank
   * @param string $kode_konsumen     Kode Konsumen
   * @param string $norek             Nomor rekening
   * @param string $atas_nama         Nama untuk Akun Bank baru
   * 
   * @return JSON
   */
  public function createRekening ()
  {
    // TEST
    /*$_POST['kode_jenis_bank'] = "2";
    $_POST['kode_konsumen'] = "KNS/JKT/20161016075806869469";
    $_POST['norek'] = "7034";
    $_POST['atas_nama'] = "Arap";*/

    // Prepare request variable
    $kode_jenis_bank = $this->input->post('kode_jenis_bank');
    $kode_konsumen = $this->input->post('kode_konsumen');
    $norek = $this->input->post('norek');
    $atas_nama = $this->input->post('atas_nama');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check request variable
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Insert new Bank Account
      $result = $this->ModelKonsumenProfile->createRekening (
        $kode_jenis_bank,
        $kode_konsumen,
        $norek,
        $atas_nama
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while creating new Bank Account.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Create new Bank Account is successful.";
        $data['kode_konsumen_bank'] = $result;
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Update Konsumen's Alamat
   * 
   * POST
   * @param string $kode_konsumen_bank    Kode tabel Konsumen Bank
   * @param string $kode_jenis_bank       new Kode Jenis Bank
   * @param string $norek                 new Nomor Rekening
   * @param string $atas_nama             new Atas Nama
   * 
   * @return JSON
   */
  public function updateRekening ()
  {
    // TEST
    /*$_POST['kode_konsumen_bank'] = "2";
    $_POST['kode_jenis_bank'] = "2";
    $_POST['norek'] = "9910";
    $_POST['atas_nama'] = "Fathir";*/

    // Prepare request variable
    $kode_konsumen_bank = $this->input->post('kode_konsumen_bank');
    $kode_jenis_bank = $this->input->post('kode_jenis_bank');
    $norek = $this->input->post('norek');
    $atas_nama = $this->input->post('atas_nama');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Check request variable
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Update Bank Account
      $result = $this->ModelKonsumenProfile->updateRekening (
        $kode_konsumen_bank,
        $kode_jenis_bank,
        $norek,
        $atas_nama
        );

      if ($result == FALSE) {
        $data['messages'] = "Error while updating Bank Account.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Update Bank Account is successful.";
      }
    }

    echo json_encode($data);
  }

  public function deleteRekening ()
  {
    // TEST
    // $_POST['kode_konsumen_bank'] = "";

    // Prepare request variable
    $kode_konsumen_bank = $this->input->post('kode_konsumen_bank');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // Checking request variable
    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Deleting Konsumen's Bank Account
      $result = $this->ModelKonsumenProfile->deleteRekening ($kode_konsumen_bank);

      if ($result == FALSE) {
        $data['messages'] = "Error while deleting Konsumen's Bank Account.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Delete Bank Account is successful.";
      }
    }
    
    echo json_encode($data);
  }

  /**
   * Function - Retrieve all Konsumen's Bank Account
   * 
   * POST
   * @param string $kode_konsumen    Kode Konsumen
   * 
   * @return JSON
   */
  public function retrieveRekening ()
  {
    // TEST
    // $_POST['kode_konsumen'] = "KNS/JKT/20161016075806869469";

    // Preparing request variable
    $kode_konsumen = $this->input->post('kode_konsumen');

    // Preparing JSON data
    $data = array(
      'success' => FALSE,
      'messages' => NULL
      );

    // Check if Request Variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "Request variable is empty.";
    } else {
      // Check if Rekening Konsumen is available
      $result = $this->ModelKonsumenProfile->retrieveRekening ($kode_konsumen);

      if ($result == FALSE) {
        $data['messages'] = "Error while retrieving Rekening, please wait for maintenance.";
      } else {
        $data['success'] = TRUE;
        $data['messages'] = "Retrieving Rekening success.";
        $data['row'] = $result;
      } 
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

}