<?php $this->view('includes/header_start.php'); ?>

<?php $this->view('includes/header_end.php'); ?>

<div class="content-page">
    <div class="content">
        <div class="container">

        <div class="page-title-box">
            <h4 class="page-title">
                Financial Year Setup
            </h4>
        </div>

        <div class="card-box">

            <form method="post"
                  action="<?= base_url('admin/financial_year/save') ?>">

                <div class="row">

                    <div class="col-md-3">

                        <label>Year Name</label>

                        <input type="text"
                               name="YearName"
                               class="form-control"
                               placeholder="2026"
                               required>

                    </div>

                    <div class="col-md-3">

                        <label>Start Date</label>

                        <input type="date"
                               name="StartDate"
                               class="form-control"
                               required>

                    </div>

                    <div class="col-md-3">

                        <label>End Date</label>

                        <input type="date"
                               name="EndDate"
                               class="form-control"
                               required>

                    </div>

                    <div class="col-md-3">

                        <label>Active</label>

                        <select name="IsActive"
                                class="form-control">

                            <option value="1">
                                Yes
                            </option>

                            <option value="0">
                                No
                            </option>

                        </select>

                    </div>

                </div>

                <hr>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="fa fa-save"></i>
                    Save Financial Year

                </button>

            </form>

        </div>

        <div class="card-box">

            <h4 class="header-title">
                Financial Year List
            </h4>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                    <tr>

                        <th>#</th>

                        <th>Code</th>

                        <th>Year Name</th>

                        <th>Start Date</th>

                        <th>End Date</th>

                        <th>Active</th>

                        <th width="100">
                            Action
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php if(!empty($records)) : ?>

                        <?php foreach($records as $k => $row) : ?>

                            <tr>

                                <td><?= $k + 1 ?></td>

                                <td><?= $row->YearCode ?></td>

                                <td><?= $row->YearName ?></td>

                                <td><?= $row->StartDate ?></td>

                                <td><?= $row->EndDate ?></td>

                                <td>

                                    <?=
                                    $row->IsActive
                                    ? '<span class="label label-success">Active</span>'
                                    : '<span class="label label-danger">Inactive</span>'
                                    ?>

                                </td>

                                <td>

                                    <a href="<?= base_url('admin/financial_year/delete/'.$row->YearID) ?>"
                                       onclick="return confirm('Are you sure?')"
                                       class="btn btn-danger btn-sm">

                                        <i class="fa fa-trash"></i>

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>

</div>

<?php $this->view('includes/footer_start.php'); ?>

<?php $this->view('includes/footer_end.php'); ?>
