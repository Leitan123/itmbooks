<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
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

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open('', ['id' => 'purchaseForm']) ?>
                    <?php if (isset($purchase) && $purchase->PurchaseID): ?>
                        <input type="hidden" name="PurchaseID" value="<?= $purchase->PurchaseID ?>">
                    <?php endif; ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30"><?= isset($obj) ? "Edit Purchase" : "Add Purchase" ?></h4>
                        
                        <div class="form-group">
                            <label for="vendor">Vendor</label>
                            <select name="form[VendorID]" class="form-control select2" required>
                                <option value="">Select Vendor</option>
                                <?php foreach ($vendors as $vendor): ?>
                                    <option value="<?= $vendor->VendorID ?>" <?= isset($purchase->VendorID) && $vendor->VendorID == $purchase->VendorID ? 'selected' : '' ?>>
                                        <?= $vendor->VendorName ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="purchase_date">Purchase Date</label>
                                    <input type="date" name="form[PurchaseDate]" class="form-control" value="<?= isset($purchase->PurchaseDate) ? htmlspecialchars($purchase->PurchaseDate) : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="po_no">PO No</label>
                                    <input type="text" name="form[PONo]" class="form-control" value="<?= isset($purchase->PONo) ? htmlspecialchars($purchase->PONo) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ref_no">Ref No</label>
                                    <input type="text" name="form[RefNo]" class="form-control" value="<?= isset($purchase->RefNo) ? htmlspecialchars($purchase->RefNo) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div id="items-container">
                            <?php if (!empty($purchase_items)): ?>
                                <?php foreach ($purchase_items as $index => $item): ?>
                                    <div class="row item-row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <select name="form[items][<?= $index ?>][ItemID]" class="form-control select2">
                                                    <option value="">Select Item</option>
                                                    <?php foreach ($items as $itemOption): ?>
                                                        <option value="<?= $itemOption->ItemID ?>" data-description="<?= htmlspecialchars($itemOption->ItemDescription ?? '') ?>" <?= $item->ItemID == $itemOption->ItemID ? 'selected' : '' ?>><?= $itemOption->CategoryName ?> | <?= $itemOption->BrandName ?> | <?= $itemOption->ItemName ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php
                                                $item_desc = '';
                                                if (isset($item->Description) && $item->Description !== '') {
                                                    $item_desc = $item->Description;
                                                } elseif (isset($item->description) && $item->description !== '') {
                                                    $item_desc = $item->description;
                                                } else {
                                                    foreach ($items as $itm) {
                                                        if ($itm->ItemID == $item->ItemID) {
                                                            $item_desc = $itm->ItemDescription ?? '';
                                                            break;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <input type="text" name="form[items][<?= $index ?>][Description]" class="form-control Description" placeholder="Item description/specifications..." value="<?= htmlspecialchars($item_desc) ?>" style="margin-top: 5px;">
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label for="Quantity">Qty / Kgs</label>
                                                <input type="number" step="any" name="form[items][<?= $index ?>][Quantity]" 
                                                       class="form-control Quantity" value="<?= $item->Quantity ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label for="QtyType">Scale</label>
                                                <select name="form[items][<?= $index ?>][QtyType]" 
                                                        class="form-control" 
                                                        required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    $qtyOptions = ['Pcs', 'Boxes', 'Kg', 'Gr', 'Bar', 'Tubes', 'Rolls', 'Sheets', 'Bottle', 'Pairs', 'Packets', 'Cartons', 'Meter', 'Feet'];
                                                    foreach ($qtyOptions as $option):
                                                        $selected = (isset($item->QtyType) && $item->QtyType == $option) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $option ?>" <?= $selected ?>><?= $option ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Purchase Price</label>
                                                <input type="number" step="any" name="form[items][<?= $index ?>][PurchasePrice]" class="form-control PurchasePrice" value="<?= $item->PurchasePrice ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>Dis. (%)</label>
                                                <input type="number" step="any" name="form[items][<?= $index ?>][Discount]" class="form-control Discount" value="<?= isset($item->Discount) ? $item->Discount : 0 ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="text" name="form[items][<?= $index ?>][TotalAmount]" class="form-control TotalAmount" value="<?= ($item->Quantity * $item->PurchasePrice) - (isset($item->Discount) ? $item->Discount : 0) ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger remove-item" style="width: 100%;">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-success add-item mt-2">Add Item</button>
                        <hr>

                        <div class="form-group">
                            <label>Remarks / Description</label>
                            <textarea name="form[Remarks]" class="form-control" rows="3" placeholder="General purchase description or remarks..."><?= isset($purchase->Remarks) ? htmlspecialchars($purchase->Remarks) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" id="TotalPurchaseAmount" class="form-control" value="<?= isset($purchase->TotalPurchaseAmount) ? number_format($purchase->TotalPurchaseAmount,2) : '0.00' ?>" readonly>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" name="submit_action" value="save_bill" class="btn btn-warning">Save Bill</button>
                            <button type="submit" name="submit_action" value="pay_cash" class="btn btn-success">Pay Cash</button>
                        </div>
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

    function calculateTotal(row){
        var qty = parseFloat(row.find('.Quantity').val()) || 0;
        var price = parseFloat(row.find('.PurchasePrice').val()) || 0;
        var discount = parseFloat(row.find('.Discount').val()) || 0;
        var total = (qty * price) - ((qty * price) * discount / 100); // <-- percentage
        row.find('.TotalAmount').val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal(){
        var grandTotal = 0;
        $('.TotalAmount').each(function(){
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#TotalPurchaseAmount').val(grandTotal.toFixed(2));
    }

    $(document).on('change', '#items-container select[name*="[ItemID]"]', function(){
        var row = $(this).closest('.item-row');
        var description = $(this).find(':selected').data('description') || '';
        row.find('.Description').val(description);
    });

    $(document).on('input', '.Quantity, .PurchasePrice, .Discount', function(){
        var row = $(this).closest('.item-row');
        calculateTotal(row);
    });

    $(document).on('click', '.remove-item', function(){
        $(this).closest('.item-row').remove();
        calculateGrandTotal();
    });

    $('.add-item').click(function(){
        var index = $('#items-container .item-row').length;
        var newRow = `
        <div class="row item-row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>Item</label>
                    <select name="form[items][${index}][ItemID]" class="form-control select2">
                        <option value="">Select Item</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item->ItemID ?>" data-description="<?= htmlspecialchars($item->ItemDescription ?? '') ?>"><?= $item->CategoryName ?> | <?= $item->BrandName ?> | <?= $item->ItemName ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="form[items][${index}][Description]" class="form-control Description" placeholder="Item description/specifications..." value="" style="margin-top: 5px;">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Qty</label>
                    <div class="input-group">
                        <input type="number" step="any" name="form[items][${index}][Quantity]" class="form-control Quantity" required>
                        <select name="form[items][${index}][QtyType]" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Boxes">Boxes</option>
                            <option value="Kg">Kg</option>
                            <option value="Gr">Gr</option>
                            <option value="Bar">Bar</option>
                            <option value="Tubes">Tubes</option>
                            <option value="Rolls">Rolls</option>
                            <option value="Sheets">Sheets</option>
                            <option value="Bottle">Bottle</option>
                            <option value="Pairs">Pairs</option>
                            <option value="Packets">Packets</option>
                            <option value="Cartons">Cartons</option>
                            <option value="Meter">Meter</option>
                            <option value="Feet">Feet</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Purchase Price</label>
                    <input type="number" step="any" name="form[items][${index}][PurchasePrice]" class="form-control PurchasePrice" value="0" required>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>Discount %</label>
                    <input type="number" step="any" name="form[items][${index}][Discount]" class="form-control Discount" value="0">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Amount</label>
                    <input type="text" name="form[items][${index}][TotalAmount]" class="form-control TotalAmount" value="0.00" readonly>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-item"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>`;
        $('#items-container').append(newRow);
        $('#items-container select.select2').select2();
    });

    // Recalculate totals on page load for edit
    $('.item-row').each(function(){
        calculateTotal($(this));
    });
});
</script>


<?php $this->view('includes/footer_end.php'); ?>
