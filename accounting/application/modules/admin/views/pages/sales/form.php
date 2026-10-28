<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
<div class="page-title-box">
<h4 class="page-title">Sales</h4>
<ol class="breadcrumb p-0">
<li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
<li><a href="<?= base_url('admin/Sales') ?>">Sales</a></li>
<li class="active"><?= isset($sale) && !empty($sale->SaleID) ? "Edit Sale" : "Add Sale" ?></li>
</ol>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php $this->view('includes/notification.php'); ?>

<div class="row">
<div class="col-sm-12">

<?= form_open('admin/Sales/save_sale') ?>

<input type="hidden" name="SaleID" value="<?= isset($sale->SaleID) ? $sale->SaleID : 0 ?>">
<input type="hidden" name="form[JobCardID]" value="<?= isset($jobcard->JobCardID) ? $jobcard->JobCardID : 0 ?>">

<div class="card-box">
<h4 class="header-title m-t-0 m-b-30"><?= isset($sale) && !empty($sale->SaleID) ? "Edit Sale" : "Add Sale" ?></h4>

<div class="row">
<div class="col-md-4">
<div class="form-group">
<label>Sale Date</label>
<input type="date" name="form[SaleDate]" class="form-control" value="<?= isset($sale->SaleDate) ? date('Y-m-d', strtotime($sale->SaleDate)) : date('Y-m-d') ?>" required>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>PO No</label>
<input type="text" name="form[PONo]" class="form-control" value="<?= isset($sale->PONo) ? $sale->PONo : '' ?>">
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Ref No</label>
<input type="text" name="form[RefNo]" class="form-control" value="<?= isset($sale->RefNo) ? $sale->RefNo : '' ?>">
</div>
</div>
</div>

<div class="form-group">
<label>Company Name</label>
<select name="form[CustomerId]" class="form-control select2" style="width: 100%;" required>
<option value="">-- Select Customer --</option>
<?php foreach($customers as $customer): ?>
<option value="<?= $customer->CustomerId ?>" <?php if((isset($sale->CustomerId) && $customer->CustomerId == $sale->CustomerId) || (isset($jobcard->CustomerID) && $customer->CustomerId == $jobcard->CustomerID)) { echo 'selected'; } ?>>
<?= $customer->CompanyName ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div id="items-container">
<?php
$qtyOptions = ['Pcs','Boxes','Kg','Gr','Bar','Tubes','Rolls','Sheets','Bottle','Pairs','Packets','Cartons','Meter','Feet'];

// FIX: Check if there are sale items OR if it's a new sale (show at least one empty row)
$hasItems = !empty($sale_items);
$isNewSale = (!isset($sale) || empty($sale->SaleID)) && !isset($jobcard->JobCardID);

if($hasItems):
    foreach($sale_items as $index => $item):
?>
<div class="row item-row">
<div class="col-md-3 col-sm-3">
<div class="form-group">
<label>Item</label>
<select name="form[items][<?= $index ?>][ItemID]" class="form-control select2 ItemID" style="width: 100%;">
<option value="">Select Item</option>
<?php foreach($items as $itemOption): ?>
<option value="<?= $itemOption->ItemID ?>" data-price="<?= $itemOption->SellingPrice ?>" <?= ($itemOption->ItemID == $item->ItemID) ? 'selected' : '' ?>>
<?= $itemOption->CategoryName ?> | <?= $itemOption->BrandName ?> | <?= $itemOption->ItemName ?>
</option>
<?php endforeach; ?>
</select>
<input type="text" name="form[items][<?= $index ?>][Description]" class="form-control Description" placeholder="Item description/specifications..." value="<?= isset($item->Description) ? htmlspecialchars($item->Description) : '' ?>" style="margin-top: 5px;">
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Qty</label>
<div class="input-group">
<input type="number" step="any" name="form[items][<?= $index ?>][Quantity]" class="form-control Quantity" value="<?= $item->Quantity ?>" required>
<select name="form[items][<?= $index ?>][QtyType]" class="form-control" required>
<?php foreach($qtyOptions as $option): ?>
<option value="<?= $option ?>" <?= (isset($item->QtyType) && $item->QtyType == $option) ? 'selected' : '' ?>><?= $option ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Sale Price</label>
<input type="number" step="any" name="form[items][<?= $index ?>][SalePrice]" class="form-control SalePrice" value="<?= $item->SalePrice ?>" required>
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Discount Amount</label>
<input type="number" step="any" name="form[items][<?= $index ?>][DiscountAmount]" class="form-control DiscountAmount" value="0">
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Amount</label>
<input type="text" name="form[items][<?= $index ?>][TotalAmount]" class="form-control TotalAmount" value="<?= ($item->Quantity * $item->SalePrice) ?>" readonly style="background:#f5f5f5;">
</div>
</div>
<div class="col-md-1 col-sm-1">
<div class="form-group">
<label>&nbsp;</label>
<button type="button" class="btn btn-danger remove-item" style="width:100%;">X</button>
</div>
</div>
</div>
<?php 
    endforeach;
elseif($isNewSale):
    // FIX: Show one empty row for new sale (direct access)
?>
<div class="row item-row">
<div class="col-md-3 col-sm-3">
<div class="form-group">
<label>Item</label>
<select name="form[items][0][ItemID]" class="form-control select2 ItemID" style="width: 100%;">
<option value="">Select Item</option>
<?php foreach($items as $itemOption): ?>
<option value="<?= $itemOption->ItemID ?>" data-price="<?= $itemOption->SellingPrice ?>"><?= $itemOption->CategoryName ?> | <?= $itemOption->BrandName ?> | <?= $itemOption->ItemName ?></option>
<?php endforeach; ?>
</select>
<input type="text" name="form[items][0][Description]" class="form-control Description" placeholder="Item description/specifications..." value="" style="margin-top: 5px;">
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Qty</label>
<div class="input-group">
<input type="number" step="any" name="form[items][0][Quantity]" class="form-control Quantity" value="1" required>
<select name="form[items][0][QtyType]" class="form-control" required>
<?php foreach($qtyOptions as $option): ?>
<option value="<?= $option ?>"><?= $option ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Sale Price</label>
<input type="number" step="any" name="form[items][0][SalePrice]" class="form-control SalePrice" value="0" required>
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Discount Amount</label>
<input type="number" step="any" name="form[items][0][DiscountAmount]" class="form-control DiscountAmount" value="0">
</div>
</div>
<div class="col-md-2 col-sm-2">
<div class="form-group">
<label>Amount</label>
<input type="text" name="form[items][0][TotalAmount]" class="form-control TotalAmount" value="0.00" readonly style="background:#f5f5f5;">
</div>
</div>
<div class="col-md-1 col-sm-1">
<div class="form-group">
<label>&nbsp;</label>
<button type="button" class="btn btn-danger remove-item" style="width:100%;">X</button>
</div>
</div>
</div>
<?php endif; ?>
</div>

<button type="button" class="btn btn-success add-item" style="margin-top:15px;">Add Item</button>
<hr>

<div class="form-group">
    <label>Remarks / Description</label>
    <textarea name="form[Remarks]" class="form-control" rows="3" placeholder="General invoice description or remarks..."><?= isset($sale->Remarks) ? htmlspecialchars($sale->Remarks) : '' ?></textarea>
</div>

<div class="form-group">
<label>Total Amount</label>
<input type="text" id="TotalSaleAmount" class="form-control" value="0.00" readonly>
</div>

<div style="display: flex; gap: 10px;">
    <button type="submit" name="submit_action" value="save_invoice" class="btn btn-warning">Save Invoice</button>
    <button type="submit" name="submit_action" value="pay_receipt" class="btn btn-success">Pay Receipt</button>
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
    $('.select2').select2({ width: '100%' });
    
    var itemIndex = <?= (!empty($sale_items) ? count($sale_items) : ($isNewSale ? 1 : 0)) ?>;
    
    calculateGrandTotal();

    function calculateTotal(row){
        var quantity = parseFloat(row.find('.Quantity').val()) || 0;
        var price = parseFloat(row.find('.SalePrice').val()) || 0;
        var discountAmount = parseFloat(row.find('.DiscountAmount').val()) || 0;
        var total = (quantity * price) - discountAmount;
        if(total < 0) total = 0;
        row.find('.TotalAmount').val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal(){
        var grandTotal = 0;
        $('.item-row').each(function(){
            var amount = parseFloat($(this).find('.TotalAmount').val()) || 0;
            grandTotal += amount;
        });
        $('#TotalSaleAmount').val(grandTotal.toFixed(2));
    }

    $(document).on('input', '.Quantity, .SalePrice, .DiscountAmount', function(){
        calculateTotal($(this).closest('.item-row'));
    });

    $(document).on('click', '.remove-item', function(){
        if($('.item-row').length > 1){
            $(this).closest('.item-row').remove();
            calculateGrandTotal();
        } else {
            // Clear values instead of removing the last row
            var row = $(this).closest('.item-row');
            row.find('.Quantity').val('1');
            row.find('.SalePrice').val('0');
            row.find('.DiscountAmount').val('0');
            row.find('.TotalAmount').val('0.00');
            calculateGrandTotal();
            alert('At least one item is required. Clear values instead.');
        }
    });

    $(document).on('click', '.add-item', function(){
        var index = $('.item-row').length;
        var newRow = $('.item-row:first').clone();
        
        // Remove select2 generated elements
        newRow.find('.select2-container').remove();
        
        // Reset select2 classes and data
        var selectEl = newRow.find('select');
        selectEl.removeClass('select2-hidden-accessible')
                 .removeAttr('data-select2-id')
                 .removeAttr('aria-hidden')
                 .removeAttr('tabindex');
        selectEl.find('option').removeAttr('data-select2-id');
        
        // Reset input values
        newRow.find('input').val('');
        newRow.find('.Quantity').val('1');
        newRow.find('.SalePrice').val('0');
        newRow.find('.DiscountAmount').val('0');
        newRow.find('.TotalAmount').val('0.00');
        
        // Reset option to select first
        selectEl.val('');
        
        // Update name index
        newRow.find('input, select').each(function() {
            var name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            }
        });
        
        $('#items-container').append(newRow);
        
        // Re-initialize select2 specifically for the new row's select dropdowns
        newRow.find('.select2').select2({ width: '100%' });
        
        calculateGrandTotal();
    });

    // Auto-populate item price on select change
    $(document).on('change', '.ItemID', function() {
        var row = $(this).closest('.item-row');
        var price = $(this).find(':selected').data('price') || 0;
        row.find('.SalePrice').val(price);
        calculateTotal(row);
    });
});
</script>

<?php $this->view('includes/footer_end.php'); ?>