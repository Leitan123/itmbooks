<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incomes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Incomes_model');
    }

    public function index()
    {
        $data['records'] = $this->Incomes_model->get_all();

        $data['accounts'] = $this->Incomes_model->get_income_accounts();

        $data['banks'] = $this->db
            ->where('IsDeleted',0)
            ->order_by('BankName','ASC')
            ->get('banks')
            ->result();

        $this->load->view(
            'pages/incomes/index',
            $data
        );
    }

    public function save()
{
    $data = [

        'IncomeDate' => $this->input->post('IncomeDate'),

        'AccountID' => $this->input->post('AccountID'),

        'Amount' => $this->input->post('Amount'),

        'PaymentMethod' => $this->input->post('PaymentMethod'),

        'BankID' => $this->input->post('BankID'),

        'Remarks' => $this->input->post('Remarks')

    ];

    // Save Income
    $this->db->insert('incomes', $data);

    $incomeID = $this->db->insert_id();

    // =========================
    // AUTO JOURNAL ENTRY
    // =========================

    $journal = [

        'JournalDate' => $this->input->post('IncomeDate'),

        'ReferenceNo' => 'INC-'.$incomeID,

        'Description' => 'Income Entry'

    ];

    $this->db->insert('journal_entries', $journal);

    $journalID = $this->db->insert_id();

    $incomeAccount = $this->input->post('AccountID');
    $amount = $this->input->post('Amount');
    $paymentMethod = $this->input->post('PaymentMethod');
    $bankId = $this->input->post('BankID');

    if($paymentMethod == 'Cash')
    {
        $debitAccount = 1; // Cash Account
    }
    else
    {
        $debitAccount = 2; // Bank Account
        if(!empty($bankId)) {
            $bank_row = $this->db->select('AccountID')->where('BankID', $bankId)->get('banks')->row();
            if($bank_row) {
                $debitAccount = $bank_row->AccountID;
            }
        }
    }

    // Debit Cash/Bank
    $this->db->insert('journal_entry_details', [
        'JournalID'    => $journalID,
        'AccountID'    => $debitAccount,
        'DebitAmount'  => $amount,
        'CreditAmount' => 0
    ]);

    // Credit Income
    $this->db->insert('journal_entry_details', [
        'JournalID'    => $journalID,
        'AccountID'    => $incomeAccount,
        'DebitAmount'  => 0,
        'CreditAmount' => $amount
    ]);

    redirect('admin/incomes');
}
}