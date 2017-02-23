<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>TỰ ĐỘNG THAM GIA NHÓM</h2>
                        </div>
                        <div class="body">
                            <form id="wizard_with_validation" method="POST" action="<?= SITE_URL ?>/nhom-facebook/tham-gia-nhom">
                                <h2 class="card-inside-title">Chọn tài khoản: </h2>
                                <select class="form-control show-tick" name="facebook_id" data-size="8" id="select-facebook-account" required>
                                    <option value="0">-- Ấn vào đây để chọn tài khoản --</option>
                                    <?php foreach ($data['facebookAccounts'] as $account) { ?>
                                    <option data-content='<div class="image" style="float: left;margin-right: 10px;"><img src="<?= $account['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;"></div><span style="vertical-align: -3px;"><?= $account['name'] ?></span>' value="<?= $account['facebook_id'] ?>"></option>
                                    <?php } ?>
                                </select>
								
								<h2 class="card-inside-title">Tìm nhóm: </h2>
                                <div class="input-group" style="width: 300px;margin-bottom: 0px;">
                                    <span class="input-group-addon">
                                        <i class="material-icons">search</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="keyword" class="form-control date" placeholder="Tìm nhóm mà bạn cần tham gia" required>
                                    </div>
                                    <span class="input-group-addon">
                                        <button type="button" id="search" class="btn btn-primary waves-effect" style="cursor: pointer;">TÌM KIẾM</button>
                                    </span>
                                </div>

                                <div class="loading"></div>
                                <div id="show-groups">
                                </div>

                                <div id="input-groups">
                                </div>

                                <h2 class="card-inside-title" id="title-pause" style="margin-bottom: 0px;">Số nhóm tham gia trong một ngày: 15 nhóm</h2>
                                <small class="col-red" style="margin-bottom: 20px;">Để tránh bị khóa tài khoản thì bạn nên để tham gia 15-20 nhóm một ngày thôi</small>
                                <div id="pause"></div>
                                <input type="hidden" name="pause" value="15">

                                <h2 class="card-inside-title" id="title-distance">Khoảng cách giữa 2 lần tham gia: 200 giây</h2>
                                <div id="distance"></div>
                                <input type="hidden" name="distance" value="200">

                                <h2 class="card-inside-title" id="title-pause-time">Thời gian nghỉ sau khi tham gia xong 20 nhóm: 14 tiếng</h2>
                                <div id="pause-time"></div>
                                <input type="hidden" name="pause-time" value="14">

                                <h2 class="card-inside-title" id="title-post-done">Thời gian hoàn thành: Chưa xác định</h2>
                                
                                <br>
                                <br>
                                
                                <center>
                                <input type="hidden" value="0" id="is-submit">
                                <button type="button" id="submit-form" class="btn bg-cyan btn-block btn-lg waves-effect" style="width:150px;">Tham gia</button>
                                </center>
                            </form>
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
        $("#title-distance").html('Khoảng cách giữa 2 lần tham gia: '+parseInt(val)+' giây');
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
        $("#title-pause-time").html('Thời gian nghỉ sau khi tham gia xong '+$('input[name="pause"]').val()+' nhóm: '+parseInt(val)+' tiếng');
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
        $("#title-pause").html('Số nhóm tham gia trong 1 ngày: '+parseInt(val)+' nhóm');
        $("#title-pause-time").html('Thời gian nghỉ sau khi tham gia xong '+parseInt(val)+' nhóm: '+$('input[name="pause-time"]').val()+' tiếng');
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

    $("#search").click(function () {

        $keyword = $('input[name="keyword"]').val();

        if($keyword === '') {
            swal({title: "Bạn chưa nhập tên nhóm",type: 'error',timer: 1000,showConfirmButton: false});
            return false;
        }

        $id = $("#select-facebook-account").val();
        if($id === '0'){
            swal({title: "Bạn chưa chọn tài khoản",type: 'error',timer: 1000,showConfirmButton: false});
            return false;
        }
        $("#show-groups").html('');
        $("#input-groups").html('');
        $html = '<table class="table table-striped table-hover js-basic-example dataTable">';
        $html += '<thead><tr><th></th><th>Tên</th></tr></thead>';
        $html += '<tbody id="all-groups"></tbody>';
        $html += '</table>';
        $("#show-groups").append($html);
        $(".loading").html('<div class="preloader pl-size-xl"><div class="spinner-layer"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"></div></div></div>');
        getAccessTokenByFacebookID($id,function (res) {
            if(!res) {showAlert('error', 'Có lỗi xảy ra trong quá trình lấy các nhóm ! Vui lòng chọn tài khoản khác');return;}
            $access_token = res.access_token;
            searchGroupByKeyword($keyword,$access_token, function (res) {
                $(".loading").html('');
                if(res.length <= 0) {showAlert('error', 'Không tìm thấy nhóm nào cả');return;}
                $(".dataTable").show();
                $("#all-groups").html('');
                $(".loading").html('Có '+res.length+' kết quả !');
                $.each(res, function (key,val) {
                    $html = '<tr>';
                    $html += '<td><input type="checkbox" name="select-group" id="'+val.id+'" class="filled-in" value="'+val.id+'" checked=""><label for="'+val.id+'" style="height:9px;"></label></td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '</tr>';
                    $("#all-groups").append($html);
                    $("#input-groups").append('<input type="checkbox" name="groups[]" data-id="'+val.id+'" class="filled-in" value="'+val.id+'" style="display:none" checked="checked">');
                });

                // show Table
                showTable('dataTable');

                // Update thời gian hoàn thành
                calcTimeDone();
            });
        });

        return false;
    });

    $("#wizard_with_validation").submit(function () {
        if($("#is-submit").val() === '1')
            return true;
        $("#search").click();
        return false;
    });

    $("#submit-form").click(function () {
        $("#is-submit").val('1');
        $("#wizard_with_validation").submit();
    });

    function calcTimeDone () {
        $numberGroup = $("#input-groups").find("input[checked='checked']").length;
        console.log($numberGroup);
        var val = $("input[name='pause-time']").val();
        if($numberGroup === 0) $("#title-post-done").html('Thời gian hoàn thành: Chưa xác định');
        else {
            if($numberGroup < 20) {
                $timePostDone = parseInt($("input[name='distance']").val()) * $numberGroup / 60;
                if($timePostDone > 60) $("#title-post-done").html('Thời gian hoàn thành: ' + parseInt($timePostDone/60) + ' tiếng ' + parseInt($timePostDone%60) + ' phút');
                else $("#title-post-done").html('Thời gian hoàn thành: ' + parseInt($timePostDone) + ' phút');
            } else {
                $timePostDone = ($numberGroup / 20) * parseInt(val);
                if($timePostDone > 24) $("#title-post-done").html('Thời gian hoàn thành: ~' + parseInt($timePostDone / 24) + ' ngày ' + parseInt($timePostDone % 24) + ' tiếng');
                else $("#title-post-done").html('Thời gian hoàn thành: ~' + parseInt($timePostDone) + ' tiếng');
            }
        }
    }
</script> 