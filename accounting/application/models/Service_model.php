<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model {
    
    private $table = 'service';

    public function __construct() {
        parent::__construct();
    }

    // Get all records
    public function get_all() {
        $this->db->select('service.*, customer.CustomerName');
        $this->db->from($this->table);
        $this->db->join('customer', 'service.CustomerId = customer.CustomerId', 'left');
        $this->db->where('service.IsDeleted', 0); // Exclude deleted records
        return $this->db->get()->result();
    }

    // Get a specific record by ID
    public function get($id) {
        $this->db->where('ServiceID', $id);
        $this->db->where('IsDeleted', 0);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    // Insert a new record
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update an existing record
    public function update($id, $data) {
        $this->db->where('ServiceID', $id);
        return $this->db->update($this->table, $data);
    }

    // Soft delete a record
    public function delete($id) {
        $this->db->where('ServiceID', $id);
        return $this->db->update($this->table, ['IsDeleted' => 1]);
    }

    // Optional: Restore a soft-deleted record
    public function restore($id) {
        $this->db->where('ServiceID', $id);
        return $this->db->update($this->table, ['IsDeleted' => 0]);
    }
}
?>
