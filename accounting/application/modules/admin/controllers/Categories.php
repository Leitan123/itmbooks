<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Categories extends MY_Controller
{
    var $page = "categories";

    function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model', 'model');
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
        $this->form_validation->set_rules("form[CategoryName]", "Category Name", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post('form');

            // Prepare category data for insertion or update
            $data = [
                'CategoryName' => $post['CategoryName'],
                'CategoryDescription' => $post['CategoryDescription']
            ];

            $this->db->trans_begin();
            if ($_id) {
                $this->model->update($_id, $data); // Update category data
            } else {
                $data['CreatedAt'] = date("Y-m-d H:i:s");
                $this->model->insert($data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>' . (!$_id ? "Creation." : "Update.") . ' Failure !!! </strong>'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong> Category Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
                ]);
            }

            redirect(current_url());
        }
        $this->view($d);
    }

    function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->model->get($_id);
            $d = [
                'page' => "$this->page/form",
                'obj' => $obj
            ];
            $this->_form($d, $_id);
        } else {
            $this->index();
        }
    }

    function index()
    {
        $d = [
            'page' => "$this->page/index",
            'records' => $this->model->get_all()
        ];
        $this->view($d);
    }

    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->delete($_id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Record Deleted Successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to Delete Record or Record Not Found.</strong>'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }
}
?>
