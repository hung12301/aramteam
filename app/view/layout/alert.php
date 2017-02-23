<?php if(Session::hasFlash()) { ?>

<?php
	$alert = Session::getFlash();
?>

<script type="text/javascript">
    var type = "<?= $alert['type'] ?>";
    var text = "<?= $alert['message'] ?>";
    
    if(type === 'error') {
        type = 'alert-danger';
        icon = '<i class="material-icons font-24">error</i>';   
    } else if (type === 'success') {
    type = 'alert-success';
        icon = '<i class="material-icons font-24">done</i>';
    }
    animateEnter = 'animated-fast fadeInUp';
    animateExit = 'animated-fast fadeOutRight';
    var allowDismiss = true;

    $.notify({
        message: text
    },
        {
            type: type,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            delay: 5000,
            placement: {
                    from: "top",
                    align: "right"
                },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" style="float:left;margin-right: 10px">'+icon+'</span> ' +
            '<span data-notify="message" style="vertical-align: -4px;">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
</script>

<?php } ?>