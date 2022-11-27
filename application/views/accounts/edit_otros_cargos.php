        <!-- Main content -->
        <section class="content" ng-controller="add_cuentaController">
       <?php if(isset($message)): ?>
         <div class="row">
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
          </div>
          <br>          
        <?php endif; ?>
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Cuenta</h3>  
                </div><!-- /.box-header -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_otros_cargos" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="proveedor">Proveedor</label>    
                              <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder="Proveedor" value="<?php echo $datos_form['proveedor']; ?>">
                            </div>
                          </div>
                          <div class="col-md-6">                      
                            <div class="form-group">
                              <label for="fecdocumento">Fecha Pago</label>
                                <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                   <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                                
                                  <input class="form-control" size="16" type="text" readonly name="fecpago" id="fecpago" value="<?php echo $datos_form['fecpago']; ?>" placeholder="dd/mm/aaaa">
                                </div>
                            </div>                              
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Monto" value="<?php echo $datos_form['monto']; ?>" <?php echo $datos_form['abonado'] > 0 ? 'readonly' : ''; ?>>
                              </div>
                            </div>
                          </div>   
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"><?php echo $datos_form['descripcion']; ?></textarea>
                            </div>
                          </div>    
                        </div>

                        <div class="row">
                          <div class="col-md-6">                                           
                            <div class="form-group">
                              <label for="exampleInputFile">Adjuntar Comprobante</label>
                              <input type="file" id="userfile" name="userfile">
                            </div>
                          </div>

                          <div class='col-md-6'>
                              <?php if($datos_form['nombrearchivo'] != ''){ ?>
                            <center><a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$datos_form['nombrearchivo'];?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><h4 class="glyphicon glyphicon-paperclip"></h4></a></center>
                              <?php } ?>
                          </div>
                          <input type="hidden" name="idcargo" id="idcargo" value="<?php echo $datos_form['idcargo']; ?>" >
                       </div>

                 </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-success" <?php if (!$existe){ echo "disabled";} ?>>Agregar</button>
                    &nbsp;&nbsp;
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->
            </div>
          </div>
        </section><!-- /.content -->


  <script>

    $(".form_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        weekStart: true,
        startView: 2,
        minView: 2,
        forceParse: 0,
        language:  'es',     
    });

  </script>

<script>
$(document).ready(function() {
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            proveedor: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Proveedor es requerido'
                    }
                }
            },
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                   /* regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    }     */               

                }
            },
        }
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true}); 

});


  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })         
</script>  