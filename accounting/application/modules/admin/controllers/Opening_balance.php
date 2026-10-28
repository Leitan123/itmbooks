<?php

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Opening_balance extends MY_Controller
{
    var $page = "opening_balance";

    function __construct()
    {
        parent::__construct();

        $this->load->model(
            'Opening_balance_model',
            'model'
        );
    }

    function index()
    {
        $d = [

            'page' => "$this->page/form",

            'obj' => $this->model->get_record()

        ];

        $this->view($d);
    }

    function save()
    {
        $data = [

            'CashBalance' =>
                $this->input->post('CashBalance'),

            'InventoryBalance' =>
                $this->input->post('InventoryBalance'),

            'ReceivableBalance' =>
                $this->input->post('ReceivableBalance'),

            'PayableBalance' =>
                $this->input->post('PayableBalance'),

            'CapitalBalance' =>
                $this->input->post('CapitalBalance')

        ];

        $record =
            $this->model->get_record();

        if($record)
        {
            $this->db->where(
                'OpeningID',
                $record->OpeningID
            );

            $this->db->update(
                'opening_balances',
                $data
            );
        }
        else
        {
            $data['CreatedDate'] =
                date('Y-m-d H:i:s');

            $this->db->insert(
                'opening_balances',
                $data
            );
        }

        redirect(
            'admin/opening_balance'
        );
    }
}