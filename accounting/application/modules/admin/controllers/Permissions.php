<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Permissions extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Permissions_model', 'permissions_model');
// 		$this->page_data['page']->title = 'Permissions Management';
// 		$this->page_data['page']->menu = 'permissions';
    }

    public function index() {

        ifPermissions('permissions_list');

        $records = $this->permissions_model->get();
        $d = [
            'page' => "permissions/list",
            'records' => $records
        ];
        $this->view($d);
    }

    public function add() {

        ifPermissions('permissions_add');

        $this->obj = $this->permissions_model->get_all_fields();
        $d = [
            'page' => "permissions/add",
            'obj' => $this->obj,
        ];

        $this->view($d);
    }

    public function edit($id) {

        ifPermissions('permissions_edit');

        $records = $this->permissions_model->getById($id);

        $d = [
            'page' => "permissions/edit",
            'obj' => $records
        ];
        $this->view($d);
    }

    public function save() {

        postAllowed();

        ifPermissions('permissions_add');
        $this->form_validation->set_rules("title", "Title", "required|is_unique[permissions.title]");
        $this->form_validation->set_rules("code", "Code", "required|is_unique[permissions.code]");
        if ($this->form_validation->run()) {
            $permission = $this->permissions_model->create([
                'title' => $this->input->post('title'),
                'code' => $this->input->post('code'),
            ]);

            //	$this->activity_model->add("New Permission #$permission Created by User: #".logged('id'));

 
            
             $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => '<strong> New Permission Created Successfully.</strong>'
            ]);

            redirect(base_url() . 'admin/permissions');
        } else {
            $this->obj = $this->permissions_model->get_all_fields();
            $d = [
                'page' => "permissions/add",
                'obj' => $this->obj,
            ];

            $this->view($d);
        }
    }

    public function update($id) {

        postAllowed();

        ifPermissions('permissions_edit');
        $this->form_validation->set_rules("title", "Title", "required|is_unique[permissions.title]");
        $this->form_validation->set_rules("code", "Code", "required|is_unique[permissions.code]");
        if ($this->form_validation->run()) {
            $data = [
                'title' => $this->input->post('title'),
                'code' => $this->input->post('code'),
            ];

            $permission = $this->permissions_model->update($id, $data);
            //  echo $this->db->last_query();
            //	$this->activity_model->add("Permission #$id Updated by User: #".logged('id'));

          
            
            $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => '<strong> Permission has been Updated Successfully.</strong>'
            ]);

            redirect(base_url() . 'admin/permissions');
        } else {
            $records = $this->permissions_model->getById($id);

            $d = [
                'page' => "permissions/edit",
                'obj' => $records
            ];
            $this->view($d);
        }
    }

    public function delete($id) {

        ifPermissions('permissions_delete');

        $this->permissions_model->delete($id);

        $this->session->set_flashdata('alert-type', 'success');
        $this->session->set_flashdata('alert', 'Permission has been Deleted Successfully');

        //	$this->activity_model->add("Permission #$permission Deleted by User: #".logged('id'));

        redirect('permissions');
    }

    public function checkIfUnique() {

        $code = get('code');

        if (!$code)
            die('Invalid Request');

        $arg = ['code' => $code];

        if (!empty(get('notId')))
            $arg['id !='] = get('notId');

        $query = $this->permissions_model->getByWhere($arg);

        if (!empty($query))
            die('false');
        else
            die('true');
    }

}

/* End of file Permissions.php */
/* Location: ./application/controllers/Permissions.php */