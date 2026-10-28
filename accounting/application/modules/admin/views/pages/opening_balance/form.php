<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

        <div class="page-title-box">

            <h4 class="page-title">
                Opening Balance
            </h4>

            <ol class="breadcrumb p-0">
                <li>
                    <a href="<?= base_url('admin') ?>">
                        Dashboard
                    </a>
                </li>
                <li class="active">
                    Opening Balance
                </li>
            </ol>

            <div class="clearfix"></div>

        </div>

        <div class="card-box">

            <h4 class="header-title m-t-0 m-b-30">
                Opening Balances Setup
            </h4>

            <form method="post"
                  action="<?= base_url('admin/opening_balance/save') ?>">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Cash Balance</label>

                            <input type="number"
                                   step="0.01"
                                   name="CashBalance"
                                   class="form-control"
                                   value="<?= isset($obj->CashBalance) ? $obj->CashBalance : '0.00' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Inventory Balance</label>

                            <input type="number"
                                   step="0.01"
                                   name="InventoryBalance"
                                   class="form-control"
                                   value="<?= isset($obj->InventoryBalance) ? $obj->InventoryBalance : '0.00' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Accounts Receivable</label>

                            <input type="number"
                                   step="0.01"
                                   name="ReceivableBalance"
                                   class="form-control"
                                   value="<?= isset($obj->ReceivableBalance) ? $obj->ReceivableBalance : '0.00' ?>">

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Accounts Payable</label>

                            <input type="number"
                                   step="0.01"
                                   name="PayableBalance"
                                   class="form-control"
                                   value="<?= isset($obj->PayableBalance) ? $obj->PayableBalance : '0.00' ?>">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Capital Balance</label>

                            <input type="number"
                                   step="0.01"
                                   name="CapitalBalance"
                                   class="form-control"
                                   value="<?= isset($obj->CapitalBalance) ? $obj->CapitalBalance : '0.00' ?>">

                        </div>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="fa fa-save"></i>

                    Save Opening Balance

                </button>

            </form>

        </div>

    </div>
</div>

</div>

<?php $this->view('includes/footer_start.php'); ?>

<?php $this->view('includes/footer_end.php'); ?>
