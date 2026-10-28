<?php
$CI =& get_instance();
$CI->load->model('Company_model', 'print_company_model');
$company = $CI->print_company_model->get_company();
$logo_url = base_url("media/images/logo.png");
if (!empty($company) && !empty($company->Logo) && file_exists(FCPATH . 'media/img/' . $company->Logo)) {
    $logo_url = base_url('media/img/' . $company->Logo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($company->CompanyName) ? htmlspecialchars($company->CompanyName) : 'Payment Receipt' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 300px; /* Fixed width for receipt printers */
        }
        .container {
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }
        .header {
            margin-bottom: 10px;
        }
        .header img {
            max-width: 80px;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            margin: 2px 0;
        }
        .details, .footer {
            text-align: left;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .details .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .description {
            border-top: 1px dashed #333;
            border-bottom: 1px dashed #333;
            padding: 5px 0;
        }
        .description table {
            width: 100%;
            border-collapse: collapse;
        }
        .description table th, .description table td {
            text-align: left;
            padding: 2px 0;
            font-size: 12px;
        }
        .description table th {
            border-bottom: 1px solid #333;
        }
        .total-row {
            font-weight: bold;
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
        }
        button {
            margin-top: 10px;
            padding: 5px 10px;
            font-size: 12px;
        }
        /* Print Styles */
        @media print {
            button { display: none; }
            body, .container { width: 100%; margin: 0; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="<?= $logo_url ?>" alt="Company Logo" style="max-height: 60px; object-fit: contain;">
        <h1><?= !empty($company->CompanyName) ? htmlspecialchars($company->CompanyName) : 'IT MARTX (Pvt) Ltd' ?></h1>
        <p style="white-space: pre-line; margin: 5px 0;"><?= !empty($company->Address) ? htmlspecialchars($company->Address) : '#22B Quarry Road, Colombo - 12' ?></p>
        <p style="margin: 5px 0;">Contact: <?= !empty($company->Telephone) ? htmlspecialchars($company->Telephone) : '+94 112 323 224 / +94 702 662 274' ?></p>
        <p><strong>Receipt</strong></p>
    </div>

    <div class="details">
        <div class="row">
            <span>Receipt No.:</span> <span><?= $customer_payments->PaymentID; ?></span>
        </div>
        <div class="row">
            <span>Date:</span> <span><?= date('Y-m-d', strtotime($customer_payments->PaymentDate)); ?></span>
        </div>
        <div class="row">
            <span>Customer:</span> <span><?= $customer_payments->CustomerName; ?></span>
        </div>
        <div class="row">
            <span>Payment Method:</span> <span><?= $customer_payments->PaymentMethod; ?></span>
        </div>
        <div class="row">
            <span>Received By:</span> <span><?= isset($customer_payments->Username) ? $customer_payments->Username : '-' ?></span>
        </div>



    </div>

    <div class="description">
        <table>
            <tr>
                <th>Description</th>
                <th style="text-align:right;">Amount</th>
            </tr>
            <tr>
                <td>Paid Amount</td>
                <td style="text-align:right;"><?= number_format($customer_payments->PaidAmount, 2); ?></td>
            </tr>
        </table>
    </div>

    <div class="details">
        <div class="total-row">
            <span>Total Paid:</span> 
            <span><?= number_format($customer_payments->PaidAmount, 2); ?></span>
        </div>
        <div class="total-row">
            <span>Due Amount:</span> 
            <span><?= number_format($customer_payments->PaymentDue, 2); ?></span>
        </div>
    </div>

    <div class="footer">
        <p><strong>THANK YOU - COME AGAIN</strong></p>
    </div>

    <button onclick="window.print()">Print</button>
</div>

</body>
</html>
