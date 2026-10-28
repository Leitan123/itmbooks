<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drawings_model extends CI_Model
{
    private $table = 'drawings';

    public function get_all($from_date = null, $to_date = null)
    {
        $this->db->select("
            drawings.*,
            employee.EmployeeName AS DirectorName,
            banks.BankName,
            banks.AccountNumber
        ");
        $this->db->from($this->table);
        $this->db->join('employee', 'employee.EmployeeID = drawings.EmployeeID', 'left');
        $this->db->join('banks', 'banks.BankID = drawings.BankID', 'left');

        if ($from_date) {
            $this->db->where('drawings.DrawingDate >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('drawings.DrawingDate <=', $to_date);
        }

        $this->db->order_by('drawings.DrawingDate', 'DESC');
        $this->db->order_by('drawings.DrawingID', 'DESC');

        return $this->db->get()->result();
    }

    public function get($id)
    {
        return $this->db->where('DrawingID', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('DrawingID', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('DrawingID', $id)->delete($this->table);
    }

    public function get_directors()
    {
        return $this->db->select('EmployeeID, EmployeeName, Designation')
                        ->from('employee')
                        ->where('IsDeleted', 0)
                        ->group_start()
                            ->like('Designation', 'Director')
                            ->or_like('Designation', 'Owner')
                        ->group_end()
                        ->order_by('EmployeeName', 'ASC')
                        ->get()
                        ->result();
    }

    public function get_banks()
    {
        return $this->db->where('IsDeleted', 0)
                        ->order_by('BankName', 'ASC')
                        ->get('banks')
                        ->result();
    }
}
?>
