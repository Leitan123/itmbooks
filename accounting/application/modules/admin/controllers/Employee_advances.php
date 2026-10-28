<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Employee_advances extends MY_Controller
{
    var $page = "employee_advances";

    function __construct()
    {
        parent::__construct();
        $this->controller = get_class($this);
        $this->load->database();
    }

    public function index()
    {
        // Get all salary advances with employee details
        $records = $this->db
            ->select('ea.*, e.EmployeeName, e.EmployeeNo, e.Designation, b.BankName')
            ->from('employee_advances ea')
            ->join('employee e', 'e.EmployeeID = ea.EmployeeID', 'left')
            ->join('banks b', 'b.BankID = ea.BankID', 'left')
            ->where('ea.IsDeleted', 0)
            ->order_by('ea.AdvanceDate', 'DESC')
            ->order_by('ea.AdvanceID', 'DESC')
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
        $obj->AdvanceID = 0;
        $obj->EmployeeID = '';
        $obj->AdvanceDate = date('Y-m-d');
        $obj->Amount = '';
        $obj->Description = '';
        $obj->PaymentMethod = 'Cash';
        $obj->BankID = '';

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

    public function save()
    {
        $form = $this->input->post('form');
        if ($form) {
            $id = isset($form['AdvanceID']) ? intval($form['AdvanceID']) : 0;
            unset($form['AdvanceID']);

            $employeeId = intval($form['EmployeeID']);
            $advanceDate = $form['AdvanceDate'];
            $amount = floatval($form['Amount']);
            $description = trim($form['Description']);
            $paymentMethod = $form['PaymentMethod'];
            $bankId = isset($form['BankID']) && $paymentMethod == 'Bank' ? intval($form['BankID']) : null;

            // Basic validation
            if (!$employeeId || !$amount || !$advanceDate) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Please fill in all required fields.'
                ]);
                redirect('admin/employee_advances/create');
                return;
            }

            // Get Employee details
            $employee = $this->db->where('EmployeeID', $employeeId)->get('employee')->row();
            if (!$employee) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Employee not found.'
                ]);
                redirect('admin/employee_advances/create');
                return;
            }

            // Start transaction
            $this->db->trans_begin();

            // 1. Create a balanced Journal Entry for this advance
            // Debit Account: 82 (Salary Advances / Employee Loans)
            // Credit Account: 1 (Cash Account) or Bank Account associated with Selected Bank
            $debitAccountID = 82; 
            $creditAccountID = 1; // Default to Cash

            if ($paymentMethod == 'Bank' && $bankId) {
                $bank = $this->db->where('BankID', $bankId)->get('banks')->row();
                if ($bank && !empty($bank->AccountID)) {
                    $creditAccountID = $bank->AccountID;
                }
            }

            $referenceNo = 'ADV-' . date('Ymd', strtotime($advanceDate)) . '-' . rand(100, 999);
            $journalDesc = "Salary Advance - " . $employee->EmployeeName . ($description ? " (" . $description . ")" : "");

            $journalMaster = [
                'JournalDate' => $advanceDate,
                'ReferenceNo' => $referenceNo,
                'Description' => $journalDesc,
                'CreatedBy'   => $this->session->userdata('UserID') ?: 1,
                'CreatedDate' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('journal_entries', $journalMaster);
            $journalID = $this->db->insert_id();

            // Debit details
            $debitDetails = [
                'JournalID'    => $journalID,
                'AccountID'    => $debitAccountID,
                'DebitAmount'  => $amount,
                'CreditAmount' => 0.00
            ];
            $this->db->insert('journal_entry_details', $debitDetails);

            // Update Account Balance (Debit increases CurrentBalance)
            $this->db->set('CurrentBalance', 'CurrentBalance + ' . $amount, FALSE)
                ->where('AccountID', $debitAccountID)
                ->update('chart_of_accounts');

            // Credit details
            $creditDetails = [
                'JournalID'    => $journalID,
                'AccountID'    => $creditAccountID,
                'DebitAmount'  => 0.00,
                'CreditAmount' => $amount
            ];
            $this->db->insert('journal_entry_details', $creditDetails);

            // Update Account Balance (Credit decreases CurrentBalance)
            $this->db->set('CurrentBalance', 'CurrentBalance - ' . $amount, FALSE)
                ->where('AccountID', $creditAccountID)
                ->update('chart_of_accounts');

            // 2. Save Advance details
            $advanceData = [
                'EmployeeID'    => $employeeId,
                'AdvanceDate'   => $advanceDate,
                'Amount'        => $amount,
                'Description'   => $description,
                'PaymentMethod' => $paymentMethod,
                'BankID'        => $bankId,
                'JournalID'     => $journalID,
                'CreatedDate'   => date('Y-m-d H:i:s')
            ];
            $this->db->insert('employee_advances', $advanceData);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> Failed to save advance and journal entry.'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    'alert' => 'success',
                    'text'  => '<strong>Salary Advance Saved Successfully.</strong> Reference No: ' . $referenceNo
                ]);
            }
        }
        redirect('admin/employee_advances');
    }

    public function delete($_id = 0)
    {
        if ($_id) {
            $advance = $this->db->where('AdvanceID', $_id)->get('employee_advances')->row();
            if ($advance && !$advance->IsDeleted) {
                // If it is already fully or partially recovered, do not allow deletion
                if ($advance->AmountRecovered > 0) {
                    $this->session->set_flashdata('notification', [
                        'alert' => 'danger',
                        'text'  => '<strong>Error:</strong> This advance has already been partially or fully recovered. You cannot delete it.'
                    ]);
                    redirect('admin/employee_advances');
                    return;
                }

                $this->db->trans_begin();

                // 1. Delete and reverse linked Journal Entry
                if ($advance->JournalID) {
                    $details = $this->db->where('JournalID', $advance->JournalID)->get('journal_entry_details')->result();
                    foreach ($details as $row) {
                        if ($row->DebitAmount > 0) {
                            // Reverse Debit (Debit originally increased balance, so now we decrease it)
                            $this->db->set('CurrentBalance', 'CurrentBalance - ' . $row->DebitAmount, FALSE)
                                ->where('AccountID', $row->AccountID)
                                ->update('chart_of_accounts');
                        }
                        if ($row->CreditAmount > 0) {
                            // Reverse Credit (Credit originally decreased balance, so now we increase it)
                            $this->db->set('CurrentBalance', 'CurrentBalance + ' . $row->CreditAmount, FALSE)
                                ->where('AccountID', $row->AccountID)
                                ->update('chart_of_accounts');
                        }
                    }
                    // Delete details and master
                    $this->db->where('JournalID', $advance->JournalID)->delete('journal_entry_details');
                    $this->db->where('JournalID', $advance->JournalID)->delete('journal_entries');
                }

                // 2. Mark Advance as deleted
                $this->db->where('AdvanceID', $_id)->update('employee_advances', ['IsDeleted' => 1]);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'danger',
                        'text'  => '<strong>Error:</strong> Failed to delete advance and reverse journal entry.'
                    ]);
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'success',
                        'text'  => '<strong>Salary Advance Deleted & Accounting Entries Reversed.</strong>'
                    ]);
                }
            }
        }
        redirect('admin/employee_advances');
    }

    public function settle($id = 0)
    {
        if ($id) {
            $advance = $this->db
                ->select('ea.*, e.EmployeeName, e.EmployeeNo, e.Designation')
                ->from('employee_advances ea')
                ->join('employee e', 'e.EmployeeID = ea.EmployeeID', 'left')
                ->where('ea.AdvanceID', $id)
                ->where('ea.IsDeleted', 0)
                ->get()
                ->row();

            if (!$advance || $advance->Amount <= $advance->AmountRecovered) {
                $this->session->set_flashdata('notification', [
                    'alert' => 'danger',
                    'text'  => '<strong>Error:</strong> This advance is already fully recovered.'
                ]);
                redirect('admin/employee_advances');
                return;
            }

            // Get active banks
            $banks = $this->db
                ->where('IsDeleted', 0)
                ->order_by('BankName', 'ASC')
                ->get('banks')
                ->result();

            $d = [
                'page'    => "$this->page/settle",
                'advance' => $advance,
                'banks'   => $banks
            ];
            $this->view($d);
        } else {
            redirect('admin/employee_advances');
        }
    }

    public function save_settlement()
    {
        $advanceId = intval($this->input->post('AdvanceID'));
        $settleAmount = floatval($this->input->post('SettleAmount'));
        $settleDate = $this->input->post('SettleDate');
        $paymentMethod = $this->input->post('PaymentMethod');
        $bankId = $paymentMethod == 'Bank' ? intval($this->input->post('BankID')) : null;
        $description = trim($this->input->post('Description'));

        if (!$advanceId || !$settleAmount || !$settleDate) {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text'  => '<strong>Error:</strong> Please fill in all required fields.'
            ]);
            redirect('admin/employee_advances');
            return;
        }

        $advance = $this->db->where('AdvanceID', $advanceId)->get('employee_advances')->row();
        if (!$advance) {
            redirect('admin/employee_advances');
            return;
        }

        $remaining = $advance->Amount - $advance->AmountRecovered;
        if ($settleAmount > $remaining) {
            $settleAmount = $remaining; // Cap at outstanding
        }

        $employee = $this->db->where('EmployeeID', $advance->EmployeeID)->get('employee')->row();

        $this->db->trans_begin();

        // 1. Create a balanced Journal Entry for this recovery
        // Debit Account: Cash (1) or Bank Account associated with selected Bank
        // Credit Account: 82 (Salary Advances / Employee Loans)
        $debitAccountID = 1; // Cash
        $creditAccountID = 82; // Salary Advances

        if ($paymentMethod == 'Bank' && $bankId) {
            $bank = $this->db->where('BankID', $bankId)->get('banks')->row();
            if ($bank && !empty($bank->AccountID)) {
                $debitAccountID = $bank->AccountID;
            }
        }

        $referenceNo = 'REC-' . date('Ymd', strtotime($settleDate)) . '-' . rand(100, 999);
        $journalDesc = "Salary Advance Recovery (Manual) - " . $employee->EmployeeName . ($description ? " (" . $description . ")" : "");

        $journalMaster = [
            'JournalDate' => $settleDate,
            'ReferenceNo' => $referenceNo,
            'Description' => $journalDesc,
            'CreatedBy'   => $this->session->userdata('UserID') ?: 1,
            'CreatedDate' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('journal_entries', $journalMaster);
        $journalID = $this->db->insert_id();

        // Debit details
        $debitDetails = [
            'JournalID'    => $journalID,
            'AccountID'    => $debitAccountID,
            'DebitAmount'  => $settleAmount,
            'CreditAmount' => 0.00
        ];
        $this->db->insert('journal_entry_details', $debitDetails);

        $this->db->set('CurrentBalance', 'CurrentBalance + ' . $settleAmount, FALSE)
            ->where('AccountID', $debitAccountID)
            ->update('chart_of_accounts');

        // Credit details
        $creditDetails = [
            'JournalID'    => $journalID,
            'AccountID'    => $creditAccountID,
            'DebitAmount'  => 0.00,
            'CreditAmount' => $settleAmount
        ];
        $this->db->insert('journal_entry_details', $creditDetails);

        $this->db->set('CurrentBalance', 'CurrentBalance - ' . $settleAmount, FALSE)
            ->where('AccountID', $creditAccountID)
            ->update('chart_of_accounts');

        // 2. Update Advance record
        $newRecovered = $advance->AmountRecovered + $settleAmount;
        $status = $newRecovered == $advance->Amount ? 'Fully Recovered' : 'Partially Recovered';
        
        $this->db->where('AdvanceID', $advanceId)->update('employee_advances', [
            'AmountRecovered' => $newRecovered,
            'Status'          => $status
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text'  => '<strong>Error:</strong> Failed to save manual settlement.'
            ]);
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text'  => '<strong>Advance Settlement Saved Successfully.</strong> Ref: ' . $referenceNo
            ]);
        }

        redirect('admin/employee_advances');
    }
}
?>
