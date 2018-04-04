<?php
  if (!defined('BASEPATH')) exit ('No direct script access allowed');
  define('DISTANCE_MATRIX_KEY', 'AIzaSyCPHXdXgFHGrLCuClrC6-zsbugpYVi8Bjo');

  /**
  * Distance Counter Class
  */
  class DistanceCounter
  {
    function __construct()
    {
      $CI =& get_instance();
    }

    /**
     * Function - generating distance based on latitude and longitude
     * 
     * @param  [float] $lat1 [Origin latitude]
     * @param  [float] $lng1 [Origin Longitude]
     * @param  [float] $lat2 [Destination Latitude]
     * @param  [float] $lng2 [Destination Longitude]
     * 
     * @return Array        
     */
    public function generateDistance (
      $lat1,
      $lng1,
      $lat2,
      $lng2
      )
    {
      // Prepare API URL 
      $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$lat1,$lng1&destinations=$lat2,$lng2&mode=driving&key=" . DISTANCE_MATRIX_KEY;

      // Prepare return value
      $return_value = array (
        "distance" => NULL,
        "duration" => NULL
        );

      // Open CURL Connection
      $ch = curl_init();

      // Set URL, Setting Port, etc
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      // Execute CURL
      $response = curl_exec($ch);
      curl_close($ch);

      // Rebuild json as an array
      $response_a = json_decode($response, true);
      $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
      $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

      // Removing character from string
      $modified_dist = str_replace (' km', '', $dist);
      $modified_time = str_replace (' mins', '', $time);

      // Convert string into float
      $float_dist = floatval ($modified_dist);
      $float_time = floatval ($modified_time);

      $return_value["distance"] = $float_dist;
      $return_value["duration"] = $float_time;

      return $return_value;
    }
  }