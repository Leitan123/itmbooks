<!-- Right Sidebar -->
<div class="side-bar right-bar">
    <div class="nicescroll">
        <ul class="nav nav-tabs text-xs-center">
            <li class="nav-item">
                <a href="#home-2" class="nav-link active" data-toggle="tab" aria-expanded="false">
                    Activity
                </a>
            </li>
            <li class="nav-item">
                <a href="#messages-2" class="nav-link" data-toggle="tab" aria-expanded="true">
                    Settings
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active" id="home-2">
                <div class="timeline-2">
                    <div class="time-item">
                        <div class="item-info">
                            <small class="text-muted">5 minutes ago</small>
                            <p><strong><a href="#" class="text-info">John Doe</a></strong> Uploaded a photo <strong>"DSC000586.jpg"</strong>
                            </p>
                        </div>
                    </div>

                    <div class="time-item">
                        <div class="item-info">
                            <small class="text-muted">30 minutes ago</small>
                            <p><a href="" class="text-info">Lorem</a> commented your post.</p>

                            <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut
                                    tincidunt euismod. "</em></p>
                        </div>
                    </div>

                    <div class="time-item">
                        <div class="item-info">
                            <small class="text-muted">59 minutes ago</small>
                            <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#"
                                                                                                class="text-success">John
                                    Doe</a>.</p>

                            <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut
                                    tincidunt euismod. "</em></p>
                        </div>
                    </div>

                    <div class="time-item">
                        <div class="item-info">
                            <small class="text-muted">1 hour ago</small>
                            <p><strong><a href="#" class="text-info">John Doe</a></strong>Uploaded 2 new photos</p>
                        </div>
                    </div>

                    <div class="time-item">
                        <div class="item-info">
                            <small class="text-muted">3 hours ago</small>
                            <p><a href="" class="text-info">Lorem</a> commented your post.</p>

                            <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut
                                    tincidunt euismod. "</em></p>
                        </div>
                    </div>

                    <div class="time-item">
                        <div class="item-info">
                            <small class="text-muted">5 hours ago</small>
                            <p><a href="" class="text-info">Jessi</a> attended a meeting with<a href="#"
                                                                                                class="text-success">John
                                    Doe</a>.</p>

                            <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam laoreet tellus ut
                                    tincidunt euismod. "</em></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="messages-2">

                <div class="row m-t-20">
                    <div class="col-xs-8">
                        <h5 class="m-0">Notifications</h5>

                        <p class="text-muted m-b-0">
                            <small>Do you need them?</small>
                        </p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-xs-8">
                        <h5 class="m-0">API Access</h5>

                        <p class="m-b-0 text-muted">
                            <small>Enable/Disable access</small>
                        </p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-xs-8">
                        <h5 class="m-0">Auto Updates</h5>

                        <p class="m-b-0 text-muted">
                            <small>Keep up to date</small>
                        </p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-xs-8">
                        <h5 class="m-0">Online Status</h5>

                        <p class="m-b-0 text-muted">
                            <small>Show your status to all</small>
                        </p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- end nicescroll -->
</div>
<!-- /Right-bar -->

<footer class="footer text-right">
    <?php echo date("Y"); ?> &copy; <?= TITLE ?> | Developed By IT MARTX. +94 113 670 717
</footer>


</div>
<!-- END wrapper -->


<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/tether.min.js') ?>"></script><!-- Tether for Bootstrap -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/detect.js') ?>"></script>
<script src="<?= base_url('assets/js/fastclick.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.blockUI.js') ?>"></script>
<script src="<?= base_url('assets/js/waves.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.nicescroll.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.scrollTo.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.slimscroll.js') ?>"></script>
<script src="<?= base_url('assets/plugins/switchery/switchery.min.js') ?>"></script>







<!-- Counter Up  -->
<script src="<?= base_url('assets/plugins/waypoints/lib/jquery.waypoints.js') ?>"></script>
<script src="<?= base_url('assets/plugins/counterup/jquery.counterup.min.js') ?>"></script>

<!-- Knob -->
<script src="<?= base_url('assets/plugins/jquery-knob/jquery.knob.js') ?>"></script>
<!-- multi select -->
<script type="text/javascript" src="<?= base_url('assets/plugins/multiselect/js/jquery.multi-select.js') ?>"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
<script src="<?= base_url() ?>assets/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/pages/jquery.formadvanced.init.js"></script>


<!-- Peity chart js -->
<script src="<?= base_url('assets/plugins/peity/jquery.peity.min.js') ?>"></script>

<!-- Sweet Alert js -->
<script src="<?= base_url('assets/plugins/bootstrap-sweetalert/sweet-alert.min.js') ?>"></script>

<!-- App js -->
<script src="<?= base_url('assets/js/jquery.core.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.app.js') ?>"></script>
<script src="<?= base_url('assets/js/common.js') ?>"></script>

<script src="<?= base_url("assets/js/jquery-ui.js") ?>"></script>



<!--================================================================================-->
<script src="<?= base_url('assets/plugins/moment/moment.js')?>"></script>
<script src="<?= base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/mjolnic-bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/clockpicker/bootstrap-clockpicker.js')?>"></script>
<script src="<?= base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')?>"></script>

<script src="<?= base_url('assets/pages/jquery.form-pickers.init.js')?>"></script>



<script type="text/javascript">
//    $("#datepicker-autoclose").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
$('#datepicker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayBtn: true,
    todayHighlight: true,
    startDate: truncateDate(new Date())
});

function truncateDate(date) {
    return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}
</script>