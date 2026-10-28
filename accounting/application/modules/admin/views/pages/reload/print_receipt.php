<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Recharge Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 302px;
            margin: 0 auto;
            border: 0px solid #333;
            padding: 0px;
        }
        .header {
            text-align: center;
        }
        .header h1 {
            font-size: 21px;
            margin: 0;
        }
        .details {
            margin-top: 20px;
            border-top: 1px dashed #333;
            padding-top: 10px;
        }
        .details .row {
            margin: 5px 0;
            display: flex;
            justify-content: space-between; /* Space between label and value */
        }
        .details .row span {
            display: inline-block;
            width: 150px;
        }
        .details .row .value {
            text-align: right; /* Right align values */
        }
        .details .total-row {
            font-weight: bold; /* Bold the total amount */
        }
        .footer {
            margin-top: 20px;
            border-top: 1px dashed #333;
            padding-top: 10px;
            text-align: left;
            font-size: 12px;
        }

        /* Print Styles */
        @media print {
            body, .container {
                margin: 0;
                padding: 0;
                width: 100%; /* Full width in print */
                height: auto; /* Allow auto height for full print view */
            }
            .container {
                page-break-inside: avoid; /* Avoid breaking the container */
                margin: 0 auto; /* Center container */
            }
            .footer {
                page-break-after: always; /* Ensure footer ends the page */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>IT MARTX</h1>
        <p>#133, Negombo Road, Wattala, Sri Lanka.<br>
           TEL : 0112 981 118<br>
           HOT LINE : 0777 880 288<br>
           Whats App: 0763 331 222
       </p>
    </div>

    <hr>

    <p style="text-align: center;">Maintenance Recharge Invoice</p>

    <div class="details">
        <div class="row">
            <span>Invoice No.:</span> <span class="value"><?= $reload->ReloadId; ?></span>
        </div>
        <div class="row">
            <span>Date:</span> <span class="value"><?= date('Y-m-d'); ?></span>
        </div>

        <hr>

        <!-- Customer SID -->
        <div class="row">
            <span>Customer SID:</span> <span class="value"><?= $reload->Sid; ?></span> <!-- Assuming SID is fetched correctly from the customer table -->
        </div>
        <div class="row">
            <span>Customer Mobile:</span> <span class="value"><?= $reload->MobileNo; ?></span> <!-- Fetching MobileNo from the customer table -->
        </div>
        <div class="row">
            <span>Customer Type:</span> <span class="value"><?= $reload->Provider ?></span>
        </div>
        <div class="row">
            <span>Amount:</span> <span class="value"><?= number_format($reload->Package, 2) ?></span>
        </div>
        
        <div class="row">
            <span>Add-on:</span> <span class="value"><?= number_format($reload->AddOn, 2) ?></span>
        </div>
        <div class="row">
            <span>Service Charge:</span> <span class="value"><?= number_format($reload->ExtraCharge, 2) ?></span>
        </div>
        <hr>
        
        <!-- Bold total row -->
        <div class="row total-row">
            <span>Total Amount:</span> <span class="value"><?= number_format($reload->Package + $reload->AddOn + $reload->ExtraCharge, 2) ?></span>
        </div>
    </div>

    <div class="footer">
        <p>Note:<br>
        
        1. Additional Chennal must be used for 90days.<br/>
        2. Monthly Rental may be increased.<br/>
        3. Payment Valid Period is 30 days.<br/>
        4. Once Discounted. You may loose 5 days.<br/>
        5. Please check the bill.</p>
        <p>THANK YOU - COME AGAIN</p>

    </div>

    <button onclick="window.print()">Print</button>
</div>

</body>
</html>
