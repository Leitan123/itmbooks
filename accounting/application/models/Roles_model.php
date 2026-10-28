<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_model extends MY_Model {

	public $table = 'roles';

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get($primary_value = null) {
    return $this->db->get('roles')->result();
}

public function getById($id)
{
    return $this->db->get_where($this->table, ['id' => $id])->row();
}

public function create($data)
{
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
}
}

/* End of file Roles_model.php */
/* Location: ./application/models/Roles_model.php */