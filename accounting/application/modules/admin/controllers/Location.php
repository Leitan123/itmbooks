<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Location extends MY_Controller
{
    var $page = "location";
    var $img_width = 600;
    var $img_height = 600;

    function __construct()
    {
        parent::__construct();
        $this->load->model('location_model', 'model');
        $this->controller = get_class($this);
    }

    function create()
    {
        $count = $this->model->count_by([]);
        $cus = $count + 1;

        // Generate a five-digit Customer Number
        $ShopName = sprintf('%05d', $cus);

        $this->obj = $this->model->get_all_fields();
        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj,
            'ShopName' => $ShopName,
        ];

        $this->_form($d);
    }



    function _form(&$d, $_id = 0)
    {
        $this->form_validation->set_rules("form[ShopName]", "Shop Name", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post('form');

            // Prepare customer data for insertion or update
            $location_data = [
                'ShopName'   => $post['ShopName'],
                'Address'     => $post['Address'],
                'CreatedBy' => isset($post['CreatedBy']) ? $post['CreatedBy'] : NULL,
                'CreatedDate' => isset($post['CreatedDate']) ? $post['CreatedDate'] : date("Y-m-d H:i:s"),
                'ModifiedBy' => isset($post['ModifiedBy']) ? $post['ModifiedBy'] : NULL,
                'ModifiedDate' => isset($post['ModifiedDate']) ? $post['ModifiedDate'] : date("Y-m-d H:i:s"),
                'IsDeleted' => isset($post['IsDeleted']) ? $post['IsDeleted'] : 0
            ];

            $this->db->trans_begin();
            if ($_id) {
                $this->model->update($_id, $location_data); // Update customer data
            } else {
                $post['CreatedDate'] = date("Y-m-d H:i:s");
                $this->model->insert($location_data); // Insert customer data
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
                    "text" => '<strong> Added the Shop Successfully ' . (!$_id ? "Added." : "Updated.") . '</strong>'
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
                'ShopName' => $obj->ShopName
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
