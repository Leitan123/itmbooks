<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Purchases</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Purchases') ?>">Purchases</a></li>
                            <li class="active"><?= isset($obj) ? "Edit Purchase" : "Add Purchase" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30"><?= isset($obj) ? "Edit Purchase" : "Add Purchase" ?></h4>
                        
                        <form action="<?= base_url('admin/purchases/save') ?>" method="post" id="purchaseForm">
    
                        <div class="form-group">
                            <label for="vendor">Vendor</label>
                            <select name="form[VendorID]" class="form-control select2" required>
                                <option value="">Select Vendor</option>
                                <?php foreach ($vendors as $vendor): ?>
                                    <option value="<?= $vendor->VendorID ?>" <?= $vendor->VendorID == (isset($purchase->VendorID) ? $purchase->VendorID : '') ? 'selected' : '' ?>>
                                        <?= $vendor->VendorName ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="purchase_date">Purchase Date</label>
                            <input type="date" name="form[PurchaseDate]" class="form-control" value="<?= isset($obj->PurchaseDate) ? htmlspecialchars($obj->PurchaseDate) : date('Y-m-d') ?>" required>
                        </div>

                        <div id="items-container">
                            <?php if (isset($obj->items)): ?>
                                <?php foreach ($obj->items as $index => $item): ?>
                                    <div class="row item-row">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label for="ItemID">Item</label>
                                                <select name="form[items][0][ItemID]" class="form-control select2" required>
                                                    <option value="">Select Item</option>
                                                    <?php foreach ($items as $item): ?>
                                                        <option value="<?= $item->ItemID ?>" <?= isset($purchase->ItemID) && $item->ItemID == $purchase->ItemID ? 'selected' : '' ?>>
                                                            <?= $item->ItemName ?> | <?= isset($item->CategoryName) ? $item->CategoryName : 'No Category' ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>


                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label for="Quantity">Qty / Kgs</label>
                                              

                                                <input type="number" step="any" name="form[items][<?= $index ?>][Quantity]" class="form-control Quantity" value="<?= $item->Quantity ?>" required>

                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="PurchasePrice">Purchase Price</label>
                                                <input type="number" step="any" name="form[items][<?= $index ?>][PurchasePrice]" class="form-control PurchasePrice" value="<?= $item->PurchasePrice ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="TotalAmount">Amount</label>
                                                <input type="text" name="form[items][<?= $index ?>][TotalAmount]" class="form-control TotalAmount" value="<?= $item->TotalAmount ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger remove-item" style="width: 100%;">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="row item-row">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="ItemID">Item</label>
                                            <select name="form[items][0][ItemID]" class="form-control select2" required>
                                                <option value="">Select Item</option>
                                                <?php foreach ($items as $item): ?>
                                                    <option value="<?= $item->ItemID ?>" <?= isset($purchase->ItemID) && $item->ItemID == $purchase->ItemID ? 'selected' : '' ?>>
                                                        <?= $item->ItemName ?> | <?= isset($item->CategoryName) ? $item->CategoryName : 'No Category' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label for="Quantity">Qty / Kgs</label>
                                            <input type="number" step="any"  name="form[items][0][Quantity]" class="form-control Quantity" required>

                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="PurchasePrice">Purchase Price</label>
                                            <input type="number" step="any" name="form[items][0][PurchasePrice]" class="form-control PurchasePrice" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="TotalAmount">Amount</label>
                                            <input type="text" name="form[items][0][TotalAmount]" class="form-control TotalAmount" readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger remove-item" style="width: 100%;">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-success add-item" style="margin-top: 15px;">Add Item</button>
                        <hr>

                        <div class="form-group">
                            <label for="TotalPurchaseAmount">Total Amount</label>
                            <input type="text" id="TotalPurchaseAmount" class="form-control" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                        </form>
                    </div>

                    <?= form_close() ?>
                </div>
                <!-- end row -->
            </div>

        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- container -->

</div>
<!-- content -->

</div>
<!-- End content-page -->

<?php $this->view('includes/footer_start.php'); ?>


<script type="text/javascript">
    $(document).ready(function () {
        function calculateTotal(row) {
            var quantity = parseFloat(row.find('.Quantity').val()) || 0;
            var purchasePrice = parseFloat(row.find('.PurchasePrice').val()) || 0;
            var totalAmount = quantity * purchasePrice;
            row.find('.TotalAmount').val(totalAmount.toFixed(2));

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            var grandTotal = 0;
            $('.TotalAmount').each(function () {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#TotalPurchaseAmount').val(grandTotal.toFixed(2));
        }

        // Add item row
        $('.add-item').click(function() {
            var index = $('#items-container .item-row').length;
            var newRow = getNewItemRow(index);
            $('#items-container').append(newRow);
        });


        // Function to generate a new item row
        function getNewItemRow(index) {
            return `
                <div class="row item-row">
                    
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="ItemID">Item</label>
                            <select name="form[items][${index}][ItemID]" class="form-control ItemID" required>
                                <option value="">Select Item</option>
                                <?php foreach ($items as $item): ?>
                                    <option value="<?= $item->ItemID ?>"><?= $item->ItemName ?> | <?= isset($item->CategoryName) ? $item->CategoryName : 'No Category' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label for="Quantity">Qty</label>
                            <input type="number" step="any"  name="form[items][${index}][Quantity]" class="form-control Quantity" required>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="PurchasePrice">Purchase Price</label>
                            <input type="number" step="any" name="form[items][${index}][PurchasePrice]" class="form-control PurchasePrice" required>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="TotalAmount">Amount</label>
                            <input type="text" name="form[items][${index}][TotalAmount]" class="form-control TotalAmount" readonly>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger remove-item" style="width: 100%;">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Remove item row
        $('#items-container').on('click', '.remove-item', function () {
            $(this).closest('.item-row').remove();
            calculateGrandTotal();
        });

        // Calculate total amount for an item when quantity or purchase price changes
        $('#items-container').on('input', '.Quantity, .PurchasePrice', function () {
            var row = $(this).closest('.item-row');
            calculateTotal(row);
        });

    });
</script>



<?php $this->view('includes/footer_end.php'); ?>
