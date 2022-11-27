<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Recuperar Contrase&ntilde;a</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="http://tugastocomun.cl/home/wp-content/uploads/2015/08/favicon.ico" />
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo">
        <a href="http://www.tugastocomun.cl"><img src="<?php echo base_url(); ?>img/logo4_1.png"></a>
      </div>
      <!-- User name -->
        <?php if($message): ?>
                <div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
                  <?php echo $message;?>
                </div>
        <?php endif; ?>      
      <div class="lockscreen-name">Olvid&eacute; mi Contrase&ntilde;a</div>

      <!-- START LOCK SCREEN ITEM -->
      <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
          <img src="<?php echo base_url(); ?>dist/img/user9-128x128.jpg" alt="user image"/>
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form action="<?php echo base_url();?>auth/forgot_password" method="post" class="lockscreen-credentials">
          <div class="input-group">
            <input type="text" name="email" id="email" class="form-control" placeholder="Email" />
            <div class="input-group-btn">
              <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
            </div>
          </div>
        </form><!-- /.lockscreen credentials -->

      </div><!-- /.lockscreen-item -->
      <div class="help-block text-center">
        Ingresa tu Email para restablecer tu contrase&ntilde;a
      </div>
      <div class='text-center'>
        <a href="<?php echo base_url(); ?>auth/login">O ingresa con un usuario diferente</a>
      </div>
      <div class='lockscreen-footer text-center'>
        Copyright &copy; 2014-2015 <b><a href="http://www.tugastocomun.cl" class='text-black'>Tu Gasto Com&uacute;n</a></b><br>
        Todos los derechos reservados
      </div>
    </div><!-- /.center -->

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>