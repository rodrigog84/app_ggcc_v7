
<!DOCTYPE html>
<html>

<!-- Start: Head -->
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Tu Gasto Com&uacute;n | Iniciar Sesi&oacute;n</title>
    <meta name="keywords" content="Tu Gasto Comun" />
    <meta name="description" content="Tu Gasto Comun">
    <meta name="author" content="Tu Gasto Comun">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font CSS (Via CDN) -->
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700'>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">

    <!-- Vendor CSS -->
    
    <!-- Theme CSS -->
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>dist/css/theme.css">
    <link href="<?php echo base_url(); ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    <!-- Admin Panels CSS -->
    
    <!-- Admin Forms CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>dist/css/admin-forms.css">
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Admin Modals CSS -->
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="http://tugastocomun.cl/home/wp-content/uploads/2015/08/favicon.ico">

    <!-- Flag CSS -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head><!-- End: Head -->

<body class="login-page external-page sb-l-c sb-r-c">

    <!-- Start: Main -->
    <div id="main" class="animated fadeIn">

        <!-- Start: Content-Wrapper -->
        <section id="content_wrapper">

            <!-- begin canvas animation bg -->
            <div id="canvas-wrapper">
                <canvas id="demo-canvas"></canvas>
            </div>

            <!-- Begin: Content -->
            <section id="content">

                <div class="admin-form theme-info" id="login1">

                    <div class="row mb15 table-layout">

                        <div class="col-xs-6 va-m pln">
                            <a href="http://www.tugastocomun.cl"><img src="<?php echo base_url(); ?>img/logo4_1.png"></a>                        </div>

                        <div class="col-xs-6 text-right va-b pr5">
                            <div class="login-links">
                                <a href="#" class="perfil active" id="prop"  title="Ingresar">Copropietario/Comit&eacute;</a>                                <span class="text-white"> | </span>
                                <a href="#" class="perfil " id="adm" title="Crear cuenta">Administrador</a>                            </div>

                        </div>

                    </div>

                    <div class="panel panel-info mt10 br-n">                        

                        <!-- end .form-header section -->
                        <form action="<?php echo base_url();?>auth/login" id="login-form" method="post" accept-charset="utf-8">
                            <div class="panel-body bg-light p30">
                                <div class="row">
                                    <div class="col-sm-5 pl30">
                                        <h3 class="mb25" style="margin-bottom:8px">Bienvenidos a Tu Gasto Com&uacute;n.</h3>
                                       <p class="mb15" style="margin-top: -20px;">Su plataforma de administraci&oacute;n de Gastos Comunes. Con nosotros ud podr&aacute;:</p>
                                       <p class="mb15"><span class="fa fa-check text-success pr5"></span>Verificar el estado de su deuda</p>
                                       <p class="mb15">
                                       <span class="fa fa-check text-success pr5"></span> Ver informaci&oacute;n Hist&oacute;rica</p>
                                       <p class="mb15"><span class="fa fa-check text-success pr5"></span> Visualizar situaci&oacute;n de su comunidad</p>                                    
                                       <p class="mb15"><span class="fa fa-info text-success pr5"></span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://tugastocomun.cl/home/wp-content/uploads/2015/06/Pasos_registro_comunidad.pdf" class="text-teal" target="_blank"><b>Manual Registro Comunidad</b></a></p> 
                                       </div>

                                    <div class="col-sm-7 br-l br-grey pr30">
                                        <div class="section">
                                            <label for="username" class="field-label text-muted fs18 mb10"><label for="identity">Email:</label></label>
                                            <label for="username" class="field prepend-icon">
                                                <input type="text" name="identity" value="" id="identity" class="gui-input" placeholder="Ingrese Email Copropietario/Comité/Administrador"  />
                                                <label for="username" class="field-icon"><i class="fa fa-envelope"></i>
                                                </label>
                                            </label>
                                        </div>
                                        <!-- end section -->
                                        <div class="section">
                                            <label for="username" class="field-label text-muted fs18 mb10"><label for="password">Contraseña:</label></label>
                                            <label for="password" class="field prepend-icon">
                                                <input type="password" name="password" value="" id="password" class="gui-input" placeholder="Ingrese Contraseña"  />
                                                
                                                <label for="password" class="field-icon"><i class="fa fa-lock"></i>
                                                </label>
                                            </label>
                                        </div>
                                        <!-- end section -->
                                        <div class="section">
                                            <a href="<?php echo base_url();?>auth/forgot_password">¿Has olvidado tu contraseña?</a><br><br>
                                            <a href="<?php echo base_url();?>guest/add_comunidad" class="text-success"><b>Registra tu comunidad y obten tu prueba gratuita</b></a>
                                                                              </div>
                                            <?php if($message): ?>
                                                  <div class="alert alert-danger alert-dismissable">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
                                                    <?php echo $message;?>
                                                  </div>
                                          <?php endif; ?>
                                    </div>
                                                                        
                                </div>
                            </div>
                            <!-- end .form-body section -->
                            <div class="panel-footer clearfix p10 ph15">
                                <input type="submit" name="submit" value="Ingresar"  class="button btn-primary mr10 pull-right" />
                                <label class="switch block switch-primary pull-left input-align mt10">
                                    <input type="checkbox" name="remember" value="1"  id="remember" />
                                    <label for="remember" data-on="YES" data-off="NO"></label>
                                    <span><label for="remember">Recuérdame</label></span>
                                </label>
                            </div>
                            <!-- end .form-footer section -->
                        </form>                        <!--/form-->
                    </div>
               </div>

            </section>
            <!-- End: Content -->

        </section>
        <!-- End: Content-Wrapper -->

    </div>

    

</body>

</html>
<script>
/*$(document).ready(function() {
        $('.perfil').on('click',function(){
                var sel_id = $(this).attr('id');
                if(sel_id == 'prop'){
                    $('#prop').addClass('active');
                    $('#adm').removeClass('active');
                    $('#identity').attr('placeholder','Ingrese Email Copropietario/Comité');
                }else if(sel_id == 'adm'){
                    $('#prop').removeClass('active');
                    $('#adm').addClass('active');
                    $('#identity').attr('placeholder','Ingrese Email Administrador');
                }

          
        })

});*/
</script>       