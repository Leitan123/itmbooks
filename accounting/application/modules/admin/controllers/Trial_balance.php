<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trial_balance extends CI_Controller
{
    public function index()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';

        $sql = "
            SELECT
                coa.AccountID,
                coa.AccountName,
                IFNULL(SUM(jd.DebitAmount),0) AS Debit,
                IFNULL(SUM(jd.CreditAmount),0) AS Credit
            FROM chart_of_accounts coa
            LEFT JOIN journal_entry_details jd
                ON jd.AccountID = coa.AccountID
            LEFT JOIN journal_entries je
                ON je.JournalID = jd.JournalID
            WHERE 1=1
        ";
        if ($fromDate) {
            $sql .= " AND je.JournalDate >= " . $this->db->escape($fromDate . ' 00:00:00');
        }
        if ($toDate) {
            $sql .= " AND je.JournalDate <= " . $this->db->escape($toDate . ' 23:59:59');
        }
        $sql .= "
            GROUP BY coa.AccountID
            HAVING Debit > 0 OR Credit > 0
            ORDER BY coa.AccountName
        ";
        $query = $this->db->query($sql);

        $data['records'] = $query->result();

        $sqlDebit = "
            SELECT IFNULL(SUM(jd.DebitAmount),0) AS Total
            FROM journal_entry_details jd
            JOIN journal_entries je ON je.JournalID = jd.JournalID
            WHERE 1=1
        ";
        $sqlCredit = "
            SELECT IFNULL(SUM(jd.CreditAmount),0) AS Total
            FROM journal_entry_details jd
            JOIN journal_entries je ON je.JournalID = jd.JournalID
            WHERE 1=1
        ";
        if ($fromDate) {
            $sqlDebit .= " AND je.JournalDate >= " . $this->db->escape($fromDate . ' 00:00:00');
            $sqlCredit .= " AND je.JournalDate >= " . $this->db->escape($fromDate . ' 00:00:00');
        }
        if ($toDate) {
            $sqlDebit .= " AND je.JournalDate <= " . $this->db->escape($toDate . ' 23:59:59');
            $sqlCredit .= " AND je.JournalDate <= " . $this->db->escape($toDate . ' 23:59:59');
        }

        $data['totalDebit'] = $this->db->query($sqlDebit)->row()->Total;
        $data['totalCredit'] = $this->db->query($sqlCredit)->row()->Total;
        $data['difference'] = $data['totalDebit'] - $data['totalCredit'];
        
        $data['from_date'] = $fromDate;
        $data['to_date'] = $toDate;

        $this->load->view('pages/trial_balance/index', $data);
    }

    public function export_csv()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';

        $sql = "
            SELECT
                coa.AccountName,
                IFNULL(SUM(jd.DebitAmount),0) AS Debit,
                IFNULL(SUM(jd.CreditAmount),0) AS Credit
            FROM chart_of_accounts coa
            LEFT JOIN journal_entry_details jd
                ON jd.AccountID = coa.AccountID
            LEFT JOIN journal_entries je
                ON je.JournalID = jd.JournalID
            WHERE 1=1
        ";
        if ($fromDate) {
            $sql .= " AND je.JournalDate >= " . $this->db->escape($fromDate . ' 00:00:00');
        }
        if ($toDate) {
            $sql .= " AND je.JournalDate <= " . $this->db->escape($toDate . ' 23:59:59');
        }
        $sql .= "
            GROUP BY coa.AccountID
            HAVING Debit > 0 OR Credit > 0
            ORDER BY coa.AccountName
        ";
        $records = $this->db->query($sql)->result();

        $sqlDebit = "
            SELECT IFNULL(SUM(jd.DebitAmount),0) AS Total
            FROM journal_entry_details jd
            JOIN journal_entries je ON je.JournalID = jd.JournalID
            WHERE 1=1
        ";
        $sqlCredit = "
            SELECT IFNULL(SUM(jd.CreditAmount),0) AS Total
            FROM journal_entry_details jd
            JOIN journal_entries je ON je.JournalID = jd.JournalID
            WHERE 1=1
        ";
        if ($fromDate) {
            $sqlDebit .= " AND je.JournalDate >= " . $this->db->escape($fromDate . ' 00:00:00');
            $sqlCredit .= " AND je.JournalDate >= " . $this->db->escape($fromDate . ' 00:00:00');
        }
        if ($toDate) {
            $sqlDebit .= " AND je.JournalDate <= " . $this->db->escape($toDate . ' 23:59:59');
            $sqlCredit .= " AND je.JournalDate <= " . $this->db->escape($toDate . ' 23:59:59');
        }

        $totalDebit = $this->db->query($sqlDebit)->row()->Total;
        $totalCredit = $this->db->query($sqlCredit)->row()->Total;
        $difference = $totalDebit - $totalCredit;

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Trial_Balance.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array('Trial Balance Report'));
        if ($fromDate || $toDate) {
            fputcsv($output, array('Period: ' . ($fromDate ?: 'Start') . ' to ' . ($toDate ?: 'End')));
        }
        fputcsv($output, array(''));

        fputcsv($output, array('Account Name', 'Debit (LKR)', 'Credit (LKR)'));
        foreach ($records as $row) {
            fputcsv($output, array(
                $row->AccountName,
                number_format($row->Debit, 2, '.', ''),
                number_format($row->Credit, 2, '.', '')
            ));
        }

        fputcsv($output, array(''));
        fputcsv($output, array(
            'TOTAL',
            number_format($totalDebit, 2, '.', ''),
            number_format($totalCredit, 2, '.', '')
        ));
        fputcsv($output, array('Difference', number_format($difference, 2, '.', ''), ''));

        fclose($output);
        exit;
    }
}