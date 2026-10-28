<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_notes_model extends CI_Model
{
    protected $table = 'credit_notes';

    public function __construct()
    {
        parent::__construct();
    }

    // Get credit note by ID
    public function get_by_id($id)
    {
        return $this->db->where('CreditNoteID', $id)
                        ->get($this->table)
                        ->row();
    }

    // Save credit note
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Optional: get all credit notes for a sale
    public function get_by_sale($saleID)
    {
        return $this->db->where('SaleID', $saleID)
                        ->get($this->table)
                        ->result();
    }
}
