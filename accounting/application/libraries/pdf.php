<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pdf {
    public function __construct() {
        // Adjust the path to the autoload.php file if necessary
        require_once APPPATH . '../vendor/autoload.php'; 
    }

    public function load($params = null) {
        return new \Mpdf\Mpdf($params);  // Ensure Mpdf is loaded correctly
    }
}
