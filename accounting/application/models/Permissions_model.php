<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions_model extends MY_Model {

	public $table = 'permissions';

	public function __construct()
	{
		parent::__construct();
	}
	
		public function get($primary_value = null) {
    return $this->db->get('permissions')->result();
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

/* End of file Permissions_model.php */
/* Location: ./application/models/Permissions_model.php */