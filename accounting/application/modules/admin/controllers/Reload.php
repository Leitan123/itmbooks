<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Reload extends MY_Controller {
    var $page = "reload";

    function __construct() {
        parent::__construct();
        $this->load->model('reload_model', 'model');
        $this->controller = get_class($this);
    }

    public function create() {
        // Fetch the list of registered customers (VC)
        $d = [
            'page' => "$this->page/form",
            'formData' => [],
            'customers' => $this->db->get_where('customer', ['IsDeleted' => 0])->result() // Fetch customers whose IsDeleted is 0
        ];

        if ($this->input->post()) {
            $post = $this->input->post('form', true); // XSS filtering

            $reload_data = [
                'Vc' => $post['Vc'],  // VC from the customer table
                'Provider' => $post['Provider'],
                'Package' => $post['Package'],
                'PackageD' => $post['PackageD'],
                'AddOn' => $post['AddOn'],
                'AddOnd' => $post['AddOnd'],
                'ExtraCharge' => $post['ExtraCharge'],
                'ExtraCharged' => $post['ExtraCharged'],
                'ExpiryON' => $post['ExpiryON'],
                'PaymentMethod' => $post['PaymentMethod'],
                'PayStatus' => $post['PayStatus'],
                'Remark' => $post['Remark'],
                'CreatedDate' => date('Y-m-d H:i:s'),
                'ModifiedDate' => date('Y-m-d H:i:s')
            ];

            if ($this->model->insert($reload_data)) {
                $mobile = $this->model->getCustomerMobile($post['Vc']);
                
                if($mobile!= ''){
                    if ($mobile[0] === '0') {
    $mobile = "94".substr($mobile, 1);
}
$addonmsg ="";
$totalReload =(int)$post['Package'];
if (isset($post['AddOn']) && $post['AddOn'] !== '' && $post['AddOn'] !== null && $post['AddOn'] > 0) {
    $totalReload +=(int)$post['AddOn'];
}
if (isset($post['AddOnd']) && $post['AddOnd'] !== '' && $post['AddOnd'] !== null) {
    $addonmsg = "including {$post['AddOnd']}";
}



                $api_instance = new NotifyLk\Api\SmsApi();
$user_id = ""; 
$api_key = ""; 
$message = "Dear Customer, You have successfully recharged Rs.{$totalReload} for your {$post['Provider']}. Please keep the setup box switched on help line : 0768866972 / 0112339920 . Thank you for choosing us!"; 
$to = $mobile; 
$sender_id = ""; 
$contact_fname = ""; 
$contact_lname = "";  
$contact_email = "";  
$contact_address = "";  
$contact_group = 0;  
$type = null; 

try {
    $api_instance->sendSMS($user_id, $api_key, $message, $to, $sender_id, $contact_fname, $contact_lname, $contact_email, $contact_address, $contact_group, $type);
} catch (Exception $e) {
    echo 'Exception when calling SmsApi->sendSMS: ', $e->getMessage(), PHP_EOL;
}
}
                redirect('admin/reload');
            } else {
                $d['error'] = 'Unable to save reload data.';
            }
        }

        $this->_form($d);
    }

    function _form(&$d, $_id = 0) {
        $this->form_validation->set_rules("form[Vc]", "VC", "required");
        $this->form_validation->set_rules("form[Provider]", "Provider", "required");
        $this->form_validation->set_rules("form[Package]", "Package", "required");
        $this->form_validation->set_rules("form[PaymentMethod]", "Payament Method", "required");
        $this->form_validation->set_rules("form[PayStatus]", "Pay Status", "required");

        if ($this->form_validation->run() == FALSE) {
            $this->view($d);
        } else {
            $post = $this->input->post('form', TRUE);
            $post['ModifiedDate'] = date("Y-m-d H:i:s");

            $this->db->trans_begin();
            try {
                if ($_id) {
                    $this->model->update($_id, $post);
                } else {
                    $post['CreatedDate'] = date("Y-m-d H:i:s");
                    $this->model->insert($post);
                }

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Transaction failed');
                }

                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong> Reload Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
                ]);

                redirect('admin/reload');
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>' . (!$_id ? "Creation" : "Update") . ' Failure!!!</strong>'
                ]);

                $this->view($d);
            }
        }
    }

    function edit($_id = 0) {
        if ($_id) {
            $obj = $this->model->get($_id);
            if ($obj) {
                $d = [
                    'page' => "$this->page/form",
                    'obj' => $obj,
                    'ReloadId' => $obj->ReloadId,
                    'customers' => $this->db->get_where('customer', ['IsDeleted' => 0])->result() // Fetch customers
                ];
                $this->_form($d, $_id);
            } else {
                show_404(); // Record not found
            }
        } else {
            $this->index();
        }
    }



    function index() {

        
        $d = [
            'page' => "$this->page/index",
            'records' => $this->model->get_all() // Fetch records that are not deleted
        ];
        $this->view($d);
    }


    function delete($_id = 0) {
        if ($_id) {
            if ($this->model->delete($_id)) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Record deleted successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to delete record.</strong>'
                ]);
            }
            redirect('admin/reload'); // Ensure this redirects properly
        } else {
            $this->index();
        }
    }


    public function print_receipt($reloadId) {
        // Load the model
        $this->load->model('reload_model');
        
        // Fetch reload data by ID
        $data['reload'] = $this->reload_model->get_by_id($reloadId);

        // Check if the record exists
        if (!$data['reload']) {
            show_404();  // Show 404 if not found
        }

        // Load the view for printing receipt (corrected path)
        $this->load->view('admin/pages/reload/print_receipt', $data);
    }


    public function unpaid_customers_list() {
        // Load the model if not autoloaded
        $this->load->model('Reload_model'); 
        
        // Fetch unpaid reload data
        $data['records'] = $this->Reload_model->get_unpaid_reload_data();

        // Load your view with the unpaid customers list
        $this->load->view('admin/pages/reload/unpaid_customers_list', $data);
    }





}

?>
