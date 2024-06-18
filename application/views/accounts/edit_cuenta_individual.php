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
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_cuenta_individual" method="post" role="form" enctype="multipart/form-data">
                <div class="box-body">
                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="concepto">Concepto</label>    
                              <select name="concepto" id="concepto"  class="form-control"  >
                                  <option value="">Seleccione un concepto</option>
                                  <?php foreach ($conceptos as $concepto) { ?>
                                  		<?php $conceptoselected = $concepto->tipo_concepto.'-'.$concepto->id == $datos_form['concepto'] ? "selected" : ""; ?>
                                      <option value="<?php echo $concepto->tipo_concepto.'-'.$concepto->id;?>" <?php echo $conceptoselected;?> ><?php echo $concepto->nombre;?></option>
                                  <?php } ?>                                
                              </select>
                            </div>
                          </div>                        
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="propiedad">Propiedad</label>    
                              <select name="propiedad" id="propiedad" multiple="multiple"  class="form-control" >
                                  <!--option value="">Seleccione una propiedad</option-->
                                  <?php foreach ($propiedades as $propiedad) { ?>
                                  	  <?php $propiedadselected = $propiedad->id == $datos_form['propiedad'] ? "selected" : ""; ?>
                                      <option value="<?php echo $propiedad->id;?>" <?php echo $propiedadselected;?> ><?php echo $propiedad->numero;?></option>
                                  <?php } ?>
                              </select>
                              <input type="hidden" name="select" id="select" value="<?php echo $datos_form['propiedad'].','; ?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="periodo">Per&iacute;odo Cargo</label>    
                              <select name="periodo" id="periodo"  class="form-control"  >
                                  <option value="">Seleccione un Per&iacute;odo</option>
                                  <?php foreach($datosperiodo as $periodo){ ?>
                                  <?php $periodoselected = $periodo->id == $datos_form['periodo'] ? "selected" : ""; ?>
                                  <option value="<?php echo $periodo->id;?>" <?php echo $periodoselected;?>><?php echo date2string($periodo->mes,$periodo->anno);?></option>
                                  <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class='col-md-6'> 
                              <div class="form-group">                    
                              <label for="fecha">Fecha</label>
                                <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                   <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                                
                                  <input class="form-control" size="16" type="text" readonly name="fecha" id="fecha" value="<?php echo $datos_form['fechadeuda']; ?>" placeholder="dd/mm/aaaa">
                                </div>
                              </div>
                          </div>
                        </div>    


                        <div class="row">
                          <div class='col-md-6'> 
                            <div class="form-group"> 
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto"  value="<?php echo $datos_form['monto']; ?>" placeholder="Monto">
                              </div>
                            </div>
                          </div>   
                          <div class='col-md-6'> 
                            <div class="form-group"> 
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"><?php echo $datos_form['descripcion']; ?></textarea>
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
	                      <div class='col-md-6'>
	                          <?php if($datos_form['nombrearchivo'] != ''){ ?>
	                        <center><a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$datos_form['nombrearchivo'];?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><h4 class="glyphicon glyphicon-paperclip"></h4></a></center>
	                          <?php } ?>
	                      </div>  
                         <input type="hidden" name="idcuenta" id="idcuenta" value="<?php echo $datos_form['idcuenta']; ?>" >                         
                        </div>

                 </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Editar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>accounts/editar_individual" class="btn btn-default">Volver</a>                     
                  </div>
                </form>
                </div><!-- /.box-body -->                


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

	// codigo por defecto para no permitir marcar más de un caso
	rebuildMultiselect(firstConfigurationSet);


	  var selectedOptions = $('#propiedad option:selected');
	  if (selectedOptions.length >= 1) {

	    // Disable all other checkboxes.
	    var nonSelectedOptions = $('#propiedad option').filter(function() {
	      return !$(this).is(':selected');
	    });

	    var dropdown = $('#propiedad').siblings('.multiselect-container');
	    nonSelectedOptions.each(function() {
	      var input = $('input[value="' + $(this).val() + '"]');

	      input.prop('disabled', true);
	      input.parent('li').addClass('disabled');


	    });
	  }
	  else {


	    // Enable all checkboxes.
	    var dropdown = $('#propiedad').siblings('.multiselect-container');

	    $('#propiedad option').each(function() {

	      var input = $('input[value="' + $(this).val() + '"]');
	      input.prop('disabled', false);
	      input.parent('li').addClass('disabled');
	    });
	    

	  }

    $('#monto').attr('disabled',false);

    $('#basicBootstrapForm')
          .find('[name="propiedad"]')
            .multiselect({
                // Re-validate the multiselect field when it is changed
                onChange: function(element, checked) {
                    $('#basicBootstrapForm').formValidation('revalidateField', 'propiedad');
                },
            })
          .end()
          .formValidation({
              framework: 'bootstrap',
              excluded: ':disabled',
              icon: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
              },
              fields: {
                  concepto: {
                      row: '.form-group',
                      validators: {
                          notEmpty: {
                              message: 'Concepto es requerido'
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

                  monto: {
                      row: '.form-group',
                      validators: {
                          notEmpty: {
                              message: 'Monto es requerido'
                          },
                         /* regexp: {
                              regexp: /^[0-9]+$/,
                              message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                          }       */             

                      }
                  },
                  propiedad: {
                      validators: {
                          notEmpty: {
                              message: 'Propiedad es requerida'
                          }
                      }
                  }                  
              }
          })
         .find('.miles').mask('000.000.000.000.000', {reverse: true}); 
    /*.on('success.form.fv', function(e) { 
        // Prevent default form submission
        e.preventDefault();

        .updateMessage(field, 'blank', response.fields[field])
                          // Set the field as invalid
        .updateStatus(field, 'INVALID', 'blank');

        fv.defaultSubmit();
    });  */         

});


  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })  
          
</script>  

<script>

$("#concepto").on('change',function(event){
  multiselect_deselectAll($("#propiedad"));
  /*if($(this).val() == 9){
    //alert("cuotas especiales");
   // $("#propiedad option[value='']").text("Todos");
    rebuildMultiselect(secondConfigurationSet);
  }else{

    //$("#propiedad option[value='']").text("Seleccione una propiedad");
    rebuildMultiselect(firstConfigurationSet);
  }*/


  rebuildMultiselect(firstConfigurationSet);
 // $('#basicBootstrapForm').formValidation('revalidateField', 'propiedad');

  $('#basicBootstrapForm').formValidation('updateStatus', 'propiedad','NOT_VALIDATED'); //quita validacion
});


  var firstConfigurationSet = {
      includeSelectAllOption: false,
      onChange: function(option, checked) {
              // Get selected options.
              $("#select").val('');
              str = "";
              if(checked){
                str += option[0].value+",";
              }
              $("#select").val(str);
              var selectedOptions = $('#propiedad option:selected');
       
              if (selectedOptions.length >= 1) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#propiedad option').filter(function() {
                  return !$(this).is(':selected');
                });
       
                var dropdown = $('#propiedad').siblings('.multiselect-container');
                nonSelectedOptions.each(function() {
                  var input = $('input[value="' + $(this).val() + '"]');
                  input.prop('disabled', true);
                  input.parent('li').addClass('disabled');
                });
              }
              else {
                // Enable all checkboxes.
                var dropdown = $('#propiedad').siblings('.multiselect-container');

                $('#propiedad option').each(function() {

                  var input = $('input[value="' + $(this).val() + '"]');
                  input.prop('disabled', false);
                  input.parent('li').addClass('disabled');
                });
                

              }

              $('#monto').attr('disabled',false);
            }
    };

    var secondConfigurationSet = {
      includeSelectAllOption: true,
      onChange: function(option, checked) {
              // Get selected options.
              $("#select").val('');
              var selectedOptions = $('#propiedad option:selected');
              // Enable all checkboxes.
              var dropdown = $('#propiedad').siblings('.multiselect-container');
              var str = "";
              $('#propiedad option').each(function() {
                var input = $('input[value="' + $(this).val() + '"]');
                input.prop('disabled', false);
                input.parent('li').addClass('disabled');
                if($(this).is(':selected')){
                  str += $(this).val()+",";
                }
              });
              $("#select").val(str);
              $('#monto').attr('disabled',false);

            }
    };

    $('#propiedad').multiselect(firstConfigurationSet); 


function rebuildMultiselect(options) {
      $('#propiedad').multiselect('setOptions', options);
      $('#propiedad').multiselect('rebuild');
    }

function multiselect_deselectAll($el) {
    $("#select").val('');    
    $('option', $el).each(function(element) {
      $el.multiselect('deselect', $(this).val());
    });
  }
        
      //  $('#propiedad').multipleSelect({width: '40%'});


</script>