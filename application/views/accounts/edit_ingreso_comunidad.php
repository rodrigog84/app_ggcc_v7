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
                  <h3 class="box-title">Agregar Ingreso Comunidad</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_ingreso" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="proveedor">Proveedor</label>    
                              <select name="proveedor" id="proveedor"  class="form-control" >
                                  <option value="">Seleccione un proveedor</option>
                                  <?php foreach ($proveedores as $proveedor) { ?>
                                      <?php $proveedorselected = $proveedor->id == $datos_form['proveedor'] ? "selected" : ""; ?>
                                      <option value="<?php echo $proveedor->id;?>" <?php echo $proveedorselected;?>><?php echo $proveedor->nombre;?></option>
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
                                      <?php $documentoselected = $documento->id == $datos_form['tipodoc'] ? "selected" : ""; ?>
                                      <option value="<?php echo $documento->id;?>" <?php echo $documentoselected;?> ><?php echo $documento->nombre;?></option>
                                  <?php } ?>
                              </select>
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="documento">Nro. Documento</label>    
                              <input type="text" class="form-control" name="documento" id="documento" placeholder="Nro. de Documento" value="<?php echo $datos_form['documento']; ?>">
                        </div>   
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Documento</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="fecdocumento" id="fecdocumento" value="<?php echo $datos_form['fecdocumento']; ?>" placeholder="dd/mm/aaaa">
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
                                      <?php $conceptoselected = $concepto->id == $datos_form['concepto'] ? "selected" : ""; ?>
                                        <option value="<?php echo $concepto->id;?>" <?php echo $conceptoselected;?> ><?php echo $concepto->nombre;?></option>
                                  <?php } ?>                                
                              </select>
                            </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Monto" value="<?php echo $datos_form['monto']; ?>" >
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
                                <input class="form-control" size="16" type="text" readonly name="fecvencimiento" id="fecvencimiento" value="<?php echo $datos_form['fecvencimiento']; ?>" placeholder="dd/mm/aaaa">

                              </div>
                        </div>   
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <?php if($datos_form['tipoingreso'] == 'cc'){ 
                                $cc_selected = 'selected';
                                $fr_selected = '';
                                $na_selected = '';
                                }else if($datos_form['tipoingreso'] == 'fr'){
                                $cc_selected = '';
                                $fr_selected = 'selected';
                                $na_selected = '';
                                }else if($datos_form['tipoingreso'] == 'na'){
                                $cc_selected = '';
                                $fr_selected = '';
                                $na_selected = 'selected';
                                } ?>                        

                              <label for="formapago">Tipo de Ingreso</label>    
                              <select name="tipoingreso" id="tipoingreso"  class="form-control" >
                                  <option value="">Seleccione un Tipo de Ingreso</option>
                                  <option value="cc" <?php echo $cc_selected;?>>Cuenta Corriente</option>
                                  <option value="fr" <?php echo $fr_selected;?>>Fondo de Reserva</option>                                  
                                  <option value="na" <?php echo $na_selected;?>>No Afecta Banco</option>                                  
                              </select>
                        </div>
                      </div>   

                    </div> 

                    <div class='row'>

                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Descripci&oacute;n</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion" ><?php echo $datos_form['descripcion']; ?></textarea>
                        </div>  
                      </div>                                         
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="monto">Habilitar para Gasto Com&uacute;n</label> <br>
                          &nbsp;&nbsp;<input type="checkbox" name="habilitagasto" id="habilitagasto" class="minimal" <?php echo $datos_form['habilitagasto'] == 1 ? "checked" : ""; ?>/>   
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
                      <div class='col-md-6'>
                          <?php if($datos_form['nombrearchivo'] != ''){ ?>
                        <center><a href="<?php echo base_url(); ?>uploads/ingresos/<?php echo $this->session->userdata('comunidadid')."/".$datos_form['nombrearchivo'];?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><h4 class="glyphicon glyphicon-paperclip"></h4></a></center>
                          <?php } ?>
                      </div>                      
                      <input type="hidden" name="idingreso" id="idingreso" value="<?php echo $datos_form['idingreso']; ?>" >  
                    </div>

                    

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Editar</button>
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
            tipoingreso: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo de Ingreso es requerido'
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
            url: '<?php echo base_url();?>accounts/validate_ingreso',
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
</script>  
<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

</script>