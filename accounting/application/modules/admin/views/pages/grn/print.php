<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Goods Received Note - <?= $grn->GrnNo ?></title>
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
        .remarks-box {
            clear: both;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            background: #f8fafc;
            margin-top: 30px;
        }
        .signature-table {
            width: 100%;
            margin-top: 60px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 33.3%;
            text-align: center;
            border: none;
            padding-top: 50px;
        }
        .sig-line {
            width: 150px;
            border-bottom: 1px solid #cbd5e1;
            margin: 0 auto 5px auto;
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print();"><i class="fa fa-print"></i> Print GRN</button>

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
                <h2 class="doc-title" style="color: #ea580c;">Goods Received Note</h2>
                <p style="margin: 0; color: #64748b;">
                    <strong>GRN Number:</strong> <?= $grn->GrnNo ?><br>
                    <strong>Date:</strong> <?= date('Y-m-d', strtotime($grn->GrnDate)) ?><br>
                    <strong>PO Reference:</strong> <?= !empty($grn->PurchaseOrderNo) ? $grn->PurchaseOrderNo : 'N/A' ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- Billing Info Grid -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-heading">Received From (Vendor)</div>
                <strong style="font-size: 16px; color: #0f172a;"><?= $grn->VendorName ?></strong>
                <?php if(!empty($grn->CompanyName)){ ?>
                    <br><span style="color: #64748b;"><?= $grn->CompanyName ?></span>
                <?php } ?>
                <p style="margin: 5px 0 0 0; color: #475569;">
                    <?= nl2br($grn->Address ?? '') ?><br>
                    Tel: <?= $grn->MobileNo ?><br>
                    Email: <?= $grn->EmailId ?>
                </p>
            </td>
            <td>
                <div class="section-heading">Warehouse / Delivery Destination</div>
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
                <th>Item Code</th>
                <th>Item / Description</th>
                <th style="text-align: center;" width="150">Quantity Received</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $k => $item): ?>
            <tr>
                <td><?= $k + 1 ?></td>
                <td><strong><?= !empty($item->ItemCode) ? $item->ItemCode : '<span style="color: #94a3b8;">None</span>' ?></strong></td>
                <td>
                    <strong><?= $item->ItemName ?></strong>
                    <?php if(!empty($item->Description)){ ?>
                        <br><span style="font-size: 12px; color: #64748b; font-style: italic;"><?= nl2br(htmlspecialchars($item->Description)) ?></span>
                    <?php } ?>
                </td>
                <td style="text-align: center; font-weight: bold; color: #ea580c;"><?= number_format($item->QtyReceived, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Remarks Block -->
    <?php if(!empty($grn->Remarks)): ?>
    <div class="remarks-box">
        <div style="font-weight: bold; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 5px;">Remarks / Delivery Notes</div>
        <?= nl2br(htmlspecialchars($grn->Remarks)) ?>
    </div>
    <?php endif; ?>

    <!-- Signature Fields -->
    <table class="signature-table">
        <tr>
            <td>
                <div class="sig-line"></div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">Prepared By</div>
            </td>
            <td>
                <div class="sig-line"></div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">Store Keeper</div>
            </td>
            <td>
                <div class="sig-line"></div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">Authorized Receiver</div>
            </td>
        </tr>
    </table>

</body>
</html>
