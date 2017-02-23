<?php require_once ROOT . '/app/view/layout/header.php' ?>

    <div class="signup-box">
        <div class="logo">
            <a href="<?= SITE_URL ?>"><img src="<?= SITE_URL ?>/public/images/mtvip1.png" alt="" style="width: 60%;margin-bottom: 20px;"></a>
            <small>Hệ thống Facebook Marketing tự động</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST">
                    <div class="msg">Đăng ký sử dụng hệ thống Facebook Marketing của chúng tôi ! Bạn sẽ có 2 ngày dùng thử</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="name" placeholder="Họ và tên" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">phone</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="phone-number" placeholder="Số điện thoại" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Mật khẩu" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password-confirm" minlength="6" placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>
                    
                    <!-- <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">Đồng ý <a href="#">các điều khoản </a> của chúng tôi.</label>
                    </div> -->

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">ĐĂNG KÝ</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="<?= SITE_URL ?>/thanh-vien/dang-nhap">Đăng nhập</a> nếu bạn đã có tài khoản
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require_once ROOT . '/app/view/layout/footer.php' ?>