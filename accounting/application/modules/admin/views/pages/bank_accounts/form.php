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

                            Add Bank Account

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li>

                                <a href="<?= base_url('admin/Bank_accounts') ?>">

                                    Bank Accounts

                                </a>

                            </li>

                            <li class="active">

                                Add Bank Account

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>


            <!-- FORM -->
            <div class="row">

                <div class="col-md-8">

                    <div class="card-box">

                        <h4 class="header-title m-t-0">

                            Bank Account Information

                        </h4>

                        <br>

                        <form method="post">

                            <!-- BANK NAME -->
                            <div class="form-group">

                                <label>

                                    Bank Name

                                </label>

                                <input type="text"
                                       name="BankName"
                                       class="form-control"
                                       placeholder="Enter Bank Name"
                                       required>

                            </div>


                            <!-- ACCOUNT NAME -->
                            <div class="form-group">

                                <label>

                                    Account Name

                                </label>

                                <input type="text"
                                       name="AccountName"
                                       class="form-control"
                                       placeholder="Enter Account Name"
                                       required>

                            </div>


                            <!-- ACCOUNT NUMBER -->
                            <div class="form-group">

                                <label>

                                    Account Number

                                </label>

                                <input type="text"
                                       name="AccountNumber"
                                       class="form-control"
                                       placeholder="Enter Account Number"
                                       required>

                            </div>


                            <!-- BRANCH -->
                            <div class="form-group">

                                <label>

                                    Branch

                                </label>

                                <input type="text"
                                       name="Branch"
                                       class="form-control"
                                       placeholder="Enter Branch Name">

                            </div>


                            <!-- OPENING BALANCE -->
                            <div class="form-group">

                                <label>

                                    Opening Balance

                                </label>

                                <input type="text"
                                       name="OpeningBalance"
                                       class="form-control"
                                       value="0">

                            </div>


                            <!-- BUTTONS -->
                            <div class="form-group text-right">

                                <a href="<?= base_url('admin/Bank_accounts') ?>"
                                   class="btn btn-secondary">

                                    Cancel

                                </a>

                                <button type="submit"
                                        class="btn btn-success">

                                    <i class="fa fa-save"></i>

                                    Save Bank Account

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<?php $this->view('includes/footer_start.php') ?>
<?php $this->view('includes/footer_end.php') ?>