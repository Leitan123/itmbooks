<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Brand extends MY_Controller
{
    var $page = "brand";

    function __construct()
    {
        parent::__construct();
        $this->load->model('brand_model', 'model');
        $this->controller = get_class($this);
    }


    function create()
    {
        $this->obj = $this->model->get_all_fields();
        $categories = $this->model->get_categories(); // Load categories
   

        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj,
            'categories' => $categories, // Pass categories to the view
         
        ];

        $this->_form($d);
    }

    function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->model->get($_id);
            $d = [
                'page' => "$this->page/form",
                'obj' => $obj
            ];
            $this->_form($d, $_id);
        } else {
            $this->index();
        }
    }

    function _form(&$d, $_id = 0)
    {
        // Set form validation rules
        $this->form_validation->set_rules("form[BrandName]", "Brand Name", "required");
        $this->form_validation->set_rules("form[CategoryID]", "Category", "required");

        // Check if form validation passed
        if ($this->form_validation->run()) {
            $post = $this->input->post('form');

            // Prepare brand data for insertion or update
            $data = [
                'BrandName' => $this->input->post('form[BrandName]'),
                'BrandDescription' => $this->input->post('form[BrandDescription]'),
                'CategoryID' => $this->input->post('form[CategoryID]') // Add CategoryID here
            ];

            // Start transaction
            $this->db->trans_begin();

            if ($_id) {
                // Update brand data
                $this->model->update($_id, $data);
            } else {
                // Add CreatedAt timestamp when creating a new brand
                $data['CreatedAt'] = date("Y-m-d H:i:s");
                $this->db->insert('brand', $data); // Insert brand data
            }

            // Check if the transaction was successful
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
                    "text" => '<strong> Brand Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
                ]);
            }

            redirect(current_url());
        }

        // Pass categories to the view
       
        $categories = $this->model->get_categories();
        $d['categories'] = $categories; 

        // Show the form view
        $this->view($d);
    }



    function index()
    {
        $d = [
            'page' => "$this->page/index",
            'records' => $this->model->get_all(),
        ];
        $this->view($d);
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
}
?>
