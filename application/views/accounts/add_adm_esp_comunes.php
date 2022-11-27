        <!-- Main content -->
        <section class="content" >
        <?php if(isset($message)): ?>
         <div class="row">
          <div class="col-md-12">
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            </div>
          </div>
          <?php endif; ?>
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Cuenta</h3>  
                </div><!-- /.box-header -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_adm_esp_comunes" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="propiedad">Propiedad</label>    
                              <select name="propiedad" id="propiedad"  class="form-control" >
                                  <option value="">Seleccione un propiedad</option>
                                  <?php foreach ($propiedades as $propiedad) { ?>
                                      <option value="<?php echo $propiedad->id;?>" ><?php echo $propiedad->numero;?></option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="periodo">Per&iacute;odo Cargo</label>    
                              <select name="periodo" id="periodo"  class="form-control"  >
                                  <option value="">Seleccione un Per&iacute;odo</option>
                                  <?php foreach($datosperiodo as $periodo){ ?>
                                  <option value="<?php echo $periodo->id;?>"><?php echo date2string($periodo->mes,$periodo->anno);?></option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="concepto">Concepto</label>    
                              <select name="concepto" id="concepto"  class="form-control"  >
                                  <option value="">Seleccione un concepto</option>
                                  <?php foreach ($conceptos as $concepto) { ?>
                                      <option value="<?php echo $concepto->id;?>" data-umid="<?php echo $concepto->idumespcomun;?>" data-um="<?php echo $concepto->unidadmedida;?>" data-monto="<?php echo $concepto->monto;?>" ><?php echo $concepto->nombre;?></option>
                                  <?php } ?>                                
                              </select>
                            </div>
                          </div>
                          <div class='col-md-6'>
                            <div class="form-group">                      
                              <label for="fecuso">Fecha Uso</label>
                                <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                   <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                                
                                  <input class="form-control" size="16" type="text" readonly name="fecuso" id="fecuso" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/aaaa">

                                </div>
                            </div>
                          </div>
                        </div>


                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group"> 
                              <label for="monto">Unidad de Medida Uso</label>    
                              <input type="text" class="form-control" name="unidadmedidalb" id="unidadmedidalb" placeholder="Ingrese unidad de medida"  readonly>
                              <input type="hidden" name="unidadmedida" id="unidadmedida"  >
                              <input type="hidden" name="ummonto" id="ummonto"  >
                            </div>
                          </div>   
                          <div class='col-md-6'>
                            <div class="form-group"> 
                              <label for="descripcion">Cantidad Uso</label>    
                              <input type="text" class="form-control numeros" name="cantidadum" id="cantidadum" placeholder="Ingrese cantidad" disabled >
                            </div>
                          </div>    
                        </div>                        


                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group"> 
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Monto" readonly>
                              </div>
                            </div>
                          </div>   
                          <div class='col-md-6'>
                            <div class="form-group"> 
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                            </div>
                          </div>    
                        </div>

                        <div class="row">
                          <div class='col-md-6'>  
                            <div class="form-group">                                           
                              <label for="exampleInputFile">Adjuntar Comprobante</label>
                              <input type="file" id="userfile" name="userfile">
                            </div>
                          </div>
                        </div>

                 </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>accounts/editar_adm_esp_comunes" class="btn btn-default">Volver</a>                                         
                  </div>
                </form>
                </div><!-- /.box-body -->                


              </div><!-- /.box -->

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
            propiedad: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Propiedad es requerida'
                    }
                }
            },
            periodo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Per&iacute;odo es requerido'
                    },
                }
            },
            concepto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Concepto es requerido'
                    }                  
                }
            },
            unidadmedida: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Unidad de medida es requerida'
                    }                  
                }
            },  
            cantidadum: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Cantidad uso es requerido'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Cantidad uso s&oacute;lo puede contener n&uacute;meros'
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
                    }*/                    

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

  $('.numeros').keypress(function(event){
    if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 46){
      event.preventDefault();
    } 
  })   


  $('#concepto').change(function(){
    if($(this).val() != ''){
      var idconcepto = $(this).val();
      $('#unidadmedidalb').val($('#concepto option[value="' + idconcepto + '"]').data('um') + " ($ " + number_format($('#concepto option[value="' + idconcepto + '"]').data('monto'),0,'.','.')  + ")");
      $('#unidadmedida').val($('#concepto option[value="' + idconcepto + '"]').data('umid'));
      $('#ummonto').val($('#concepto option[value="' + idconcepto + '"]').data('monto'));
      $('#cantidadum').attr('disabled',false);
      calcula_monto();
    }else{
      $('#cantidadum').val('');
      $('#monto').val('');
      $('#unidadmedidalb').val('');
      $('#unidadmedida').val('');
      $('#ummonto').val(0);
      $('#cantidadum').attr('disabled',true);
    }
  })


  $('#cantidadum').on('input',function(event){
    calcula_monto();
  })


  function calcula_monto(){
    var montoum = $('#ummonto').val();
    var cantidad = $('#cantidadum') .val();
    var montoesp= number_format(parseInt(montoum*cantidad),0,'.','.');
    $('#monto').val(montoesp);

  }


</script>  