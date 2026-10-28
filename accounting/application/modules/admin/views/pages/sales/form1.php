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
                            <li class="active"><?= isset($obj) ? "Edit Sale" : "Add Sale" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>

                    <input type="hidden" name="SaleID" value="<?= isset($sale->SaleID) ? $sale->SaleID : '' ?>">

                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30" style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-weight: bold;"><?= isset($obj) ? "Edit Sale" : "Add Sale" ?></div>
                            <div style="display: flex; gap: 20px; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <label for="CustomerType" style="margin: 0;">Customer Type:</label>
                                    <input type="text" id="CustomerType" class="form-control" style="width: 150px;" readonly>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <label for="CreditLimit" style="margin: 0;">Credit Limit:</label>
                                    <input type="text" id="CreditLimit" class="form-control" style="width: 150px;" readonly>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <label for="CreditBalance" style="margin: 0;">Credit Balance:</label>
                                    <input type="text" id="CreditBalance" class="form-control" style="width: 150px;" readonly>
                                </div>
                            </div>
                        </h4>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sale_date">Sale Date</label>
                                    <input type="date" id="saleDate" name="form[SaleDate]" class="form-control" value="<?= isset($obj->SaleDate) ? date('Y-m-d', strtotime($obj->SaleDate)) : date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="po_no">PO No</label>
                                    <input type="text" id="po_no" name="form[PONo]" class="form-control" value="<?= isset($sale->PONo) ? htmlspecialchars($sale->PONo) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ref_no">Ref No</label>
                                    <input type="text" id="ref_no" name="form[RefNo]" class="form-control" value="<?= isset($sale->RefNo) ? htmlspecialchars($sale->RefNo) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="customer_select">Customer</label>
                            <select name="form[CustomerId]" id="customer_select" class="form-control select2">
                                <option value="">-- Select Customer --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer->CustomerId ?>" <?= (isset($sale->CustomerId) && $customer->CustomerId == $sale->CustomerId) ? 'selected' : '' ?> required>
                                        <?= $customer->CustomerName ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="items-container">
                            <?php 
                            $qtyOptions = ['Pcs','Boxes','Kg','Gr','Bar','Tubes','Rolls','Sheets','Bottle','Pairs','Packets','Cartons','Meter','Feet'];
                            if (!empty($sale_items)): ?>
                                <?php foreach ($sale_items as $index => $item): ?>
                                    <div class="row item-row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <select name="form[items][<?= $index ?>][ItemID]" class="form-control select2 ItemID" id="ItemID_<?= $index ?>" required>
                                                    <option value="">Select Item</option>
                                                    <?php foreach ($items as $itemOption): ?>
                                                        <option value="<?= $itemOption->ItemID ?>" <?= ($itemOption->ItemID == $item->ItemID) ? 'selected' : '' ?>>
                                                            <?= $itemOption->CategoryName ?> | <?= $itemOption->BrandName ?> | <?= $itemOption->ItemName ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Qty</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" name="form[items][<?= $index ?>][Quantity]" class="form-control Quantity" value="<?= $item->Quantity ?>" required>
                                                    <select name="form[items][<?= $index ?>][QtyType]" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($qtyOptions as $option): ?>
                                                            <option value="<?= $option ?>" <?= (isset($item->QtyType) && $item->QtyType == $option) ? 'selected' : '' ?>><?= $option ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Sale Price</label>
                                                <input type="number" step="any" name="form[items][<?= $index ?>][SalePrice]" class="form-control SalePrice" value="<?= $item->SalePrice ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>Disc %</label>
                                                <input type="number" step="any" name="form[items][<?= $index ?>][DiscountPercent]" class="form-control DiscountPercent" value="<?= isset($item->DiscountPercent) ? $item->DiscountPercent : 0 ?>">
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="text" name="form[items][<?= $index ?>][TotalAmount]" class="form-control TotalAmount" value="<?= ($item->Quantity * $item->SalePrice) - (($item->Quantity * $item->SalePrice) * (isset($item->DiscountPercent) ? $item->DiscountPercent : 0)/100) ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger remove-item" style="width: 100%;">X</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-success add-item" style="margin-top: 15px;">Add Item</button>
                        <hr>

                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" id="TotalSaleAmount" class="form-control" value="<?= isset($sale->TotalSaleAmount) ? number_format($sale->TotalSaleAmount, 2) : '0.00' ?>" readonly>
                        </div>

                        <div id="creditAlert" class="alert alert-danger" style="display:none;">
                            ⚠ Customer Credit Limit Exceeded! Please reduce sale amount.
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-success">Save</button>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>

<script type="text/javascript">
$(document).ready(function () {

    // Fetch customer credit details when customer changed
    $("#customer_select").on("change", function () {
        let customerId = $(this).val();
        
        $.ajax({
            url: "<?= base_url('admin/Sales/get_customer_details'); ?>",
            type: "POST",
            data: { customerId: customerId },
            dataType: "json",
            success: function (data) {
                if (!data.error) {
                    $("#CustomerType").val(data.CustomerType);
                    $("#CreditLimit").val(data.CreditLimit);
                    $("#CreditBalance").val(data.CreditBalance);

                    updateCreditWarning();
                }
            }
        });
    });

    // Recalculate total & validate credit on changes
    $(document).on("input", ".itemQty, .itemPrice, .itemDiscount", function () {
        updateCreditWarning();
    });

    function updateCreditWarning() {
        let creditBal = parseFloat($("#CreditBalance").val()) || 0;
        let totalSale = calculateTotal();

        if (totalSale > creditBal) {
            $("#CreditBalance").css("color", "red");
            $("#submitBtn").prop("disabled", true);
            $("#creditAlert").show();
        } else {
            $("#CreditBalance").css("color", "black");
            $("#submitBtn").prop("disabled", false);
            $("#creditAlert").hide();
        }
    }

    function calculateTotal() {
        let total = 0;
        $(".sale-item-row").each(function () {
            let qty = parseFloat($(this).find(".itemQty").val()) || 0;
            let price = parseFloat($(this).find(".itemPrice").val()) || 0;
            let discount = parseFloat($(this).find(".itemDiscount").val()) || 0;

            let sub = qty * price;
            let discountAmt = (sub * discount) / 100;
            total += (sub - discountAmt);
        });
        return total;
    }

});


$(document).ready(function(){
    $('select.select2').select2();

    var qtyOptions = ['Pcs','Boxes','Kg','Gr','Bar','Tubes','Rolls','Sheets','Bottle','Pairs','Packets','Cartons','Meter','Feet'];

    function calculateTotal(row){
        var quantity = parseFloat(row.find('.Quantity').val()) || 0;
        var price = parseFloat(row.find('.SalePrice').val()) || 0;
        var discountPercent = parseFloat(row.find('.DiscountPercent').val()) || 0;
        var total = (quantity * price) - ((quantity * price) * discountPercent / 100);
        row.find('.TotalAmount').val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal(){
        var grandTotal = 0;
        $('.TotalAmount').each(function(){
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#TotalSaleAmount').val(grandTotal.toFixed(2));
    }

    $('.add-item').click(function(){
        var index = $('#items-container .item-row').length;
        var newRow = getNewItemRow(index);
        $('#items-container').append(newRow);
        $('#ItemID_' + index).select2();
    });

    $(document).on('click', '.remove-item', function(){
        $(this).closest('.item-row').remove();
        calculateGrandTotal();
    });

    $(document).on('input', '.Quantity, .SalePrice, .DiscountPercent', function(){
        var row = $(this).closest('.item-row');
        calculateTotal(row);
    });

    function getNewItemRow(index){
        var options = '<option value="">-- Select --</option>'; // <-- default first option
        var qtyOptions = ['Pcs','Boxes','Kg','Gr','Bar','Tubes','Rolls','Sheets','Bottle','Pairs','Packets','Cartons','Meter','Feet'];
        for(var i=0;i<qtyOptions.length;i++){
            options += '<option value="'+qtyOptions[i]+'">'+qtyOptions[i]+'</option>';
        }
        return `
        <div class="row item-row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label>Item</label>
                    <select name="form[items][${index}][ItemID]" id="ItemID_${index}" class="form-control" required>
                        <option value="">Select Item</option>
                        <?php foreach($items as $item): ?>
                            <option value="<?= $item->ItemID ?>"><?= $item->CategoryName ?> | <?= $item->BrandName ?> | <?= $item->ItemName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Qty</label>
                    <div class="input-group">
                        <input type="number" step="any" name="form[items][${index}][Quantity]" class="form-control Quantity" required>
                        <select name="form[items][${index}][QtyType]" class="form-control" required>` + options + `</select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Sale Price</label>
                    <input type="number" step="any" name="form[items][${index}][SalePrice]" class="form-control SalePrice" required>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>Disc %</label>
                    <input type="number" step="any" name="form[items][${index}][DiscountPercent]" class="form-control DiscountPercent" value="0">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label>Amount</label>
                    <input type="text" name="form[items][${index}][TotalAmount]" class="form-control TotalAmount" readonly>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-item" style="width:100%;">X</button>
                </div>
            </div>
        </div>
        `;
    }
});
</script>

<?php $this->view('includes/footer_end.php'); ?>
