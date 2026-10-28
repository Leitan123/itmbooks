  <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privileges_model extends CI_Model {

    protected $_table = 'privileges';
    protected $primary_key = 'PrivilegeId';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->where('IsDeleted', 0);
        return $this->db->get($this->_table)->result();
    }

    public function get($id) {
        return $this->db->where($this->primary_key, $id)
                       ->get($this->_table)
                       ->row();
    }

    public function insert($data) {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where($this->primary_key, $id)
                       ->update($this->_table, $data);
    }

    public function delete($id) {
        return $this->db->where($this->primary_key, $id)
                       ->update($this->_table, ['IsDeleted' => 1]);
    }
}