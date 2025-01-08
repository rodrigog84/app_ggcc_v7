        <!-- Main content -->
        <section class="content">
        <form id="basicBootstrapForm" action="<?php echo isset($idcuenta) ? base_url().'accounts/submit_edit_cobro_individual' :  base_url().'accounts/submit_cobro_individual'; ?>" id="basicBootstrapForm" method="post">
         <div class="row">

            <div class="col-md-6">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Distribuci&oacute;n de Valores</h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                  <div class="box-body">
                        <div class="form-group">
                          <label for="fr"><h4><span class="label label-danger">MONTO DEUDA $ <?php echo number_format($datos_cuenta['monto'],0,".",".");?></span></h4></label>
                          
                        </div>
                        <div class="form-group">
                          <label for="periodo">Per&iacute;odo Cargo</label>    
                          <select name="periodo" id="periodo"  class="form-control"  >
                              <option value="">Seleccione un Per&iacute;odo</option>
                              <?php foreach($datosperiodo as $periodo){ ?>
                                <?php if(isset($idcuenta)){
                                  $periodoselected = $periodo->id == $datos_cuenta['idperiodo'] ? "selected" : "";
                                }else{
                                    $periodoselected = "";
                                } ?>
                              <option value="<?php echo $periodo->id;?>" <?php echo $periodoselected;?>><?php echo date2string($periodo->mes,$periodo->anno);?></option>
                              <?php } ?>
                          </select>
                        </div>   
                        <div class="form-group">
                          <label for="medicion_previa">Obtener Mediciones</label>    
                          <select name="medicion_previa" id="medicion_previa"  class="form-control"  >
                              <option value="">Seleccione cuenta</option>
                          </select>
                          <p class="help-block">(*) Permite obtener mediciones de una cuenta anteriormente ingresada.</p>                                            
                        </div>  

                        <div class="form-group" > 
                          <label for="unidad_medida">Unidad de Medida</label>    
                          <select name="unidad_medida" id="unidad_medida"  class="form-control"  >
                              <option value="">Seleccione Unidad de Medida</option>
                              <option value="m3" <?php echo $info_cuenta['unidadmedida'] == 'm3' ? 'selected' : '';?> >m3</option>
                              <option value="kw" <?php echo $info_cuenta['unidadmedida'] == 'kw' ? 'selected' : '';?> >kw</option>
                              <option value="unidad" <?php echo $info_cuenta['unidadmedida'] == 'unidad' ? 'selected' : '';?> >unidad</option>
                          </select>
                        </div>      
                        <div class="form-group"  <?php echo isset($idcuenta) ? "style='display:none'" : "";?>>
                          <label for="unidad_medida">Nuevo Medidor</label>    
                          &nbsp;&nbsp;<input type="checkbox" name="nuevomedidor" id="nuevomedidor" class="minimal" <?php echo $nuevomedidor == 'Y' ? 'checked' : '';?> />                             
                          <p class="help-block">(*) Al seleccionar las mediciones partir&aacute;n desde cero.</p>                                            
                        </div>                                           
                        
                        <div class="form-group" <?php echo isset($idcuenta) ? "style='display:none'" : "";?>>
                              <label for="documento">Porcentaje Cobro Individual</label>    
                              <div class="input-group">                               
                                <span class="input-group-addon">%</span>
                                <input type="text" class="form-control" name="porc" id="porc" onpaste="return false" placeholder="Porcentaje Cobro Individual" value="100">
                              </div>
                        </div>                  

                        <div class="form-group">
                              <label for="monto_ci">Monto Cobro Individual</label>   
                              <div class="input-group"> 
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto_ci" id="monto_ci" onpaste="return false" <?php echo isset($idcuenta) ? "" : "readonly";?> value="<?php echo number_format($datos_cuenta['monto'],0,".",".");?>">
                              </div>
                        </div>  
                        <div class="form-group" <?php echo isset($idcuenta) ? "style='display:none'" : "";?>>
                              <label for="monto_ci">Monto Cobro Gasto Com&uacute;n</label>    
                              <div class="input-group"> 
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" name="monto_gc" id="monto_gc" onpaste="return false" readonly value="0">
                              </div>
                        </div>  
                        <input type="hidden" name="total_cuenta" id="total_cuenta" value="<?php echo $datos_cuenta['monto'];?>" >
                  </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>


            <div class="col-md-6">
              <div class="box box-primary ">
                <div class="box-header ">
                  <h3 class="box-title">Informaci&oacute;n de Cuenta</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table">
                    <tr>
                    <td>
                    <p><b>Concepto</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $info_cuenta['concepto']; ?></p>
                    <p><b>Proveedor</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $info_cuenta['proveedor']; ?></p>
                    <p><b>Tipo Documento</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $info_cuenta['tipodoc']; ?></p>                   
                    <p><b>Nro. Documento</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $info_cuenta['nrodocumento']; ?></p>                   
                    </td>
                    </tr>
                    </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (left) -->

         
          </div>

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Propiedades</h3>  
                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">
                  <table  class="table table-hover">
                  <thead>
                    <tr>
                      <th>Propiedad</th>
                      <th>Lectura Anterior</th>
                      <th>Lectura Actual</th>
                      <th>Total Consumo</th>
                      <th>Monto a Pagar ($)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($propiedades as $propiedad){ ?>
                    <tr>
                      <td><?php echo $propiedad->numero;?></td>
                      <td class="form-group">
                        <?php if(isset($idcuenta)){
                                $lectura_anterior = isset($datos_lectura_ant[$propiedad->id]) ? $datos_lectura_ant[$propiedad->id] : 0;
                        }else{
                                $lectura_anterior = isset($datos_lectura[$propiedad->id]) ? $datos_lectura[$propiedad->id] : 0;

                        } ?>


                        <input type="text" class="lectura lect_anterior" name="lectura_anterior-<?php echo $propiedad->id;?>" id="lectura_anterior-<?php echo $propiedad->id;?>" value="<?php echo $lectura_anterior;?>" <?php /*echo $readonly; */?> >
                        <input type="hidden" class="lecturah lect_anteriorh" name="hlectura_anteriorh-<?php echo $propiedad->id;?>" id="hlectura_anterior-<?php echo $propiedad->id;?>" value="<?php echo $lectura_anterior;?>" >
                      </td>
                      <td class="form-group"> 
                              <input type="text" class="lectura lect_actual" name="lectura_actual-<?php echo $propiedad->id;?>" id="lectura_actual-<?php echo $propiedad->id;?>" value="<?php echo $datos_lectura[$propiedad->id];?>" >
                      </td>
                      <td ><b><span id="span_consumo-<?php echo $propiedad->id;?>">0</span></b><input type="hidden" name="consumo-<?php echo $propiedad->id;?>" id="consumo-<?php echo $propiedad->id;?>" value="0" readonly></td>
                      <td ><b><span id="span_monto_pagar-<?php echo $propiedad->id;?>">0</span></b><input type="hidden" name="monto_pagar-<?php echo $propiedad->id;?>" id="monto_pagar-<?php echo $propiedad->id;?>" value="0" readonly></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Totales</th>
                      <th><span id="total_lectura_anterior">0</span></th>
                      <th><span id="total_lectura_actual">0</span></th>
                      <th ><span id="total_consumo">0</span></th>
                      <th ><span id="total_monto_pagar">0</span></th>
                    </tr>
                  </tfoot>                  
                  </table>




                </div><!-- /.box-body -->
                <div class="box-footer">
                    <!-- #messages is where the messages are placed inside -->
               
                  <button type="submit" class="btn btn-success" id="agregar" style="display:none"><?php echo isset($idcuenta) ? "Editar" : "Agregar";?></button>
                  <input type="button" id="recalcular" class="btn btn-primary" value="Calcular" >
                  <input type="hidden" id="idcuenta" name="idcuenta" value="<?php echo isset($idcuenta) ? $idcuenta : 0;?>" >
                  &nbsp;&nbsp;                  
                  <a href="<?php echo isset($idcuenta) ? base_url().'accounts/editar_cuenta' :  base_url().'accounts/add_cuenta'; ?>" class="btn btn-default">Volver</a>
                </div>

              </div><!-- /.box -->
              
            </div>
          </div>
          </form>
        </section><!-- /.content -->
 
 <script>

$(document).ready(function() {
    recalcula_form();
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        /*err: {
            container: '#messages'
        },   */     
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            porc: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Porcentaje es requerido'
                    },
                    between: {
                        min: 0,
                        max: 100,
                        message: 'El porcentaje debe estar entre 0 y 100'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },

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

            unidad_medida: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Unidad de medida es requerida'
                    },
                }
            },
            
            monto_ci: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto Cobro Individual es requerido'
                    }                  
                }
                                  
            },

            lectura: {
                // The children's full name are inputs with class .childFullName
                selector: '.lectura',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Lectura es requerida'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Lectura s&oacute;lo puede contener n&uacute;meros'
                    },
                    callback: {
                        message: 'Lectura actual debe ser mayor o igual a lectura anterior',
                        callback: function (value, validator, $field) {
                            var id_text = $field.attr('id');
                            var array_field = id_text.split("-");
                            idpropiedad = array_field[1];
                            var lectura_anterior = $('#lectura_anterior-'+idpropiedad).val() == '' ? 0 : parseFloat($('#lectura_anterior-'+idpropiedad).val());
                            var lectura_actual = $('#lectura_actual-'+idpropiedad).val() == '' ? 0 : parseFloat($('#lectura_actual-'+idpropiedad).val());                            

                            if(lectura_anterior <= lectura_actual){
                              return true;
                            }else{
                              return  {
                                    valid: false,
                                    message: 'Lectura actual debe ser mayor o igual a lectura anterior'
                                }
                            }
                        }
                    }                    

                },

            }
        }
    })
        .find('.miles').mask('000.000.000.000.000', {reverse: true});           
});


$('#recalcular').on('click',function(){
  $(this).hide();
  $('#agregar').show()
  recalcula_form();
});

// CALCULO DE COBRO INDIVIDUAL Y GASTO COMUN
$("#porc").on('input',function(event){

    var total_cuenta = parseInt($('#total_cuenta').val());
    var valor_actual = parseFloat($(this).val());
    if(valor_actual >= 0 && valor_actual <= 100 ){
      var valor_cobro_individual = total_cuenta*(valor_actual/100);
      valor_gc = total_cuenta - valor_cobro_individual;
      $('#monto_ci').val(number_format(Math.round(valor_cobro_individual,2),0,'.','.'));
      $('#monto_gc').val(number_format(Math.round(valor_gc,2),0,'.','.'));
      $('#recalcular').show();
      $('#agregar').hide()
      //recalcula_form();
    }
});    

$("#monto_ci").on('input',function(event){
      $('#recalcular').show();
      $('#agregar').hide();

});


$(".lectura").on('blur',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'lectura','NOT_VALIDATED');

});


$(".lectura").on('input',function(event){

      $('#recalcular').show();
      $('#agregar').hide();

   /* var id_text =  $(this).attr('id');


    var array_field = id_text.split("-");
    idpropiedad = array_field[1];


    var lectura_anterior = $('#lectura_anterior-'+idpropiedad).val() == '' ? 0 : parseFloat($('#lectura_anterior-'+idpropiedad).val());
    var lectura_actual = $('#lectura_actual-'+idpropiedad).val() == '' ? 0 : parseFloat($('#lectura_actual-'+idpropiedad).val());

    var consumo = lectura_actual - lectura_anterior;

    // llenamos el consumo por propiedad
    $('#consumo-'+idpropiedad).val(consumo);
    $('#span_consumo-'+idpropiedad).html( Math.round(consumo*100)/100); // redondea 2 decimales
    $('#basicBootstrapForm').formValidation('updateStatus', 'lectura','NOT_VALIDATED'); //quita validacion

    recalcula_form();*/


});   




function recalcula_form(){
    var monto_ci = $('#monto_ci').val();
    var total_cuenta = parseInt(monto_ci.replace(/\./g,''));
    // SUMA DE LECTURA ANTERIOR
    var total_lectura_anterior = 0;
    $("[id^='lectura_anterior']").each(
        function(index,value){
          total_lectura_anterior += parseFloat($(this).val());

          // CALCULO POR LINEA
          var id_text =  $(this).attr('id');
          var array_field = id_text.split("-");
          idpropiedad = array_field[1];
          var lectura_anterior = $('#lectura_anterior-'+idpropiedad).val() == '' ? 0 : parseFloat($('#lectura_anterior-'+idpropiedad).val());
          var lectura_actual = $('#lectura_actual-'+idpropiedad).val() == '' ? 0 : parseFloat($('#lectura_actual-'+idpropiedad).val());

          var consumo = lectura_actual - lectura_anterior;

          // llenamos el consumo por propiedad
          $('#consumo-'+idpropiedad).val(consumo);
          $('#span_consumo-'+idpropiedad).html(number_format(Math.round(consumo*1000)/1000,3,'.','.')); // redondea 3 decimales
          // FIN CALCULO POR LINEA
        }
        
    );

    // SUMA DE LECTURA ACTUAL
    var total_lectura_actual = 0;
    $("[id^='lectura_actual']").each(
        function(index,value){
          total_lectura_actual += parseFloat($(this).val());
        }
        
    );

    // SUMA DE CONSUMO
    var total_consumo = 0;
    $("[id^='consumo']").each(
        function(index,value){
          total_consumo += parseFloat($(this).val());
        }
        
    );

    // CALCULO MONTO A PAGAR
    var monto = 0;
    var total_monto_pagar = 0;
    $("[id^='monto_pagar']").each(
        function(index,value){
            var id_text =  $(this).attr('id');
            var array_field = id_text.split("-");
            idpropiedad = array_field[1];          
            var consumo_propiedad = parseFloat($('#consumo-'+idpropiedad).val());
            var tconsumo = total_consumo == 0 ? 1 : total_consumo;
            var prorrateo = consumo_propiedad/tconsumo;
            monto = Math.round(total_cuenta*prorrateo,2);
            $(this).val(monto);
            $('#span_monto_pagar-'+idpropiedad).html(number_format(monto,0,'.','.'));
            total_monto_pagar += monto;
        }
    );

    $('#total_lectura_anterior').html(number_format(total_lectura_anterior,3,'.','.'));
    $('#total_lectura_actual').html(number_format(Math.round(total_lectura_actual*1000)/1000,3,'.','.'));
    $('#total_consumo').html(number_format(Math.round(total_consumo*1000)/1000,3,'.','.')); // redondea 2 decimales
    $('#total_monto_pagar').html(number_format(total_monto_pagar,0,'.','.'));


}


$('#periodo').change(function(){

    if($(this).val() != ''){

      $.get("<?php echo base_url();?>accounts/get_cobros_periodo/"+$(this).val(),function(data){
               // Limpiamos el select
                    $('#medicion_previa option').remove();
                    var_json = $.parseJSON(data);
                    $('#medicion_previa').append('<option value="">Seleccione cuenta</option>');
                    for(i=0;i<var_json.length;i++){
                      $('#medicion_previa').append('<option value="' + var_json[i].id + '">' + var_json[i].concepto + ' | ' + var_json[i].proveedor + '</option>');
                    }

      });
      
    }
});



$('#medicion_previa').change(function(){
   // $('#recalcular').show();
   // $('#agregar').hide()
    $('#basicBootstrapForm').formValidation('updateStatus', 'lectura','NOT_VALIDATED'); //quita validacion
    $("[id^='lectura_actual']").each(
        function(index,value){
          var id_text =  $(this).attr('id');
          var array_field = id_text.split("-");
          idpropiedad = array_field[1];
          $(this).val($('#lectura_anterior-'+idpropiedad).val());

        }
        
    );    
    if($(this).val() != ''){

      $.get("<?php echo base_url();?>accounts/get_lectura_cuenta/"+$(this).val(),function(data){
                    var_json = $.parseJSON(data);
                    for(i=0;i<var_json.length;i++){
                      $('#lectura_actual-'+var_json[i].idpropiedad).val(var_json[i].valor);
                    }
                    recalcula_form();                
      });
      
    }else{
      recalcula_form();   
    }

    
});


$("#nuevomedidor").on('ifChecked',function(event){
  $('.lect_anterior').val(0);
  $('.lect_actual').val(0);
  $('.lect_anterior').prop('readonly',false);
  recalcula_form();
});


$("#nuevomedidor").on('ifUnchecked',function(event){
    $("[id^='lectura_anterior']").each(
        function(index,value){
          var id_text =  $(this).attr('id');
          var array_field = id_text.split("-");
          idpropiedad = array_field[1];
          $(this).val($('#hlectura_anterior-'+idpropiedad).val());

        }
        
    ); 


    $("[id^='lectura_actual']").each(
        function(index,value){
          var id_text =  $(this).attr('id');
          var array_field = id_text.split("-");
          idpropiedad = array_field[1];
          $(this).val($('#hlectura_anterior-'+idpropiedad).val());

        }
        
    ); 

    $('.lect_anterior').prop('readonly',<?php echo $readonly_jquery; ?>);
    recalcula_form();
});



 </script>
<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-green',
          radioClass: 'iradio_minimal-green'
        });

</script>