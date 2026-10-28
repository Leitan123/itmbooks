<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pay Slip - <?= htmlspecialchars($slip->EmployeeName) ?> - <?= htmlspecialchars($slip->PayrollMonth) ?></title>
    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            background-color: #fff;
            padding: 20px;
        }
        .payslip-page {
            display: flex;
            flex-direction: column;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .payslip-container {
            flex: 1;
            padding: 30px;
            box-sizing: border-box;
        }
        .payslip-container:first-child {
            border-right: none;
            border-bottom: 1px dashed #999;
            margin-bottom: 15px;
            padding-bottom: 45px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-details h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #111;
        }
        .company-details p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .payslip-title {
            text-align: right;
        }
        .payslip-title h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
            text-transform: uppercase;
        }
        .payslip-title p {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }
        .employee-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        .info-group {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            width: 140px;
            font-weight: bold;
            color: #666;
        }
        .info-value {
            flex-grow: 1;
            color: #111;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .salary-table th {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .salary-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }
        .salary-table .subheading {
            font-weight: bold;
            background-color: #f5f5f5;
            color: #555;
        }
        .salary-columns {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .salary-columns .column {
            flex: 1;
        }
        .text-right {
            text-align: right;
        }
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        .summary-box {
            width: 320px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .summary-row:last-child {
            border-bottom: none;
            background-color: #f9f9f9;
        }
        .net-pay {
            font-size: 16px;
            font-weight: bold;
            color: #111;
            border-top: 1px double #333;
            border-bottom: 1px double #333;
            padding: 12px 15px !important;
            background-color: #eafaf1 !important;
        }
        .contributions-section {
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 40px;
            background-color: #fcfcfc;
        }
        .contributions-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 11px;
            color: #777;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .contributions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding-top: 20px;
        }
        .signature-line {
            width: 220px;
            border-top: 1px solid #999;
            text-align: center;
            padding-top: 8px;
            font-size: 12px;
            color: #666;
        }
        .no-print-bar {
            background-color: #f1f1f1;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .btn {
            background-color: #25D366;
            color: white;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            margin-right: 10px;
        }
        .btn-secondary {
            background-color: #666;
        }
        .btn:hover {
            opacity: 0.9;
        }
        @media print {
            @page {
                size: A4 portrait;
                margin: 5mm;
            }
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            .no-print-bar {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
                font-size: 10px;
                line-height: 1.3;
            }
            .payslip-page {
                box-shadow: none;
                max-width: 100%;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .payslip-container {
                height: 48%;
                padding: 10px 25px;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .payslip-container:first-child {
                border-bottom: 1px dashed #999;
                padding-bottom: 15px;
                margin-bottom: 0px;
            }
            .header { 
                margin-bottom: 5px; 
                padding-bottom: 5px; 
            }
            .company-details h2 { font-size: 15px; }
            .company-details p { font-size: 9px; margin-top: 2px; }
            .payslip-title h1 { font-size: 16px; }
            .payslip-title p { font-size: 11px; margin-top: 2px; }
            .employee-info { 
                gap: 5px 10px; 
                margin-bottom: 8px; 
                padding: 6px 10px; 
            }
            .info-group { margin-bottom: 1px; }
            .info-label { width: 110px; }
            .salary-columns {
                gap: 10px;
                margin-bottom: 8px;
            }
            .salary-table {
                margin-bottom: 0px;
            }
            .salary-table th {
                padding: 4px 6px;
                font-size: 10px;
            }
            .salary-table td {
                padding: 4px 6px;
                font-size: 9.5px;
            }
            .summary-section { 
                margin-bottom: 8px; 
            }
            .summary-box {
                width: 260px;
            }
            .summary-row {
                padding: 4px 8px;
            }
            .net-pay {
                font-size: 13px;
                padding: 6px 8px !important;
            }
            .contributions-section { 
                padding: 6px 10px; 
                margin-bottom: 8px; 
            }
            .contributions-title {
                margin-bottom: 4px;
                font-size: 9px;
            }
            .contributions-grid {
                gap: 5px 10px;
            }
            .signatures { 
                margin-top: 15px; 
                padding-top: 5px; 
            }
            .signature-line {
                width: 180px;
                padding-top: 4px;
                font-size: 9.5px;
            }
        }
    </style>
</head>
<body>

<div class="no-print-bar">
    <button class="btn" onclick="window.print()"><i class="fa fa-print"></i> Print Pay Slip</button>
    <button class="btn btn-secondary" onclick="window.close()">Close Window</button>
</div>

<div class="payslip-page">
<?php for($i = 0; $i < 2; $i++): ?>
<div class="payslip-container">
    <div class="header">
        <div class="company-details">
            <h2>IT MARTX (PVT) LTD</h2>
            <p>IT Solutions Provider</p>
            <p>200, 4th Floor, George R De Silva Mawatha,Colombo 13, Sri Lanka.</p>
        </div>
        <div class="payslip-title">
            <h1>Pay Slip</h1>
            <p>Month: <?= date('F Y', strtotime($slip->PayrollMonth . '-01')) ?></p>
        </div>
    </div>

    <div class="employee-info">
        <div>
            <div class="info-group">
                <div class="info-label">Employee Name:</div>
                <div class="info-value"><strong><?= htmlspecialchars($slip->EmployeeName) ?></strong></div>
            </div>
            <div class="info-group">
                <div class="info-label">Employee ID:</div>
                <div class="info-value"><?= htmlspecialchars($slip->EmployeeNo) ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Designation:</div>
                <div class="info-value"><?= htmlspecialchars($slip->Designation ?: '-') ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Department:</div>
                <div class="info-value"><?= htmlspecialchars($slip->Department ?: '-') ?></div>
            </div>
        </div>
        <div>
            <div class="info-group">
                <div class="info-label">Payment Date:</div>
                <div class="info-value"><?= htmlspecialchars($slip->PaymentDate) ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Payment Method:</div>
                <div class="info-value">
                    <?= htmlspecialchars($slip->PaymentMethod) ?>
                    <?php if($slip->PaymentMethod == 'Bank' && $slip->BankName): ?>
                        (<?= htmlspecialchars($slip->BankName) ?>)
                    <?php endif; ?>
                </div>
            </div>
            <div class="info-group">
                <div class="info-label">Reference No:</div>
                <div class="info-value">SAL-<?= str_replace('-', '', $slip->PayrollMonth) ?>-<?= $slip->EmployeeID ?></div>
            </div>
        </div>
    </div>

    <div class="salary-columns">
        <!-- EARNINGS COLUMN -->
        <div class="column">
            <table class="salary-table">
                <thead>
                    <tr>
                        <th colspan="2">1. EARNINGS / ALLOWANCES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="70%">Basic Salary</td>
                        <td width="30%" class="text-right"><?= number_format($slip->BasicSalary, 2) ?></td>
                    </tr>
                    <?php if($slip->Allowance > 0): ?>
                        <tr>
                            <td>Fixed Allowance</td>
                            <td class="text-right"><?= number_format($slip->Allowance, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php 
                    if(!empty($slip->AllowancesJSON)): 
                        $customAllowances = json_decode($slip->AllowancesJSON);
                        if($customAllowances):
                            foreach($customAllowances as $ca):
                                if($ca->amount > 0):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($ca->name) ?></td>
                            <td class="text-right"><?= number_format($ca->amount, 2) ?></td>
                        </tr>
                    <?php 
                                endif;
                            endforeach;
                        endif;
                    endif; 
                    ?>
                    <?php if($slip->OTAmount > 0): ?>
                        <tr>
                            <td>Overtime</td>
                            <td class="text-right"><?= number_format($slip->OTAmount, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr style="font-weight: bold;">
                        <td>Total Gross (A)</td>
                        <td class="text-right" style="border-top: 1px solid #ddd;"><?= number_format($slip->GrossSalary, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- DEDUCTIONS COLUMN -->
        <div class="column">
            <table class="salary-table">
                <thead>
                    <tr>
                        <th colspan="2">2. DEDUCTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="70%">Employee EPF (8%)</td>
                        <td width="30%" class="text-right" style="color: #ff5b5b;">-<?= number_format($slip->EmployeeEPF, 2) ?></td>
                    </tr>
                    <?php if($slip->AdvanceDeduction > 0): ?>
                        <tr>
                            <td>Advance Recovery</td>
                            <td class="text-right" style="color: #ff5b5b;">-<?= number_format($slip->AdvanceDeduction, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr style="font-weight: bold;">
                        <td>Total Deductions (B)</td>
                        <td class="text-right" style="border-top: 1px solid #ddd; color: #ff5b5b;">
                            -<?= number_format($slip->EmployeeEPF + $slip->AdvanceDeduction, 2) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-row">
                <span>Gross Earnings (A):</span>
                <span>Rs. <?= number_format($slip->GrossSalary, 2) ?></span>
            </div>
            <div class="summary-row">
                <span style="color: #ff5b5b;">Total Deductions (B):</span>
                <span style="color: #ff5b5b;">-Rs. <?= number_format($slip->EmployeeEPF + $slip->AdvanceDeduction, 2) ?></span>
            </div>
            <div class="summary-row net-pay">
                <span>NET SALARY PAYABLE:</span>
                <span>Rs. <?= number_format($slip->NetSalary, 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Statutory compliance information -->
    <div class="contributions-section">
        <div class="contributions-title">Employer Contributions (Not deducted from salary)</div>
        <div class="contributions-grid">
            <div class="info-group">
                <div class="info-label">Company EPF (12%):</div>
                <div class="info-value">Rs. <?= number_format($slip->CompanyEPF, 2) ?></div>
            </div>
            <div class="info-group">
                <div class="info-label">Company ETF (3%):</div>
                <div class="info-value">Rs. <?= number_format($slip->CompanyETF, 2) ?></div>
            </div>
        </div>
    </div>

    <div class="signatures">
        <div class="signature-line">
            Prepared By (HR Dept)
        </div>
        <div class="signature-line">
            Approved By (Finance Dept)
        </div>
        <div class="signature-line">
            Employee Signature (Acknowledgement)
        </div>
    </div>
</div>
<?php endfor; ?>
</div>

</body>
</html>
