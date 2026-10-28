<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Items extends MY_Controller
{
    var $page = "items";
    var $img_width = 600;
    var $img_height = 600;

    function __construct()
    {
        parent::__construct();
        $this->load->model('items_model', 'model');
        $this->load->model('brand_model'); // Ensure the brand model is loaded
        $this->controller = get_class($this);
    }

    function create()
    {
        $this->obj = $this->model->get_all_fields();
        $categories = $this->model->get_categories(); // Load categories
        $brands = $this->model->get_brand(); // Load brands
        $vendors = $this->db->where('IsDeleted', 0)->get('vendor')->result(); // Load vendors

        $data = [
            'page' => "{$this->page}/form",
            'obj' => $this->obj,
            'categories' => $categories,
            'brands' => $brands,
            'vendors' => $vendors,
        ];

        $this->_form($data);
    }

    function _form(&$data, $_id = 0)
    {
        $this->form_validation->set_rules("form[ItemName]", "Item Name", "required");

        if ($this->form_validation->run()) {
            $post = $this->input->post('form');

            $itemType = isset($post['ItemType']) ? $post['ItemType'] : 'Product';
            
            $item_data = [
                'ItemName' => isset($post['ItemName']) ? $post['ItemName'] : '',
                'ItemCode' => isset($post['ItemCode']) ? $post['ItemCode'] : '',
                'ItemType' => $itemType,
                'ItemDescription' => isset($post['ItemDescription']) ? $post['ItemDescription'] : '',
                'PurchasePrice' => isset($post['PurchasePrice']) ? $post['PurchasePrice'] : 0,
                'SellingPrice' => isset($post['SellingPrice']) ? $post['SellingPrice'] : 0,
                'Stock' => isset($post['Stock']) ? $post['Stock'] : 0,
                'CategoryID' => isset($post['CategoryID']) ? $post['CategoryID'] : 0,
                'CategoryName' => isset($post['CategoryName']) ? $post['CategoryName'] : '',
                'BrandID' => isset($post['BrandID']) ? $post['BrandID'] : 0,
                'BrandName' => isset($post['BrandName']) ? $post['BrandName'] : '',
                'DomainName' => isset($post['DomainName']) ? $post['DomainName'] : '',
                'RenewalDate' => isset($post['RenewalDate']) ? $post['RenewalDate'] : NULL,
                'ServiceDuration' => isset($post['ServiceDuration']) ? $post['ServiceDuration'] : '',
                'Length' => ($itemType == 'Product' && isset($post['Length']) && $post['Length'] !== '') ? (float)$post['Length'] : NULL,
                'Width' => ($itemType == 'Product' && isset($post['Width']) && $post['Width'] !== '') ? (float)$post['Width'] : NULL,
                'Height' => ($itemType == 'Product' && isset($post['Height']) && $post['Height'] !== '') ? (float)$post['Height'] : NULL,
                'DimensionUnit' => ($itemType == 'Product' && isset($post['DimensionUnit'])) ? $post['DimensionUnit'] : 'cm',
                'Weight' => ($itemType == 'Product' && isset($post['Weight']) && $post['Weight'] !== '') ? (float)$post['Weight'] : NULL,
                'WeightUnit' => ($itemType == 'Product' && isset($post['WeightUnit'])) ? $post['WeightUnit'] : 'kg',
                'Unit' => isset($post['Unit']) ? $post['Unit'] : 'Pcs',
                'PreferredVendorID' => ($itemType == 'Product' && !empty($post['PreferredVendorID'])) ? (int)$post['PreferredVendorID'] : NULL,
                'UpdatedAt' => date('Y-m-d H:i:s'),
            ];

            if (!$_id) {
                $item_data['CreatedAt'] = date('Y-m-d H:i:s');
                $item_data['IsDeleted'] = 0;
            }

            $this->db->trans_begin();

            if ($_id) {
                $this->model->update($_id, $item_data);
                $itemId = $_id;
            } else {
                $this->model->insert($item_data);
                $itemId = $this->db->insert_id();
            }

            // Image Upload Handling
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $dir = FCPATH . 'media/img/items/';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (empty($ext)) {
                    $ext = 'png';
                }
                $relativePath = 'media/img/items/' . $itemId . '.' . $ext;
                $dest = FCPATH . $relativePath;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $this->db->where('ItemID', $itemId)->update('items', ['Image' => $relativePath]);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => 'Failed to save item. Please try again.'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => 'Item saved successfully.'
                ]);
            }

            redirect(current_url());
        }

        $this->view($data);
    }

    function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->model->get($_id);
            if (!$obj) {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => 'Record not found.'
                ]);
                redirect(base_url("admin/$this->controller"));
            }

            $categories = $this->model->get_categories();
            $brands = $this->model->get_brand();
            $vendors = $this->db->where('IsDeleted', 0)->get('vendor')->result(); // Load vendors

            $data = [
                'page' => "{$this->page}/form",
                'obj' => $obj,
                'categories' => $categories,
                'brands' => $brands,
                'vendors' => $vendors,
            ];

            $this->_form($data, $_id);
        } else {
            $this->index();
        }
    }


    

    function index()
    {
        $data = [
            'page' => "{$this->page}/index",
            'records' => $this->model->get_items_with_categories(),
        ];
        $this->view($data);
    }

    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->delete($_id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => 'Record deleted successfully.'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => 'Failed to delete record or record not found.'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }

    public function get_brands_by_category()
    {
        $category_id = $this->input->post('category_id');

        if ($category_id) {
            $brands = $this->brand_model->get_brands_by_category($category_id);
            echo json_encode($brands);
        } else {
            echo json_encode([]);
        }
    }
}
?>
