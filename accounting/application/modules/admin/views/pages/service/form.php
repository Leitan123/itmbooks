<?php $this->view('includes/header_start.php'); ?>
<!-- extra css start -->
<!-- extra css end -->
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Service</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li><a href="<?= base_url('admin/service') ?>">Service</a></li>
                            <li class="active"><?= empty($obj->ServiceId) ? "Create" : "Edit" ?></li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php $this->view('includes/notification.php'); ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= form_open() ?>
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-30">Service Details</h4>

                        <div class="row">
                            <!-- Customer Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="customer_select">Customer Name / Contact</label>
                                    <select name="form[CustomerId]" id="customer_select" class="form-control select2">
                                        <option value="">-- Select Customer --</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->CustomerId ?>" 
                                                <?= (isset($obj) && $obj->CustomerId == $customer->CustomerId) ? 'selected' : '' ?>>
                                                <?= $customer->CustomerName ?> - <?= $customer->MobileNo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Inquiry Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inquiry">Inquiry</label>
                                    <input type="text" id="inquiry" name="form[Inquiry]" class="form-control" 
                                        value="<?= isset($obj->Inquiry) ? htmlspecialchars($obj->Inquiry) : '' ?>" 
                                        required>
                                </div>
                            </div>

                            <!-- Job Status Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="job_status">Job Status</label>
                                    <select id="job_status" name="form[JobStatus]" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="Done" <?= isset($obj->JobStatus) && $obj->JobStatus === 'Done' ? 'selected' : '' ?>>Done</option>
                                        <option value="Process" <?= isset($obj->JobStatus) && $obj->JobStatus === 'Process' ? 'selected' : '' ?>>Process</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Technician Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="technician">Technician</label>
                                    <input type="text" id="technician" name="form[Technician]" class="form-control" 
                                        value="<?= isset($obj->Technician) ? htmlspecialchars($obj->Technician) : '' ?>" 
                                        required>
                                </div>
                            </div>

                            <!-- Service Date Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="service_date">Service Date</label>
                                    <input type="date" id="service_date" name="form[ServiceDate]" class="form-control" 
                                        value="<?= isset($obj->ServiceDate) ? htmlspecialchars($obj->ServiceDate) : '' ?>" 
                                        >
                                </div>
                            </div>

                            <!-- JobDetails Field -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="remark">Job Details</label>
                                    <textarea id="remark" name="form[JobDetails]" class="form-control" rows="3"><?= isset($obj->JobDetails) ? htmlspecialchars($obj->JobDetails) : '' ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-success pull-right">Save</button>

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>
<!-- extra js start -->
<!-- extra js end -->
<?php $this->view('includes/footer_end.php') ?>
