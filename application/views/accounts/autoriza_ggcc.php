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

            <div class="col-md-3">
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Monto Autorizado</span>
                  <span class="info-box-number" id="monto_autorizado_span">0</span>
                  <input type="hidden" id="monto_autorizado" value="0">
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->             

          <div class="row">
              <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#cuentas" data-toggle="tab">Cuentas</a></li>
                      <li class=""><a href="#nota_credito" data-toggle="tab">Notas de Cr&eacute;dito</a></li>
                      <li class=""><a href="#cargos" data-toggle="tab">Otros Cargos</a></li>
                      <li class=""><a href="#remuneraciones" data-toggle="tab">Remuneraciones</a></li>
                      <li class=""><a href="#ingresos" data-toggle="tab">Ingresos</a></li>
                    </ul>


                    <div class="tab-content"><!-- espacio de contenido -->


                      <div class="tab-pane active" id="cuentas" >
                        <section id="new">

                              <h4 class="box-title">Cuentas sin Autorizar</h4>  
                              <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                              <thead>
                                <tr>
                                  <th width="20px"><input type="checkbox" class="minimal-green" id="cuentas_all" /></th>
                                  <th><small>Proveedor</small></th>
                                  <th><small>Tipo Documento Tributario</small></th>
                                  <th><small>Num. Documento</small></th>
                                  <th><small>Concepto</small></th>
                                  <th><small>Fecha Documento</small></th>
                                  <th><small>Monto</small></th>
                                  <th><small>Fecha Vencimiento</small></th>
                                  <th>&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(count($cuentas) > 0 ){ ?>
                                  <?php foreach ($cuentas as $cuenta) { ?>
                                   <tr >
                                    <td><small><input type="checkbox" class="minimal cuentas" name="cuenta-<?php echo $cuenta->id;?>" id="cuenta-<?php echo $cuenta->id;?>" /></small></td>
                                    <td><small><?php echo $cuenta->proveedor;?></small></td>
                                    <td><small><?php echo $cuenta->tipodocumentotributario;?></small></td>
                                    <td><small><?php echo $cuenta->nrodocumento;?></small></td>
                                    <td><small><?php echo $cuenta->concepto;?></small></td>
                                    <td><small><?php echo $cuenta->fecdocumento;?></small></td>
                                    <td><small>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?><input type="hidden" id="cuenta_monto-<?php echo $cuenta->id;?>" value="<?php echo $cuenta->monto;?>" /></small></td>
                                    <td><small><?php echo $cuenta->fecvencimiento;?></small></td>
                                    
                                    <td><small>
                                    <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                                    &nbsp;
                                    &nbsp;                      
                                    <?php if($cuenta->nombrearchivo != ''){ ?>
                                    <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Ver Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                    <?php } ?>
                                    </small></td>
                                    
                                  </tr>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <tr  >
                                    <td colspan="9"><small>No existen cuentas sin autorizar</small></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                              </table>
                        </section>
                      </div><!-- ab-pane active-->


                      <div class="tab-pane " id="nota_credito" >
                        <section id="new">

                              <h4 class="box-title">Notas de Cr&eacute;dito sin Autorizar&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="Notas de Cr&eacute;dito rebaraj&aacute;n monto del gasto com&uacute;n" title="Atenci&oacute;n"></i></h4>  
                              <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                              <thead>
                                <tr>
                                  <th width="20px"><input type="checkbox" class="minimal-green" id="nc_all" /></th>
                                  <th><small>Proveedor</small></th>
                                  <th><small>Tipo Documento Tributario</small></th>
                                  <th><small>Num. Documento</small></th>
                                  <th><small>Concepto</small></th>
                                  <th><small>Fecha Documento</small></th>
                                  <th><small>Monto</small></th>
                                  <th><small>Fecha Vencimiento</small></th>
                                  <th>&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(count($notas_credito) > 0 ){ ?>
                                  <?php foreach ($notas_credito as $nc) { ?>
                                   <tr >
                                    <td><small><input type="checkbox" class="minimal ncs" name="nc-<?php echo $nc->id;?>" id="nc-<?php echo $nc->id;?>" /></small></td>
                                    <td><small><?php echo $nc->proveedor;?></small></td>
                                    <td><small><?php echo $nc->tipodocumentotributario;?></small></td>
                                    <td><small><?php echo $nc->nrodocumento;?></small></td>
                                    <td><small><?php echo $nc->concepto;?></small></td>
                                    <td><small><?php echo $nc->fecdocumento;?></small></td>
                                    <td><small>$&nbsp;<?php echo number_format($nc->monto,0,".",".");?><input type="hidden" id="nc_monto-<?php echo $nc->id;?>" value="<?php echo $nc->monto;?>" /></small></td>
                                    <td><small><?php echo $nc->fecvencimiento;?></small></td>
                                    
                                    <td><small>
                                    <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $nc->id;?>" data-toggle="tooltip" title="Ver Nota de Cr&eacute;dito"><span class="glyphicon glyphicon-search"></span></a>
                                    &nbsp;
                                    &nbsp;                      
                                    <?php if($nc->nombrearchivo != ''){ ?>
                                    <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$nc->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Ver Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                    <?php } ?>
                                    </small></td>
                                    
                                  </tr>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <tr  >
                                    <td colspan="9"><small>No existen Notas de Cr&eacute;dito sin autorizar</small></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                              </table>
                        </section>
                      </div><!-- ab-pane active-->

                      <div class="tab-pane" id="cargos" >
                        <section id="new">

                              <h4 class="box-title">Otros Cargos sin Autorizar</h4>  
                             <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                            <thead>
                              <tr>
                                <th width="20px"><small><input type="checkbox" class="minimal-green" id="cargos_all" /></small></th>
                                <th><small>Proveedor</small></th>
                                <th><small>Fecha de Pago</small></th>
                                <th><small>Monto</small></th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if(count($cargos) > 0 ){ ?>
                                <?php foreach ($cargos as $cargo) { ?>
                                 <tr >
                                  <td><small><input type="checkbox" class="minimal cargos" name="cargo-<?php echo $cargo->id;?>" id="cargo-<?php echo $cargo->id;?>"/></small></td>
                                  <td><small><?php echo $cargo->nombreproveedor;?></small></td>
                                  <td><small><?php echo $cargo->fecpago;?></small></td>
                                  <td><small>$&nbsp;<?php echo number_format($cargo->monto,0,".",".");?><input type="hidden" id="cargo_monto-<?php echo $cargo->id;?>" value="<?php echo $cargo->monto;?>" /></small></td>
                                  <td><small>
                                  <a href="<?php echo base_url(); ?>reports/ver_cargo/<?php echo $cargo->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                                  &nbsp;
                                  &nbsp;
                                  <?php if($cargo->nombrearchivo != ''){ ?>
                                  <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cargo->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Ver Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                  <?php } ?>
                                  </small></td>
                                </tr>
                                <?php } ?>
                              <?php }else{ ?>
                                <tr  >
                                  <td colspan="5"><small>No existen cargos sin autorizar</small></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                            </table>
                        </section>
                      </div><!-- ab-pane -->



                      <div class="tab-pane" id="remuneraciones" >
                        <section id="new">

                              <h4 class="box-title">Cuentas Remuneraciones sin Autorizar</h4>  
                              <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                              <thead>
                                <tr>
                                  <th width="20px"><small><input type="checkbox" class="minimal-green" id="remuneraciones_all" /></small></th>
                                  <th><small>Proveedor</small></th>
                                  <th><small>Tipo Documento Tributario</small></th>
                                  <th><small>Num. Documento</small></th>
                                  <th><small>Concepto</small></th>
                                  <th><small>Fecha Documento</small></th>
                                  <th><small>Monto</small></th>
                                  <th><small>Fecha Vencimiento</small></th>
                                  <th>&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(count($remuneraciones) + count($remuneraciones_anticipos) > 0 ){ ?>

                                  <?php foreach ($remuneraciones_anticipos as $remuneracion) { ?>
                                   <tr >
                                    <td><small><input type="checkbox" class="minimal remuneraciones" name="remuneracion-<?php echo $remuneracion->id;?>" id="remuneracion-<?php echo $remuneracion->id;?>"/></small></td>
                                    <td><small><?php echo $remuneracion->proveedor;?></small></td>
                                    <td><small><?php echo $remuneracion->tipodocumentotributario;?></small></td>
                                    <td><small><?php echo $remuneracion->nrodocumento;?></small></td>
                                    <td><small><?php echo $remuneracion->concepto;?></small></td>
                                    <td><small><?php echo $remuneracion->fecdocumento;?></small></td>
                                    <td><small>$&nbsp;<?php echo number_format($remuneracion->monto,0,".",".");?><input type="hidden" id="remuneracion_monto-<?php echo $remuneracion->id;?>" value="<?php echo $remuneracion->monto;?>" /></small></td>
                                    <td><small><?php echo $remuneracion->fecvencimiento;?></small></td>
                                    
                                    <td><small>
                                    <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $remuneracion->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                                    </small></td>
                                    
                                  </tr>
                                  <?php } ?>  

                                  <?php foreach ($remuneraciones as $remuneracion) { ?>
                                   <tr >
                                    <td><small><input type="checkbox" class="minimal remuneraciones" name="remuneracion-<?php echo $remuneracion->id;?>" id="remuneracion-<?php echo $remuneracion->id;?>"/></small></td>
                                    <td><small><?php echo $remuneracion->proveedor;?></small></td>
                                    <td><small><?php echo $remuneracion->tipodocumentotributario;?></small></td>
                                    <td><small><?php echo $remuneracion->nrodocumento;?></small></td>
                                    <td><small><?php echo $remuneracion->concepto;?></small></td>
                                    <td><small><?php echo $remuneracion->fecdocumento;?></small></td>
                                    <td><small>$&nbsp;<?php echo number_format($remuneracion->monto,0,".",".");?><input type="hidden" id="remuneracion_monto-<?php echo $remuneracion->id;?>" value="<?php echo $remuneracion->monto;?>" /></small></td>
                                    <td><small><?php echo $remuneracion->fecvencimiento;?></small></td>
                                    
                                    <td><small>
                                    <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $remuneracion->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                                    </small></td>
                                    
                                  </tr>
                                  <?php } ?>                        
                                <?php }else{ ?>
                                  <tr  >
                                    <td colspan="9"><small>No existen cuentas de remuneraciones sin autorizar</small></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                              </table>
                        </section>
                      </div><!-- ab-pane -->        


                      <div class="tab-pane" id="ingresos" >
                        <section id="new">

                              <h4 class="box-title">Ingresos sin Autorizar&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="Ingresos rebaraj&aacute;n monto del gasto com&uacute;n" title="Atenci&oacute;n"></i></h4>  
                              <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                              <thead>
                                <tr>
                                  <th width="20px"><small><input type="checkbox" class="minimal-green" id="ingresos_all" /></th>
                                  <th><small>Proveedor</small></th>
                                  <th><small>Tipo Documento Tributario</small></th>
                                  <th><small>Num. Documento</small></th>
                                  <th><small>Concepto</small></th>
                                  <th><small>Fecha Documento</small></th>
                                  <th><small>Monto</small></th>
                                  <th><small>Fecha Vencimiento</small></th>
                                  <th>&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(count($ingresos) > 0 ){ ?>
                                  <?php foreach ($ingresos as $ingreso) { ?>
                                   <tr >
                                    <td><small><input type="checkbox" class="minimal ingresos" name="ingreso-<?php echo $ingreso->id;?>" id="ingreso-<?php echo $ingreso->id;?>" /></small></td>
                                    <td><small><?php echo $ingreso->proveedor;?></small></td>
                                    <td><small><?php echo $ingreso->tipodocumentotributario;?></small></td>
                                    <td><small><?php echo $ingreso->nrodocumento;?></small></td>
                                    <td><small><?php echo $ingreso->concepto;?></small></td>
                                    <td><small><?php echo $ingreso->fecdocumento;?></small></td>
                                    <td><small>$&nbsp;<?php echo number_format($ingreso->monto,0,".",".");?><input type="hidden" id="ingreso_monto-<?php echo $ingreso->id;?>" value="<?php echo $ingreso->monto;?>" /></small></td>
                                    <td><small><?php echo $ingreso->fecvencimiento;?></small></td>
                                    
                                    <td><small>
                                    <a href="<?php echo base_url(); ?>reports/ver_ingreso/<?php echo $ingreso->id;?>" data-toggle="tooltip" title="Ver Ingreso"><span class="glyphicon glyphicon-search"></span></a>
                                    &nbsp;
                                    &nbsp;                      
                                    <?php if($ingreso->nombrearchivo != ''){ ?>
                                    <a href="<?php echo base_url(); ?>uploads/ingresos/<?php echo $this->session->userdata('comunidadid')."/".$ingreso->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Ver Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                    <?php } ?>
                                    </small></td>
                                    
                                  </tr>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <tr  >
                                    <td colspan="9"><small>No existen ingresos sin autorizar</small></td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                              </table>
                        </section>
                      </div><!-- ab-pane -->                                     


                    </div><!-- tab-content --> 

                </div><!-- nav-tabs-custom --> 
              </div><!-- col-md-12 -->

          </div><!-- row -->
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
                    blank: {},
                    //SI DEBO PERMITIR AUTORIZAR UN MONTO NEGATIVO.  EN EL PRORRATEO DEBO VALIDAR QUE SEA POSITIVO
                    /*callback: {
                        message: 'Monto autorizado debe ser mayor a cero',
                        callback: function (value, validator, $field) {
                            if(parseInt($('#monto_autorizado').val()) > 0){
                              return true;
                            }else{
                              return  {
                                    valid: false,
                                    message: 'Monto autorizado debe ser mayor a cero'
                                }
                            }
                        }
                    }    */                 

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

$("#nc_all").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $(".ncs").iCheck('check');
});


$("#cargos_all").on('ifChecked',function(event){
   $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $(".cargos").iCheck('check');
});

$("#remuneraciones_all").on('ifChecked',function(event){
   $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $(".remuneraciones").iCheck('check');
});


$("#ingresos_all").on('ifChecked',function(event){
   $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $(".ingresos").iCheck('check');
});

$("#cuentas_all").on('ifUnchecked',function(event){
   $(".cuentas").iCheck('uncheck');
});

$("#nc_all").on('ifUnchecked',function(event){
   $(".ncs").iCheck('uncheck');
});


$("#cargos_all").on('ifUnchecked',function(event){
   $(".cargos").iCheck('uncheck');
});

$("#remuneraciones_all").on('ifUnchecked',function(event){
   $(".remuneraciones").iCheck('uncheck');
});


$("#ingresos_all").on('ifUnchecked',function(event){
   $(".ingresos").iCheck('uncheck');
});


$(".cuentas").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_cuenta = array_elem[1];

  var valor_cuenta = parseInt($('#cuenta_monto-'+id_cuenta).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado += valor_cuenta;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));

});


$(".cuentas").on('ifUnchecked',function(event){

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_cuenta = array_elem[1];

  var valor_cuenta = parseInt($('#cuenta_monto-'+id_cuenta).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado -= valor_cuenta;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));

});





$(".cargos").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_cargo = array_elem[1];

  var valor_cargo = parseInt($('#cargo_monto-'+id_cargo).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado += valor_cargo;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));



});

$(".cargos").on('ifUnchecked',function(event){
  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_cargo = array_elem[1];

  var valor_cargo = parseInt($('#cargo_monto-'+id_cargo).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado -= valor_cargo;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));



});



$(".remuneraciones").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_remuneracion = array_elem[1];

  var valor_remuneracion = parseInt($('#remuneracion_monto-'+id_remuneracion).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado += valor_remuneracion;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));  
});


$(".remuneraciones").on('ifUnchecked',function(event){
  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_remuneracion = array_elem[1];

  var valor_remuneracion = parseInt($('#remuneracion_monto-'+id_remuneracion).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado -= valor_remuneracion;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));  
});



$(".ingresos").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_ingreso = array_elem[1];

  var valor_ingreso = parseInt($('#ingreso_monto-'+id_ingreso).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado -= valor_ingreso;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));

});



$(".ingresos").on('ifUnchecked',function(event){

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_ingreso = array_elem[1];

  var valor_ingreso = parseInt($('#ingreso_monto-'+id_ingreso).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado += valor_ingreso;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));

});



$(".ncs").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_nc = array_elem[1];

  var valor_nc = parseInt($('#nc_monto-'+id_nc).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado -= valor_nc;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));

});


$(".ncs").on('ifUnchecked',function(event){

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var id_nc = array_elem[1];

  var valor_nc = parseInt($('#nc_monto-'+id_nc).val());
  var monto_autorizado = parseInt($('#monto_autorizado').val());
  monto_autorizado += valor_nc;
  $('#monto_autorizado').val(monto_autorizado);
  $('#monto_autorizado_span').html(number_format(monto_autorizado,0,'.','.'));

});

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
      trigger : 'hover',
    html: true,});   
});
</script>
<style type="text/css">
  .bs-example{
      margin: 300px 50px;
    }
</style>