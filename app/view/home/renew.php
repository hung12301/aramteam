<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>GIA HẠN BẢN QUYỀN</h2>
                    </div>
                    <div class="body" style="overflow: visible;">
                        <form action="<?= SITE_URL ?>/thanh-vien/gia-han-tai-khoan/vip1" method="POST">
                            <?php if($data['redirect'] != false) { ?>
                            <div class="alert alert-danger">
                                <strong>Tài khoản hết hạn!</strong> Bạn vui lòng gia hạn để có thể sử dụng tiếp.
                            </div>
                            <?php } ?>
                            <h2 class="card-inside-title">Tài khoản bạn đang có: </h2>
                            <p style="font-size: 20px;display: inline-block;margin-right: 20px;margin-bottom: 0px;"><b><?= number_format($_SESSION['user']['money'],0,',','.') ?> <span style="font-size: 14px;">VNĐ</span><b></p>
                            <a href="<?= SITE_URL ?>/nap-tien" class="btn btn-primary waves-effect" style="cursor: pointer;vertical-align: -6px;">NẠP TIỀN</a>
                            <h2 class="card-inside-title m-t-20">Chọn thời hạn muốn gia hạn: </h2>
                            <select name="price" data-size="8" required>
                                <?php foreach ($data['price'] as $price) { ?>
                                    <?php if($price['time'] != 0) { ?>
                                    <option value="<?= $price['time'] ?>|<?= $price['price'] ?>">-- <?= round($price['time'] / 30) ?> THÁNG/<?= number_format($price['price'],0,',','.') ?> VNĐ --</option>
                                    <?php } else { ?>
                                    <option value="<?= $price['time'] ?>|<?= $price['price'] ?>">-- VĨNH VIỄN/<?= number_format($price['price'],0,',','.') ?> VNĐ --</option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <br>
                            <br>
                            <center>
                                <button type="submit" class="btn bg-cyan btn-block btn-lg waves-effect" style="width:150px;">GIA HẠN</button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT . '/app/view/layout/footer.php' ?>