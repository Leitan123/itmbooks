<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <div class="card-box">

                <h4>Bank Reconciliation</h4>

                <a href="<?= base_url('admin/bank_rec/create') ?>"
                   class="btn btn-primary">

                    Add Reconciliation

                </a>

                <br><br>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Bank</th>

                            <th>Statement Balance</th>

                            <th>System Balance</th>

                            <th>Difference</th>

                            <th>Notes</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach($records as $row){ ?>

                        <tr>

                            <td>
                                <?= $row->ReconciliationDate ?>
                            </td>

                            <td>
                                <?= $row->BankName ?>
                            </td>

                            <td>
                                <?= number_format($row->BankStatementBalance,2) ?>
                            </td>

                            <td>
                                <?= number_format($row->SystemBalance,2) ?>
                            </td>

                            <td>
                                <?= number_format($row->DifferenceAmount,2) ?>
                            </td>

                            <td>
                                <?= $row->Notes ?>
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