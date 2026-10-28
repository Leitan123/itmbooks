<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
<div class="page-title-box">
<h4 class="page-title">GRN (Goods Received Note)</h4>
<ol class="breadcrumb p-0">
<li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
<li><a href="<?= base_url('admin/grn') ?>">GRN</a></li>
<li class="active">Add/Edit GRN</li>
</ol>
<div class="clearfix"></div>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">

<?= form_open('admin/grn/save') ?>

<input type="hidden" name="form[GrnID]" value="<?= isset($obj->GrnID) ? $obj->GrnID : 0 ?>">

<div class="card-box">
<div class="row">

<div class="col-lg-3">
<div class="form-group">
<label>GRN Number</label>
<input type="text" name="form[GrnNo]" class="form-control" value="<?= isset($obj->GrnNo) ? $obj->GrnNo : (isset($grn_no) ? $grn_no : '') ?>" readonly>
</div>
</div>

<div class="col-lg-3">
<div class="form-group">
<label>Date</label>
<input type="date" name="form[GrnDate]" class="form-control" value="<?= isset($obj->GrnDate) ? date('Y-m-d', strtotime($obj->GrnDate)) : date('Y-m-d') ?>" required>
</div>
</div>

<div class="col-lg-3">
<div class="form-group">
<label>Vendor</label>
<select name="form[VendorID]" class="form-control select2" required>
<option value="">Select Vendor</option>
<?php foreach($vendors as $vendor): ?>
<option value="<?= $vendor->VendorID ?>" <?= isset($obj->VendorID) && $obj->VendorID == $vendor->VendorID ? 'selected' : '' ?>>
    <?= $vendor->VendorName ?>
</option>
<?php endforeach; ?>
</select>
</div>
</div>

<div class="col-lg-3">
<div class="form-group">
<label>PO Reference</label>
<select name="form[PurchaseOrderNo]" class="form-control select2">
<option value="">-- None / Direct --</option>
<?php foreach($purchase_orders as $po): ?>
<option value="<?= $po->PurchaseOrderNo ?>" <?= isset($obj->PurchaseOrderNo) && $obj->PurchaseOrderNo == $po->PurchaseOrderNo ? 'selected' : '' ?>>
    <?= $po->PurchaseOrderNo ?> (Rs. <?= number_format($po->GrandTotal, 2) ?>)
</option>
<?php endforeach; ?>
</select>
</div>
</div>

</div>

<hr>

<div id="items-container">
<?php if(isset($grn_items) && count($grn_items) > 0) { ?>
    <?php foreach($grn_items as $k => $gitem) { ?>
    <div class="row item-row">
        <div class="col-lg-8">
            <div class="form-group">
                <label>Received Item</label>
                <select name="form[items][<?= $k ?>][ItemID]" class="form-control select2 ItemID">
                    <option value="">Select Item</option>
                    <?php foreach($items as $item) { ?>
                    <option value="<?= $item->ItemID ?>" <?= ($item->ItemID == $gitem->ItemID) ? 'selected' : '' ?>>
                        <?= $item->ItemName ?>
                    </option>
                    <?php } ?>
                </select>
                <input type="text" name="form[items][<?= $k ?>][Description]" class="form-control Description" placeholder="Item description/specifications..." value="<?= isset($gitem->Description) ? htmlspecialchars($gitem->Description) : '' ?>" style="margin-top: 5px;">
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>Qty Received</label>
                <input type="number" step="0.01" name="form[items][<?= $k ?>][QtyReceived]" class="form-control" value="<?= $gitem->QtyReceived ?>">
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
        <div class="col-lg-8">
            <div class="form-group">
                <label>Received Item</label>
                <select name="form[items][0][ItemID]" class="form-control select2 ItemID">
                    <option value="">Select Item</option>
                    <?php foreach($items as $item) { ?>
                    <option value="<?= $item->ItemID ?>">
                        <?= $item->ItemName ?>
                    </option>
                    <?php } ?>
                </select>
                <input type="text" name="form[items][0][Description]" class="form-control Description" placeholder="Item description/specifications..." value="" style="margin-top: 5px;">
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label>Qty Received</label>
                <input type="number" step="0.01" name="form[items][0][QtyReceived]" class="form-control" value="1">
            </div>
        </div>
    </div>
<?php } ?>
</div>

<button type="button" class="btn btn-success add-item">Add Item Line</button>

<hr>

<div class="form-group">
    <label>Remarks / Notes</label>
    <textarea name="form[Remarks]" class="form-control" rows="3"><?= isset($obj->Remarks) ? $obj->Remarks : '' ?></textarea>
</div>

<button type="submit" class="btn btn-primary">Save GRN</button>

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
    
    $('select[name="form[PurchaseOrderNo]"]').change(function(){
        var poNo = $(this).val();
        if (poNo) {
            $.ajax({
                url: '<?= base_url("admin/grn/get_po_details") ?>/' + encodeURIComponent(poNo),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Set the vendor ID
                        $('select[name="form[VendorID]"]').val(response.po.VendorID).trigger('change');
                        
                        // Clear items container
                        $('#items-container').empty();
                        
                        // Append PO items to the container
                        if (response.items && response.items.length > 0) {
                            $.each(response.items, function(k, item) {
                                var index = $('#items-container .item-row').length;
                                var newRow = `
                                <div class="row item-row" style="margin-top: 10px;">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>Received Item</label>
                                            <select name="form[items][${index}][ItemID]" class="form-control select2 ItemID" required>
                                                <option value="">Select Item</option>
                                                <?php foreach($items as $itm): ?>
                                                <option value="<?= $itm->ItemID ?>" ${item.ItemID == '<?= $itm->ItemID ?>' ? 'selected' : ''}>
                                                    <?= $itm->ItemName ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="text" name="form[items][${index}][Description]" class="form-control Description" placeholder="Item description/specifications..." value="${item.Description || ''}" style="margin-top: 5px;">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>Qty Received</label>
                                            <input type="number" step="0.01" name="form[items][${index}][QtyReceived]" class="form-control" value="${item.Qty || 1}">
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
                            });
                            $('#items-container .select2').select2();
                        }
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    $(document).on('click','.remove-item', function(){
        $(this).closest('.item-row').remove();
    });

    $('.add-item').click(function(){
        var index = $('.item-row').length;
        var newRow = `
        <div class="row item-row" style="margin-top: 10px;">
            <div class="col-lg-8">
                <div class="form-group">
                    <label>Received Item</label>
                    <select name="form[items][${index}][ItemID]" class="form-control select2 ItemID" required>
                        <option value="">Select Item</option>
                        <?php foreach($items as $item): ?>
                        <option value="<?= $item->ItemID ?>">
                            <?= $item->ItemName ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="form[items][${index}][Description]" class="form-control Description" placeholder="Item description/specifications..." value="" style="margin-top: 5px;">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label>Qty Received</label>
                    <input type="number" step="0.01" name="form[items][${index}][QtyReceived]" class="form-control" value="1">
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
