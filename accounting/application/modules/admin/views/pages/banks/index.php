<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

        <div class="page-title-box">

            <h4 class="page-title">
                Bank Master
            </h4>

            <ol class="breadcrumb p-0">
                <li>
                    <a href="<?= base_url('admin') ?>">
                        Dashboard
                    </a>
                </li>
                <li class="active">
                    Bank Master
                </li>
            </ol>

            <div class="clearfix"></div>

        </div>

        <div class="card-box">

            <h4 class="header-title m-t-0 m-b-30">
                Add Bank Account
            </h4>

            <form method="post"
                  action="<?= base_url('admin/banks/save') ?>">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Bank Name</label>

                            <input type="text"
                                   name="BankName"
                                   class="form-control"
                                   placeholder="Commercial Bank"
                                   required>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Account Name</label>

                            <input type="text"
                                   name="AccountName"
                                   class="form-control"
                                   placeholder="Current Account">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Account Number</label>

                            <input type="text"
                                   name="AccountNumber"
                                   class="form-control"
                                   placeholder="123456789">

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Branch</label>

                            <input type="text"
                                   name="Branch"
                                   class="form-control"
                                   placeholder="Pettah Branch">

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Opening Balance</label>

                            <input type="number"
                                   step="0.01"
                                   name="OpeningBalance"
                                   value="0.00"
                                   class="form-control">

                        </div>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="fa fa-save"></i>

                    Save Bank

                </button>

            </form>

        </div>

        <div class="card-box">

            <h4 class="header-title">
                Bank List
            </h4>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Bank Code</th>

                            <th>Bank Name</th>

                            <th>Account Name</th>

                            <th>Account Number</th>

                            <th>Branch</th>

                            <th>Opening Balance</th>

                            <th>Current Balance</th>

                            <th width="100">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if(!empty($records)) : ?>

                            <?php foreach($records as $k => $row) : ?>

                            <tr>

                                <td><?= $k + 1 ?></td>

                                <td><?= $row->BankCode ?></td>

                                <td><?= $row->BankName ?></td>

                                <td><?= $row->AccountName ?></td>

                                <td><?= $row->AccountNumber ?></td>

                                <td><?= $row->Branch ?></td>

                                <td style="text-align:right">

                                    <?= number_format($row->OpeningBalance,2) ?>

                                </td>

                                <td style="text-align:right; font-weight: bold; color: <?= $row->CurrentBalance < 0 ? '#dc2626' : ($row->CurrentBalance > 0 ? '#059669' : '#64748b') ?>">

                                    <?= number_format($row->CurrentBalance,2) ?>

                                </td>

                                <td>

                                    <a href="<?= base_url('admin/banks/delete/'.$row->BankID) ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure?')">

                                        <i class="fa fa-trash"></i>

                                    </a>

                                </td>

                            </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
```

</div>

<?php $this->view('includes/footer_start.php'); ?>

<?php $this->view('includes/footer_end.php'); ?>
