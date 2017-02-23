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
                            <h2>XEM & SỬA THAM GIA NHÓM</h2>
                        </div>
                        <div class="body">
                            <form action="<?= SITE_URL ?>/nhom-facebook/sua-tham-gia-nhom/<?= $data['schedule']['id'] ?>" method="POST">
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
                                <!-- Khoảng cách -->
                                <h2 class="card-inside-title" id="title-pause" style="margin-bottom: 0px;">Số nhóm tham gia trong một ngày: <?= $data['schedule']['pause'] ?> nhóm</h2>
                                <small class="col-red" style="margin-bottom: 20px;">Để tránh bị khóa tài khoản thì bạn nên để tham gia 15-20 nhóm một ngày thôi</small>
                                <div id="pause"></div>
                                <input type="hidden" name="pause" value="<?= $data['schedule']['pause'] ?>">

                                <h2 class="card-inside-title" id="title-distance">Khoảng cách giữa 2 lần tham gia: <?= $data['schedule']['distance'] ?> giây</h2>
                                <div id="distance"></div>
                                <input type="hidden" name="distance" value="<?= $data['schedule']['distance'] ?>">

                                <h2 class="card-inside-title" id="title-pause-time">Thời gian nghỉ sau khi tham gia xong <?= $data['schedule']['pause'] ?> nhóm: <?= $data['schedule']['pause_time'] ?> tiếng</h2>
                                <div id="pause-time"></div>
                                <input type="hidden" name="pause-time" value="<?= $data['schedule']['pause_time'] ?>">

                                <h2 class="card-inside-title" id="title-post-done">Thời gian còn lại: Chưa xác định</h2>
                                <div style="text-align: center"><button type="submit" class="btn btn-lg btn-primary waves-effect">LƯU THAY ĐỔI</button></div>
                                <!-- <div style="clear: both"></div> -->
                                <br>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Thời gian</th>
                                            <th>Group ID</th>
                                            <th>Trạng thái</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['groups'] as $key=>$group) { ?>
                                        <tr>
                                            <!-- STT -->
                                            <td><?= $key+1 ?></td>
                                            <!-- Thời gian -->
                                            <?php if($group['updated_at'] != $group['created_at']) { ?>
                                            <td><?= getCharTime(strtotime($group['updated_at'])) ?></td>
                                            <?php }else{ ?>
                                            <td>Đang chờ</td>
                                            <?php } ?>
                                            <!-- Group ID -->
                                            <td><a href="https://facebook.com/<?= $group['group_id'] ?>" target="_blank"><?= $group['group_id'] ?></a></td>
                                            <!-- Trạng thái -->
                                            <?php if($group['status'] == 0) { ?>
                                            <td><span class="badge bg-orange">Đang chờ</span></td>
                                            <?php }else if($group['error_message'] != ''){ ?>
                                            <td><span class="badge bg-red">Lỗi</span></td>
                                            <?php } else { ?>
                                            <td><span class="badge bg-green">Thành công</span></td>
                                            <?php } ?>
                                            <!-- Ghi chú -->
                                            <?php if($group['error_message'] != '') { ?>
                                            <td><?= $group['error_message'] ?></td>
                                            <?php } else if($group['status'] == 1)  { ?>
                                            <td>Đã gửi yêu cầu</td>
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

<script type="text/javascript">
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
        $("#title-distance").html('Khoảng cách giữa 2 lần tham gia: '+parseInt(val)+' giây');
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    var pauseTime = document.getElementById('pause-time');

    noUiSlider.create(pauseTime, {
        start: <?= $data['schedule']['pause_time'] / 60 / 60 ?>,
        connect: 'lower',
        range: {
            'min': 12,
            'max': 24
        }
    });

    pauseTime.noUiSlider.on('update', function () {
        var val = pauseTime.noUiSlider.get();
        $id = $(pauseTime).attr('id');
        $(pauseTime).parent().find('input[name="'+$id+'"]').val(parseInt(val));
        $("#title-pause-time").html('Thời gian nghỉ sau khi tham gia xong '+$('input[name="pause"]').val()+' nhóm: '+parseInt(val)+' tiếng');
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    var pause = document.getElementById('pause');

    noUiSlider.create(pause, {
        start: <?= $data['schedule']['pause'] ?>,
        connect: 'lower',
        range: {
            'min': 1,
            'max': 50
        }
    });

    pause.noUiSlider.on('update', function () {
        var val = pause.noUiSlider.get();
        $id = $(pause).attr('id');
        $(pause).parent().find('input[name="'+$id+'"]').val(parseInt(val));
        $("#title-pause").html('Số nhóm tham gia trong 1 ngày: '+parseInt(val)+' nhóm');
        $("#title-pause-time").html('Thời gian nghỉ sau khi tham gia xong '+parseInt(val)+' nhóm: '+$('input[name="pause-time"]').val()+' tiếng');
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    function calcTimeDone () {
        $numberGroup = <?= count($data['groups']) - $data['joined'] ?>;
        $pauseTime = $("input[name='pause-time']").val();
        $distance = $("input[name='distance']").val();
        $numberPostOneDay = $("input[name='pause']").val();
        if($numberGroup === 0) $("#title-post-done").html('Thời gian còn lại: Chưa xác định');
        else {
            if($numberGroup < $numberPostOneDay) {
                $timePostDone = parseInt($distance) * $numberGroup / 60;
                if($timePostDone > 60) $("#title-post-done").html('Thời gian còn lại: ' + parseInt($timePostDone/60) + ' tiếng ' + parseInt($timePostDone%60) + ' phút');
                else $("#title-post-done").html('Thời gian còn lại: ' + parseInt($timePostDone) + ' phút');
            } else {
                $timePostDone = ($numberGroup / $numberPostOneDay) * parseInt($pauseTime);
                if($timePostDone > 24) $("#title-post-done").html('Thời gian còn lại: ~' + parseInt($timePostDone / 24) + ' ngày ' + parseInt($timePostDone % 24) + ' tiếng');
                else $("#title-post-done").html('Thời gian còn lại: ~' + parseInt($timePostDone) + ' tiếng');
            }
        }
    }
</script>