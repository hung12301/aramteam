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
                            <h2>TỰ ĐỘNG GỬI LỜI MỜI KẾT BẠN</h2>
                        </div>
                        <div class="body">
                            <form id="wizard_with_validation" method="POST" action="<?= SITE_URL ?>/ban-be-facebook/tu-dong-ket-ban">
                                <h2 class="card-inside-title">Chọn tài khoản: </h2>
                                <select class="form-control show-tick" name="facebook_id" data-size="8" id="select-facebook-account" required>
                                    <option value="0">-- Ấn vào đây để chọn tài khoản --</option>
                                    <?php foreach ($data['facebookAccounts'] as $account) { ?>
                                    <option data-content='<div class="image" style="float: left;margin-right: 10px;"><img src="<?= $account['avatar'] ?>" width="24" height="24" alt="User" style="border-radius:50%;"></div><span style="vertical-align: -3px;"><?= $account['name'] ?></span>' value="<?= $account['facebook_id'] ?>"></option>
                                    <?php } ?>
                                </select>


                                <h2 class="card-inside-title">Lấy danh sách từ: </h2>
                                <input name="type" type="radio" id="user" class="with-gap radio-col-black" value="user" checked="checked">
                                <label for="user" class="m-r-20">BẠN BÈ</label>
                                <input name="type" type="radio" id="group" class="with-gap radio-col-black" value="group">
                                <label for="group">THÀNH VIÊN NHÓM</label>

								
								<h2 class="card-inside-title">Link hoặc ID: </h2>
                                <div class="input-group" style="width: 300px;margin-bottom: 0px;">
                                    <span class="input-group-addon">
                                        <i class="material-icons">search</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="link" class="form-control date" placeholder="Nhập Link hoặc ID" required>
                                    </div>
                                    <span class="input-group-addon">
                                        <button type="button" id="search" class="btn btn-primary waves-effect" style="cursor: pointer;">LẤY DANH SÁCH</button>
                                    </span>
                                </div>

                                <div class="loading"></div>
                                
                                <div id="my-friends">
                                </div>

                                <div id="show-users">
                                </div>

                                <div id="input-users">
                                </div>

                                <h2 class="card-inside-title" id="title-distance">Khoảng cách giữa 2 lần gửi lời mời: 200 giây</h2>
                                <div id="distance"></div>
                                <input type="hidden" name="distance" value="200">
                                
                                <h2 class="card-inside-title" id="title-post-done">Thời gian hoàn thành: Chưa xác định</h2>

                                <br>
                                <br>
                                
                                <center>
                                <button type="submit" class="btn bg-cyan btn-block btn-lg waves-effect" style="width:150px;">BẮT ĐẦU</button>
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
        $("#title-distance").html('Khoảng cách giữa 2 lần gửi lời mời: '+parseInt(val)+' giây');
        // Update thời gian hoàn thành
        calcTimeDone();
    });

    $('button[type="submit"]').click(function () {
        $accountID = $("#select-facebook-account").val();
        $numberGroup = $("#input-users").find("input[checked='checked']").length;
        $link = $('input[name="link"]').val();
        if($accountID !== 0 && $numberGroup > 0 && $link !== '') {
            $(this).waitMe({
                effect: "facebook",
                bg: "rgba(255,255,255,0.8)",
            });
        }
    });

    $("body").on('change', 'input[name="select-group"]', function () {
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

    $("body").on('click','.get-friends', function () {
        $id = $(this).attr('id');
        $('input[name="link"]').val($id);
        $("#search").click();
    });

    $("input[name='type']").on('change', function () {
    	$accountID = parseInt($("#select-facebook-account").val());
    	$type = $(this).val();
    	if($accountID !== 0) selectChange();
    });

    $("#select-facebook-account").on('changed.bs.select', function () {
    	selectChange();
    });

    $("#search").click(function () {

        $type = $("input[name='type']:checked").val();
        $link = $('input[name="link"]').val();

        if($link === '') {
            swal({title: "Bạn chưa nhập Link hoặc ID",type: 'error',timer: 1000,showConfirmButton: false});
            return false;
        }

        $accountID = $("#select-facebook-account").val();
        if($accountID === '0'){
            swal({title: "Bạn chưa chọn tài khoản",type: 'error',timer: 1000,showConfirmButton: false});
            return false;
        }
        // RESET
        $("#show-users").html('');
        $("#input-users").html('');
        $("#my-friends").html('');
        // CREATE TABLE
        $html = '<div class="table-responsive">';
        $html += '<table class="table table-striped table-hover js-basic-example show-users">';
        $html += '<thead><tr><th></th><th>STT</th><th>Facebook ID</th><th>Tên</th></tr></thead>';
        $html += '<tbody id="all-users"></tbody>';
        $html += '</table>';
        $html += '</div>';
        $("#show-users").append($html);
        $(".loading").html('<div class="preloader pl-size-xl"><div class="spinner-layer"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"></div></div></div>');

        $isLink = false;
        if($link.indexOf("facebook.com") > -1) {
            $isLink = true;
        }

        if($type == 'user') {
	        if($isLink === true) {
	            getFacebookIdByUrl($link,$type,function (res) {
	                if(res.error) showAlert('error', res.error);
	                showFriendsOfUser($accountID,res.success);
	            });
	        } else {
	            showFriendsOfUser($accountID,$link);
	        }
        } else {
        	if($isLink === true) {
	            getFacebookIdByUrl($link,$type,function (res) {
	                if(res.error) showAlert('error', res.error);
	                showMembersOfGroup($accountID,res.success);
	            });
	        } else {
	            showMembersOfGroup($accountID,$link);
	        }
        }

        return false;
    });

    function selectChange () {
    	// RESET
        $("#show-users").html('');
        $("#input-users").html('');
        $("#my-friends").html('');
        // GET INFO
        $accountID = $("#select-facebook-account").val();
        $type = $("input[name='type']:checked").val();
        // CREATE TABLE
        $html = '<div class="table-responsive">';
        $html += '<table class="table table-bordered table-striped table-hover js-basic-example my-friends">';
        if($type == 'user')
        	$html += '<thead><tr><th>STT</th><th>Facebook ID</th><th>Tên</th><th>Hành động</th></tr></thead>';
    	if($type == 'group')
    		$html += '<thead><tr><th>STT</th><th>ID Nhóm</th><th>Tên</th><th>Hành động</th></tr></thead>';
        $html += '<tbody id="my-friends-show"></tbody>';
        $html += '</table>';
        $html += '</div>';
        $("#my-friends").append($html);
        $(".loading").html('<div class="preloader pl-size-xl"><div class="spinner-layer"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"></div></div></div>');
        if($type == 'user') {
        	showFriendsOfMyAccount($accountID);
        }
        if($type == 'group') {
        	showGroupsOfMyAccount($accountID);
        }
    }

    function showGroupsOfMyAccount ($accountID) {
    	getAccessTokenByFacebookID($accountID,function ($data) {
            if(!$data) showAlert('error', 'Có lỗi xảy ra trong quá trình lấy danh sách bạn bè');
            $access_token = $data.access_token;
            getAllGroupOfUser('me', $access_token, function(res) {
                if(res.error) showAlert('error', res.error.message);
                $(".loading").html('');
                $.each(res.data, function(key,val){
                    $html = '';
                    $html += '<tr>';
                    $html += '<td>'+(key+1)+'</td>';
                    $html += '<td>'+val.id+'</td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '<td><button type="button" class="btn bg-teal btn-block btn-xs waves-effect get-friends" id="'+val.id+'">LẤY DANH SÁCH</button></td>';
                    $html += '</tr>';
                    $("body").find("#my-friends-show").append($html);
                });
                // show table
                showTable('my-friends');
            });
        });
    }

    function showFriendsOfMyAccount($accountID) {
    	getAccessTokenByFacebookID($accountID,function ($data) {
            if(!$data) showAlert('error', 'Có lỗi xảy ra trong quá trình lấy danh sách bạn bè');
            $access_token = $data.access_token;
            getAllFriendOfUser('me', $access_token, function(res) {
                if(res.error) showAlert('error', res.error.message);
                $(".loading").html('');
                $.each(res.data, function(key,val){
                    $html = '';
                    $html += '<tr>';
                    $html += '<td>'+(key+1)+'</td>';
                    $html += '<td>'+val.id+'</td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '<td><button type="button" class="btn bg-teal btn-block btn-xs waves-effect get-friends" id="'+val.id+'">LẤY DANH SÁCH</button></td>';
                    $html += '</tr>';
                    $("body").find("#my-friends-show").append($html);
                });
                // show Table
                showTable('my-friends');
            });
        });
    }

    function showMembersOfGroup ($accountID, $groupID) {
    	getAccessTokenByFacebookID($accountID, function (res) {
            if(!res) {showAlert('error', 'Tài khoản này có thể đã bị khóa ! Vui lòng chọn tài khoản khác'); return;}
            getAllMembersOfGroup($groupID,$access_token,function (res) {
            	console.log(res);
                if(res.error) {showAlert('error', res.error.message); return;}
                $data = res.data;
                $(".show-users").show();
                $("#all-users").html('');
                $(".loading").html('Có '+$data.length+' kết quả !');
                $.each($data, function (key,val) {
                    $html = '<tr>';
                    $html += '<td><input type="checkbox" name="select-group" id="'+val.id+'" class="filled-in" value="'+val.id+'" checked=""><label for="'+val.id+'" style="height:9px;"></label></td>';
                    $html += '<td>'+(key+1)+'</td>';
                    $html += '<td>'+val.id+'</td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '</tr>';
                    $("#all-users").append($html);
                    $("#input-users").append('<input type="checkbox" name="users[]" data-id="'+val.id+'" class="filled-in" value="'+val.id+'|'+val.name+'" style="display:none" checked="checked">');
                });

                // show table
                showTable('show-users');

                calcTimeDone ();
            });
        });
    }

    function showFriendsOfUser ($accountID, $userID) {
        getAccessTokenByFacebookID($accountID, function (res) {
            if(!res) {showAlert('error', 'Tài khoản này có thể đã bị khóa ! Vui lòng chọn tài khoản khác'); return;}
            getAllFriendOfUser($userID,$access_token,function (res) {
                if(res.error) {showAlert('error', res.error.message); return;}
                $data = res.data;
                $(".show-users").show();
                $("#all-users").html('');
                $(".loading").html('Có '+$data.length+' kết quả !');
                $.each($data, function (key,val) {
                    $html = '<tr>';
                    $html += '<td><input type="checkbox" name="select-group" id="'+val.id+'" class="filled-in" value="'+val.id+'" checked=""><label for="'+val.id+'" style="height:9px;"></label></td>';
                    $html += '<td>'+(key+1)+'</td>';
                    $html += '<td>'+val.id+'</td>';
                    $html += '<td><a href="https://facebook.com/'+val.id+'" target="_blank">'+val.name+'</a></td>';
                    $html += '</tr>';
                    $("#all-users").append($html);
                    $("#input-users").append('<input type="checkbox" name="users[]" data-id="'+val.id+'" class="filled-in" value="'+val.id+'|'+val.name+'" style="display:none" checked="checked">');
                });

                // show table
                showTable('show-users');

                calcTimeDone ();
            });
        });
    }

    function calcTimeDone () {
        $numberGroup = $("#input-users").find("input[checked='checked']").length;
        $distance = $("input[name='distance']").val();
        var val = $("input[name='pause-time']").val();
        if($numberGroup === 0) $("#title-post-done").html('Thời gian hoàn thành: Chưa xác định');
        else {
            $timePostDone = parseInt($distance) * $numberGroup / 60;
            if($timePostDone > 60) $("#title-post-done").html('Thời gian hoàn thành: ' + parseInt($timePostDone/60) + ' tiếng ' + parseInt($timePostDone%60) + ' phút');
            else $("#title-post-done").html('Thời gian hoàn thành: ' + parseInt($timePostDone) + ' phút');
        }
    }

    function setButtonWavesEffect(event) {
        $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
        $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
    }

</script> 