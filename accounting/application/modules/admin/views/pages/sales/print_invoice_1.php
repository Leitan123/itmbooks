<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= isset($invoice->invoice_number) ? htmlspecialchars($invoice->invoice_number) : 'N/A' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: right;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .bill-to {
            margin: 20px 0;
        }
        .bill-to h2 {
            margin: 0;
            font-size: 16px;
        }
        .bill-to p {
            margin: 2px 0;
            font-size: 14px;
        }
        .invoice-details {
            text-align: right;
            margin: 20px 0;
        }
        .invoice-details p {
            margin: 5px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .items-table td {
            background-color: #fff;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals p {
            margin: 5px 0;
            font-size: 14px;
        }
        .totals .total-amount {
            font-size: 18px;
            font-weight: bold;
        }
        .notes {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        @media print {
            .container {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE</h1>
            <p># <?= isset($invoice->invoice_number) ? htmlspecialchars($invoice->invoice_number) : 'N/A' ?></p>
        </div>

        <!-- Bill To Section -->
        <div class="bill-to">
            <h2>Bill To</h2>
            <p><?= isset($invoice->customer_name) ? htmlspecialchars($invoice->customer_name) : 'N/A' ?></p>
            <p><?= isset($invoice->customer_address) ? htmlspecialchars($invoice->customer_address) : 'N/A' ?></p>
            <p><?= isset($invoice->customer_location) ? htmlspecialchars($invoice->customer_location) : 'N/A' ?></p>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <p><strong>Invoice Date:</strong> <?= isset($invoice->invoice_date) ? htmlspecialchars($invoice->invoice_date) : 'N/A' ?></p>
            <p><strong>Terms:</strong> <?= isset($invoice->terms) ? htmlspecialchars($invoice->terms) : 'N/A' ?></p>
            <p><strong>Due Date:</strong> <?= isset($invoice->due_date) ? htmlspecialchars($invoice->due_date) : 'N/A' ?></p>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item & Description</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalAmount = 0;
                if (!empty($invoice->items)): 
                    foreach ($invoice->items as $index => $item): 
                        $itemAmount = $item->quantity * $item->rate;
                        $totalAmount += $itemAmount;
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item->description) ?></td>
                        <td><?= number_format($item->quantity, 2) ?></td>
                        <td><?= number_format($item->rate, 2) ?></td>
                        <td><?= number_format($itemAmount, 2) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals">
            <p><strong>Sub Total:</strong> LKR <?= number_format($totalAmount, 2) ?></p>
            <p class="total-amount"><strong>Total:</strong> LKR <?= number_format($totalAmount, 2) ?></p>
            <p><strong>Balance Due:</strong> LKR <?= number_format($totalAmount, 2) ?></p>
        </div>

        <!-- Notes -->
        <div class="notes">
            <p><?= isset($invoice->notes) ? htmlspecialchars($invoice->notes) : 'Thanks for your business.' ?></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>ITM, Sri Lanka | <?= isset($invoice->email) ? htmlspecialchars($invoice->email) : 'contact@itmartx.com' ?></p>
        </div>
    </div>
</body>
</html>
