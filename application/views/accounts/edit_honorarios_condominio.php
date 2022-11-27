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
                                      <?php $proveedorselected = $proveedor->id == $datos_form['proveedor'] ? "selected" : ""; ?>
                                      <option value="<?php echo $proveedor->id;?>" <?php echo $proveedorselected; ?>><?php echo $proveedor->nombre;?></option>
                                  <?php } ?>
                              </select>
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="documento">Nro. Boleta</label>    
                              <input type="text" class="form-control" name="documento" id="documento" placeholder="Nro. de Documento" value="<?php echo $datos_form['documento'];?>" >
                        </div>   
                      </div>   
                    </div>                 

                    <div class='row'>

                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Boleta</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="fecdocumento" id="fecdocumento" value="<?php echo $datos_form['fecdocumento'];?>" placeholder="dd/mm/aaaa">
                                     
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
                                      <?php $conceptoselected = $concepto->id == $datos_form['concepto'] ? "selected" : ""; ?>
                                      <?php if(!($concepto->idpadre == '' && $concepto->hijos > 0)){ ?>
                                        <option value="<?php echo $concepto->id;?>" <?php echo $conceptoselected;?>><?php echo $concepto->nombre;?></option>
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
                                <input type="text" class="form-control miles" name="montobruto" id="montobruto" placeholder="Monto" value="<?php echo number_format($datos_form['bruto'],0,".","."); ?>">
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="formapago">Tipo Retenci&oacute;n</label>    
                              <br><input type="radio" name="retencion" id="cr" class="minimal"  value='cr' <?php echo $datos_form['tiporetencion'] == 'cr' ? 'checked' : ''; ?> />&nbsp;
                              <label for="tc">Con Retenci&oacute;n</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="retencion" id="sr" class="minimal"  value='sr' <?php echo $datos_form['tiporetencion'] == 'sr' ? 'checked' : ''; ?>/>&nbsp;
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
                                <input type="text" class="form-control miles" name="montoretencion" id="montoretencion" placeholder="Monto" readonly value="<?php echo number_format($datos_form['retencion'],0,".","."); ?>">
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto L&iacute;quido</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="montoliquido" id="montoliquido" placeholder="Monto L&iacute;quido" readonly value="<?php echo number_format($datos_form['monto'],0,".","."); ?>">
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
                                <input class="form-control" size="16" type="text" readonly name="fecvencimiento" id="fecvencimiento" value="<?php echo $datos_form['fecvencimiento'];?>" placeholder="dd/mm/aaaa" >

                              </div>
                        </div>   
                      </div>                      
                      
                      <div class='col-md-6'>
                        <div class="form-group">

                            <?php if($datos_form['formapago'] == 'gc'){ 
                                $gc_selected = 'selected';
                                $fr_selected = '';
                                $sc_selected = '';
                                $af_selected = '';
                              }else if($datos_form['formapago'] == 'fr'){
                                $gc_selected = '';
                                $fr_selected = 'selected';
                                $sc_selected = '';
                                $af_selected = '';
                                }else if($datos_form['formapago'] == 'sc'){
                                $gc_selected = '';
                                $fr_selected = '';
                                $sc_selected = 'selected';
                                $af_selected = '';
                                }else if($datos_form['formapago'] == 'af'){
                                $gc_selected = '';
                                $fr_selected = '';
                                $sc_selected = '';
                                $af_selected = 'selected';

                                } ?>


                              <label for="formapago">Forma de Cobro</label>    
                              <select name="formapago" id="formapago"  class="form-control" >
                                  <option value="">Seleccione una Forma de Pago</option>
                                  <option value="gc" <?php echo $gc_selected;?>>Gasto Com&uacute;n</option>
                                  <option value="fr" <?php echo $fr_selected;?>>Fondo de Reserva</option>
                                  <!--option value="ci">Cobro por Lectura Individual</option-->
                                  <option value="sc" <?php echo $sc_selected;?>>Sin Cobro</option>
                                  <option value="af" <?php echo $af_selected;?>>Activo Fijo</option>
                              </select>
                        </div>
                      </div>   



                    </div> 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Descripci&oacute;n</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"><?php echo $datos_form['descripcion']; ?></textarea>
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
                    <?php if($datos_form['nombrearchivo'] != ''){ ?>
                    <div class='row'>
                      <div class='col-md-6'>
                        <center><a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$datos_form['nombrearchivo'];?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><h4 class="glyphicon glyphicon-paperclip"></h4></a></center>  
                      </div>  
                                       
                    </div>


                    <?php } ?>

                    <input type="hidden" name="idcuenta" id="idcuenta" value="<?php echo $datos_form['idcuenta']; ?>" >
                    <input type="hidden" name="idretencion" id="idretencion" value="<?php echo $datos_form['idretencion']; ?>" >
                    <input type="hidden" name="tipodoc" id="tipodoc" value="15" >
                    <input type="hidden" name="tiporetencion" id="tiporetencion" value="<?php echo $datos_form['tiporetencion']; ?>" >                    

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Editar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>accounts/honorarios_condominio" class="btn btn-default">Volver</a>                    
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
        var retencion = parseInt(montobruto*0.1);
      }else{
        var retencion = 0;
      }

      var montoliquido = montobruto - retencion;

      $('#montoretencion').val(number_format(retencion,0,'.','.'));
      $('#montoliquido').val(number_format(montoliquido,0,'.','.'))
  }
</script>  