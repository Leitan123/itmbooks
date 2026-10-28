<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">

                            Issued Cheques

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>

                                <a href="<?= base_url('admin') ?>">

                                    Dashboard

                                </a>

                            </li>

                            <li class="active">

                                Issued Cheques

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>


            <div class="card-box">

                <h4 class="header-title">

                    Issued Cheque Register

                </h4>

                <br>

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr style="background:#4F81BD;color:#fff;">

                            <th>Payee</th>

                            <th>Expense Account</th>

                            <th>Bank</th>

                            <th>Cheque No</th>

                            <th>Cheque Date</th>

                            <th>Amount</th>

                            <th>Status</th>

                            <th>Notes</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach($records as $row){ ?>

                        <tr>

                            <td>
                                <?= $row->PayeeName ?>
                            </td>

                            <td>
                                <?= $row->AccountName ?>
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

                                <?= number_format($row->Amount,2) ?>

                            </td>

                            <td>

                                <?php if($row->Status == 'Issued'){ ?>

                                    <span class="label label-warning">

                                        Issued

                                    </span>

                                <?php } ?>

                                <?php if($row->Status == 'Cleared'){ ?>

                                    <span class="label label-success">

                                        Cleared

                                    </span>

                                <?php } ?>

                                <?php if($row->Status == 'Cancelled'){ ?>

                                    <span class="label label-danger">

                                        Cancelled

                                    </span>

                                <?php } ?>

                            </td>

                            <td>

                                <?= isset($row->Remarks) ? $row->Remarks : '' ?>


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