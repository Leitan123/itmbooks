<?php
class Customer_model extends MY_Model
{
    protected $_table = "customer";  // Adjust according to your actual table name
    protected $primary_key = "CustomerId";

    // Define before_create and before_update hooks
    public $before_create = ['prop_data_before_create'];
    public $before_update = ['prop_data_before_update'];

    // Method to handle data before creation (must be public)
    public function prop_data_before_create($data)
    {
        // Add logic here if needed before creating a customer
        return $data;
    }

    // Method to handle data before update (must be public)
    public function prop_data_before_update($data)
    {
        // Add logic here if needed before updating a customer
        return $data;
    }

    // Fetch all customers
    public function get_all()
    {
        $this->db->select('
            c.*,
            COALESCE(
                (SELECT MAX(cp.PaymentDate) 
                 FROM customer_payments cp 
                 WHERE cp.CustomerId = c.CustomerId AND cp.IsDeleted = 0),
                c.LastRenewalDate
            ) AS LastRenewalDate
        ', FALSE);
        $this->db->from($this->_table . ' c');
        $this->db->where('c.IsDeleted', 0);
        return $this->db->get()->result();
    }

    // Check if the same MobileNo already exists
    public function check_duplicate_mobileno($mobileno, $exclude_id = null)
    {
        $this->db->where('MobileNo', $mobileno);

        if ($exclude_id) {
            // Exclude the current record when updating
            $this->db->where('CustomerId !=', $exclude_id);
        }

        $query = $this->db->get($this->_table);

        return $query->num_rows() > 0;
    }

    public function check_duplicate_domainname($domainname, $exclude_id = null)
    {
        $this->db->where('DomainName', $domainname);

        if ($exclude_id) {
            // Exclude the current record when updating
            $this->db->where('CustomerId !=', $exclude_id);
        }

        $query = $this->db->get($this->_table);

        return $query->num_rows() > 0;
    }


    public function get_total_customers()
    {
        $this->db->where('IsDeleted', 0); // Assuming 'IsDeleted' column indicates active customers
        return $this->db->count_all_results('customer');
    }


    public function get_customer_details($customerId)
    {
        $this->db->select('CreditLimit, CustomerType');
        $this->db->from('customer');
        $this->db->where('CustomerId', $customerId);
        $result = $this->db->get()->row_array();

        log_message('debug', 'Database Query Result: ' . json_encode($result));  // Debugging log

        return $result;
    }

    public function get_by_id($customerId)
    {
        $this->db->where('CustomerId', $customerId);
        return $this->db->get('customer')->row();
    }


    public function get_all_customers()
{
    return $this->db->select('CustomerId, CustomerName')
                    ->from('customer')
                    ->where('IsDeleted', 0)
                    ->order_by('CustomerName', 'ASC')
                    ->get()
                    ->result();
}







}
?>
