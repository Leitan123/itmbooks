<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Employee extends MY_Controller
{
    var $page = "employee";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Employee_model', 'model');
        $this->controller = get_class($this);
    }

    public function index()
    {
        $d = [
            'page'    => "$this->page/index",
            'records' => $this->model->get_all()
        ];
        $this->view($d);
    }

    public function create()
    {
        $obj = new stdClass();
        $obj->EmployeeID = 0;
        $obj->EmployeeNo = '';
        $obj->MemberNo = '';
        $obj->EmployeeName = '';
        $obj->NIC = '';
        $obj->MobileNo = '';
        $obj->Email = '';
        $obj->Address = '';
        $obj->Designation = '';
        $obj->Department = '';
        $obj->BasicSalary = 0.00;
        $obj->Allowance = 0.00;
        $obj->OTPerHour = 0.00;
        $obj->CostPerMinute = 0.00;
        $obj->JoinDate = date('Y-m-d');
        $obj->Status = 'Active';

        $d = [
            'page' => "$this->page/form",
            'obj'  => $obj
        ];
        $this->view($d);
    }

    public function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->db->where('EmployeeID', $_id)->where('IsDeleted', 0)->get('employee')->row();
            if (!$obj) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Employee not found.'
                ]);
                redirect('admin/employee');
                return;
            }

            $d = [
                'page' => "$this->page/form",
                'obj'  => $obj
            ];
            $this->view($d);
        } else {
            redirect('admin/employee');
        }
    }

    public function save()
    {
        $form = $this->input->post('form');
        if ($form) {
            $id = isset($form['EmployeeID']) ? intval($form['EmployeeID']) : 0;
            unset($form['EmployeeID']);

            // Parse numeric fields safely
            $form['BasicSalary'] = isset($form['BasicSalary']) ? floatval($form['BasicSalary']) : 0.00;
            $form['Allowance'] = isset($form['Allowance']) ? floatval($form['Allowance']) : 0.00;
            $form['OTPerHour'] = isset($form['OTPerHour']) ? floatval($form['OTPerHour']) : 0.00;
            $form['CostPerMinute'] = isset($form['CostPerMinute']) ? floatval($form['CostPerMinute']) : 0.00;

            if (empty($form['JoinDate'])) {
                $form['JoinDate'] = null;
            }

            // Password creation or reset via admin
            if (!empty($form['Password'])) {
                $form['Password'] = password_hash($form['Password'], PASSWORD_BCRYPT);
            } else {
                if ($id) {
                    unset($form['Password']);
                } else {
                    $form['Password'] = password_hash('123456', PASSWORD_BCRYPT); // Default password for new employees
                }
            }

            if ($id) {
                // Update
                $this->db->where('EmployeeID', $id)->update('employee', $form);
                $message = 'Employee Updated Successfully';
            } else {
                // Create
                $form['CreatedDate'] = date('Y-m-d H:i:s');
                $this->db->insert('employee', $form);
                $id = $this->db->insert_id();
                $message = 'Employee Added Successfully';
            }

            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $dir = FCPATH . 'media/img/employees/';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                
                $dest = $dir . $id . '.png';
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], $dest);
            }

            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text'  => '<strong>' . $message . '</strong>'
            ]);
        }
        redirect('admin/employee');
    }

    public function details($_id = 0)
    {
        if ($_id) {
            $obj = $this->db->where('EmployeeID', $_id)->where('IsDeleted', 0)->get('employee')->row();
            if (!$obj) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Employee not found.'
                ]);
                redirect('admin/employee');
                return;
            }

            $d = [
                'page' => "$this->page/view",
                'obj'  => $obj
            ];
            $this->view($d);
        } else {
            redirect('admin/employee');
        }
    }

    public function delete($_id = 0)
    {
        if ($_id) {
            $this->db->where('EmployeeID', $_id)->update('employee', ['IsDeleted' => 1]);
            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text'  => '<strong>Employee Deleted Successfully</strong>'
            ]);
        }
        redirect('admin/employee');
    }

    public function epf_c_form()
    {
        $month = $this->input->get('month');
        $epf_reg_no = $this->input->get('epf_reg_no');
        $cheque_no = $this->input->get('cheque_no');
        $bank_branch = $this->input->get('bank_branch');
        $surcharges = floatval($this->input->get('surcharges'));

        if (!$month) {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text'  => '<strong>Error:</strong> Please specify a month.'
            ]);
            redirect('admin/employee');
            return;
        }

        // Query active salary slips for this month
        $records = $this->db
            ->select('ss.*, e.EmployeeName, e.NIC, e.MemberNo')
            ->from('salary_slips ss')
            ->join('employee e', 'e.EmployeeID = ss.EmployeeID', 'left')
            ->where('ss.PayrollMonth', $month)
            ->where('ss.IsDeleted', 0)
            ->order_by('CAST(e.MemberNo AS UNSIGNED) ASC, e.MemberNo ASC')
            ->get()
            ->result();

        // Load company settings
        $this->load->model('Company_model', 'company_model');
        $company = $this->company_model->get_company();

        $d = [
            'month'       => $month,
            'epf_reg_no'  => $epf_reg_no,
            'cheque_no'   => $cheque_no,
            'bank_branch' => $bank_branch,
            'surcharges'  => $surcharges,
            'records'     => $records,
            'company'     => $company
        ];

        $this->load->view('pages/employee/epf_c_form', $d);
    }

    public function etf_r4_form()
    {
        $start_month = $this->input->get('start_month');
        $end_month = $this->input->get('end_month');
        $epf_reg_no = $this->input->get('epf_reg_no');
        $cheque_no = $this->input->get('cheque_no');
        $bank_branch = $this->input->get('bank_branch');
        $surcharges = floatval($this->input->get('surcharges'));

        if (!$start_month) {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text'  => '<strong>Error:</strong> Please specify at least a start month.'
            ]);
            redirect('admin/employee');
            return;
        }

        if (!$end_month) {
            $end_month = $start_month;
        }

        // Query salary slips in the date range grouped by employee to sum up basic and etf
        $records = $this->db
            ->select('e.EmployeeID, e.EmployeeName, e.NIC, e.MemberNo, SUM(ss.BasicSalary) as BasicSalary, SUM(ss.CompanyETF) as CompanyETF')
            ->from('salary_slips ss')
            ->join('employee e', 'e.EmployeeID = ss.EmployeeID', 'left')
            ->where('ss.PayrollMonth >=', $start_month)
            ->where('ss.PayrollMonth <=', $end_month)
            ->where('ss.IsDeleted', 0)
            ->group_by('e.EmployeeID')
            ->order_by('CAST(e.MemberNo AS UNSIGNED) ASC, e.MemberNo ASC')
            ->get()
            ->result();

        // Load company settings
        $this->load->model('Company_model', 'company_model');
        $company = $this->company_model->get_company();

        $d = [
            'start_month' => $start_month,
            'end_month'   => $end_month,
            'epf_reg_no'  => $epf_reg_no,
            'cheque_no'   => $cheque_no,
            'bank_branch' => $bank_branch,
            'surcharges'  => $surcharges,
            'records'     => $records,
            'company'     => $company
        ];

        $this->load->view('pages/employee/etf_r4_form', $d);
    }

    public function leaves()
    {
        $records = $this->db
            ->select('el.*, e.EmployeeName, e.EmployeeNo')
            ->from('employee_leaves el')
            ->join('employee e', 'e.EmployeeID = el.EmployeeID', 'left')
            ->order_by('el.CreatedDate', 'DESC')
            ->get()
            ->result();

        $d = [
            'page'    => "$this->page/leaves",
            'records' => $records
        ];
        $this->view($d);
    }

    public function save_leave_status()
    {
        $id = $this->input->post('LeaveID');
        $status = $this->input->post('Status');
        $remarks = $this->input->post('Remarks');

        if ($id && $status) {
            $this->db->where('LeaveID', $id)->update('employee_leaves', [
                'Status' => $status,
                'Remarks' => $remarks
            ]);
            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text'  => '<strong>Leave status updated successfully</strong>'
            ]);
        }
        redirect('admin/employee/leaves');
    }
}
?>