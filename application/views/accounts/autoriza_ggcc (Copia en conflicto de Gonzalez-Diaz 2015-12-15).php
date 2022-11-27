        <!-- Main content -->
        <section class="content" >

         <div class="row">
            <?php if(isset($message)): ?>
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            <?php endif; ?>
            <br>             
          </div>
          <br>
          <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_autoriza_ggcc" method="post">

          <div class="col-md-6">
            <div class="box box-primary ">
              <div class="box-body">
                        <div class="form-group">              
                          <label for="periodo">Per&iacute;odo Gasto Com&uacute;n</label>    
                          <select name="periodo" id="periodo"  class="form-control"  >
                              <option value="">Seleccione un Per&iacute;odo</option>
                              <?php foreach($datosperiodo as $periodo){ ?>
                              <option value="<?php echo $periodo->id;?>"><?php echo date2string($periodo->mes,$periodo->anno);?></option>
                              <?php } ?>
                          </select>             
                        </div>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div><!-- /.col (left) -->

          <div class="bottom-aligned-text col-md-2">
              <br><br><button type="submit" class="btn btn-primary">Autorizar Gasto Com&uacute;n</button>
          </div>

          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Cuentas sin Autorizar</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th width="20px"><input type="checkbox" class="minimal-green" id="cuentas_all" /></th>
                        <th>Proveedor</th>
                        <th>Tipo Documento Tributario</th>
                        <th>Num. Documento</th>
                        <th>Concepto</th>
                        <th>Fecha Documento</th>
                        <th>Monto</th>
                        <th>Fecha Vencimiento</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if((count($cuentas) + count($remuneraciones)) > 0 ){ ?>
                        <?php foreach ($cuentas as $cuenta) { ?>
                         <tr >
                          <td><input type="checkbox" class="minimal cuentas" name="cuenta-<?php echo $cuenta->id;?>" /></td>
                          <td><?php echo $cuenta->proveedor;?></td>
                          <td><?php echo $cuenta->tipodocumentotributario;?></td>
                          <td><?php echo $cuenta->nrodocumento;?></td>
                          <td><?php echo $cuenta->concepto;?></td>
                          <td><?php echo $cuenta->fecdocumento;?></td>
                          <td>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></td>
                          <td><?php echo $cuenta->fecvencimiento;?></td>
                          
                          <td>
                          <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;                      
                          <?php if($cuenta->nombrearchivo != ''){ ?>
                          <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Ver Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php } ?>
                          </td>
                          
                        </tr>
                        <?php } ?>

                        <?php foreach ($remuneraciones as $remuneracion) { ?>
                         <tr >
                          <td><input type="checkbox" class="minimal cuentas" name="cuenta-<?php echo $cuenta->id;?>" /></td>
                          <td><?php echo $remuneracion->proveedor;?></td>
                          <td><?php echo $remuneracion->tipodocumentotributario;?></td>
                          <td><?php echo $remuneracion->nrodocumento;?></td>
                          <td><?php echo $remuneracion->concepto;?></td>
                          <td><?php echo $remuneracion->fecdocumento;?></td>
                          <td>$&nbsp;<?php echo number_format($remuneracion->monto,0,".",".");?></td>
                          <td><?php echo $remuneracion->fecvencimiento;?></td>
                          
                          <td>
                          <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $remuneracion->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          </td>
                          
                        </tr>
                        <?php } ?>                        
                      <?php }else{ ?>
                        <tr  >
                          <td colspan="9">No existen cuentas sin autorizar</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                 </div>
              </div>

            
          </div>
          <br>

          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Otros Cargos sin Autorizar</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th width="20px"><input type="checkbox" class="minimal-green" id="cargos_all" /></th>
                        <th>Proveedor</th>
                        <th>Fecha de Pago</th>
                        <th>Monto</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($cargos) > 0 ){ ?>
                        <?php foreach ($cargos as $cargo) { ?>
                         <tr >
                          <td><input type="checkbox" class="minimal cargos" name="cargo-<?php echo $cargo->id;?>"/></td>
                          <td><?php echo $cargo->nombreproveedor;?></td>
                          <td><?php echo $cargo->fecpago;?></td>
                          <td>$&nbsp;<?php echo number_format($cargo->monto,0,".",".");?></td>
                          <td>
                          <a href="<?php echo base_url(); ?>reports/ver_cargo/<?php echo $cargo->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;
                          <?php if($cargo->nombrearchivo != ''){ ?>
                          <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cargo->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Ver Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php } ?>
                          </td>
                        </tr>
                        <?php } ?>
                      <?php }else{ ?>
                        <tr  >
                          <td colspan="5">No existen cuentas sin autorizar</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->


                </div>
              </div>

            
          </div>          
          </form>
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
            periodo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Per&iacute;odo es requerido'
                    },
                    // The bank validator doesn't have any option
                    blank: {}                                    
                }
            }
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
            url: '<?php echo base_url();?>accounts/validate_autoriza_ggcc',
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
    });      

});



</script>  

<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-green, input[type="radio"].minimal-green').iCheck({
          checkboxClass: 'icheckbox_minimal-green',
          radioClass: 'iradio_minimal-green'
        });

</script>

<script>

$("#cuentas_all").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $(".cuentas").iCheck('check');
});

$("#cargos_all").on('ifChecked',function(event){
   $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $(".cargos").iCheck('check');
});

$("#cuentas_all").on('ifUnchecked',function(event){
   $(".cuentas").iCheck('uncheck');
});

$("#cargos_all").on('ifUnchecked',function(event){
   $(".cargos").iCheck('uncheck');
});

$(".cuentas").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
});

$(".cargos").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
});


</script>