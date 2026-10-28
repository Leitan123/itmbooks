<?php
$notification = $this->session->flashdata('notification');
if (isset($notification)) { ?>
    <div class="row">
        <div class=" col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12
         alert alert-<?= $notification['alert'] ?>  alert-success alert-dismissible fade in ">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?= $notification['text'] ?>
        </div>
    </div>
<?php } ?>