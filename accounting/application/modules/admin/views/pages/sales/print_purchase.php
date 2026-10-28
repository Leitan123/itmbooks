<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .invoice-box {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-box table {
            width: 100%;
            line-height: 24px;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-box table td { padding: 8px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: left; }
        .invoice-box table tr td:nth-child(4),
        .invoice-box table tr td:nth-child(5),
        .invoice-box table tr td:nth-child(6) { text-align: right; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 2px solid #ddd; font-weight: bold; text-align: center; }
        .invoice-box table tr.item td { border-bottom: 1px solid #ddd; }
        .invoice-box table tr.total td { font-weight: bold; border-top: 2px solid #eee; }
        .print-button { display: block; width: 100%; text-align: center; margin: 20px 0; }
        .print-button button { background: #007bff; color: #fff; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 5px; }
        .print-button button:hover { background: #0056b3; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="6">
                <table>
                    <tr>
                        <td class="title" style="font-size: 24px; font-weight: bold;">
                            Vendor Name: <?= isset($purchases->VendorName) ? $purchases->VendorName : 'N/A' ?>
                        </td>
                        <td style="text-align: right;">
                            P.O No. #: <?= isset($purchases->PurchaseID) ? $purchases->PurchaseID : 'N/A' ?><br>
                            Date: <?= isset($purchases->PurchaseDate) ? date('d M Y', strtotime($purchases->PurchaseDate)) : 'N/A' ?><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="6">
                <p style="font-size: 14px; margin: 10px 0;">
                    IT MARTX (Pvt) Ltd<br>
                    Contact No: +94 112 323 224 /  +94 702 662 274
                </p>
            </td>
        </tr>

        <tr class="heading">
            <td>No</td>
            <td>Description</td>
            <td>Qty</td>
            <td>Unit Price</td>
            <td>Discount</td>
            <td>Amount</td>
        </tr>

        <?php if (!empty($purchases->items)): ?>
            <?php $totalAmount = 0; ?>
            <?php foreach ($purchases->items as $i => $item): ?>
                <tr class="item">
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($item->ItemName) ?></td>
                    <td><?= $item->Quantity ?> <?= $item->QtyType ?></td>
                    <td><?= number_format($item->PurchasePrice, 2) ?></td>
                    <td><?= isset($item->Discount) ? number_format($item->Discount, 2) : '0.00' ?></td>
                    <td>
                        <?php 
                            $amount = ($item->Quantity * $item->PurchasePrice) - (isset($item->Discount) ? $item->Discount : 0);
                            $totalAmount += $amount; 
                            echo number_format($amount, 2);
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No items found.</td>
            </tr>
        <?php endif; ?>

        <tr class="total">
            <td colspan="5" style="text-align: right;">Total Amount:</td>
            <td style="text-align: right;"><?= number_format($totalAmount, 2) ?></td>
        </tr>

    </table>
</div>

<div class="print-button">
    <button onclick="window.print()">Print</button>
</div>
</body>
</html>
