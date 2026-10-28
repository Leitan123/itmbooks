<?php

class Job_card_model extends CI_Model {

    public function get_all()
    {
        $this->db->select('job_card.*,
                           customer.CustomerName,
                           customer.CompanyName,
                           items.ItemName,
                           employee.EmployeeName');

        $this->db->from('job_card');

        $this->db->join(
            'customer',
            'customer.CustomerId = job_card.CustomerID',
            'left'
        );

        $this->db->join(
            'items',
            'items.ItemID = job_card.ServiceID',
            'left'
        );

        $this->db->join(
            'employee',
            'employee.EmployeeID = job_card.EmployeeID',
            'left'
        );

        $this->db->where('job_card.IsDeleted', 0);

        return $this->db->get()->result();
    }

    public function get_by_id($JobCardID)
    {
        $this->db->where(
            'JobCardID',
            $JobCardID
        );

        return $this->db->get('job_card')->row();
    }

    public function insert($data)
    {
        $this->db->insert('job_card', $data);

        return $this->db->insert_id();
    }

    public function update($JobCardID, $data)
    {
        $this->db->where(
            'JobCardID',
            $JobCardID
        );

        return $this->db->update(
            'job_card',
            $data
        );
    }

    public function delete($JobCardID)
    {
        $this->db->where(
            'JobCardID',
            $JobCardID
        );

        return $this->db->update(
            'job_card',
            [
                'IsDeleted' => 1
            ]
        );
    }

}