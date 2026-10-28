<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_reconciliation extends CI_Controller
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

        $this->db->select('*');

        $this->db->from('bank_reconciliation');

        $this->db->join(
            'banks',
            'banks.BankID = bank_reconciliation.BankID',
            'left'
        );

        $this->db->order_by(
            'ReconciliationID',
            'DESC'
        );

        $data['records'] =
            $this->db
            ->get()
            ->result();


        $this->load->view(
            'pages/bank_reconciliation/index',
            $data
        );
    }


    // =====================================
    // CREATE
    // =====================================

    public function create()
    {

        $data['banks'] =
            $this->db
            ->get('banks')
            ->result();


        if($this->input->post())
        {

            $bankId =
                $this->input
                ->post('BankID');

            $bankStatementBalance =
                $this->input
                ->post('BankStatementBalance');


            // TOTAL DEPOSITS
            $this->db->select_sum('Amount');

            $this->db->where(
                'BankID',
                $bankId
            );

            $this->db->where(
                'TransactionType',
                'Deposit'
            );

            $deposit =
                $this->db
                ->get('bank_transactions')
                ->row();


            // TOTAL WITHDRAWALS
            $this->db->select_sum('Amount');

            $this->db->where(
                'BankID',
                $bankId
            );

            $this->db->where(
                'TransactionType',
                'Withdrawal'
            );

            $withdrawal =
                $this->db
                ->get('bank_transactions')
                ->row();


            $depositAmount =
                $deposit->Amount
                ? $deposit->Amount
                : 0;

            $withdrawalAmount =
                $withdrawal->Amount
                ? $withdrawal->Amount
                : 0;


            // SYSTEM BALANCE
            $systemBalance =
                $depositAmount
                -
                $withdrawalAmount;


            // DIFFERENCE
            $difference =
                $bankStatementBalance
                -
                $systemBalance;


            // SAVE
            $save = [

                'BankID' =>
                    $bankId,

                'ReconciliationDate' =>
                    $this->input
                    ->post('ReconciliationDate'),

                'BankStatementBalance' =>
                    $bankStatementBalance,

                'SystemBalance' =>
                    $systemBalance,

                'DifferenceAmount' =>
                    $difference,

                'Notes' =>
                    $this->input
                    ->post('Notes')

            ];


            $this->db->insert(
                'bank_reconciliation',
                $save
            );


            redirect(
                base_url(
                    'admin/bank_rec'
                )
            );
        }


        $this->load->view(
            'pages/bank_reconciliation/form',
            $data
        );


    }


    public function save()
    {
        $data = [

            'BankID' => $this->input->post('BankID'),
            'ReconciliationDate' => $this->input->post('ReconciliationDate'),
            'BankStatementBalance' => $this->input->post('BankStatementBalance'),
            'Notes' => $this->input->post('Notes')

        ];

        $this->db->insert(
            'bank_reconciliation',
            $data
        );

        redirect(
            'admin/bank_rec'
        );
    }


}