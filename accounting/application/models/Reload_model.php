<?php
class Reload_model extends MY_Model
{
    protected $_table = "reload";
    protected $primary_key = "ReloadId";

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


    public function get_all() {
        $this->db->select('reload.*, customer.MobileNo');
        $this->db->from($this->_table);
        $this->db->join('customer', 'customer.Vc = reload.Vc');
        $this->db->where('reload.IsDeleted', 0);
          $this->db->order_by('reload.ReloadId', 'DESC');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }


    public function get_by_id($reloadId) {
        $this->db->select('reload.*, customer.MobileNo, customer.Sid, customer.CustomerName');  // Select all needed fields
        $this->db->from($this->_table);
        $this->db->join('customer', 'customer.Vc = reload.Vc');  // Join on VC
        $this->db->where('reload.ReloadId', $reloadId);
        $this->db->where('reload.IsDeleted', 0);  // Assuming soft deletes
        $query = $this->db->get();
        
        // No debugging output, just return the result
        return $query->row();
    }


    public function get_reload_data() {
        // Example query to fetch reload data from the 'reload' table
        $query = $this->db->select('*')->from('reload')->get();

        if ($query->num_rows() > 0) {
            return $query->result();  // Return result if there are rows
        } else {
            return [];  // Return an empty array if no data is found
        }
    }

    
    
     function getCustomerMobile($Vc) {
        $query = $this->db->query("SELECT MobileNo  "
                . "FROM customer where Vc ='$Vc'");
        $res = $query->result();
        return $res[0]->MobileNo;
    }

    function getExpiryRemider($Vc) {
            $query = $this->db->query("SELECT r.ReloadId,r.Provider,r.ExpiryON,c.MobileNo
    FROM reload r
    INNER join customer c on  c.Vc = r.vc
    WHERE r.ExpiryON <= DATE_ADD(CURDATE(), INTERVAL 5 DAY) AND r.isExpiryMsg=0;");

            return $query->result();;
        }
        
          function UpdateExpiryStatus($ReloadId) {

    $data = array(
                    'isExpiryMsg' => 1,
                );
    $where = array('ReloadId ' => $ReloadId );
            $this->db->update('reload', $data, $where);

            $db_error = $this->db->error();

            //echo $this->db->last_query();
            if ($db_error['message'] != '') {
                throw new Exception('Error: ' . $db_error['message']);

                return $db_error['message'];
            }
            return '';
        }



    public function get_latest_reload_data($limit = 10)
    {
        // Join the reload and customer tables based on a common key (assumed as Vc or CustomerID)
        $this->db->select('reload.*, customer.MobileNo'); // Select reload fields and MobileNo from customer
        $this->db->from('reload');
        
        $this->db->join('customer', 'customer.Vc = reload.Vc'); // Assuming Vc is the common field
        $this->db->where('reload.IsDeleted', 0); // Only include records where IsDeleted is 0
        $this->db->order_by('reload.ReloadId', 'DESC'); // Order by the latest reloads
        $this->db->limit($limit); // Limit the results to the last 10 records
        
        $query = $this->db->get();
        return $query->result_array();
    }


    // Total reloads for today
    public function get_reload_total_today()
    {
        // Sum multiple fields if needed
        $this->db->select('SUM(Package + ExtraCharge + AddOn) as total_amount');
        $this->db->from('reload');
        $this->db->where('DATE(CreatedDate)', date('Y-m-d'));
        $this->db->where('IsDeleted', 0);

        $query = $this->db->get();
        return $query->row()->total_amount;
    }

    // Total reloads for the current month
    public function get_total_reload_month() {
        $this->db->select_sum('Amount'); // Adjust 'Amount' field as needed
        $this->db->where('MONTH(CreatedDate)', date('m'));
        $this->db->where('YEAR(CreatedDate)', date('Y'));
        $query = $this->db->get('reload');
        return $query->row()->Amount;
    }

    // Total reloads for the year
    public function get_total_reload_year() {
        $this->db->select_sum('Amount'); // Adjust 'Amount' if necessary
        $this->db->where('YEAR(CreatedDate)', date('Y'));
        $query = $this->db->get('reload');
        return $query->row()->Amount;
    }

    // Reload data by provider
    public function get_provider_reload_data() {
        $this->db->select('Provider, SUM(Amount) as total_amount'); // Adjust 'Amount' as necessary
        $this->db->where('IsDeleted', 0);
        $this->db->group_by('Provider');
        $query = $this->db->get('reload');
        return $query->result_array();
    }

    public function get_reload_count_today()
    {
        $this->db->select('COUNT(*) as reload_count'); // Count the number of reloads
        $this->db->from('reload');
        $this->db->where('DATE(CreatedDate)', date('Y-m-d')); // Assuming `CreatedDate` stores the timestamp of reloads
        $this->db->where('IsDeleted', 0);
        $query = $this->db->get();
        return $query->row()->reload_count; // Return the count
    }


   public function get_unpaid_reload_data() {
        $this->db->select('reload.*, customer.MobileNo');
        $this->db->from($this->_table);
        $this->db->join('customer', 'customer.Vc = reload.Vc');
        $this->db->where('reload.IsDeleted', 0);
          $this->db->order_by('reload.ReloadId', 'DESC');
        $this->db->where('PayStatus', 'Unpaid'); // Assuming 'PayStatus' column has 'Unpaid' value for unpaid records
        $query = $this->db->get();
        return $query->result();
    }


}

?>
