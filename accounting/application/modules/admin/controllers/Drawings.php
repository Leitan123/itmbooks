<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Drawings extends MY_Controller
{
    var $page = "drawings";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Drawings_model', 'model');
        $this->controller = get_class($this);
    }

    public function index()
    {
        $from_date = $this->input->get('from_date') ?: null;
        $to_date = $this->input->get('to_date') ?: null;

        // Current month's first and last dates
        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');

        // Get total drawings for current month
        $monthTotal = $this->db
            ->select('IFNULL(SUM(Amount),0) as total')
            ->where('DrawingDate >=', $firstDayOfMonth)
            ->where('DrawingDate <=', $lastDayOfMonth)
            ->get('drawings')
            ->row()
            ->total;

        // Get total drawings matching search / all time
        $this->db->select('IFNULL(SUM(Amount),0) as total');
        if ($from_date) {
            $this->db->where('DrawingDate >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('DrawingDate <=', $to_date);
        }
        $totalAmount = $this->db->get('drawings')->row()->total;

        // Get drawings list
        $records = $this->model->get_all($from_date, $to_date);

        $d = [
            'page'        => "$this->page/index",
            'records'     => $records,
            'totalAmount' => $totalAmount,
            'monthTotal'  => $monthTotal,
            'from_date'   => $from_date,
            'to_date'     => $to_date
        ];

        $this->view($d);
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('DrawingDate', 'Date', 'required');
            $this->form_validation->set_rules('EmployeeID', 'Director / Owner', 'required');
            $this->form_validation->set_rules('WithdrawalType', 'Withdrawal Type', 'required');
            $this->form_validation->set_rules('PaymentMethod', 'Paid From', 'required');
            $this->form_validation->set_rules('Amount', 'Amount', 'required|numeric');

            if ($this->form_validation->run()) {
                $this->db->trans_begin();

                // Handle file attachment upload
                $attachment = null;
                if (!empty($_FILES['Attachment']['name'])) {
                    $config['upload_path'] = './media/doc/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
                    $config['max_size'] = '10240'; // 10MB
                    $config['encrypt_name'] = TRUE;

                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('Attachment')) {
                        $uploadData = $this->upload->data();
                        $attachment = $uploadData['file_name'];
                    } else {
                        $this->session->set_flashdata('error', 'Upload failed: ' . $this->upload->display_errors('', ''));
                        redirect('admin/drawings/create');
                        return;
                    }
                }

                $data = [
                    'DrawingDate'    => $this->input->post('DrawingDate'),
                    'EmployeeID'     => $this->input->post('EmployeeID'),
                    'WithdrawalType' => $this->input->post('WithdrawalType'),
                    'PaymentMethod'  => $this->input->post('PaymentMethod'),
                    'BankID'         => $this->input->post('PaymentMethod') == 'Bank' ? $this->input->post('BankID') : null,
                    'Amount'         => $this->input->post('Amount'),
                    'Payee'          => $this->input->post('Payee'),
                    'Description'    => $this->input->post('Description'),
                    'Attachment'     => $attachment,
                    'CreatedBy'      => $this->session->userdata('UserID') ?: 1,
                    'CreatedDate'    => date('Y-m-d H:i:s')
                ];

                $this->db->insert('drawings', $data);
                $drawing_id = $this->db->insert_id();

                // Post Journal Entry
                $this->post_journal_entry($drawing_id, $data);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'danger',
                        'text'  => '<strong>Error:</strong> Failed to save Drawings entry.'
                    ]);
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'success',
                        'text'  => '<strong>Success:</strong> Drawings entry added successfully.'
                    ]);
                }

                redirect('admin/drawings');
                return;
            }
        }

        $d = [
            'page'      => "$this->page/form",
            'obj'       => $this->model->get(0), // empty object
            'directors' => $this->model->get_directors(),
            'banks'     => $this->model->get_banks()
        ];

        $this->view($d);
    }

    public function edit($id = 0)
    {
        if (!$id) {
            redirect('admin/drawings');
        }

        $obj = $this->model->get($id);
        if (!$obj) {
            redirect('admin/drawings');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('DrawingDate', 'Date', 'required');
            $this->form_validation->set_rules('EmployeeID', 'Director / Owner', 'required');
            $this->form_validation->set_rules('WithdrawalType', 'Withdrawal Type', 'required');
            $this->form_validation->set_rules('PaymentMethod', 'Paid From', 'required');
            $this->form_validation->set_rules('Amount', 'Amount', 'required|numeric');

            if ($this->form_validation->run()) {
                $this->db->trans_begin();

                // Handle file attachment upload
                $attachment = $obj->Attachment;
                if (!empty($_FILES['Attachment']['name'])) {
                    $config['upload_path'] = './media/doc/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
                    $config['max_size'] = '10240'; // 10MB
                    $config['encrypt_name'] = TRUE;

                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('Attachment')) {
                        // Delete old file if exists
                        if ($obj->Attachment && file_exists('./media/doc/' . $obj->Attachment)) {
                            @unlink('./media/doc/' . $obj->Attachment);
                        }
                        $uploadData = $this->upload->data();
                        $attachment = $uploadData['file_name'];
                    } else {
                        $this->session->set_flashdata('error', 'Upload failed: ' . $this->upload->display_errors('', ''));
                        redirect('admin/drawings/edit/' . $id);
                        return;
                    }
                }

                $data = [
                    'DrawingDate'    => $this->input->post('DrawingDate'),
                    'EmployeeID'     => $this->input->post('EmployeeID'),
                    'WithdrawalType' => $this->input->post('WithdrawalType'),
                    'PaymentMethod'  => $this->input->post('PaymentMethod'),
                    'BankID'         => $this->input->post('PaymentMethod') == 'Bank' ? $this->input->post('BankID') : null,
                    'Amount'         => $this->input->post('Amount'),
                    'Payee'          => $this->input->post('Payee'),
                    'Description'    => $this->input->post('Description'),
                    'Attachment'     => $attachment
                ];

                $this->db->where('DrawingID', $id)->update('drawings', $data);

                // Post updated journal entry (by reverting old first)
                $this->post_journal_entry($id, $data, true);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'danger',
                        'text'  => '<strong>Error:</strong> Failed to update Drawings entry.'
                    ]);
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'success',
                        'text'  => '<strong>Success:</strong> Drawings entry updated successfully.'
                    ]);
                }

                redirect('admin/drawings');
                return;
            }
        }

        $d = [
            'page'      => "$this->page/form",
            'obj'       => $obj,
            'directors' => $this->model->get_directors(),
            'banks'     => $this->model->get_banks()
        ];

        $this->view($d);
    }

    public function delete($id = 0)
    {
        if ($id) {
            $obj = $this->model->get($id);
            if ($obj) {
                $this->db->trans_begin();

                // 1. Get associated journal entry
                $journal = $this->db->select('JournalID')
                                    ->where('ReferenceNo', 'DRW-' . $id)
                                    ->get('journal_entries')
                                    ->row();
                if ($journal) {
                    // 2. Revert account balances
                    $details = $this->db->where('JournalID', $journal->JournalID)->get('journal_entry_details')->result();
                    foreach ($details as $detail) {
                        if ($detail->DebitAmount > 0) {
                            $this->db->set('CurrentBalance', 'CurrentBalance - ' . $detail->DebitAmount, FALSE);
                            $this->db->where('AccountID', $detail->AccountID);
                            $this->db->update('chart_of_accounts');
                        }
                        if ($detail->CreditAmount > 0) {
                            $this->db->set('CurrentBalance', 'CurrentBalance + ' . $detail->CreditAmount, FALSE);
                            $this->db->where('AccountID', $detail->AccountID);
                            $this->db->update('chart_of_accounts');
                        }
                    }

                    // 3. Delete journal details and header
                    $this->db->where('JournalID', $journal->JournalID)->delete('journal_entry_details');
                    $this->db->where('JournalID', $journal->JournalID)->delete('journal_entries');
                }

                // Delete attachment file
                if ($obj->Attachment && file_exists('./media/doc/' . $obj->Attachment)) {
                    @unlink('./media/doc/' . $obj->Attachment);
                }

                // 4. Delete drawings record
                $this->model->delete($id);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'danger',
                        'text'  => '<strong>Error:</strong> Failed to delete Drawings entry.'
                    ]);
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('notification', [
                        'alert' => 'success',
                        'text'  => '<strong>Success:</strong> Drawings entry deleted successfully.'
                    ]);
                }
            }
        }
        redirect('admin/drawings');
    }

    private function post_journal_entry($drawing_id, $drawing_data, $is_update = false)
    {
        // 1. If it's an update, first revert old journal entry and its details balances
        if ($is_update) {
            $old_journal = $this->db->select('JournalID')->where('ReferenceNo', 'DRW-' . $drawing_id)->get('journal_entries')->row();
            if ($old_journal) {
                $old_details = $this->db->where('JournalID', $old_journal->JournalID)->get('journal_entry_details')->result();
                foreach ($old_details as $detail) {
                    if ($detail->DebitAmount > 0) {
                        $this->db->set('CurrentBalance', 'CurrentBalance - ' . $detail->DebitAmount, FALSE);
                        $this->db->where('AccountID', $detail->AccountID);
                        $this->db->update('chart_of_accounts');
                    }
                    if ($detail->CreditAmount > 0) {
                        $this->db->set('CurrentBalance', 'CurrentBalance + ' . $detail->CreditAmount, FALSE);
                        $this->db->where('AccountID', $detail->AccountID);
                        $this->db->update('chart_of_accounts');
                    }
                }
                // Delete old details
                $this->db->where('JournalID', $old_journal->JournalID)->delete('journal_entry_details');
                
                // Update journal header
                $this->db->where('JournalID', $old_journal->JournalID)->update('journal_entries', [
                    'JournalDate' => $drawing_data['DrawingDate'],
                    'Description' => 'Drawings: ' . ($drawing_data['Description'] ?: 'Personal Withdrawal'),
                    'CreatedDate' => date('Y-m-d H:i:s')
                ]);
                $journalId = $old_journal->JournalID;
            } else {
                $is_update = false; // fallback to create if journal not found
            }
        }

        if (!$is_update) {
            // Create journal entry header
            $journalData = [
                'JournalDate' => $drawing_data['DrawingDate'],
                'ReferenceNo' => 'DRW-' . $drawing_id,
                'Description' => 'Drawings: ' . ($drawing_data['Description'] ?: 'Personal Withdrawal'),
                'CreatedBy' => $this->session->userdata('UserID') ?: 1,
                'CreatedDate' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('journal_entries', $journalData);
            $journalId = $this->db->insert_id();
        }

        // Resolve Debit Account (Drawings - DirectorName)
        // Get EmployeeName
        $employee = $this->db->select('EmployeeName')->where('EmployeeID', $drawing_data['EmployeeID'])->get('employee')->row();
        $directorName = $employee ? trim($employee->EmployeeName) : 'Unknown';
        $accountName = 'Drawings - ' . $directorName;

        // Check if account exists
        $coa = $this->db->where('AccountName', $accountName)->get('chart_of_accounts')->row();
        if ($coa) {
            $debitAccountID = $coa->AccountID;
        } else {
            // Find next available AccountCode for Type 3 (Equity / Capital)
            $maxCodeRow = $this->db->select('MAX(CAST(AccountCode AS UNSIGNED)) AS MaxCode')
                                   ->where('AccountTypeID', 3)
                                   ->get('chart_of_accounts')
                                   ->row();
            $nextCode = $maxCodeRow && $maxCodeRow->MaxCode ? ($maxCodeRow->MaxCode + 1) : 3005;

            // Create account
            $newAccountData = [
                'AccountCode' => (string)$nextCode,
                'AccountName' => $accountName,
                'AccountTypeID' => 3,
                'OpeningBalance' => 0.00,
                'CurrentBalance' => 0.00,
                'Status' => 1,
                'CreatedDate' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('chart_of_accounts', $newAccountData);
            $debitAccountID = $this->db->insert_id();
        }

        // Resolve Credit Account
        $creditAccountID = null;
        if ($drawing_data['PaymentMethod'] == 'Cash') {
            // Find cash account
            $cashAccount = $this->db->select('AccountID')->where('AccountName', 'Cash Account')->get('chart_of_accounts')->row();
            $creditAccountID = $cashAccount ? $cashAccount->AccountID : 1;
        } elseif ($drawing_data['PaymentMethod'] == 'Bank' && !empty($drawing_data['BankID'])) {
            $bank = $this->db->select('AccountID')->where('BankID', $drawing_data['BankID'])->get('banks')->row();
            $creditAccountID = $bank ? $bank->AccountID : null;
        }

        // Save Journal Details
        // 1. Debit detail row
        $debitDetail = [
            'JournalID' => $journalId,
            'AccountID' => $debitAccountID,
            'DebitAmount' => $drawing_data['Amount'],
            'CreditAmount' => 0,
            'Description' => 'Personal Withdrawal - ' . $drawing_data['WithdrawalType'] . ($drawing_data['Payee'] ? ' to ' . $drawing_data['Payee'] : '')
        ];
        $this->db->insert('journal_entry_details', $debitDetail);

        // Update Debit Account balance
        $this->db->set('CurrentBalance', 'CurrentBalance + ' . $drawing_data['Amount'], FALSE);
        $this->db->where('AccountID', $debitAccountID);
        $this->db->update('chart_of_accounts');

        // 2. Credit detail row
        if ($creditAccountID) {
            $creditDetail = [
                'JournalID' => $journalId,
                'AccountID' => $creditAccountID,
                'DebitAmount' => 0,
                'CreditAmount' => $drawing_data['Amount'],
                'Description' => 'Paid from ' . $drawing_data['PaymentMethod']
            ];
            $this->db->insert('journal_entry_details', $creditDetail);

            // Update Credit Account balance
            $this->db->set('CurrentBalance', 'CurrentBalance - ' . $drawing_data['Amount'], FALSE);
            $this->db->where('AccountID', $creditAccountID);
            $this->db->update('chart_of_accounts');
        }
    }
}
?>
