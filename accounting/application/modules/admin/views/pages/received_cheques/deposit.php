<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <div class="card-box">

                <h4>Deposit Cheque</h4>

                <hr>

                <form method="post"
                      action="<?= base_url('admin/received_cheques/save_deposit') ?>">

                    <input type="hidden"
                           name="ReceivedChequeID"
                           value="<?= $cheque->ReceivedChequeID ?>">

                    <div class="row">

                        <div class="col-md-4">

                            <label>Customer</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= $cheque->CustomerName ?>"
                                   readonly>

                        </div>

                        <div class="col-md-4">

                            <label>Cheque No</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= $cheque->ChequeNo ?>"
                                   readonly>

                        </div>

                        <div class="col-md-4">

                            <label>Amount</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= number_format($cheque->Amount,2) ?>"
                                   readonly>

                        </div>

                    </div>

                    <br>

                    <div class="row">

                        <div class="col-md-6">

                            <label>Deposit To Bank</label>

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

                        <div class="col-md-6">

                            <label>Deposit Date</label>

                            <input type="date"
                                   name="DepositDate"
                                   class="form-control"
                                   value="<?= date('Y-m-d') ?>"
                                   required>

                        </div>

                    </div>

                    <br>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="fa fa-save"></i>

                        Deposit Cheque

                    </button>

                    <a href="<?= base_url('admin/received_cheques') ?>"
                       class="btn btn-default">

                        Cancel

                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

<?php $this->view('includes/footer_start.php'); ?>
<?php $this->view('includes/footer_end.php'); ?>