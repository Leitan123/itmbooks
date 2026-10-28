<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function page_missing() {
        $data['heading'] = '404 Page Not Found';
        $data['message'] = 'The page you are looking for does not exist.';
        $this->load->view('errors/html/error_404', $data);
    }
}

?>

