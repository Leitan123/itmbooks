<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Allow Cross-Origin Requests (CORS) for external devices/Flutter web debugging
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Content-Type: application/json');
        
        // Handle CORS preflight pre-checks
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }

    // 1. POST /admin/api/login
    public function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email and password are required']);
            return;
        }

        $employee = $this->db->get_where('employee', [
            'Email' => $email,
            'Status' => 'Active', // Check if active
            'IsDeleted' => 0
        ])->row();

        if ($employee && password_verify($password, $employee->Password)) {
            // Generate a simple API token containing employee ID and designation
            $token = base64_encode(json_encode([
                'id' => $employee->EmployeeID,
                'designation' => $employee->Designation,
                'expiry' => time() + (86400 * 30) // valid for 30 days
            ]));

            // Check if profile picture exists
            $profile_pic_path = FCPATH . 'media/img/employees/' . $employee->EmployeeID . '.png';
            $profile_pic_url = file_exists($profile_pic_path) 
                ? base_url('media/img/employees/' . $employee->EmployeeID . '.png?') . time() 
                : '';

            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'employee' => [
                    'id' => (int)$employee->EmployeeID,
                    'employee_no' => $employee->EmployeeNo,
                    'name' => $employee->EmployeeName,
                    'email' => $employee->Email,
                    'designation' => $employee->Designation,
                    'department' => $employee->Department,
                    'basic_salary' => (float)$employee->BasicSalary,
                    'profile_picture' => $profile_pic_url
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        }
    }

    // 2. GET /admin/api/payments
    public function payments() {
        $employee_id = $this->input->get('employee_id');

        if (empty($employee_id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Employee ID is required']);
            return;
        }

        $slips = $this->db->select('ss.SlipID, ss.PayrollMonth as Month, ss.NetSalary, ss.BasicSalary, ss.Allowance, ss.AllowancesJSON, ss.OTAmount, ss.GrossSalary, ss.EmployeeEPF, ss.AdvanceDeduction, ss.CompanyEPF AS EmployerEPF, ss.CompanyETF AS EmployerETF, ss.PaymentMethod, ss.PaymentDate, ss.CreatedDate, b.BankName')
            ->from('salary_slips ss')
            ->join('banks b', 'b.BankID = ss.BankID', 'left')
            ->where('ss.EmployeeID', $employee_id)
            ->where('ss.IsDeleted', 0)
            ->order_by('ss.PayrollMonth', 'DESC')
            ->get()
            ->result();

        $formatted_slips = [];
        foreach ($slips as $slip) {
            $allowances_val = (float)$slip->Allowance;
            $deductions_val = (float)$slip->EmployeeEPF + (float)$slip->AdvanceDeduction;

            $formatted_slips[] = [
                'SlipID'           => (string)$slip->SlipID,
                'Month'            => (string)$slip->Month,
                'NetSalary'        => (string)$slip->NetSalary,
                'BasicSalary'      => (string)$slip->BasicSalary,
                'Allowance'        => (string)$slip->Allowance, // For Flutter
                'Allowances'       => (string)$allowances_val,  // For iOS (native)
                'Deductions'       => (string)$deductions_val,  // For iOS (native)
                'AllowancesJSON'   => (string)$slip->AllowancesJSON,
                'OTAmount'         => (string)$slip->OTAmount,
                'GrossSalary'      => (string)$slip->GrossSalary,
                'EmployeeEPF'      => (string)$slip->EmployeeEPF,
                'AdvanceDeduction' => (string)$slip->AdvanceDeduction,
                'EmployerEPF'      => (string)$slip->EmployerEPF,
                'EmployerETF'      => (string)$slip->EmployerETF,
                'PaymentMethod'    => (string)$slip->PaymentMethod,
                'PaymentDate'      => (string)$slip->PaymentDate,
                'CreatedDate'      => (string)$slip->CreatedDate,
                'BankName'         => (string)($slip->BankName ?? ''),
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => $formatted_slips
        ]);
    }

    // 7. POST /admin/api/upload_profile
    public function upload_profile() {
        $employee_id = $this->input->post('employee_id');

        if (empty($employee_id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Employee ID is required']);
            return;
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Valid image file is required']);
            return;
        }

        $dir = FCPATH . 'media/img/employees/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $dest = $dir . $employee_id . '.png';
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            echo json_encode([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'profile_picture' => base_url('media/img/employees/' . $employee_id . '.png?') . time()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to save profile picture']);
        }
    }

    // 3. GET /admin/api/leaves
    public function leaves() {
        $employee_id = $this->input->get('employee_id');

        if (empty($employee_id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Employee ID is required']);
            return;
        }

        $leaves = $this->db->select('LeaveID, StartDate, EndDate, LeaveType, Reason, Status, Remarks, CreatedDate')
            ->where('EmployeeID', $employee_id)
            ->order_by('CreatedDate', 'DESC')
            ->get('employee_leaves')
            ->result();

        echo json_encode([
            'success' => true,
            'data' => $leaves
        ]);
    }

    // 4. POST /admin/api/request_leave
    public function request_leave() {
        $employee_id = $this->input->post('employee_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $leave_type = $this->input->post('leave_type');
        $reason = $this->input->post('reason');

        if (empty($employee_id) || empty($start_date) || empty($end_date) || empty($leave_type)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Required parameters missing']);
            return;
        }

        $data = [
            'EmployeeID'  => $employee_id,
            'StartDate'   => $start_date,
            'EndDate'     => $end_date,
            'LeaveType'   => $leave_type,
            'Reason'      => $reason,
            'Status'      => 'Pending',
            'CreatedDate' => date('Y-m-d H:i:s')
        ];

        if ($this->db->insert('employee_leaves', $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Leave request submitted successfully',
                'leave_id' => $this->db->insert_id()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error submitting request']);
        }
    }

    // 5. POST /admin/api/change_password
    public function change_password() {
        $employee_id = $this->input->post('employee_id');
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');

        if (empty($employee_id) || empty($current_password) || empty($new_password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Required parameters missing']);
            return;
        }

        $employee = $this->db->get_where('employee', [
            'EmployeeID' => $employee_id,
            'Status' => 'Active',
            'IsDeleted' => 0
        ])->row();

        if ($employee && password_verify($current_password, $employee->Password)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $this->db->where('EmployeeID', $employee_id)->update('employee', ['Password' => $hashed_password]);
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Incorrect current password']);
        }
    }

    // 6. POST /admin/api/update_leave_status
    public function update_leave_status() {
        $leave_id = $this->input->post('leave_id');
        $status = $this->input->post('status'); // Approved or Rejected
        $remarks = $this->input->post('remarks');

        if (empty($leave_id) || empty($status)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Required parameters missing']);
            return;
        }

        if (!in_array($status, ['Approved', 'Rejected'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid status value']);
            return;
        }

        $data = [
            'Status' => $status,
            'Remarks' => $remarks
        ];

        $this->db->where('LeaveID', $leave_id);
        if ($this->db->update('employee_leaves', $data)) {
            echo json_encode(['success' => true, 'message' => 'Leave status updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error updating status']);
        }
    }
}
