<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Company extends MY_Controller
{
    var $page = "company";

    function __construct()
    {
        parent::__construct();

        $this->load->model('Company_model', 'model');

        $this->controller = get_class($this);
    }

    function index()
    {
        if (!$this->db->field_exists('ThemeColor', 'company_settings')) {
            $this->load->dbforge();
            $fields = array(
                'ThemeColor' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'default' => '#2f3d4f',
                    'null' => TRUE
                )
            );
            $this->dbforge->add_column('company_settings', $fields);
        }

        $d = [

            'page' => "$this->page/form",

            'company' => $this->model->get_company()

        ];

        $this->view($d);
    }

    function save()
    {
        $data = [

            'CompanyName'    => $this->input->post('CompanyName'),
            'Address'        => $this->input->post('Address'),
            'Telephone'      => $this->input->post('Telephone'),
            'Mobile'         => $this->input->post('Mobile'),
            'Email'          => $this->input->post('Email'),
            'Website'        => $this->input->post('Website'),
            'RegistrationNo' => $this->input->post('RegistrationNo'),
            'TaxNo'          => $this->input->post('TaxNo'),
            'ThemeColor'     => $this->input->post('ThemeColor')

        ];

        $company = $this->model->get_company();

        // Handle Logo Upload
        if (isset($_FILES['Logo']) && !empty($_FILES['Logo']['name'])) {
            $config['upload_path']   = FCPATH . 'media/img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB max
            $config['file_name']     = 'logo_' . time();

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('Logo')) {
                $upload_data = $this->upload->data();
                $data['Logo'] = $upload_data['file_name'];

                // Delete old logo file if it exists
                if ($company && !empty($company->Logo)) {
                    $old_logo = FCPATH . 'media/img/' . $company->Logo;
                    if (file_exists($old_logo)) {
                        @unlink($old_logo);
                    }
                }
            } else {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text' => 'Logo upload failed: ' . $error
                ]);
                redirect('admin/company');
                return;
            }
        }

        if($company)
        {
            $this->db->where(
                'CompanyID',
                $company->CompanyID
            );

            $this->db->update(
                'company_settings',
                $data
            );
        }
        else
        {
            $data['CreatedDate'] =
                date('Y-m-d H:i:s');

            $this->db->insert(
                'company_settings',
                $data
            );
        }

        $this->session->set_flashdata('notification', [
            'alert' => 'success',
            'text' => 'Company settings saved successfully.'
        ]);

        redirect('admin/company');
    }
}