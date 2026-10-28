<?php

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Banks extends MY_Controller
{
    var $page = "banks";

    function __construct()
    {
        parent::__construct();

        $this->load->model('Banks_model','model');

        $this->controller = get_class($this);
    }

    function index()
    {
        $d = [

            'page' => "$this->page/index",

            'obj' => $this->model->get_all_fields(),

            'records' => $this->model->get_all()

        ];

        $this->view($d);
    }

    function save()
    {
        $last = $this->db
            ->order_by('BankID','DESC')
            ->limit(1)
            ->get('banks')
            ->row();

        if($last)
        {
            $no = (int)substr($last->BankCode,1);
            $bankCode = 'B'.str_pad($no+1,4,'0',STR_PAD_LEFT);
        }
        else
        {
            $bankCode = 'B0001';
        }

        $data = [

            'BankCode' => $bankCode,

            'BankName' => $this->input->post('BankName'),

            'AccountName' => $this->input->post('AccountName'),

            'AccountNumber' => $this->input->post('AccountNumber'),

            'Branch' => $this->input->post('Branch'),

            'OpeningBalance' => $this->input->post('OpeningBalance'),

            'CreatedDate' => date('Y-m-d H:i:s')

        ];

        $this->db->insert('banks',$data);

        redirect('admin/banks');
    }

    function delete($id=0)
    {
        $this->db->where('BankID',$id);

        $this->db->update('banks',[
            'IsDeleted'=>1
        ]);

        redirect('admin/banks');
    }
}