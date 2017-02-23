<?php require_once ROOT . '/app/view/layout/header.php' ?>
<?php require_once ROOT . '/app/view/layout/sidebar.php' ?>
<?php require_once ROOT . '/app/view/layout/rightsidebar.php' ?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TỰ ĐỘNG UP TOP BÀI VIẾT</h2>
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
							
							<h2 class="card-inside-title">Link/ID bài viết: </h2>
							<div class="input-group" style="margin-bottom: 0px;">
                                <div class="form-line">
                                    <textarea rows="1" class="form-control no-resize" name="list" placeholder="Nhập ID hoặc link bài viết (Mỗi bài viết một dòng)... ENTER để xuống dòng" required="required"><?= $data['listPostID'] != '' ? $data['listPostID'] : '' ?></textarea>
                                </div>
                            </div>

                            <h2 class="card-inside-title">Nội dung: </h2>
                            <div class="input-group" style="margin-bottom: 5px;">
                                <div class="form-line">
                                    <textarea rows="1" class="form-control no-resize" name="content" placeholder="Nhập nội dung... ENTER để xuống dòng" required="required"></textarea>
                                </div>
                            </div>
                            <small>Gõ: {icon} để random một icon nào đó</small>

                            <div class="loading"></div>
                            <div id="show-groups">
                            </div>

                            <div id="input-groups">
                            </div>

                            <h2 class="card-inside-title" id="title-distance">Khoảng cách giữa 2 lần up bài: 200 giây</h2>
                            <div id="distance"></div>
                            <input type="hidden" name="distance" value="200">
                            
                            <br>
                            <br>
                            
                            <center>
                            <input type="hidden" value="0" id="is-submit">
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

	autosize($('textarea[name="list"]'));
	autosize($('textarea[name="content"]'));

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
    });
</script>