<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
<div class="content">
<div class="container">

<div class="row">
<div class="col-xs-12">
<div class="page-title-box">

<h4 class="page-title">Quotation</h4>

<ol class="breadcrumb p-0">
<li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
<li><a href="<?= base_url('admin/Quotation') ?>">Quotation</a></li>
<li class="active">Add Quotation</li>
</ol>

<div class="clearfix"></div>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">

<?= form_open('admin/quotation/save') ?>


<input type="hidden"
       name="form[QuotationID]"
       value="<?= isset($obj->QuotationID)
       ? $obj->QuotationID : 0 ?>">

<div class="card-box">

<div class="row">

<div class="col-lg-4">
<div class="form-group">
<label>Quotation No</label>

<input type="text"
       name="form[QuotationNo]"
       class="form-control"
       value="<?= isset($obj->QuotationNo)
       ? $obj->QuotationNo
       : (isset($quotation_no)
            ? $quotation_no
            : '') ?>"
       readonly>
</div>
</div>

<div class="col-lg-4">
<div class="form-group">
<label>Date</label>

<input type="date"
       name="form[QuotationDate]"
       class="form-control"
       value="<?= isset($obj->QuotationDate)
       ? date('Y-m-d', strtotime($obj->QuotationDate))
       : date('Y-m-d') ?>"
       required>
</div>
</div>

<div class="col-lg-4">
<div class="form-group">
<label>Customer</label>

<select name="form[CustomerID]"
        class="form-control select2"
        required>

<option value="">Select Customer</option>

<?php foreach($customers as $customer): ?>

<option value="<?= $customer->CustomerId ?>"

<?= isset($obj->CustomerID)
&& $obj->CustomerID == $customer->CustomerId
? 'selected'
: '' ?>>

<?= $customer->CompanyName ?>

</option>

<?php endforeach; ?>

</select>

</div>
</div>

<div class="col-lg-4" style="display: none;">
    <div class="form-group">

        <label>Domain Name</label>

        <select name="form[DomainID]"
                id="DomainID"
                class="form-control select2">

            <option value="">
                Select Domain
            </option>

        </select>

    </div>
</div>


</div>

<hr>

<div id="items-container">

<?php if(isset($quotation_items) && count($quotation_items) > 0) { ?>

    <?php foreach($quotation_items as $k => $qitem) { ?>

    <div class="row item-row">

        <div class="col-lg-4">
        <div class="form-group">

        <label>Service</label>

        <select name="form[items][<?= $k ?>][ItemID]"
                class="form-control select2 ItemID">

            <option value="">Select Service</option>

            <?php foreach($items as $item) { ?>

            <option value="<?= $item->ItemID ?>"
                    data-price="<?= $item->SellingPrice ?>"
                    <?= ($item->ItemID == $qitem->ItemID) ? 'selected' : '' ?>>

                <?= $item->ItemName ?>

            </option>

            <?php } ?>

        </select>

        </div>
        </div>

        <div class="col-lg-2">
        <div class="form-group">

        <label>Qty</label>

        <input type="number"
               step="0.01"
               name="form[items][<?= $k ?>][Qty]"
               class="form-control Qty"
               value="<?= $qitem->Qty ?>">

        </div>
        </div>

        <div class="col-lg-2">
        <div class="form-group">

        <label>Price</label>

        <input type="number"
               step="0.01"
               name="form[items][<?= $k ?>][UnitPrice]"
               class="form-control UnitPrice"
               value="<?= $qitem->UnitPrice ?>">

        </div>
        </div>

        <div class="col-lg-2">
        <div class="form-group">

        <label>Total</label>

        <input type="text"
               name="form[items][<?= $k ?>][TotalAmount]"
               class="form-control TotalAmount"
               value="<?= $qitem->TotalAmount ?>"
               readonly>

        </div>
        </div>

        <div class="col-lg-1">
        <div class="form-group">

        <label>&nbsp;</label>

        <button type="button"
                class="btn btn-danger remove-item">

            <i class="fa fa-times"></i>

        </button>

        </div>
        </div>



    <?php } ?>

<?php } else { ?>

    <div class="row item-row">

        <div class="col-lg-4">
        <div class="form-group">

        <label>Service</label>

        <select name="form[items][0][ItemID]"
                class="form-control select2 ItemID">

            <option value="">Select Service</option>

            <?php foreach($items as $item) { ?>

            <option value="<?= $item->ItemID ?>"
                    data-price="<?= $item->SellingPrice ?>">

                <?= $item->ItemName ?>

            </option>

            <?php } ?>

        </select>

        </div>
        </div>

        <div class="col-lg-2">
        <div class="form-group">

        <label>Qty</label>

        <input type="number"
               step="0.01"
               name="form[items][0][Qty]"
               class="form-control Qty"
               value="1">

        </div>
        </div>

        <div class="col-lg-2">
        <div class="form-group">

        <label>Price</label>

        <input type="number"
               step="0.01"
               name="form[items][0][UnitPrice]"
               class="form-control UnitPrice"
               value="0">

        </div>
        </div>

        <div class="col-lg-2">
        <div class="form-group">

        <label>Total</label>

        <input type="text"
               name="form[items][0][TotalAmount]"
               class="form-control TotalAmount"
               value="0.00"
               readonly>

        </div>
        </div>

<?php } ?>

<div class="col-lg-1">
<div class="form-group">

<label>&nbsp;</label>



</div>
</div>

</div>

</div>

<button type="button"
        class="btn btn-success add-item">

Add Service

</button>

<hr>

<div class="row">

<div class="col-lg-4">
<div class="form-group">

<label>Sub Total</label>

<input type="text"
       id="SubTotal"
       name="form[SubTotal]"
       class="form-control"
       value="<?= isset($obj->SubTotal)
       ? $obj->SubTotal
       : '0.00' ?>"
       readonly>

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>Discount</label>

<input type="number"
       step="0.01"
       id="DiscountAmount"
       name="form[DiscountAmount]"
       class="form-control"
       value="<?= isset($obj->DiscountAmount)
       ? $obj->DiscountAmount
       : '0' ?>">

</div>
</div>

<div class="col-lg-4">
<div class="form-group">

<label>Grand Total</label>

<input type="text"
       id="GrandTotal"
       name="form[GrandTotal]"
       class="form-control"
       value="<?= isset($obj->GrandTotal)
       ? $obj->GrandTotal
       : '0.00' ?>"
       readonly>

</div>
</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea name="form[Remarks]"
          class="form-control"></textarea>

</div>

<button type="submit"
        class="btn btn-primary">

Submit

</button>

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

    function calculateTotal(row)
    {
        var qty = parseFloat(row.find('.Qty').val()) || 0;
        var price = parseFloat(row.find('.UnitPrice').val()) || 0;

        var total = qty * price;

        row.find('.TotalAmount').val(total.toFixed(2));

        calculateGrandTotal();
    }

    function calculateGrandTotal()
    {
        var subtotal = 0;

        $('.TotalAmount').each(function(){

            subtotal += parseFloat($(this).val()) || 0;

        });

        $('#SubTotal').val(subtotal.toFixed(2));

        var discount = parseFloat($('#DiscountAmount').val()) || 0;

        $('#GrandTotal').val((subtotal - discount).toFixed(2));
    }


    $('select[name="form[CustomerID]"]').change(function(){

        var customerID = $(this).val();

        $.ajax({

            url : '<?= base_url("admin/quotation/get_domains") ?>',

            type : 'POST',

            data : {
                CustomerID : customerID
            },

            dataType : 'json',

            success : function(data)
            {
                var html = '<option value="">Select Domain</option>';

                $.each(data, function(i,row){

                    html += '<option value="'+row.DomainID+'">'+
                                row.DomainName+
                            '</option>';

                });

                $('#DomainID').html(html);

            }

        });

    });

    $(document).on('change','.ItemID', function(){

        var row = $(this).closest('.item-row');

        var price = $(this).find(':selected').data('price');

        row.find('.UnitPrice').val(price);

        calculateTotal(row);

    });

    $(document).on('keyup','.Qty,.UnitPrice,#DiscountAmount', function(){

        var row = $(this).closest('.item-row');

        calculateTotal(row);

    });

    $(document).on('click','.remove-item', function(){

        $(this).closest('.item-row').remove();

        calculateGrandTotal();

    });

    $('.add-item').click(function(){

        var index = $('.item-row').length;

        var newRow = `

        <div class="row item-row">

            <div class="col-lg-4">
                <div class="form-group">

                    <label>Service</label>

                    <select name="form[items][${index}][ItemID]"
                            class="form-control select2 ItemID"
                            required>

                        <option value="">Select Service</option>

                        <?php foreach($items as $item): ?>

                        <option value="<?= $item->ItemID ?>"
                                data-price="<?= $item->SellingPrice ?>">

                            <?= $item->ItemName ?>

                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">

                    <label>Qty</label>

                    <input type="number"
                           step="0.01"
                           name="form[items][${index}][Qty]"
                           class="form-control Qty"
                           value="1">

                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">

                    <label>Price</label>

                    <input type="number"
                           step="0.01"
                           name="form[items][${index}][UnitPrice]"
                           class="form-control UnitPrice"
                           value="0">

                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">

                    <label>Total</label>

                    <input type="text"
                           name="form[items][${index}][TotalAmount]"
                           class="form-control TotalAmount"
                           value="0.00"
                           readonly>

                </div>
            </div>

            <div class="col-lg-1">
                <div class="form-group">

                    <label>&nbsp;</label>

                    <button type="button"
                            class="btn btn-danger remove-item">

                        <i class="fa fa-times"></i>

                    </button>

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