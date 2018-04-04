<?php
  if (!defined('BASEPATH')) exit ('No direct script access allowed');

  /**
  * Envelope Class
  */
  class Envelope {

    // Notification
    private $notif;

    // Notification sound
    private $sound = "notification";

    // Notification title
    private $title;

    // Notification message
    private $message;

    // Data's data
    private $data;

    function __construct ()
    {
    }

    public function setTitle ($title)
    {
      $this->title = $title;
    }

    public function setMessage ($message)
    {
      $this->message = $message;
    }

    public function setData (array $param)
    {
      $this->data = $param;
    }

    public function getTitle ()
    {
      return $this->title;
    }

    public function getMessage ()
    {
      return $this->message;
    }

    public function getNotification ()
    {
      $notif = array (
        'title' => $this->title,
        'body' => $this->message,
        'sound' => $this->sound
        );
      return $notif;
    }

    public function getData ()
    {
      return $this->data;
    }
  }