<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Received_cheques extends CI_Controller
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
            received_cheques.*,
            customer.CustomerName,
            bank_accounts.BankName
        ');

        $this->db->from(
            'received_cheques'
        );

        $this->db->join(
            'customer',
            'customer.CustomerId = received_cheques.CustomerID',
            'left'
        );

        $this->db->join(
            'bank_accounts',
            'bank_accounts.BankID = received_cheques.BankID',
            'left'
        );

        $this->db->order_by(
            'ReceivedChequeID',
            'DESC'
        );

        $data['records'] =
            $this->db
            ->get()
            ->result();


        $this->load->view(
            'pages/received_cheques/index',
            $data
        );
    }


    public function deposit($id)
    {
        $this->db->select('
        received_cheques.*,
        customer.CustomerName
    ');

    $this->db->from('received_cheques');

    $this->db->join(
        'customer',
        'customer.CustomerID = received_cheques.CustomerID',
        'left'
    );

    $this->db->where(
        'received_cheques.ReceivedChequeID',
        $id
    );

    $data['cheque'] =
        $this->db->get()->row();

        $data['banks'] = $this->db
            ->where('IsDeleted',0)
            ->get('banks')
            ->result();

        $this->load->view(
            'pages/received_cheques/deposit',
            $data
        );
    }


    public function save_deposit()
    {
        $id = $this->input->post('ReceivedChequeID');

        $this->db
            ->where('ReceivedChequeID',$id)
            ->update(
                'received_cheques',
                [

                    'Status' => 'Deposited',

                    'DepositDate' =>
                        $this->input->post('DepositDate'),

                    'CreditedBankID' =>
                        $this->input->post('BankID')

                ]
            );

        redirect('admin/received_cheques');
    }

    public function clear_cheque($id)
    {
        $cheque = $this->db
            ->where('ReceivedChequeID',$id)
            ->get('received_cheques')
            ->row();

        $this->db
            ->where('ReceivedChequeID',$id)
            ->update(
                'received_cheques',
                [

                    'Status' => 'Cleared',

                    'ClearedDate' => date('Y-m-d')

                ]
            );

        $this->db->set(
            'OpeningBalance',
            'OpeningBalance + '.$cheque->Amount,
            FALSE
        );

        $this->db->where(
            'BankID',
            $cheque->CreditedBankID
        );

        $this->db->update('banks');

        redirect('admin/received_cheques');
    }



}