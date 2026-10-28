<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Purchase Returns (Vendor Credits)</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/Purchase_returns') ?>">Purchase Return</a></li>
                            <li class="active"><?= isset($obj->PurchaseReturnID) ? "Edit Return" : "Add Return" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open('admin/Purchase_returns/save') ?>
                    
                    <input type="hidden" name="PurchaseReturnID" value="<?= isset($obj->PurchaseReturnID) ? $obj->PurchaseReturnID : 0 ?>">

                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30"><?= isset($obj->PurchaseReturnID) ? "Edit Purchase Return" : "Add Purchase Return" ?></h4>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <select name="form[VendorID]" class="form-control select2" required>
                                        <option value="">Select Vendor</option>
                                        <?php foreach($vendors as $vendor): ?>
                                            <option value="<?= $vendor->VendorID ?>" <?= isset($obj->VendorID) && $obj->VendorID == $vendor->VendorID ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($vendor->VendorName) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="date" name="form[ReturnDate]" class="form-control" value="<?= isset($obj->ReturnDate) ? date('Y-m-d', strtotime($obj->ReturnDate)) : date('Y-m-d') ?>" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Reference No</label>
                                    <input type="text" name="form[ReferenceNo]" class="form-control" value="<?= isset($obj->ReferenceNo) ? htmlspecialchars($obj->ReferenceNo) : '' ?>" placeholder="e.g. GRN-1004 Return">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="header-title m-t-0 m-b-20">Returned Items</h5>

                        <div id="items-container">
                            <?php if(isset($return_items) && count($return_items) > 0) { ?>
                                <?php foreach($return_items as $k => $ritem) { ?>
                                    <div class="row item-row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <select name="form[items][<?= $k ?>][ItemID]" class="form-control select2 ItemID" required>
                                                    <option value="">Select Item</option>
                                                    <?php foreach($items as $item) { ?>
                                                        <option value="<?= $item->ItemID ?>" data-price="<?= $item->SellingPrice ?>" <?= ($item->ItemID == $ritem->ItemID) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($item->ItemName) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Qty</label>
                                                <input type="number" step="0.01" name="form[items][<?= $k ?>][Quantity]" class="form-control Qty" value="<?= $ritem->Quantity ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>Scale</label>
                                                <input type="text" name="form[items][<?= $k ?>][QtyType]" class="form-control" value="<?= htmlspecialchars($ritem->QtyType) ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Refund Price</label>
                                                <input type="number" step="0.01" name="form[items][<?= $k ?>][RefundPrice]" class="form-control UnitPrice" value="<?= $ritem->RefundPrice ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Total</label>
                                                <input type="text" name="form[items][<?= $k ?>][TotalAmount]" class="form-control TotalAmount" value="<?= number_format($ritem->TotalAmount, 2, '.', '') ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 text-center">
                                            <div class="form-group">
                                                <label>Damaged?</label>
                                                <div style="margin-top: 8px;">
                                                    <input type="checkbox" name="form[items][<?= $k ?>][IsDamaged]" value="1" <?= isset($ritem->IsDamaged) && $ritem->IsDamaged == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger remove-item" style="width:100%;"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="row item-row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Item</label>
                                            <select name="form[items][0][ItemID]" class="form-control select2 ItemID" required>
                                                <option value="">Select Item</option>
                                                <?php foreach($items as $item) { ?>
                                                    <option value="<?= $item->ItemID ?>" data-price="<?= $item->SellingPrice ?>">
                                                        <?= htmlspecialchars($item->ItemName) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Qty</label>
                                            <input type="number" step="0.01" name="form[items][0][Quantity]" class="form-control Qty" value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>Scale</label>
                                            <input type="text" name="form[items][0][QtyType]" class="form-control" value="Pcs" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Refund Price</label>
                                            <input type="number" step="0.01" name="form[items][0][RefundPrice]" class="form-control UnitPrice" value="0.00" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>Total</label>
                                            <input type="text" name="form[items][0][TotalAmount]" class="form-control TotalAmount" value="0.00" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 text-center">
                                        <div class="form-group">
                                            <label>Damaged?</label>
                                            <div style="margin-top: 8px;">
                                                <input type="checkbox" name="form[items][0][IsDamaged]" value="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger remove-item" style="width:100%;"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <button type="button" class="btn btn-success add-item">Add Item</button>
                        <hr>

                        <div class="row">
                            <div class="col-lg-4 pull-right text-right">
                                <div class="form-group">
                                    <label style="font-size: 16px; font-weight: bold;">Total Return Value</label>
                                    <input type="text" id="GrandTotal" name="form[TotalReturnAmount]" class="form-control" style="font-size: 18px; font-weight: bold; text-align: right;" value="<?= isset($obj->TotalReturnAmount) ? number_format($obj->TotalReturnAmount, 2, '.', '') : '0.00' ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes / Reason for Return</label>
                            <textarea name="form[Notes]" class="form-control" rows="3" placeholder="Enter return reason..."><?= isset($obj->Notes) ? htmlspecialchars($obj->Notes) : '' ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Vendor Credit</button>
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
    $('select.select2').select2();

    function calculateTotal(row) {
        var qty = parseFloat(row.find('.Qty').val()) || 0;
        var price = parseFloat(row.find('.UnitPrice').val()) || 0;
        var total = qty * price;
        row.find('.TotalAmount').val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var total = 0;
        $('.TotalAmount').each(function(){
            total += parseFloat($(this).val()) || 0;
        });
        $('#GrandTotal').val(total.toFixed(2));
    }

    $(document).on('change', '.ItemID', function(){
        var row = $(this).closest('.item-row');
        var price = $(this).find(':selected').data('price') || 0;
        row.find('.UnitPrice').val(price);
        calculateTotal(row);
    });

    $(document).on('keyup change', '.Qty, .UnitPrice', function(){
        var row = $(this).closest('.item-row');
        calculateTotal(row);
    });

    $(document).on('click', '.remove-item', function(){
        $(this).closest('.item-row').remove();
        calculateGrandTotal();
    });

    $('.add-item').click(function(){
        var index = $('.item-row').length;
        var newRow = `
        <div class="row item-row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>Item</label>
                    <select name="form[items][${index}][ItemID]" class="form-control select2 ItemID" required>
                        <option value="">Select Item</option>
                        <?php foreach($items as $item): ?>
                            <option value="<?= $item->ItemID ?>" data-price="<?= $item->SellingPrice ?>">
                                <?= htmlspecialchars($item->ItemName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" step="0.01" name="form[items][${index}][Quantity]" class="form-control Qty" value="1" required>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>Scale</label>
                    <input type="text" name="form[items][${index}][QtyType]" class="form-control" value="Pcs" readonly>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Refund Price</label>
                    <input type="number" step="0.01" name="form[items][${index}][RefundPrice]" class="form-control UnitPrice" value="0.00" required>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Total</label>
                    <input type="text" name="form[items][${index}][TotalAmount]" class="form-control TotalAmount" value="0.00" readonly>
                </div>
            </div>
            <div class="col-lg-1 text-center">
                <div class="form-group">
                    <label>Damaged?</label>
                    <div style="margin-top: 8px;">
                        <input type="checkbox" name="form[items][${index}][IsDamaged]" value="1">
                    </div>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-item" style="width:100%;"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>`;
        
        $('#items-container').append(newRow);
        $('#items-container .select2').last().select2();
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
