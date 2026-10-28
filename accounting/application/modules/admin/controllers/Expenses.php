<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Expenses extends MY_Controller
{
    var $page = "expenses";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Expenses_model','model');
        $this->controller = get_class($this);
    }

    public function create()
    {
        // Check if form was submitted
        if($this->input->post())
        {
            $this->form_validation->set_rules('ExpenseDate', 'Date', 'required');
            $this->form_validation->set_rules('AccountID', 'Expense Account', 'required');
            $this->form_validation->set_rules('Amount', 'Amount', 'required');

            if($this->form_validation->run())
            {
                $data = [
                    'ExpenseDate'   => $this->input->post('ExpenseDate'),
                    'AccountID'     => $this->input->post('AccountID'),
                    'EmployeeID'    => $this->input->post('EmployeeID') ?: null,
                    'Description'   => $this->input->post('Description'),
                    'Payee'         => $this->input->post('Payee'),
                    'Amount'        => $this->input->post('Amount'),
                    'PaymentMethod' => $this->input->post('PaymentMethod'),
                    'BankID'        => $this->input->post('BankID') ?: null,
                    'Remarks'       => $this->input->post('Remarks'),
                    'ChequeNo'      => $this->input->post('ChequeNo'),
                    'ChequeDate'    => $this->input->post('ChequeDate'),
                    'CreatedDate'   => date('Y-m-d H:i:s')
                ];

                $this->db->insert('expenses', $data);
                $expense_id = $this->db->insert_id();

                // Create journal entry for this expense
                $this->create_journal_entry($expense_id, $data);

                // Save customer associations
                $customerIds = $this->input->post('CustomerIds');
                if(!empty($customerIds) && is_array($customerIds))
                {
                    foreach($customerIds as $item)
                    {
                        if(!empty($item))
                        {
                            $parts = explode(':', $item, 2);
                            $customerId = $parts[0];
                            $domainName = isset($parts[1]) ? $parts[1] : '';

                            $this->db->insert('expense_customers', [
                                'ExpenseID' => $expense_id,
                                'CustomerId' => $customerId,
                                'DomainName' => $domainName
                            ]);
                        }
                    }
                }

                $this->session->set_flashdata('notification', [
                    'alert' => 'success',
                    'text' => '<strong>Expense Added Successfully</strong>'
                ]);

                redirect('admin/expenses');
            }
        }

        // Display form
        $this->obj = $this->model->get_all_fields();

        $customers = $this->db->where('IsDeleted',0)->order_by('CompanyName','ASC')->get('customer')->result();
        $additional_domains = $this->db->get('customer_domains')->result();
        $domains_by_customer = [];
        foreach ($additional_domains as $d_row) {
            $domains_by_customer[$d_row->CustomerID][] = $d_row->DomainName;
        }
        foreach ($customers as $c) {
            $c->AdditionalDomains = isset($domains_by_customer[$c->CustomerId]) ? $domains_by_customer[$c->CustomerId] : [];
        }

        $d = [
            'page'      => "$this->page/form",
            'obj'       => $this->obj,
            'accounts'  => $this->model->get_expense_accounts(),
            'banks'     => $this->db->where('IsDeleted',0)->order_by('BankName','ASC')->get('banks')->result(),
            'employees' => $this->db->where('IsDeleted',0)->order_by('EmployeeName','ASC')->get('employee')->result(),
            'customers' => $customers
        ];

        $this->view($d);
    }

    public function edit($_id = 0)
    {
        if(!$_id)
        {
            redirect('admin/expenses');
        }

        // Check if form was submitted
        if($this->input->post())
        {
            $this->form_validation->set_rules('ExpenseDate', 'Date', 'required');
            $this->form_validation->set_rules('AccountID', 'Expense Account', 'required');
            $this->form_validation->set_rules('Amount', 'Amount', 'required');

            if($this->form_validation->run())
            {
                $data = [
                    'ExpenseDate'   => $this->input->post('ExpenseDate'),
                    'AccountID'     => $this->input->post('AccountID'),
                    'EmployeeID'    => $this->input->post('EmployeeID') ?: null,
                    'Description'   => $this->input->post('Description'),
                    'Payee'         => $this->input->post('Payee'),
                    'Amount'        => $this->input->post('Amount'),
                    'PaymentMethod' => $this->input->post('PaymentMethod'),
                    'BankID'        => $this->input->post('BankID') ?: null,
                    'Remarks'       => $this->input->post('Remarks'),
                    'ChequeNo'      => $this->input->post('ChequeNo'),
                    'ChequeDate'    => $this->input->post('ChequeDate')
                ];

                $this->db->where('ExpenseID', $_id)->update('expenses', $data);
                
                // Update journal entries for this expense
                $this->update_journal_entry($_id, $data);
                
                // Delete existing customer associations
                $this->db->where('ExpenseID', $_id)->delete('expense_customers');
                
                // Save new customer associations
                $customerIds = $this->input->post('CustomerIds');
                if(!empty($customerIds) && is_array($customerIds))
                {
                    foreach($customerIds as $item)
                    {
                        if(!empty($item))
                        {
                            $parts = explode(':', $item, 2);
                            $customerId = $parts[0];
                            $domainName = isset($parts[1]) ? $parts[1] : '';

                            $this->db->insert('expense_customers', [
                                'ExpenseID' => $_id,
                                'CustomerId' => $customerId,
                                'DomainName' => $domainName
                            ]);
                        }
                    }
                }

                $this->session->set_flashdata('notification', [
                    'alert' => 'success',
                    'text' => '<strong>Expense Updated Successfully</strong>'
                ]);

                redirect('admin/expenses');
            }
        }

        // Get expense data
        $obj = $this->model->get($_id);
        
        if(!$obj)
        {
            redirect('admin/expenses');
        }

        // Get selected customer-domain associations for this expense
        $selectedCustomerDomains = $this->db
            ->select('ec.CustomerId, COALESCE(NULLIF(ec.DomainName, ""), c.DomainName, "") AS DomainName', false)
            ->from('expense_customers ec')
            ->join('customer c', 'c.CustomerId = ec.CustomerId', 'left')
            ->where('ec.ExpenseID', $_id)
            ->get()
            ->result();

        $selectedValues = array_map(function($item) {
            return $item->CustomerId . ':' . $item->DomainName;
        }, $selectedCustomerDomains);

        $obj->CustomerIds = $selectedValues;

        $customers = $this->db->where('IsDeleted',0)->order_by('CompanyName','ASC')->get('customer')->result();
        $additional_domains = $this->db->get('customer_domains')->result();
        $domains_by_customer = [];
        foreach ($additional_domains as $d_row) {
            $domains_by_customer[$d_row->CustomerID][] = $d_row->DomainName;
        }
        foreach ($customers as $c) {
            $c->AdditionalDomains = isset($domains_by_customer[$c->CustomerId]) ? $domains_by_customer[$c->CustomerId] : [];
        }

        $d = [
            'page'      => "$this->page/form",
            'obj'       => $obj,
            'accounts'  => $this->model->get_expense_accounts(),
            'banks'     => $this->db->where('IsDeleted',0)->order_by('BankName','ASC')->get('banks')->result(),
            'employees' => $this->db->where('IsDeleted',0)->order_by('EmployeeName','ASC')->get('employee')->result(),
            'customers' => $customers
        ];

        $this->view($d);
    }

    public function index()
    {
        // Get current month's first and last date
        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');
        
        // Get total expenses for current month
        $monthTotal = $this->db
            ->select('IFNULL(SUM(Amount),0) as total')
            ->where('IsDeleted', 0)
            ->where('ExpenseDate >=', $firstDayOfMonth)
            ->where('ExpenseDate <=', $lastDayOfMonth)
            ->get('expenses')
            ->row()
            ->total;
        
        // Get total expenses all time
        $totalAmount = $this->db
            ->select('IFNULL(SUM(Amount),0) as total')
            ->where('IsDeleted', 0)
            ->get('expenses')
            ->row()
            ->total;
        
        // Get records sorted by date DESC (using direct query instead of model)
        $records = $this->db
            ->select("
                expenses.*,
                chart_of_accounts.AccountCode,
                chart_of_accounts.AccountName,  
                employee.EmployeeName,
                banks.BankName,
                banks.AccountNumber,
                (
                    SELECT GROUP_CONCAT(
                        CONCAT(
                            c.CompanyName,
                            '<br><small>',
                            COALESCE(NULLIF(ec.DomainName, ''), c.DomainName, '-'),
                            '</small>'
                        )
                        SEPARATOR '<br>'
                    )
                    FROM expense_customers ec
                    INNER JOIN customer c ON c.CustomerId = ec.CustomerId
                    WHERE ec.ExpenseID = expenses.ExpenseID
                ) AS Domains
            ")
            ->from('expenses')
            ->join('chart_of_accounts', 'chart_of_accounts.AccountID = expenses.AccountID', 'left')
            ->join('banks', 'banks.BankID = expenses.BankID', 'left')
            ->join('employee', 'employee.EmployeeID = expenses.EmployeeID', 'left')
            ->where('expenses.IsDeleted', 0)
            ->order_by('expenses.ExpenseDate', 'DESC')
            ->order_by('expenses.ExpenseID', 'DESC')
            ->get()
            ->result();
        
        $d = [
            'page'        => "$this->page/index",
            'records'     => $records,
            'totalAmount' => $totalAmount,
            'monthTotal'  => $monthTotal
        ];

        $this->view($d);
    }

    public function delete($_id = 0)
    {
        if($_id)
        {
            // Delete customer associations
            $this->db->where('ExpenseID', $_id)->delete('expense_customers');
            
            // Delete journal entries for this expense
            $this->delete_journal_entry($_id);
            
            // Soft delete the expense
            $this->db->where('ExpenseID', $_id)->update('expenses', ['IsDeleted' => 1]);
            
            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text' => '<strong>Expense Deleted Successfully</strong>'
            ]);
        }
        
        redirect('admin/expenses');
    }

    // ============ JOURNAL ENTRY METHODS (Based on your table structure) ============
    
    private function create_journal_entry($expense_id, $data)
    {
        // Create journal entry header
        $journalData = [
            'JournalDate' => $data['ExpenseDate'],
            'ReferenceNo' => 'EXP-' . $expense_id,
            'Description' => 'Expense: ' . ($data['Description'] ?: 'Expense Entry'),
            'CreatedBy' => $this->session->userdata('UserID') ?: 1,
            'CreatedDate' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('journal_entries', $journalData);
        $journalId = $this->db->insert_id();
        
        // 1. DEBIT Entry - Expense Account
        $debitData = [
            'JournalID' => $journalId,
            'AccountID' => $data['AccountID'],
            'DebitAmount' => $data['Amount'],
            'CreditAmount' => 0,
            'Description' => $data['Description']
        ];
        $this->db->insert('journal_entry_details', $debitData);
        
        // 2. CREDIT Entry - Cash or Bank Account
        $creditAccountID = null;
        
        if($data['PaymentMethod'] == 'Cash') {
            // Find cash account
            $cashAccount = $this->db
                ->select('AccountID')
                ->where('AccountName LIKE', '%Cash%')
                ->or_where('AccountName LIKE', '%Petty Cash%')
                ->get('chart_of_accounts')
                ->row();
            if($cashAccount) {
                $creditAccountID = $cashAccount->AccountID;
            } else {
                // Create or use default cash account ID 1
                $creditAccountID = 1;
            }
        } 
        elseif(($data['PaymentMethod'] == 'Bank' || $data['PaymentMethod'] == 'Cheque') && !empty($data['BankID'])) {
            $bank_row = $this->db->select('AccountID')->where('BankID', $data['BankID'])->get('banks')->row();
            $creditAccountID = $bank_row ? $bank_row->AccountID : 2;
        }
        
        if($creditAccountID) {
            $creditData = [
                'JournalID' => $journalId,
                'AccountID' => $creditAccountID,
                'DebitAmount' => 0,
                'CreditAmount' => $data['Amount'],
                'Description' => 'Payment via ' . $data['PaymentMethod'] . ($data['ChequeNo'] ? ' - Cheque No: ' . $data['ChequeNo'] : '')
            ];
            $this->db->insert('journal_entry_details', $creditData);
        } else {
            // If no credit account found, log error or use a default
            log_message('error', 'No credit account found for expense ID: ' . $expense_id);
        }
    }
    
    private function update_journal_entry($expense_id, $data)
    {
        // Find existing journal entry for this expense
        $journal = $this->db
            ->select('JournalID')
            ->where('ReferenceNo', 'EXP-' . $expense_id)
            ->get('journal_entries')
            ->row();
        
        if($journal) {
            // Delete old journal details
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entry_details');
            // Update journal header
            $this->db->where('JournalID', $journal->JournalID)->update('journal_entries', [
                'JournalDate' => $data['ExpenseDate'],
                'Description' => 'Expense: ' . ($data['Description'] ?: 'Expense Entry'),
                'CreatedDate' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Create new journal entry with updated data
        $this->create_journal_entry($expense_id, $data);
    }
    
    private function delete_journal_entry($expense_id)
    {
        $journal = $this->db
            ->select('JournalID')
            ->where('ReferenceNo', 'EXP-' . $expense_id)
            ->get('journal_entries')
            ->row();
        
        if($journal) {
            // Delete journal details
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entry_details');
            // Delete journal header
            $this->db->where('JournalID', $journal->JournalID)->delete('journal_entries');
        }
    }


    // Add this method for Excel export with date filter
public function export_excel()
{
    // Get filter parameters
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');
    
    // Build query with filters
    $this->db->select("
        expenses.*,
        chart_of_accounts.AccountCode,
        chart_of_accounts.AccountName,  
        employee.EmployeeName,
        banks.BankName,
        banks.AccountNumber,
        (
            SELECT GROUP_CONCAT(
                CONCAT(
                    c.CompanyName,
                    ' - ',
                    COALESCE(NULLIF(ec.DomainName, ''), c.DomainName, '-')
                )
                SEPARATOR ', '
            )
            FROM expense_customers ec
            INNER JOIN customer c ON c.CustomerId = ec.CustomerId
            WHERE ec.ExpenseID = expenses.ExpenseID
        ) AS Domains
    ");
    
    $this->db->from('expenses');
    $this->db->join('chart_of_accounts', 'chart_of_accounts.AccountID = expenses.AccountID', 'left');
    $this->db->join('banks', 'banks.BankID = expenses.BankID', 'left');
    $this->db->join('employee', 'employee.EmployeeID = expenses.EmployeeID', 'left');
    $this->db->where('expenses.IsDeleted', 0);
    
    // Apply date filters
    if(!empty($from_date)) {
        $this->db->where('expenses.ExpenseDate >=', $from_date);
    }
    if(!empty($to_date)) {
        $this->db->where('expenses.ExpenseDate <=', $to_date);
    }
    
    $this->db->order_by('expenses.ExpenseDate', 'DESC');
    $this->db->order_by('expenses.ExpenseID', 'DESC');
    
    $records = $this->db->get()->result();
    
    // Load PHPExcel or use simple CSV export
    $this->load->library('excel');
    
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    
    // Set worksheet title
    $objPHPExcel->getActiveSheet()->setTitle('Expenses Report');
    
    // Set headers
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Date');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Expense Account');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Customer/Domain');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Employee');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Payee');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Description');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Bank');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Amount');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Payment Method');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Remarks');
    
    // Style header row
    $headerStyle = array(
        'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '4F81BD')),
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    );
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($headerStyle);
    
    // Add data rows
    $row = 2;
    $totalAmount = 0;
    foreach($records as $record) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $record->ExpenseID);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, date('Y-m-d', strtotime($record->ExpenseDate)));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $record->AccountCode . ' - ' . $record->AccountName);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $record->Domains ?: '-');
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $record->EmployeeName ?: '-');
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $record->Payee ?: '-');
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $record->Description ?: '-');
        
        $bankName = $record->BankName ? $record->BankName . ' - ' . $record->AccountNumber : '-';
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $bankName);
        
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $record->Amount);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $record->PaymentMethod ?: '-');
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $record->Remarks ?: '-');
        
        $totalAmount += $record->Amount;
        $row++;
    }
    
    // Add total row
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, 'TOTAL:');
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $totalAmount);
    $objPHPExcel->getActiveSheet()->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->getStyle('H' . $row . ':I' . $row)->getFont()->setBold(true);
    
    // Auto-size columns
    foreach(range('A', 'K') as $col) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Set filename
    $filename = 'Expenses_Report_' . date('Y-m-d');
    if(!empty($from_date) && !empty($to_date)) {
        $filename .= '_from_' . $from_date . '_to_' . $to_date;
    } elseif(!empty($from_date)) {
        $filename .= '_from_' . $from_date;
    } elseif(!empty($to_date)) {
        $filename .= '_until_' . $to_date;
    }
    $filename .= '.xls';
    
    // Export to Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

// Alternative: Simple CSV Export (no PHPExcel required)
public function export_csv()
{
    // Get filter parameters
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');
    
    // Build query with filters
    $this->db->select("
        expenses.*,
        chart_of_accounts.AccountCode,
        chart_of_accounts.AccountName,  
        employee.EmployeeName,
        banks.BankName,
        banks.AccountNumber,
        (
            SELECT GROUP_CONCAT(
                CONCAT(
                    c.CompanyName,
                    ' - ',
                    COALESCE(NULLIF(ec.DomainName, ''), c.DomainName, '-')
                )
                SEPARATOR ', '
            )
            FROM expense_customers ec
            INNER JOIN customer c ON c.CustomerId = ec.CustomerId
            WHERE ec.ExpenseID = expenses.ExpenseID
        ) AS Domains
    ");
    
    $this->db->from('expenses');
    $this->db->join('chart_of_accounts', 'chart_of_accounts.AccountID = expenses.AccountID', 'left');
    $this->db->join('banks', 'banks.BankID = expenses.BankID', 'left');
    $this->db->join('employee', 'employee.EmployeeID = expenses.EmployeeID', 'left');
    $this->db->where('expenses.IsDeleted', 0);
    
    // Apply date filters
    if(!empty($from_date)) {
        $this->db->where('expenses.ExpenseDate >=', $from_date);
    }
    if(!empty($to_date)) {
        $this->db->where('expenses.ExpenseDate <=', $to_date);
    }
    
    $this->db->order_by('expenses.ExpenseDate', 'DESC');
    $records = $this->db->get()->result();
    
    // Set filename
    $filename = 'Expenses_Report_' . date('Y-m-d');
    if(!empty($from_date) && !empty($to_date)) {
        $filename .= '_from_' . $from_date . '_to_' . $to_date;
    } elseif(!empty($from_date)) {
        $filename .= '_from_' . $from_date;
    } elseif(!empty($to_date)) {
        $filename .= '_until_' . $to_date;
    }
    $filename .= '.csv';
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Add headers
    fputcsv($output, [
        'ID', 'Date', 'Expense Account', 'Customer/Domain', 'Employee', 
        'Payee', 'Description', 'Bank', 'Amount', 'Payment Method', 'Remarks'
    ]);
    
    // Add data rows
    $totalAmount = 0;
    foreach($records as $record) {
        fputcsv($output, [
            $record->ExpenseID,
            date('Y-m-d', strtotime($record->ExpenseDate)),
            $record->AccountCode . ' - ' . $record->AccountName,
            $record->Domains ?: '-',
            $record->EmployeeName ?: '-',
            $record->Payee ?: '-',
            $record->Description ?: '-',
            $record->BankName ? $record->BankName . ' - ' . $record->AccountNumber : '-',
            $record->Amount,
            $record->PaymentMethod ?: '-',
            $record->Remarks ?: '-'
        ]);
        $totalAmount += $record->Amount;
    }
    
    // Add total row
    fputcsv($output, ['', '', '', '', '', '', '', 'TOTAL:', $totalAmount, '', '']);
    
    fclose($output);
    exit;
}


}
?>