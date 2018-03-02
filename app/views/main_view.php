<div class="row">
    <div class="col-xs-12 bg-primary">
        <h2 class="text-center">Остатки продуктов</h2>
    </div>

</div>

<?php if(!empty($msg)): ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?php if ($msg == '1') : ?>
                <div class="alert alert-success text-center" role="alert">Загрузка прошла успешно</div>
            <?php else: ?>
                <div class="alert alert-danger text-center" role="alert"><?= $data['msg'] ?></div>
            <?php endif; ?>

        </div>
    </div>
<?php endif; ?>

<div class="row" style="padding-top: 50px;">

    <div class="col-md-6 col-md-offset-3">
        <div class="table-bordered">
            <table class="table">
                <tr>
                    <th>Product name</th>
                    <th>Qty</th>
                    <th>Warehouses</th>
                </tr>
                <?php foreach ($products as $value) : ?>
                    <tr>
                        <td><?= $value['product'] ?></td>
                        <td><?= $value['sum'] ?></td>
                        <td><?= $value['wh'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<div class="row" style="padding-top: 50px;">
    <div class="col-lg-6 col-lg-offset-3">
        <form enctype="multipart/form-data" action="upload" method="post">
        <div class="input-group">

                <input type="file" accept=".csv" name="csv" class="form-control" placeholder="File input">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Загрузить</button>
                </span>

        </div><!-- /input-group -->
        </form>
    </div><!-- /.col-lg-6 -->
</div>

