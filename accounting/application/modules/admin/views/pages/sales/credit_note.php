<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="page-title-box">
                <h4 class="page-title">Create Credit Note</h4>
            </div>

            <div class="card-box">

                <form method="post" action="<?= base_url('admin/sales/save_credit_note') ?>">

                    <input type="hidden" name="SaleID" value="<?= $sale->SaleID ?>">
                    <input type="hidden" name="CustomerID" value="<?= $sale->CustomerID ?>">
                    <input type="hidden" name="TotalSaleAmount" value="<?= $sale->TotalSaleAmount ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <label>Customer</label>
                            <input type="text" class="form-control"
                                   value="<?= $sale->CustomerName ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label>Sale Amount</label>
                            <input type="text" class="form-control"
                                   value="<?= number_format($sale->TotalSaleAmount,2) ?>" readonly>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Credit Amount</label>
                            <input type="number" step="any" name="CreditAmount"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Reason</label>
                            <textarea name="Reason" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="submit" class="btn btn-success">
                            Save Credit Note
                        </button>
                        <a href="<?= base_url('admin/sales') ?>"
                           class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>
<?php $this->view('includes/footer_end.php'); ?>
