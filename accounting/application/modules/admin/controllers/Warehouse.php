<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Warehouse extends MY_Controller
{
    var $page = "warehouse";

    function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_model', 'model');
        $this->controller = get_class($this);
    }

    function create()
    {
        $this->obj = $this->model->get_all_fields();
        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj,
        ];

        $this->_form($d);
    }

    function _form(&$d, $_id = 0)
    {
        $this->form_validation->set_rules("form[WarehouseName]", "Warehouse Name", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post('form');

            $warehouse_data = [
                'WarehouseName' => $post['WarehouseName'],
                'Location'      => $post['Location'],
                'ContactPerson' => $post['ContactPerson'],
                'Phone'         => $post['Phone'],
                'IsDeleted'     => isset($post['IsDeleted']) ? $post['IsDeleted'] : 0
            ];

            $this->db->trans_begin();
            if ($_id) {
                $this->model->update($_id, $warehouse_data);
            } else {
                $this->model->insert($warehouse_data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>' . (!$_id ? "Creation" : "Update") . ' Failure !!! </strong>'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong> Warehouse successfully ' . (!$_id ? "added" : "updated") . '.</strong>'
                ]);
            }

            redirect(base_url('admin/warehouse'));
        }
        $this->view($d);
    }

    function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->model->get($_id);
            $d = [
                'page' => "$this->page/form",
                'obj' => $obj,
            ];
            $this->_form($d, $_id);
        } else {
            $this->index();
        }
    }

    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->update($_id, ['IsDeleted' => 1]);
            $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => "<strong>Warehouse deleted successfully.</strong>"
            ]);
        }
        redirect(base_url('admin/warehouse'));
    }

    function index()
    {
        $d = [
            'page' => "$this->page/index",
            'records' => $this->model->get_all()
        ];
        $this->view($d);
    }
}
?>
