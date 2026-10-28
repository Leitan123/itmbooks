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
    <title><?= !empty($company->CompanyName) ? htmlspecialchars($company->CompanyName) : 'IT MARTX (Pvt) Ltd' ?> - Sales Invoice</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 210mm;
            margin: 20px auto;
            padding: 20px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 120px;
            height: auto;
        }
        .header h1 {
            font-size: 24px;
            margin: 10px 0;
        }
        .header p {
            font-size: 14px;
            margin: 3px 0;
        }
        .invoice-type {
            font-weight: bold;
            font-size: 18px;
            color: #007bff;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .details div p {
            margin: 3px 0;
            font-size: 14px;
        }
        .details span {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        th {
            background-color: #f4f4f4;
        }
        td {
            background-color: #fff;
        }
        .text-right {
            text-align: right;
        }
        .total-table {
            width: 50%;
            float: right;
            margin-bottom: 40px;
        }
        .total-table td {
            font-size: 16px;
            padding: 5px 10px;
        }
        .footer-note {
            clear: both;
            margin-top: 50px;
            font-size: 12px;
            text-align: left;
            color: #333;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
            font-size: 12px;
        }
        .signatures td {
            padding-top: 30px;
            text-align: center;
        }
        @media print {
            button { display: none; }
            .container {
                border: none;
                background: #fff;
                padding: 10mm;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <img src="<?= $logo_url ?>" alt="Logo" style="max-height: 80px; object-fit: contain;">
        <h1><?= !empty($company->CompanyName) ? htmlspecialchars($company->CompanyName) : 'IT MARTX (Pvt) Ltd' ?></h1>
        <p><?= !empty($company->Address) ? nl2br(htmlspecialchars($company->Address)) : '#22B Quarry Road, Colombo - 12' ?><br>Contact No: <?= !empty($company->Telephone) ? htmlspecialchars($company->Telephone) : '+94 112 323 224 / +94 702 662 274' ?></p>
        <p class="invoice-type">Sales Invoice</p>
    </div>

    <div class="details">
        <div>
            <p><span>Invoice No.:</span> <?= $sales->SaleID ?></p>
            <p><span>Ref No.:</span> <?= $sales->RefNo ?></p>
            <p><span>PO No.:</span> <?= $sales->PONo ?></p>
            <p><span>Date:</span> <?= date('d M Y', strtotime($sales->SaleDate)) ?></p>
            <p><span>Customer:</span> <?= $sales->CustomerName ?></p>
            <p><span>Prepared By:</span> <?= !empty($sales->UserName) ? $sales->UserName : 'N/A' ?></p>

        </div>
    </div>

    <?php
        $subTotal = 0;
        $totalDiscount = 0;
        $finalTotal = 0;

        $hasItemsObj = isset($items) && is_array($items);
        $loopCount = $hasItemsObj ? count($items) : 0;
        if (!$hasItemsObj) {
            $itemNames = explode(',', $sales->ItemNames);
            $quantities = explode(',', $sales->Quantities);
            $qtytypes = explode(',', $sales->QtyTypes);
            $prices = explode(',', $sales->Prices);
            $discounts = explode(',', $sales->Discounts);
            $loopCount = count($itemNames);
        }
    ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $loopCount; $i++): ?>
                <?php 
                    if ($hasItemsObj) {
                        $item = $items[$i];
                        $name = $item->ItemName;
                        $desc = $item->Description;
                        $qty = (float)$item->Quantity;
                        $price = (float)$item->SalePrice;
                        $qtyType = $item->QtyType;
                        $disc = (float)$item->DiscountAmount;
                    } else {
                        $name = trim($itemNames[$i]);
                        $desc = '';
                        $qty = (float)trim($quantities[$i]);
                        $price = (float)trim($prices[$i]);
                        $qtyType = $qtytypes[$i];
                        $disc = isset($discounts[$i]) ? (float)trim($discounts[$i]) : 0;
                    }

                    $amount = ($qty * $price);
                    $discountedAmount = $amount - $disc;

                    $subTotal += $amount;
                    $totalDiscount += $disc;
                    $finalTotal += $discountedAmount;
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <?php if (!empty($name)): ?>
                            <strong><?= htmlspecialchars($name) ?></strong>
                            <?php if (!empty($desc)): ?>
                                <br><span style="font-size: 12px; color: #555; white-space: pre-wrap;"><?= htmlspecialchars(trim($desc)) ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="white-space: pre-wrap;"><?= htmlspecialchars(trim($desc)) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?= $qty ?> <?= $qtyType ?></td>
                    <td class="text-right"><?= number_format($price, 2) ?></td>
                    <td class="text-right"><?= number_format($disc, 2) ?></td>
                    <td class="text-right"><?= number_format($discountedAmount, 2) ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <table class="total-table">
        <tr>
            <td class="text-right"><strong>Subtotal:</strong></td>
            <td class="text-right"><?= number_format($subTotal, 2) ?></td>
        </tr>
        <tr>
            <td class="text-right"><strong>Final Total:</strong></td>
            <td class="text-right"><?= number_format($finalTotal, 2) ?></td>
        </tr>
        <?php if (isset($sales->CashReceived) && $sales->CashReceived > 0): ?>
            <tr>
                <td class="text-right"><strong>Cash Received:</strong></td>
                <td class="text-right"><?= number_format($sales->CashReceived, 2) ?></td>
            </tr>
            <tr>
                <td class="text-right"><strong>Change / Balance:</strong></td>
                <td class="text-right"><?= number_format($sales->ChangeGiven, 2) ?></td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if (!empty($sales->Remarks)): ?>
        <div class="remarks-section" style="margin-top: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #ddd; font-size: 13px; clear: both; margin-bottom: 20px;">
            <div style="font-weight: bold; font-size: 12px; text-transform: uppercase; color: #555; margin-bottom: 6px;">Description / Remarks:</div>
            <div style="font-size: 13px; line-height: 1.5; color: #333; white-space: pre-wrap;"><?= htmlspecialchars($sales->Remarks) ?></div>
        </div>
    <?php endif; ?>

    <div class="footer-note" style="clear: both;">
        All cheques should be drawn in favour of <strong>Vagmi Creations (Pvt) Ltd</strong> Crossed A/C payee only.
    </div>

    <table class="signatures">
        <tr>
            <td>Authorized by</td>
            <td>Prepared by</td>
            <td>Checked by</td>
            <td>Received by</td>
        </tr>
    </table>

    <button onclick="window.print()">Print Invoice</button>

</div>

</body>
</html>
