<?php $this->view('includes/header_start.php'); ?>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">

    <div class="content">

        <div class="container">

            <!-- Page Title -->
            <div class="row">

                <div class="col-xs-12">

                    <div class="page-title-box">

                        <h4 class="page-title">

                            Bank Ledger

                        </h4>

                        <ol class="breadcrumb p-0">

                            <li>
                                <a href="<?= base_url('admin') ?>">
                                    Dashboard
                                </a>
                            </li>

                            <li>
                                Bank Ledger
                            </li>

                            <li class="active">

                                <?= $bank->BankName ?>

                            </li>

                        </ol>

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>

            <!-- Bank Summary -->

            <div class="row">

                <div class="col-lg-12">

                    <div class="card-box">

                        <h4 class="header-title m-t-0 m-b-30">

                            <?= $bank->BankName ?>

                            -
                            <?= $bank->AccountName ?>

                        </h4>

                        <!-- Month Wise Filter Form -->
                        <form method="GET" action="" class="form-inline no-print" style="margin-bottom: 25px; display: flex; gap: 15px; flex-wrap: wrap; align-items: center; background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #e3e3e3;">
                            <div class="form-group" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                <label for="month" style="font-weight: 600; margin: 0; color: #555;">Month:</label>
                                <select name="month" id="month" class="form-control" style="min-width: 140px; border-radius: 4px;">
                                    <option value="all" <?= (isset($selected_month) && $selected_month === 'all') ? 'selected' : '' ?>>All Months</option>
                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        $m_str = str_pad($m, 2, '0', STR_PAD_LEFT);
                                        $selected = (isset($selected_month) && $selected_month === $m_str) ? 'selected' : '';
                                        echo '<option value="' . $m_str . '" ' . $selected . '>' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" style="margin: 0; display: flex; align-items: center; gap: 8px;" id="year-group">
                                <label for="year" style="font-weight: 600; margin: 0; color: #555;">Year:</label>
                                <select name="year" id="year" class="form-control" style="min-width: 100px; border-radius: 4px;">
                                    <?php
                                    $current_year = date('Y');
                                    for ($y = $current_year - 5; $y <= $current_year + 2; $y++) {
                                        $selected = (isset($selected_year) && $selected_year == $y) ? 'selected' : '';
                                        echo '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 6px 16px; border-radius: 4px; font-weight: bold; background: #4F81BD; border: none; color: #fff;"><i class="fa fa-filter"></i> Filter</button>
                            <a href="<?= base_url('admin/home/bank_ledger/' . $bank->BankID) ?>" class="btn btn-default" style="padding: 6px 16px; border-radius: 4px; border: 1px solid #ccc; background: #fff; color: #333;"><i class="fa fa-undo"></i> Reset</a>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var monthSelect = document.getElementById('month');
                                var yearGroup = document.getElementById('year-group');
                                function toggleYear() {
                                    if (monthSelect.value === 'all') {
                                        yearGroup.style.setProperty('display', 'none', 'important');
                                    } else {
                                        yearGroup.style.display = 'flex';
                                    }
                                }
                                monthSelect.addEventListener('change', toggleYear);
                                toggleYear();
                            });
                        </script>

                        <table class="table table-bordered table-striped">

                            <thead>

                                <tr style="background:#4F81BD;color:#fff;">

                                    <th width="15%">
                                        Date
                                    </th>

                                    <th>
                                        Description
                                    </th>

                                    <th style="text-align:right;" width="15%">
                                        Debit (Money IN)
                                    </th>

                                    <th style="text-align:right;" width="15%">
                                        Credit (Money OUT)
                                    </th>

                                    <th style="text-align:right;" width="15%">
                                        Balance
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php

                            $runningBalance = isset($bank_opening_balance) ? $bank_opening_balance : $bank->OpeningBalance;

                            ?>

                            <tr style="background:#f5f5f5;font-weight:bold;">

                                <td colspan="4">

                                    Opening Balance

                                </td>

                                <td style="text-align:right;">

                                    <?= number_format($runningBalance,2) ?>

                                </td>

                            </tr>

                            <?php foreach($transactions as $row){ ?>

                                <?php

                                // CORRECT FORMULA: Balance = Previous Balance + Debit - Credit
                                // Debit = Money IN (Customer Payments, Income)
                                // Credit = Money OUT (Expenses, Transfers)
                                $runningBalance = $runningBalance + $row['Debit'] - $row['Credit'];

                                ?>

                                <tr>

                                    <td>

                                        <?= date('Y-m-d', strtotime($row['Date'])) ?>

                                    </td>

                                    <td>

                                        <?= $row['Description'] ?>

                                    </td>

                                    <td style="text-align:right;">

                                        <?= number_format($row['Debit'],2) ?>

                                    </td>

                                    <td style="text-align:right;">

                                        <?= number_format($row['Credit'],2) ?>

                                    </td>

                                    <td style="text-align:right;font-weight:bold;">

                                        <?= number_format($runningBalance,2) ?>

                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody>

                            <tfoot>

                                <tr style="background:#dff0d8;font-weight:bold;">

                                    <td colspan="4">

                                        Closing Balance

                                    </td>

                                    <td style="text-align:right;">

                                        <?= number_format($runningBalance,2) ?>

                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php $this->view('includes/footer_start.php') ?>
<?php $this->view('includes/footer_end.php') ?>