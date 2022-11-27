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
          <div class="alert alert-warning" role="alert">
            Se les recuerda que a partir del 01/01/2021, los pagos de honorarios est&aacute;n sujetos a una retenci&oacute;n del 11,5%
          </div>
          <br>
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Boleta Honorarios</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_honorarios_condominio" method="post" role="form" enctype="multipart/form-data">
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
                              <label for="documento">Nro. Boleta</label>    
                              <input type="text" class="form-control" name="documento" id="documento" placeholder="Nro. de Documento">
                        </div>   
                      </div>   
                    </div>                 

                    <div class='row'>

                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Boleta</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="fecdocumento" id="fecdocumento" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/aaaa">
                                     
                                 </div>
                        </div> 
                      </div> 
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


                    </div>

                    <div class='row'>
                      

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto Bruto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="montobruto" id="montobruto" placeholder="Monto">
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="formapago">Tipo Retenci&oacute;n</label>    
                              <br><input type="radio" name="retencion" id="cr" class="minimal"  value='cr' checked />&nbsp;
                              <label for="tc">Con Retenci&oacute;n</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="retencion" id="sr" class="minimal"  value='sr'/>&nbsp;
                              <label for="tc">Sin Retenci&oacute;n</label>                          
                         
                        </div>
                      </div>                      
                    </div>


                    <div class='row'>
                      

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto Retenido</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="montoretencion" id="montoretencion" placeholder="Monto" readonly>
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto L&iacute;quido</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="montoliquido" id="montoliquido" placeholder="Monto L&iacute;quido" readonly>
                              </div>
                        </div>   
                      </div>                      
                    </div>


                    <div class='row'>

                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fecvencimiento">Fecha Vencimiento</label>
                              <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                              
                                <input class="form-control" size="16" type="text" readonly name="fecvencimiento" id="fecvencimiento" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/aaaa">

                              </div>
                        </div>   
                      </div>                      
                      
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="formapago">Forma de Cobro</label>    
                              <select name="formapago" id="formapago"  class="form-control" >
                                  <option value="">Seleccione una Forma de Pago</option>
                                  <option value="gc">Gasto Com&uacute;n</option>
                                  <option value="fr">Fondo de Reserva</option>
                                  <!--option value="ci">Cobro por Lectura Individual</option-->
                                  <option value="sc">Sin Cobro</option>
                                  <option value="af">Activo Fijo</option>
                              </select>
                        </div>
                      </div>   



                    </div> 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Descripci&oacute;n</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                        </div>  
                      </div>  
                                       
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
                    <input type="hidden" name="idretencion" id="idretencion" value="0" >
                    <input type="hidden" name="tipodoc" id="tipodoc" value="15" >
                    <input type="hidden" name="tiporetencion" id="tiporetencion" value="cr" >                    

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

    $("#fecdocumento").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        weekStart: true,
        startView: 2,
        minView: 2,
        forceParse: 0,
        language:  'es',     
       }) ;


        $("#fecvencimiento").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        weekStart: true,
        startView: 2,
        minView: 2,
        forceParse: 0,
        language:  'es',     
       }) ;


$('#fecdocumento').on('changeDate', function (e) {
    calcula_liquido();
});

// $('#fecdocumento').on('dp.change', function(e){ console.log("asdasda"); })

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
            montobruto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto Bruto es requerido'
                    },
                    /*regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    } */                   

                }
            },
            formapago: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Forma de Pago es requerida'
                    },
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


      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });



  $('#concepto').on('change',function(){
    $('#basicBootstrapForm').formValidation('updateStatus', 'formapago','NOT_VALIDATED');
  })


  $('#montobruto').on('input',function(){
    calcula_liquido();
  })


$("#cr").on('ifChecked',function(event){
   $("#tiporetencion").val('cr'); 
   calcula_liquido();
});


$("#sr").on('ifChecked',function(event){
  $("#tiporetencion").val('sr');  
  calcula_liquido();
});

function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}



  function calcula_liquido(){

      var tiporetencion = $("#tiporetencion").val()
      var montobruto = parseInt(replaceAll($('#montobruto').val(),".",""));
      if(tiporetencion == 'cr'){
          var fecdocumento = $('#fecdocumento').val();
          var array_fec_documento = fecdocumento.split('/');
         
          var f1 = new Date(array_fec_documento[2], array_fec_documento[1], array_fec_documento[0]); //31 de diciembre de 2015
          var f2 = new Date(2020, 1, 1);
          var f3 = new Date(2021, 1, 1);
          var f4 = new Date(2022, 1, 1);
          var f5 = new Date(2023, 1, 1);
          var f6 = new Date(2024, 1, 1);
          var f7 = new Date(2025, 1, 1);
          var f8 = new Date(2026, 1, 1);
          var f9 = new Date(2027, 1, 1);
          var f10 = new Date(2028, 1, 1);

          if(f1 >= f2 && f1 < f3){ // 2020 
            var porcentaje = 0.1075;
          }else if (f1 >= f3 && f1 < f4){ // 2021 
            var porcentaje = 0.115;
          }else if (f1 >= f4 && f1 < f5){ // 2022
            var porcentaje = 0.1225;
          }else if (f1 >= f5 && f1 < f6){ // 2023
            var porcentaje = 0.13;
          }else if (f1 >= f6 && f1 < f7){ // 2024
            var porcentaje = 0.1375;
          }else if (f1 >= f7 && f1 < f8){ // 2025
            var porcentaje = 0.145;
          }else if (f1 >= f8 && f1 < f9){ // 2026
            var porcentaje = 0.1525;
          }else if (f1 >= f9 && f1 < f10){ // 2027
            var porcentaje = 0.16;
          }else if (f1 >= f10){ // 2028
            var porcentaje = 0.17;
          }

         
          var retencion = parseInt(montobruto*porcentaje);
      }else{
        var retencion = 0;
      }

      var montoliquido = montobruto - retencion;

      $('#montoretencion').val(number_format(retencion,0,'.','.'));
      $('#montoliquido').val(number_format(montoliquido,0,'.','.'))
  }
</script>  