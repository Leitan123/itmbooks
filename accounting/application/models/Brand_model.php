<?php
class Brand_model extends MY_Model
{
    protected $_table = "brand";
    protected $primary_key = "BrandID";

    // Define before_create and before_update hooks
    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    // Method to handle data before creation (must be public)
    public function prop_data_before_create($data)
    {
        // Add logic here if needed before creating a vendor
        return $data;
    }

    // Method to handle data before update (must be public)
    public function prop_data_before_update($data)
    {
        // Add logic here if needed before updating a vendor
        return $data;
    }


    public function get_categories() {

        $this->db->where('IsDeleted', 0); 
        return $this->db->get('categories')->result();
    }

    public function get_all() {
        // Select the necessary fields from both tables
        $this->db->select('brand.*, categories.CategoryName');
        
        // Join the brand table with the categories table based on CategoryID
        $this->db->from('brand');
        $this->db->join('categories', 'brand.CategoryID = categories.CategoryID', 'left');
        
        // Ensure that only records where IsDeleted is 0 are selected
        $this->db->where('brand.IsDeleted', 0); 
        
        // Execute the query and return the result
        return $this->db->get()->result();
    }


    public function get_brands_by_category($category_id)
    {
        // Get the brands for the given CategoryID
        $this->db->where('CategoryID', $category_id);
        $query = $this->db->get('brand');
        
        return $query->result();
    }




}
?>
