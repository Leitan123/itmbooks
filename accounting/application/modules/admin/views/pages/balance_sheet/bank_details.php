<table class="table table-bordered">

    <thead>
        <tr>
            <th>Bank</th>
            <th style="text-align:right">Balance</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($banks as $bank){ ?>

        <tr>
            <td><?= $bank->BankName ?></td>

            <td style="text-align:right">
                <?= number_format($bank->Balance,2) ?>
            </td>
        </tr>

    <?php } ?>

    </tbody>

</table>