<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">EPF "C" Form</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <form method="get" action="<?= base_url('admin/payroll/epf_form') ?>" class="form-inline m-b-20 d-print-none">
                            <div class="form-group m-r-10">
                                <label for="month">Month:</label>
                                <input type="month" name="month" id="month" class="form-control" value="<?= $month ?>">
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Generate</button>
                            <button type="button" class="btn btn-default waves-effect m-l-10" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        </form>

                        <div id="printArea" style="background:#fff; padding:30px; font-family: Arial, sans-serif;">
                            <div class="text-center m-b-20">
                                <h3 style="font-weight:bold; margin-bottom: 0;">" C " Form <span style="font-size:16px; font-weight:normal;">EPF Act No. 15 of 1958</span></h3>
                            </div>

                            <table style="width: 100%; margin-bottom: 20px;">
                                <tr>
                                    <td style="width: 50%; vertical-align: top;">
                                        <div style="font-size: 12px; margin-bottom: 5px;">Employer's Address</div>
                                        <div style="border: 2px solid #000; padding: 15px; font-weight: bold;">
                                            <h4 style="margin-top:0; font-weight:bold;"><?= $company->CompanyName ?? 'IT MARTX (PVT) LTD' ?></h4>
                                            <?php 
                                            $address = $company->Address ?? 'NO. 200, 4TH FLOOR,<br>GEORGE R DE SILVA MAWATHA,<br>COLOMBO 13';
                                            echo nl2br($address);
                                            ?>
                                        </div>
                                    </td>
                                    <td style="width: 5%;">&nbsp;</td>
                                    <td style="width: 45%; vertical-align: top;">
                                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 13px;" border="1">
                                            <tr>
                                                <td style="padding: 5px;">E.P.F. Registration No.</td>
                                                <td style="padding: 5px; font-weight: bold;">A- 61019</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">Contribution for the month of</td>
                                                <td style="padding: 5px; font-weight: bold; text-transform: uppercase;"><?= date('F Y', strtotime($month . '-01')) ?></td>
                                            </tr>
                                            <?php
                                                $totalEPF = 0;
                                                foreach($records as $r) {
                                                    $totalEPF += ($r->EmployeeEPF + $r->CompanyEPF);
                                                }
                                            ?>
                                            <tr>
                                                <td style="padding: 5px;">Contributions</td>
                                                <td style="padding: 5px; text-align: right;">Rs. <?= number_format($totalEPF, 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">Surcharges</td>
                                                <td style="padding: 5px; text-align: right;">Rs. </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">Total Remittance</td>
                                                <td style="padding: 5px; text-align: right; font-weight: bold;">
                                                    Rs. <span style="border-bottom: 3px double #000; padding-bottom: 2px; display:inline-block; min-width:80px; text-align:right;"><?= number_format($totalEPF, 2) ?></span>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <div style="margin-top: 15px; font-size: 13px;">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td style="width: 40%;">Cheque No</td>
                                                    <td style="width: 5%;">:</td>
                                                    <td style="border-bottom: 2px dotted #000;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top:10px;">Bank and Branch Name</td>
                                                    <td style="padding-top:10px;">:</td>
                                                    <td style="border-bottom: 2px dotted #000;">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 13px;" border="1">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center; padding: 5px; border: 1px solid #000;">Employee's Name</th>
                                        <th rowspan="2" style="text-align: center; padding: 5px; border: 1px solid #000;">National ID No.</th>
                                        <th rowspan="2" style="text-align: center; padding: 5px; border: 1px solid #000;">Member<br>No</th>
                                        <th colspan="3" style="text-align: center; padding: 5px; border: 1px solid #000;">Contributions (Rs)</th>
                                        <th rowspan="2" style="text-align: center; padding: 5px; border: 1px solid #000;">Total Earning<br>(Rs)</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center; padding: 5px; border: 1px solid #000;">Total</th>
                                        <th style="text-align: center; padding: 5px; border: 1px solid #000;">Employer</th>
                                        <th style="text-align: center; padding: 5px; border: 1px solid #000;">Employee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sumTotal = 0;
                                    $sumEmployer = 0;
                                    $sumEmployee = 0;
                                    $minRows = 15;
                                    $rowCount = count($records);
                                    
                                    foreach($records as $r): 
                                        $tot = $r->EmployeeEPF + $r->CompanyEPF;
                                        $sumTotal += $tot;
                                        $sumEmployer += $r->CompanyEPF;
                                        $sumEmployee += $r->EmployeeEPF;
                                        $earning = $r->BasicSalary; // Total Earning = Basic Salary
                                    ?>
                                    <tr>
                                        <td style="padding: 3px 5px; border: 1px solid #000;"><?= $r->EmployeeName ?></td>
                                        <td style="padding: 3px 5px; border: 1px solid #000; text-align: center;"><?= $r->NIC ?></td>
                                        <td style="padding: 3px 5px; border: 1px solid #000; text-align: center;"><?= $r->EmployeeNo ?></td>
                                        <td style="padding: 3px 5px; border: 1px solid #000; text-align: right;"><?= number_format($tot, 2) ?></td>
                                        <td style="padding: 3px 5px; border: 1px solid #000; text-align: right;"><?= number_format($r->CompanyEPF, 2) ?></td>
                                        <td style="padding: 3px 5px; border: 1px solid #000; text-align: right;"><?= number_format($r->EmployeeEPF, 2) ?></td>
                                        <td style="padding: 3px 5px; border: 1px solid #000; text-align: right;"><?= number_format($earning, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php for($i=$rowCount; $i<$minRows; $i++): ?>
                                    <tr>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                        <td style="padding: 3px 5px; border: 1px solid #000;">&nbsp;</td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="padding: 5px; border: 1px solid #000; font-size: 11px;">
                                            I certify that the information given above is correct<br><br>
                                            ...........................................................................................
                                        </td>
                                        <td style="padding: 5px; border: 1px solid #000; text-align: right; font-weight: bold;"><?= number_format($sumTotal, 2) ?></td>
                                        <td style="padding: 5px; border: 1px solid #000; text-align: right; font-weight: bold;"><?= number_format($sumEmployer, 2) ?></td>
                                        <td style="padding: 5px; border: 1px solid #000; text-align: right; font-weight: bold;"><?= number_format($sumEmployee, 2) ?></td>
                                        <td style="padding: 5px; border: 1px solid #000;">&nbsp;</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div style="margin-top: 15px; font-size: 13px;">
                                <table style="width: 60%; margin-left: 40%;">
                                    <tr>
                                        <td style="width: 30%;">Telephone</td>
                                        <td style="width: 5%;">:</td>
                                        <td><?= $company->Telephone ?? '0762 115572' ?></td>
                                    </tr>
                                    <tr>
                                        <td>Fax</td>
                                        <td>:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>E-mail</td>
                                        <td>:</td>
                                        <td><?= $company->Email ?? '' ?></td>
                                    </tr>
                                </table>
                            </div>

                        </div> <!-- end print area -->

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media print {
    @page {
        size: A4;
        margin: 1cm;
    }
    body {
        margin: 0;
        background: #fff;
    }
    body * {
        visibility: hidden;
    }
    #printArea, #printArea * {
        visibility: visible;
    }
    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0 !important;
    }
    .d-print-none {
        display: none !important;
    }
    .content-page { margin-left: 0 !important; }
}
</style>
