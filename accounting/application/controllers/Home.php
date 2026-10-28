<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function index() {
        $this->load->view('welcome_message');
    }

    // Optional custom 404 method if you need it
    public function e_404() {
        show_404();
    }
}
?>

