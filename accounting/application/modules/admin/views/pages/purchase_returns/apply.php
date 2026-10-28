<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Apply Vendor Credit to Bills</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Purchase_returns') ?>">Purchase Returns</a></li>
                            <li class="active">Apply Credit</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open('admin/Purchase_returns/apply_save', ['id' => 'applyForm']) ?>
                    
                    <input type="hidden" name="PurchaseReturnID" value="<?= $return->PurchaseReturnID ?>">

                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Vendor Credit PR-<?= str_pad($return->PurchaseReturnID, 5, '0', STR_PAD_LEFT) ?> Details</h4>

                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Vendor:</strong> <?= htmlspecialchars($return->VendorName) ?></p>
                                <p><strong>Return Date:</strong> <?= date('Y-m-d', strtotime($return->ReturnDate)) ?></p>
                            </div>
                            <div class="col-md-4 text-right pull-right">
                                <h3>Total Value: Rs. <?= number_format($return->TotalReturnAmount, 2) ?></h3>
                                <h4 class="text-info">Unused Credit: Rs. <span id="unusedDisplay"><?= number_format($unusedCredit, 2, '.', '') ?></span></h4>
                                <h4 class="text-warning">Remaining to Allocate: Rs. <span id="remainingDisplay"><?= number_format($unusedCredit, 2, '.', '') ?></span></h4>
                            </div>
                        </div>

                        <hr>
                        <h4>Unpaid Vendor Bills</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="billsTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Bill No / Ref No</th>
                                        <th class="text-right">Total Bill</th>
                                        <th class="text-right">Paid</th>
                                        <th class="text-right">Due Amount</th>
                                        <th class="text-right" width="200">Amount to Apply</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($bills) && empty($allocations)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No outstanding unpaid bills found for this vendor.</td>
                                        </tr>
                                    <?php else: ?>
                                        <!-- Combine outstanding bills and already allocated ones -->
                                        <?php 
                                        $shownPurchaseIDs = [];
                                        foreach($allocations as $alloc) {
                                            $shownPurchaseIDs[] = $alloc->PurchaseID;
                                            $purchase = $this->db->get_where('purchases', ['PurchaseID' => $alloc->PurchaseID])->row();
                                            // Get other payments on this purchase (excluding this Vendor Credit allocation)
                                            $otherPaid = $this->db->select_sum('PaidAmount')
                                                ->where('PurchaseID', $alloc->PurchaseID)
                                                ->where('PurchaseReturnID !=', $return->PurchaseReturnID)
                                                ->where('IsDeleted', 0)
                                                ->get('vendor_payments')
                                                ->row()
                                                ->PaidAmount ?: 0;
                                            $due = $purchase->TotalAmount - $otherPaid;
                                            ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($purchase->PurchaseDate)) ?></td>
                                                <td><?= htmlspecialchars($purchase->ReferenceNo ?? 'PO-'.$purchase->PurchaseID) ?></td>
                                                <td class="text-right">Rs. <?= number_format($purchase->TotalAmount, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($otherPaid, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($due, 2) ?></td>
                                                <td>
                                                    <input type="number" step="0.01" max="<?= $due ?>" class="form-control text-right apply-input" name="amounts[<?= $purchase->PurchaseID ?>]" value="<?= number_format($alloc->PaidAmount, 2, '.', '') ?>" data-due="<?= $due ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <?php foreach($bills as $bill): ?>
                                            <?php if(in_array($bill->PurchaseID, $shownPurchaseIDs)) continue; ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($bill->PurchaseDate)) ?></td>
                                                <td><?= htmlspecialchars($bill->ReferenceNo ?? 'PO-'.$bill->PurchaseID) ?></td>
                                                <td class="text-right">Rs. <?= number_format($bill->TotalAmount, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($bill->PaidAmount, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($bill->due, 2) ?></td>
                                                <td>
                                                    <input type="number" step="0.01" max="<?= $bill->due ?>" class="form-control text-right apply-input" name="amounts[<?= $bill->PurchaseID ?>]" value="0.00" data-due="<?= $bill->due ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary pull-right">Save Allocations</button>
                        <div class="clearfix"></div>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script>
$(document).ready(function(){
    var creditNoteTotal = parseFloat("<?= $return->TotalReturnAmount ?>") || 0;
    
    function calculateAllocation() {
        var totalAllocated = 0;
        $('.apply-input').each(function(){
            var val = parseFloat($(this).val()) || 0;
            var maxDue = parseFloat($(this).data('due')) || 0;
            if (val > maxDue) {
                val = maxDue;
                $(this).val(val.toFixed(2));
            }
            totalAllocated += val;
        });

        var remaining = creditNoteTotal - totalAllocated;
        $('#remainingDisplay').text(remaining.toFixed(2));

        if (remaining < 0) {
            $('#remainingDisplay').parent().removeClass('text-warning').addClass('text-danger');
            $(':submit').attr('disabled', 'disabled');
        } else {
            $('#remainingDisplay').parent().removeClass('text-danger').addClass('text-warning');
            $(':submit').removeAttr('disabled');
        }
    }

    $(document).on('keyup change', '.apply-input', function(){
        calculateAllocation();
    });

    // Run baseline check on load
    calculateAllocation();

    $('#applyForm').submit(function(e){
        calculateAllocation();
        var remaining = creditNoteTotal - parseFloat($('#remainingDisplay').text());
        if (remaining > creditNoteTotal) {
            alert('Allocated amount exceeds total Vendor Credit value!');
            e.preventDefault();
            return false;
        }
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
