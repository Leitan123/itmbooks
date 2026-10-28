<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Service extends MY_Controller {
    var $page = "service";

    function __construct() {
        parent::__construct();
        $this->load->model('service_model', 'model');  // Load the Service model
        $this->controller = get_class($this);
    }

    public function index() {
        // Load a list of all service records
        $d = [
            'page' => "$this->page/index",
            'records' => $this->model->get_all() // Fetch all services
        ];
        $this->view($d);
    }

    public function create() {
        // Data to pass to the form view
        $d = [
            'page' => "$this->page/form",
            'formData' => [],
            'customers' => $this->db->get_where('customer', ['IsDeleted' => 0])->result() // Fetch active customers
        ];

        if ($this->input->post()) {
            $post = $this->input->post('form', true); // XSS filtering

            // Prepare data for insertion
            $service_data = [
                'CustomerId' => $post['CustomerId'],
                'Inquiry' => $post['Inquiry'],
                'JobStatus' => $post['JobStatus'],
                'JobDetails' => $post['JobDetails'],
                'Technician' => $post['Technician'],
                'ServiceDate' => $post['ServiceDate'],
                'CreatedAt' => date('Y-m-d H:i:s'),
                'UpdatedAt' => date('Y-m-d H:i:s')
            ];

            if ($this->model->insert($service_data)) {
                redirect('admin/service'); // Redirect to service list
            } else {
                $d['error'] = 'Unable to save service data.';
            }
        }

        $this->_form($d);
    }


    function edit($_id = 0) {
        if ($_id) {
            $obj = $this->model->get($_id);
            if ($obj) {
                $d = [
                    'page' => "$this->page/form",
                    'obj' => $obj,
                    'ServiceID' => $obj->ServiceID,
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



    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->delete($_id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Record Deleted Successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to Delete Record or Record Not Found.</strong>'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }

    private function _form(&$d, $_id = 0) {
        // Set form validation rules
        $this->form_validation->set_rules("form[CustomerId]", "Customer ID", "required");
        $this->form_validation->set_rules("form[Inquiry]", "Inquiry", "required");
        $this->form_validation->set_rules("form[JobStatus]", "Job Status", "required");
        $this->form_validation->set_rules("form[JobDetails]", "Job Details", "required");

        if ($this->form_validation->run() == FALSE) {
            $this->view($d);
        } else {
            $post = $this->input->post('form', TRUE);
            $post['UpdatedAt'] = date("Y-m-d H:i:s");

            $this->db->trans_begin();
            try {
                if ($_id) {
                    $this->model->update($_id, $post);
                } else {
                    $post['CreatedAt'] = date("Y-m-d H:i:s");
                    $this->model->insert($post);
                }

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Transaction failed');
                }

                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong> Service Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
                ]);

                redirect('admin/service');
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
}
?>
