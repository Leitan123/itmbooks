<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
<div class="page-title-box">
<h4 class="page-title">Purchase Order</h4>
<ol class="breadcrumb p-0">
<li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
<li><a href="<?= base_url('admin/purchase_orders') ?>">Purchase Orders</a></li>
<li class="active">Add/Edit PO</li>
</ol>
<div class="clearfix"></div>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">

<?= form_open('admin/purchase_orders/save') ?>

<input type="hidden" name="form[PurchaseOrderID]" value="<?= isset($obj->PurchaseOrderID) ? $obj->PurchaseOrderID : 0 ?>">

<div class="card-box">
<div class="row">

<div class="col-lg-4">
<div class="form-group">
<label>PO Number</label>
<input type="text" name="form[PurchaseOrderNo]" class="form-control" value="<?= isset($obj->PurchaseOrderNo) ? $obj->PurchaseOrderNo : (isset($po_no) ? $po_no : '') ?>" readonly>
</div>
</div>

<div class="col-lg-4">
<div class="form-group">
<label>Date</label>
<input type="date" name="form[PurchaseOrderDate]" class="form-control" value="<?= isset($obj->PurchaseOrderDate) ? date('Y-m-d', strtotime($obj->PurchaseOrderDate)) : date('Y-m-d') ?>" required>
</div>
</div>

<div class="col-lg-4">
<div class="form-group">
<label>Vendor</label>
<select name="form[VendorID]" class="form-control select2" required>
<option value="">Select Vendor</option>
<?php foreach($vendors as $vendor): ?>
<option value="<?= $vendor->VendorID ?>" <?= isset($obj->VendorID) && $obj->VendorID == $vendor->VendorID ? 'selected' : '' ?>>
    <?= $vendor->VendorName ?> (<?= $vendor->CompanyName ?>)
</option>
<?php endforeach; ?>
</select>
</div>
</div>

</div>

<hr>

<div id="items-container">
<?php if(isset($po_items) && count($po_items) > 0) { ?>
    <?php foreach($po_items as $k => $poitem) { ?>
    <div class="row item-row">
        <div class="col-lg-4">
            <div class="form-group">
                <label>Item</label>
                <select name="form[items][<?= $k ?>][ItemID]" class="form-control select2 ItemID">
                    <option value="">Select Item</option>
                    <?php foreach($items as $item) { ?>
                    <option value="<?= $item->ItemID ?>" data-price="<?= $item->PurchasePrice ?>" data-description="<?= htmlspecialchars($item->ItemDescription ?? '') ?>" <?= ($item->ItemID == $poitem->ItemID) ? 'selected' : '' ?>>
                        <?= $item->ItemName ?>
                    </option>
                    <?php } ?>
                </select>
                <?php
                $item_desc = '';
                if (isset($poitem->Description) && $poitem->Description !== '') {
                    $item_desc = $poitem->Description;
                } elseif (isset($poitem->description) && $poitem->description !== '') {
                    $item_desc = $poitem->description;
                } else {
                    foreach ($items as $itm) {
                        if ($itm->ItemID == $poitem->ItemID) {
                            $item_desc = $itm->ItemDescription ?? '';
                            break;
                        }
                    }
                }
                ?>
                <input type="text" name="form[items][<?= $k ?>][Description]" class="form-control Description" placeholder="Item description/specifications..." value="<?= htmlspecialchars($item_desc) ?>" style="margin-top: 5px;">
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label>Qty</label>
                <input type="number" step="0.01" name="form[items][<?= $k ?>][Qty]" class="form-control Qty" value="<?= $poitem->Qty ?>">
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="form[items][<?= $k ?>][UnitPrice]" class="form-control UnitPrice" value="<?= $poitem->UnitPrice ?>">
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label>Total</label>
                <input type="text" name="form[items][<?= $k ?>][TotalAmount]" class="form-control TotalAmount" value="<?= $poitem->TotalAmount ?>" readonly>
            </div>
        </div>

        <div class="col-lg-1">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger remove-item"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>
    <?php } ?>
<?php } else { ?>
    <div class="row item-row">
        <div class="col-lg-4">
            <div class="form-group">
                <label>Item</label>
                <select name="form[items][0][ItemID]" class="form-control select2 ItemID">
                    <option value="">Select Item</option>
                    <?php foreach($items as $item) { ?>
                    <option value="<?= $item->ItemID ?>" data-price="<?= $item->PurchasePrice ?>" data-description="<?= htmlspecialchars($item->ItemDescription ?? '') ?>">
                        <?= $item->ItemName ?>
                    </option>
                    <?php } ?>
                </select>
                <input type="text" name="form[items][0][Description]" class="form-control Description" placeholder="Item description/specifications..." value="" style="margin-top: 5px;">
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label>Qty</label>
                <input type="number" step="0.01" name="form[items][0][Qty]" class="form-control Qty" value="1">
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="form[items][0][UnitPrice]" class="form-control UnitPrice" value="0">
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label>Total</label>
                <input type="text" name="form[items][0][TotalAmount]" class="form-control TotalAmount" value="0.00" readonly>
            </div>
        </div>
        
        <div class="col-lg-1">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger remove-item"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<button type="button" class="btn btn-success add-item">Add Item</button>

<hr>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label>Sub Total</label>
            <input type="text" id="SubTotal" name="form[SubTotal]" class="form-control" value="<?= isset($obj->SubTotal) ? $obj->SubTotal : '0.00' ?>" readonly>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label>Discount</label>
            <input type="number" step="0.01" id="DiscountAmount" name="form[DiscountAmount]" class="form-control" value="<?= isset($obj->DiscountAmount) ? $obj->DiscountAmount : '0' ?>">
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label>Grand Total</label>
            <input type="text" id="GrandTotal" name="form[GrandTotal]" class="form-control" value="<?= isset($obj->GrandTotal) ? $obj->GrandTotal : '0.00' ?>" readonly>
        </div>
    </div>
</div>

<div class="form-group">
    <label>Remarks</label>
    <textarea name="form[Remarks]" class="form-control"><?= isset($obj->Remarks) ? $obj->Remarks : '' ?></textarea>
</div>

<button type="submit" class="btn btn-primary">Save PO</button>

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
        var subtotal = 0;
        $('.TotalAmount').each(function(){
            subtotal += parseFloat($(this).val()) || 0;
        });
        $('#SubTotal').val(subtotal.toFixed(2));
        var discount = parseFloat($('#DiscountAmount').val()) || 0;
        $('#GrandTotal').val((subtotal - discount).toFixed(2));
    }

    $(document).on('change','.ItemID', function(){
        var row = $(this).closest('.item-row');
        var price = $(this).find(':selected').data('price');
        var description = $(this).find(':selected').data('description') || '';
        row.find('.UnitPrice').val(price);
        row.find('.Description').val(description);
        calculateTotal(row);
    });

    $(document).on('keyup change','.Qty,.UnitPrice,#DiscountAmount', function(){
        var row = $(this).closest('.item-row');
        if(row.length) {
            calculateTotal(row);
        } else {
            calculateGrandTotal();
        }
    });

    $(document).on('click','.remove-item', function(){
        $(this).closest('.item-row').remove();
        calculateGrandTotal();
    });

    $('.add-item').click(function(){
        var index = $('.item-row').length;
        var newRow = `
        <div class="row item-row" style="margin-top: 10px;">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>Item</label>
                    <select name="form[items][${index}][ItemID]" class="form-control select2 ItemID" required>
                        <option value="">Select Item</option>
                        <?php foreach($items as $item): ?>
                        <option value="<?= $item->ItemID ?>" data-price="<?= $item->PurchasePrice ?>" data-description="<?= htmlspecialchars($item->ItemDescription ?? '') ?>">
                            <?= $item->ItemName ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="form[items][${index}][Description]" class="form-control Description" placeholder="Item description/specifications..." value="" style="margin-top: 5px;">
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" step="0.01" name="form[items][${index}][Qty]" class="form-control Qty" value="1">
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="form[items][${index}][UnitPrice]" class="form-control UnitPrice" value="0">
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <label>Total</label>
                    <input type="text" name="form[items][${index}][TotalAmount]" class="form-control TotalAmount" value="0.00" readonly>
                </div>
            </div>

            <div class="col-lg-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-item"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        `;

        $('#items-container').append(newRow);
        $('#items-container .select2').select2();
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
