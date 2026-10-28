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
        $this->obj = $this->model->get_all_fields();

        $d = [
            'page'     => "$this->page/index",
            'obj'      => $this->obj,
            'accounts' => $this->model->get_expense_accounts(),
            'records'  => $this->model->get_all()
        ];

        $this->index();
    }



    public function index()
    {
        $data['records'] = $this->model->get_all();

        $data['accounts'] = $this->model->get_expense_accounts();

        $data['banks'] = $this->db
            ->where('IsDeleted', 0)
            ->order_by('BankName', 'ASC')
            ->get('banks')
            ->result();

        $data['employees'] = $this->db
            ->where('IsDeleted', 0)
            ->order_by('EmployeeName', 'ASC')
            ->get('employee')
            ->result();

        $data['customers'] = $this->db
            ->where('IsDeleted',0)
            ->order_by('DomainName','ASC')
            ->get('customer')
            ->result();

        $this->load->view(
            'pages/expenses/index',
            $data
        );
    }


    public function save()
    {
    $paymentMethod = $this->input->post('PaymentMethod');

    
    $data = [

        'ExpenseDate'   => $this->input->post('ExpenseDate'),
        'AccountID'     => $this->input->post('AccountID'),
        'EmployeeID'    => !empty($this->input->post('EmployeeID'))
                            ? $this->input->post('EmployeeID')
                            : NULL,
        'Description'   => $this->input->post('Description'),
        'Payee'         => $this->input->post('Payee'),
        'Amount'        => $this->input->post('Amount'),
        'PaymentMethod' => $paymentMethod,

        'BankID'        => !empty($this->input->post('BankID'))
                            ? $this->input->post('BankID')
                            : NULL,

        'CustomerId' => !empty($this->input->post('CustomerId'))
                ? $this->input->post('CustomerId')
                : NULL,

        'Remarks'       => $this->input->post('Remarks'),

        'CreatedDate'   => date('Y-m-d H:i:s')

    ];

    // =====================================
    // SAVE EXPENSE
    // =====================================

    $this->db->insert('expenses', $data);

    $expenseID = $this->db->insert_id();

    // =====================================
    // SAVE MULTIPLE CUSTOMERS / DOMAINS
    // =====================================

    $customerIds = $this->input->post('CustomerIds');

    if(!empty($customerIds))
    {
        foreach($customerIds as $customerId)
        {
            $this->db->insert(
                'expense_customers',
                array(
                    'ExpenseID'  => $expenseID,
                    'CustomerId' => $customerId
                )
            );
        }
    }

    // =====================================
    // JOURNAL ENTRY HEADER
    // =====================================

    $journal = [

        'JournalDate' => $this->input->post('ExpenseDate'),

        'ReferenceNo' => 'EXP-' . $expenseID,

        'Description' => $this->input->post('Description')

    ];

    $this->db->insert(
        'journal_entries',
        $journal
    );

    $journalID = $this->db->insert_id();

    $expenseAccount = $this->input->post('AccountID');

    $amount = (float)$this->input->post('Amount');

    // =====================================
    // CASH / BANK ACCOUNT
    // =====================================

    if(
        $paymentMethod == 'Bank'
        ||
        $paymentMethod == 'Bank Transfer'
        ||
        $paymentMethod == 'Cheque'
    )
    {
        $cashAccount = 2; // Bank Account
    }
    else
    {
        $cashAccount = 1; // Cash In Hand
    }

    // =====================================
    // DEBIT EXPENSE ACCOUNT
    // =====================================

    $this->db->insert(
        'journal_entry_details',
        [

            'JournalID'    => $journalID,

            'AccountID'    => $expenseAccount,

            'DebitAmount'  => $amount,

            'CreditAmount' => 0

        ]
    );

    // =====================================
    // CREDIT CASH/BANK
    // =====================================

    $this->db->insert(
        'journal_entry_details',
        [

            'JournalID'    => $journalID,

            'AccountID'    => $cashAccount,

            'DebitAmount'  => 0,

            'CreditAmount' => $amount

        ]
    );

    // =====================================
    // UPDATE EXPENSE ACCOUNT BALANCE
    // =====================================

    $this->db->set(
        'CurrentBalance',
        'CurrentBalance + '.$amount,
        FALSE
    );

    $this->db->where(
        'AccountID',
        $expenseAccount
    );

    $this->db->update(
        'chart_of_accounts'
    );

    // =====================================
    // UPDATE CASH/BANK BALANCE
    // =====================================

    $this->db->set(
        'CurrentBalance',
        'CurrentBalance - '.$amount,
        FALSE
    );

    $this->db->where(
        'AccountID',
        $cashAccount
    );

    $this->db->update(
        'chart_of_accounts'
    );

    // =====================================
    // CREATE ISSUED CHEQUE
    // =====================================

    if(
        strtolower(
            trim($paymentMethod)
        ) == 'cheque'
    )
    {
        $issuedCheque = [

            'ChequeNo' => $this->input->post('ChequeNo'),

            'ChequeDate' =>
                !empty(
                    $this->input->post('ChequeDate')
                )
                ?
                $this->input->post('ChequeDate')
                :
                $this->input->post('ExpenseDate'),

            'VendorID' => NULL,

            'PayeeName' =>
                trim(
                    $this->input->post('Payee')
                ),

            'AccountID' => $expenseAccount,

            'BankID' =>
                $this->input->post('BankID'),

            'Amount' => $amount,

            'Status' => 'Issued',

            'Remarks' =>
                $this->input->post('Remarks'),

            'CreatedDate' =>
                date('Y-m-d H:i:s'),

            'ExpenseID' =>
                $expenseID

        ];

        $this->db->insert(
            'issued_cheques',
            $issuedCheque
        );
    }

    redirect('admin/expenses');
    

    }



    public function delete($id = 0)
    {
        if($id)
        {
            $journal = $this->db
                ->where('ReferenceNo', 'EXP-'.$id)
                ->get('journal_entries')
                ->row();

            if($journal)
            {
                $this->db
                    ->where('JournalID', $journal->JournalID)
                    ->delete('journal_entry_details');

                $this->db
                    ->where('JournalID', $journal->JournalID)
                    ->delete('journal_entries');
            }

            $this->db
                ->where('ExpenseID', $id)
                ->delete('expenses');
        }

        redirect('admin/expenses');
    }


    public function rebuild_expense_journals()
    {
        $expenses = $this->db
            ->where('IsDeleted',0)
            ->get('expenses')
            ->result();

        foreach($expenses as $exp)
        {
            $journal = array(
                'JournalDate' => $exp->ExpenseDate,
                'ReferenceNo' => 'EXP-'.$exp->ExpenseID,
                'Description' => $exp->Description
            );

            $this->db->insert('journal_entries',$journal);

            $journalID = $this->db->insert_id();

            $this->db->insert('journal_entry_details',array(
                'JournalID'=>$journalID,
                'AccountID'=>$exp->AccountID,
                'DebitAmount'=>$exp->Amount,
                'CreditAmount'=>0
            ));

            $cashAccount = 1;

            if(
                $exp->PaymentMethod == 'Bank'
                ||
                $exp->PaymentMethod == 'Bank Transfer'
                ||
                $exp->PaymentMethod == 'Cheque'
            )
            {
                $cashAccount = 2;
            }

            $this->db->insert('journal_entry_details',array(
                'JournalID'=>$journalID,
                'AccountID'=>$cashAccount,
                'DebitAmount'=>0,
                'CreditAmount'=>$exp->Amount
            ));
        }

        echo 'Expense Journals Rebuilt Successfully';
    }

    public function edit($id = 0)
    {
        $data['obj'] = $this->db
            ->where('ExpenseID', $id)
            ->get('expenses')
            ->row();

        if(!$data['obj'])
        {
            redirect('admin/expenses');
        }

        $data['records'] = $this->model->get_all();

        $data['accounts'] = $this->model->get_expense_accounts();

        $data['banks'] = $this->db
            ->where('IsDeleted',0)
            ->order_by('BankName','ASC')
            ->get('banks')
            ->result();

        $data['employees'] = $this->db
            ->where('IsDeleted',0)
            ->order_by('EmployeeName','ASC')
            ->get('employee')
            ->result();

        $data['customers'] = $this->db
            ->where('IsDeleted',0)
            ->order_by('DomainName','ASC')
            ->get('customer')
            ->result();

        $this->load->view(
            'pages/expenses/index',
            $data
        );
    }






    }

?>
