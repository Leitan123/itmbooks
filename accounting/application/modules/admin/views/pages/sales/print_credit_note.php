<!DOCTYPE html>
<html>
<head>
    <title>Credit Note - <?= isset($sale->SaleID) ? $sale->SaleID : 'N/A' ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

<h2>Credit Note</h2>

<table>
    <tr>
        <td>Credit Note No:</td>
        <td><?= isset($credit_note->CreditNoteNo) ? $credit_note->CreditNoteNo : 'N/A' ?></td>
    </tr>
    <tr>
        <td>Credit Date:</td>
        <td><?= isset($credit_note->CreditDate) ? $credit_note->CreditDate : date('Y-m-d') ?></td>
    </tr>
    <tr>
        <td>Invoice No:</td>
        <td><?= isset($sale->SaleID) ? $sale->SaleID : 'N/A' ?></td>
    </tr>
    <tr>
        <td>Sale Date:</td>
        <td><?= isset($sale->SaleDate) ? $sale->SaleDate : 'N/A' ?></td>
    </tr>
    <tr>
        <td>Customer Name:</td>
        <td><?= isset($sale->CustomerName) ? $sale->CustomerName : 'N/A' ?></td>
    </tr>
    <tr>
        <td>Total Sale Amount:</td>
        <td class="text-right"><?= isset($sale->TotalSaleAmount) ? number_format($sale->TotalSaleAmount, 2) : '0.00' ?></td>
    </tr>
    <tr>
        <td>Credit Amount:</td>
        <td class="text-right"><?= isset($credit_note->CreditAmount) ? number_format($credit_note->CreditAmount, 2) : '0.00' ?></td>
    </tr>
    <tr>
        <td>Reason:</td>
        <td><?= isset($credit_note->Reason) ? $credit_note->Reason : '-' ?></td>
    </tr>
</table>

<br /><br />

<p>Authorized By: ___________________________</p>
<p>Date: <?= date('Y-m-d') ?></p>

</body>
</html>
