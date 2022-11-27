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
          <br>
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Cuenta</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_cuenta" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="proveedor">Proveedor</label>    
                              <select name="proveedor" id="proveedor"  class="form-control" >
                                  <option value="">Seleccione un proveedor</option>
                                  <?php foreach ($proveedores as $proveedor) { ?>
                                      <option value="<?php echo $proveedor->id;?>" ><?php echo $proveedor->nombre;?></option>
                                  <?php } ?>
                              </select>
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="tipodoc">Tipo Documento Tributario</label>    
                              <select name="tipodoc" id="tipodoc"  class="form-control" >
                                  <option value="">Seleccione un Tipo de Documento</option>
                                  <?php foreach ($tipodoc as $documento) { ?>
                                      <option value="<?php echo $documento->id;?>" ><?php echo $documento->nombre;?></option>
                                  <?php } ?>
                              </select>
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="documento">Nro. Documento</label>    
                              <input type="text" class="form-control" name="documento" id="documento" placeholder="Nro. de Documento">
                        </div>   
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Documento</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="fecdocumento" id="fecdocumento" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/aaaa">
                                     
                                 </div>
                        </div> 
                      </div>  
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="concepto">Concepto</label>      
                              <?php $label_concepto = ""; ?>
                              <select name="concepto" id="concepto"  class="form-control"  >
                                  <option value="">Seleccione un concepto</option>
                                  <?php foreach ($conceptos as $concepto) { ?>
                                      <?php if($concepto->idpadre != $label_concepto){
                                              if($label_concepto != ''){
                                                  echo "</optgroup>";
                                              }
                                              echo "<optgroup label='". $concepto->nombrepadre . "''>";
                                              $label_concepto = $concepto->idpadre;
                                      } ?>
                                      <?php if(!($concepto->idpadre == '' && $concepto->hijos > 0)){ ?>
                                        <option value="<?php echo $concepto->id;?>" ><?php echo $concepto->nombre;?></option>
                                      <?php } ?>
                                  <?php } 
                                        if($label_concepto != ''){
                                          echo "</optgroup>";
                                        }
                                        ?>                                
                              </select>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Monto">
                              </div>
                        </div>   
                      </div>
                    </div>



                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="formapago">Cuotas</label>    
                              <br><input type="radio" name="cuotas" id="cc" class="minimal"  value='cc'  />&nbsp;
                              <label for="tc">Si</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="cuotas" id="sc" class="minimal"  value='sc' checked/>&nbsp;
                              <label for="tc">No</label>                          
                         
                        </div>
                      </div>
                      <div class='col-md-6'>


                        <div class="form-group">
                              <label for="formapago">N&uacute;mero Cuotas</label>    
                              <select name="numcuotas" id="numcuotas"  class="form-control" disabled >
                                  <option value="">Seleccione N&uacute;mero de Cuotas</option>
                                  <?php for($i=2;$i<=18;$i++){ ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                  <?php } ?>
                              </select>
                        </div>   
                      </div>
                    </div>                    

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto Cuota Aprox.</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" name="montocuota" id="montocuota" placeholder="Monto Cuota" readonly="">
                              </div>
                        </div>   
                      </div>


                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fecvencimiento">Fecha Vencimiento</label>
                              <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                              
                                <input class="form-control" size="16" type="text" readonly name="fecvencimiento" id="fecvencimiento" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/aaaa">

                              </div>
                        </div>   
                      </div>


                    </div> 

                    <div class='row'>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="formapago">Forma de Cobro</label>    
                              <select name="formapago" id="formapago"  class="form-control" >
                                  <option value="">Seleccione una Forma de Pago</option>
                                  <option value="gc">Gasto Com&uacute;n</option>
                                  <option value="fr">Fondo de Reserva</option>
                                  <option value="ci">Cobro por Lectura Individual</option>
                                  <option value="sc">Sin Cobro</option>
                                  <option value="af">Activo Fijo</option>
                              </select>
                        </div>
                      </div>                       

                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Descripci&oacute;n</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                        </div>  
                      </div>                                         

                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Adjuntar Comprobante</label>
                                  <input type="file" id="userfile" name="userfile">
                                </div>
                        </div>  
                      </div>
                    </div>

                    <input type="hidden" name="idcuenta" id="idcuenta" value="0" >

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>accounts/editar_cuenta" class="btn btn-default">Volver</a>                    
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
        excluded: ':disabled',
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
            tipodoc: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo de documento es requerido'
                    }
                }
            },            
            documento: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Documento es requerido'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Nro. Documento s&oacute;lo puede contener n&uacute;meros'
                    },
                    // The bank validator doesn't have any option
                    blank: {}                                    

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
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                    /*regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    } */                   

                }
            },

            cuotas: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Cuotas es requerido'
                    }
                }
            },   


            numcuotas: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'N&uacute;mero de cuotas es requerido'
                    }
                }
            },   

            formapago: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Forma de Pago es requerida'
                    },
                    remote: {
                        url: '<?php echo base_url();?>accounts/cuenta_servicios_basicos/',
                        // Send { email: 'its value', username: 'its value' } to the back-end
                        data: function(validator, $field, value) {
                            return {
                                concepto: $('#concepto').val(),
                                formapago: $('#formapago').val(),
                            };
                        },
                        message: 'Cobro por Lectura Individual s&oacute;lo debe asociarse a servicios b&aacute;sicos',
                        type: 'POST'
                    },
                    callback: {
                        message: 'Cuenta en cuotas s&oacutelo se puede asociar a Gasto Com&uacute;n',
                        callback: function (value, validator, $field) {
                            if($("#cc").is(':checked') && $('#formapago').val() != 'gc'){
                              return  {
                                    valid: false,
                                    message: 'Cuenta en cuotas s&oacutelo se puede asociar a Gasto Com&uacute;n',
                                }
                            }else{
                              return true;
                            }
                        }
                    } 

                }
            },   

            descripcion: {
                row: '.form-group',
                validators: {
                    stringLength: {
                        min: 0,
                        max: 150,
                        message: 'Descripci&oacute;n puede tener hasta 150 caracteres'
                    }
                }
            },                      
        }
    })
    .on('success.form.fv', function(e) { /**** VALIDAR EN SERVIDOR VIA AJAX ******/
        // Prevent default form submission
        e.preventDefault();

        var $form = $(e.target),                    // The form instance
            fv    = $form.data('formValidation');   // FormValidation instance

        // Send data to back-end
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>accounts/validate_cuenta',
            data: $form.serialize(),
            dataType: 'json'
        }).success(function(response) {
            // We will display the messages from server if they're available

            // If there is error returned from server

              if (response.result === 'error') {
                  //console.log(response.fields);
                  for (var field in response.fields) {

                      fv
                          // Show the custom message
                          .updateMessage(field, 'blank', response.fields[field])
                          // Set the field as invalid
                          .updateStatus(field, 'INVALID', 'blank');
                  }
              } else {
                  // Do whatever you want here
                  // such as showing a modal ...
                  fv.defaultSubmit();
              }            
        });
        
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true});          

});

  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })  


  $('#concepto').on('change',function(){
    $('#basicBootstrapForm').formValidation('updateStatus', 'formapago','NOT_VALIDATED');
  })

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });  


$("#sc").on('ifChecked',function(event){
   $('#basicBootstrapForm').formValidation('updateStatus', 'numcuotas','NOT_VALIDATED'); 
   $('#basicBootstrapForm').formValidation('updateStatus', 'formapago','NOT_VALIDATED'); 
   $("#numcuotas").val('');  
   $("#numcuotas").attr('disabled',true);  
   //var monto = $('#monto').val();
   calcula_cuota();

});


$("#cc").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'formapago','NOT_VALIDATED'); 
  $("#numcuotas").attr('disabled',false);  
  calcula_cuota();

});  

 $('#numcuotas').on('change',function(){
    calcula_cuota();
  })


$('#monto').on('input',function(){
    calcula_cuota();
  })


function calcula_cuota(){

  if($("#sc").is(':checked') && $('#monto').val() != ""){
    monto = parseInt(replaceAll($('#monto').val(),".",""));
    $("#montocuota").val(number_format(monto,0,".","."));
  }else if($("#cc").is(':checked') && $("#numcuotas").val() != ""  && $('#monto').val() != ""){
    //var monto = $('#monto').val();
    monto = parseInt(replaceAll($('#monto').val(),".",""));
    montocuota = parseInt(monto/parseInt($("#numcuotas").val()));
    $("#montocuota").val(number_format(montocuota,0,".","."));
  }else{
    if($('#monto').val() != ""){
      monto = parseInt(replaceAll($('#monto').val(),".",""));
      $("#montocuota").val(number_format(monto,0,".","."));
    }else{
      $("#montocuota").val('');  
    }
    
  }

}    
</script>  