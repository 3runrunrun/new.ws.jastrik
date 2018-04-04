<?php
  if (!defined('BASEPATH')) exit ('No direct script access allowed');

  class JasCalculator {

    protected $CI;

    function __construct() {
      $this->CI =& get_instance();
    }

    public function test ()
    {
      $result = $this->CI->ModelAgenTransaksi->index();
      echo $result;
    }
  }