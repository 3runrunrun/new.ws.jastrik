<?php
  if (!defined('BASEPATH')) exit ('No direct script access allowed');
  // define('FIREBASE_API_KEY', 'AAAAVg-7OnY:APA91bHogI2R46plo2RO8xgtZoiudZoA0HDp80m7efTOBMun8HHXHNC5ChAc6W1ZK_YYeCI8ZWQkjCqtpCsdFQP-lJFGlz3Fjndv6n3u5rGstY7FN4YnUqfe1NqoKjWMfpbgqlD4fYwwd0Dh4MplQlF6NQgqGz9jlA');
  define('FIREBASE_API_KEY', 'AAAAkO86J70:APA91bFo8I9xyzlqF6VB9DeJauXL6mB5qYsqPMwDWqnmVmFfAJgKLGATv5axabAGUq_AtywqVrob_D8zESHq0ZyXeC6SytJKTPaeg_gnLfbgAxkx1qJXp3KESSlk9zDSJJ4-TnNGWPNfaEU0rAjyg-DH5SSi8jvD_Q');

  /**
  * Firebase class
  */
  class Firebase 
  { 
    function __construct ()
    {
      $CI =& get_instance();
    }

    // Send to single device
    public function send (
      $url,
      $to,
      $notification,
      $payload
      )
    {
      $letter = array (
        "to" => $to,
        "priority" => "high",
        "notification" => $notification,
        "data" => $payload
        );

      return $this->sendPushNotification (
        $url,
        $letter
        );
    }

    // Send to single device (Data Only)
    public function sendDataSingle (
      $url,
      $to,
      $payload
      )
    {
      $letter = array (
        "to" => $to,
        "priority" => "high",
        "data" => $payload
        );

      return $this->sendPushNotification (
        $url,
        $letter
        );
    }

    // Send to several device
    public function sendToMany (
      $url,
      array $registration_ids,
      $payload
      )
    {
      $letter = array (
        "registration_ids" => $registration_ids,
        "priority" => "high",
        "data" => $payload
        );

      return $this->sendPushNotification (
        $url,
        $letter
        );
    }

    /*
    * This function will make the actuall curl request to firebase server
    * and then the message is sent 
    */
    private function sendPushNotification (
      $url,
      $letter
      )
    {
      // Headers preparation
      $headers = array(
          'Authorization: key=' . FIREBASE_API_KEY,
          'Content-Type: application/json'
      );

      // Letter contain to POST
      $letter = json_encode($letter, JSON_UNESCAPED_SLASHES);

      // Open connection
      $ch = curl_init();

      // Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $url);

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Disabling SSL Certificate support temporarly
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $letter);

      // Execute post
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('Curl failed: ' . curl_error($ch));
      }

      // Close connection
      curl_close($ch);
      
      return $result;
      // return $letter;
    }
  }