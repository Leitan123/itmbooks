<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_entries extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Journal_entries_model');
    }

    public function index()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/journal_entries/index');

        $config['total_rows'] = $this->db->count_all('journal_entries');

        $config['per_page'] = 20;

        $config['uri_segment'] = 4;

        // Bootstrap 4 pagination configuration
        $config['full_tag_open'] = '<ul class="pagination pagination-split justify-content-end">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $page = $this->uri->segment(4);

        if(!$page)
        {
            $page = 0;
        }

        $data['accounts'] = $this->Journal_entries_model->get_accounts();

        $data['records'] = $this->Journal_entries_model->get_paged($config['per_page'], $page);

        $data['links'] = $this->pagination->create_links();

        $this->load->view('pages/journal_entries/index', $data);
    }

    public function save()
    {
        $accounts = $this->input->post('AccountID');
        $debits   = $this->input->post('DebitAmount');
        $credits  = $this->input->post('CreditAmount');
        $descriptions = $this->input->post('DescriptionDetail');

        if(empty($accounts))
        {
            $this->session->set_flashdata('error', 'Please enter journal details.');
            redirect('admin/journal_entries');
            return;
        }

        // =====================================
        // CHECK DEBIT & CREDIT TOTAL
        // =====================================

        $totalDebit  = 0;
        $totalCredit = 0;

        foreach($accounts as $key => $account)
        {
            $totalDebit  += floatval($debits[$key]);
            $totalCredit += floatval($credits[$key]);
        }

        if(round($totalDebit,2) != round($totalCredit,2))
        {
            $this->session->set_flashdata('error', 'Debit Total and Credit Total must be equal.');
            redirect('admin/journal_entries');
            return;
        }

        // =====================================
        // CHECK DUPLICATE ACCOUNTS
        // =====================================

        $selectedAccounts = array();

        foreach($accounts as $account)
        {
            if(empty($account))
            {
                continue;
            }

            if(in_array($account, $selectedAccounts))
            {
                $this->session->set_flashdata('error', 'Duplicate account selected.');
                redirect('admin/journal_entries');
                return;
            }

            $selectedAccounts[] = $account;
        }

        // =====================================
        // START TRANSACTION
        // =====================================

        $this->db->trans_begin();

        // =====================================
        // SAVE JOURNAL MASTER
        // =====================================

        $journalDate = $this->input->post('JournalDate');
        $referenceNo = $this->input->post('ReferenceNo');
        $description = $this->input->post('Description');

        // Generate Reference No if empty
        if(empty($referenceNo))
        {
            $referenceNo = 'JN-' . date('Ymd') . '-' . rand(100, 999);
        }

        $master = array(
            'JournalDate' => $journalDate,
            'ReferenceNo' => $referenceNo,
            'Description' => $description,
            'CreatedBy'   => $this->session->userdata('UserID') ?: 1,
            'CreatedDate' => date('Y-m-d H:i:s')
        );

        $this->db->insert('journal_entries', $master);
        $journalID = $this->db->insert_id();

        // =====================================
        // SAVE JOURNAL DETAILS
        // =====================================

        foreach($accounts as $key => $account)
        {
            $account = trim($account);

            $debit  = floatval($debits[$key]);
            $credit = floatval($credits[$key]);

            if(empty($account) || ($debit == 0 && $credit == 0))
            {
                continue;
            }

            $details = array(
                'JournalID'    => $journalID,
                'AccountID'    => $account,
                'DebitAmount'  => $debit,
                'CreditAmount' => $credit,
                'Description'  => isset($descriptions[$key]) ? trim($descriptions[$key]) : ''
            );

            $this->db->insert('journal_entry_details', $details);

            // =====================================
            // UPDATE ACCOUNT BALANCE
            // =====================================
            // DEBIT increases account balance
            // CREDIT decreases account balance
            if($debit > 0)
            {
                $this->db->set('CurrentBalance', 'CurrentBalance + ' . $debit, FALSE);
                $this->db->where('AccountID', $account);
                $this->db->update('chart_of_accounts');
            }

            if($credit > 0)
            {
                $this->db->set('CurrentBalance', 'CurrentBalance - ' . $credit, FALSE);
                $this->db->where('AccountID', $account);
                $this->db->update('chart_of_accounts');
            }
        }

        // =====================================
        // COMMIT / ROLLBACK
        // =====================================

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Journal Entry Save Failed.');
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Journal Entry Saved Successfully. Reference No: ' . $referenceNo);
        }

        redirect('admin/journal_entries');
    }

    public function edit($id)
    {
        // Get journal entry details
        $journal = $this->db
            ->select('*')
            ->where('JournalID', $id)
            ->get('journal_entries')
            ->row();

        if(!$journal)
        {
            redirect('admin/journal_entries');
        }

        // Get journal details
        $details = $this->db
            ->select('*')
            ->where('JournalID', $id)
            ->get('journal_entry_details')
            ->result();

        $data['journal'] = $journal;
        $data['details'] = $details;
        $data['accounts'] = $this->Journal_entries_model->get_accounts();

        $this->load->view('pages/journal_entries/edit', $data);
    }

    public function update($id)
    {
        $accounts = $this->input->post('AccountID');
        $debits   = $this->input->post('DebitAmount');
        $credits  = $this->input->post('CreditAmount');
        $descriptions = $this->input->post('DescriptionDetail');

        if(empty($accounts))
        {
            $this->session->set_flashdata('error', 'Please enter journal details.');
            redirect('admin/journal_entries');
            return;
        }

        // =====================================
        // CHECK DEBIT & CREDIT TOTAL
        // =====================================
        $totalDebit  = 0;
        $totalCredit = 0;

        foreach($accounts as $key => $account)
        {
            $totalDebit  += floatval($debits[$key]);
            $totalCredit += floatval($credits[$key]);
        }

        if(round($totalDebit,2) != round($totalCredit,2))
        {
            $this->session->set_flashdata('error', 'Debit Total and Credit Total must be equal.');
            redirect('admin/journal_entries');
            return;
        }

        // =====================================
        // CHECK DUPLICATE ACCOUNTS
        // =====================================
        $selectedAccounts = array();
        foreach($accounts as $account)
        {
            if(empty($account))
            {
                continue;
            }

            if(in_array($account, $selectedAccounts))
            {
                $this->session->set_flashdata('error', 'Duplicate account selected.');
                redirect('admin/journal_entries');
                return;
            }
            $selectedAccounts[] = $account;
        }

        // Start transaction
        $this->db->trans_begin();

        // 1. Get old details to revert balances
        $old_details = $this->db->where('JournalID', $id)->get('journal_entry_details')->result();
        foreach($old_details as $detail)
        {
            if($detail->DebitAmount > 0)
            {
                $this->db->set('CurrentBalance', 'CurrentBalance - ' . $detail->DebitAmount, FALSE);
                $this->db->where('AccountID', $detail->AccountID);
                $this->db->update('chart_of_accounts');
            }
            if($detail->CreditAmount > 0)
            {
                $this->db->set('CurrentBalance', 'CurrentBalance + ' . $detail->CreditAmount, FALSE);
                $this->db->where('AccountID', $detail->AccountID);
                $this->db->update('chart_of_accounts');
            }
        }

        // 2. Delete old details
        $this->db->where('JournalID', $id)->delete('journal_entry_details');

        // 3. Update master
        $master = array(
            'JournalDate' => $this->input->post('JournalDate'),
            'ReferenceNo' => $this->input->post('ReferenceNo'),
            'Description' => $this->input->post('Description')
        );
        $this->db->where('JournalID', $id)->update('journal_entries', $master);

        // 4. Insert new details and update balances
        foreach($accounts as $key => $account)
        {
            $account = trim($account);
            $debit  = floatval($debits[$key]);
            $credit = floatval($credits[$key]);

            if(empty($account) || ($debit == 0 && $credit == 0))
            {
                continue;
            }

            $details = array(
                'JournalID'    => $id,
                'AccountID'    => $account,
                'DebitAmount'  => $debit,
                'CreditAmount' => $credit,
                'Description'  => isset($descriptions[$key]) ? trim($descriptions[$key]) : ''
            );
            $this->db->insert('journal_entry_details', $details);

            if($debit > 0)
            {
                $this->db->set('CurrentBalance', 'CurrentBalance + ' . $debit, FALSE);
                $this->db->where('AccountID', $account);
                $this->db->update('chart_of_accounts');
            }
            if($credit > 0)
            {
                $this->db->set('CurrentBalance', 'CurrentBalance - ' . $credit, FALSE);
                $this->db->where('AccountID', $account);
                $this->db->update('chart_of_accounts');
            }
        }

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Journal Entry Update Failed.');
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Journal Entry Updated Successfully.');
        }

        redirect('admin/journal_entries');
    }

    public function delete($id)
    {
        $this->db->trans_begin();

        // 1. Get journal entry details
        $journal = $this->db
            ->select('*')
            ->where('JournalID', $id)
            ->get('journal_entries')
            ->row();

        if ($journal) {
            // 2. Revert account balances in chart_of_accounts
            $old_details = $this->db->where('JournalID', $id)->get('journal_entry_details')->result();
            foreach($old_details as $detail)
            {
                if($detail->DebitAmount > 0)
                {
                    $this->db->set('CurrentBalance', 'CurrentBalance - ' . $detail->DebitAmount, FALSE);
                    $this->db->where('AccountID', $detail->AccountID);
                    $this->db->update('chart_of_accounts');
                }
                if($detail->CreditAmount > 0)
                {
                    $this->db->set('CurrentBalance', 'CurrentBalance + ' . $detail->CreditAmount, FALSE);
                    $this->db->where('AccountID', $detail->AccountID);
                    $this->db->update('chart_of_accounts');
                }
            }

            // 3. Delete from related tables based on ReferenceNo
            $ref = $journal->ReferenceNo;
            if (preg_match('/^EXP-(\d+)$/i', $ref, $matches)) {
                $expense_id = $matches[1];
                $this->db->where('ExpenseID', $expense_id)->delete('expense_customers');
                $this->db->where('ExpenseID', $expense_id)->update('expenses', ['IsDeleted' => 1]);
            } elseif (preg_match('/^PUR-(\d+)$/i', $ref, $matches)) {
                $purchase_id = $matches[1];
                $this->db->where('PurchaseID', $purchase_id)->update('purchases', ['IsDeleted' => 1]);
            } elseif (preg_match('/^CP-(\d+)$/i', $ref, $matches)) {
                $payment_id = $matches[1];
                $this->db->where('PaymentID', $payment_id)->update('customer_payments', ['IsDeleted' => 1]);
            } elseif (preg_match('/^VP-(\d+)$/i', $ref, $matches)) {
                $payment_id = $matches[1];
                $this->db->where('PaymentID', $payment_id)->update('vendor_payments', ['IsDeleted' => 1]);
            } elseif (preg_match('/^INC-(\d+)$/i', $ref, $matches)) {
                $income_id = $matches[1];
                $this->db->where('IncomeID', $income_id)->delete('incomes');
            } elseif (preg_match('/^DRW-(\d+)$/i', $ref, $matches)) {
                $drawing_id = $matches[1];
                $this->db->where('DrawingID', $drawing_id)->delete('drawings');
            }

            // 4. Delete journal entry and its details
            $this->db->where('JournalID', $id)->delete('journal_entry_details');
            if ($this->db->table_exists('journal_entry_details_backup')) {
                $this->db->where('JournalID', $id)->delete('journal_entry_details_backup');
            }
            $this->db->where('JournalID', $id)->delete('journal_entries');
        }

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Delete Failed.');
        }
        else
        {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Journal Entry Deleted Successfully.');
        }

        redirect('admin/journal_entries');
    }
}
?>