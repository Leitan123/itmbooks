<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <div class="card-box">

                <h4>Received Cheques</h4>

                <br><br>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Customer</th>

                            <th>Bank</th>

                            <th>Cheque No</th>

                            <th>Cheque Date</th>

                            <th>Deposit Date</th>

                            <th>Amount</th>

                            <th>Status</th>
                            <th width="180">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach($records as $row){ ?>

                        <tr>

                            <td>
                                <?= $row->CustomerName ?>
                            </td>

                            <td>
                                <?= $row->BankName ?>
                            </td>

                            <td>
                                <?= $row->ChequeNo ?>
                            </td>

                            <td>
                                <?= $row->ChequeDate ?>
                            </td>

                            <td>
                                <?= $row->DepositDate ?>
                            </td>

                            <td>
                                <?= number_format($row->Amount,2) ?>
                            </td>

                            <td>

                            <?php if($row->Status == 'Pending'){ ?>

                            <a href="<?= base_url('admin/received_cheques/deposit/'.$row->ReceivedChequeID) ?>"
                               class="btn btn-warning btn-sm">

                                Deposit

                            </a>

                            <?php } ?>

                            <?php if($row->Status == 'Deposited'){ ?>

                            <a href="<?= base_url('admin/received_cheques/clear_cheque/'.$row->ReceivedChequeID) ?>"
                               class="btn btn-success btn-sm">

                                Clear

                            </a>

                            <?php } ?>

                            </td>

                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php $this->view('includes/footer_start.php') ?>
<?php $this->view('includes/footer_end.php') ?>