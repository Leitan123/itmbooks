<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Apply Credit Note to Invoices</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Sales_returns') ?>">Sales Returns</a></li>
                            <li class="active">Apply Credit</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open('admin/Sales_returns/apply_save', ['id' => 'applyForm']) ?>
                    
                    <input type="hidden" name="SalesReturnID" value="<?= $return->SalesReturnID ?>">

                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Credit Note SR-<?= str_pad($return->SalesReturnID, 5, '0', STR_PAD_LEFT) ?> Details</h4>

                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Customer:</strong> <?= htmlspecialchars($return->CompanyName ?? $return->CustomerName) ?></p>
                                <p><strong>Return Date:</strong> <?= date('Y-m-d', strtotime($return->ReturnDate)) ?></p>
                            </div>
                            <div class="col-md-4 text-right pull-right">
                                <h3>Total Value: Rs. <?= number_format($return->TotalReturnAmount, 2) ?></h3>
                                <h4 class="text-info">Unused Credit: Rs. <span id="unusedDisplay"><?= number_format($unusedCredit, 2, '.', '') ?></span></h4>
                                <h4 class="text-warning">Remaining to Allocate: Rs. <span id="remainingDisplay"><?= number_format($unusedCredit, 2, '.', '') ?></span></h4>
                            </div>
                        </div>

                        <hr>
                        <h4>Unpaid Invoices</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="invoicesTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice No</th>
                                        <th class="text-right">Total Invoice</th>
                                        <th class="text-right">Paid</th>
                                        <th class="text-right">Due Amount</th>
                                        <th class="text-right" width="200">Amount to Apply</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($invoices) && empty($allocations)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No outstanding unpaid invoices found for this customer.</td>
                                        </tr>
                                    <?php else: ?>
                                        <!-- Combine outstanding invoices and already allocated ones -->
                                        <?php 
                                        $shownInvoiceIDs = [];
                                        foreach($allocations as $alloc) {
                                            $shownInvoiceIDs[] = $alloc->SaleID;
                                            $sale = $this->db->get_where('sales', ['SaleID' => $alloc->SaleID])->row();
                                            // Get other payments on this sale (excluding this Credit Note allocation)
                                            $otherPaid = $this->db->select_sum('PaidAmount')
                                                ->where('SaleID', $alloc->SaleID)
                                                ->where('SalesReturnID !=', $return->SalesReturnID)
                                                ->where('IsDeleted', 0)
                                                ->get('customer_payments')
                                                ->row()
                                                ->PaidAmount ?: 0;
                                            $due = $sale->TotalSaleAmount - $otherPaid;
                                            ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($sale->SaleDate)) ?></td>
                                                <td><?= htmlspecialchars($sale->RefNo) ?></td>
                                                <td class="text-right">Rs. <?= number_format($sale->TotalSaleAmount, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($otherPaid, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($due, 2) ?></td>
                                                <td>
                                                    <input type="number" step="0.01" max="<?= $due ?>" class="form-control text-right apply-input" name="amounts[<?= $sale->SaleID ?>]" value="<?= number_format($alloc->PaidAmount, 2, '.', '') ?>" data-due="<?= $due ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <?php foreach($invoices as $invoice): ?>
                                            <?php if(in_array($invoice->SaleID, $shownInvoiceIDs)) continue; ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($invoice->SaleDate)) ?></td>
                                                <td><?= htmlspecialchars($invoice->RefNo) ?></td>
                                                <td class="text-right">Rs. <?= number_format($invoice->TotalSaleAmount, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($invoice->PaidAmount, 2) ?></td>
                                                <td class="text-right">Rs. <?= number_format($invoice->due, 2) ?></td>
                                                <td>
                                                    <input type="number" step="0.01" max="<?= $invoice->due ?>" class="form-control text-right apply-input" name="amounts[<?= $invoice->SaleID ?>]" value="0.00" data-due="<?= $invoice->due ?>">
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
            alert('Allocated amount exceeds total Credit Note value!');
            e.preventDefault();
            return false;
        }
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
