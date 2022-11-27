<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Tu Gasto Com&uacute;n | Selecci&oacute;n</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="http://tugastocomun.cl/home/wp-content/uploads/2015/08/favicon.ico" />
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-purple sidebar-collapse layout-boxed ">
    <!-- Site wrapper -->
    <div class="wrapper">
      
      <header class="main-header">
        <a href="#" class="logo"><img src="<?php echo base_url(); ?>img/logo4_1_80p.png"></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $this->session->userdata('photo') != '' ? base_url().'dist/img/'.$this->session->userdata('photo') : base_url().'dist/img/user9-128x128.jpg'; ?>" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo $this->session->userdata('name'); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo base_url(); ?>dist/img/user9-128x128.jpg" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo  $this->session->userdata('name'); ?> - <?php echo $this->session->userdata('level_name'); ?>
                      <small>Miembro desde <?php echo $this->session->userdata('level_name');?></small>
                    </p>
                  </li>
                 <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Perfil</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url();?>auth/logout" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>


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
        <strong>Copyright &copy; 2014-2015 <a href="http://www.tugastocomun.cl">Tu Gasto Com&uacute;n</a>.</strong> Todos los derechos reservados.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?php echo base_url(); ?>plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>dist/js/app.min.js" type="text/javascript"></script>
  </body>
</html>