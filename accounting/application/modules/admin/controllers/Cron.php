<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function expiryReminder() {
        $this->load->model('reload_model', 'model');
        $data = $this->model->getExpiryRemider();
         $api_instance = new NotifyLk\Api\SmsApi();
$user_id = ""; 
$api_key = ""; 

$sender_id = ""; 
$contact_fname = ""; 
$contact_lname = "";  
$contact_email = "";  
$contact_address = "";  
$contact_group = 0;  
$type = null; 
try {
   
    foreach ($data as $row) {
        $message = "Dear Customer, your connection for the {$row->Provider} is going to expire on {$row->ExpiryON}. Please recharge before this date to avoid service interruption. call for recharge : 0768866972 / 0112339920 . Thank you for staying with us! 
"; 
$data = $this->model->UpdateExpiryStatus($row->ReloadId);
$mobile =$row->MobileNo;
if($mobile!= ''){
                    if ($mobile[0] === '0') {
    $mobile = "94".substr($mobile, 1);
}
}
$to = $mobile; 

    $api_instance->sendSMS($user_id, $api_key, $message, $to, $sender_id, $contact_fname, $contact_lname, $contact_email, $contact_address, $contact_group, $type);
        file_put_contents('/home/g45lpenyspna/public_html/itmartx.com/cron_log.txt', 'Cron executed on '.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
}
} catch (Exception $e) {
    echo 'Exception when calling SmsApi->sendSMS: ', $e->getMessage(), PHP_EOL;
}
    }

    public function whatsappExpiryReminder() {
        $this->config->load('whatsapp');
        $access_token = $this->config->item('whatsapp_access_token');
        $phone_number_id = $this->config->item('whatsapp_phone_number_id');
        $template_name = $this->config->item('whatsapp_template_name');
        $language_code = $this->config->item('whatsapp_language_code') ?: 'en_US';

        if ($access_token === 'YOUR_ACCESS_TOKEN' || $phone_number_id === 'YOUR_PHONE_NUMBER_ID') {
            echo "WhatsApp API configuration is not set. Please configure config/whatsapp.php" . PHP_EOL;
            return;
        }

        $warning_date = date('Y-m-d', strtotime('+7 days'));
        
        $this->db->select('CustomerId, CompanyName, CustomerName, MobileNo, WhatsAppNo, ServiceExpiry, DomainName');
        $this->db->from('customer');
        $this->db->where('IsDeleted', 0);
        $this->db->where('RenewalNotified', 0);
        $this->db->where('ServiceExpiry >', '2000-01-01');
        $this->db->where('ServiceExpiry <=', $warning_date);
        
        $customers = $this->db->get()->result();

        if (empty($customers)) {
            echo "No expiring customers found to notify." . PHP_EOL;
            return;
        }

        foreach ($customers as $row) {
            $to = trim($row->WhatsAppNo);
            if (empty($to)) {
                $to = trim($row->MobileNo);
            }
            
            // Sanitize phone number (keep only digits)
            $to = preg_replace('/[^0-9]/', '', $to);
            
            // If it starts with 0 and has 10 digits (e.g. 0771234567), change to 94771234567 (Sri Lanka country code)
            if (strlen($to) == 10 && $to[0] == '0') {
                $to = '94' . substr($to, 1);
            }

            if (empty($to)) {
                $log_entry = date('Y-m-d H:i:s') . " - WhatsApp skipped for Cust ID: {$row->CustomerId} (no phone number)\n";
                file_put_contents(FCPATH . 'cron_log.txt', $log_entry, FILE_APPEND);
                continue;
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

            if ($http_code >= 200 && $http_code < 300) {
                $this->db->where('CustomerId', $row->CustomerId);
                $this->db->update('customer', ['RenewalNotified' => 1]);
                $log_entry = date('Y-m-d H:i:s') . " - WhatsApp sent successfully to {$to} (Cust ID: {$row->CustomerId})\n";
                file_put_contents(FCPATH . 'cron_log.txt', $log_entry, FILE_APPEND);
                echo "Notified Customer ID {$row->CustomerId} ({$row->CompanyName}) via WhatsApp." . PHP_EOL;
            } else {
                $log_entry = date('Y-m-d H:i:s') . " - WhatsApp FAILED for Cust ID: {$row->CustomerId}. Response: {$response}. Error: {$curl_err}\n";
                file_put_contents(FCPATH . 'cron_log.txt', $log_entry, FILE_APPEND);
                echo "Failed to notify Customer ID {$row->CustomerId} via WhatsApp. HTTP Code: {$http_code}." . PHP_EOL;
            }
        }
    }

    public function test_whatsapp() {
        $this->config->load('whatsapp');
        $access_token = $this->config->item('whatsapp_access_token');
        $phone_id = $this->config->item('whatsapp_phone_number_id');
        $template = $this->config->item('whatsapp_template_name');

        if ($this->input->post()) {
            $token = $this->input->post('token');
            $phone_id = $this->input->post('phone_id');
            $template = $this->input->post('template');
            $to = $this->input->post('to');
            $lang = $this->input->post('lang') ?: 'en_US';
            
            // Sanitize phone number
            $to = preg_replace('/[^0-9]/', '', $to);
            if (strlen($to) == 10 && $to[0] == '0') {
                $to = '94' . substr($to, 1);
            }

            $url = "https://graph.facebook.com/v18.0/{$phone_id}/messages";
            
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $template,
                    'language' => [
                        'code' => $lang
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => 'Test Company'
                                ],
                                [
                                    'type' => 'text',
                                    'text' => 'test-domain.com'
                                ],
                                [
                                    'type' => 'text',
                                    'text' => date('Y-m-d')
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $headers = [
                "Authorization: Bearer " . $token,
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

            header('Content-Type: application/json');
            echo json_encode([
                'http_code' => $http_code,
                'response' => json_decode($response) ?: $response,
                'error' => $curl_err
            ]);
            return;
        }

        // Render a simple, premium HTML test form
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>WhatsApp Expiry Reminder Test Console</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background-color: #0f172a;
                    color: #e2e8f0;
                    margin: 0;
                    padding: 40px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                }
                .container {
                    background-color: #1e293b;
                    padding: 40px;
                    border-radius: 12px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                    width: 100%;
                    max-width: 600px;
                }
                h2 {
                    margin-top: 0;
                    color: #38bdf8;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 600;
                    font-size: 0.9rem;
                    color: #94a3b8;
                }
                input, textarea {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #334155;
                    background-color: #0f172a;
                    color: #e2e8f0;
                    border-radius: 6px;
                    box-sizing: border-box;
                    font-size: 0.95rem;
                }
                input:focus, textarea:focus {
                    outline: none;
                    border-color: #38bdf8;
                }
                button {
                    background-color: #0284c7;
                    color: white;
                    border: none;
                    padding: 14px 20px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 1rem;
                    width: 100%;
                    transition: background-color 0.2s;
                }
                button:hover {
                    background-color: #0369a1;
                }
                pre {
                    background-color: #0f172a;
                    padding: 15px;
                    border-radius: 6px;
                    overflow-x: auto;
                    font-size: 0.85rem;
                    border: 1px solid #334155;
                }
                .result-title {
                    margin-top: 30px;
                    font-weight: 600;
                    color: #38bdf8;
                }
            </style>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        </head>
        <body>
            <div class="container">
                <h2>WhatsApp API Test Console</h2>
                <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 30px;">
                    Fill in your Meta credentials below to send a test message to your phone number securely.
                </p>
                <form id="testForm">
                    <div class="form-group">
                        <label>Meta Access Token (starts with EAAC...)</label>
                        <textarea name="token" rows="3" required placeholder="Paste your Access Token here"><?= html_escape($access_token) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Phone Number ID</label>
                        <input type="text" name="phone_id" value="<?= html_escape($phone_id) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Approved Template Name</label>
                        <input type="text" name="template" value="<?= html_escape($template) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Recipient WhatsApp Number (e.g. 0773041287)</label>
                        <input type="text" name="to" value="0773041287" required>
                    </div>
                    <button type="submit" id="submitBtn">Send Test Message</button>
                </form>

                <div id="resultSection" style="display: none;">
                    <div class="result-title">API Response</div>
                    <pre id="apiResult"></pre>
                </div>
            </div>

            <script>
                $('#testForm').on('submit', function(e) {
                    e.preventDefault();
                    $('#submitBtn').text('Sending...').prop('disabled', true);
                    $('#resultSection').hide();

                    $.ajax({
                        url: '',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(res) {
                            $('#apiResult').text(JSON.stringify(res, null, 4));
                            $('#resultSection').show();
                        },
                        error: function(xhr, status, err) {
                            $('#apiResult').text("Error: " + err);
                            $('#resultSection').show();
                        },
                        complete: function() {
                            $('#submitBtn').text('Send Test Message').prop('disabled', false);
                        }
                    });
                });
            </script>
        </body>
        </html>
        <?php
    }
}
