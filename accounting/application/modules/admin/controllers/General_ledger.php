<?php

class General_ledger extends CI_Controller
{
    public function index()
    {
        $account_id = $this->input->get('account');
        $data['selected_account'] = '';

        if (!empty($account_id)) {
            // Get selected account name first
            $acc = $this->db->select('AccountName')
                            ->where('AccountID', $account_id)
                            ->get('chart_of_accounts')
                            ->row();
            if ($acc) {
                $data['selected_account'] = $acc->AccountName;
            }
        }

        $this->db->select('
            journal_entry_details.*,
            chart_of_accounts.AccountName,
            journal_entries.JournalDate,
            journal_entries.ReferenceNo,
            journal_entries.Description AS JournalDescription,
            COALESCE(
                (
                    SELECT GROUP_CONCAT(COALESCE(NULLIF(ec.DomainName, ""), NULLIF(c.DomainName, ""), c.CompanyName) SEPARATOR ", ")
                    FROM expense_customers ec
                    JOIN customer c ON c.CustomerId = ec.CustomerId
                    WHERE journal_entries.ReferenceNo LIKE "EXP-%" 
                      AND ec.ExpenseID = CAST(SUBSTRING(journal_entries.ReferenceNo, 5) AS UNSIGNED)
                ),
                (
                    SELECT e.Payee
                    FROM expenses e
                    WHERE journal_entries.ReferenceNo LIKE "EXP-%"
                      AND e.ExpenseID = CAST(SUBSTRING(journal_entries.ReferenceNo, 5) AS UNSIGNED)
                    LIMIT 1
                ),
                (
                    SELECT COALESCE(NULLIF(c.DomainName, ""), c.CompanyName)
                    FROM customer_payments cp
                    JOIN customer c ON c.CustomerId = cp.CustomerId
                    WHERE journal_entries.ReferenceNo LIKE "CP-%"
                      AND cp.PaymentID = CAST(SUBSTRING(journal_entries.ReferenceNo, 4) AS UNSIGNED)
                    LIMIT 1
                ),
                (
                    SELECT v.VendorName
                    FROM purchases p
                    JOIN vendor v ON v.VendorID = p.VendorID
                    WHERE journal_entries.ReferenceNo LIKE "PUR-%"
                      AND p.PurchaseID = CAST(SUBSTRING(journal_entries.ReferenceNo, 5) AS UNSIGNED)
                    LIMIT 1
                ),
                (
                    SELECT v.VendorName
                    FROM vendor_payments vp
                    JOIN vendor v ON v.VendorID = vp.VendorID
                    WHERE journal_entries.ReferenceNo LIKE "VP-%"
                      AND vp.PaymentID = CAST(SUBSTRING(journal_entries.ReferenceNo, 4) AS UNSIGNED)
                    LIMIT 1
                ),
                ""
            ) AS CustomerDomain
        ');

        $this->db->from('journal_entry_details');

        $this->db->join(
            'chart_of_accounts',
            'chart_of_accounts.AccountID = journal_entry_details.AccountID'
        );

        $this->db->join(
            'journal_entries',
            'journal_entries.JournalID = journal_entry_details.JournalID'
        );

        if (!empty($account_id)) {
            $this->db->where('journal_entry_details.AccountID', $account_id);
        }

        $data['records'] = $this->db->get()->result();

        $this->load->view(
            'pages/general_ledger/index',
            $data
        );
    }
}