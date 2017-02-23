<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>NẠP TIỀN CHO THÀNH VIÊN</h2>
                    </div>
                    <div class="body" style="overflow: visible;">
                        <form action="<?= SITE_URL ?>/thanh-vien/nap-tien-cho-thanh-vien" method="POST">

                            <h2 class="card-inside-title">Email người nhận: </h2>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">person</i>
                                </span>
                                <div class="form-line">
                                    <input type="email" name="email" class="form-control date" placeholder="Nhập email người nhận">
                                </div>
                            </div>

                            <h2 class="card-inside-title">Số tiền: </h2>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">account_balance_wallet</i>
                                </span>
                                <div class="form-line">
                                    <input type="number" name="money" class="form-control date" placeholder="Nhập số tiền muốn nạp">
                                </div>
                            </div>

                            <center>
                                <button type="submit" class="btn bg-cyan btn-block btn-lg waves-effect" style="width:150px;">NẠP TIỀN</button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT . '/app/view/layout/footer.php' ?>