<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Privileges extends MY_Controller
{
    var $page = "privileges";
    var $img_width = 600;
    var $img_height = 600;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Privileges_model', 'model');
        $this->controller = get_class($this);
    }

    function create()
    {
        $this->obj = $this->model->get_all();
        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj
        ];
        $this->_form($d);
    }

    function _form(&$d, $_id = 0)
    {
        $this->form_validation->set_rules("form[Name]", "Privilege Name", "required");
        $this->form_validation->set_rules("form[Description]", "Description", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post('form');
            
            // Check if privilege with same name already exists
            $existing = $this->model->get_by(['Name' => $post['Name']], true);
            if ($existing && $existing->PrivilegeId != $_id) {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Privilege with this name already exists.</strong>'
                ]);
                redirect(current_url());
            }

            $this->db->trans_begin();
            if ($_id) {
                $this->model->update($_id, $post);
            } else {
                $post['CreatedDate'] = date("Y-m-d H:i:s");
                $this->model->insert($post);
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
                    "text" => '<strong> Privilege Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
                ]);
            }

            redirect(base_url("admin/$this->controller"));
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
            // Soft delete (set IsDeleted = 1)
            $this->model->update($_id, ['IsDeleted' => 1]);
            
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Privilege Deleted Successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to Delete Privilege or Record Not Found.</strong>'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }

   
}