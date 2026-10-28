<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>


<div class="content-page">

    <div class="content">

        <div class="container">

            <!-- PAGE TITLE -->
            <div class="row">

                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">

                            Items & Services

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li>

                                <a href="<?= base_url('admin/items') ?>">

                                    Items

                                </a>

                            </li>

                            <li class="active">

                                <?= empty($obj->ItemID)
                                    ? "Create"
                                    : "Edit" ?>

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>


            <?php $this->view('includes/notification.php'); ?>


            <div class="row">

                <div class="col-sm-12">

                    <?= form_open_multipart() ?>

                    <div class="card-box" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">

                        <h4 class="header-title m-t-0 m-b-30" style="font-size: 18px; font-weight: 700; color: #1e293b; border-bottom: 2px solid #1abc9c; padding-bottom: 10px; display: inline-block;">
                            New Item / Service
                        </h4>

                        <!-- Custom Zoho-like Styles -->
                        <style>
                            .zoho-section-header {
                                border-bottom: 1px solid #cbd5e1;
                                padding-bottom: 8px;
                                margin-top: 30px;
                                margin-bottom: 20px;
                                font-size: 15px;
                                font-weight: 700;
                                color: #334155;
                                display: flex;
                                align-items: center;
                            }
                            .zoho-section-header input[type="checkbox"] {
                                margin-right: 10px;
                                transform: scale(1.15);
                                cursor: pointer;
                                margin-top: 0;
                            }
                            .zoho-radio-group {
                                display: flex;
                                gap: 20px;
                                align-items: center;
                                height: 38px;
                            }
                            .zoho-radio-group label {
                                font-weight: 500;
                                color: #475569;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                                margin-bottom: 0;
                            }
                            .zoho-radio-group input[type="radio"] {
                                margin: 0;
                                transform: scale(1.1);
                                cursor: pointer;
                            }
                            .zoho-image-uploader {
                                border: 2px dashed #cbd5e1;
                                border-radius: 10px;
                                height: 190px;
                                position: relative;
                                background: #f8fafc;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                cursor: pointer;
                                transition: all 0.2s ease-in-out;
                                margin-top: 5px;
                            }
                            .zoho-image-uploader:hover {
                                border-color: #1abc9c;
                                background: #f0fdf4;
                            }
                            .zoho-image-uploader input[type="file"] {
                                position: absolute;
                                width: 100%;
                                height: 100%;
                                opacity: 0;
                                cursor: pointer;
                                z-index: 10;
                            }
                            .zoho-image-preview-placeholder {
                                text-align: center;
                                color: #64748b;
                                font-size: 13px;
                            }
                            .zoho-image-preview-placeholder img {
                                max-height: 170px;
                                max-width: 100%;
                                border-radius: 6px;
                            }
                            .zoho-image-preview-placeholder i {
                                color: #94a3b8;
                                margin-bottom: 8px;
                                display: block;
                            }
                            .form-control {
                                border-radius: 6px;
                                border: 1px solid #cbd5e1;
                                height: 38px;
                                transition: all 0.2s;
                            }
                            .form-control:focus {
                                border-color: #1abc9c;
                                box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15);
                            }
                            textarea.form-control {
                                height: auto;
                            }
                        </style>

                        <!-- Basic Information Row -->
                        <div class="row">
                            
                            <!-- Left Fields Column -->
                            <div class="col-md-8">
                                <div class="row">
                                    
                                    <!-- Name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Name *</label>
                                            <input type="text" name="form[ItemName]" value="<?= set_value('form[ItemName]', $obj->ItemName) ?>" class="form-control" placeholder="Item or Service Name">
                                            <?= form_error('form[ItemName]') ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Type -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600; display: block; margin-bottom: 5px;">Type</label>
                                            <div class="zoho-radio-group">
                                                <label>
                                                    <input type="radio" name="form[ItemType]" value="Product" id="typeGoods" <?= (empty($obj->ItemType) || $obj->ItemType == 'Product') ? 'checked' : '' ?>>
                                                    Goods
                                                </label>
                                                <label>
                                                    <input type="radio" name="form[ItemType]" value="Service" id="typeService" <?= ($obj->ItemType == 'Service') ? 'checked' : '' ?>>
                                                    Service
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Unit -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Unit</label>
                                            <select name="form[Unit]" class="form-control">
                                                <option value="Pcs" <?= (empty($obj->Unit) || $obj->Unit == 'Pcs') ? 'selected' : '' ?>>Pcs</option>
                                                <option value="Box" <?= ($obj->Unit == 'Box') ? 'selected' : '' ?>>Box</option>
                                                <option value="Ltr" <?= ($obj->Unit == 'Ltr') ? 'selected' : '' ?>>Ltr</option>
                                                <option value="Kg" <?= ($obj->Unit == 'Kg') ? 'selected' : '' ?>>Kg</option>
                                                <option value="Mtr" <?= ($obj->Unit == 'Mtr') ? 'selected' : '' ?>>Mtr</option>
                                                <option value="Pkt" <?= ($obj->Unit == 'Pkt') ? 'selected' : '' ?>>Pkt</option>
                                                <option value="Doz" <?= ($obj->Unit == 'Doz') ? 'selected' : '' ?>>Doz</option>
                                                <option value="Set" <?= ($obj->Unit == 'Set') ? 'selected' : '' ?>>Set</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Item Code / Barcode -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Item Code / Barcode</label>
                                            <input type="text" name="form[ItemCode]" value="<?= set_value('form[ItemCode]', isset($obj->ItemCode) ? $obj->ItemCode : '') ?>" class="form-control" placeholder="Barcode SKU / Serial Number">
                                        </div>
                                    </div>

                                    <!-- Category -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Category</label>
                                            <select id="CategoryID" name="form[CategoryID]" class="form-control">
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category->CategoryID ?>" <?= ($obj->CategoryID == $category->CategoryID) ? 'selected' : '' ?>><?= $category->CategoryName ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Brand -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Brand</label>
                                            <select id="BrandID" name="form[BrandID]" class="form-control">
                                                <option value="">Select Brand</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Image Column -->
                            <div class="col-md-4">
                                <label style="color: #475569; font-weight: 600; display: block; margin-bottom: 8px;">Product Image</label>
                                <div class="zoho-image-uploader">
                                    <input type="file" name="image" id="itemImageFile" accept="image/*">
                                    <div class="zoho-image-preview-placeholder">
                                        <?php if(!empty($obj->Image)): ?>
                                            <img src="<?= base_url($obj->Image) ?>" id="itemImagePreview">
                                        <?php else: ?>
                                            <i class="fa fa-picture-o fa-3x"></i>
                                            <div style="font-weight: 600; color: #1abc9c; margin-top: 5px;">Browse image</div>
                                            <div style="font-size: 11px; margin-top: 2px;">Drag and drop or select file</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 1. SALES INFORMATION -->
                        <div class="zoho-section-header">
                            <input type="checkbox" id="chkSalesInfo" checked>
                            <span>Sales Information</span>
                        </div>
                        <div id="salesInfoSection">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Selling Price (Rs.) *</label>
                                        <input type="number" step="0.01" name="form[SellingPrice]" value="<?= set_value('form[SellingPrice]', $obj->SellingPrice) ?>" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Sales Account</label>
                                        <select class="form-control" disabled>
                                            <option>Sales</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Description</label>
                                        <textarea name="form[ItemDescription]" rows="3" class="form-control" placeholder="Description visible on invoices"><?= set_value('form[ItemDescription]', $obj->ItemDescription) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. PURCHASE INFORMATION -->
                        <div class="zoho-section-header">
                            <input type="checkbox" id="chkPurchaseInfo" checked>
                            <span>Purchase Information</span>
                        </div>
                        <div id="purchaseInfoSection">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Cost Price (Rs.) *</label>
                                        <input type="number" step="0.01" name="form[PurchasePrice]" value="<?= set_value('form[PurchasePrice]', $obj->PurchasePrice) ?>" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Purchase Account</label>
                                        <select class="form-control" disabled>
                                            <option>Cost of Goods Sold</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Preferred Vendor</label>
                                        <select name="form[PreferredVendorID]" class="form-control select2" style="width: 100%;">
                                            <option value="">-- Select Preferred Vendor --</option>
                                            <?php foreach ($vendors as $vendor): ?>
                                                <option value="<?= $vendor->VendorID ?>" <?= ($obj->PreferredVendorID == $vendor->VendorID) ? 'selected' : '' ?>><?= $vendor->VendorName ?> (<?= $vendor->CompanyName ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. INVENTORY VALUATION (GOODS ONLY) -->
                        <div class="product-fields">
                            <div class="zoho-section-header">
                                <input type="checkbox" id="chkInventoryInfo" checked>
                                <span>Track Inventory for this item</span>
                            </div>
                            <div id="inventoryInfoSection">
                                <div class="row">
                                    
                                    <!-- Opening Stock -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Opening Stock</label>
                                            <input type="number" name="form[Stock]" value="<?= set_value('form[Stock]', $obj->Stock) ?>" class="form-control" placeholder="0">
                                        </div>
                                    </div>
                                    
                                    <!-- Dimensions -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Dimensions (L x W x H)</label>
                                            <div class="row">
                                                <div class="col-xs-3" style="padding-right: 2px;">
                                                    <input type="number" step="0.01" name="form[Length]" placeholder="L" value="<?= set_value('form[Length]', isset($obj->Length) ? $obj->Length : '') ?>" class="form-control" style="padding: 6px 4px; text-align: center;">
                                                </div>
                                                <div class="col-xs-3" style="padding-left: 2px; padding-right: 2px;">
                                                    <input type="number" step="0.01" name="form[Width]" placeholder="W" value="<?= set_value('form[Width]', isset($obj->Width) ? $obj->Width : '') ?>" class="form-control" style="padding: 6px 4px; text-align: center;">
                                                </div>
                                                <div class="col-xs-3" style="padding-left: 2px; padding-right: 2px;">
                                                    <input type="number" step="0.01" name="form[Height]" placeholder="H" value="<?= set_value('form[Height]', isset($obj->Height) ? $obj->Height : '') ?>" class="form-control" style="padding: 6px 4px; text-align: center;">
                                                </div>
                                                <div class="col-xs-3" style="padding-left: 2px;">
                                                    <select name="form[DimensionUnit]" class="form-control" style="padding: 6px 2px;">
                                                        <option value="cm" <?= (isset($obj->DimensionUnit) && $obj->DimensionUnit == 'cm') ? 'selected' : '' ?>>cm</option>
                                                        <option value="in" <?= (isset($obj->DimensionUnit) && $obj->DimensionUnit == 'in') ? 'selected' : '' ?>>in</option>
                                                        <option value="mm" <?= (isset($obj->DimensionUnit) && $obj->DimensionUnit == 'mm') ? 'selected' : '' ?>>mm</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Weight -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label style="color: #475569; font-weight: 600;">Weight</label>
                                            <div class="row">
                                                <div class="col-xs-8" style="padding-right: 2px;">
                                                    <input type="number" step="0.01" name="form[Weight]" placeholder="Weight" value="<?= set_value('form[Weight]', isset($obj->Weight) ? $obj->Weight : '') ?>" class="form-control">
                                                </div>
                                                <div class="col-xs-4" style="padding-left: 2px;">
                                                    <select name="form[WeightUnit]" class="form-control" style="padding: 6px 2px;">
                                                        <option value="kg" <?= (isset($obj->WeightUnit) && $obj->WeightUnit == 'kg') ? 'selected' : '' ?>>kg</option>
                                                        <option value="g" <?= (isset($obj->WeightUnit) && $obj->WeightUnit == 'g') ? 'selected' : '' ?>>g</option>
                                                        <option value="lb" <?= (isset($obj->WeightUnit) && $obj->WeightUnit == 'lb') ? 'selected' : '' ?>>lb</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- 4. SERVICE CONFIG (SERVICES ONLY) -->
                        <div class="service-fields">
                            <div class="zoho-section-header">
                                <span>Service Period & Configuration</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Service Duration</label>
                                        <input type="text" name="form[ServiceDuration]" value="<?= set_value('form[ServiceDuration]', isset($obj->ServiceDuration) ? $obj->ServiceDuration : '') ?>" class="form-control" placeholder="1 Year / 6 Months">
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Domain Association</label>
                                        <input type="text" name="form[DomainName]" value="<?= set_value('form[DomainName]', isset($obj->DomainName) ? $obj->DomainName : '') ?>" class="form-control" placeholder="example.com">
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label style="color: #475569; font-weight: 600;">Renewal Date</label>
                                        <input type="date" name="form[RenewalDate]" value="<?= set_value('form[RenewalDate]', isset($obj->RenewalDate) ? $obj->RenewalDate : '') ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <button class="btn btn-success pull-right">

                        <i class="fa fa-save"></i>

                        Save

                    </button>

                    <?= form_close() ?>

                </div>

            </div>

        </div>

    </div>

</div>


<?php $this->view('includes/footer_start.php') ?>


<script>

$(document).ready(function () {

    // =====================================
    // PRODUCT / SERVICE DISPLAY
    // =====================================

    function toggleFields()
    {
        var itemType = $('input[name="form[ItemType]"]:checked').val();

        if(itemType == 'Service')
        {
            $('.service-fields').show();
            $('.product-fields').hide();
        }
        else
        {
            $('.service-fields').hide();
            $('.product-fields').show();
        }
    }

    toggleFields();

    $('input[name="form[ItemType]"]').change(function(){
        toggleFields();
    });

    // Section toggles
    $('#chkSalesInfo').change(function() {
        if ($(this).is(':checked')) {
            $('#salesInfoSection').slideDown();
        } else {
            $('#salesInfoSection').slideUp();
        }
    });

    $('#chkPurchaseInfo').change(function() {
        if ($(this).is(':checked')) {
            $('#purchaseInfoSection').slideDown();
        } else {
            $('#purchaseInfoSection').slideUp();
        }
    });

    $('#chkInventoryInfo').change(function() {
        if ($(this).is(':checked')) {
            $('#inventoryInfoSection').slideDown();
        } else {
            $('#inventoryInfoSection').slideUp();
        }
    });

    // Client-side image preview
    $('#itemImageFile').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.zoho-image-preview-placeholder').html('<img src="' + e.target.result + '" id="itemImagePreview">');
            }
            reader.readAsDataURL(file);
        }
    });


    // =====================================
    // BRAND LOAD
    // =====================================

    var selectedCategory = $('#CategoryID').val();

    var selectedBrand = "<?= $obj->BrandID ?>";


    function loadBrands(categoryID, selectedBrand)
    {
        if (categoryID)
        {
            $.ajax({

                url: '<?= base_url('admin/Items/get_brands_by_category') ?>',

                type: 'POST',

                data: {
                    category_id: categoryID
                },

                success: function (response)
                {
                    var brands = JSON.parse(response);

                    $('#BrandID').empty();

                    $('#BrandID').append(
                        '<option value="">Select Brand</option>'
                    );

                    $.each(brands, function (index, brand)
                    {
                        var selected =
                            (brand.BrandID == selectedBrand)
                            ? 'selected'
                            : '';

                        $('#BrandID').append(
                            '<option value="' + brand.BrandID + '" '
                            + selected + '>'
                            + brand.BrandName +
                            '</option>'
                        );
                    });
                }
            });
        }
    }


    $('#CategoryID').change(function ()
    {
        var categoryID = $(this).val();

        loadBrands(categoryID, null);
    });


    if (selectedCategory)
    {
        loadBrands(selectedCategory, selectedBrand);
    }

});

</script>


<?php $this->view('includes/footer_end.php') ?>