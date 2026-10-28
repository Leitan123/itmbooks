<?php

class Banks_model extends MY_Model
{
    protected $_table = "banks";
    protected $primary_key = "BankID";

    public function get_balance($bank)
    {
        $balance = floatval($bank->OpeningBalance);
        if(!empty($bank->AccountID))
        {
            $journalCredits = $this->db->select('IFNULL(SUM(jd.CreditAmount),0) Amount')
                ->from('journal_entry_details jd')
                ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                ->where('jd.AccountID',$bank->AccountID)
                ->not_like('je.ReferenceNo', 'SAL-', 'after')
                ->get()
                ->row()
                ->Amount;
            $balance -= floatval($journalCredits);
            
            $journalDebits = $this->db->select('IFNULL(SUM(jd.DebitAmount),0) Amount')
                ->from('journal_entry_details jd')
                ->join('journal_entries je', 'je.JournalID = jd.JournalID')
                ->where('jd.AccountID',$bank->AccountID)
                ->not_like('je.ReferenceNo', 'SAL-', 'after')
                ->get()
                ->row()
                ->Amount;
            $balance += floatval($journalDebits);
        }
        return $balance;
    }

    public function get_all()
    {
        $records = $this->db
            ->where('IsDeleted',0)
            ->order_by('BankID','DESC')
            ->get('banks')
            ->result();

        foreach ($records as &$record) {
            $record->CurrentBalance = $this->get_balance($record);
        }

        return $records;
    }

    public function get_all_fields()
    {
        return (object)[
            'BankID' => '',
            'BankCode' => '',
            'BankName' => '',
            'AccountName' => '',
            'AccountNumber' => '',
            'Branch' => '',
            'OpeningBalance' => 0
        ];
    }
}