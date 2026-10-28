<?php

class Journal_entries_model extends CI_Model
{
    public function get_accounts()
    {
        return $this->db->get('chart_of_accounts')->result();
    }

    public function get_all()
    {
        return $this->db->get('journal_entries')->result();
    }

    public function get_paged($limit,$start)
    {
        $this->db->order_by(
            'JournalID',
            'DESC'
        );

        return $this->db
            ->get(
                'journal_entries',
                $limit,
                $start
            )
            ->result();
    }


}