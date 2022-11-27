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
          <form id="basicBootstrapForm"  action="<?php echo base_url();?>accounts/submit_pagos_cuentas" method="post">
     
          <div class="row">
            <div class="col-md-8">
              <div class="box box-primary ">
                <div class="box-header ">
                  <h3 class="box-title">Informaci&oacute;n de Pago</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fechapago">Fecha de Pago</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fechapago"  value="<?php echo date("d/m/Y"); ?>" placeholder="dd/mm/aaaa">
                               
                            </div>
                        </div> 
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="periodo">Forma de Pago</label>
                            <select name="formas_pago" id="formas_pago" class="form-control">
                              <option value="">Seleccione Forma de Pago</option>
                              <?php foreach($formas_pago as $forma_pago){ ?>
                              <option value="<?php echo $forma_pago->id;?>"><?php echo $forma_pago->nombre;?></option>
                              <?php } ?>
                            </select>
                        </div>
                      </div>  
                  </div>
                  <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="cheque">Nro. Cheque</label>  
                           <input type="text" id="cheque" name="cheque" class="form-control" placeholder="Nro. Cheque" disabled>
                        </div>
                      </div>                  
                    <div class='col-md-6'>
                        <div class="form-group">
                          <label for="cheque">Paguese a</label>  
                           <input type="text" id="paguesea" name="paguesea" class="form-control" placeholder="Destinatario Pago">
                           <p class="help-block">(*) Nombre visualizado en comprobante de egreso.</p>
                        </div>
                    </div>
                  </div>  
                  <div class='row'>
                      <div class='col-md-3'>
                        <div class="form-group ">
                        <label for="ruttitular">&nbsp;</label> 
                        <button type="submit" class="btn btn-success btn-block">Pagar</button>
                      </div>
                      </div>                  
                    <!--div class='col-md-3'>
                      <div class="form-group ">
                        <label for="ruttitular">&nbsp;</label> 
                        <button type="submit" class="btn btn-success btn-block">Pagar</button>
                      </div>
                    </div-->
                  </div>                                                                                                                   
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (left) -->          
          

            <div class="col-md-3">
              <div class="info-box <?php echo $classinfo_caja; ?>">
                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Saldo Disponible</span>
                  <span class="info-box-number"><?php echo number_format($saldo_disponible,0,".",".");?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->   

            <div class="col-md-3">
              <div class="info-box <?php echo $classinfo_saldo; ?>">
                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Deuda Total</span>
                  <span class="info-box-number"><?php echo number_format($deuda_comunidad,0,".",".");?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            

            <div class="col-md-3">
              <div class="info-box bg-light-blue">
                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Monto a Pagar</span>
                  <span class="info-box-number" id="monto_a_pagar_span">0</span>
                  <input type="hidden" id="monto_a_pagar" value="0">                  
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->                 

          </div>
          <div class="row">
            
              <div class="col-md-12">
                <div class="nav-tabs-custom">
                  <!-- definicion de pestañas ---->
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#cuentas" data-toggle="tab">Cuentas</a></li>
                      <li class=""><a href="#notas_credito" data-toggle="tab">Notas de Cr&eacute;dito</a></li>
                    </ul>

                    <div class="tab-content"><!-- espacio de contenido -->

                      <div class="tab-pane active" id="cuentas" >
                        <section id="new">

                              <h3 class="page-header">Cuentas sin pagar
                              &nbsp;&nbsp;&nbsp;
                                <div class="btn-group">
                                <button type="button" class="btn btn-default"><?php echo $title_button;?></button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <span class="caret"></span>
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="<?php echo base_url();?>accounts/pagos_cuentas" />Mostrar Activos</a></li>
                                  <li><a href="<?php echo base_url();?>accounts/pagos_cuentas/inactivos" />Mostrar Inactivos</a></li>
                                  <li><a href="<?php echo base_url();?>accounts/pagos_cuentas/todos" />Mostrar Todos</a></li>
                                </ul>
                                </div>   
                              </h3>
                             
                            
                            <br>                          
                              <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                              <thead>
                                <tr>
                                  <th colspan="3"><small>Tipo de Pago</small></th>
                                  <th rowspan="2"><small>Monto</small></th>
                                  <th rowspan="2"><small>Proveedor</small></th>
                                  <th rowspan="2"><small>Monto Deuda</small></th>
                                  <th rowspan="2"><small>Abonado</small></th>
                                  <th rowspan="2"><small>Saldo</small></th>
                                  <th rowspan="2"><small>Fecha Docto</small></th>
                                  <th rowspan="2"><small>Documento</small></th>
                                  <th rowspan="2"><small>Desactivar</small></th>
                                  <!--th rowspan="2">Acci&oacute;n</th-->
                                </tr>
                                <tr>
                                  <th><small><input type="radio" class="minimal-green" name="pago" id="sinpago_all" checked />&nbsp;&nbsp;Sin Pago</small></th>
                                  <th><small><input type="radio" class="minimal-green" name="pago" id="total_all" />&nbsp;&nbsp;Total</small></th>
                                  <th><small><input type="radio" class="minimal-green" name="pago" id="abono_all" />&nbsp;&nbsp;Abono</small></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(count($cuentas) > 0 ){ ?>
                                  <?php foreach ($cuentas as $cuenta) { ?>
                                   <tr <?php echo $cuenta->active == 0 ? "class='info'" : ""; ?> >
                                    <td><?php if($cuenta->active == 1) { ?><input type="radio" class="minimal sinpago" name="cuenta-<?php echo $cuenta->id;?>" id="sinpago-<?php echo $cuenta->id;?>" value="sinpago"   /><?php } ?></td>
                                    <td><?php if($cuenta->active == 1) { ?><input type="radio" class="minimal total" name="cuenta-<?php echo $cuenta->id;?>" id="total-<?php echo $cuenta->id;?>" value="total"  /><?php } ?></td>
                                    <td><?php if($cuenta->active == 1) { ?><input type="radio" class="minimal abono" name="cuenta-<?php echo $cuenta->id;?>" id="abono-<?php echo $cuenta->id;?>" value="abono"  /><?php } ?></td>
                                    <td class="form-group">
                                      <?php if($cuenta->active == 1) { ?>
                                      <input type="text" class="col-xs-9 monto_abono miles" name="monto_abono-<?php echo $cuenta->id;?>"  id="monto_abono-<?php echo $cuenta->id;?>" placeholder="" onpaste="return false" <?php echo $cuenta->active == 0 ? "style='visibility:hidden'" : ""; ?> readonly>
                                      <input type="hidden" name="monto_abono_hidden-<?php echo $cuenta->id;?>" class="abono_hidden"  id="monto_abono_hidden-<?php echo $cuenta->id;?>" value="0" readonly>
                                      <?php } ?>
                                    </td>
                                    <td><small><?php echo $cuenta->proveedor;?></small></td>
                                    <td class="text-right"><small>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></small></td>
                                    <td class="text-right"><small>$&nbsp;<?php echo number_format($cuenta->abonado,0,".",".");?></small></td>
                                    <td class="text-right"><small>$&nbsp;<?php echo number_format($cuenta->saldo,0,".",".");?></small></td>
                                    <td><small><?php echo $cuenta->fecdocumento;?></small></td>
                                      <td><small>
                                      <?php if($cuenta->nombrearchivo != ''){ ?>
                                      <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                                      <?php }else{ ?>
                                        <?php if($upload){ ?>
                                          <a href="<?php echo base_url(); ?>accounts/upfile/<?php echo $cuenta->id;?>/<?php echo $cuenta->tipocuenta;?>/<?php echo $origen;?>" data-toggle="tooltip" title="Adjuntar Comprobante"><i class="fa fa-upload"></i></a>
                                        <?php } ?>
                                      <?php } ?>
                                      </small>
                                      </td>
                                    <td>
                                    
                                    <?php $txt_a = $cuenta->active == 0 ? 'Activar' : 'Desactivar'; ?>
                                          <a href="<?php echo base_url();?>accounts/desactiva_cuenta/<?php echo $cuenta->id;?>/<?php echo $tipo_cuentas;?>" /><small><?php echo $txt_a;?></small></a>

                                    </td>

                                    <input type="hidden" name="saldo-<?php echo $cuenta->id;?>" id="saldo-<?php echo $cuenta->id;?>" value="<?php echo $cuenta->saldo;?>" />
                                    <input type="hidden" name="provee-<?php echo $cuenta->id;?>" id="provee-<?php echo $cuenta->id;?>" value="<?php echo $cuenta->idproveedor;?>" />
                                    <!--td>
                                      <a href="#" id="quitar-<?php echo $cuenta->id;?>" class="quitar">X</a>
                                    </td-->
                                  </tr>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <tr  >
                                    <td colspan="8">No existen cuentas pendientes</td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                              </table>

                        </section>
                      </div><!-- ab-pane active-->


                      <div class="tab-pane" id="notas_credito" >
                        <section id="new">

                              <h3 class="page-header">Notas de Cr&eacute;dito</h3>
                              <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                              <thead>
                                <tr>
                                  <th ><small>Seleccionar</small></th>
                                  <th ><small>Proveedor</small></th>
                                  <th ><small>Monto</small></th>
                                  <th ><small>Fecha Docto</small></th>
                                  <th ><small>Documento</small></th>
                                  <!--th rowspan="2">Acci&oacute;n</th-->
                                </tr>
                              </thead>
                              <tbody>
                                <input type="hidden" name="totalnc" id="totalnc" value="0" />
                                <?php if(count($notas_credito) > 0 ){ ?>
                                  <?php foreach ($notas_credito as $nc) { ?>
                                   <tr >
                                    <td><input type="checkbox" class="minimal selnc" name="selnc-<?php echo $nc->id;?>" id="selnc-<?php echo $nc->id;?>" value="<?php echo $nc->id;?>" /></td>
                                    <td><small><?php echo $nc->proveedor;?></small></td>
                                    <td class="text-right"><small>$&nbsp;<?php echo number_format($nc->monto,0,".",".");?></small></td>
                                    <td><small><?php echo $nc->fecdocumento;?></small></td>
                                      <td><small>
                                      <?php if($nc->nombrearchivo != ''){ ?>
                                      <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$nc->nombrearchivo;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                                      <?php }else{ ?>
                                        <?php if($upload){ ?>
                                          <a href="<?php echo base_url(); ?>accounts/upfile/<?php echo $nc->id;?>/<?php echo $nc->tipocuenta;?>/<?php echo $origen;?>" data-toggle="tooltip" title="Adjuntar Comprobante"><i class="fa fa-upload"></i></a>
                                        <?php } ?>
                                      <?php } ?>
                                      </small>
                                      </td>
                                    <input type="hidden" name="saldonc-<?php echo $nc->id;?>" id="saldonc-<?php echo $nc->id;?>" value="<?php echo $nc->saldo;?>" />
                                    <input type="hidden" name="proveenc-<?php echo $nc->id;?>" id="proveenc-<?php echo $nc->id;?>" value="<?php echo $nc->idproveedor;?>" />
                                    <!--td>
                                      <a href="#" id="quitar-<?php echo $cuenta->id;?>" class="quitar">X</a>
                                    </td-->
                                  </tr>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <tr  >
                                    <td colspan="8">No existen notas de cr&eacute;dito pendientes</td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                              </table>

                        </section>
                      </div><!-- ab-pane active-->                      

                    </div><!-- espacio de contenido -->
                </div><!-- /.nav-tabs-custom -->
              </div>

            
          </div>
          </form>
        </section><!-- /.content -->

<script type="text/javascript">

var provee = [];


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


        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
</script>

<script>

$("#sinpago_all").on('ifChecked',function(event){
   $(".sinpago").iCheck('check'); 
   $(".total").iCheck('uncheck');
   $(".abono").iCheck('uncheck');
   $(".monto_abono").prop('readonly',true);
});

$("#total_all").on('ifChecked',function(event){
   $(".sinpago").iCheck('uncheck'); 
   $(".total").iCheck('check');
   $(".abono").iCheck('uncheck');
   $(".monto_abono").prop('readonly',true);
});

$("#abono_all").on('ifChecked',function(event){
   $(".sinpago").iCheck('uncheck'); 
   $(".abono").iCheck('check');
   $(".total").iCheck('uncheck');
   $(".monto_abono").prop('readonly',false);
   $(".monto_abono [value!='0']").val(0);
});

$(".sinpago").on('ifChecked',function(event){
  var id_press = $(this).attr('id');
  var array_id_press = id_press.split("-");


  
  //valor_cuenta = $("#monto_abono-"+array_id_press[1]).prop('readonly') ? parseInt($("#saldo-"+array_id_press[1]).val()) : parseInt($("#monto_abono_hidden-"+array_id_press[1]).val());
  valor_cuenta = parseInt($("#monto_abono_hidden-"+array_id_press[1]).val());
  $('#monto_abono_hidden-'+array_id_press[1]).val(0);
  var monto_a_pagar = parseInt($('#monto_a_pagar').val());
  monto_a_pagar -= valor_cuenta;  
  $('#monto_a_pagar').val(monto_a_pagar);
  $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));



  $("#monto_abono-"+array_id_press[1]).val('');
  $("#monto_abono-"+array_id_press[1]).prop('readonly',true);
  /*var index = provee.indexOf($("#provee-"+array_id_press[1]).val());
  if (index > -1) {
    provee.splice(index, 1);
  }
  console.log(provee);
*/
  $('#basicBootstrapForm').formValidation('updateStatus', 'formas_pago','NOT_VALIDATED'); //quita validacion
});

$(".total").on('ifChecked',function(event){
  var id_press = $(this).attr('id');
  var array_id_press = id_press.split("-");

  $("#monto_abono-"+array_id_press[1]).val('');
  $("#monto_abono-"+array_id_press[1]).prop('readonly',true);


  // AGREGA MONTO A PAGAR

    var monto_a_pagar = parseInt($('#monto_a_pagar').val());
    monto_a_pagar -= parseInt($('#monto_abono_hidden-'+array_id_press[1]).val());  

  


  valor_cuenta = parseInt($("#saldo-"+array_id_press[1]).val());
  monto_a_pagar += valor_cuenta;  
  $('#monto_a_pagar').val(monto_a_pagar);
  $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));
  $('#monto_abono_hidden-'+array_id_press[1]).val(valor_cuenta);

  $('#basicBootstrapForm').formValidation('updateStatus', 'formas_pago','NOT_VALIDATED'); //quita validacion
  //provee.push($("#provee-"+array_id_press[1]).val());
  //console.log(provee);

});

$(".abono").on('ifChecked',function(event){
  var id_press = $(this).attr('id');
  var array_id_press = id_press.split("-");
    var monto_a_pagar = parseInt($('#monto_a_pagar').val());
    monto_a_pagar -= parseInt($('#monto_abono_hidden-'+array_id_press[1]).val());  
  $('#monto_a_pagar').val(monto_a_pagar);
  $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));
  $('#monto_abono_hidden-'+array_id_press[1]).val(0);

  $("#monto_abono-"+array_id_press[1]).prop('readonly',false);
  $("#monto_abono-"+array_id_press[1]).val(0);

  //provee.push($("#provee-"+array_id_press[1]).val());
  //console.log(provee);
  // $('#basicBootstrapForm').formValidation('addField', $("#monto_abono-"+array_id_press[1]),accountValidators); //quita validacion
  $('#basicBootstrapForm').formValidation('updateStatus', 'formas_pago','NOT_VALIDATED'); //quita validacion
});

/*$(".monto_abono").on('input',function(event){

console.log("aaa");
});*/

  $('.monto_abono').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
    $('#basicBootstrapForm').formValidation('updateStatus', 'formas_pago','NOT_VALIDATED'); //quita validacion
  }) 


$(".selnc").on('ifChecked',function(event){
 
  var totalnc = parseInt($('#totalnc').val());
  var id_press = $(this).attr('id');
  var array_id_press = id_press.split("-");
  var monto_a_pagar = parseInt($('#monto_a_pagar').val());
  //monto_a_pagar += parseInt($('#totalnc').val());    

    //var monto_a_pagar = parseInt($('#monto_a_pagar').val());
    monto_a_pagar -= parseInt($('#saldonc-'+array_id_press[1]).val());  
  $('#monto_a_pagar').val(monto_a_pagar);
  $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));
  $('#totalnc').val($('#saldonc-'+array_id_press[1]).val());
  $('#basicBootstrapForm').formValidation('updateStatus', 'formas_pago','NOT_VALIDATED'); //quita validacion
});   


$(".selnc").on('ifUnchecked',function(event){
  var id_press = $(this).attr('id');
  var array_id_press = id_press.split("-");
  var monto_a_pagar = parseInt($('#monto_a_pagar').val());
  //monto_a_pagar += parseInt($('#totalnc').val());    
  
  
    //var monto_a_pagar = parseInt($('#monto_a_pagar').val());
    monto_a_pagar += parseInt($('#saldonc-'+array_id_press[1]).val());  
  $('#monto_a_pagar').val(monto_a_pagar);
  $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));
  $('#totalnc').val($('#saldonc-'+array_id_press[1]).val());
  $('#basicBootstrapForm').formValidation('updateStatus', 'formas_pago','NOT_VALIDATED'); //quita validacion
});    

$(".monto_abono").on('keyup',function(event){
    var fieldid = $(this).attr('id');
    var array_field = fieldid.split("-");
    var valor_limite =  parseInt($("#saldo-"+array_field[1]).val()); 
    var valor_actual = parseInt($('#monto_abono_hidden-'+array_field[1]).val());
    var valor_ingresado = $(this).val() == '' ? 0 : parseInt(replaceAll($(this).val(),".",""));
    // AGREGA MONTO A PAGAR
    var monto_a_pagar = parseInt($('#monto_a_pagar').val());
    monto_a_pagar -= valor_actual;  
   
    if(parseInt(valor_ingresado) > valor_limite){
        $(this).val(valor_limite);
        $('#monto_abono_hidden-'+array_field[1]).val(valor_limite);
        monto_a_pagar += valor_limite; 
        $('#monto_a_pagar').val(monto_a_pagar);
        $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));        
        return false;
    }else{
        $('#monto_abono_hidden-'+array_field[1]).val(valor_ingresado);
        monto_a_pagar += parseInt(valor_ingresado); 
        $('#monto_a_pagar').val(monto_a_pagar);
        $('#monto_a_pagar_span').html(number_format(monto_a_pagar,0,'.','.'));  

    }
    //if()       
  //console.log(event.charCode);

});        


$(".monto_abono").on('focus',function(event){
  if($(this).val() == '0'){
    $(this).val('');
  }
});        



$(".monto_abono").on('blur',function(event){
  var fieldid = $(this).attr('id');
  var array_field = fieldid.split("-");
  if($("#abono-"+array_field[1]).is(':checked') && $(this).val() == ''){
    $(this).val(0);
  }
});        

</script>

<script>
$("#formas_pago").on('change',function(event){
  $("#cheque").val('');
  if($(this).val() == 1){
    $('#basicBootstrapForm').formValidation('updateStatus', 'cheque','NOT_VALIDATED'); //quita validacion
    $("#cheque").prop("disabled",true);
  }else if($(this).val() == 2){    
    $("#cheque").prop("disabled",false);
  }else{
    $('#basicBootstrapForm').formValidation('updateStatus', 'cheque','NOT_VALIDATED'); //quita validacion
    $("#cheque").prop("disabled",true);
  }
});


</script>

<script>
$(document).ready(function() {
     FormValidation.Validator.validateRut = {
        validate: function(validator, $field, options) {
          var validador = true;
          $field.Rut();
          var rut = $field.val();
          var cleanRut = replaceAll(rut,".","");
          var cleanRut = replaceAll(cleanRut,"-","");
          if(VerificaRut(cleanRut)){
              return true;

          }else{
              return {
                  valid : false
              }

          }


        }
    };  
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            formas_pago: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Forma de Pago es requerido'
                    },
                    blank: {},

                    /*callback: {
                        message: 'Monto a pagar debe ser mayor a cero',
                        callback: function (value, validator, $field) {
                            if(parseInt($('#monto_a_pagar').val()) > 0){

                              var proveedores = [];
                              $('.abono_hidden').each(function(i, obj) {
                                if($(this).val() > 0){
                                    var fieldid = $(this).attr('id');
                                    var array_field = fieldid.split("-");
                                    var index = proveedores.indexOf($("#provee-"+array_field[1]).val());
                                    if (index == -1) {
                                      proveedores.push($("#provee-"+array_field[1]).val());
                                    }                                  

                                }                                
                                  
                              });


                              $('.selnc').each(function(i, obj) {
                                    if($(this).is(':checked')){
                                      var fieldid = $(this).attr('id');
                                      var array_field = fieldid.split("-");
                                      var index = proveedores.indexOf($("#proveenc-"+array_field[1]).val());
                                      if (index == -1) {
                                        proveedores.push($("#proveenc-"+array_field[1]).val());
                                      } 
                                    }          
                              });

                              

                              if(proveedores.length > 1){
                                return  {
                                      valid: false,
                                      message: 'No es posible pagar a m&aacute;s de un proveedor'
                                  }                                
                                }else{
                                  return true;

                                }
                              
                            }else{
                              return  {
                                    valid: false,
                                    message: 'Monto a pagar debe ser mayor a cero'
                                }
                            }
                        }
                    }  */                  


                }
            },               
            cheque: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Cheque es requerido'
                    }              
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
            if(parseInt($('#monto_a_pagar').val()) > 0){

                var proveedores = [];
                var tiene_nc = false;
                $('.abono_hidden').each(function(i, obj) {
                  if($(this).val() > 0){
                      var fieldid = $(this).attr('id');
                      var array_field = fieldid.split("-");
                      var index = proveedores.indexOf($("#provee-"+array_field[1]).val());
                      if (index == -1) {
                        proveedores.push($("#provee-"+array_field[1]).val());
                      }                                  

                  }                                
                    
                });


                $('.selnc').each(function(i, obj) {
                      if($(this).is(':checked')){
                        tiene_nc = true;
                        var fieldid = $(this).attr('id');
                        var array_field = fieldid.split("-");
                        var index = proveedores.indexOf($("#proveenc-"+array_field[1]).val());
                        if (index == -1) {
                          proveedores.push($("#proveenc-"+array_field[1]).val());
                        } 
                      }          
                });

                
                if(proveedores.length > 1 && tiene_nc){
                      fv
                          // Show the custom message
                          .updateMessage('formas_pago', 'blank', 'Selecci&oacute;n de Notas de cr&eacute;dito no est&aacute; permitido para m&aacute;s de un proveedor')
                          // Set the field as invalid
                          .updateStatus('formas_pago', 'INVALID', 'blank');                              
                  }else{

                    fv.defaultSubmit();

                  }
                
              }else{
                  fv
                      // Show the custom message
                      .updateMessage('formas_pago', 'blank', 'Monto a pagar debe ser mayor a cero')
                      // Set the field as invalid
                      .updateStatus('formas_pago', 'INVALID', 'blank');                                                
              }
    });      
});
</script>   
<script language="Javascript">


function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}

  $('.miles').mask('000.000.000.000.000', {reverse: true});     

  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })    



  /*$(".desactiva").on('ifChecked',function(event){
    var id = $(this).attr('id');
    var array_id = id.split("-");
        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>accounts/desactiva_cuenta/' + array_id[1] + '/0'
        }).success(function(response) {
            location.reload();
        });    
  });*/


  /*$(".desactiva").on('ifUnchecked',function(event){
    var id = $(this).attr('id');
    var array_id = id.split("-");
        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>accounts/desactiva_cuenta/' + array_id[1] + '/1'
        }).success(function(response) {
            location.reload();
        });    
  });*/
</script>
