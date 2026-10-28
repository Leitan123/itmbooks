<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">


        <div class="page-title-box">
            <h4 class="page-title">Company Settings</h4>

            <ol class="breadcrumb p-0">
                <li>
                    <a href="<?= base_url('admin') ?>">
                        Dashboard
                    </a>
                </li>
                <li class="active">
                    Company Settings
                </li>
            </ol>

            <div class="clearfix"></div>
        </div>

        <?php if($this->session->flashdata('notification')): ?>

            <?php $notification = $this->session->flashdata('notification'); ?>

            <div class="alert alert-<?= $notification['alert']; ?>">
                <?= $notification['text']; ?>
            </div>

        <?php endif; ?>

        <div class="card-box">

            <h4 class="header-title m-t-0 m-b-30">
                Company Information
            </h4>

            <form method="post"
                  action="<?= base_url('admin/company/save') ?>"
                  enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Company Name</label>

                            <input type="text"
                                   name="CompanyName"
                                   class="form-control"
                                   value="<?= isset($company->CompanyName) ? $company->CompanyName : '' ?>"
                                   required>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Telephone</label>

                            <input type="text"
                                   name="Telephone"
                                   class="form-control"
                                   value="<?= isset($company->Telephone) ? $company->Telephone : '' ?>">

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">

                            <label>Address</label>

                            <textarea name="Address"
                                      class="form-control"
                                      rows="3"><?= isset($company->Address) ? $company->Address : '' ?></textarea>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Mobile</label>

                            <input type="text"
                                   name="Mobile"
                                   class="form-control"
                                   value="<?= isset($company->Mobile) ? $company->Mobile : '' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Email</label>

                            <input type="email"
                                   name="Email"
                                   class="form-control"
                                   value="<?= isset($company->Email) ? $company->Email : '' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Website</label>

                            <input type="text"
                                   name="Website"
                                   class="form-control"
                                   value="<?= isset($company->Website) ? $company->Website : '' ?>">

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Registration No</label>

                            <input type="text"
                                   name="RegistrationNo"
                                   class="form-control"
                                   value="<?= isset($company->RegistrationNo) ? $company->RegistrationNo : '' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Tax No</label>

                            <input type="text"
                                   name="TaxNo"
                                   class="form-control"
                                   value="<?= isset($company->TaxNo) ? $company->TaxNo : '' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Company Logo</label>

                            <input type="file"
                                   name="Logo"
                                   class="form-control">

                            <?php if (!empty($company->Logo) && file_exists(FCPATH . 'media/img/' . $company->Logo)): ?>
                                <div style="margin-top: 10px;">
                                    <p class="text-muted" style="font-size: 11px; margin-bottom: 5px;">Current Logo:</p>
                                    <img src="<?= base_url('media/img/' . $company->Logo) ?>" alt="Current Logo" style="max-height: 65px; max-width: 100%; border: 1px solid #e2e8f0; padding: 4px; border-radius: 4px; background: #f8fafc; object-fit: contain;">
                                </div>
                            <?php endif; ?>

                        </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Theme Color</label>

                            <input type="color"
                                   name="ThemeColor"
                                   class="form-control"
                                   value="<?= isset($company->ThemeColor) ? htmlspecialchars($company->ThemeColor) : '#2f3d4f' ?>"
                                   style="height: 40px; padding: 2px 5px; cursor: pointer;">

                        </div>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="fa fa-save"></i>

                    Save Company Settings

                </button>

            </form>

        </div>

    </div>
</div>

</div>

<?php $this->view('includes/footer_start.php'); ?>

<?php $this->view('includes/footer_end.php'); ?>
