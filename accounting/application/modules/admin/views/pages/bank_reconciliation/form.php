<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

```
<div class="content">

    <div class="container">

        <div class="page-title-box">

            <h4 class="page-title">
                Bank Reconciliation
            </h4>

        </div>

        <div class="card-box">

            <h4 class="header-title m-t-0 m-b-20">

                Add Bank Reconciliation

            </h4>

            <form method="post"
                  action="<?= base_url('admin/bank_rec/save') ?>">

                <div class="row">

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Bank</label>

                            <select name="BankID"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Select Bank
                                </option>

                                <?php foreach($banks as $bank){ ?>

                                <option value="<?= $bank->BankID ?>">

                                    <?= $bank->BankName ?> - <?= $bank->AccountNumber ?> (<?= $bank->AccountName ?>)

                                </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Reconciliation Date</label>

                            <input type="date"
                                   name="ReconciliationDate"
                                   class="form-control"
                                   value="<?= date('Y-m-d') ?>"
                                   required>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>Bank Statement Balance</label>

                            <input type="number"
                                   step="0.01"
                                   name="BankStatementBalance"
                                   class="form-control"
                                   required>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">

                            <label>Notes</label>

                            <textarea name="Notes"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-success">

                    <i class="fa fa-save"></i>

                    Save Reconciliation

                </button>

                <a href="<?= base_url('admin/bank_rec') ?>"
                   class="btn btn-default">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>
```

</div>

<?php $this->view('includes/footer_start.php'); ?>

<?php $this->view('includes/footer_end.php'); ?>
