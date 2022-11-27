<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Iniciar Sesi&oacute;n</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="http://tugastocomun.cl/home/wp-content/uploads/2015/08/favicon.ico" />
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="http://www.tugastocomun.cl"><img src="<?php echo base_url(); ?>img/logo4_1.png"></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Iniciar Sesi&oacute;n</p>
          <?php if($message): ?>
                  <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
                    <?php echo $message;?>
                  </div>
          <?php endif; ?>
        <form action="<?php echo base_url();?>auth/login" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="identity" id="identity" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" id="password"  placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember" id="remember" value="1"> Recordarme
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
            </div><!-- /.col -->
          </div>
        </form>
        <!-- email/usuario: admin@admin.com password: password  -->
        <a href="forgot_password">Olvid&eacute; mi Password</a><br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>