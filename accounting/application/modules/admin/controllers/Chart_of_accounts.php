<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart_of_accounts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Chart_of_accounts_model');
    }

    public function index()
    {
        $data['accounts'] = $this->Chart_of_accounts_model->get_all();
        $data['types'] = $this->Chart_of_accounts_model->get_account_types();

        $this->load->view('pages/chart_of_accounts/index', $data);
    }

    public function save()
    {
        $data = [
            'AccountCode' => $this->input->post('AccountCode'),
            'AccountName' => $this->input->post('AccountName'),
            'AccountTypeID' => $this->input->post('AccountTypeID'),
            'OpeningBalance' => $this->input->post('OpeningBalance')
        ];

        $this->Chart_of_accounts_model->insert($data);

        redirect('admin/chart_of_accounts');
    }
}