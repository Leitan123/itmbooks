<?php
include_once APPPATH . "modules/admin/core/MY_Controller.php";

class Customer extends MY_Controller
{
    var $page = "customer";
    var $img_width = 600;
    var $img_height = 600;

    function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model', 'model');
        $this->controller = get_class($this);
    }

    function create()
    {
        $count = $this->model->count_by([]);
        $cus = $count + 1;

        // Generate a five-digit Customer Number
        $CustomerNo = sprintf('%05d', $cus);

        // Generate a random six-digit SID using mt_rand() for PHP 5.6 compatibility
        $Sid = mt_rand(10000, 99999);

        $this->obj = $this->model->get_all_fields();
        $d = [
            'page' => "$this->page/form",
            'obj' => $this->obj,
            'CustomerNo' => $CustomerNo,
            'Sid' => $Sid,
            'renewals' => []
        ];

        $this->_form($d);
    }

    function _form(&$d, $_id = 0)
    {
        $this->form_validation->set_rules("form[CustomerName]", "Customer Name", "required");

        if ($this->form_validation->run())
        {
            $post = $this->input->post('form');

            $customer_data = [
                'CustomerNo'       => $post['CustomerNo'],
                'CompanyName'      => isset($post['CompanyName']) ? $post['CompanyName'] : '',
                'CustomerName'     => $post['CustomerName'],
                'RegDate'          => $post['RegDate'],
                'Phone'            => isset($post['Phone']) ? $post['Phone'] : '',
                'MobileNo'         => $post['MobileNo'],
                'Address'          => isset($post['Address']) ? $post['Address'] : '',
                'CustomerType'     => isset($post['CustomerType']) ? $post['CustomerType'] : 'COD',
                'CreditLimit'      => isset($post['CreditLimit']) ? $post['CreditLimit'] : 0.00,
                'OpeningBalance'   => isset($post['OpeningBalance']) ? $post['OpeningBalance'] : 0.00,
                'Email'            => isset($post['Email']) ? $post['Email'] : '',
                'WhatsAppNo'       => isset($post['WhatsAppNo']) ? $post['WhatsAppNo'] : '',
                'Website'          => isset($post['Website']) ? $post['Website'] : '',
                'DomainName'       => isset($post['DomainName']) ? $post['DomainName'] : '',
                'HostingExpiry'    => isset($post['HostingExpiry']) ? $post['HostingExpiry'] : NULL,
                'ServiceExpiry'    => isset($post['ServiceExpiry']) ? $post['ServiceExpiry'] : NULL,
                'ContactPerson'    => isset($post['ContactPerson']) ? $post['ContactPerson'] : '',
                'CustomerGroup'    => isset($post['CustomerGroup']) ? $post['CustomerGroup'] : '',
                'Notes'            => isset($post['Notes']) ? $post['Notes'] : '',
                'CustomerTypeZoho' => isset($post['CustomerTypeZoho']) ? $post['CustomerTypeZoho'] : 'Business',
                'Salutation'       => isset($post['Salutation']) ? $post['Salutation'] : '',
                'FirstName'        => isset($post['FirstName']) ? $post['FirstName'] : '',
                'LastName'         => isset($post['LastName']) ? $post['LastName'] : '',
                'Language'         => isset($post['Language']) ? $post['Language'] : 'English',
                'TaxRate'          => isset($post['TaxRate']) ? $post['TaxRate'] : NULL,
                'CompanyID'        => isset($post['CompanyID']) ? $post['CompanyID'] : NULL,
                'Currency'         => isset($post['Currency']) ? $post['Currency'] : 'LKR',
                'AccountsReceivable' => isset($post['AccountsReceivable']) ? $post['AccountsReceivable'] : NULL,
                'PaymentTerms'     => isset($post['PaymentTerms']) ? $post['PaymentTerms'] : 'Due on Receipt',
                'EnablePortal'     => isset($post['EnablePortal']) ? intval($post['EnablePortal']) : 0
            ];

            $this->db->trans_begin();

            if ($_id)
            {
                $this->model->update($_id, $customer_data);
                $customerID = $_id;
            }
            else
            {
                $customer_data['CreatedDate'] = date("Y-m-d H:i:s");
                $this->model->insert($customer_data);
                $customerID = $this->db->insert_id();
            }

            // Save Additional Domains (Removed from interface, so database updates bypassed here)
            /*
            $this->db->where('CustomerID', $customerID)->delete('customer_domains');
            $domains_post = $this->input->post('domains');
            if (!empty($domains_post) && is_array($domains_post))
            {
                foreach ($domains_post as $d_data)
                {
                    $domainName = trim($d_data['DomainName']);
                    if (empty($domainName)) continue;
                    
                    if (strtolower($domainName) == strtolower(trim($post['DomainName']))) continue;

                    $this->db->insert('customer_domains', [
                        'CustomerID'       => $customerID,
                        'DomainName'       => $domainName,
                        'ExpiryDate'       => !empty($d_data['ExpiryDate']) ? $d_data['ExpiryDate'] : NULL,
                        'AnnualFee'        => !empty($d_data['AnnualFee']) ? floatval($d_data['AnnualFee']) : 0.00,
                        'Status'           => !empty($d_data['Status']) ? $d_data['Status'] : 'Active'
                    ]);
                }
            }
            */

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => "<strong>" . (!$_id ? "Creation." : "Update.") . " Failure !!! </strong>"
                ]);
            }
            else
            {
                $this->db->trans_commit();
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => "<strong>Customer Successfully " . (!$_id ? "Added." : "Updated.") . "</strong>"
                ]);
            }
            redirect(current_url());
        }
        $this->view($d);
    }

    function edit($_id = 0)
    {
        if ($_id) {
            $obj = $this->model->get($_id);
            $domains = $this->db->where('CustomerID', $_id)->get('customer_domains')->result();

            $obj->AdditionalDomains = '';
            foreach($domains as $domain)
            {
                if(strtolower(trim($domain->DomainName)) != strtolower(trim($obj->DomainName)))
                {
                    $obj->AdditionalDomains .= $domain->DomainName . "\n";
                }
            }
            $renewals = $this->db
                ->select('
                    cp.PaymentID,
                    cp.PaymentDate AS RenewalDate,
                    cp.PaidAmount AS AmountPaid,
                    cp.PaymentMethod,
                    cp.ChequeNumber,
                    cp.ChequeDate,
                    cp.CreatedDate,
                    b.BankName,
                    sr.OldExpiryDate,
                    sr.NewExpiryDate
                ')
                ->from('customer_payments cp')
                ->join('banks b', 'b.BankID = cp.BankID', 'left')
                ->join('subscription_renewals sr', 'sr.CustomerId = cp.CustomerId AND sr.RenewalDate = DATE(cp.PaymentDate) AND sr.AmountPaid = cp.PaidAmount', 'left')
                ->where('cp.CustomerId', $_id)
                ->where('cp.IsDeleted', 0)
                ->order_by('cp.PaymentDate', 'DESC')
                ->order_by('cp.PaymentID', 'DESC')
                ->get()
                ->result();

            $d = [
                'page' => "$this->page/form",
                'obj' => $obj,
                'CustomerNo' => $obj->CustomerNo,
                'renewals' => $renewals
            ];
            $this->_form($d, $_id);
        } else {
            $this->index();
        }
    }

    function index()
    {
        $records = $this->model->get_all();

        // Fetch additional domains and group them by CustomerID
        $additional_domains = $this->db->get('customer_domains')->result();
        $domains_by_customer = [];
        foreach ($additional_domains as $d_row) {
            $domains_by_customer[$d_row->CustomerID][] = $d_row;
        }

        // Attach additional domains and latest renewal invoice ID to each record
        foreach ($records as $row) {
            $row->AdditionalDomains = [];
            $row->AdditionalDomainsDetail = [];
            if (isset($domains_by_customer[$row->CustomerId])) {
                foreach ($domains_by_customer[$row->CustomerId] as $d_obj) {
                    $row->AdditionalDomains[] = $d_obj->DomainName;
                    $row->AdditionalDomainsDetail[] = $d_obj;
                }
            }
            
            $row->LatestSaleID = null;
            if ($row->SubscriptionStatus == 'Sent renewal invoice') {
                $last_sale = $this->db
                    ->select('SaleID')
                    ->where('CustomerId', $row->CustomerId)
                    ->where('IsDeleted', 0)
                    ->order_by('SaleID', 'DESC')
                    ->limit(1)
                    ->get('sales')
                    ->row();
                if ($last_sale) {
                    $row->LatestSaleID = $last_sale->SaleID;
                }
            }
        }

        $d = [
            'page' => "$this->page/index",
            'records' => $records
        ];
        $this->view($d);
    }

    function delete($_id = 0)
    {
        if ($_id) {
            $this->model->delete($_id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('notification', [
                    "alert" => "success",
                    "text" => '<strong>Record Deleted Successfully.</strong>'
                ]);
            } else {
                $this->session->set_flashdata('notification', [
                    "alert" => "danger",
                    "text" => '<strong>Failed to Delete Record or Record Not Found.</strong>'
                ]);
            }
            redirect(base_url("admin/$this->controller"));
        } else {
            $this->index();
        }
    }

    public function get_customer_details()
    {
        $customerId = $this->input->post('customerId');
        if ($customerId) {
            $details = $this->model->get_customer_details($customerId);
            echo json_encode($details);
        } else {
            echo json_encode(['error' => 'Invalid Customer ID']);
        }
    }


    public function renew_subscription()
    {
        $customerId = $this->input->post('customer_id');
        $newExpiry = $this->input->post('new_expiry');
        $renewalDate = $this->input->post('renewal_date');
        $amountPaid = $this->input->post('amount_paid');
        $paymentMethod = $this->input->post('payment_method');
        
        if($customerId && $newExpiry) {
            // Update customer expiry
            $this->db->where('CustomerId', $customerId);
            $this->db->update('customer', [
                'ServiceExpiry' => $newExpiry,
                'SubscriptionStatus' => 'Active',
                'LastRenewalDate' => $renewalDate
            ]);
            
            // Log renewal transaction (optional - create a renewals table)
            $this->db->insert('subscription_renewals', [
                'CustomerId' => $customerId,
                'OldExpiryDate' => $this->input->post('current_expiry'),
                'NewExpiryDate' => $newExpiry,
                'RenewalDate' => $renewalDate,
                'AmountPaid' => $amountPaid,
                'PaymentMethod' => $paymentMethod,
                'CreatedDate' => date('Y-m-d H:i:s')
            ]);
            
            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text' => '<strong>Subscription renewed successfully!</strong> New expiry date: ' . date('d-m-Y', strtotime($newExpiry))
            ]);
        } else {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text' => '<strong>Renewal failed!</strong> Please try again.'
            ]);
        }
        
        redirect('admin/customer');
    }

    public function subscription_notice($customerId)
    {
        $customer = $this->model->get_by_id($customerId);
        if (!$customer) show_404();

        // Fetch additional domains
        $domains = $this->db->where('CustomerID', $customerId)->get('customer_domains')->result();
        $customer->AdditionalDomains = [];
        $customer->AdditionalDomainsDetail = $domains;
        foreach($domains as $d) {
            $customer->AdditionalDomains[] = $d->DomainName;
        }

        // Fetch unpaid renewal domains
        $unpaid_domains = [];
        $unpaid_sales = $this->db
            ->select('SaleID, TotalSaleAmount, RenewalDomain')
            ->where('CustomerId', $customerId)
            ->where('RenewalDomain IS NOT NULL')
            ->where('IsDeleted', 0)
            ->get('sales')
            ->result();
        foreach ($unpaid_sales as $sale) {
            $paid = $this->db->select_sum('PaidAmount')
                ->where('SaleID', $sale->SaleID)
                ->where('IsDeleted', 0)
                ->get('customer_payments')
                ->row()->PaidAmount;
            $paid = $paid ?: 0;
            if ($paid < $sale->TotalSaleAmount) {
                $normDomain = ltrim(strtolower($sale->RenewalDomain), 'w.');
                $normDomain = preg_replace('/^www\./i', '', $normDomain);
                $unpaid_domains[] = $normDomain;
            }
        }
        $customer->UnpaidInvoiceDomains = $unpaid_domains;

        // Fetch default amount from the last payment
        $last_payment = $this->db
            ->where('CustomerId', $customerId)
            ->where('IsDeleted', 0)
            ->order_by('PaymentDate', 'DESC')
            ->limit(1)
            ->get('customer_payments')
            ->row();

        // Fetch saved subscription notice settings if any
        $saved_notice = $this->db->where('CustomerID', $customerId)->get('customer_subscription_notices')->row();

        $data['customer'] = $customer;
        $data['default_amount'] = $last_payment ? $last_payment->PaidAmount : 24400.00;
        $data['saved_notice'] = $saved_notice;
        $data['banks'] = $this->db->where('IsDeleted', 0)->get('banks')->result();
        $this->load->view('admin/pages/customer/subscription_notice', $data);
    }

    public function save_subscription_notice()
    {
        $customerId = $this->input->post('customer_id');
        $invoiceNo = $this->input->post('invoice_no');
        $invoiceDate = $this->input->post('invoice_date');
        $toDomain = $this->input->post('to_domain');
        $detailDesc = $this->input->post('detail_desc');
        $period = $this->input->post('period');
        $amount = floatval($this->input->post('amount'));
        $saveInvoice = intval($this->input->post('save_invoice'));
        $bankId = $this->input->post('bank_id') ? intval($this->input->post('bank_id')) : 4;

        if (!$customerId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Customer ID']);
            return;
        }

        // 1. Save or update subscription notice details
        $exists = $this->db->where('CustomerID', $customerId)->get('customer_subscription_notices')->row();
        $noticeData = [
            'CustomerID' => $customerId,
            'InvoiceNo' => $invoiceNo,
            'InvoiceDate' => $invoiceDate,
            'ToDomain' => $toDomain,
            'DetailDesc' => $detailDesc,
            'RenewalPeriod' => $period,
            'Amount' => $amount,
            'BankID' => $bankId,
            'UpdatedAt' => date('Y-m-d H:i:s')
        ];

        if ($exists) {
            $this->db->where('CustomerID', $customerId)->update('customer_subscription_notices', $noticeData);
        } else {
            $this->db->insert('customer_subscription_notices', $noticeData);
        }

        // 2. Save records to invoice if save_invoice is 1
        if ($saveInvoice === 1) {
            $user = $this->session->userdata('user');
            $userId = (is_object($user) && isset($user->UserId)) ? $user->UserId : 1;

            $saleDate = date('Y-m-d H:i:s');
            if ($invoiceDate) {
                $parsedDate = strtotime($invoiceDate);
                if ($parsedDate !== false) {
                    $saleDate = date('Y-m-d H:i:s', $parsedDate);
                }
            }

            $saleData = [
                'UserId'          => $userId,
                'CustomerId'      => $customerId,
                'SaleDate'        => $saleDate,
                'PONo'            => NULL,
                'RefNo'           => $invoiceNo,
                'JobCardID'       => NULL,
                'TotalDiscount'   => 0.00,
                'TotalSaleAmount' => $amount,
                'IsDeleted'       => 0,
                'RenewalDomain'   => $toDomain,
                'CreatedAt'       => date('Y-m-d H:i:s'),
                'UpdatedAt'       => date('Y-m-d H:i:s')
            ];

            $this->db->insert('sales', $saleData);
            $saleID = $this->db->insert_id();

            $this->db->insert('sale_items', [
                'SaleID'          => $saleID,
                'ItemID'          => 17, // "Renewal Domain and Hosting"
                'Quantity'        => 1.00,
                'QtyType'         => 'Pcs',
                'SalePrice'       => $amount,
                'DiscountPercent' => 0.00,
                'DiscountAmount'  => 0.00,
                'Discount'        => 0.00,
                'TotalAmount'     => $amount,
                'CategoryID'      => 0,
                'BrandID'         => 0
            ]);

            // Update customer status to 'Sent renewal invoice'
            $this->db->where('CustomerId', $customerId)->update('customer', [
                'SubscriptionStatus' => 'Sent renewal invoice'
            ]);
        }

        echo json_encode(['status' => 'success']);
    }


    public function send_whatsapp_reminder($customerId = 0) {
        if (!$customerId) {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text' => '<strong>Error:</strong> Invalid Customer ID.'
            ]);
            redirect('admin/customer');
            return;
        }

        $row = $this->db->where('CustomerId', $customerId)->where('IsDeleted', 0)->get('customer')->row();
        if (!$row) {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text' => '<strong>Error:</strong> Customer not found.'
            ]);
            redirect('admin/customer');
            return;
        }

        $to = trim($row->WhatsAppNo);
        if (empty($to)) {
            $to = trim($row->MobileNo);
        }
        
        // Sanitize phone number (keep only digits)
        $to = preg_replace('/[^0-9]/', '', $to);
        
        // If it starts with 0 and has 10 digits, change to 94771234567 (Sri Lanka country code)
        if (strlen($to) == 10 && $to[0] == '0') {
            $to = '94' . substr($to, 1);
        }

        if (empty($to)) {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text' => '<strong>Error:</strong> Customer has no mobile/WhatsApp number.'
            ]);
            redirect('admin/customer');
            return;
        }

        $this->config->load('whatsapp');
        $access_token = $this->config->item('whatsapp_access_token');
        $phone_number_id = $this->config->item('whatsapp_phone_number_id');
        $template_name = $this->config->item('whatsapp_template_name');
        $language_code = $this->config->item('whatsapp_language_code') ?: 'en_US';

        if ($access_token === 'YOUR_ACCESS_TOKEN' || $phone_number_id === 'YOUR_PHONE_NUMBER_ID') {
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text' => '<strong>Error:</strong> WhatsApp API credentials not configured.'
            ]);
            redirect('admin/customer');
            return;
        }

        $url = "https://graph.facebook.com/v18.0/{$phone_number_id}/messages";
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $template_name,
                'language' => [
                    'code' => $language_code
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $row->CompanyName ?: $row->CustomerName
                            ],
                            [
                                'type' => 'text',
                                'text' => $row->DomainName ?: '-'
                            ],
                            [
                                'type' => 'text',
                                'text' => $row->ServiceExpiry
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err = curl_error($ch);
        curl_close($ch);

        $res_decoded = json_decode($response, true);

        if ($http_code >= 200 && $http_code < 300) {
            $this->db->where('CustomerId', $row->CustomerId);
            $this->db->update('customer', ['RenewalNotified' => 1]);
            
            $log_entry = date('Y-m-d H:i:s') . " - WhatsApp sent manually to {$to} (Cust ID: {$row->CustomerId})\n";
            file_put_contents(FCPATH . 'cron_log.txt', $log_entry, FILE_APPEND);
            
            $this->session->set_flashdata('notification', [
                'alert' => 'success',
                'text' => '<strong>WhatsApp message sent successfully to ' . ($row->CompanyName ?: $row->CustomerName) . '!</strong>'
            ]);
        } else {
            $err_msg = isset($res_decoded['error']['message']) ? $res_decoded['error']['message'] : 'Meta API Connection Error';
            
            $log_entry = date('Y-m-d H:i:s') . " - WhatsApp Manual FAILED for Cust ID: {$row->CustomerId}. Response: {$response}. Error: {$curl_err}\n";
            file_put_contents(FCPATH . 'cron_log.txt', $log_entry, FILE_APPEND);
            
            $this->session->set_flashdata('notification', [
                'alert' => 'danger',
                'text' => '<strong>Failed to send WhatsApp message.</strong> Error: ' . $err_msg
            ]);
        }

        redirect('admin/customer');
    }

}
?>