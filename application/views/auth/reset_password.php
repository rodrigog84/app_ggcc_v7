<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Cambiar Contrase&ntilde;a</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="http://tugastocomun.cl/home/wp-content/uploads/2015/08/favicon.ico" />
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
 
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="http://www.tugastocomun.cl"><img src="<?php echo base_url(); ?>img/logo4_1.png"></a>
      </div>

      <div class="register-box-body">
        <p class="login-box-msg">Cambiar Contrase&ntilde;a</p>
        <form id="basicBootstrapForm" action="<?php echo base_url();?>auth/reset_password/<?php echo $code; ?>" method="post">
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="new" id="new"  placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="new_confirm" id="new_confirm" placeholder="Repetir password"/>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-4" >
              <button type="submit" class="btn btn-primary btn-block btn-flat">Actualizar</button>
            </div><!-- /.col -->
          </div>
          <?php echo form_input($user_id);?>
          <?php echo form_hidden($csrf); ?>          
        </form>        

      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>dist/js/formValidation.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>dist/js/framework/bootstrap.js"></script>       
  </body>
</html>
<script>

$(document).ready(function() {
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {

            new: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Clave nueva es requerida'
                    },
                    stringLength: {
                        min: 6,
                        max: 20,
                        message: 'La Password debe contener entre 6 y 20 caracteres'
                    }
                }
            },                           

            new_confirm: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Confirmaci&oacute;n de Clave nueva es requerido'
                    },
                    identical: {
                        field: 'new',
                        message: 'Password y su confirmaci&oacute;n no son iguales'
                    }                    
                }
            },
        }
    })
});
</script> 