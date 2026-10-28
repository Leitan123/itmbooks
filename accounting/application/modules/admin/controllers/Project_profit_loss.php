<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_profit_loss extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';
        $jobCardID = $this->input->get('job_card_id') ?: '';

        // Count total rows for pagination
        $this->db->where('IsDeleted', 0);
        if ($jobCardID) {
            $this->db->where('JobCardID', $jobCardID);
        }
        $total_rows = $this->db->count_all_results('job_card');

        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/project_profit_loss/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $config['reuse_query_string'] = TRUE;

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
        $page = $this->uri->segment(4) ?: 0;

        // Fetch all active Job Cards (Projects) for current page
        $this->db->select('jc.JobCardID, jc.JobCardNo, jc.Title, jc.CustomerID, jc.CustomerName, c.CompanyName, c.DomainName')
            ->from('job_card jc')
            ->join('customer c', 'c.CustomerId = jc.CustomerID', 'left')
            ->where('jc.IsDeleted', 0)
            ->order_by('jc.JobCardDate', 'DESC')
            ->limit($config['per_page'], $page);
        if ($jobCardID) {
            $this->db->where('jc.JobCardID', $jobCardID);
        }
        $projects = $this->db->get()->result();

        foreach ($projects as $p) {
            // Get Incomes (Sales) linked to this JobCardID
            $this->db->select('SaleID, RefNo, SaleDate, TotalSaleAmount')
                ->where('JobCardID', $p->JobCardID)
                ->where('IsDeleted', 0);
            if ($fromDate) $this->db->where('SaleDate >=', $fromDate . ' 00:00:00');
            if ($toDate) $this->db->where('SaleDate <=', $toDate . ' 23:59:59');
            $p->incomes = $this->db->get('sales')->result();
            $p->total_income = array_sum(array_column($p->incomes, 'TotalSaleAmount'));

            // Get Expenses linked to the project's CustomerID
            $p->expenses = [];
            $p->total_expenses = 0;
            if ($p->CustomerID) {
                $this->db->select('e.ExpenseID, e.ExpenseDate, e.ExpenseNo, e.Amount, e.Description, coa.AccountName')
                    ->from('expenses e')
                    ->join('expense_customers ec', 'ec.ExpenseID = e.ExpenseID')
                    ->join('chart_of_accounts coa', 'coa.AccountID = e.AccountID')
                    ->where('ec.CustomerId', $p->CustomerID)
                    ->where('e.IsDeleted', 0);
                if ($fromDate) $this->db->where('e.ExpenseDate >=', $fromDate);
                if ($toDate) $this->db->where('e.ExpenseDate <=', $toDate);
                $p->expenses = $this->db->get()->result();
                $p->total_expenses = array_sum(array_column($p->expenses, 'Amount'));
            }

            $p->net_profit = $p->total_income - $p->total_expenses;
        }

        // 1. Calculate general company overhead (fixed expenses) from last calendar month
        $lastMonthStart = date('Y-m-01', strtotime('first day of last month'));
        $lastMonthEnd = date('Y-m-t', strtotime('last day of last month'));
        $lastMonthDays = (int)date('t', strtotime('last day of last month'));

        $this->db->select('IFNULL(SUM(e.Amount), 0) as total')
            ->from('expenses e')
            ->join('expense_customers ec', 'ec.ExpenseID = e.ExpenseID', 'left')
            ->where('e.ExpenseDate >=', $lastMonthStart)
            ->where('e.ExpenseDate <=', $lastMonthEnd)
            ->where('e.IsDeleted', 0)
            ->where('ec.CustomerID IS NULL');
        $overhead_total = (float)$this->db->get()->row()->total;

        $day_cost = $overhead_total / ($lastMonthDays ?: 30);
        $hour_cost = $day_cost / 8; // 8 hours workday

        // 2. Fetch projects that are NOT renewals AND are unfinished OR have outstanding customer balances
        // Query non-renewal Job Cards
        $this->db->select('jc.JobCardID, jc.JobCardNo, jc.Title, jc.CustomerID, jc.CustomerName, c.CompanyName, c.DomainName, jc.EstimatedDays, jc.Status')
            ->from('job_card jc')
            ->join('customer c', 'c.CustomerId = jc.CustomerID', 'left')
            ->where('jc.IsDeleted', 0)
            ->not_like('jc.Title', 'Renewal', 'both');
        $all_non_renewals = $this->db->get()->result();

        $unpaid_projects = [];
        foreach ($all_non_renewals as $p) {
            // Get customer total sales
            $total_sales = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')
                ->where('CustomerId', $p->CustomerID)
                ->where('IsDeleted', 0)
                ->get('sales')
                ->row()->total;

            // Get customer total payments
            $total_payments = (float)$this->db->select('IFNULL(SUM(PaidAmount), 0) as total')
                ->where('CustomerID', $p->CustomerID)
                ->where('IsDeleted', 0)
                ->get('customer_payments')
                ->row()->total;

            $outstanding = $total_sales - $total_payments;

            // Check if project is unfinished (Status != 'Completed' and Status != 'done') OR has outstanding balance
            $is_unfinished = ($p->Status != 'Completed' && $p->Status != 'done');
            if ($is_unfinished || $outstanding > 1) {
                // Get project's total sale value (linked sales)
                $p->project_value = (float)$this->db->select('IFNULL(SUM(TotalSaleAmount), 0) as total')
                    ->where('JobCardID', $p->JobCardID)
                    ->where('IsDeleted', 0)
                    ->get('sales')
                    ->row()->total;

                // Get project's direct expenses
                $p->direct_expenses = (float)$this->db->select('IFNULL(SUM(e.Amount), 0) as total')
                    ->from('expenses e')
                    ->join('expense_customers ec', 'ec.ExpenseID = e.ExpenseID')
                    ->where('ec.CustomerId', $p->CustomerID)
                    ->where('e.IsDeleted', 0)
                    ->get()
                    ->row()->total;

                $p->outstanding_balance = $outstanding;
                $proj_days = $p->EstimatedDays > 0 ? (int)$p->EstimatedDays : 1;
                $p->allocated_overhead = $proj_days * $day_cost;
                $p->total_cost = $p->allocated_overhead + $p->direct_expenses;

                $unpaid_projects[] = $p;
            }
        }

        // Fetch all projects for the dropdown filter
        $data['all_projects'] = $this->db->select('JobCardID, JobCardNo, Title')
            ->where('IsDeleted', 0)
            ->order_by('JobCardNo', 'ASC')
            ->get('job_card')
            ->result();

        $data['overhead_total'] = $overhead_total;
        $data['day_cost'] = $day_cost;
        $data['hour_cost'] = $hour_cost;
        $data['last_month_name'] = date('F Y', strtotime('last day of last month'));
        $data['unpaid_projects'] = $unpaid_projects;

        $data['projects'] = $projects;
        $data['from_date'] = $fromDate;
        $data['to_date'] = $toDate;
        $data['job_card_id'] = $jobCardID;
        $data['links'] = $this->pagination->create_links();

        $this->load->view('pages/project_profit_loss/index', $data);
    }

    public function export_csv()
    {
        $fromDate = $this->input->get('from_date') ?: '';
        $toDate = $this->input->get('to_date') ?: '';
        $jobCardID = $this->input->get('job_card_id') ?: '';

        $this->db->select('jc.JobCardID, jc.JobCardNo, jc.Title, jc.CustomerID, jc.CustomerName, c.CompanyName, c.DomainName')
            ->from('job_card jc')
            ->join('customer c', 'c.CustomerId = jc.CustomerID', 'left')
            ->where('jc.IsDeleted', 0)
            ->order_by('jc.JobCardDate', 'DESC');
        if ($jobCardID) {
            $this->db->where('jc.JobCardID', $jobCardID);
        }
        $projects = $this->db->get()->result();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Project_Profit_Loss_Report.csv');
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, array('Project-wise Profit & Loss Report'));
        if ($fromDate || $toDate) {
            fputcsv($output, array('Period: ' . ($fromDate ?: 'Start') . ' to ' . ($toDate ?: 'End')));
        }
        fputcsv($output, array(''));

        foreach ($projects as $p) {
            // Get Incomes
            $this->db->select('RefNo, SaleDate, TotalSaleAmount')
                ->where('JobCardID', $p->JobCardID)
                ->where('IsDeleted', 0);
            if ($fromDate) $this->db->where('SaleDate >=', $fromDate . ' 00:00:00');
            if ($toDate) $this->db->where('SaleDate <=', $toDate . ' 23:59:59');
            $incomes = $this->db->get('sales')->result();
            $total_income = array_sum(array_column($incomes, 'TotalSaleAmount'));

            // Get Expenses
            $expenses = [];
            $total_expenses = 0;
            if ($p->CustomerID) {
                $this->db->select('e.ExpenseDate, e.ExpenseNo, e.Amount, e.Description, coa.AccountName')
                    ->from('expenses e')
                    ->join('expense_customers ec', 'ec.ExpenseID = e.ExpenseID')
                    ->join('chart_of_accounts coa', 'coa.AccountID = e.AccountID')
                    ->where('ec.CustomerId', $p->CustomerID)
                    ->where('e.IsDeleted', 0);
                if ($fromDate) $this->db->where('e.ExpenseDate >=', $fromDate);
                if ($toDate) $this->db->where('e.ExpenseDate <=', $toDate);
                $expenses = $this->db->get()->result();
                $total_expenses = array_sum(array_column($expenses, 'Amount'));
            }

            fputcsv($output, array('Project:', $p->Title . ' (' . $p->JobCardNo . ')'));
            fputcsv($output, array('Company Name:', $p->CompanyName));
            if (!empty($p->CustomerName)) {
                fputcsv($output, array('Contact Person:', $p->CustomerName));
            }
            if (!empty($p->DomainName)) {
                fputcsv($output, array('Domain Name:', $p->DomainName));
            }
            fputcsv($output, array('INCOMES'));
            fputcsv($output, array('Date', 'Ref No', 'Amount'));
            foreach ($incomes as $inc) {
                fputcsv($output, array(date('Y-m-d', strtotime($inc->SaleDate)), $inc->RefNo, number_format($inc->TotalSaleAmount, 2, '.', '')));
            }
            fputcsv($output, array('Total Incomes', '', number_format($total_income, 2, '.', '')));
            
            fputcsv($output, array('EXPENSES'));
            fputcsv($output, array('Date', 'Expense No / Account', 'Description', 'Amount'));
            foreach ($expenses as $exp) {
                fputcsv($output, array($exp->ExpenseDate, $exp->ExpenseNo . ' - ' . $exp->AccountName, $exp->Description, number_format($exp->Amount, 2, '.', '')));
            }
            fputcsv($output, array('Total Expenses', '', '', number_format($total_expenses, 2, '.', '')));
            
            $net = $total_income - $total_expenses;
            fputcsv($output, array('NET PROFIT / LOSS', '', '', number_format($net, 2, '.', '')));
            fputcsv($output, array(''));
            fputcsv($output, array('----------------------------------------------------'));
            fputcsv($output, array(''));
        }

        fclose($output);
        exit;
    }
}
