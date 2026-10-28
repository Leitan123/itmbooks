<?php
class Warehouse_model extends MY_Model
{
    protected $_table = "warehouse";
    protected $primary_key = "WarehouseID";

    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    public function prop_data_before_create($data)
    {
        return $data;
    }

    public function prop_data_before_update($data)
    {
        return $data;
    }

    public function get_all()
    {
        $this->db->where('IsDeleted', 0);
        return $this->db->get($this->_table)->result();
    }
}
?>
