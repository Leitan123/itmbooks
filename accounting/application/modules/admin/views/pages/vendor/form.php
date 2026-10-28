<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<style>
    .zoho-form-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #e2e8f0;
    }
    .form-group label {
        font-weight: 500;
        color: #4a5568;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .form-control {
        border-radius: 4px;
        border: 1px solid #cbd5e0;
        height: 38px;
        font-size: 13px;
        transition: border-color 0.15s ease-in-out;
    }
    .form-control:focus {
        border-color: #3182ce;
        box-shadow: 0 0 0 1px #3182ce;
    }
    .required:after {
        content: " *";
        color: #e53e3e;
        font-weight: bold;
    }
    .radio-label {
        font-weight: normal !important;
        margin-right: 20px;
        cursor: pointer;
        font-size: 13px !important;
        color: #4a5568;
        margin-top: 0 !important;
    }
    .radio-inline-group {
        display: flex;
        align-items: center;
        height: 38px;
    }
    .radio-inline-group input[type="radio"] {
        margin-right: 6px;
        position: relative;
        top: 1px;
    }
    /* Tabs custom styling */
    .nav-tabs {
        border-bottom: 2px solid #edf2f7;
        margin-bottom: 25px;
    }
    .nav-tabs .nav-item {
        margin-bottom: -2px;
    }
    .nav-tabs .nav-link {
        border: none !important;
        color: #718096 !important;
        font-weight: 600;
        font-size: 13px;
        padding: 10px 20px;
        margin-right: 5px;
        background: transparent !important;
        transition: color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
    .nav-tabs .nav-link.active, .nav-tabs .nav-link:hover {
        color: #3182ce !important;
        border-bottom: 3px solid #3182ce !important;
    }
    .tab-content {
        padding: 10px 5px;
    }
    .input-group-addon {
        background-color: #f7fafc;
        border: 1px solid #cbd5e0;
        color: #718096;
        font-size: 13px;
    }
    @media (min-width: 992px) {
        .text-right-desktop {
            text-align: right;
            padding-right: 25px;
        }
    }
</style>
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title"><?= empty($obj->VendorID) ? "New Vendor" : "Edit Vendor" ?></h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/vendor') ?>">Vendors</a></li>
                            <li class="active"><?= empty($obj->VendorID) ? "Create" : "Edit" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open_multipart() ?>
                    <div class="zoho-form-container">
                        
                        <!-- Primary Contact Section -->
                        <div class="row">
                            <div class="col-md-3 text-right-desktop">
                                <label style="margin-top: 8px; font-weight: 600;">Primary Contact</label>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <select name="form[Salutation]" id="Salutation" class="form-control">
                                            <option value="">Salutation</option>
                                            <option value="Mr." <?= (isset($obj->Salutation) && $obj->Salutation == 'Mr.') ? 'selected' : '' ?>>Mr.</option>
                                            <option value="Mrs." <?= (isset($obj->Salutation) && $obj->Salutation == 'Mrs.') ? 'selected' : '' ?>>Mrs.</option>
                                            <option value="Ms." <?= (isset($obj->Salutation) && $obj->Salutation == 'Ms.') ? 'selected' : '' ?>>Ms.</option>
                                            <option value="Miss" <?= (isset($obj->Salutation) && $obj->Salutation == 'Miss') ? 'selected' : '' ?>>Miss</option>
                                            <option value="Dr." <?= (isset($obj->Salutation) && $obj->Salutation == 'Dr.') ? 'selected' : '' ?>>Dr.</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" name="form[FirstName]" id="FirstName" class="form-control" placeholder="First Name"
                                               value="<?= isset($obj->FirstName) ? htmlspecialchars($obj->FirstName) : '' ?>">
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" name="form[LastName]" id="LastName" class="form-control" placeholder="Last Name"
                                               value="<?= isset($obj->LastName) ? htmlspecialchars($obj->LastName) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />

                        <!-- Company Name Section -->
                        <div class="row">
                            <div class="col-md-3 text-right-desktop">
                                <label style="margin-top: 8px; font-weight: 600;">Company Name</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="form[CompanyName]" id="CompanyName" class="form-control" placeholder="Company Name"
                                       value="<?= isset($obj->CompanyName) ? htmlspecialchars($obj->CompanyName) : '' ?>">
                            </div>
                        </div>
                        <br />

                        <!-- Display Name Section -->
                        <div class="row">
                            <div class="col-md-3 text-right-desktop">
                                <label style="margin-top: 8px; font-weight: 600;" class="required">Vendor Display Name</label>
                            </div>
                            <div class="col-md-9">
                                <input required type="text" name="form[VendorName]" id="VendorName" class="form-control" placeholder="Display Name"
                                       value="<?= isset($obj->VendorName) ? htmlspecialchars($obj->VendorName) : '' ?>">
                                <?= form_error('form[VendorName]') ?>
                            </div>
                        </div>
                        <br />

                        <!-- Email Section -->
                        <div class="row">
                            <div class="col-md-3 text-right-desktop">
                                <label style="margin-top: 8px; font-weight: 600;">Email Address</label>
                            </div>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" name="form[EmailId]" id="EmailId" class="form-control" placeholder="Email Address"
                                           value="<?= isset($obj->EmailId) ? htmlspecialchars($obj->EmailId) : '' ?>">
                                </div>
                            </div>
                        </div>
                        <br />

                        <!-- Phone Section -->
                        <div class="row">
                            <div class="col-md-3 text-right-desktop">
                                <label style="margin-top: 8px; font-weight: 600;">Phone</label>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="form[ContactNo]" id="ContactNo" class="form-control" placeholder="Work Phone"
                                                   value="<?= isset($obj->ContactNo) ? htmlspecialchars($obj->ContactNo) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                            <input type="text" name="form[MobileNo]" id="MobileNo" class="form-control" placeholder="Mobile Phone"
                                                   value="<?= isset($obj->MobileNo) ? htmlspecialchars($obj->MobileNo) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />

                        <!-- Vendor Language Section -->
                        <div class="row">
                            <div class="col-md-3 text-right-desktop">
                                <label style="margin-top: 8px; font-weight: 600;">Vendor Language</label>
                            </div>
                            <div class="col-md-9">
                                <select name="form[VendorLanguage]" class="form-control" id="VendorLanguage">
                                    <option value="English" <?= (!isset($obj->VendorLanguage) || $obj->VendorLanguage == 'English') ? 'selected' : '' ?>>English</option>
                                    <option value="Sinhala" <?= (isset($obj->VendorLanguage) && $obj->VendorLanguage == 'Sinhala') ? 'selected' : '' ?>>Sinhala</option>
                                    <option value="Tamil" <?= (isset($obj->VendorLanguage) && $obj->VendorLanguage == 'Tamil') ? 'selected' : '' ?>>Tamil</option>
                                </select>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid #edf2f7; margin: 30px 0 20px 0;" />

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item active">
                                <a href="#tab-other" class="nav-link active" data-toggle="tab" role="tab">Other Details</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-address" class="nav-link" data-toggle="tab" role="tab">Address</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-remarks" class="nav-link" data-toggle="tab" role="tab">Remarks</a>
                            </li>
                        </ul>

                        <!-- Tabs Content Panels -->
                        <div class="tab-content">
                            
                            <!-- TAB: Other Details -->
                            <div role="tabpanel" class="tab-pane active" id="tab-other">
                                
                                <!-- Tax Rate -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Tax Rate</label>
                                    <div class="col-md-6">
                                        <select name="form[TaxRate]" class="form-control" id="TaxRate">
                                            <option value="">Select a Tax</option>
                                            <option value="VAT 8%" <?= (isset($obj->TaxRate) && $obj->TaxRate == 'VAT 8%') ? 'selected' : '' ?>>VAT 8%</option>
                                            <option value="VAT 15%" <?= (isset($obj->TaxRate) && $obj->TaxRate == 'VAT 15%') ? 'selected' : '' ?>>VAT 15%</option>
                                            <option value="VAT 18%" <?= (isset($obj->TaxRate) && $obj->TaxRate == 'VAT 18%') ? 'selected' : '' ?>>VAT 18%</option>
                                            <option value="Non-Taxable" <?= (isset($obj->TaxRate) && $obj->TaxRate == 'Non-Taxable') ? 'selected' : '' ?>>Non-Taxable</option>
                                        </select>
                                        <small class="text-muted" style="display: block; margin-top: 4px;">To associate more than one tax, you need to create a tax group in Settings.</small>
                                    </div>
                                </div>

                                <!-- Company ID -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Company ID <i class="fa fa-info-circle text-muted" title="Unique registration identifier for this vendor company"></i></label>
                                    <div class="col-md-6">
                                        <input type="text" name="form[CompanyID]" id="CompanyID" class="form-control" placeholder="Company ID"
                                               value="<?= isset($obj->CompanyID) ? htmlspecialchars($obj->CompanyID) : '' ?>">
                                    </div>
                                </div>

                                <!-- Currency -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Currency</label>
                                    <div class="col-md-6">
                                        <select name="form[Currency]" class="form-control" id="Currency">
                                            <option value="LKR" <?= (!isset($obj->Currency) || $obj->Currency == 'LKR') ? 'selected' : '' ?>>LKR- Sri Lankan Rupee</option>
                                            <option value="USD" <?= (isset($obj->Currency) && $obj->Currency == 'USD') ? 'selected' : '' ?>>USD- United States Dollar</option>
                                            <option value="GBP" <?= (isset($obj->Currency) && $obj->Currency == 'GBP') ? 'selected' : '' ?>>GBP- British Pound Sterling</option>
                                            <option value="EUR" <?= (isset($obj->Currency) && $obj->Currency == 'EUR') ? 'selected' : '' ?>>EUR- Euro</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Accounts Payable -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Accounts Payable <i class="fa fa-info-circle text-muted" title="Control account tracking outstanding balances"></i></label>
                                    <div class="col-md-6">
                                        <select name="form[AccountsPayable]" class="form-control" id="AccountsPayable">
                                            <option value="">Select an account</option>
                                            <option value="Accounts Payable" <?= (!isset($obj->AccountsPayable) || $obj->AccountsPayable == 'Accounts Payable') ? 'selected' : '' ?>>Accounts Payable</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Payment Terms -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Payment Terms</label>
                                    <div class="col-md-6">
                                        <select name="form[PaymentTerms]" class="form-control" id="PaymentTerms">
                                            <option value="Due on Receipt" <?= (!isset($obj->PaymentTerms) || $obj->PaymentTerms == 'Due on Receipt') ? 'selected' : '' ?>>Due on Receipt</option>
                                            <option value="Net 15" <?= (isset($obj->PaymentTerms) && $obj->PaymentTerms == 'Net 15') ? 'selected' : '' ?>>Net 15</option>
                                            <option value="Net 30" <?= (isset($obj->PaymentTerms) && $obj->PaymentTerms == 'Net 30') ? 'selected' : '' ?>>Net 30</option>
                                            <option value="Net 45" <?= (isset($obj->PaymentTerms) && $obj->PaymentTerms == 'Net 45') ? 'selected' : '' ?>>Net 45</option>
                                            <option value="Net 60" <?= (isset($obj->PaymentTerms) && $obj->PaymentTerms == 'Net 60') ? 'selected' : '' ?>>Net 60</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Enable Portal? -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Enable Portal?</label>
                                    <div class="col-md-6" style="padding-top: 8px;">
                                        <label class="radio-label">
                                            <input type="checkbox" name="form[EnablePortal]" value="1" <?= (isset($obj->EnablePortal) && $obj->EnablePortal == 1) ? 'checked' : '' ?>> Allow portal access for this vendor
                                        </label>
                                    </div>
                                </div>

                                <!-- Vendor Date -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop required">Date</label>
                                    <div class="col-md-6">
                                        <input required type="date" id="VendorDate" name="form[VendorDate]" class="form-control" 
                                            value="<?= isset($obj->VendorDate) ? htmlspecialchars($obj->VendorDate) : date('Y-m-d') ?>">
                                    </div>
                                </div>

                                <!-- Vendor No -->
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Vendor No</label>
                                    <div class="col-md-6">
                                        <input readonly type="text" id="VendorNo" name="form[VendorNo]"
                                               value="<?= set_value('form[VendorNo]', $VendorNo) ?>"
                                               class="form-control">
                                    </div>
                                </div>

                            </div>

                            <!-- TAB: Address -->
                            <div role="tabpanel" class="tab-pane" id="tab-address">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Address</label>
                                    <div class="col-md-6">
                                        <textarea name="form[Address]" class="form-control" rows="4" placeholder="Enter full address..."><?= isset($obj->Address) ? htmlspecialchars($obj->Address) : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Website URL</label>
                                    <div class="col-md-6">
                                        <input type="text" name="form[Website]" class="form-control" placeholder="https://example.com"
                                               value="<?= isset($obj->Website) ? htmlspecialchars($obj->Website) : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">WhatsApp Number</label>
                                    <div class="col-md-6">
                                        <input type="text" name="form[WhatsAppNo]" class="form-control" placeholder="+94 XX XXX XXXX"
                                               value="<?= isset($obj->WhatsAppNo) ? htmlspecialchars($obj->WhatsAppNo) : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: Remarks -->
                            <div role="tabpanel" class="tab-pane" id="tab-remarks">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right-desktop">Remarks / Notes</label>
                                    <div class="col-md-6">
                                        <textarea name="form[Remark]" rows="5" class="form-control" placeholder="Internal notes..."><?= isset($obj->Remark) ? htmlspecialchars($obj->Remark) : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Actions -->
                    <div class="form-group text-right">
                        <a href="<?= base_url('admin/vendor') ?>" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-success">Save Vendor</button>
                    </div>
                    <?= form_close() ?>

                 </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<script src="<?= base_url() ?>assets/ckeditor/ckeditor.js"></script>

<script>
$(document).ready(function() {
    
    // Display Name Autogenerator Logic
    function updateDisplayName() {
        var company = $('#CompanyName').val().trim();
        var salutation = $('#Salutation').val();
        var first = $('#FirstName').val().trim();
        var last = $('#LastName').val().trim();
        
        var fullName = ((salutation ? salutation + ' ' : '') + (first ? first + ' ' : '') + last).trim();
        
        var currentDisplay = $('#VendorName').val().trim();
        
        if (!currentDisplay || currentDisplay === company || currentDisplay === fullName) {
            if (company) {
                $('#VendorName').val(company);
            } else if (fullName) {
                $('#VendorName').val(fullName);
            }
        }
    }

    $('#CompanyName, #Salutation, #FirstName, #LastName').on('input change', updateDisplayName);
});
</script>
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
