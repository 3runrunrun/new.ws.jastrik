<?php
  if (!defined('BASEPATH')) exit ('No direct script access allowed');

  /**
  * 
  */
  class Qrcode
  {
    private $qr;
    
    function __construct()
    {
      $CI =& get_instance();
      $this->qr = new Endroid\QrCode\QrCode();
    }

    public function setEncrypt ($text)
    {
      $enkripsi = md5(md5($text).md5('jastrik'));
      return $enkripsi;
    }

    public function setEncryptKurir ($text)
    {
      $enkripsi = md5(md5($text).md5('jaswallet'));
      return $enkripsi;
    }

    public function setText ($text)
    {
      $this->qr->setText ($text);
    }

    public function setSize ($size)
    {
      $this->qr->setSize ($size);
    }

    public function setPadding ($padding)
    {
      $this->qr->setPadding ($padding);
    }

    public function render ()
    {
      header('Content-Type: image/png');
      $this->qr->render();
    }

    public function getUri ()
    {
      return $this->qr->getDataUri();
    }
  }