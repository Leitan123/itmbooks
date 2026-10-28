<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order - <?= $po->PurchaseOrderNo ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 30px;
            font-size: 14px;
            line-height: 1.6;
        }
        .print-btn {
            background-color: #1abc9c;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .header-table td {
            vertical-align: top;
            border: none;
        }
        .company-title {
            font-size: 24px;
            font-weight: bold;
            color: #2f3d4f;
            margin: 0 0 5px 0;
        }
        .doc-title {
            font-size: 28px;
            font-weight: 800;
            color: #1abc9c;
            text-align: right;
            text-transform: uppercase;
            margin: 0 0 10px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-table td {
            width: 50%;
            vertical-align: top;
            border: none;
        }
        .section-heading {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #2f3d4f;
            color: white;
            text-align: left;
            padding: 10px 12px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals-table {
            width: 350px;
            float: right;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals-table tr:last-child td {
            border-bottom: none;
            font-size: 16px;
            font-weight: bold;
            color: #1abc9c;
        }
        .remarks-box {
            clear: both;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            background: #f8fafc;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print();"><i class="fa fa-print"></i> Print Purchase Order</button>

    <!-- Header Grid -->
    <table class="header-table">
        <tr>
            <td>
                <h1 class="company-title"><?= isset($company->CompanyName) ? $company->CompanyName : 'IT MARTX' ?></h1>
                <p style="margin: 0; color: #64748b;">
                    <?= isset($company->Address) ? nl2br($company->Address) : '' ?><br>
                    Tel: <?= isset($company->Telephone) ? $company->Telephone : '' ?><br>
                    Email: <?= isset($company->Email) ? $company->Email : '' ?>
                </p>
            </td>
            <td style="text-align: right;">
                <h2 class="doc-title">Purchase Order</h2>
                <p style="margin: 0; color: #64748b;">
                    <strong>PO Number:</strong> <?= $po->PurchaseOrderNo ?><br>
                    <strong>Date:</strong> <?= date('Y-m-d', strtotime($po->PurchaseOrderDate)) ?><br>
                    <strong>Status:</strong> <?= $po->Status ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- Billing Info Grid -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-heading">Vendor / Supplier</div>
                <strong style="font-size: 16px; color: #0f172a;"><?= $po->VendorName ?></strong>
                <?php if(!empty($po->CompanyName)){ ?>
                    <br><span style="color: #64748b;"><?= $po->CompanyName ?></span>
                <?php } ?>
                <p style="margin: 5px 0 0 0; color: #475569;">
                    <?= nl2br($po->Address ?? '') ?><br>
                    Tel: <?= $po->MobileNo ?><br>
                    Email: <?= $po->EmailId ?>
                </p>
            </td>
            <td>
                <div class="section-heading">Deliver To</div>
                <strong style="font-size: 16px; color: #0f172a;"><?= isset($company->CompanyName) ? $company->CompanyName : 'IT MARTX' ?></strong>
                <p style="margin: 5px 0 0 0; color: #475569;">
                    <?= isset($company->Address) ? nl2br($company->Address) : '' ?><br>
                    Tel: <?= isset($company->Telephone) ? $company->Telephone : '' ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- Items Grid -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Item / Description</th>
                <th style="text-align: center;" width="100">Qty</th>
                <th style="text-align: right;" width="150">Unit Price (Rs.)</th>
                <th style="text-align: right;" width="150">Total (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $k => $item): ?>
            <tr>
                <td><?= $k + 1 ?></td>
                <td>
                    <strong><?= $item->ItemName ?></strong>
                    <?php if(!empty($item->ItemCode)){ ?>
                        <br><small style="color: #64748b;">Code: <?= $item->ItemCode ?></small>
                    <?php } ?>
                    <?php if(!empty($item->Description)){ ?>
                        <br><small style="color: #64748b;">Desc: <?= nl2br(htmlspecialchars($item->Description)) ?></small>
                    <?php } ?>
                </td>
                <td style="text-align: center;"><?= number_format($item->Qty, 2) ?></td>
                <td style="text-align: right;"><?= number_format($item->UnitPrice, 2) ?></td>
                <td style="text-align: right; font-weight: 600;"><?= number_format($item->TotalAmount, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Totals Block -->
    <table class="totals-table">
        <tr>
            <td style="color: #64748b;">Sub Total</td>
            <td style="text-align: right; font-weight: 600;">Rs. <?= number_format($po->SubTotal, 2) ?></td>
        </tr>
        <tr>
            <td style="color: #64748b;">Discount</td>
            <td style="text-align: right; color: #ef4444;">Rs. <?= number_format($po->DiscountAmount, 2) ?></td>
        </tr>
        <tr>
            <td>Grand Total</td>
            <td style="text-align: right;">Rs. <?= number_format($po->GrandTotal, 2) ?></td>
        </tr>
    </table>

    <!-- Remarks Block -->
    <?php if(!empty($po->Remarks)): ?>
    <div class="remarks-box">
        <div style="font-weight: bold; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 5px;">Remarks / Terms</div>
        <?= nl2br(htmlspecialchars($po->Remarks)) ?>
    </div>
    <?php endif; ?>

</body>
</html>
