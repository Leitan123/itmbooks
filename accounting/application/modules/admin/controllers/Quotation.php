<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Quotation_model');
    }

    public function index()
    {
        $data['records'] = $this->Quotation_model->get_all();

        $this->load->view('pages/quotation/index', $data);
    }

    public function create()
    {
        $last = $this->db
            ->order_by('QuotationID','DESC')
            ->limit(1)
            ->get('quotation')
            ->row();

        if($last)
        {
            $nextNo = $last->QuotationID + 1;
        }
        else
        {
            $nextNo = 1;
        }

        $data['quotation_no'] =
            'QT-' . date('Ymd') . '-' . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

        $data['customers'] = $this->db
            ->get_where('customer',['IsDeleted'=>0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items',['IsDeleted'=>0])
            ->result();

        $this->load->view(
            'pages/quotation/form',
            $data
        );
    }

    public function save()
    {
        $form = $this->input->post('form');

        $quotation = array(

            'QuotationNo'    => $form['QuotationNo'],
            'QuotationDate'  => $form['QuotationDate'],
            'CustomerID'     => $form['CustomerID'],
            'SubTotal'       => $form['SubTotal'],
            'DiscountAmount' => $form['DiscountAmount'],
            'GrandTotal'     => $form['GrandTotal'],
            'Remarks'        => $form['Remarks']

        );

        /*
        UPDATE
        */

        if(!empty($form['QuotationID']))
        {
            $QuotationID =
                $form['QuotationID'];

            $quotation['ModifiedDate'] =
                date('Y-m-d H:i:s');

            $this->db
                ->where(
                    'QuotationID',
                    $QuotationID
                );

            $this->db->update(
                'quotation',
                $quotation
            );

            /*
            DELETE OLD ITEMS
            */

            $this->db
                ->where(
                    'QuotationID',
                    $QuotationID
                );

            $this->db->delete(
                'quotation_items'
            );
        }
        else
        {
            /*
            INSERT
            */

            $quotation['Status'] = 'Pending';

            $quotation['CreatedDate'] =
                date('Y-m-d H:i:s');

            $quotation['IsDeleted'] = 0;

            $this->db->insert(
                'quotation',
                $quotation
            );

            $QuotationID =
                $this->db->insert_id();
        }

        /*
        SAVE ITEMS
        */

        if(!empty($form['items']))
        {
            foreach($form['items'] as $item)
            {
                if(empty($item['ItemID']))
                {
                    continue;
                }

                $itemData = array(

                    'QuotationID' =>
                        $QuotationID,

                    'ItemID' =>
                        $item['ItemID'],

                    'Qty' =>
                        $item['Qty'],

                    'UnitPrice' =>
                        $item['UnitPrice'],

                    'TotalAmount' =>
                        $item['TotalAmount']

                );

                $this->db->insert(
                    'quotation_items',
                    $itemData
                );
            }
        }

        redirect('admin/quotation');
    }

    public function edit($QuotationID)
    {
        $data['obj'] = $this->db
            ->get_where(
                'quotation',
                [
                    'QuotationID' => $QuotationID
                ]
            )
            ->row();

        $data['quotation_items'] = $this->db
            ->get_where(
                'quotation_items',
                [
                    'QuotationID' => $QuotationID
                ]
            )
            ->result();

        $data['customers'] = $this->db
            ->get_where(
                'customer',
                [
                    'IsDeleted' => 0
                ]
            )
            ->result();

        $data['items'] = $this->db
            ->get_where(
                'items',
                [
                    'IsDeleted' => 0
                ]
            )
            ->result();

        $this->load->view(
            'pages/quotation/form',
            $data
        );
    }


    public function delete($QuotationID)
    {
        $this->db
            ->where(
                'QuotationID',
                $QuotationID
            );

        $this->db->update(
            'quotation',
            [
                'IsDeleted' => 1
            ]
        );

        redirect('admin/quotation');
    }


    public function get_domains()
    {
        $customerID = $this->input->post('CustomerID');

        $domains = array();

        // Main Domain
        $customer = $this->db
            ->where('CustomerId', $customerID)
            ->get('customer')
            ->row();

        if(!empty($customer->DomainName))
        {
            $domains[] = array(
                'DomainID'   => 0,
                'DomainName' => $customer->DomainName
            );
        }

        // Additional Domains
        $additional = $this->db
            ->where('CustomerID', $customerID)
            ->get('customer_domains')
            ->result();

        foreach($additional as $row)
        {
            $domains[] = array(
                'DomainID'   => $row->DomainID,
                'DomainName' => $row->DomainName
            );
        }

        echo json_encode($domains);
    }

    public function print($QuotationID)
    {
        $quotation = $this->db
            ->select('q.*, c.CompanyName, c.CustomerName, c.MobileNo, c.Email, c.Address, c.DomainName')
            ->from('quotation q')
            ->join('customer c', 'c.CustomerId = q.CustomerID', 'left')
            ->where('q.QuotationID', $QuotationID)
            ->get()
            ->row();

        if (!$quotation) {
            show_404();
        }

        $items = $this->db
            ->select('qi.*, i.ItemName')
            ->from('quotation_items qi')
            ->join('items i', 'i.ItemID = qi.ItemID', 'left')
            ->where('qi.QuotationID', $QuotationID)
            ->get()
            ->result();

        $data = [
            'quotation' => $quotation,
            'items' => $items,
            'banks' => $this->db->where('IsDeleted', 0)->get('banks')->result()
        ];

        $this->load->view('pages/quotation/print', $data);
    }

}