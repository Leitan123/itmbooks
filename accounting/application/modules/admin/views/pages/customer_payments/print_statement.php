<tbody>
<?php 
$total_inv  = 0;
$total_paid = 0;
$total_due  = 0;
?>

<?php if (!empty($records)): ?>
    <?php foreach ($records as $r): 

        if (!isset($r['type']) || $r['type'] !== 'invoice') {
            continue;
        }

        $rowClass = ($r['due'] <= 0) ? 'paid' : 'unpaid';

        $total_inv  += $r['debit'];
        $total_paid += $r['paid'];
        $total_due  += $r['due'];
    ?>
        <tr class="<?= $rowClass ?>">
            <td><?= $r['ref'] ?></td>
            <td><?= date('Y-m-d', strtotime($r['date'])) ?></td>
            <td class="text-right"><?= number_format($r['debit'], 2) ?></td>
            <td class="text-right"><?= number_format($r['paid'], 2) ?></td>
            <td class="text-right"><?= number_format($r['due'], 2) ?></td>
        </tr>
    <?php endforeach; ?>

    <tr class="total-row">
        <td colspan="2">TOTAL</td>
        <td class="text-right"><?= number_format($total_inv, 2) ?></td>
        <td class="text-right"><?= number_format($total_paid, 2) ?></td>
        <td class="text-right"><?= number_format($total_due, 2) ?></td>
    </tr>
<?php else: ?>
    <tr>
        <td colspan="5">No Records Found</td>
    </tr>
<?php endif; ?>
</tbody>
