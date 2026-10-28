<?php $this->view('includes/header_start.php'); ?>

<!-- extra css start -->
<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<!-- extra css end -->

<?php $this->view('includes/header_end.php'); ?>


<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="page-title-box">
                <h4 class="page-title">Expenses</h4>
            </div>

            <div class="card-box">

                <form method="post"
                      action="<?= base_url('admin/expenses/save') ?>">

                    <div class="row">

                        <div class="col-md-2">
                            <label>Date</label>

                            <input type="date"
                                   name="ExpenseDate"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-3">

                            <label>Expense Account</label>

                            <select name="AccountID"
                                    id="AccountID"
                                    class="form-control select2"
                                    required>

                                <option value="">
                                    Select Account
                                </option>

                                <?php foreach($accounts as $row){ ?>

                                <option value="<?= $row->AccountID ?>">

                                    <?= $row->AccountCode ?> -
                                    <?= $row->AccountName ?>

                                </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="col-md-2">
                            <label>Amount</label>

                            <input type="number"
                                   step="0.01"
                                   name="Amount"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-4 customer-section"
                             style="display:none;">

                            <label>Customer / Domain</label>

                            <select name="CustomerIds[]"
                                class="form-control select2"
                                multiple>

                                <option value="">
                                    Select Customer
                                </option>

                                 <?php foreach($customers as $customer){ 
                                     $domains = [];
                                     if (!empty($customer->DomainName)) {
                                         $domains[] = $customer->DomainName;
                                     }
                                     if (!empty($customer->AdditionalDomains)) {
                                         $domains = array_merge($domains, $customer->AdditionalDomains);
                                     }
                                     $domains = array_filter(array_unique($domains));
                                     
                                     if (empty($domains)) {
                                 ?>
                                 <option value="<?= $customer->CustomerId ?>:">
                                     <?= htmlspecialchars($customer->CompanyName) ?> - No Domain
                                 </option>
                                 <?php 
                                     } else {
                                         foreach ($domains as $domain) {
                                 ?>
                                 <option value="<?= $customer->CustomerId ?>:<?= htmlspecialchars($domain) ?>">
                                     <?= htmlspecialchars($customer->CompanyName) ?> - <?= htmlspecialchars($domain) ?>
                                 </option>
                                 <?php 
                                         }
                                     }
                                 } 
                                 ?>

                            </select>

                        </div>

                        <div class="col-md-2">


                        <label>Payment Method</label>

                        <select name="PaymentMethod"
                                id="PaymentMethod"
                                class="form-control">

                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Cheque">Cheque</option>

                        </select>
                        

                        </div>

                        <div class="col-md-3 bank-section"
                             style="display:none;">

                        
                        <label>Bank Account</label>

                        <select name="BankID"
                                class="form-control">

                            <option value="">
                                Select Bank
                            </option>

                            <?php foreach($banks as $bank){ ?>

                            <option value="<?= $bank->BankID ?>">

                                <?= $bank->BankName ?>
                                -
                                <?= $bank->AccountNumber ?>

                            </option>

                            <?php } ?>

                        </select>
                        

                        </div>

                        <div class="col-md-2 cheque-section"
                             style="display:none;">

                        
                        <label>Cheque No</label>

                        <input type="text"
                               name="ChequeNo"
                               class="form-control">
                        

                        </div>

                        <div class="col-md-2 cheque-section"
                             style="display:none;">

                        
                        <label>Cheque Date</label>

                        <input type="date"
                               name="ChequeDate"
                               class="form-control">
                        

                        </div>


                        <div class="col-md-2">
                            <label>Description</label>

                            <input type="text"
                                   name="Description"
                                   class="form-control">
                        </div>

                        <div class="col-md-2">

                            <label>Payee</label>

                            <input type="text"
                                   name="Payee"
                                   id="Payee"
                                   class="form-control">

                        </div>

                        <div class="col-md-3 employee-section"
                             style="display:none;">

                            <label>Employee</label>

                            <select name="EmployeeID"
                                    id="EmployeeID"
                                    class="form-control">

                                <option value="">
                                    Select Employee
                                </option>

                                <?php foreach($employees as $emp){ ?>

                                <option value="<?= $emp->EmployeeID ?>">

                                    <?= $emp->EmployeeName ?>

                                </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="col-md-2">
                            <label>Remarks</label>

                            <input type="text"
                                   name="Remarks"
                                   class="form-control">
                        </div>

                        <div class="col-md-1">
                            <label>&nbsp;</label>

                            <button type="submit"
                                    class="btn btn-primary btn-block">

                                Save

                            </button>
                        </div>

                    </div>

                </form>

            </div>

            <div class="card-box">

    <table id="datatable"
           class="table table-bordered table-striped">

        <thead>
        <tr>

            <th>ID</th>
            <th>Date</th>
            <th>Expense Account</th>
            <th>Customer / Domain</th>
            <th>Employee</th>
            <th>Payee</th>
            <th>Description</th>
            <th>Bank</th>
            <th style="text-align:right;">Amount</th>
            <th>Payment Method</th>
            <th>Remarks</th>
            <th width="120">Action</th>

        </tr>
        </thead>

        <tbody>

            <?php
            $i = 1;
            $totalAmount = 0;

            foreach($records as $row){

                $totalAmount += $row->Amount;
            ?>

            <tr>

            <td><?= $row->ExpenseID ?></td>

            <td><?= date('Y-m-d', strtotime($row->ExpenseDate)) ?></td>

            <td><?= $row->AccountCode ?> - <?= $row->AccountName ?></td>

            <td>

            <?php if(!empty($row->Domains)){ ?>

                <?= $row->Domains ?>

            <?php }elseif(!empty($row->CompanyName)){ ?>

                <?= $row->CompanyName ?>

                <?php if(!empty($row->DomainName)){ ?>

                    <br>
                    <small style="color:#666;">

                        <?= $row->DomainName ?>

                    </small>

                <?php } ?>

            <?php }else{ ?>

                -

            <?php } ?>

            </td>

            <td>
                <?= !empty($row->EmployeeName)
                    ? $row->EmployeeName
                    : '-' ?>
            </td>

            <td><?= $row->Payee ?></td>

            <td><?= $row->Description ?></td>

            <td>

            <?php if(!empty($row->BankName)){ ?>

                <?= $row->BankName ?>

                <br>

                <small style="color:#888">

                    <?= $row->AccountName ?>

                    -

                    <?= $row->AccountNumber ?>

                </small>

            <?php } else { ?>

                -

            <?php } ?>

            </td>

            <td style="text-align:right;font-weight:bold;">
                <?= number_format($row->Amount,2) ?>
            </td>

            <td><?= $row->PaymentMethod ?></td>

            <td><?= $row->Remarks ?></td>

            <td>

                <a href="<?= base_url('admin/expenses/edit/'.$row->ExpenseID) ?>"
                   class="btn btn-warning btn-sm">
                    <i class="fa fa-pencil"></i>
                </a>

                <a href="<?= base_url('admin/expenses/delete/'.$row->ExpenseID) ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this expense?')">
                    <i class="fa fa-trash"></i>
                </a>

            </td>

        </tr>

            <?php } ?>

        </tbody>

        <tfoot>

        <tr>

            <th colspan="8"
                style="text-align:right;">

                Total Expenses

            </th>

            <th style="text-align:right;color:red;">

                <?= number_format($totalAmount,2) ?>

            </th>

            <th colspan="3"></th>

        </tr>

        </tfoot>

    </table>

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
        "order": [[0, "desc"]]
    });

});

</script>

<script>

$(document).ready(function(){

    $('#PaymentMethod').change(function(){

        var method = $(this).val();

        if(method == 'Bank')
        {
            $('.bank-section').show();
            $('.cheque-section').hide();
        }
        else if(method == 'Cheque')
        {
            $('.bank-section').show();
            $('.cheque-section').show();
        }
        else
        {
            $('.bank-section').hide();
            $('.cheque-section').hide();
        }

    });

});

</script>

<script>

$(document).ready(function(){

    $('#AccountID').change(function(){

        var accountText = $("#AccountID option:selected").text().toLowerCase();

        if(accountText.indexOf('salary') >= 0)
        {
            $('.employee-section').show();
            $('#Payee').prop('readonly', true);
        }
        else
        {
            $('.employee-section').hide();
            $('#Payee').prop('readonly', false);
        }

    });

    $('#EmployeeID').change(function(){

        var employeeName = $("#EmployeeID option:selected").text();

        $('#Payee').val(employeeName);

    });


    $('#AccountID').change(function(){

        var account =
            $("#AccountID option:selected")
            .text()
            .toLowerCase();

        if(
            account.indexOf('hosting') >= 0 ||
            account.indexOf('domain') >= 0 ||
            account.indexOf('email') >= 0 ||
            account.indexOf('server') >= 0 ||
            account.indexOf('subscription') >= 0
        )
        {
            $('.customer-section').show();
        }
        else
        {
            $('.customer-section').hide();
        }

    });


});



</script>

<?php $this->view('includes/footer_end.php') ?>