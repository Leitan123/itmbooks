<?php

class Quotation_model extends CI_Model {

    public function get_all()
    {
        $this->db->select('
            quotation.*,
            customer.CompanyName,
            customer.DomainName,
            GROUP_CONCAT(items.ItemName SEPARATOR ", ") AS ItemName
        ');

        $this->db->from('quotation');

        $this->db->join(
            'customer',
            'customer.CustomerId = quotation.CustomerID',
            'left'
        );

        $this->db->join(
            'quotation_items',
            'quotation_items.QuotationID = quotation.QuotationID',
            'left'
        );

        $this->db->join(
            'items',
            'items.ItemID = quotation_items.ItemID',
            'left'
        );

        $this->db->where('quotation.IsDeleted', 0);

        $this->db->group_by('quotation.QuotationID');

        $this->db->order_by('quotation.QuotationID', 'DESC');

        return $this->db->get()->result();
    }

    public function insert($data)
    {
        $this->db->insert('quotation', $data);

        return $this->db->insert_id();
    }

    public function insert_item($data)
    {
        return $this->db->insert('quotation_items', $data);
    }
}