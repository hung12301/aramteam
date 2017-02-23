<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<style>
    .dataTables_wrapper {
        border: none;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TẤT CẢ TÁC VỤ</h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example task">
                            <thead>
                                <tr>
                                    <th style="display: none">STT</th>
                                    <th>Thời gian</th>
                                    <th style="padding-right: 60px;">Tài khoản</th>
                                    <th>Loại</th>
                                    <th style="padding-right: 100px;">Tiến trình</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php foreach($data as $key=>$schedule) { ?>
                                <tr>
                                    <td style="display: none"><?= $key+1 ?></td>
                                    <!-- Thời gian -->
                                    <td><?= getCharTime(strtotime($schedule['created_at'])) ?></td>
                                	<!-- Tài khoản -->
                                	<td>
                                        <a href="https://facebook.com/<?= $schedule['facebookAccount']['facebook_id'] ?>">
                                        <div class="image" style="float: left;margin-right: 10px;">
						                    <img src="<?= $schedule['facebookAccount']['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;">
						                </div>
                                        <span style="vertical-align: -3px;"><?= $schedule['facebookAccount']['name'] ?></span>
                                        </a>
                                    </td>
                                    <!-- Loại -->
                                	<?php if($schedule['type'] == 'post-group') { ?>
                                	<td><span class="badge bg-teal">Đăng nhóm</span></td>
                                	<?php }else if($schedule['type'] == 'join-group'){ ?>
									<td><span class="badge bg-indigo">Tham gia nhóm</span></td>
                                	<?php } else if($schedule['type'] == 'up-top') { ?>
									<td><span class="badge bg-brown">Up top bài viết</span></td>
                                    <?php } else if($schedule['type'] == 'add-friend') { ?>
                                    <td><span class="badge bg-blue-grey">Kết bạn</span></td>
                                    <?php } else if($schedule['type'] == 'post-friend') { ?>
                                    <td><span class="badge bg-grey">Đăng lên tường bạn bè</span></td>
                                	<?php } ?>
                                	<!-- Tiến trình -->
                                	<td>
                                		<?php if($schedule['done'] == 0 && $schedule['status'] == 1) { ?>
                                		<div class="progress" style="margin-bottom: 0px;position: relative;">
                                			<div style="position: absolute;text-align: center;width: 100%;height: 100%;line-height: 22px;font-size:12px;z-index: 10">ĐANG CHẠY <?= $schedule['process']['done'] . '/' . $schedule['process']['all'] ?></div>
			                                <div class="progress-bar bg-blue progress-bar-striped active" role="progressbar" style="width: <?= $schedule['process']['done'] / $schedule['process']['all'] * 100 ?>%"></div>
			                            </div>
			                            <?php } else if($schedule['done'] == 0 && $schedule['status'] == 0) { ?>
			                            <div class="progress" style="margin-bottom: 0px;position: relative;">
			                          	  <div style="position: absolute;text-align: center;width: 100%;height: 100%;line-height: 22px;font-size:12px;z-index: 10">TẠM DỪNG <?= $schedule['process']['done'] . '/' . $schedule['process']['all'] ?></div>
			                                <div class="progress-bar bg-orange progress-bar-striped" role="progressbar" style="width: <?= $schedule['process']['done'] / $schedule['process']['all'] * 100 ?>%">
			                                </div>
			                            </div>
			                            <?php } else { ?>
			                            <div class="progress" style="margin-bottom: 0px;position: relative;">
			                            	<div style="position: absolute;text-align: center;width: 100%;height: 100%;line-height: 22px;font-size:12px;z-index: 10;color:#fff">HOÀN THÀNH <?= $schedule['process']['done'] . '/' . $schedule['process']['all'] ?></div>
			                                <div class="progress-bar bg-green progress-bar-striped" role="progressbar" style="width: 100%">
			                                </div>
			                            </div>
			                            <?php } ?>
                                	</td>
                                    <!-- Hành động -->
                                    <td>
                                        <?php if($schedule['type'] == 'post-group') { ?>
                                        <a href="<?= SITE_URL ?>/nhom-facebook/xem-chi-tiet-dang-nhom/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                        <?php } else if($schedule['type'] == 'join-group') { ?>
                                        <a href="<?= SITE_URL ?>/nhom-facebook/xem-chi-tiet-tham-gia-nhom/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                        <?php } else if ($schedule['type'] == 'up-top') { ?>
                                        <a href="<?= SITE_URL ?>/binh-luan-facebook/xem-chi-tiet-up-top/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                        <?php } else if ($schedule['type'] == 'add-friend') { ?>
                                        <a href="<?= SITE_URL ?>/ban-be-facebook/xem-chi-tiet-ket-ban/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                        <?php } else if ($schedule['type'] == 'post-friend') { ?>
                                        <a href="<?= SITE_URL ?>/ban-be-facebook/xem-chi-tiet-dang-len-tuong-ban-be/<?= $schedule['id']?>" class="btn btn-primary waves-effect m-r-20">Xem & Sửa</a>
                                        <?php } ?>
                                        <?php if($schedule['type'] == 'post-group') { ?>
                                        <a href="<?= SITE_URL ?>/ajax/upTopByScheduleId/<?= $schedule['facebook_id']?>/<?= $schedule['id']?>" class="btn bg-brown waves-effect m-r-20" data-toggle="tooltip" data-placement="top" title="Up top tất cả các bài viết đã đăng"><i class="material-icons" style="font-size:14px">trending_up</i></button></a>
                                        <?php } ?>
                                        <?php if($schedule['status'] == 1 && $schedule['done'] == 0) { ?>
                                        <button type="button" class="btn btn-warning waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tạm dừng"><i class="material-icons" style="font-size:14px">pause</i></button>
                                        <?php } else if($schedule['done'] == 0) { ?>
                                        <button type="button" class="btn btn-success waves-effect m-r-20 pause-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Tiếp tục"><i class="material-icons" style="font-size:14px">play_arrow</i></button>
                                        <?php } ?>
                                        <button type="button" class="btn btn-danger waves-effect delete-schedule" id="<?= $schedule['id'] ?>" data-toggle="tooltip" data-placement="top" title="Xóa"><i class="material-icons" style="font-size:14px">delete</i></button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT . '/app/view/layout/footer.php' ?>

<script type="text/javascript">

    // show Table
    $("body").find('.task').DataTable({
        "lengthMenu": [[-1, 10, 25, 50], ["Tất cả", 10, 25, 50]],
        "order": [[ 0, "desc" ]],
        "language": {
          "search" : "Tìm kiếm",
          "lengthMenu": "Hiển thị _MENU_ dòng trên một trang",
          "zeroRecords": "Không có dữ liệu nào",
          "info": "Trang _PAGE_ (Có tổng _PAGES_ trang)",
          "infoEmpty": "Không có trang nào",
          "paginate" : {
            "frist": "Đầu",
            "last" : "Cuối",
            "previous" : "Lùi",
            "next": "Tiếp",
          },
        }
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
</script>