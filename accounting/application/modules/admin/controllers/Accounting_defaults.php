<?php

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Accounting_defaults extends MY_Controller
{
    var $page = "accounting_defaults";

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            'Accounting_defaults_model',
            'model'
        );
    }

    function index()
    {
        $d = [

            'page' => "$this->page/form",

            'obj' => $this->model->get_record(),

            'accounts' => $this->model->get_accounts()

        ];

        $this->view($d);
    }

    function save()
    {
        $data = [

            'CashAccountID' =>
                $this->input->post('CashAccountID'),

            'BankAccountID' =>
                $this->input->post('BankAccountID'),

            'ReceivableAccountID' =>
                $this->input->post('ReceivableAccountID'),

            'PayableAccountID' =>
                $this->input->post('PayableAccountID'),

            'InventoryAccountID' =>
                $this->input->post('InventoryAccountID'),

            'CapitalAccountID' =>
                $this->input->post('CapitalAccountID'),

            'SalesAccountID' =>
                $this->input->post('SalesAccountID')

        ];

        $record = $this->model->get_record();

        if($record)
        {
            $this->db->where(
                'DefaultID',
                $record->DefaultID
            );

            $this->db->update(
                'accounting_defaults',
                $data
            );
        }
        else
        {
            $this->db->insert(
                'accounting_defaults',
                $data
            );
        }

        redirect(
            'admin/accounting_defaults'
        );
    }
}