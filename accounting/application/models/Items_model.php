<?php
class Items_model extends MY_Model
{
    protected $_table = "items";
    protected $primary_key = "ItemID";

    // Define before_create and before_update hooks
    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    // Method to handle data before creation (must be public)
    public function prop_data_before_create($data)
    {
        // Add logic here if needed before creating a vendor
        if (isset($data['CategoryID']) && $data['CategoryID'] != 0) {
            $category = $this->db->get_where('categories', ['CategoryID' => $data['CategoryID'], 'IsDeleted' => 0])->row();
            if ($category) {
                $data['CategoryName'] = $category->CategoryName;
            }
        }

        if (isset($data['BrandID']) && $data['BrandID'] != 0) {
            $brand = $this->db->get_where('brand', ['BrandID' => $data['BrandID'], 'IsDeleted' => 0])->row();
            if ($brand) {
                $data['BrandName'] = $brand->BrandName;
            }
        }

        return $data;
    }

    // Method to handle data before update (must be public)
    public function prop_data_before_update($data)
    {
        if (isset($data['CategoryID']) && $data['CategoryID'] != 0) {
            $category = $this->db->get_where('categories', ['CategoryID' => $data['CategoryID'], 'IsDeleted' => 0])->row();
            if ($category) {
                $data['CategoryName'] = $category->CategoryName;
            }
        }

        if (isset($data['BrandID']) && $data['BrandID'] != 0) {
            $brand = $this->db->get_where('brand', ['BrandID' => $data['BrandID'], 'IsDeleted' => 0])->row();
            if ($brand) {
                $data['BrandName'] = $brand->BrandName;
            }
        }

        return $data;
    }



    // In Items_model.php
    public function get_categories() {

        $this->db->where('IsDeleted', 0); 
        return $this->db->get('categories')->result();
    }

    public function get_items_with_categories() {
        $this->db->select('items.*, categories.CategoryName, brand.BrandName');
        $this->db->from('items');
        $this->db->join('categories', 'items.CategoryID = categories.CategoryID', 'left');
        $this->db->join('brand', 'items.BrandID = brand.BrandID', 'left');
        $this->db->where('items.IsDeleted', 0); // Adding condition to filter out deleted items
        $result = $this->db->get()->result();
        log_message('debug', print_r($result, TRUE));
        return $result;
    }



    public function get_brand() {

        $this->db->where('IsDeleted', 0); 
        return $this->db->get('brand')->result();
    }

    public function get_items($filters = [], $limit = 10, $offset = 0) {
        $this->db->select('items.ItemID, items.ItemName, categories.CategoryName, brand.BrandName');
        $this->db->from('items');
        $this->db->join('categories', 'items.CategoryID = categories.CategoryID', 'left');
        $this->db->join('brand', 'items.BrandID = brand.BrandID', 'left');
        $this->db->where('items.IsDeleted', 0);

        if (!empty($filters['CategoryID'])) {
            $this->db->where('items.CategoryID', $filters['CategoryID']);
        }
        if (!empty($filters['BrandID'])) {
            $this->db->where('items.BrandID', $filters['BrandID']);
        }

        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }



    


}
?>
