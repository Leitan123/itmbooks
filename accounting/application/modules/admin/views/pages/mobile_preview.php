<?php $this->view('includes/header_start.php'); ?>
<style>
    /* Premium mock-device frame */
    .device-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 40px 0;
        background: #f4f7f6;
    }
    .iphone-x {
        position: relative;
        width: 375px;
        height: 812px;
        background: #000;
        border-radius: 40px;
        box-shadow: 0px 25px 50px rgba(0, 0, 0, 0.25);
        border: 12px solid #1a1a1a;
        overflow: hidden;
    }
    /* Notch */
    .iphone-x::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 150px;
        height: 25px;
        background: #1a1a1a;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
        z-index: 100;
    }
    /* Simulated Mobile Screen */
    .screen-content {
        width: 100%;
        height: 100%;
        background: #f8f9fa;
        color: #333;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    /* Status bar */
    .status-bar {
        height: 44px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding: 0 24px 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: #333;
        z-index: 99;
    }
    .status-bar-right i {
        margin-left: 5px;
    }
    /* Simulated Pages Container */
    .app-body {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
    }
    /* Bottom Navigation Bar */
    .nav-bar {
        height: 65px;
        background: #fff;
        border-top: 1px solid #eef0f3;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding-bottom: 10px;
    }
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 11px;
        color: #a0aec0;
        cursor: pointer;
        transition: color 0.2s;
    }
    .nav-item.active {
        color: #3182ce;
    }
    .nav-item i {
        font-size: 20px;
        margin-bottom: 2px;
    }
    /* Floating Action Button */
    .fab {
        position: absolute;
        bottom: 80px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: #3182ce;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 10px rgba(49, 130, 206, 0.4);
        cursor: pointer;
        border: none;
    }
    /* Form components */
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 5px;
        display: block;
    }
    .form-control-app {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e0;
        font-size: 14px;
        background: #fff;
        box-sizing: border-box;
    }
    .btn-app {
        width: 100%;
        padding: 12px;
        background: #3182ce;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-app:hover {
        background: #2b6cb0;
    }
    /* Slips list styling */
    .slip-card, .leave-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #edf2f7;
    }
    .slip-card-header, .leave-card-header {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        border-bottom: 1px solid #f7fafc;
        padding-bottom: 8px;
        margin-bottom: 8px;
    }
    .label-badge {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 12px;
        font-weight: bold;
    }
    .badge-pending { background: #feebc8; color: #c05621; }
    .badge-approved { background: #c6f6d5; color: #22543d; }
    .badge-rejected { background: #fed7d7; color: #9b2c2c; }
</style>
<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Mobile App Simulator Preview</h4>
                        <ol class="breadcrumb p-0">
                            <li><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="active">Mobile Preview</li>
                        </ol>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <!-- Simulator Instructions -->
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="card-box">
                        <h4 class="header-title m-t-0 m-b-20" style="font-weight: bold;">Mobile Simulator Help</h4>
                        <p>This is a live interactive mock view of your upcoming mobile application. It connects directly to your newly created <strong>REST API controller</strong>.</p>
                        <hr>
                        <h5>Test Login Account:</h5>
                        <p>Use the credentials below to log in inside the simulated phone screen:</p>
                        <ul>
                            <li><strong>Email:</strong> <code>thimo@gmail.com</code></li>
                            <li><strong>Password:</strong> <code>123456</code></li>
                        </ul>
                        <button class="btn btn-primary btn-sm btn-block" onclick="prefillCredentials()">
                            <i class="fa fa-magic"></i> Auto-fill Credentials
                        </button>
                    </div>
                </div>

                <!-- Phone frame container -->
                <div class="col-md-8 col-xs-12 device-container">
                    <div class="iphone-x">
                        <div class="screen-content">
                            <!-- Status bar -->
                            <div class="status-bar">
                                <div class="status-bar-left">09:41</div>
                                <div class="status-bar-right">
                                    <i class="fa fa-signal"></i>
                                    <i class="fa fa-wifi"></i>
                                    <i class="fa fa-battery-three-quarters"></i>
                                </div>
                            </div>

                            <!-- APP CONTENT -->
                            <div class="app-body" id="appBody">
                                <!-- Dynamic screen loaded by JS -->
                            </div>

                            <!-- Navigation tabs (Visible when logged in) -->
                            <div class="nav-bar" id="appNav" style="display: none;">
                                <div class="nav-item active" onclick="switchTab('payments')" id="nav-payments">
                                    <i class="fa fa-credit-card"></i>
                                    <span>Payments</span>
                                </div>
                                <div class="nav-item" onclick="switchTab('leaves')" id="nav-leaves">
                                    <i class="fa fa-calendar-check-o"></i>
                                    <span>Leaves</span>
                                </div>
                                <div class="nav-item" onclick="logoutApp()">
                                    <i class="fa fa-sign-out"></i>
                                    <span>Logout</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->view('includes/footer_start.php'); ?>
<script>
    const baseUrl = "<?= base_url('admin/api') ?>";
    let loggedInUser = null;
    let currentTab = 'payments';

    $(document).ready(function() {
        showLoginScreen();
    });

    // Prefill test account details
    function prefillCredentials() {
        if (document.getElementById('appEmail')) {
            document.getElementById('appEmail').value = 'thimo@gmail.com';
            document.getElementById('appPassword').value = '123456';
        }
    }

    // Render login screen
    function showLoginScreen() {
        $('#appNav').hide();
        $('#appBody').html(`
            <div style="text-align: center; margin-top: 50px; margin-bottom: 40px;">
                <div style="background: #3182ce; color: #fff; width: 70px; height: 70px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; box-shadow: 0 4px 12px rgba(49,130,206,0.3); margin-bottom: 15px;">
                    <i class="fa fa-university"></i>
                </div>
                <h3 style="margin: 0; font-weight: bold; color: #2d3748;">IT MARTX</h3>
                <p style="color: #718096; font-size: 13px; margin-top: 5px;">Employee Services Portal</p>
            </div>
            
            <div id="loginError" style="color: #e53e3e; font-size: 13px; text-align: center; margin-bottom: 15px; display: none;"></div>

            <form id="appLoginForm" onsubmit="handleAppLogin(event)">
                <div class="form-group">
                    <label>EMAIL ADDRESS</label>
                    <input type="email" id="appEmail" class="form-control-app" placeholder="name@company.com" required>
                </div>
                <div class="form-group" style="margin-bottom: 25px;">
                    <label>PASSWORD</label>
                    <input type="password" id="appPassword" class="form-control-app" placeholder="••••••" required>
                </div>
                <button type="submit" class="btn-app">Sign In</button>
            </form>
        `);
    }

    // Handle Login API Request
    function handleAppLogin(event) {
        event.preventDefault();
        $('#loginError').hide();
        const email = $('#appEmail').val();
        const password = $('#appPassword').val();

        $.ajax({
            url: `${baseUrl}/login`,
            type: 'POST',
            data: { email: email, password: password },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loggedInUser = response.employee;
                    $('#appNav').show();
                    loadPaymentsTab();
                }
            },
            error: function(xhr) {
                let msg = "Login failed. Check server connection.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $('#loginError').text(msg).show();
            }
        });
    }

    // Load payments history tab
    function loadPaymentsTab() {
        currentTab = 'payments';
        $('.nav-item').removeClass('active');
        $('#nav-payments').addClass('active');

        $('#appBody').html(`
            <div style="margin-bottom: 20px;">
                <span style="color: #718096; font-size: 11px;">WELCOME BACK</span>
                <h4 style="margin: 2px 0 0 0; font-weight: bold; color: #2d3748;">${loggedInUser.name}</h4>
                <p style="color: #4a5568; font-size: 12px; margin: 3px 0 0 0;">${loggedInUser.designation} • ${loggedInUser.department}</p>
            </div>
            <h5 style="font-weight: bold; margin-bottom: 12px; color: #4a5568;">Payment History</h5>
            <div id="slipsList" style="flex: 1; overflow-y: auto;">
                <p style="text-align: center; color: #718096; font-size: 13px; margin-top: 40px;"><i class="fa fa-spinner fa-spin"></i> Loading payslips...</p>
            </div>
        `);

        // Fetch salary slips from API
        $.getJSON(`${baseUrl}/payments?employee_id=${loggedInUser.id}`, function(response) {
            if (response.success && response.data.length > 0) {
                let html = '';
                response.data.forEach(slip => {
                    html += `
                        <div class="slip-card">
                            <div class="slip-card-header">
                                <span style="color: #2d3748;">${slip.Month}</span>
                                <span style="color: #3182ce;">LKR ${parseFloat(slip.NetSalary).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                            </div>
                            <div style="font-size: 12px; color: #718096; display: flex; justify-content: space-between; margin-bottom: 4px;">
                                <span>Basic Salary:</span>
                                <span>LKR ${parseFloat(slip.BasicSalary).toLocaleString()}</span>
                            </div>
                            <div style="font-size: 12px; color: #718096; display: flex; justify-content: space-between; margin-bottom: 4px;">
                                <span>Allowances:</span>
                                <span style="color: #38a169;">+ LKR ${parseFloat(slip.Allowances).toLocaleString()}</span>
                            </div>
                            <div style="font-size: 12px; color: #718096; display: flex; justify-content: space-between;">
                                <span>Deductions:</span>
                                <span style="color: #e53e3e;">- LKR ${parseFloat(slip.Deductions).toLocaleString()}</span>
                            </div>
                        </div>
                    `;
                });
                $('#slipsList').html(html);
            } else {
                $('#slipsList').html('<p style="text-align: center; color: #718096; font-size: 13px; margin-top: 40px;">No salary records found.</p>');
            }
        });
    }

    // Load leaves requested tab
    function loadLeavesTab() {
        currentTab = 'leaves';
        $('.nav-item').removeClass('active');
        $('#nav-leaves').addClass('active');

        $('#appBody').html(`
            <div style="margin-bottom: 20px;">
                <h4 style="margin: 0; font-weight: bold; color: #2d3748;">Leave Applications</h4>
                <p style="color: #718096; font-size: 12px; margin-top: 3px;">Submit and check your leave requests</p>
            </div>
            <div id="leavesList" style="flex: 1; overflow-y: auto;">
                <p style="text-align: center; color: #718096; font-size: 13px; margin-top: 40px;"><i class="fa fa-spinner fa-spin"></i> Loading leaves...</p>
            </div>
            <button class="fab" onclick="showRequestLeaveScreen()"><i class="fa fa-plus"></i></button>
        `);

        // Fetch leaves from API
        $.getJSON(`${baseUrl}/leaves?employee_id=${loggedInUser.id}`, function(response) {
            if (response.success && response.data.length > 0) {
                let html = '';
                response.data.forEach(leave => {
                    let badgeClass = 'badge-pending';
                    if (leave.Status === 'Approved') badgeClass = 'badge-approved';
                    if (leave.Status === 'Rejected') badgeClass = 'badge-rejected';

                    html += `
                        <div class="leave-card">
                            <div class="leave-card-header">
                                <span style="color: #2d3748; font-size: 13px;">${leave.LeaveType} Leave</span>
                                <span class="label-badge ${badgeClass}">${leave.Status}</span>
                            </div>
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">
                                <i class="fa fa-calendar"></i> ${leave.StartDate} to ${leave.EndDate}
                            </div>
                            ${leave.Reason ? `<div style="font-size: 12px; background: #f7fafc; padding: 6px; border-radius: 4px; color: #4a5568; border-left: 3px solid #cbd5e0;">"${leave.Reason}"</div>` : ''}
                        </div>
                    `;
                });
                $('#leavesList').html(html);
            } else {
                $('#leavesList').html('<p style="text-align: center; color: #718096; font-size: 13px; margin-top: 40px;">No leave requests found.</p>');
            }
        });
    }

    // Render leave request screen
    function showRequestLeaveScreen() {
        $('#appBody').html(`
            <div style="margin-bottom: 20px; display: flex; align-items: center;">
                <button class="btn btn-link" style="color: #3182ce; padding: 0 10px 0 0;" onclick="loadLeavesTab()"><i class="fa fa-chevron-left"></i> Back</button>
                <h4 style="margin: 0; font-weight: bold; color: #2d3748;">Apply for Leave</h4>
            </div>

            <form id="leaveRequestForm" onsubmit="handleLeaveSubmission(event)">
                <div class="form-group">
                    <label>LEAVE TYPE</label>
                    <select id="leaveType" class="form-control-app" required>
                        <option value="Annual">Annual Leave</option>
                        <option value="Casual">Casual Leave</option>
                        <option value="Sick">Sick Leave</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>START DATE</label>
                    <input type="date" id="startDate" class="form-control-app" required>
                </div>
                <div class="form-group">
                    <label>END DATE</label>
                    <input type="date" id="endDate" class="form-control-app" required>
                </div>
                <div class="form-group" style="margin-bottom: 25px;">
                    <label>REASON</label>
                    <textarea id="leaveReason" class="form-control-app" rows="3" placeholder="Explain reason briefly..." required></textarea>
                </div>
                <button type="submit" class="btn-app">Submit Application</button>
            </form>
        `);
    }

    // Submit leave request API call
    function handleLeaveSubmission(event) {
        event.preventDefault();
        const leaveType = $('#leaveType').val();
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();
        const reason = $('#leaveReason').val();

        $.ajax({
            url: `${baseUrl}/request_leave`,
            type: 'POST',
            data: {
                employee_id: loggedInUser.id,
                start_date: startDate,
                end_date: endDate,
                leave_type: leaveType,
                reason: reason
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadLeavesTab();
                }
            },
            error: function() {
                alert("Failed to submit leave request.");
            }
        });
    }

    // Switch tabs
    function switchTab(tab) {
        if (tab === 'payments') loadPaymentsTab();
        if (tab === 'leaves') loadLeavesTab();
    }

    // Logout
    function logoutApp() {
        loggedInUser = null;
        showLoginScreen();
    }
</script>
<?php $this->view('includes/footer_end.php'); ?>
