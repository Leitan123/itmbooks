<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Payroll extends MY_Controller
{
    var $page = "payroll";

    function __construct()
    {
        parent::__construct();
        $this->controller = get_class($this);
        $this->load->database();
    }

    public function index()
    {
        // Get all processed pay slips with employee names
        $records = $this->db
            ->select('ss.*, e.EmployeeName, e.EmployeeNo, e.Designation, b.BankName')
            ->from('salary_slips ss')
            ->join('employee e', 'e.EmployeeID = ss.EmployeeID', 'left')
            ->join('banks b', 'b.BankID = ss.BankID', 'left')
            ->where('ss.IsDeleted', 0)
            ->order_by('ss.PayrollMonth', 'DESC')
            ->order_by('ss.SlipID', 'DESC')
            ->get()
            ->result();

        $d = [
            'page'    => "$this->page/index",
            'records' => $records
        ];
        $this->view($d);
    }

    public function create()
    {
        $obj = new stdClass();
        $obj->SlipID = 0;
        $obj->EmployeeID = '';
        $obj->PayrollMonth = date('Y-m');
        $obj->BasicSalary = 0.00;
        $obj->Allowance = 0.00;
        $obj->OTHours = 0.00;
        $obj->OTRate = 0.00;
        $obj->OTAmount = 0.00;
        $obj->GrossSalary = 0.00;
        $obj->EmployeeEPF = 0.00;
        $obj->AdvanceDeduction = 0.00;
        $obj->NetSalary = 0.00;
        $obj->CompanyEPF = 0.00;
        $obj->CompanyETF = 0.00;
        $obj->PaymentMethod = 'Cash';
        $obj->BankID = '';
        $obj->PaymentDate = date('Y-m-d');

        // Get active employees
        $employees = $this->db
            ->where('IsDeleted', 0)
            ->where('Status', 'Active')
            ->order_by('EmployeeName', 'ASC')
            ->get('employee')
            ->result();

        // Get active banks
        $banks = $this->db
            ->where('IsDeleted', 0)
            ->order_by('BankName', 'ASC')
            ->get('banks')
            ->result();

        $d = [
            'page'      => "$this->page/form",
            'obj'       => $obj,
            'employees' => $employees,
            'banks'     => $banks
        ];
        $this->view($d);
    }

    public function get_employee_payroll_data()
    {
        $employeeId = intval($this->input->post('employeeId'));
        if (!$employeeId) {
            echo json_encode(['error' => 'Invalid Employee ID']);
            return;
        }

        $employee = $this->db->where('EmployeeID', $employeeId)->get('employee')->row();
        if (!$employee) {
            echo json_encode(['error' => 'Employee not found']);
            return;
        }

        // Calculate outstanding salary advances
        $advances = $this->db
            ->select('SUM(Amount - AmountRecovered) as outstanding')
            ->where('EmployeeID', $employeeId)
            ->where('IsDeleted', 0)
            ->where('Status !=', 'Fully Recovered')
            ->get('employee_advances')
            ->row();

        $outstandingBalance = $advances ? floatval($advances->outstanding) : 0.00;

        echo json_encode([
            'BasicSalary' => floatval($employee->BasicSalary),
            'Allowance'   => floatval($employee->Allowance),
            'OTPerHour'   => floatval($employee->OTPerHour),
            'OutstandingAdvances' => $outstandingBalance
        ]);
    }

    public function save()
    {
        $form = $this->input->post('form');
        if ($form) {
            $employeeId = intval($form['EmployeeID']);
            $payrollMonth = trim($form['PayrollMonth']);
            $paymentDate = $form['PaymentDate'];
            $paymentMethod = $form['PaymentMethod'];
            $bankId = isset($form['BankID']) && $paymentMethod == 'Bank' ? intval($form['BankID']) : null;

            $customNames = $this->input->post('custom_allowance_names');
            $customAmounts = $this->input->post('custom_allowance_amounts');
            
            $customAllowances = [];
            
            if (!empty($customNames) && !empty($customAmounts)) {
                for ($i = 0; $i < count($customNames); $i++) {
                    $name = trim($customNames[$i]);
                    $amount = floatval($customAmounts[$i]);
                    if ($name !== '' && $amount > 0) {
                        $customAllowances[] = [
                            'name' => $name,
                            'amount' => $amount
                        ];
                    }
                }
            }
            
            $allowancesJSON = !empty($customAllowances) ? json_encode($customAllowances) : null;

            // Extract numeric fields
            $basicSalary = floatval($form['BasicSalary']);
            $allowance = floatval($form['Allowance']);
            $otHours = floatval($form['OTHours']);
            $otRate = floatval($form['OTRate']);
            $otAmount = floatval($form['OTAmount']);
            $grossSalary = floatval($form['GrossSalary']);
            $employeeEPF = floatval($form['EmployeeEPF']);
            $advanceDeduction = floatval($form['AdvanceDeduction']);
            $netSalary = floatval($form['NetSalary']);
            $companyEPF = floatval($form['CompanyEPF']);
            $companyETF = floatval($form['CompanyETF']);

            // Validations
            if (!$employeeId || empty($payrollMonth) || !$paymentDate) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Please fill in all required fields.'
                ]);
                redirect('admin/payroll/create');
                return;
            }

            // Check if payroll already exists for this employee in this month
            $exists = $this->db
                ->where('EmployeeID', $employeeId)
                ->where('PayrollMonth', $payrollMonth)
                ->where('IsDeleted', 0)
                ->get('salary_slips')
                ->row();

            if ($exists) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> A pay slip has already been generated for this employee for ' . $payrollMonth
                ]);
                redirect('admin/payroll/create');
                return;
            }

            // Verify employee
            $employee = $this->db->where('EmployeeID', $employeeId)->get('employee')->row();
            if (!$employee) {
                redirect('admin/payroll');
                return;
            }

            $this->db->trans_begin();

            // 1. Recover/deduct from advances
            if ($advanceDeduction > 0) {
                // Get all pending advances for this employee
                $advances = $this->db
                    ->where('EmployeeID', $employeeId)
                    ->where('IsDeleted', 0)
                    ->where('Status !=', 'Fully Recovered')
                    ->order_by('AdvanceDate', 'ASC')
                    ->get('employee_advances')
                    ->result();

                $tempDeduction = $advanceDeduction;
                foreach ($advances as $adv) {
                    if ($tempDeduction <= 0) break;

                    $remaining = $adv->Amount - $adv->AmountRecovered;
                    if ($tempDeduction >= $remaining) {
                        $this->db->where('AdvanceID', $adv->AdvanceID)->update('employee_advances', [
                            'AmountRecovered' => $adv->Amount,
                            'Status'          => 'Fully Recovered'
                        ]);
                        $tempDeduction -= $remaining;
                    } else {
                        $this->db->where('AdvanceID', $adv->AdvanceID)->update('employee_advances', [
                            'AmountRecovered' => $adv->AmountRecovered + $tempDeduction,
                            'Status'          => 'Partially Recovered'
                        ]);
                        $tempDeduction = 0;
                    }
                }
            }

            // 2. Generate accounting journal entry
            // Debits:
            // - AccountID 10 (Salary Expense) -> GrossSalary
            // - AccountID 34 (EPF Contribution Expense) -> CompanyEPF
            // - AccountID 35 (ETF Contribution Expense) -> CompanyETF
            // Credits:
            // - Cash (1) or Bank Account ID -> NetSalary
            // - AccountID 51 (EPF Payable) -> EmployeeEPF + CompanyEPF
            // - AccountID 52 (ETF Payable) -> CompanyETF
            // - AccountID 82 (Salary Advances / Employee Loans) -> AdvanceDeduction (if > 0)

            $referenceNo = 'SAL-' . str_replace('-', '', $payrollMonth) . '-' . $employee->EmployeeID;
            $journalDesc = "Salary Slip - " . $employee->EmployeeName . " (" . $payrollMonth . ")";

            $journalMaster = [
                'JournalDate' => $paymentDate,
                'ReferenceNo' => $referenceNo,
                'Description' => $journalDesc,
                'CreatedBy'   => $this->session->userdata('UserID') ?: 1,
                'CreatedDate' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('journal_entries', $journalMaster);
            $journalID = $this->db->insert_id();

            // Set up credit account for net salary payment
            $creditPaymentAccountID = 1; // Default Cash
            if ($paymentMethod == 'Bank' && $bankId) {
                $bank = $this->db->where('BankID', $bankId)->get('banks')->row();
                if ($bank && !empty($bank->AccountID)) {
                    $creditPaymentAccountID = $bank->AccountID;
                }
            }

            // Define postings to make
            $postings = [
                // DEBITS
                ['AccountID' => 10, 'Debit' => $grossSalary, 'Credit' => 0.00],
                ['AccountID' => 34, 'Debit' => $companyEPF, 'Credit' => 0.00],
                ['AccountID' => 35, 'Debit' => $companyETF, 'Credit' => 0.00],
                // CREDITS
                ['AccountID' => $creditPaymentAccountID, 'Debit' => 0.00, 'Credit' => $netSalary],
                ['AccountID' => 51, 'Debit' => 0.00, 'Credit' => $employeeEPF + $companyEPF],
                ['AccountID' => 52, 'Debit' => 0.00, 'Credit' => $companyETF]
            ];

            if ($advanceDeduction > 0) {
                $postings[] = ['AccountID' => 82, 'Debit' => 0.00, 'Credit' => $advanceDeduction];
            }

            foreach ($postings as $post) {
                if ($post['Debit'] == 0 && $post['Credit'] == 0) continue;

                $this->db->insert('journal_entry_details', [
                    'JournalID'    => $journalID,
                    'AccountID'    => $post['AccountID'],
                    'DebitAmount'  => $post['Debit'],
                    'CreditAmount' => $post['Credit']
                ]);

                // Update balances
                if ($post['Debit'] > 0) {
                    $this->db->set('CurrentBalance', 'CurrentBalance + ' . $post['Debit'], FALSE)
                        ->where('AccountID', $post['AccountID'])
                        ->update('chart_of_accounts');
                }
                if ($post['Credit'] > 0) {
                    $this->db->set('CurrentBalance', 'CurrentBalance - ' . $post['Credit'], FALSE)
                        ->where('AccountID', $post['AccountID'])
                        ->update('chart_of_accounts');
                }
            }

            // 3. Save Pay Slip
            $slipData = [
                'EmployeeID'       => $employeeId,
                'PayrollMonth'     => $payrollMonth,
                'BasicSalary'      => $basicSalary,
                'Allowance'        => $allowance,
                'AllowancesJSON'   => $allowancesJSON,
                'OTHours'          => $otHours,
                'OTRate'           => $otRate,
                'OTAmount'         => $otAmount,
                'GrossSalary'      => $grossSalary,
                'EmployeeEPF'      => $employeeEPF,
                'AdvanceDeduction' => $advanceDeduction,
                'NetSalary'        => $netSalary,
                'CompanyEPF'       => $companyEPF,
                'CompanyETF'       => $companyETF,
                'PaymentMethod'    => $paymentMethod,
                'BankID'           => $bankId,
                'PaymentDate'      => $paymentDate,
                'JournalID'        => $journalID,
                'CreatedDate'      => date('Y-m-d H:i:s')
            ];
            $this->db->insert('salary_slips', $slipData);

            // Also insert into expenses table so it shows on Dashboard and Profit & Loss statement
            $this->db->insert('expenses', [
                'ExpenseDate'   => $paymentDate,
                'AccountID'     => 10, // Salaries & Wages
                'Description'   => 'Salary payout for ' . $payrollMonth,
                'Payee'         => $employee->EmployeeName,
                'Amount'        => $grossSalary,
                'PaymentMethod' => ($paymentMethod == 'Bank') ? 'Bank' : 'Cash',
                'BankID'        => !empty($bankId) ? $bankId : NULL,
                'Remarks'       => '',
                'CreatedDate'   => date('Y-m-d H:i:s'),
                'IsDeleted'     => 0,
                'EmployeeID'    => $employeeId,
                'ExpenseNo'     => ''
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Failed to save pay slips and ledger journal entries.'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    'alert' => 'success',
                    'text'  => '<strong>Payroll slip created & paid successfully.</strong> Reference No: ' . $referenceNo
                ]);
            }
        }
        redirect('admin/payroll');
    }

    public function print_slip($id = 0)
    {
        if ($id) {
            $slip = $this->db
                ->select('ss.*, e.EmployeeName, e.EmployeeNo, e.Designation, e.Department, b.BankName')
                ->from('salary_slips ss')
                ->join('employee e', 'e.EmployeeID = ss.EmployeeID', 'left')
                ->join('banks b', 'b.BankID = ss.BankID', 'left')
                ->where('ss.SlipID', $id)
                ->where('ss.IsDeleted', 0)
                ->get()
                ->row();

            if (!$slip) {
                show_404();
            }

            // Get outstanding advance balance for this employee
            $advances = $this->db
                ->select('SUM(Amount - AmountRecovered) as outstanding')
                ->where('EmployeeID', $slip->EmployeeID)
                ->where('IsDeleted', 0)
                ->get('employee_advances')
                ->row();
            $outstandingBalance = $advances ? floatval($advances->outstanding) : 0.00;

            $d = [
                'slip' => $slip,
                'outstandingBalance' => $outstandingBalance
            ];
            $this->load->view('pages/payroll/print', $d);
        } else {
            redirect('admin/payroll');
        }
    }

    public function delete($_id = 0)
    {
        if ($_id) {
            $slip = $this->db->where('SlipID', $_id)->get('salary_slips')->row();
            if ($slip && !$slip->IsDeleted) {
                $this->db->trans_begin();

                // 1. Reverse Advance deductions
                if ($slip->AdvanceDeduction > 0) {
                    $advances = $this->db
                        ->where('EmployeeID', $slip->EmployeeID)
                        ->where('IsDeleted', 0)
                        ->where('AmountRecovered >', 0)
                        ->order_by('AdvanceDate', 'DESC')
                        ->get('employee_advances')
                        ->result();

                    $tempReverse = $slip->AdvanceDeduction;
                    foreach ($advances as $adv) {
                        if ($tempReverse <= 0) break;

                        if ($adv->AmountRecovered >= $tempReverse) {
                            $newRecovered = $adv->AmountRecovered - $tempReverse;
                            $status = $newRecovered == 0 ? 'Pending' : 'Partially Recovered';
                            $this->db->where('AdvanceID', $adv->AdvanceID)->update('employee_advances', [
                                'AmountRecovered' => $newRecovered,
                                'Status'          => $status
                            ]);
                            $tempReverse = 0;
                        } else {
                            $tempReverse -= $adv->AmountRecovered;
                            $this->db->where('AdvanceID', $adv->AdvanceID)->update('employee_advances', [
                                'AmountRecovered' => 0.00,
                                'Status'          => 'Pending'
                            ]);
                        }
                    }
                }

                // 2. Reverse accounting entries
                if ($slip->JournalID) {
                    $details = $this->db->where('JournalID', $slip->JournalID)->get('journal_entry_details')->result();
                    foreach ($details as $row) {
                        if ($row->DebitAmount > 0) {
                            $this->db->set('CurrentBalance', 'CurrentBalance - ' . $row->DebitAmount, FALSE)
                                ->where('AccountID', $row->AccountID)
                                ->update('chart_of_accounts');
                        }
                        if ($row->CreditAmount > 0) {
                            $this->db->set('CurrentBalance', 'CurrentBalance + ' . $row->CreditAmount, FALSE)
                                ->where('AccountID', $row->AccountID)
                                ->update('chart_of_accounts');
                        }
                    }
                    $this->db->where('JournalID', $slip->JournalID)->delete('journal_entry_details');
                    $this->db->where('JournalID', $slip->JournalID)->delete('journal_entries');
                }

                // 3. Mark slip as deleted
                $this->db->where('SlipID', $_id)->update('salary_slips', ['IsDeleted' => 1]);

                // Also mark corresponding expense as deleted
                $this->db->where('EmployeeID', $slip->EmployeeID)
                         ->where('ExpenseDate', $slip->PaymentDate)
                         ->where('Amount', $slip->GrossSalary)
                         ->update('expenses', ['IsDeleted' => 1]);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'danger',
                        'text'  => '<strong>Error:</strong> Failed to delete pay slip and reverse transactions.'
                    ]);
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'success',
                        'text'  => '<strong>Pay Slip Deleted & Ledger Entries Reversed.</strong>'
                    ]);
                }
            }
        }
        redirect('admin/payroll');
    }
    public function epf_form()
    {
        $month = $this->input->get('month') ?: date('Y-m');

        // Get company settings
        $company = $this->db->get('company_settings')->row();

        

        
        $records = $this->db
            ->select('ss.*, e.EmployeeName, e.EmployeeNo, e.NIC')
            ->from('salary_slips ss')
            ->join('employee e', 'e.EmployeeID = ss.EmployeeID', 'left')
            ->where('ss.IsDeleted', 0)
            ->where('ss.PayrollMonth', $month)
            ->where('(ss.EmployeeEPF > 0 OR ss.CompanyEPF > 0)')
            ->order_by('e.EmployeeNo', 'ASC')
            ->get()
            ->result();

        $d = [
            'page'    => "$this->page/epf_form",
            'records' => $records,
            'month'   => $month,
            'company' => $company
        ];
        $this->view($d);
    }

    public function etf_form()
    {
        $month = $this->input->get('month') ?: date('Y-m');

        // Get company settings
        $company = $this->db->get('company')->row();

        // Get processed pay slips for the month
        $records = $this->db
            ->select('ss.*, e.EmployeeName, e.EmployeeNo, e.NIC')
            ->from('salary_slips ss')
            ->join('employee e', 'e.EmployeeID = ss.EmployeeID', 'left')
            ->where('ss.IsDeleted', 0)
            ->where('ss.PayrollMonth', $month)
            ->where('ss.CompanyETF > 0')
            ->order_by('e.EmployeeNo', 'ASC')
            ->get()
            ->result();

        $d = [
            'page'    => "$this->page/etf_form",
            'records' => $records,
            'month'   => $month,
            'company' => $company
        ];
        $this->view($d);
    }
}
?>
