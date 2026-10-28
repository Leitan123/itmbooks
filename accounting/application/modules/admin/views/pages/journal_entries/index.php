<?php $this->view('includes/header_start.php'); ?>

<link href="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/buttons.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url("assets/plugins/datatables/responsive.bootstrap4.min.css") ?>" rel="stylesheet" type="text/css"/>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

        <!-- Page Title -->
        <div class="row">
            <div class="col-xs-12">

                <div class="page-title-box">

                    <h4 class="page-title">
                        Journal Entries
                    </h4>

                    <ol class="breadcrumb p-0">

                        <li>
                            <a href="<?= base_url('admin') ?>">
                                Dashboard
                            </a>
                        </li>

                        <li class="active">
                            Journal Entries
                        </li>

                    </ol>

                    <div class="clearfix"></div>

                </div>

            </div>
        </div>

        <!-- Add Journal Entry -->
        <div class="row">

            <div class="col-lg-12">

                <div class="card-box">

                    <h4 class="header-title m-t-0 m-b-30">
                        Add Journal Entry
                    </h4>

                    <form method="post"
                          action="<?= base_url('admin/journal_entries/save') ?>">

                        <div class="row">

                            <div class="col-md-2">

                                <div class="form-group">

                                    <label>Date</label>

                                    <input type="date"
                                           name="JournalDate"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>Reference No</label>

                                    <input type="text"
                                           name="ReferenceNo"
                                           class="form-control">

                                </div>

                            </div>

                            <div class="col-md-5">

                                <div class="form-group">

                                    <label>Description</label>

                                    <input type="text"
                                           name="Description"
                                           class="form-control">

                                </div>

                            </div>

                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i=0;$i<5;$i++){ ?>
                                <tr>
                                    <td>
                                        <select name="AccountID[]"
                                                class="form-control select2">
                                            <option value="">
                                                Select Account
                                            </option>
                                            <?php foreach($accounts as $acc){ ?>
                                            <option value="<?= $acc->AccountID ?>">
                                                <?= $acc->AccountCode ?>
                                                -
                                                <?= $acc->AccountName ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="DescriptionDetail[]"
                                               value=""
                                               class="form-control">
                                    </td>
                                    <td>
                                        <input type="number"
                                                 step="0.01"
                                                 name="DebitAmount[]"
                                                 value=""
                                                 class="form-control">
                                    </td>
                                    <td>
                                        <input type="number"
                                                 step="0.01"
                                                 name="CreditAmount[]"
                                                 value=""
                                                 class="form-control">
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>

                        </table>

                        <button type="submit"
                                class="btn btn-primary">

                            Save Journal Entry

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Journal Entry List -->
        <div class="row">

            <div class="col-sm-12">

                <div class="card-box">

                    <h4 class="header-title m-t-0 m-b-30">
                        Journal Entry List
                    </h4>

                    <div class="table-responsive">

                        <table id="datatable"
                               class="table table-striped table-bordered">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Reference No</th>
                                    <th>Description</th>
                                    <th>Actions</th>

                                </tr>

                            </thead>

                            <tbody>
                                <?php foreach($records as $row){ ?>
                                <tr>
                                    <td><?= $row->JournalID ?></td>
                                    <td><?= date('Y-m-d', strtotime($row->JournalDate)) ?></td>
                                    <td><?= !empty($row->ReferenceNo) ? $row->ReferenceNo : '-' ?></td>
                                    <td><?= $row->Description ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/journal_entries/edit/'.$row->JournalID) ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('admin/journal_entries/delete/'.$row->JournalID) ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this journal entry?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>

                        </table>

                    </div>

                    <div class="row m-t-15">
                        <div class="col-sm-12">
                            <nav aria-label="Page navigation" class="pull-right">
                                <?= $links ?>
                            </nav>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>


</div>

<?php $this->view('includes/footer_start.php') ?>

<script src="<?= base_url("assets/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/datatables/dataTables.bootstrap4.min.js") ?>"></script>

<script>

$(document).ready(function(){

    $('#datatable').DataTable({

        "order": [[0,"desc"]],

        "responsive": true,

        "autoWidth": false,

        "paging": false,

        "info": false

    });

    // Initialize Select2 Elements
    $('.select2').select2({
        width: '100%'
    });

    // Prevent Duplicate Account Selection

    $('select[name="AccountID[]"]').change(function(){

        var selectedAccounts = [];

        var duplicateFound = false;

        $('select[name="AccountID[]"]').each(function(){

            var accountID = $(this).val();

            if(accountID != '')
            {
                if($.inArray(accountID, selectedAccounts) !== -1)
                {
                    duplicateFound = true;

                    $(this).val('');

                    return false;
                }

                selectedAccounts.push(accountID);
            }

        });

        if(duplicateFound)
        {
            alert('This account is already selected. Please choose another account.');
        }

    });

    // Debit/Credit Validation Before Submit

    $('form').submit(function(){

        var totalDebit = 0;
        var totalCredit = 0;

        $('input[name="DebitAmount[]"]').each(function(){

            totalDebit += parseFloat($(this).val()) || 0;

        });

        $('input[name="CreditAmount[]"]').each(function(){

            totalCredit += parseFloat($(this).val()) || 0;

        });

        if(totalDebit != totalCredit)
        {
            alert(
                'Total Debit (' +
                totalDebit.toFixed(2) +
                ') must equal Total Credit (' +
                totalCredit.toFixed(2) +
                ').'
            );

            return false;
        }

        return true;

    });

});

</script>