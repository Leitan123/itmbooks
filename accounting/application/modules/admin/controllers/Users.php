<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Users extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Roles_model','roles_model');
			$this->load->model('User_model','users_model');
// 		$this->page_data['page']->title = 'Users Management';
// 		$this->page_data['page']->menu = 'users';
	}
	
		public function index()
	{
		
		ifPermissions('users_list');


			$records = $this->users_model->get();
		$d = [
            'page' => "users/list",
            'records' => $records
        ];

		$this->view($d);
	}

 

	public function add()
	{
		ifPermissions('users_add');
		$this->obj = $this->users_model->get_all_fields();
		$roles = $this->users_model->get_roles();
        $d = [
            'page' => "users/add",
            'obj' => $this->obj,
            'roles' => $roles
        ];
 
		$this->view($d);
	 
	}

	public function save()
	{
		ifPermissions('users_add');
		postAllowed();

		$id = $this->users_model->create([
			'role' => post('role'),
			'Name' => post('Name'),
			'Username' => post('Username'),   
			'CreatedBy' => logged('UserId'),   
			'CreatedDate' => date('d/m/Y H:i'),  
			'Password' => sha1(post('Password') ),
		]);

		 

	//	$this->activity_model->add('New User $'.$id.' Created by User:'.logged('name'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'New User Created Successfully');

	    redirect(base_url() .'admin/users');

	}

// 	public function view($id)
// 	{

// 		ifPermissions('users_view');

// 		$this->page_data['User'] = $this->users_model->getById($id);
// 		$this->page_data['User']->role = $this->roles_model->getByWhere([
// 			'id'=> $this->page_data['User']->role
// 		])[0];
// 		$this->page_data['User']->activity = $this->activity_model->getByWhere([
// 			'user'=> $id
// 		], [ 'order' => ['id', 'desc'] ]);
// 		$this->load->view('users/view', $this->page_data);

// 	}

	public function edit($id)
	{
 
		
		ifPermissions('users_edit');

		$records = $this->users_model->getById($id);
		$roles = $this->users_model->get_roles();
			$d = [
            'page' => "users/edit",
            'roles' => $roles,
            'obj' => $records
        ];
        
        
		$this->view($d);
		
 

	}


	public function update($id)
	{

		ifPermissions('users_edit');
		
		postAllowed();

		$data = [
			'role' => post('role'),
			'Name' => post('Name'),
			'Username' => post('Username'), 
			'ModifiedDate' => date('d/m/Y H:i'),  
		];
		
		 

		$password = sha1(post('Password'));

		if(logged('id')!=$id)
			$data['status'] = post('status')==1;

		if(!empty($password))
			$data['password'] = hash( "sha256", $password );

		$id = $this->users_model->update($id, $data);

	 
	//	$this->activity_model->add("User #$id Updated by User:".logged('name'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Client Profile has been Updated Successfully');
		
		 redirect(base_url() .'admin/users');

	}

	public function check()
	{
		$email = !empty(get('email')) ? get('email') : false;
		$username = !empty(get('username')) ? get('username') : false;
		$notId = !empty($this->input->get('notId')) ? $this->input->get('notId') : 0;

		if($email)
			$exists = count($this->users_model->getByWhere([
					'email' => $email,
					'id !=' => $notId,
				])) > 0 ? true : false;

		if($username)
			$exists = count($this->users_model->getByWhere([
					'username' => $username,
					'id !=' => $notId,
				])) > 0 ? true : false;

		echo $exists ? 'false' : 'true';
	}

	public function delete($id)
	{

		ifPermissions('users_delete');

		if($id!==1 && $id!=logged($id)){ }else{
			redirect('/','refresh');
			return;
		}

		$id = $this->users_model->delete($id);

		$this->activity_model->add("User #$id Deleted by User:".logged('name'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'User has been Deleted Successfully');
		
		redirect('users');

	}

	public function change_status($id)
	{
		$this->users_model->update($id, ['status' => get('status') == 'true' ? 1 : 0 ]);
		echo 'done';
	}

}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */