    <script>
        var SITE_URL = "<?= SITE_URL ?>";
    </script>

    <!-- Jquery Core Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?= SITE_URL ?>/public/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/node-waves/waves.js"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-steps/jquery.steps.js"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-validation/jquery.validate.js"></script>
    
    <!-- Select Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Morris Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/raphael/raphael.min.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="<?= SITE_URL ?>/public/plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/flot-charts/jquery.flot.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- noUISlider Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/nouislider/nouislider.js"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-spinner/js/jquery.spinner.js"></script>

    <!-- Jquery Simple Timer Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-simple-timer/jquery.simple.timer.js"></script>

    <!-- Wait Me -->
    <script src="<?= SITE_URL ?>/public/plugins/waitme/waitMe.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="<?= SITE_URL ?>/public/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= SITE_URL ?>/public/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>

    <!-- Custom Js -->
    <script src="<?= SITE_URL ?>/public/js/admin.js"></script>
    <script src="<?= SITE_URL ?>/public/js/functions.js"></script>
    <script src="<?= SITE_URL ?>/public/js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="<?= SITE_URL ?>/public/js/demo.js"></script>

    <script type="text/javascript">
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    </script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    <?php if(isset($_SESSION['user'])) { ?>
    Tawk_API.visitor = {
        name  : '<?= $_SESSION['user']['name'] ?>',
        email : '<?= $_SESSION['user']['email'] ?>'
    };
    <?php } ?>
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/58a5e6dd69c2661545c0b37d/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
	<!-- Google Analytics -->
    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-33385884-5', 'auto');
	  ga('send', 'pageview');

	</script>
	<!-- End Google Analytics -->

    <?php require_once ROOT . '/app/view/layout/alert.php' ?>
</body>

</html>