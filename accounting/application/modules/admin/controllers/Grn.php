<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grn extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('user')) {
            redirect(base_url('admin'));
        }
        $this->load->database();
        if (!$this->db->field_exists('Description', 'grn_items')) {
            $this->db->query("ALTER TABLE grn_items ADD Description TEXT DEFAULT NULL");
        }
    }

    public function index()
    {
        $data['records'] = $this->db
            ->where('IsDeleted', 0)
            ->order_by('GrnID', 'DESC')
            ->get('grn')
            ->result();

        $this->load->view('pages/grn/index', $data);
    }

    public function create()
    {
        $last = $this->db
            ->order_by('GrnID','DESC')
            ->limit(1)
            ->get('grn')
            ->row();

        if($last)
        {
            $nextNo = $last->GrnID + 1;
        }
        else
        {
            $nextNo = 1;
        }

        $data['grn_no'] = 'GRN-' . date('Ymd') . '-' . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

        $data['vendors'] = $this->db
            ->get_where('vendor',['IsDeleted'=>0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items',['IsDeleted'=>0])
            ->result();

        $data['purchase_orders'] = $this->db
            ->get_where('purchase_order',['IsDeleted'=>0])
            ->result();

        $this->load->view('pages/grn/form', $data);
    }

    public function save()
    {
        $form = $this->input->post('form');

        $vendor = $this->db->where('VendorID', $form['VendorID'])->get('vendor')->row();
        $vendorName = $vendor ? $vendor->VendorName : 'Unknown Vendor';

        $grn = array(
            'GrnNo'           => $form['GrnNo'],
            'GrnDate'         => $form['GrnDate'],
            'VendorID'        => $form['VendorID'],
            'VendorName'      => $vendorName,
            'PurchaseOrderNo' => $form['PurchaseOrderNo'] ?? '',
            'Remarks'         => $form['Remarks']
        );

        if(!empty($form['GrnID']))
        {
            $GrnID = $form['GrnID'];

            $this->db
                ->where('GrnID', $GrnID)
                ->update('grn', $grn);

            $this->db
                ->where('GrnID', $GrnID)
                ->delete('grn_items');
        }
        else
        {
            $grn['CreatedDate'] = date('Y-m-d H:i:s');
            $grn['IsDeleted'] = 0;

            $this->db->insert('grn', $grn);
            $GrnID = $this->db->insert_id();
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
                    'GrnID'       => $GrnID,
                    'ItemID'      => $item['ItemID'],
                    'ItemName'    => $itemName,
                    'Description' => isset($item['Description']) ? $item['Description'] : '',
                    'QtyReceived' => $item['QtyReceived']
                );

                $this->db->insert('grn_items', $itemData);
            }
        }

        redirect('admin/grn');
    }

    // Ajax endpoint to fetch PO details and items for GRN loading
    public function get_po_details($po_no)
    {
        $po = $this->db
            ->where('PurchaseOrderNo', urldecode($po_no))
            ->get('purchase_order')
            ->row();

        if ($po) {
            $items = $this->db
                ->where('PurchaseOrderID', $po->PurchaseOrderID)
                ->get('purchase_order_items')
                ->result();

            echo json_encode([
                'status' => 'success',
                'po' => $po,
                'items' => $items
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Purchase Order not found'
            ]);
        }
    }

    public function edit($GrnID)
    {
        $data['obj'] = $this->db
            ->get_where('grn', ['GrnID' => $GrnID])
            ->row();

        $data['grn_items'] = $this->db
            ->get_where('grn_items', ['GrnID' => $GrnID])
            ->result();

        $data['vendors'] = $this->db
            ->get_where('vendor', ['IsDeleted' => 0])
            ->result();

        $data['items'] = $this->db
            ->get_where('items', ['IsDeleted' => 0])
            ->result();

        $data['purchase_orders'] = $this->db
            ->get_where('purchase_order', ['IsDeleted' => 0])
            ->result();

        $this->load->view('pages/grn/form', $data);
    }

    public function delete($GrnID)
    {
        $this->db
            ->where('GrnID', $GrnID)
            ->update('grn', ['IsDeleted' => 1]);

        redirect('admin/grn');
    }

    public function print($GrnID)
    {
        $grn = $this->db
            ->select('g.*, v.VendorName, v.CompanyName, v.MobileNo, v.EmailId, v.Address')
            ->from('grn g')
            ->join('vendor v', 'v.VendorID = g.VendorID', 'left')
            ->where('g.GrnID', $GrnID)
            ->get()
            ->row();

        if (!$grn) {
            show_404();
        }

        $items = $this->db
            ->select('gi.*, i.ItemName, i.ItemCode')
            ->from('grn_items gi')
            ->join('items i', 'i.ItemID = gi.ItemID', 'left')
            ->where('gi.GrnID', $GrnID)
            ->get()
            ->result();

        $data = [
            'grn' => $grn,
            'items' => $items,
            'company' => $this->db->get('company_settings')->row()
        ];

        $this->load->view('pages/grn/print', $data);
    }
}
?>
