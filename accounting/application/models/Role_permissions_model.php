<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_permissions_model extends MY_Model {

	public $table = 'role_permissions';

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getByWhere($where) {
        $res =  $this->db->get_where($this->table, $where)->result();
      //  echo $this->db->last_query();
        return $res;
    }
    
    public function get($primary_value = null) {
    return $this->db->get($this->table)->result();
}

public function getById($id)
{
    return $this->db->get_where($this->table, ['id' => $id])->row();
}

public function create_batch($data)
{
    return $this->db->insert_batch($this->table, $data);
}

}

/* End of file Role_permissions_model.php */
/* Location: ./application/models/Role_permissions_model.php */