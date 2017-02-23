<?php require_once ROOT . '/app/view/layout/header.php' ?>

    <div class="login-box">
        <div class="logo">
            <a href="<?= SITE_URL ?>"><img src="<?= SITE_URL ?>/public/images/mtvip1.png" alt="" style="width: 60%;margin-bottom: 20px;"></a>
            <small>Hệ thống Facebook Marketing tự động</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                    <div class="msg">Đăng nhập để sử dụng hệ thống</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="remember" id="remember" class="filled-in chk-col-pink">
                            <label for="remember">Nhớ tài khoản</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">Đăng nhập</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="<?= SITE_URL ?>/thanh-vien/dang-ky" class="font-bold">ĐĂNG KÝ NGAY !</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="forgot-password.html">Quên mật khẩu?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/app/view/layout/footer.php' ?>