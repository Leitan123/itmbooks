<!DOCTYPE html>
<html>
<head>
    <title>Invoice Aging Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin:20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border:1px solid #000; padding: 6px; text-align: center; font-size: 14px; }
        th { background: #f1f1f1; }
        .paid { background: #d4edda; }    /* Green for fully paid */
        .overdue { background: #f8d7da; } /* Red for due */
        .filter-box { margin-bottom: 15px; }
        .filter-box label { margin-right: 5px; font-weight: bold; }
        .filter-box input, .filter-box select { margin-right: 10px; }
        .label-paid { background:#28a745; color:#fff; padding:2px 6px; border-radius:4px; font-size:12px; }
    </style>
</head>
<body>

<h2>Invoice Aging Report</h2>

<?php if(isset($customer) && !empty($customer)): ?>
    <p><strong>Company Name:</strong> <?= htmlspecialchars(!empty($customer->CompanyName) ? $customer->CompanyName : $customer->CustomerName) ?></p>
<?php else: ?>
    <p><strong>Customer:</strong> All Customers</p>
<?php endif; ?>

<div class="filter-box">
    <form method="get" action="<?= base_url('admin/Customer_payments/invoice_aging') ?>">
        <label>Select Company:</label>
        <select name="customerId" id="customer_select">
            <option value="">-- All Customers --</option>
            <?php foreach($customers as $c): ?>
                <option value="<?= $c->CustomerId ?>" <?= (isset($customer) && $customer->CustomerId == $c->CustomerId) ? 'selected' : '' ?>>
                    <?= htmlspecialchars(!empty($c->CompanyName) ? $c->CompanyName : $c->CustomerName) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>From:</label>
        <input type="date" name="from" value="<?= isset($from) ? $from : '' ?>">

        <label>To:</label>
        <input type="date" name="to" value="<?= isset($to) ? $to : '' ?>">

        <button type="submit">Filter</button>
    </form>
</div>

<table id="agingTable">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Days Outstanding</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($records)): ?>
            <?php foreach($records as $r):
                $class = ($r->DueAmount <= 0) ? 'paid' : 'overdue';
                $status = ($r->DueAmount <= 0) ? '<span class="label-paid">PAID</span>' : 'DUE';
            ?>
            <tr class="<?= $class ?>">
                <td><?= $r->SaleID ?></td>
                <td><?= date("Y-m-d", strtotime($r->SaleDate)) ?></td>
                <td><?= number_format($r->TotalSaleAmount,2) ?></td>
                <td><?= number_format($r->PaidAmount,2) ?></td>
                <td><?= number_format($r->DueAmount,2) ?></td>
                <td><?= $r->DaysOutstanding ?></td>
                <td><?= $status ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No records found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Automatically submit form when customer changes
    $('#customer_select').change(function(){
        $(this).closest('form').submit();
    });
</script>

</body>
</html>
