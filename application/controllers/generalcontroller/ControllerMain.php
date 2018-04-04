<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerMain extends CI_Controller {
  
  public function __construct ()
  {
    parent::__construct();
    header('Content-Type: application/json');
  }

  public function index()
  {
    $data = array(
      'success' => TRUE,
      'messages' => "Welcome to Main Controller. You read this messages because you intend to, or you just forget to put some arguments. Check again, mate!"
      );

    echo json_encode($data);
  }

  /**
   * Function - Signing in user into system
   *
   * @param string $usertype  tipe user (konsumen, agen, etc.)
   * @param string $email     email yang masuk ketika sign in
   * @param string $fcm       fcm / UID Firebase
   * 
   * @return JSON(data)
   */
  public function signIn ()
  {
    // Prepare request variable
    $usertype = $this->input->post('usertype');
    $email = $this->input->post('email');
    $fcm = $this->input->post('fcm');

    // Prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    $checkRequestMethod = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkRequestMethod == FALSE) {
      $data['messages'] = "Please fill your form completely.";
    } else {
      // Set usertype to lower case
      // $usertype = strtolower($usertype);

      switch ($usertype) {
        case 'konsumen':
          // Check if user is exist
          $ifUserExist = $this->ModelSystem->checkUserExistent ($usertype,
            $email,
            $fcm
            );

          if ($ifUserExist == FALSE) {
            $data['messages'] = "User doesn't exist.";
            break;
          } else {
            // Check if Alamat Konsumen is available
            $result = $this->ModelSystem->checkAlamat (
              $email,
              $fcm
              );

            if ($result == FALSE) {
              $data['isAlamatSet'] = FALSE;
            } else {
              $data['isAlamatSet'] = TRUE;
            }

            // Check if Rekening Konsumen is available
            $result = $this->ModelSystem->checkRekening (
              $email,
              $fcm
              );

            if ($result == FALSE) {
              $data['isRekeningSet'] = FALSE;
            } else {
              $data['isRekeningSet'] = TRUE;
            } 

            $data['success'] = TRUE;
            $data['messages'] = "User (Konsumen) exist";
            $data['row'] = $this->ModelKonsumenProfile->retrieveKonsumenProfile (
              $email,
              $fcm
              );
          }
          break;

        case 'agen':
          // Check if email exist
          $checkEmailExistent = $this->ModelSystem->checkEmailExistent (
            $usertype,
            $email
            );

          if ($checkEmailExistent == FALSE) {
            $data['messages'] = "Sorry, you are not listed on our system.";
          } else {
            // Check if agen's fcm is empty
            $checkFCMIsNotEmpty = $this->ModelSystem->checkFCMIsNotEmpty (
              $usertype,
              $email
              );

            if ($checkFCMIsNotEmpty == FALSE) {
              $data['messages'] = "Error while checking FCM, please wait for maintenance.";
            } elseif ($checkFCMIsNotEmpty == "EMPTY") {
              $addFCM = $this->ModelSystem->addFCM (
                $usertype,
                $email,
                $fcm
                );

              if ($addFCM == FALSE) {
                $data['messages'] = "Failed to add FCM, login fail.";
              } else {
                goto login_agen;
              } 
            } elseif ($checkFCMIsNotEmpty == TRUE) {
              goto login_agen;
            }

            login_agen:
            $data['success'] = TRUE;
            $data['messages'] = "User (Agen) exist";
            $data['row'] = $this->ModelAgen->retrieveAgenProfile ($email,
              $fcm);
          }
          break;
        
        case 'kurir':
          // Check if email exist
          $checkEmailExistent = $this->ModelSystem->checkEmailExistent (
            $usertype,
            $email
            );

          if ($checkEmailExistent == FALSE) {
            $data['messages'] = "Sorry, you are not listed on our system.";
          } else {
            // Check if agen's fcm is empty
            $checkFCMIsNotEmpty = $this->ModelSystem->checkFCMIsNotEmpty (
              $usertype,
              $email
              );

            if ($checkFCMIsNotEmpty == "EMPTY") {
              $addFCM = $this->ModelSystem->addFCM (
                $usertype,
                $email,
                $fcm
                );

              if ($addFCM == FALSE) {
                $data['messages'] = "Failed to add FCM, login fail.";
              } else {
                goto login_kurir;
              } 
            } elseif ($checkFCMIsNotEmpty == FALSE) {
              $data['messages'] = "Error while checking FCM, please wait for maintenance.";
            } else {
              goto login_kurir;
            }

            login_kurir:
            $data['success'] = TRUE;
            $data['messages'] = "User (Kurir) exist";
            $data['row'] = $this->ModelKurir->retrieveKurirProfile ($email,
              $fcm);
          }
          break;

        case 'afiliasi':
          // Check if email exist
          $checkEmailExistent = $this->ModelSystem->checkEmailExistent (
            $usertype,
            $email
            );

          if ($checkEmailExistent == FALSE) {
            $data['messages'] = "Sorry, you are not listed on our system.";
          } else {
            // Check if agen's fcm is empty
            $checkFCMIsNotEmpty = $this->ModelSystem->checkFCMIsNotEmpty (
              $usertype,
              $email
              );

            if ($checkFCMIsNotEmpty == FALSE) {
              $data['messages'] = "Error while checking FCM, please wait for maintenance.";
            } elseif ($checkFCMIsNotEmpty == "EMPTY") {
              $addFCM = $this->ModelSystem->addFCM (
                $usertype,
                $email,
                $fcm
                );

              if ($addFCM == FALSE) {
                $data['messages'] = "Failed to add FCM, login fail.";
              } else {
                goto login_afiliasi;
              } 
            } elseif ($checkFCMIsNotEmpty == TRUE) {
              goto login_afiliasi;
            }

            login_afiliasi:
            $data['success'] = TRUE;
            $data['messages'] = "User (Afiliasi) exist";
            $data['row'] = $this->ModelAfiliasiProfile->retrieveAfiliasiProfile (
                $email,
                $fcm
                );
          }
          break;

        case 'checker':
          // Check if email exist
          $checkEmailExistent = $this->ModelSystem->checkEmailExistent (
            $usertype,
            $email
            );

          if ($checkEmailExistent == FALSE) {
            $data['messages'] = "Sorry, you are not listed on our system.";
          } else {
            // Check if agen's fcm is empty
            $checkFCMIsNotEmpty = $this->ModelSystem->checkFCMIsNotEmpty (
              $usertype,
              $email
              );

            if ($checkFCMIsNotEmpty == FALSE) {
              $data['messages'] = "Error while checking FCM, please wait for maintenance.";
            } elseif ($checkFCMIsNotEmpty == "EMPTY") {
              $addFCM = $this->ModelSystem->addFCM (
                $usertype,
                $email,
                $fcm
                );

              if ($addFCM == FALSE) {
                $data['messages'] = "Failed to add FCM, login fail.";
              } else {
                goto login_checker;
              } 
            } elseif ($checkFCMIsNotEmpty == TRUE) {
              goto login_checker;
            }

            login_checker:
            $data['success'] = TRUE;
            $data['messages'] = "User (Checker) exist";
            $data['row'] = $this->ModelCheckerProfile->retrieveCheckerProfile (
                $email,
                $fcm
                );
          }
          break;

        default:
          # code...
          break;
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  // Online upload
  public function do_upload ()
  {
    // prepare request variable
    $usertype = $this->input->post('usertype');
    $fcm = $this->input->post('fcm');

    // set upload config
    $config['allowed_types'] = 'jpg|png';
    $config['max_size'] = 1024;
    $config['max_width'] = 1024;
    $config['max_height'] = 1024;
    $config['overwrite'] = TRUE;    
    $config['file_name'] = $fcm;    

    // prepare JSON data
    $data = array (
      'success' => FALSE,
      'messages' => NULL
      );

    // check if request variable is empty
    $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

    if ($checkIfEmpty == FALSE) {
      $data['messages'] = "please fill your form completely.";
    } else {

      // declare folder for user
      $avatarfolder = "";
      switch ($usertype) {
        case 'bayarbelidompet':
          $avatarfolder = 'konfirmasibld';
          break;

        case 'bayartransfertransaksi':
          $avatarfolder = 'konfirmasibtt';
          break;

        case 'konsumen':
          $avatarfolder = 'avatarkonsumen';
          break;

        case 'agen':
          $avatarfolder = 'avataragen';
          break;

        case 'ktpagen':
          $avatarfolder = 'ktpagen';
          break;

        case 'kkagen':
          $avatarfolder = 'kkagen';
          break;

        case 'kurir':
          $avatarfolder = 'avatarkurir';
          break;

        case 'afiliasi':
          $avatarfolder = 'avatarafiliasi';
          break;

        case 'checker':
          $avatarfolder = 'avatarchecker';
          break;
        
        default:
          # code...
          break;
      }

      // Directory name
      $path = "manifest";
      
      // upload URL
      $url = $path . "/" . $avatarfolder;

      $config['upload_path'] = $url;
      $this->upload->initialize($config);
      $this->load->library('upload', $config);

      // check if upload is success
      if (!$this->upload->do_upload('userfile')) {
        $data['messages'] = "upload avatar error.";
        $data['displayed_error'] =  $this->upload->display_errors();
      } else {
        //Get server name
        $svrname = substr(site_url(), 0, 34);
        $photodir = str_replace("/home/citridia/dev.citridia.com/ws.jastrik", "", $this->upload->data('full_path'));

        switch ($usertype) {
          case 'bayarbelidompet':
            // Assigning foto field on konsumen_bayar_beli_dompet
            $result = $this->ModelKonsumenDompet->uploadFotoPembayaranDompet (
                $fcm,
                $svrname.$photodir
                );
            break;

          case 'bayartransfertransaksi':
            // Assigning foto field on konsumen_bayar_beli_dompet
            $result = $this->ModelKonsumenTransaksi->uploadFotoTransferTransaksi (
                $fcm,
                $svrname.$photodir
                );
            break;

          case 'konsumen':
            // Assigning foto field on konsumen
            $result = $this->ModelSystem->updateAvatarName (
              $usertype,
              $fcm,
              $svrname.$photodir
              );
            break;

          case 'agen':
            // Assigning logo field on agen
            $result = $this->ModelSystem->updateAvatarName (
              $usertype,
              $fcm,
              $svrname.$photodir
              );
            break;

          case 'ktpagen':
            // Assigning ktp agen field on agen
            $result = $this->ModelAgenSystem->updateFileKTP (
              $fcm,
              $svrname.$photodir
              );
            break;

          case 'kkagen':
            // Assigning ktp agen field on agen
            $result = $this->ModelAgenSystem->updateFileKK (
              $fcm,
              $svrname.$photodir
              );
            break;

          case 'kurir':
            // Assigning foto field on kurir
            $result = $this->ModelSystem->updateAvatarName (
              $usertype,
              $fcm,
              $svrname.$photodir
              );
            break;

          case 'afiliasi':
            // Assigning foto field on afiliasi
            $result = $this->ModelSystem->updateAvatarName (
              $usertype,
              $fcm,
              $svrname.$photodir
              );
            break;

          case 'checker':
            // Assigning foto field on checker
            $result = $this->ModelSystem->updateAvatarName (
              $usertype,
              $fcm,
              $svrname.$photodir
              );
            break;
          
          default:
            # code...
            break;
        }
        
        if ($result == FALSE) {
          $data['messages'] = "Error while updating avatar name.";
        } else {
          $data['success'] = TRUE;
          $data['messages'] = "upload avatar success.";
          $data['file_name'] =  $svrname.$photodir;
        }
      }
    }

    echo json_encode($data, JSON_UNESCAPED_SLASHES);
  }

  // Offline upload
  // public function do_upload ()
  // {
  //   // TEST
  //   /*$_POST['usertype'] = "konsumen";
  //   $_POST['fcm'] = "fcmfathir";*/

  //   // prepare request variable
  //   $usertype = $this->input->post('usertype');
  //   $fcm = $this->input->post('fcm');

  //   // set upload config
  //   $config['allowed_types'] = 'jpg|png';
  //   $config['max_size'] = 1024;
  //   $config['max_width'] = 500;
  //   $config['max_height'] = 500;
  //   $config['overwrite'] = TRUE;    
  //   $config['file_name'] = $fcm;    

  //   // prepare JSON data
  //   $data = array (
  //     'success' => FALSE,
  //     'messages' => NULL
  //     );

  //   // check if request variable is empty
  //   $checkIfEmpty = $this->ModelSystem->checkRequestMethod ($this->input->post());

  //   if ($checkIfEmpty == FALSE) {
  //     $data['messages'] = "please fill your form completely.";
  //   } else {

  //     // declare folder for user
  //     $avatarfolder = "";
  //     switch ($usertype) {
  //       case 'konsumen':
  //         $avatarfolder = 'avatarkonsumen';
  //         break;
        
  //       default:
  //         # code...
  //         break;
  //     }

  //     // Directory name
  //     $path = "manifest";
      
  //     // upload URL
  //     $url = $path . "/" . $avatarfolder;

  //     $config['upload_path'] = $url;
  //     $this->upload->initialize($config);
  //     $this->load->library('upload', $config);

  //     // check if upload is success
  //     if (!$this->upload->do_upload('userfile')) {
  //       $data['messages'] = "upload avatar error.";
  //       $data['displayed_error'] =  $this->upload->display_errors();
  //     } else {
  //       // Assigning foto field on konsumen
  //       $resultPhotoUpdate = $this->ModelSystem->updateAvatarName (
  //         $usertype,
  //         $fcm,
  //         $this->upload->data('file_name');
  //         );

  //       if ($resultPhotoUpdate == FALSE) {
  //         $data['messages'] = "Error while updating avatar name.";
  //       } else {
  //         $data['success'] = TRUE;
  //         $data['messages'] = "upload avatar success.";
  //         $data['upload_data'] =  $this->upload->data();
  //       }
  //     }
  //   }

  //   echo json_encode($data, JSON_UNESCAPED_SLASHES);
  // }

}