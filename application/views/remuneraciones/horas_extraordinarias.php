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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_horas_extraordinarias" id="basicBootstrapForm" method="post"> 
            <div class="row">

                <div class="col-md-6">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">Per&iacute;odo&nbsp;&nbsp;<span class="label " id="span_status"></span></h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                      <div class='row'>
                          <div class='col-md-6'>
                            <div class="form-group">
                                <label for="mes">Meses</label>
                                <select name="mes" id="mes" class="form-control periodo">
                                  <option value="1" <?php echo $mes == 1 ? "selected" : ""; ?>>Enero</option>
                                  <option value="2" <?php echo $mes == 2 ? "selected" : ""; ?>>Febrero</option>
                                  <option value="3" <?php echo $mes == 3 ? "selected" : ""; ?>>Marzo</option>
                                  <option value="4" <?php echo $mes == 4 ? "selected" : ""; ?>>Abril</option>
                                  <option value="5" <?php echo $mes == 5 ? "selected" : ""; ?>>Mayo</option>
                                  <option value="6" <?php echo $mes == 6 ? "selected" : ""; ?>>Junio</option>
                                  <option value="7" <?php echo $mes == 7 ? "selected" : ""; ?>>Julio</option>
                                  <option value="8" <?php echo $mes == 8 ? "selected" : ""; ?>>Agosto</option>
                                  <option value="9" <?php echo $mes == 9 ? "selected" : ""; ?>>Septiembre</option>
                                  <option value="10" <?php echo $mes == 10 ? "selected" : ""; ?>>Octubre</option>
                                  <option value="11" <?php echo $mes == 11 ? "selected" : ""; ?>>Noviembre</option>
                                  <option value="12" <?php echo $mes == 12 ? "selected" : ""; ?>>Diciembre</option>
                                </select>
                            </div> 
                          </div>
                          <div class='col-md-6'>
                            <div class="form-group">
                                <label for="anno">A&ntilde;o</label>
                                <select name="anno" id="anno" class="form-control periodo">
                                  <?php for($i=(date('Y')-7);$i<=(date('Y')+2);$i++){ ?>
                                  <?php $yearselected = $i == $anno ? "selected" : ""; ?>
                                  <option value="<?php echo $i;?>" <?php echo $yearselected; ?>><?php echo $i;?></option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div>  
                      </div>                    
                    </div><!-- /.box-body -->
                  </div>
                </div>


            </div>     


            <div class="row">

                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">Ingreso de Horas Extraordinarias</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                          <table  class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th rowspan="2"><small>#</small></th>
                              <th rowspan="2" ><small>Rut</small></th>
                              <th rowspan="2"><small>Nombre Trabajador</small></th>
                              <!--th rowspan="2" ><small>Sueldo Base</small></th>
                              <th rowspan="2" ><small>Valor por Hora ($)</small></th-->
                              <th colspan="3" ><small>Horas al 50 %</small></th>
                              <th colspan="3" ><small>Horas al 100 %</small></th>
                            </tr>
                            <tr>
                              <th ><small>Valor Hora</small></th>
                              <th ><small>Horas</small></th>
                              <th ><small>Monto ($)</small></th>
                              <th ><small>Valor Hora</small></th>
                              <th ><small>Horas</small></th>
                              <th ><small>Monto ($)</small></th>                              
                             </tr>
                          </thead>
                          <tbody>
                            <?php if(count($personal) > 0 ){ ?>
                              <?php $i = 1; ?>
                              <?php $total_horas_50 = 0; ?>
                              <?php $total_horas_100 = 0; ?>
                              <?php foreach ($personal as $trabajador) { ?>
                              <?php $valorhora = $trabajador->parttime == 1 ? round((($trabajador->sueldobase)/$trabajador->diastrabajo)/$trabajador->horasdiarias,0) : round(((($trabajador->sueldobase)/30)*7)/45,0); ?>

                              <?php $valorhora50 = round($valorhora*1.5,0); ?>
                              <?php $valorhora100 = round($valorhora*2,0); ?>
                               <tr >
                                <td><small><?php echo $i ;?></small></td>
                                <td><small><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut,0,".",".")."-".$trabajador->dv;?></small></td>
                                <td><small><?php echo $trabajador->nombre." ".$trabajador->apaterno." ".$trabajador->amaterno;?></small></td>
                                <!--td><small><?php echo number_format($trabajador->sueldobase,0,".",".");?></small></td-->
                                <!--td>
                                  <span id="spanvalorhora_<?php echo $trabajador->id;?>"  class="text-right input-sm" ><?php echo number_format($valorhora,0,",",".");?></span> 

                                </td-->
                                  <input type="hidden" name="valorhora_<?php echo $trabajador->id;?>" id="valorhora_<?php echo $trabajador->id;?>" class="form-control" value="<?php echo round($valorhora,0); ?>"  />
                                  <input type="hidden" name="montoactual_<?php echo $trabajador->id;?>" id="montoactual_<?php echo $trabajador->id;?>" class="form-control" value="<?php echo $valorhora; ?>"  />                                
                                <td>
                                  <span id="spanvalorhora50_<?php echo $trabajador->id;?>"  class="text-right input-sm" ><?php echo number_format($valorhora50,0,",",".");?></span> 
                                  <input type="hidden" name="montoactual50_<?php echo $trabajador->id;?>" id="montoactual50_<?php echo $trabajador->id;?>" class="form-control" value="<?php echo $valorhora50; ?>"  />
                                </td>                                
                                <td class="form-group">
                                    <input type="text" name="horas50_<?php echo $trabajador->id;?>" id="horas50_<?php echo $trabajador->id;?>" class="horas50 horas miles_decimales numeros input-sm" value="<?php echo isset($datos_remuneracion['horasextras50'][$trabajador->id]) ? $datos_remuneracion['horasextras50'][$trabajador->id] : 0; ?>"  />   
                                </td>
                                <td class="form-group">
                                  <input type="hidden" name="monto50_<?php echo $trabajador->id;?>" class="monto50" id="monto50_<?php echo $trabajador->id;?>" value="<?php echo isset($datos_remuneracion['horasextras50'][$trabajador->id]) ? $datos_remuneracion['horasextras50'][$trabajador->id]*$valorhora50 : 0; ?>"  />   
                                  <b><span id="spanmonto50_<?php echo $trabajador->id;?>"  class="text-right input-sm" ><?php echo isset($datos_remuneracion['horasextras50'][$trabajador->id]) ? number_format($datos_remuneracion['horasextras50'][$trabajador->id]*$valorhora50,0,".",".") : 0;?></span></b>   
                                </td>
                                <td>
                                  <span id="spanvalorhora100_<?php echo $trabajador->id;?>"  class="text-right input-sm" ><?php echo number_format($valorhora100,0,",",".");?></span> 
                                  <input type="hidden" name="montoactual100_<?php echo $trabajador->id;?>" id="montoactual100_<?php echo $trabajador->id;?>" class="form-control" value="<?php echo $valorhora100; ?>"  />
                                </td>                                                                
                                <td class="form-group">
                                    <input type="text" name="horas100_<?php echo $trabajador->id;?>" id="horas100_<?php echo $trabajador->id;?>" class="horas100 horas miles_decimales numeros input-sm" value="<?php echo isset($datos_remuneracion['horasextras100'][$trabajador->id]) ? $datos_remuneracion['horasextras100'][$trabajador->id] : 0; ?>"  />   
                                </td>
                                <td class="form-group">
                                  <input type="hidden" name="monto100_<?php echo $trabajador->id;?>" class="monto100" id="monto100_<?php echo $trabajador->id;?>" value="<?php echo isset($datos_remuneracion['horasextras100'][$trabajador->id]) ? $datos_remuneracion['horasextras100'][$trabajador->id]*$valorhora100 : 0; ?>"  />   
                                  <b><span id="spanmonto100_<?php echo $trabajador->id;?>"  class="text-right input-sm" ><?php echo isset($datos_remuneracion['horasextras100'][$trabajador->id]) ? number_format($datos_remuneracion['horasextras100'][$trabajador->id]*$valorhora100,0,".",".") : 0;?></span></b>   
                                </td>                                
                              </tr>
                              <?php $i++;?>
                              <?php $total_horas_50 += isset($datos_remuneracion['horasextras50'][$trabajador->id]) ? $datos_remuneracion['horasextras50'][$trabajador->id]*$valorhora50 : 0; ?>
                              <?php $total_horas_100 += isset($datos_remuneracion['horasextras100'][$trabajador->id]) ? $datos_remuneracion['horasextras100'][$trabajador->id]*$valorhora100 : 0; ?>
                              <?php } ?>
                            <?php }else{ ?>
                            <tr>
                              <td colspan="11">No existen trabajadores en la comunidad</td>
                            </tr>
                          <?php } ?>
                          </tbody>
                          <?php if(count($personal) > 0 ){ ?>
                            <tfoot>
                              <tr>
                                <th colspan="3">Totales</th>
                                <th>&nbsp;</th>
                                <!--th>&nbsp;</th>
                                <th>&nbsp;</th-->
                                <th>&nbsp;</th>
                                <th><span id="total_horas_50" class="input-sm"><?php echo number_format($total_horas_50,0,".","."); ?></span></th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th ><span id="total_horas_100" class="input-sm"><?php echo number_format($total_horas_100,0,".","."); ?></span></th>
                              </tr>
                            </tfoot> 
                          <?php } ?>
                          </table>
                    </div><!-- /.box-body -->
                    <?php if(count($personal) > 0 ){ ?>
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;
                      </div>
                    <?php } ?>
                  </div>
                </div>


            </div>

          </form>          
        </section><!-- /.content -->


<script>

$('.periodo').change(function(){

    $('#basicBootstrapForm').formValidation('revalidateField', 'anno');

      var cerrado = false;
      $.ajax({url: "<?php echo base_url();?>remuneraciones/get_status_rem/horas_extraordinarias/"+$('#mes').val()+"/"+$('#anno').val(),
        type: 'GET',
        async: false,
        success : function(data) {
            var_json = $.parseJSON(data);
            $('#span_status').html(var_json["label_text"]);
            $('#span_status').attr('class',"label "+var_json["label_style"]);     
            cerrado = var_json["status"] == 'cerrado' ? true : false;
        }});

      if(cerrado){
        $('input').attr('readonly',true);
      }else{
        $('input').attr('readonly',false);
      }


        $.get("<?php echo base_url();?>remuneraciones/get_datos_remuneracion/"+$('#mes').val()+"/"+$('#anno').val(),function(data){
                 // Limpiamos el select
                      var_json = $.parseJSON(data);
                      var total_horas_50 = 0;
                      var total_horas_100 = 0;
                      $(".horas50").each(
                          function(index,value){
                              var id_text = $(this).attr('id');
                              var array_field = id_text.split("_");
                              idtrabajador = array_field[1]; 

                              var horasextras50 =  typeof(var_json["horasextras50_"+idtrabajador]) != 'undefined' &&  var_json["horasextras50_"+idtrabajador] != null ? var_json["horasextras50_"+idtrabajador] : 0;
                              var horasextras100 =  typeof(var_json["horasextras100_"+idtrabajador]) != 'undefined' &&  var_json["horasextras100_"+idtrabajador] != null ? var_json["horasextras100_"+idtrabajador] : 0;
                              if(cerrado){
                                var valorhora =  typeof(var_json["valorhora_"+idtrabajador]) != 'undefined' &&  var_json["valorhora_"+idtrabajador] != null ? var_json["valorhora_"+idtrabajador] : 0;
                                var valorhora50 =  typeof(var_json["valorhorasextras50_"+idtrabajador]) != 'undefined' &&  var_json["valorhorasextras50_"+idtrabajador] != null ? var_json["valorhorasextras50_"+idtrabajador] : 0;
                                var valorhora100 =  typeof(var_json["valorhorasextras100_"+idtrabajador]) != 'undefined' &&  var_json["valorhorasextras100_"+idtrabajador] != null ? var_json["valorhorasextras100_"+idtrabajador] : 0;
                                var montohorasextras50 =  typeof(var_json["montohorasextras50_"+idtrabajador]) != 'undefined' && var_json["montohorasextras50_"+idtrabajador] != null ? var_json["montohorasextras50_"+idtrabajador] : 0;
                                var montohorasextras100 =  typeof(var_json["montohorasextras100_"+idtrabajador]) != 'undefined' && var_json["montohorasextras100_"+idtrabajador] != null ? var_json["montohorasextras100_"+idtrabajador] : 0;

                              }else{
                                var valorhora =  $('#montoactual_'+idtrabajador).val();
                                var valorhora50 =  $('#montoactual50_'+idtrabajador).val();
                                var valorhora100 =  $('#montoactual100_'+idtrabajador).val();

                                var montohorasextras50 = horasextras50*valorhora50;
                                var montohorasextras100 = horasextras100*valorhora100;
                              }

                              $('#spanvalorhora_'+idtrabajador).html(number_format(valorhora,0,'.','.'));
                              $('#valorhora_'+idtrabajador).val(valorhora);
                              $('#spanvalorhora50_'+idtrabajador).html(number_format(valorhora50,0,'.','.'));
                              $('#valorhora50_'+idtrabajador).val(valorhora50);
                              $('#spanvalorhora100_'+idtrabajador).html(number_format(valorhora100,0,'.','.'));
                              $('#valorhora100_'+idtrabajador).val(valorhora100);

                              $('#horas50_'+idtrabajador).val(horasextras50);
                              $('#monto50_'+idtrabajador).val(montohorasextras50);
                              $('#spanmonto50_'+idtrabajador).html(number_format(montohorasextras50,0,'.','.'));
                              $('#horas100_'+idtrabajador).val(horasextras100);
                              $('#monto100_'+idtrabajador).val(montohorasextras100);
                              $('#spanmonto100_'+idtrabajador).html(number_format(montohorasextras100,0,'.','.'));                            




                              total_horas_50 += parseFloat(montohorasextras50);
                              total_horas_100 += parseFloat(montohorasextras100);
                          }
                          
                      );  
                      $('#total_horas_50').html(number_format(total_horas_50,0,'.','.')); 
                      $('#total_horas_100').html(number_format(total_horas_100,0,'.','.'));                   
        });
      
});


$(document).ready(function() {
      var cerrado = false;
      $.ajax({url: "<?php echo base_url();?>remuneraciones/get_status_rem/horas_extraordinarias/"+$('#mes').val()+"/"+$('#anno').val(),
        type: 'GET',
        async: false,
        success : function(data) {
            var_json = $.parseJSON(data);
            $('#span_status').html(var_json["label_text"]);
            $('#span_status').attr('class',"label "+var_json["label_style"]);     
            cerrado = var_json["status"] == 'cerrado' ? true : false;
        }});


      if(cerrado){
        $('input').attr('readonly',true);

        $.get("<?php echo base_url();?>remuneraciones/get_datos_remuneracion/"+$('#mes').val()+"/"+$('#anno').val(),function(data){
                 // Limpiamos el select
                      var_json = $.parseJSON(data);
                      var total_horas_50 = 0;
                      var total_horas_100 = 0;
                      $(".horas50").each(
                          function(index,value){
                              var id_text = $(this).attr('id');
                              var array_field = id_text.split("_");
                              idtrabajador = array_field[1]; 

                              var horasextras50 =  typeof(var_json["horasextras50_"+idtrabajador]) != 'undefined' &&  var_json["horasextras50_"+idtrabajador] != null ? var_json["horasextras50_"+idtrabajador] : 0;
                              var horasextras100 =  typeof(var_json["horasextras100_"+idtrabajador]) != 'undefined' &&  var_json["horasextras100_"+idtrabajador] != null ? var_json["horasextras100_"+idtrabajador] : 0;
                              if(cerrado){
                                var valorhora =  typeof(var_json["valorhora_"+idtrabajador]) != 'undefined' &&  var_json["valorhora_"+idtrabajador] != null ? var_json["valorhora_"+idtrabajador] : 0;
                                var valorhora50 =  typeof(var_json["valorhorasextras50_"+idtrabajador]) != 'undefined' &&  var_json["valorhorasextras50_"+idtrabajador] != null ? var_json["valorhorasextras50_"+idtrabajador] : 0;
                                var valorhora100 =  typeof(var_json["valorhorasextras100_"+idtrabajador]) != 'undefined' &&  var_json["valorhorasextras100_"+idtrabajador] != null ? var_json["valorhorasextras100_"+idtrabajador] : 0;
                                var montohorasextras50 =  typeof(var_json["montohorasextras50_"+idtrabajador]) != 'undefined' && var_json["montohorasextras50_"+idtrabajador] != null ? var_json["montohorasextras50_"+idtrabajador] : 0;
                                var montohorasextras100 =  typeof(var_json["montohorasextras100_"+idtrabajador]) != 'undefined' && var_json["montohorasextras100_"+idtrabajador] != null ? var_json["montohorasextras100_"+idtrabajador] : 0;

                              }else{
                                var valorhora =  $('#montoactual_'+idtrabajador).val();
                                var valorhora50 =  $('#montoactual50_'+idtrabajador).val();
                                var valorhora100 =  $('#montoactual100_'+idtrabajador).val();

                                var montohorasextras50 = horasextras50*valorhora50;
                                var montohorasextras100 = horasextras100*valorhora100;
                              }

                              $('#spanvalorhora_'+idtrabajador).html(number_format(valorhora,0,'.','.'));
                              $('#valorhora_'+idtrabajador).val(valorhora);
                              $('#spanvalorhora50_'+idtrabajador).html(number_format(valorhora50,0,'.','.'));
                              $('#valorhora50_'+idtrabajador).val(valorhora50);
                              $('#spanvalorhora100_'+idtrabajador).html(number_format(valorhora100,0,'.','.'));
                              $('#valorhora100_'+idtrabajador).val(valorhora100);

                              $('#horas50_'+idtrabajador).val(horasextras50);
                              $('#monto50_'+idtrabajador).val(montohorasextras50);
                              $('#spanmonto50_'+idtrabajador).html(number_format(montohorasextras50,0,'.','.'));
                              $('#horas100_'+idtrabajador).val(horasextras100);
                              $('#monto100_'+idtrabajador).val(montohorasextras100);
                              $('#spanmonto100_'+idtrabajador).html(number_format(montohorasextras100,0,'.','.'));                            




                              total_horas_50 += parseFloat(montohorasextras50);
                              total_horas_100 += parseFloat(montohorasextras100);
                          }
                          
                      );  
                      $('#total_horas_50').html(number_format(total_horas_50,0,'.','.')); 
                      $('#total_horas_100').html(number_format(total_horas_100,0,'.','.'));                   
        });

      }else{
        $('input').attr('readonly',false);
      }



    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            lectura: {
                // The children's full name are inputs with class .childFullName
                selector: '.horas',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Informaci&oacute;n de Horas Extraordinarias es requerida'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Horas extraordinarias s&oacute;lo puede contener n&uacute;meros'
                    }                 
                },

            },
            anno: {
                row: '.form-group',
                validators: {

                    remote: {
                        url: '<?php echo base_url();?>remuneraciones/estado_periodo/',
                        // Send { email: 'its value', username: 'its value' } to the back-end
                        data: function(validator, $field, value) {
                            return {
                                mes: $('#mes').val()
                            };
                        },
                        message: 'Per&iacute;odo cerrado o no permitido para la comunidad ',
                        type: 'POST'
                    }
                },

            }
        }
    })
    .formValidation('revalidateField', 'anno');

});




$(".horas50").on('input',function(event){
    var id_text =  $(this).attr('id');
    var array_field = id_text.split("_");
    idtrabajador = array_field[1];
    var horas50 = $('#horas50_'+idtrabajador).val() == '' ? 0 : parseFloat($('#horas50_'+idtrabajador).val());
    var m_hora50 = Math.round(parseFloat($('#valorhora_'+idtrabajador).val())*1.5,0);

    var valor50 = horas50*m_hora50;

    $('#spanmonto50_'+idtrabajador).html(number_format(valor50,0,'.','.')); 
    $('#monto50_'+idtrabajador).val(number_format(valor50,0,'','')); 


    // SUMA DE HORAS 50
    var total_horas_50 = 0;
    $(".monto50").each(
        function(index,value){
          total_horas_50 += parseFloat($(this).val());
        }
        
    );   
    $('#total_horas_50').html(number_format(total_horas_50,0,'.','.')); 

});   


$(".horas100").on('input',function(event){
    var id_text =  $(this).attr('id');
    var array_field = id_text.split("_");
    idtrabajador = array_field[1];
    var horas100 = $('#horas100_'+idtrabajador).val() == '' ? 0 : parseFloat($('#horas100_'+idtrabajador).val());
    var m_hora100 = Math.round(parseFloat($('#valorhora_'+idtrabajador).val())*2,0);
    var valor100 = horas100*m_hora100;
    $('#spanmonto100_'+idtrabajador).html(number_format(valor100,0,'.','.')); 
    $('#monto100_'+idtrabajador).val(number_format(valor100,0,'','')); 

    // SUMA DE HORAS 100
    var total_horas_100 = 0;
    $(".monto100").each(
        function(index,value){
          total_horas_100 += parseFloat($(this).val());
        }
        
    );  
    $('#total_horas_100').html(number_format(total_horas_100,0,'.','.'));   

});   



$(document).ready(function(){
 $('.miles_decimales').mask('#.##0,00', {reverse: true})        

});

  $('.numeros').keypress(function(event){
    if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 46){
      event.preventDefault();
    } 
  })   

</script>


