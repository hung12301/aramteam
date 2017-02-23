<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<!-- CSS Add-on -->

<!-- End Css Add-on -->
    <section class="content">
        <div class="container-fluid">
        	<div class="row">
        		<div class="col-md-12">
        			<div class="alert alert-info">
	                    <strong><a href="https://www.youtube.com/watch?v=eI2kjyBGnHs" target="_blank" class="col-white">Hướng dẫn sử dụng tại đây</a></strong>
	                </div>
        		</div>
        	</div>
            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">account_circle</i>
                        </div>
                        <div class="content">
                            <div class="text">TÀI KHOẢN FB</div>
                            <div class="number count-to" data-from="0" data-to="<?= count($data['facebookAccounts']) ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">done</i>
                        </div>
                        <div class="content">
                            <div class="text">HOÀN THÀNH</div>
                            <div class="number count-to" data-from="0" data-to="<?= $data['scheduleDone'] ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <div class="content">
                            <div class="text">CHƯA HOÀN THÀNH</div>
                            <div class="number count-to" data-from="0" data-to="<?= $data['scheduleRuning'] ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">BẢN QUYỀN CÒN (ngày)</div>
                            <?php if($data['license']['forever'] == 0 ) { ?>
                            <div class="number count-to" data-from="0" data-to="<?= $data['license']['expried_at'] ?>" data-speed="1000" data-fresh-interval="20"></div>
                            <?php } else { ?>
                            <div class="number">VĨNH VIỄN</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->
            <div class="row clearfix">
                <?php foreach ($data['allSchedules'] as $key=>$schedule) { ?>
                    <!-- POST GROUP -->
                    <?php if($schedule['type'] == 'post-group') { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="body bg-teal align-center" style="height: 230px;">
                                <div class="align-left">
                                    <div class="image" style="float: left;margin-right: 10px;">
                                        <img src="<?= $schedule['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
                                    </div>
                                    <span style="vertical-align: -3px;"><a href="https://facebook.com/<?= $schedule['facebookAccount']['facebook_id'] ?>" style="color:#fff" target="_blank"><?= $schedule['facebookAccount']['name'] ?></a></span>
                                    <div class="badge bg-purple" style="float:right;margin-top: 2px;">Đã đăng <?= $schedule['posted'] ?>/<?= $schedule['post'] ?></div>
                                </div>
                                <div style="clear: both;"></div>
                                <?php if($schedule['status'] == 1) { ?>
                                <p class="m-b-0 m-t-10">Đếm ngược</p>
                                <?php if($schedule['posted'] != 0 && $schedule['pause'] != 0 && $schedule['posted'] % $schedule['pause'] == 0) { ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['pause_time'] + $schedule['distance']) - time() ?>"></div>
                                <?php }else{ ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['distance']) - time() ?>"></div>
                                <?php } ?>
                                <?php } else { ?>
                                <div class="col-amber" style="height: 75px;line-height: 75px;">Đang tạm dừng ...</div>
                                <?php } ?>
                                <p style="height: 40px;overflow: hidden;">Sẽ đăng lên <b><?= $schedule['nearPost']['groupName'] ?></b></p>
                                <a href="<?= SITE_URL ?>/nhom-facebook/xem-chi-tiet-dang-nhom/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                <?php if($schedule['status'] == 1) { ?>
                                <button type="button" class="btn btn-warning waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tạm dừng"><i class="material-icons" style="font-size:14px">pause</i></button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-success waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tiếp tục"><i class="material-icons" style="font-size:14px">play_arrow</i></button>
                                <?php } ?>
                                <button type="button" class="btn btn-danger waves-effect delete-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="material-icons" style="font-size:14px">delete</i></button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- JOIN GROUP -->
                    <?php if($schedule['type'] == 'join-group') { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="body bg-indigo align-center" style="height: 230px;">
                                <div class="align-left">
                                    <div class="image" style="float: left;margin-right: 10px;">
                                        <img src="<?= $schedule['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
                                    </div>
                                    <span style="vertical-align: -3px;"><a href="https://facebook.com/<?= $schedule['facebookAccount']['facebook_id'] ?>" style="color:#fff" target="_blank"><?= $schedule['facebookAccount']['name'] ?></a></span>
                                    <div class="badge bg-deep-orange" style="float:right;margin-top: 2px;">Đã tham gia <?= $schedule['joined'] ?>/<?= $schedule['join'] ?></div>
                                </div>
                                <div style="clear: both;"></div>
                                <?php if($schedule['status'] == 1) { ?>
                                <p class="m-b-0 m-t-10">Đếm ngược</p>
                                <?php if($schedule['joined'] != 0 && $schedule['pause'] != 0 && $schedule['joined'] % $schedule['pause'] == 0) { ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['pause_time'] + $schedule['distance']) - time() ?>"></div>
                                <?php }else{ ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['distance']) - time() ?>"></div>
                                <?php } ?>
                                <?php } else { ?>
                                <div class="col-amber" style="height: 75px;line-height: 75px;">Đang tạm dừng ...</div>
                                <?php } ?>
                                <p style="height: 40px;overflow: hidden;">Sẽ gửi yêu cầu tham gia nhóm <b><?= $schedule['nearJoin']['groupName'] ?></b></p>
                                <a href="<?= SITE_URL ?>/nhom-facebook/xem-chi-tiet-tham-gia-nhom/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                <?php if($schedule['status'] == 1) { ?>
                                <button type="button" class="btn btn-warning waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tạm dừng"><i class="material-icons" style="font-size:14px">pause</i></button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-success waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tiếp tục"><i class="material-icons" style="font-size:14px">play_arrow</i></button>
                                <?php } ?>
                                <button type="button" class="btn btn-danger waves-effect delete-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="material-icons" style="font-size:14px">delete</i></button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- UP TOP BÀI VIẾT -->
                    <?php if($schedule['type'] == 'up-top') { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="body bg-brown align-center" style="height: 230px;">
                                <div class="align-left">
                                    <div class="image" style="float: left;margin-right: 10px;">
                                        <img src="<?= $schedule['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
                                    </div>
                                    <span style="vertical-align: -3px;"><a href="https://facebook.com/<?= $schedule['facebookAccount']['facebook_id'] ?>" style="color:#fff" target="_blank"><?= $schedule['facebookAccount']['name'] ?></a></span>
                                    <div class="badge bg-deep-orange" style="float:right;margin-top: 2px;">Đã up <?= $schedule['commented'] ?>/<?= $schedule['comment'] ?></div>
                                </div>
                                <div style="clear: both;"></div>
                                <?php if($schedule['status'] == 1) { ?>
                                <p class="m-b-0 m-t-10">Đếm ngược</p>
                                <?php if($schedule['commented'] != 0 && $schedule['pause'] != 0 && $schedule['commented'] % $schedule['pause'] == 0) { ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['pause_time'] + $schedule['distance']) - time() ?>"></div>
                                <?php }else{ ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['distance']) - time() ?>"></div>
                                <?php } ?>
                                <?php } else { ?>
                                <div class="col-amber" style="height: 75px;line-height: 75px;">Đang tạm dừng ...</div>
                                <?php } ?>
                                <p style="height: 40px;overflow: hidden;">Sẽ bình luận vào bài viết có ID <b><?= $schedule['nearComment']['post_id'] ?></b></p>
                                <a href="<?= SITE_URL ?>/binh-luan-facebook/xem-chi-tiet-up-top/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                <?php if($schedule['status'] == 1) { ?>
                                <button type="button" class="btn btn-warning waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tạm dừng"><i class="material-icons" style="font-size:14px">pause</i></button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-success waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tiếp tục"><i class="material-icons" style="font-size:14px">play_arrow</i></button>
                                <?php } ?>
                                <button type="button" class="btn btn-danger waves-effect delete-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="material-icons" style="font-size:14px">delete</i></button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Add Friend -->
                    <?php if($schedule['type'] == 'add-friend') { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="body bg-blue-grey align-center" style="height: 230px;">
                                <div class="align-left">
                                    <div class="image" style="float: left;margin-right: 10px;">
                                        <img src="<?= $schedule['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
                                    </div>
                                    <span style="vertical-align: -3px;"><a href="https://facebook.com/<?= $schedule['facebookAccount']['facebook_id'] ?>" style="color:#fff" target="_blank"><?= $schedule['facebookAccount']['name'] ?></a></span>
                                    <div class="badge bg-yellow" style="float:right;margin-top: 2px;color:black!important;">Đã add <?= $schedule['added'] ?>/<?= $schedule['add'] ?></div>
                                </div>
                                <div style="clear: both;"></div>
                                <?php if($schedule['status'] == 1) { ?>
                                <p class="m-b-0 m-t-10">Đếm ngược</p>
                                <?php if($schedule['added'] != 0 && $schedule['pause'] != 0 && $schedule['added'] % $schedule['pause'] == 0) { ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['pause_time'] + $schedule['distance']) - time() ?>"></div>
                                <?php }else{ ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['distance']) - time() ?>"></div>
                                <?php } ?>
                                <?php } else { ?>
                                <div class="col-amber" style="height: 75px;line-height: 75px;">Đang tạm dừng ...</div>
                                <?php } ?>
                                <p style="height: 40px;overflow: hidden;">Sẽ gửi yêu cầu kết bạn đến <b><?= $schedule['nearAdd']['user_name'] ?></b></p>
                                <a href="<?= SITE_URL ?>/ban-be-facebook/xem-chi-tiet-ket-ban/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                <?php if($schedule['status'] == 1) { ?>
                                <button type="button" class="btn btn-warning waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tạm dừng"><i class="material-icons" style="font-size:14px">pause</i></button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-success waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tiếp tục"><i class="material-icons" style="font-size:14px">play_arrow</i></button>
                                <?php } ?>
                                <button type="button" class="btn btn-danger waves-effect delete-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="material-icons" style="font-size:14px">delete</i></button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- POST FRIEND -->
                    <?php if($schedule['type'] == 'post-friend') { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="body bg-grey align-center" style="height: 230px;">
                                <div class="align-left">
                                    <div class="image" style="float: left;margin-right: 10px;">
                                        <img src="<?= $schedule['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
                                    </div>
                                    <span style="vertical-align: -3px;"><a href="https://facebook.com/<?= $schedule['facebookAccount']['facebook_id'] ?>" style="color:#fff" target="_blank"><?= $schedule['facebookAccount']['name'] ?></a></span>
                                    <div class="badge bg-teal" style="float:right;margin-top: 2px;">Đã đăng <?= $schedule['posted'] ?>/<?= $schedule['post'] ?></div>
                                </div>
                                <div style="clear: both;"></div>
                                <?php if($schedule['status'] == 1) { ?>
                                <p class="m-b-0 m-t-10">Đếm ngược</p>
                                <?php if($schedule['posted'] != 0 && $schedule['pause'] != 0 && $schedule['posted'] % $schedule['pause'] == 0) { ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['pause_time'] + $schedule['distance']) - time() ?>"></div>
                                <?php }else{ ?>
                                <div class="count-down font-32" data-seconds="<?= (strtotime($schedule['updated_at']) + $schedule['distance']) - time() ?>"></div>
                                <?php } ?>
                                <?php } else { ?>
                                <div class="col-amber" style="height: 75px;line-height: 75px;">Đang tạm dừng ...</div>
                                <?php } ?>
                                <p style="height: 40px;overflow: hidden;">Sẽ đăng lên tường <b><?= $schedule['nearPost']['facebook_id'] ?></b></p>
                                <a href="<?= SITE_URL ?>/ban-be-facebook/xem-chi-tiet-dang-len-tuong-ban-be/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                <?php if($schedule['status'] == 1) { ?>
                                <button type="button" class="btn btn-warning waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tạm dừng"><i class="material-icons" style="font-size:14px">pause</i></button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-success waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tiếp tục"><i class="material-icons" style="font-size:14px">play_arrow</i></button>
                                <?php } ?>
                                <button type="button" class="btn btn-danger waves-effect delete-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="material-icons" style="font-size:14px">delete</i></button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="row clearfix">
                <!-- Visitors -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body">
                            <div class="font-bold">THÊM TÀI KHOẢN FACEBOOK</div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#add_with_account" data-toggle="tab">
                                        <i class="material-icons">lock</i> TÀI KHOẢN
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#add_with_accesstoken" data-toggle="tab">
                                        <i class="material-icons">vpn_key</i> ACCESS TOKEN
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="add_with_account">
                                     <form action="<?= SITE_URL ?>/tai-khoan-facebook/them-bang-username-password" method="POST" class="align-center" id="login-with-account">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="username" class="form-control" id="email">
                                                <label class="form-label">Tài khoản Facebook</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="password" name="password" class="form-control" id="password">
                                                <label class="form-label">Mật khẩu Facebook</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="response">
                                        <button type="submit" class="btn btn-lg btn-primary waves-effect">THÊM TÀI KHOẢN</button>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="add_with_accesstoken">
                                    <p>Bước 1: <a class="btn bg-pink btn-xs waves-effect" href="https://goo.gl/O25zAd" target="_blank">VÀO ĐÂY</a> và ấn OK 3 lần</p>
                                    <p>Bước 2: <a class="btn bg-pink btn-xs waves-effect" href="https://developers.facebook.com/tools/debug/accesstoken/?app_id=41158896424" target="_blank">VÀO ĐÂY</a> copy mã truy cập dán xuống khung bên dưới</p>
                                    <hr>
                                    <form action="<?= SITE_URL ?>/tai-khoan-facebook/them-bang-access-token" method="POST" class="align-center">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea rows="4" name="access_token" class="form-control no-resize" placeholder="Dán Accesstoken của bạn vào đây ..."></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-lg btn-primary waves-effect">THÊM TÀI KHOẢN</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Visitors -->
                                <!-- Task Info -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="header">
                            <h2>TÀI KHOẢN FACEBOOK</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive" id="all-facebook-account" style="max-height: 498px;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Facebook ID</th>
                                            <th>Avatar & Tên</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php foreach($data['facebookAccounts'] as $account) { ?>
                                        <tr>
                                            <td id="facebook_id"><?= $account['facebook_id'] ?></td>
                                            <td>
                                                <a href="https://facebook.com/<?= $account['facebook_id'] ?>">
	                                            <div class="image" style="float: left;margin-right: 10px;">
								                    <img src="<?= $account['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
								                </div>
	                                            <span style="vertical-align: -3px;"><?= $account['name'] ?></span>
                                                </a>
                                            </td>
                                            <td><?= $account['status']==1 ? '<span class="label bg-green">Còn sống</span>' : '<span class="label bg-red" data-toggle="tooltip" data-placement="top" title="'.$account['error_message'].'">Bị khóa</span>' ?></td>
                                            <td><button type="button" class="btn bg-red btn-block btn-xs waves-effect delete-facebook-account" id="<?= $account['id'] ?>"><i class="material-icons" style="font-size:14px;">delete</i> Xóa</button></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Task Info -->
            </div>
        </div>
    </section>
<?php require_once ROOT . '/app/view/layout/footer.php' ?>
<!-- Javascript Add-on -->
<!-- Javascript Add-on -->
<div id="altContent" style="display: none">
      <h1>TheManInTheMiddle</h1>
      <p><a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash player</a></p>
</div>
<script src="<?= SITE_URL ?>/public/js/swfobject.js"></script>
<script type="text/javascript">

    $("#login-with-account").submit(function () {
        var email = $(this).find('input[name="username"]').val();
        var password = $(this).find('input[name="password"]').val();
        var submit = $(this).find('button[type="submit"]');
        submit.waitMe({
            effect: "facebook",
            bg: "rgba(255,255,255,0.8)",
        });
        get('response', 'https://api.facebook.com/method/auth.login?credentials_type=password&password='+password+'&email='+email+'&format=json&generate_session_cookies=1&locale=vi_VN&method=auth.login&access_token=350685531728|62f8ce9f74b12f84c123cc23437a4a32');
        return false;
    });

    $('.delete-schedule').click(function () {
        $id = parseInt($(this).attr('id'));
        swal({
          title: "Bạn có chắc chắn muốn xóa tác vụ này không?",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
          confirmButtonText: "Có",
          cancelButtonText: "Không"
        },
        function(){
            $.get(SITE_URL + '/ajax/deleteSchedule/' + $id, function (res) {
                $data = JSON.parse(res);
                if($data.error) swal('Lỗi',$data.error,'error');
                else location.reload();
            });
        });
    });

    $('.pause-schedule').click(function () {
        $id = parseInt($(this).attr('id'));
        $wrap = $(this).parent().parent();
        $wrap.waitMe({
            effect: "facebook",
            bg: "rgba(255,255,255,0.8)",
        });
        $.get(SITE_URL + '/ajax/pauseSchedule/' + $id, function (res) {
            $data = JSON.parse(res);

            if($data.error) swal('Lỗi',$data.error,'error');
            else location.reload();
        });
    });

    $('.delete-facebook-account').click(function () {
        $id = $(this).attr('id');
        swal({
          title: "Bạn có chắc chắn muốn xóa tài khoản này không?",
          text : "Nếu xóa tài khoản các lịch đăng bài cũng sẽ bị hủy",
          type: "warning",
          showCancelButton: true,
          closeOnConfirm: false,
          showLoaderOnConfirm: true,
          confirmButtonText: "Có",
          cancelButtonText: "Không"
        },
        function(){
            $.get(SITE_URL + '/tai-khoan-facebook/xoa-tai-khoan/' + $id, function (res) {
                $data = JSON.parse(res);
                if($data.error) {
                    swal({title:$data.error,type:'error',timer:2000});
                } else {
                    swal({title:"Xóa thành công",type:'success',timer:2000});
                    location.reload();
                }
            });
        });
    });

    $.each($('.count-down'), function(key,val) {
        $seconds = $(val).data('seconds');
        if($seconds <= 0) {
            $(val).html('<div class="preloader pl-size-sm"><div class="spinner-layer pl-black"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>');
            setInterval(function () {
                location.reload();
            }, 20000);
        } else {
            var stopwatch = new Stopwatch({
              'element': $(val), 
              'paused': false,       
              'elapsed': $seconds,        
              'countingUp': false,
              'timeLimit': 0,                   
              'updateRate': 1000,               
              'onTimeUp': function() { location.reload(); },
              'onTimeUpdate': function() {
                    $seconds = this.elapsed;
                    if($seconds < 60) $(this.element).html(formatTwoDigit(this.elapsed)); 
                    else if($seconds < 3600) $(this.element).html(formatTwoDigit(parseInt(this.elapsed / 60)) + ':' + formatTwoDigit(parseInt(this.elapsed % 60))); 
                    else $(this.element).html(formatTwoDigit(parseInt(this.elapsed / 3600)) + ':' + formatTwoDigit(parseInt(this.elapsed % 3600 / 60))  + ':' + formatTwoDigit(parseInt(this.elapsed % 3600 % 60)));
               }
            });
        }
    });

    function formatTwoDigit ($number) {
        if($number > 9) return $number;
        return "0" + $number;
    }

    var flashvars = {};
    var params = {
        menu: "false",
        scale: "noScale",
        allowFullscreen: "true",
        allowScriptAccess: "always",
        bgcolor: "",
        wmode: "direct"
    };
    var attributes = {
        id:"TheManInTheMiddle"
    };

    swfobject.embedSWF(
        SITE_URL + "/public/swf/TheManInTheMiddle.swf", 
        "altContent", "100%", "100%", "10.0.0", 
        "expressInstall.swf",
    flashvars, params, attributes);

    function response(res) {
        var data = {};
        data.response = decodeURIComponent(res);
        $.post(SITE_URL + '/tai-khoan-facebook/them-bang-username-password', data, function (res) {
            res = JSON.parse(res);
            if(res.error) {
                showAlert('error', res.error);
                $("#login-with-account").find('button[type="submit"]').waitMe('hide');
            } else {
                showAlert('success', res.success);
                location.reload();
            }
        });
    }

    function get(callback, url) {
      var obj = document.getElementById('TheManInTheMiddle');
      if (obj.get) {
        obj.get(callback,url);
      }
      else {
        setTimeout(function() {
          get(callback, url);
        }, 100);
      }
    }

</script> 