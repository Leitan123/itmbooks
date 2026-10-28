<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_orders extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('user')) {
            redirect(base_url('admin'));
        }
        $this->load->database();
        if (!$this->db->field_exists('Description', 'purchase_order_items')) {
            $this->db->query("ALTER TABLE purchase_order_items ADD Description TEXT DEFAULT NULL");
        }
    }

    public function index()
    {
        $data['records'] = $this->db
            ->where('IsDeleted', 0)
            ->order_by('PurchaseOrderID', 'DESC')
            ->get('purchase_order')
            ->result();

        $this->load->view('pages/purchase_orders/index', $data);
    }

    public function create()
    {
        $last = $this->db
            ->order_by('PurchaseOrderID','DESC')
            ->limit(1)
            ->get('purchase_order')
            ->row();

        if($last)
        {
            $nextNo = $last->PurchaseOrderID + 1;
        }
        else
        {
            $nextNo = 1;
        }

        $data['po_no'] = 'PO-' . date('Ymd') . '-' . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

        $data['vendors'] = $this->db
            ->get_where('vendor',['IsDeleted'=>0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items',['IsDeleted'=>0])
            ->result();

        $this->load->view('pages/purchase_orders/form', $data);
    }

    public function save()
    {
        $form = $this->input->post('form');

        $vendor = $this->db->where('VendorID', $form['VendorID'])->get('vendor')->row();
        $vendorName = $vendor ? $vendor->VendorName : 'Unknown Vendor';

        $po = array(
            'PurchaseOrderNo'    => $form['PurchaseOrderNo'],
            'PurchaseOrderDate'  => $form['PurchaseOrderDate'],
            'VendorID'           => $form['VendorID'],
            'VendorName'         => $vendorName,
            'SubTotal'           => $form['SubTotal'],
            'DiscountAmount'     => $form['DiscountAmount'],
            'GrandTotal'         => $form['GrandTotal'],
            'Remarks'            => $form['Remarks']
        );

        if(!empty($form['PurchaseOrderID']))
        {
            $PurchaseOrderID = $form['PurchaseOrderID'];

            $this->db
                ->where('PurchaseOrderID', $PurchaseOrderID)
                ->update('purchase_order', $po);

            $this->db
                ->where('PurchaseOrderID', $PurchaseOrderID)
                ->delete('purchase_order_items');
        }
        else
        {
            $po['Status'] = 'Pending';
            $po['CreatedDate'] = date('Y-m-d H:i:s');
            $po['IsDeleted'] = 0;

            $this->db->insert('purchase_order', $po);
            $PurchaseOrderID = $this->db->insert_id();
        }

        if(!empty($form['items']))
        {
            foreach($form['items'] as $item)
            {
                if(empty($item['ItemID']))
                {
                    continue;
                }

                $itemObj = $this->db->where('ItemID', $item['ItemID'])->get('items')->row();
                $itemName = $itemObj ? $itemObj->ItemName : 'Unknown Item';

                $itemData = array(
                    'PurchaseOrderID' => $PurchaseOrderID,
                    'ItemID'          => $item['ItemID'],
                    'ItemName'        => $itemName,
                    'Description'     => isset($item['Description']) ? $item['Description'] : '',
                    'Qty'             => $item['Qty'],
                    'UnitPrice'       => $item['UnitPrice'],
                    'TotalAmount'     => $item['TotalAmount']
                );

                $this->db->insert('purchase_order_items', $itemData);
            }
        }

        redirect('admin/purchase_orders');
    }

    public function edit($PurchaseOrderID)
    {
        $data['obj'] = $this->db
            ->get_where('purchase_order', ['PurchaseOrderID' => $PurchaseOrderID])
            ->row();

        $data['po_items'] = $this->db
            ->get_where('purchase_order_items', ['PurchaseOrderID' => $PurchaseOrderID])
            ->result();

        $data['vendors'] = $this->db
            ->get_where('vendor', ['IsDeleted' => 0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items', ['IsDeleted' => 0])
            ->result();

        $this->load->view('pages/purchase_orders/form', $data);
    }

    public function delete($PurchaseOrderID)
    {
        $this->db
            ->where('PurchaseOrderID', $PurchaseOrderID)
            ->update('purchase_order', ['IsDeleted' => 1]);

        redirect('admin/purchase_orders');
    }

    public function print($PurchaseOrderID)
    {
        $po = $this->db
            ->select('po.*, v.VendorName, v.CompanyName, v.MobileNo, v.EmailId, v.Address')
            ->from('purchase_order po')
            ->join('vendor v', 'v.VendorID = po.VendorID', 'left')
            ->where('po.PurchaseOrderID', $PurchaseOrderID)
            ->get()
            ->row();

        if (!$po) {
            show_404();
        }

        $items = $this->db
            ->select('poi.*, i.ItemName, i.ItemCode')
            ->from('purchase_order_items poi')
            ->join('items i', 'i.ItemID = poi.ItemID', 'left')
            ->where('poi.PurchaseOrderID', $PurchaseOrderID)
            ->get()
            ->result();

        $data = [
            'po' => $po,
            'items' => $items,
            'company' => $this->db->get('company_settings')->row()
        ];

        $this->load->view('pages/purchase_orders/print', $data);
    }
}
?>
