<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

        <div class="page-title-box">

            <h4 class="page-title">
                Accounting Defaults
            </h4>

            <ol class="breadcrumb p-0">
                <li>
                    <a href="<?= base_url('admin') ?>">
                        Dashboard
                    </a>
                </li>
                <li class="active">
                    Accounting Defaults
                </li>
            </ol>

            <div class="clearfix"></div>

        </div>

        <div class="card-box">

            <h4 class="header-title m-t-0 m-b-30">
                Default Account Settings
            </h4>

            <form method="post"
                  action="<?= base_url('admin/accounting_defaults/save') ?>">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Cash Account</label>

                            <select name="CashAccountID"
                                    class="form-control">

                                <option value="">
                                    Select Cash Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                    <option value="<?= $row->AccountID ?>"
                                        <?= isset($obj->CashAccountID) && $obj->CashAccountID == $row->AccountID ? 'selected' : '' ?>>

                                        <?= $row->AccountCode ?>
                                        -
                                        <?= $row->AccountName ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Bank Account</label>

                            <select name="BankAccountID"
                                    class="form-control">

                                <option value="">
                                    Select Bank Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                    <option value="<?= $row->AccountID ?>"
                                        <?= isset($obj->BankAccountID) && $obj->BankAccountID == $row->AccountID ? 'selected' : '' ?>>

                                        <?= $row->AccountCode ?>
                                        -
                                        <?= $row->AccountName ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Accounts Receivable</label>

                            <select name="ReceivableAccountID"
                                    class="form-control">

                                <option value="">
                                    Select Receivable Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                    <option value="<?= $row->AccountID ?>"
                                        <?= isset($obj->ReceivableAccountID) && $obj->ReceivableAccountID == $row->AccountID ? 'selected' : '' ?>>

                                        <?= $row->AccountCode ?>
                                        -
                                        <?= $row->AccountName ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Accounts Payable</label>

                            <select name="PayableAccountID"
                                    class="form-control">

                                <option value="">
                                    Select Payable Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                    <option value="<?= $row->AccountID ?>"
                                        <?= isset($obj->PayableAccountID) && $obj->PayableAccountID == $row->AccountID ? 'selected' : '' ?>>

                                        <?= $row->AccountCode ?>
                                        -
                                        <?= $row->AccountName ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Inventory Account</label>

                            <select name="InventoryAccountID"
                                    class="form-control">

                                <option value="">
                                    Select Inventory Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                    <option value="<?= $row->AccountID ?>"
                                        <?= isset($obj->InventoryAccountID) && $obj->InventoryAccountID == $row->AccountID ? 'selected' : '' ?>>

                                        <?= $row->AccountCode ?>
                                        -
                                        <?= $row->AccountName ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Capital Account</label>

                            <select name="CapitalAccountID"
                                    class="form-control">

                                <option value="">
                                    Select Capital Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                    <option value="<?= $row->AccountID ?>"
                                        <?= isset($obj->CapitalAccountID) && $obj->CapitalAccountID == $row->AccountID ? 'selected' : '' ?>>

                                        <?= $row->AccountCode ?>
                                        -
                                        <?= $row->AccountName ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="fa fa-save"></i>

                    Save Accounting Defaults

                </button>

            </form>

        </div>

    </div>
</div>

</div>

<?php $this->view('includes/footer_start.php'); ?>

<?php $this->view('includes/footer_end.php'); ?>
