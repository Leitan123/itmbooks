<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Roles extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Roles_model', 'roles_model');
        $this->load->model('Permissions_model', 'permissions_model');
        $this->load->model('Role_permissions_model', 'role_permissions_model');
// 		$this->page_data['page']->title = 'Roles Management';
// 		$this->page_data['page']->menu = 'roles';
    }

    public function index() {
        ifPermissions('roles_list');
        $records = $this->roles_model->get();
        $d = [
            'page' => "roles/list",
            'records' => $records
        ];
        $this->view($d);
    }

    public function add() {
        ifPermissions('roles_add');

        $d = [
            'page' => "roles/add",
            'obj' => $this->obj,
        ];

        $this->view($d);
    }

    public function save() {

        ifPermissions('roles_add');

        postAllowed();
        $this->form_validation->set_rules("name", "Title", "required");
        if ($this->form_validation->run()) {
            $role = $this->roles_model->create([
                'title' => $this->input->post('name'),
            ]);

            $Data = [];
            foreach (post('permission') as $permission) {
                array_push($Data, [
                    'role' => $role,
                    'permission' => $permission,
                ]);
            }

            $this->role_permissions_model->create_batch($Data);

            $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => '<strong> New Role Created Successfully.</strong>'
            ]);

//	$this->activity_model->add("New Role #$role Created by User: #".logged('id'));

            redirect(base_url() . 'admin/roles');
        } else {
            $d = [
                'page' => "roles/add",
                'obj' => $this->obj,
            ];

            $this->view($d);
        }
    }

    public function edit($id) {

        ifPermissions('roles_edit');

        $role = $this->roles_model->getById($id);
        $permissions = $this->role_permissions_model->getByWhere([
            'role' => $role->id
        ]);

        $permissions = array_map(function ($data) {
            return $data->permission;
        }, $permissions);

        $role_permissions = $permissions;

        $records = $this->role_permissions_model->getById($id);

        $d = [
            'page' => "roles/edit",
            'role' => $role,
            'role_permissions' => $role_permissions,
        ];

        $this->view($d);
    }

    public function update($id) {


        ifPermissions('roles_edit');

        postAllowed();
        $this->form_validation->set_rules("name", "Title", "required");
        if ($this->form_validation->run()) {
            $data = [
                'title' => $this->input->post('name'),
            ];

            $role = $this->roles_model->update($id, $data);

// Data which will be added
            $Data = [];
            foreach (post('permission') as $permission) {
                if (!empty($this->role_permissions_model->getByWhere(['role' => $id, 'permission' => $permission]))) {
                    
                } else {
                    array_push($Data, [
                        'role' => $id,
                        'permission' => $permission,
                    ]);
                }
            }

            if (!empty($Data))
                $this->role_permissions_model->create_batch($Data);
            echo $this->db->last_query();
            $all_permissions = $this->role_permissions_model->getByWhere([
                'role' => $id
            ]);

            if (!empty($all_permissions)) {
// Permissions which will be deleted
                foreach ($all_permissions as $data) {

                    if (!in_array($data->permission, post('permission'))) {
                        $this->role_permissions_model->delete($data->id);
                    }
                }
            }

//	$this->activity_model->add("Role #$role Updated by User: #".logged('id'));
            $this->session->set_flashdata('notification', [
                "alert" => "success",
                "text" => '<strong> User Role has been Updated Successfully.</strong>'
            ]);

            redirect(base_url() . 'admin/roles');
        } else {
            $role = $this->roles_model->getById($id);
            $permissions = $this->role_permissions_model->getByWhere([
                'role' => $role->id
            ]);

            $permissions = array_map(function ($data) {
                return $data->permission;
            }, $permissions);

            $role_permissions = $permissions;

            $records = $this->role_permissions_model->getById($id);

            $d = [
                'page' => "roles/edit",
                'role' => $role,
                'role_permissions' => $role_permissions,
            ];

            $this->view($d);
        }
    }

}

/* End of file Roles.php */
/* Location: ./application/controllers/Roles.php */