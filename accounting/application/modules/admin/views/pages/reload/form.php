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
                        <h4 class="page-title">Reload</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/reload') ?>">Reload</a></li>
                            <li class="active"><?= empty($obj->ReloadId) ? "Create" : "Edit" ?></li>
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
                        <h4 class="header-title m-t-0 m-b-30">Reload Details</h4>

                        <div class="row">
                            <!-- Vc Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="vc_select">Customer / VC / Mobile No.</label>
                                    <select name="form[Vc]" id="vc_select" class="form-control select2">
                                        <option value="">-- Select VC --</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->Vc ?>" 
                                                <?= (isset($obj) && $obj->Vc == $customer->Vc) ? 'selected' : '' ?>>
                                                <?= $customer->Vc ?> - <?= $customer->CustomerName ?> - <?= $customer->MobileNo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Provider Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="provider">Provider</label>
                                    <select id="provider" name="form[Provider]" class="form-control" required>
                                        <option value="">Select Provider</option>
                                        <option value="VideoCon" <?= isset($obj->Provider) && $obj->Provider === 'VideoCon' ? 'selected' : '' ?>>VideoCon</option>
                                        <option value="DishTv" <?= isset($obj->Provider) && $obj->Provider === 'DishTv' ? 'selected' : '' ?>>DishTv</option>
                                        <option value="SunDirect" <?= isset($obj->Provider) && $obj->Provider === 'SunDirect' ? 'selected' : '' ?>>SunDirect</option>
                                        <option value="Airtel" <?= isset($obj->Provider) && $obj->Provider === 'Airtel' ? 'selected' : '' ?>>Airtel</option>
                                        <option value="TataSky" <?= isset($obj->Provider) && $obj->Provider === 'TataSky' ? 'selected' : '' ?>>TataSky</option>
                                        <option value="TvLanka" <?= isset($obj->Provider) && $obj->Provider === 'TvLanka' ? 'selected' : '' ?>>TvLanka</option>
                                    </select>
                                    <?= form_error('form[Provider]') ?>
                                </div>
                            </div>

                            <!-- Package Field -->
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="package">Package Amount</label>
                                    <input required type="number" id="package" name="form[Package]" class="form-control" 
                                        value="<?= isset($obj->Package) ? htmlspecialchars($obj->Package) : '0.00' ?>" 
                                        step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="package">Description</label>
                                    <input required type="text" id="packaged" name="form[PackageD]" class="form-control" 
                                        value="<?= isset($obj->PackageD) ? htmlspecialchars($obj->PackageD) : '' ?>">
                                </div>
                            </div>

                            <!-- AddOn Field -->
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="addon">Add-On</label>
                                    <input type="text" id="addon" name="form[AddOn]" class="form-control" 
                                        value="<?= isset($obj->AddOn) ? htmlspecialchars($obj->AddOn) : '0.00' ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="package">Description</label>
                                    <input type="text" id="addond" name="form[AddOnd]" class="form-control" 
                                        value="<?= isset($obj->AddOnd) ? htmlspecialchars($obj->AddOnd) : '' ?>">
                                </div>
                            </div>

                            <!-- ExtraCharge Field -->
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="extraCharge">Extra Charge</label>
                                    <input type="text" id="extraCharge" name="form[ExtraCharge]" class="form-control" 
                                        value="<?= isset($obj->ExtraCharge) ? htmlspecialchars($obj->ExtraCharge) : '0.00' ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="package">Description</label>
                                    <input type="text" id="extraCharged" name="form[ExtraCharged]" class="form-control" 
                                        value="<?= isset($obj->ExtraCharged) ? htmlspecialchars($obj->ExtraCharged) : '' ?>">
                                </div>
                            </div>

                            <!-- ExpiryON Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="expiryON">Expiry On</label>
                                    <input type="date" id="expiryON" name="form[ExpiryON]" class="form-control" 
                                        value="<?= isset($obj->ExpiryON) ? htmlspecialchars($obj->ExpiryON) : '' ?>">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select id="payment_method" name="form[PaymentMethod]" class="form-control" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="Cash" <?= isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Cash' ? 'selected' : '' ?>>Cash</option>
                                        <option value="Bank" <?= isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Bank' ? 'selected' : '' ?>>Bank</option>
                                        <option value="Ez Cash" <?= isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Ez Cash' ? 'selected' : '' ?>>Ez Cash</option>
                                        <option value="Card" <?= isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Card' ? 'selected' : '' ?>>Card</option>
                                        <option value="Credit" <?= isset($obj->PaymentMethod) && $obj->PaymentMethod === 'Credit' ? 'selected' : '' ?>>Credit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="pay_status">Pay Status</label>
                                    <select id="pay_status" name="form[PayStatus]" class="form-control" required>
                                        <option value="">Select Pay Status</option>
                                        <option value="Paid" <?= isset($obj->PayStatus) && $obj->PayStatus === 'Paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="Unpaid" <?= isset($obj->PayStatus) && $obj->PayStatus === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea id="remark" name="form[Remark]" class="form-control" rows="3"><?= isset($obj->Remark) ? htmlspecialchars($obj->Remark) : '' ?></textarea>
                                </div>
                            </div>


                        </div>
                    </div>

                    <button type="submit" class="btn btn-success pull-right">Save</button>

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

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
