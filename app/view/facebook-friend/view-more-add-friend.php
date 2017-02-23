<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<section class="content">
    <div class="container-fluid">
    	<!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>XEM & SỬA GỬI YÊU CẦU KẾT BẠN</h2>
                        </div>
                        <div class="body">
                            <form action="<?= SITE_URL ?>/ban-be-facebook/sua-ket-ban/<?= $data['schedule']['id'] ?>" method="POST">
                                <!-- TÀI KHOẢN -->
                                <h2 class="card-inside-title">Sử dụng tài khoản : </h2>
                                <div class="facebook-account">
                                    <a href="https://facebook.com/<?= $data['facebookAccount']['facebook_id'] ?>">
                                    <div class="image" style="float: left;margin-right: 10px;">
                                        <img src="<?= $data['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
                                    </div>
                                    <span style="vertical-align: -3px;"><?= $data['facebookAccount']['name'] ?></span>
                                     </a>
                                </div>
                                <?php if($data['schedule']['done'] == 0) { ?>
                                <h2 class="card-inside-title" id="title-distance">Khoảng cách giữa 2 lần gửi yêu cầu: <?= $data['schedule']['distance'] ?> giây</h2>
                                <div id="distance"></div>
                                <input type="hidden" name="distance" value="<?= $data['schedule']['distance'] ?>">
                                <br>
                                <div style="text-align: center"><button type="submit" class="btn btn-lg btn-primary waves-effect">LƯU THAY ĐỔI</button></div>
                                <?php } ?>
                                <!-- <div style="clear: both"></div> -->
                                <br>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Thời gian</th>
                                            <th>Facebook ID</th>
                                            <th>Trạng thái</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['users'] as $key=>$user) { ?>
                                        <tr>
                                            <!-- STT -->
                                            <td><?= $key+1 ?></td>
                                            <!-- Thời gian -->
                                            <?php if($user['updated_at'] != $user['created_at']) { ?>
                                            <td><?= getCharTime(strtotime($user['updated_at'])) ?></td>
                                            <?php }else{ ?>
                                            <td>Đang chờ</td>
                                            <?php } ?>
                                            <!-- User ID -->
                                            <td><?= $user['user_id'] ?></a></td>
                                            <!-- Trạng thái -->
                                            <?php if($user['status'] == 0) { ?>
                                            <td><span class="badge bg-orange">Đang chờ</span></td>
                                            <?php }else if($user['error_message'] != ''){ ?>
                                            <td><span class="badge bg-red">Lỗi</span></td>
                                            <?php } else { ?>
                                            <td><span class="badge bg-green">Thành công</span></td>
                                            <?php } ?>
                                            <!-- Ghi chú -->
                                            <?php if($user['error_message'] != '') { ?>
                                            <td><?= $user['user_name'] . ': ' . $user['error_message'] ?></td>
                                            <?php } else if($user['status'] == 1)  { ?>
                                            <td><a href="https://www.facebook.com/<?= $user['user_id'] ?>" target="_blank">Xem <?= $user['user_name'] ?></a></td>
                                            <?php } else { ?>
                                            <td></td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	</div>
</section>

<?php require_once ROOT . '/app/view/layout/footer.php' ?>

<!-- Javascript Add-on -->
<script src="<?= SITE_URL ?>/public/plugins/autosize/autosize.js"></script>
<!-- End Javascript Add-on -->

<script type="text/javascript">
    autosize($('textarea[name="content"]'));

    <?php if($data['schedule']['done'] == 0) { ?>

    var postDistance = document.getElementById('distance');

    noUiSlider.create(postDistance, {
        start: <?= $data['schedule']['distance'] ?>,
        connect: 'lower',
        range: {
            'min': 1,
            'max': 300
        }
    });

    postDistance.noUiSlider.on('update', function () {
        var val = postDistance.noUiSlider.get();
        $id = $(postDistance).attr('id');
        $(postDistance).parent().find('input[name="'+$id+'"]').val(parseInt(val));
        $("#title-distance").html('Khoảng cách giữa 2 bài đăng: '+parseInt(val)+' giây');
    });

    <?php } ?>
</script>