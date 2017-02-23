<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<style type="text/css">
    .dataTables_wrapper .row {
        margin: 0px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TỰ ĐỘNG HỦY KẾT BẠN</h2>
                    </div>
                    <div class="body">
                        <form id="wizard_with_validation" method="POST" action="<?= SITE_URL ?>/binh-luan-facebook/up-top-bai-viet">
                            <h2 class="card-inside-title">Chọn tài khoản: </h2>
                            <select class="form-control show-tick" name="facebook_id" data-size="8" id="select-facebook-account" required>
                                <option value="0">-- Ấn vào đây để chọn tài khoản --</option>
                                <?php foreach ($data['facebookAccounts'] as $account) { ?>
                                <option data-content='<div class="image" style="float: left;margin-right: 10px;"><img src="<?= $account['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;"></div><span style="vertical-align: -3px;"><?= $account['name'] ?></span>' value="<?= $account['facebook_id'] ?>" <?= $data['facebookID'] == $account['facebook_id'] ? "selected" : "" ?>></option>
                                <?php } ?>
                            </select>

                            <div class="loading"></div>

                            <div id="show-users">
                            </div>

                            <div id="input-users">
                            </div>

                            <h2 class="card-inside-title" id="title-mutual">Hủy kết bạn với những người có dưới : 0 bạn chung</h2>
                            <div id="mutual"></div>
                            <input type="hidden" name="mutual" value="0">
                            <br><br>
                            <div id="result">
                                <div class="progress" style="margin-bottom: 0px;position: relative;">
                                    <div style="position: absolute;text-align: center;width: 100%;height: 100%;line-height: 22px;font-size:12px;z-index: 10" class="result-status">ĐANG CHẠY</div>
                                    <div class="progress-bar bg-blue progress-bar-striped active" role="progressbar"></div>
                                </div>
                            </div>
                            <br>
                            <br>
                            
                            <center>
                            <button type="submit" id="submit-form" class="btn bg-cyan btn-block btn-lg waves-effect" style="width:150px;">Bắt đầu</button>
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

    $("#result").hide();

    $("body").on('change', 'input[name="select-user"]', function () {
        alert(1);
        $id = $(this).attr('id');
        $status = this.checked;
        if($status === true) {
            $('input[data-id="'+$id+'"]').attr('checked','checked');
        } else {
            $('input[data-id="'+$id+'"]').removeAttr('checked');
        }
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    $("#wizard_with_validation").submit(function (){
        $accountID = parseInt($("#select-facebook-account").val());
        if($accountID === 0) {
            showAlert('error', 'Bạn chưa chọn tài khoản Facebook');
            return false;
        }
        $userIDs = $("#input-users").find('input[type="checkbox"]:checked');
        if($userIDs.length === 0) {
            showAlert('error', 'Không có người nào được chọn');
            return false;
        }
        $("#result").show();
        $status = $(".result-status");
        $bar = $("#result .progress-bar");
        $bar.attr('class','progress-bar bg-blue progress-bar-striped active');
        $status.attr('style', 'position: absolute;text-align: center;width: 100%;height: 100%;line-height: 22px;font-size:12px;z-index: 10');

        $("#submit-form").waitMe({
            effect: "facebook",
            bg: "rgba(255,255,255,0.8)",
        });

        $("#submit-form").attr('disabled', 'disabled');
        $("#show-users").html('');
        $("#input-users").html('');

        getAccessTokenByFacebookID($accountID,function ($data) {
            if(!$data) {
                showAlert('error', 'Có lỗi xảy ra khi đang lấy Access Token');
                $("#submit-form").waitMe('hide');
            }
            $access_token = $data.access_token;
            run($userIDs,$access_token,0, {
                'success': 0,
                'error': 0,
                'errorMutual': 0
            });
        });

        return false;
    });

    function run ($userIDs, $access_token, count, $result) {
        // Variables
        $status = $(".result-status");
        $bar = $("#result .progress-bar");
        $id = $($userIDs[count]).attr('data-id');
        $name = $($userIDs[count]).attr('data-name');
        $maxMutual = parseInt($('input[name="mutual"]').val());
        // Check
        if(count == $userIDs.length) {
            $("#submit-form").waitMe('hide');
            $status.html("HOÀN THÀNH "+count+"/"+ $userIDs.length);
            $status.attr('style', 'position: absolute;text-align: center;width: 100%;height: 100%;line-height: 22px;font-size:12px;z-index: 10;color:#fff');
            $bar.attr('class', 'progress-bar bg-green progress-bar-striped');
            $("#submit-form").removeAttr('disabled');
            return 0;
        }
        // Update
        $status.html("ĐANG CHẠY "+(count+1)+"/"+ $userIDs.length);
        $bar.attr('style','width:'+((count+1)/$userIDs.length * 100 )+'%');
        getNumberMutualFriend($id, $access_token, function ($mutual) {
            $mutual = parseInt($mutual);
            if($mutual <= $maxMutual) {
                // RUN
                deleteFriend($id,$access_token, function (res) {
                    if(res.error) {
                        showAlert('error', 'Chưa hủy kết bạn được với ' + $name);
                        run($userIDs, $access_token, count+1, $result);
                        $result.success++;
                        return;
                    }
                    showAlert('success', 'Đã hủy kết bạn với ' + $name);
                    $result.error++;
                    run($userIDs, $access_token, count+1, $result);
                });
            } else {
                $result.errorMutual++;
                run($userIDs, $access_token, count+1, $result);
            }
        });
    }

    $("#select-facebook-account").on('changed.bs.select', function () {
        selectChange();
    });

	var mutual = document.getElementById('mutual');

    noUiSlider.create(mutual, {
        start: 100,
        connect: 'lower',
        range: {
            'min': 0,
            'max': 200
        }
    });

    mutual.noUiSlider.on('update', function () {
        var val = mutual.noUiSlider.get();
        $id = $(mutual).attr('id');
        $(mutual).parent().find('input[name="'+$id+'"]').val(parseInt(val));
        $("#title-mutual").html('Hủy kết bạn với những người có dưới : '+parseInt(val)+' bạn chung');
    });

    $("body").on('change', 'input[name="select-group"]', function () {
        $id = $(this).attr('id');
        $status = this.checked;
        if($status === true) {
            $('input[data-id="'+$id+'"]').attr('checked','checked');
        } else {
            $('input[data-id="'+$id+'"]').removeAttr('checked');
        }
    });

    function selectChange () {
        // RESET
        $("#show-users").html('');
        $("#input-users").html('');
        // GET INFO
        $accountID = $("#select-facebook-account").val();
        if($accountID === '0') return;
        // CREATE TABLE
        $html = '<div class="table-responsive">';
        $html += '<table class="table table-bordered table-striped table-hover js-basic-example friend-request">';
        $html += '<thead><tr><th></th><th>Facebook ID</th><th>Tên</th></tr></thead>';
        $html += '<tbody id="friend-request-show"></tbody>';
        $html += '</table>';
        $html += '</div>';
        $("#show-users").append($html);
        $(".loading").html('<div class="preloader pl-size-xl"><div class="spinner-layer"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"></div></div></div>');
        showFriends($accountID);
    }

    function showFriends($accountID) {
        getAccessTokenByFacebookID($accountID,function ($data) {
            if(!$data) showAlert('error', 'Có lỗi xảy ra trong quá trình lấy danh sách bạn bè');
            $access_token = $data.access_token;
            getAllFriendOfUser('me',$access_token, function(res) {
                if(res.error) showAlert('error', res.error.message);
                $(".loading").html('');
                $.each(res.data, function(key,val){
                    $html = '';
                    $html += '<tr>';
                    $html += '<td><input type="checkbox" id="'+val.id+'" class="filled-in select-user" name="select-user" value="'+val.id+'" checked="checked"><label for="'+val.id+'" style="height:9px;"></label></td>';
                    $html += '<td>'+val.id+'</td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '</tr>';
                    $("body").find("#friend-request-show").append($html);
                    $("#input-users").append('<input type="checkbox" name="users[]" data-id="'+val.id+'" data-name="'+val.name+'" data-mutual="'+val.mutual_friend_count+'" class="filled-in" style="display:none" checked="checked">');
                });
                // show Table
                showTable('friend-request');
            });
        });
    }
</script>