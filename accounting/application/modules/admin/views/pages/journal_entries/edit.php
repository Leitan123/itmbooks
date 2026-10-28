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
                        Edit Journal Entry
                    </h4>

                    <ol class="breadcrumb p-0">

                        <li>
                            <a href="<?= base_url('admin') ?>">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/journal_entries') ?>">
                                Journal Entries
                            </a>
                        </li>

                        <li class="active">
                            Edit Journal Entry
                        </li>

                    </ol>

                    <div class="clearfix"></div>

                </div>

            </div>
        </div>

        <!-- Edit Journal Entry -->
        <div class="row">

            <div class="col-lg-12">

                <div class="card-box">

                    <h4 class="header-title m-t-0 m-b-30">
                        Edit Journal Entry (ID: <?= $journal->JournalID ?>)
                    </h4>

                    <form method="post"
                          action="<?= base_url('admin/journal_entries/update/'.$journal->JournalID) ?>">

                        <div class="row">

                            <div class="col-md-2">

                                <div class="form-group">

                                    <label>Date</label>

                                    <input type="date"
                                           name="JournalDate"
                                           value="<?= date('Y-m-d', strtotime($journal->JournalDate)) ?>"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>Reference No</label>

                                    <input type="text"
                                           name="ReferenceNo"
                                           value="<?= html_escape($journal->ReferenceNo) ?>"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                            <div class="col-md-5">

                                <div class="form-group">

                                    <label>Description</label>

                                    <input type="text"
                                           name="Description"
                                           value="<?= html_escape($journal->Description) ?>"
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
                                <?php 
                                $num_rows = max(5, count($details));
                                for($i=0; $i<$num_rows; $i++){ 
                                    $detail = isset($details[$i]) ? $details[$i] : null;
                                ?>
                                <tr>
                                    <td>
                                        <select name="AccountID[]"
                                                class="form-control select2">
                                            <option value="">
                                                Select Account
                                            </option>
                                            <?php foreach($accounts as $acc){ ?>
                                            <option value="<?= $acc->AccountID ?>" <?= ($detail && $detail->AccountID == $acc->AccountID) ? 'selected' : '' ?>>
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
                                               value="<?= ($detail && !empty($detail->Description)) ? html_escape($detail->Description) : '' ?>"
                                               class="form-control">
                                    </td>
                                    <td>
                                        <input type="number"
                                                 step="0.01"
                                                 name="DebitAmount[]"
                                                 value="<?= ($detail && $detail->DebitAmount > 0) ? $detail->DebitAmount : '' ?>"
                                                 class="form-control">
                                    </td>
                                    <td>
                                        <input type="number"
                                                 step="0.01"
                                                 name="CreditAmount[]"
                                                 value="<?= ($detail && $detail->CreditAmount > 0) ? $detail->CreditAmount : '' ?>"
                                                 class="form-control">
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>

                        </table>

                        <button type="submit"
                                class="btn btn-primary">

                            Update Journal Entry

                        </button>
                        
                        <a href="<?= base_url('admin/journal_entries') ?>"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>
</div>


</div>

<?php $this->view('includes/footer_start.php') ?>

<script>

$(document).ready(function(){

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

<?php $this->view('includes/footer_end.php') ?>
