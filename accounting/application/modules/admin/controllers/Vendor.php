<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Vendor extends MY_Controller
{
    var $page = "vendor";
    var $img_width = 600;
    var $img_height = 600;

    function __construct()
    {
        parent::__construct();
        $this->load->model('vendor_model', 'model');
        $this->controller = get_class($this);
    }

    function create()
    {
        $count = $this->model->count_by([]);
        $ven = $count + 1;
        $VendorNo = sprintf('%06d', $ven);
        $this->obj = $this->model->get_all_fields();
        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj,
            'VendorNo' => $VendorNo
        ];
        $this->_form($d);
    }

    function _form(&$d, $_id = 0)
    {
        $this->form_validation->set_rules("form[VendorName]", "Vendor Name", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post('form');
            $Vendor_data = [
                'VendorNo' => $post['VendorNo'],
                'VendorName' => $post['VendorName'],
                'VendorDate' => $post['VendorDate'],
                'CompanyName' => isset($post['CompanyName']) ? $post['CompanyName'] : '',
                'Address' => isset($post['Address']) ? $post['Address'] : '',
                'ContactNo' => isset($post['ContactNo']) ? $post['ContactNo'] : '',
                'MobileNo' => isset($post['MobileNo']) ? $post['MobileNo'] : '',
                'EmailId' => isset($post['EmailId']) ? $post['EmailId'] : '',
                'Remark' => isset($post['Remark']) ? $post['Remark'] : '',
                'Image' => isset($post['Image']) ? $post['Image'] : '',
                'CreatedBy' => isset($post['CreatedBy']) ? $post['CreatedBy'] : NULL,
                'CreatedDate' => isset($post['CreatedDate']) ? $post['CreatedDate'] : date("Y-m-d H:i:s"),
                'ModifiedBy' => isset($post['ModifiedBy']) ? $post['ModifiedBy'] : NULL,
                'ModifiedDate' => isset($post['ModifiedDate']) ? $post['ModifiedDate'] : date("Y-m-d H:i:s"),
                'IsDeleted' => isset($post['IsDeleted']) ? $post['IsDeleted'] : 0,
                'Salutation' => isset($post['Salutation']) ? $post['Salutation'] : '',
                'FirstName' => isset($post['FirstName']) ? $post['FirstName'] : '',
                'LastName' => isset($post['LastName']) ? $post['LastName'] : '',
                'VendorLanguage' => isset($post['VendorLanguage']) ? $post['VendorLanguage'] : 'English',
                'TaxRate' => isset($post['TaxRate']) ? $post['TaxRate'] : NULL,
                'CompanyID' => isset($post['CompanyID']) ? $post['CompanyID'] : NULL,
                'Currency' => isset($post['Currency']) ? $post['Currency'] : 'LKR',
                'AccountsPayable' => isset($post['AccountsPayable']) ? $post['AccountsPayable'] : NULL,
                'PaymentTerms' => isset($post['PaymentTerms']) ? $post['PaymentTerms'] : 'Due on Receipt',
                'EnablePortal' => isset($post['EnablePortal']) ? intval($post['EnablePortal']) : 0,
                'Website' => isset($post['Website']) ? $post['Website'] : NULL,
                'WhatsAppNo' => isset($post['WhatsAppNo']) ? $post['WhatsAppNo'] : NULL
            ];

            $this->db->trans_begin();
            if ($_id) {
                $this->model->update($_id, $Vendor_data); // Update vendor data
            } else {
                $this->model->insert($Vendor_data); // Insert vendor data
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>' . (!$_id ? "Creation." : "Update.") . ' Failure !!! </strong>'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong> Vendor Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
                ]);
            }

            redirect(current_url());
        }
        $this->view($d);
    }


    function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->model->get($_id);
            $d = [
                'page' => "$this->page/form",
                'obj' => $obj,
                'VendorNo' => $obj->VendorNo
            ];
            $this->_form($d, $_id);
        } else {
            $this->index();
        }
    }

    function index()
    {
        $d = [
            'page' => "$this->page/index",
            'records' => $this->model->get_all()
        ];
        $this->view($d);
    }

    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->delete($_id);
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }

    function rearrange()
    {
        $d = [
            'page' => "$this->page/rearrange",
            'records' => $this->model->get_all()
        ];
        $this->_form($d);
    }

    function get_settle_date()
    {
        $days = $this->input->post('days', TRUE); // Sanitize input
        $date = date('Y-m-d');
        $holis = $this->db->query("SELECT Date FROM calendar WHERE Status=0 AND Date >= ?", [$date])->result();
        $holidayDates = [];
        foreach ($holis as $holi) {
            $holidayDates[] = $holi->Date;
        }

        $count5WD = 0;
        $temp = $date;
        while ($count5WD < $days) {
            $next1WDDate = date('Y-m-d', strtotime("+1 day", strtotime($temp)));
            if (!in_array($next1WDDate, $holidayDates)) {
                $count5WD++;
            }
            $temp = $next1WDDate;
        }

        echo $next1WDDate;
    }
    
}
?>
