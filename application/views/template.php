<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tu Gasto Com&uacute;n | Dashboard</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="http://tugastocomun.cl/home/wp-content/uploads/2015/08/favicon.ico" />
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />



    <!-------- ACA EMPIEZAN LOS ARCHIVOS CONFIGURABLES -->

    <?php if (!isset($jQuery213)) { ?>
        <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <?php } ?>

    <?php if (isset($jQuery191)) { ?>
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <!--script>
            var jQuery = jQuery.noConflict();
            </script-->
    <?php } ?>


    <?php if (isset($maleta)) { ?>
        <script src="<?php echo base_url(); ?>js/maleta.js"></script>
    <?php } ?>

    <?php if (isset($wysihtml5)) { ?>
        <!--link href="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script-->
        <!--script src="//cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script-->
        <script src="https://cdn.ckeditor.com/4.10.1/standard-all/ckeditor.js"></script>
        <!--script type="text/javascript" src="<?php echo base_url(); ?>plugins/tiny_mce/jquery.tinymce.js"></script-->
    <?php } ?>

    <?php if (isset($formValidation)) { ?>
        <!--link rel="stylesheet" href="<?php echo base_url(); ?>vendor/bootstrap/css/bootstrap.css"/-->
        <!--link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/formValidation.css"/-->
        <script type="text/javascript" src="<?php echo base_url(); ?>dist/js/formValidation.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>dist/js/framework/bootstrap.js"></script>
    <?php } ?>


    <?php if (isset($datetimepicker)) { ?>
        <link href="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
    <?php } ?>

    <?php if (isset($daterangepicker)) { ?>
        <script src="<?php echo base_url(); ?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <link href="<?php echo base_url(); ?>plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <?php } ?>






    <?php if (isset($jqueryRut)) { ?>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.Rut/jquery.Rut.js" charset="UTF-8"></script>
    <?php } ?>


    <!-- DATA TABES SCRIPT -->
    <?php if (isset($dataTables)) { ?>
        <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <?php } ?>

    <!-- iCheck 1.0.1 -->
    <?php if (isset($icheck)) { ?>
        <link href="<?php echo base_url(); ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <?php } ?>

    <?php /*if(isset($angular)){ ?>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.10/angular.min.js"></script>
        <script src="<?php echo base_url(); ?>js/angular/app.js"></script>
        <script src="<?php echo base_url(); ?>js/angular/<?php echo $angular_controller; ?>"></script>
      <?php }*/ ?>

    <?php if (isset($multipleSelect)) { ?>
        <link href="<?php echo base_url(); ?>plugins/jQuery-Multiple-Select/dist/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>plugins/jQuery-Multiple-Select/dist/js/bootstrap-multiselect.js"></script>
    <?php } ?>

    <?php if (isset($highchartsGraph)) : ?>
        <script src="<?php echo base_url(); ?>plugins/highcharts/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>plugins/highcharts/exporting.js"></script>
        <!--script src="<?php echo base_url(); ?>plugins/highcharts/style.js"></script-->
        <script src="<?php echo base_url(); ?>plugins/highcharts/highcharts-more.js"></script>
    <?php endif; ?>


    <?php if (isset($mask)) { ?>
        <script src="<?php echo base_url(); ?>plugins/jquery.mask.min.js"></script>
    <?php } ?>


    <?php if (isset($inputmask)) { ?>
        <script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <?php } ?>


    <?php if (!isset($moment)) { ?>
        <script src="<?php echo base_url(); ?>plugins/moment.js"></script>
        <!--script src="<?php echo base_url(); ?>plugins/moment-with-locales.js"></script-->
    <?php } ?>

    <?php if (isset($daterangepicker2)) { ?>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-daterangepicker-master/daterangepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-daterangepicker-master/moment-weekday-calc.min.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>plugins/bootstrap-daterangepicker-master/daterangepicker.css" />
    <?php } ?>


    <?php if (isset($kartik)) { ?>
        <link href="<?php echo base_url(); ?>plugins/kartik/css/fileinput.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>plugins/kartik/js/fileinput.js"></script>
        <script src="<?php echo base_url(); ?>plugins/kartik/js/fileinput_locale_es.js"></script>
    <?php } ?>

    <?php if (isset($gritter)) { ?>
        <link href="<?php echo base_url(); ?>plugins/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>

    <?php } ?>

    <?php if (isset($fakeLoader)) { ?>
        <link href="<?php echo base_url(); ?>plugins/fakeLoader/fakeLoader.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>plugins/fakeLoader/fakeLoader.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                var spinner = Math.floor(Math.random() * 7) + 1;
                spinner = spinner < 1 || spinner > 7 ? 1 : spinner;

                $('.main-header').hide();

                $(".fakeloader").fakeLoader({
                    timeToHide: 2000, //Time in milliseconds for fakeLoader disappear
                    spinner: "spinner" + spinner, //Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
                    bgColor: "#605ca8", //Hex, RGB or RGBA colors
                    //imagePath:"yourPath/customizedImage.gif" //If you want can you insert your custom image
                });
                $(".flbackdrop").remove();
                setTimeout(function() {
                    $('.main-header').fadeIn()
                }, 2000);

                //$('.main-header').show();

            });
        </script>
    <?php } ?>

    <?php if (isset($loadingOverlay)) { ?>
        <script src="<?php echo base_url(); ?>plugins/loading_overlay/src/loadingoverlay.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/loading_overlay/extras/loadingoverlay_progress/loadingoverlay_progress.min.js" type="text/javascript"></script>

    <?php } ?>


    <!--script src="<?php echo base_url(); ?>plugins/usertimeout/jquery.userTimeout.js" type="text/javascript"></script-->
    <!-------->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-purple">


    <?php if (isset($fakeLoader)) { ?>
        <div class="flbackdrop"></div>
        <div class="fakeloader"></div>
    <?php } ?>

    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
            <a href="#" class="logo"><img src="<?php echo base_url(); ?>img/logo4_1_80p.png"></a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <?php //var_dump($this->session->userdata('level')); exit;
                ?>

                <?php $fin_suscripcion = $this->session->userdata('level') == 1 ? "Quedan " . $this->session->userdata('diasvencsuscripcion') . " d&iacute;as de Suscripci&oacute;n" : ""; ?>

                <span class="" style="color:white;vertical-align:middle;text-align:center;top:0;bottom:0;margin:auto;position:absolute;padding: 15px 15px;"><?php echo $this->session->userdata('comunidadnombre') != '' ? "<b>Comunidad</b>: " . $this->session->userdata('comunidadnombre') . "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;" . $fin_suscripcion : ''; ?><?php echo $this->session->userdata('comunidadnumero') != '' ? "  -   Propiedad Nro. " . $this->session->userdata('comunidadnumero') : ""; ?>
                    <!--div id="clock" class="clock">loading ...</div-->
                </span>



                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo $this->session->userdata('photo') != '' ? base_url() . 'dist/img/' . $this->session->userdata('photo') : base_url() . 'dist/img/user9-128x128.jpg'; ?>" class="user-image" alt="User Image" />
                                <span class="hidden-xs"><?php echo $this->session->userdata('name'); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo $this->session->userdata('photo') != '' ? base_url() . 'dist/img/' . $this->session->userdata('photo') : base_url() . 'dist/img/user9-128x128.jpg'; ?>" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo  $this->session->userdata('name'); ?> - <?php echo $this->session->userdata('level_name'); ?>
                                        <small>Miembro desde <?php echo $this->session->userdata('created_on');; ?></small>

                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo base_url(); ?>admins/profile" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url(); ?>auth/logout" class="btn btn-default btn-flat">Salir</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo $this->session->userdata('photo') != '' ? base_url() . 'dist/img/' . $this->session->userdata('photo') : base_url() . 'dist/img/user9-128x128.jpg'; ?>" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $this->session->userdata('name'); ?></p>

                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">MENU PRINCIPAL</li>

                    <li><a href="<?php echo base_url(); ?>main/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                    <?php foreach ($this->session->userdata('menu_list') as $menu) : ?>
                        <?php if ($menu->menuname == 'Contabilidad' && $this->session->userdata('identity') != 'admin@admin.com' && $this->session->userdata('identity') != 'csandoval@aurbana.cl') {
                            $show_menu = false;
                        } else {
                            if ($menu->menuleaf == 0 && $menu->cant_visible > 0) {
                                $show_menu = true;
                                $menuhref = "#";
                            } else if ($menu->menuleaf == 0 && $menu->cant_visible == 0) {
                                $show_menu = false;
                            } else {
                                $show_menu = true;
                                $menuhref = $menu->app[0]->appfunction;
                            }
                        }

                        ?>
                        <?php //$menuhref = $menu->menuleaf == 0 ? "#" : $menu->app[0]->appfunction;
                        ?>

                        <?php if ($show_menu) { ?>
                            <?php $angle_left = $menu->menuleaf == 0 ? "fa-angle-left" : ""; ?>
                            <li class="treeview">
                                <a href="<?php echo base_url() . $menuhref; ?>">
                                    <i class="fa <?php echo $menu->menuimg; ?>"></i>
                                    <span><?php echo $menu->menuname; ?></span>
                                    <i class="fa <?php echo $angle_left; ?> pull-right"></i>
                                </a>
                                <?php if ($menu->menuleaf == 0) : ?>
                                    <ul class="treeview-menu">
                                        <?php foreach ($menu->app as $app) : ?>
                                            <?php if ($app->appvisible == 1) : ?>
                                                <?php /*if($app->appname == 'Fondos' && $this->session->userdata('identity') != 'admin@admin.com' && $this->session->userdata('identity') != 'csandoval@aurbana.cl'){ 
                                                            $show_submenu = false;
                                                        }else{
                                                            $show_submenu = true;
                                                        }*/
                                                        $show_submenu = true;

                                                         ?>

                                                <?php if($show_submenu){ ?>
                                                <li><a href="<?php echo base_url() . $app->appfunction; ?>"><i class="fa fa-circle-o"></i><?php echo $app->appname; ?></a></li>
                                                <?php } ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php } ?>
                    <?php endforeach; ?>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo $content_menu['title']; ?>
                    <small><?php echo $content_menu['subtitle']; ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <!--li><a href="#">Dashboard</a></li-->
                    <li class="active"><?php echo $content_menu['menu']; ?></li>
                </ol>
            </section>
            <?php $this->load->view($content_view); ?>
        </div><!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2014-<?php echo date("Y"); ?> <a href="http://www.tugastocomun.cl">Tu Gasto Com&uacute;n</a>.</strong> Todos los derechos reservados.
        </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->

    <!-- Bootstrap 3.3.2 JS -->


    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?php echo base_url(); ?>plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>dist/js/app.min.js" type="text/javascript"></script>




    <!--script>
/*
$(document).userTimeout({
  // ULR to redirect to, to log user out
  logouturl: "<?php echo base_url(); ?>auth/logout",
  // URL Referer - false, auto or a passed URL
  referer: false,
  // Name of the passed referal in the URL
  refererName: 'refer',
  // Toggle for notification of session ending
  notify: true,
  // Toggle for enabling the countdown timer
  timer: true,
  // 10 Minutes in Milliseconds, then notification of logout
  session: 600000,
  // 5 Minutes in Milliseconds, then logout
  force: 60000,
  // Model Dialog selector (auto, bootstrap, jqueryui)
  ui: 'auto',
  // Shows alerts
  debug: false,
  // <a href="http://www.jqueryscript.net/tags.php?/Modal/">Modal</a> Title
  modalTitle: 'Se ha detectado inactividad',
  // Modal Body
  modalBody: 'Tu sessión está por terminar debido a inactividad. Favor indica que deseas hacer.'

});*/

/*
$(document).ready(function(){
function update() {
  $('#clock').html(moment().format('D MMMM YYYY HH:mm:ss'));
}

setInterval(update, 1000);

});*/
    </script-->
</body>

</html>
