<?php

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Financial_year extends MY_Controller
{
    var $page = "financial_year";

    function __construct()
    {
        parent::__construct();

        $this->load->model('Financial_year_model', 'model');

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
        $data = [

            'CompanyID' => $this->input->post('CompanyID'),

            'YearName' => $this->input->post('YearName'),

            'StartDate' => $this->input->post('StartDate'),

            'EndDate' => $this->input->post('EndDate'),

            'IsActive' => $this->input->post('IsActive') ? 1 : 0,

            'IsDeleted' => 0

        ];

        $this->db->insert('financial_year', $data);

        redirect('admin/financial_year');
    }

    function edit($id = 0)
    {
        $d = [

            'page' => "$this->page/form",

            'record' => $this->db
                ->where('YearID', $id)
                ->get('financial_year')
                ->row(),

            'obj' => $this->model->get_all_fields()

        ];

        $this->view($d);
    }

    function update($id = 0)
    {
        $data = [

            'CompanyID' => $this->input->post('CompanyID'),

            'YearName' => $this->input->post('YearName'),

            'StartDate' => $this->input->post('StartDate'),

            'EndDate' => $this->input->post('EndDate'),

            'IsActive' => $this->input->post('IsActive') ? 1 : 0

        ];

        $this->db->where('YearID', $id);

        $this->db->update('financial_year', $data);

        redirect('admin/financial_year');
    }

    function delete($id = 0)
    {
        $this->db->where('YearID', $id);

        $this->db->update('financial_year', [

            'IsDeleted' => 1

        ]);

        redirect('admin/financial_year');
    }

    function activate($id = 0)
    {
        // Deactivate all years
        $this->db->update('financial_year', [
            'IsActive' => 0
        ]);

        // Activate selected year
        $this->db->where('YearID', $id);

        $this->db->update('financial_year', [
            'IsActive' => 1
        ]);

        redirect('admin/financial_year');
    }
}