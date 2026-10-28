it <?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <!-- Page Title -->
            <div class="row">
                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">
                            Income Management
                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>
                                <a href="<?= base_url('admin') ?>">
                                    Dashboard
                                </a>
                            </li>

                            <li class="active">
                                Income
                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>
            </div>

            <!-- Add Income -->
            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">
                            Add Income
                        </h4>

                        <form method="post"
                              action="<?= base_url('admin/incomes/save') ?>">

                            <div class="row">

                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label>Date</label>

                                        <input type="date"
                                               name="IncomeDate"
                                               class="form-control"
                                               required>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label>Income Account</label>

                                        <select name="AccountID"
                                                class="form-control"
                                                required>

                                            <option value="">
                                                Select Account
                                            </option>

                                            <?php foreach($accounts as $row){ ?>

                                                <option value="<?= $row->AccountID ?>">

                                                    <?= $row->AccountName ?>

                                                </option>

                                            <?php } ?>

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label>Amount</label>

                                        <input type="number"
                                               step="0.01"
                                               name="Amount"
                                               class="form-control"
                                               required>

                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label>Payment Method</label>

                                        <select name="PaymentMethod"
                                                class="form-control">

                                            <option value="Cash">
                                                Cash
                                            </option>

                                            <option value="Bank">
                                                Bank
                                            </option>

                                            <option value="Cheque">
                                                Cheque
                                            </option>

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label>Bank</label>

                                        <select name="BankID"
                                                id="BankID"
                                                class="form-control">

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

                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label>Remarks</label>

                                        <input type="text"
                                               name="Remarks"
                                               class="form-control">

                                    </div>

                                </div>

                                <div class="col-md-1">

                                    <div class="form-group">

                                        <label>&nbsp;</label>

                                        <button type="submit"
                                                class="btn btn-primary btn-block">

                                            Save

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <!-- Income List -->
            <div class="row">

                <div class="col-sm-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">
                            Income List
                        </h4>

                        <div class="table-responsive">

                            <table id="datatable"
                                   class="table table-striped table-bordered">

                                <thead>

                                    <tr>

                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Income Account</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Remarks</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php $i = 1; ?>

                                    <?php foreach($records as $row){ ?>

                                        <tr>

                                            <td>
                                                <?= $i++ ?>
                                            </td>

                                            <td>
                                                <?= $row->IncomeDate ?>
                                            </td>

                                            <td>
                                                <?= $row->AccountName ?>
                                            </td>

                                            <td style="text-align:right;">

                                                <?= number_format($row->Amount, 2) ?>

                                            </td>

                                            <td>
                                                <?= $row->PaymentMethod ?>
                                            </td>

                                            <td>
                                                <?= $row->Remarks ?>
                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                                <tfoot>

                                    <tr>

                                        <td colspan="3"
                                            style="text-align:right; font-weight:bold;">

                                            Total Income :

                                        </td>

                                        <td style="text-align:right; font-weight:bold;">

                                            <?php
                                                $total = 0;

                                                foreach($records as $row){

                                                    $total += $row->Amount;

                                                }

                                                echo number_format($total,2);
                                            ?>

                                        </td>

                                        <td colspan="2"></td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php') ?>

<!-- extra js start -->

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>

<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

    $(document).ready(function () {

        $('#datatable').DataTable({

            "order": [[0, "desc"]],
            "responsive": true,
            "autoWidth": false

        });

    });


    $('select[name="PaymentMethod"]').change(function(){

        var method = $(this).val();

        if(method == 'Bank' || method == 'Cheque')
        {
            $('#BankID').closest('.form-group').parent().show();
        }
        else
        {
            $('#BankID').val('');
            $('#BankID').closest('.form-group').parent().hide();
        }

    }).trigger('change');

</script>

<!-- extra js end -->

<?php $this->view('includes/footer_end.php') ?>