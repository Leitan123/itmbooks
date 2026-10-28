<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Issued_cheques extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }


    // =====================================
    // INDEX
    // =====================================

    public function index()
    {
    $this->db->select('
    issued_cheques.*,
    banks.BankName,
    chart_of_accounts.AccountName
    ');

    $this->db->from('issued_cheques');

    $this->db->join(
        'banks',
        'banks.BankID = issued_cheques.BankID',
        'left'
    );

    $this->db->join(
        'chart_of_accounts',
        'chart_of_accounts.AccountID = issued_cheques.AccountID',
        'left'
    );

    $this->db->order_by(
        'IssuedChequeID',
        'DESC'
    );

    $data['records'] =
        $this->db
        ->get()
        ->result();

    $this->load->view(
        'pages/issued_cheques/index',
        $data
    );


    }

}