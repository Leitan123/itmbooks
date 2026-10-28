<?php


class User_model extends MY_Model
{
    var $_table = "user_tab",
        $primary_key = "UserId";
        public $table = 'user_tab';
        public function getById($id) {
        $query = $this->db->get_where($this->table, ['UserId' => $id]);
        return $query->row(); // returns a single result object
    }
    
    	public function get($primary_value = null) {
    return $this->db->get('user_tab')->result();
}

public function create($data)
{
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
}
     public function get_roles() {

        $this->db->where('IsDeleted', 0); 
        return $this->db->get('roles')->result();
    }
    
    public function appendToSelectStr() {
			return NULL;
	}

	public function fromTableStr() {
		return 'users';
	}

	public function joinArray(){
		return NULL;
	}

	public function whereClauseArray(){
		return NULL;
	}
}