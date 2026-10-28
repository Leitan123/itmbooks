<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_accounts extends CI_Controller
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

        $data['records'] =
            $this->db
            ->get('bank_accounts')
            ->result();

        $this->load->view(
            'pages/bank_accounts/index',
            $data
        );
    }


    // =====================================
    // CREATE
    // =====================================

    public function create()
    {

        if($this->input->post())
        {

            $save = [

                'BankName' =>
                    $this->input
                    ->post('BankName'),

                'AccountName' =>
                    $this->input
                    ->post('AccountName'),

                'AccountNumber' =>
                    $this->input
                    ->post('AccountNumber'),

                'Branch' =>
                    $this->input
                    ->post('Branch'),

                'OpeningBalance' =>
                    $this->input
                    ->post('OpeningBalance'),

                'CurrentBalance' =>
                    $this->input
                    ->post('OpeningBalance')

            ];

            $this->db->insert(
                'bank_accounts',
                $save
            );

            redirect(
                base_url(
                    'admin/Bank_accounts'
                )
            );
        }

        $this->load->view(
            'pages/bank_accounts/form'
        );
    }
}