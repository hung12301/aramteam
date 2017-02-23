<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>TỰ ĐỘNG ĐĂNG BÀI LÊN NHÓM</h2>
                        </div>
                        <div class="body">
                            <form id="wizard_with_validation" method="POST" action="<?= SITE_URL ?>/nhom-facebook/len-lich-dang">
                                <h2 class="card-inside-title">Chọn tài khoản: </h2>
                                <select class="form-control show-tick" name="facebook_id" data-size="8" id="select-facebook-account" required>
                                    <option value="0">-- Ấn vào đây để chọn tài khoản --</option>
                                    <?php foreach ($data['facebookAccounts'] as $account) { ?>
                                    <option data-content='<div class="image" style="float: left;margin-right: 10px;"><img src="<?= $account['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;"></div><span style="vertical-align: -3px;"><?= $account['name'] ?></span>' value="<?= $account['facebook_id'] ?>"></option>
                                    <?php } ?>
                                </select>
								
								<h2 class="card-inside-title">Link chia sẻ: </h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">link</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="link" class="form-control date" placeholder="Nhập link cần chia sẻ" required>
                                    </div>
                                </div>

                                <h2 class="card-inside-title">Mô tả link (không bắt buộc): </h2>
                                <div class="input-group" style="margin-bottom: 0px;">
                                    <div class="form-line">
                                        <textarea rows="1" class="form-control no-resize" name="description" placeholder="Nhập mô tả link... ENTER để xuống dòng"></textarea>
                                    </div>
                                </div>

                                <div class="loading"></div>
                                <div id="show-groups">
                                </div>

                                <div id="input-groups">
                                </div>

                                <h2 class="card-inside-title" id="title-pause" style="margin-bottom: 0px;">Số nhóm đăng trong một ngày: 15 nhóm</h2>
                                <small class="col-red" style="margin-bottom: 20px;">Để tránh bị khóa tài khoản thì bạn nên để đăng 15-20 nhóm một ngày thôi</small>
                                <div id="pause"></div>
                                <input type="hidden" name="pause" value="15">

                                <h2 class="card-inside-title" id="title-distance">Khoảng cách giữa 2 bài đăng: 200 giây</h2>
                                <div id="distance"></div>
                                <input type="hidden" name="distance" value="200">
                                
                                <h2 class="card-inside-title" id="title-pause-time">Thời gian nghỉ sau khi đăng xong 20 nhóm: 14 tiếng</h2>
                                <div id="pause-time"></div>
                                <input type="hidden" name="pause-time" value="14">

                                <h2 class="card-inside-title" id="title-post-done">Thời gian hoàn thành: Chưa xác định</h2>

                                <br>
                                <br>
                                <div class="switch">
                                    <label><input type="checkbox" name="repeat" checked=""><span class="lever"></span>Lặp lại</label>
                                </div>
                                <center>
                                <button type="submit" class="btn bg-cyan btn-block btn-lg waves-effect" style="width:150px;">ĐĂNG</button>
                                </center>
                            </form>
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

    autosize($("textarea[name='description']"));

    var postDistance = document.getElementById('distance');

    noUiSlider.create(postDistance, {
        start: 200,
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
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    var pauseTime = document.getElementById('pause-time');

    noUiSlider.create(pauseTime, {
        start: 14,
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
        $("#title-pause-time").html('Thời gian nghỉ sau khi đăng xong '+$('input[name="pause"]').val()+' nhóm: '+parseInt(val)+' tiếng');
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    var pause = document.getElementById('pause');

    noUiSlider.create(pause, {
        start: 15,
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
        $("#title-pause").html('Số nhóm đăng trong 1 ngày: '+parseInt(val)+' nhóm');
        $("#title-pause-time").html('Thời gian nghỉ sau khi đăng xong '+parseInt(val)+' nhóm: '+$('input[name="pause-time"]').val()+' tiếng');
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    $("body").on('change', 'input[name="select-group"]', function () {
        $id = $(this).attr('id');
        $status = this.checked;
        if($status === true) {
            $("#input-groups").find('input[data-id="'+$id+'"]').prop('checked',true);
        } else {
            $("#input-groups").find('input[data-id="'+$id+'"]').prop('checked',false);
        }
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    $("#select-facebook-account").on('changed.bs.select', function () {
        $("#show-groups").html('');
        $("#input-groups").html('');
        $html = '<table class="table table-bordered table-striped table-hover js-basic-example dataTable">';
        $html += '<thead><tr><th></th><th>Tên</th></tr></thead>';
        $html += '<tbody id="all-groups"></tbody>';
        $html += '</table>';
        $("#show-groups").append($html);
        $(".loading").html('<div class="preloader pl-size-xl"><div class="spinner-layer"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"></div></div></div>');
        $id = $(this).val();
        getAccessTokenByFacebookID($id, function (res) {
            if(!res) {showAlert('error', 'Có lỗi xảy ra trong quá trình lấy các nhóm');return;}
            $access_token = res.access_token;
            getAllGroupOfUser($id,$access_token, function (res) {
                $(".loading").html('');
                if(res.data.length <= 0) {showAlert('error', 'Tài khoản này chưa tham gia nhóm nào cả');return;}
                $(".dataTable").show();
                $("#all-groups").html('');
                $.each(res.data, function (key,val) {
                    $html = '<tr>';
                    $html += '<td><input type="checkbox" name="select-group" id="'+val.id+'" class="filled-in" value="'+val.id+'" checked=""><label for="'+val.id+'" style="height:9px;"></label></td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '</tr>';
                    $("#all-groups").append($html);
                    $("#input-groups").append('<input type="checkbox" name="groups[]" data-id="'+val.id+'" class="filled-in" value="'+val.id+'" style="display:none" checked="checked">');
                });

                // Show Table
                showTable('dataTable');

                // Update thời gian hoàn thành
                calcTimeDone();
            });
        });
    });
                        
    function calcTimeDone () {
        $numberGroup = $("#input-groups").find("input[checked='checked']").length;
        $numberPostOneDay = $("input[name='pause']").val();
        var val = $("input[name='pause-time']").val();
        if($numberGroup === 0) $("#title-post-done").html('Thời gian hoàn thành: Chưa xác định');
        else {
            if($numberGroup < $numberPostOneDay) {
                $timePostDone = parseInt($("input[name='distance']").val()) * $numberGroup / 60;
                if($timePostDone > 60) $("#title-post-done").html('Thời gian hoàn thành: ' + parseInt($timePostDone/60) + ' tiếng ' + parseInt($timePostDone%60) + ' phút');
                else $("#title-post-done").html('Thời gian hoàn thành: ' + parseInt($timePostDone) + ' phút');
            } else {
                $timePostDone = ($numberGroup / $numberPostOneDay) * parseInt(val);
                if($timePostDone > 24) $("#title-post-done").html('Thời gian hoàn thành: ~' + parseInt($timePostDone / 24) + ' ngày ' + parseInt($timePostDone % 24) + ' tiếng');
                else $("#title-post-done").html('Thời gian hoàn thành: ~' + parseInt($timePostDone) + ' tiếng');
            }
        }
    }

    function setButtonWavesEffect(event) {
        $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
        $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
    }

</script> 