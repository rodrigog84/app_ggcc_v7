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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_anticipos" id="basicBootstrapForm" method="post"> 
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
                      <h3 class="box-title">Ingreso de Anticipos</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                          <table  class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Rut</th>
                              <th>Nombre Trabajador</th>
                              <th>Sueldo Base</th>
                              <th>Anticipo</th>
                              <th>Aguinaldo</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(count($personal) > 0 ){ ?>
                              <?php $i = 1; ?>
                              <?php $total_anticipo = 0; ?>
                              <?php $total_aguinaldo = 0; ?>
                              <?php foreach ($personal as $trabajador) { ?>

                               <tr >
                                <td><?php echo $i ;?></td>
                                <td><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut,0,".",".")."-".$trabajador->dv;?></td>
                                <td><?php echo $trabajador->nombre." ".$trabajador->apaterno." ".$trabajador->amaterno;?></td>
                                <td>
                                  <?php echo number_format($trabajador->sueldobase,0,".",".");?>
                                  <input type="hidden" name="sueldobase_<?php echo $trabajador->id;?>" id="sueldobase_<?php echo $trabajador->id;?>"  value="<?php echo $trabajador->sueldobase; ?>"  />
                                </td>
                                <td class="form-group">
                                    <input type="text" name="anticipo_<?php echo $trabajador->id;?>" id="anticipo_<?php echo $trabajador->id;?>" class="anticipo miles" value="<?php echo isset($datos_remuneracion['anticipo'][$trabajador->id]) ? $datos_remuneracion['anticipo'][$trabajador->id] : 0; ?>"  />   
                                </td>
                                <td class="form-group">
                                    <input type="text" name="aguinaldo_<?php echo $trabajador->id;?>" id="aguinaldo_<?php echo $trabajador->id;?>" class="aguinaldo miles" value="<?php echo isset($datos_remuneracion['aguinaldo'][$trabajador->id]) ? $datos_remuneracion['aguinaldo'][$trabajador->id] : 0; ?>"  />   
                                </td>                                
                              </tr>
                              <?php $i++;?>
                              <?php $total_anticipo += isset($datos_remuneracion['anticipo'][$trabajador->id]) ? $datos_remuneracion['anticipo'][$trabajador->id] : 0; ?>
                              <?php $total_aguinaldo += isset($datos_remuneracion['aguinaldo'][$trabajador->id]) ? $datos_remuneracion['aguinaldo'][$trabajador->id] : 0; ?>
                              <?php } ?>
                            <?php }else{ ?>
                            <tr>
                              <td colspan="6">No existen trabajadores en la comunidad</td>
                            </tr>
                          <?php } ?>
                          </tbody>
                          <?php if(count($personal) > 0 ){ ?>
                          <tfoot>
                            <tr>
                              <th colspan="4">Totales</th>
                              <th><span id="total_anticipo" ><?php echo number_format($total_anticipo,0,".","."); ?></span></th>
                              <th><span id="total_aguinaldo"><?php echo number_format($total_aguinaldo,0,".","."); ?></span></th>
                            </tr>
                          </tfoot>                           
                          <?php } ?>
                          </table>
                    </div><!-- /.box-body -->
                    <?php if(count($personal) > 0 ){ ?>
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo base_url();?>remuneraciones/traspasa_anticipos" class="btn btn-success" id="traspaso">Traspasar Datos</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo base_url();?>remuneraciones/reversa_anticipos" class="btn btn-danger" id="reversa">Reversa Traspaso</a>&nbsp;&nbsp;
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

      $('#traspaso').attr('href',"<?php echo base_url();?>remuneraciones/traspasa_anticipos/"+$('#mes').val()+"/"+$('#anno').val());
      $('#reversa').attr('href',"<?php echo base_url();?>remuneraciones/reversa_anticipos/"+$('#mes').val()+"/"+$('#anno').val());
      $.ajax({url: "<?php echo base_url();?>remuneraciones/get_status_rem/anticipos/"+$('#mes').val()+"/"+$('#anno').val(),
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
        $('#traspaso').addClass('disabled');
        if(var_json["estado"] == 3){
        	$('#reversa').removeClass('disabled');	
        }else{
        	$('#reversa').addClass('disabled');	
        }
        
      }else{
        $('input').attr('readonly',false);

        if(var_json["status"] == 'pendiente' || var_json["status"] == 'nuevo'){
          $('#traspaso').addClass('disabled');
          $('#reversa').addClass('disabled');
        }else{
          $('#traspaso').removeClass('disabled');
          $('#reversa').addClass('disabled')
          }
      } 

      $.get("<?php echo base_url();?>remuneraciones/get_datos_remuneracion/"+$('#mes').val()+"/"+$('#anno').val(),function(data){
               // Limpiamos el select
                    var_json = $.parseJSON(data);
                    total_anticipo = 0;
                    total_aguinaldo = 0;
                    
                    $(".anticipo").each(
                        function(index,value){
                            var id_text = $(this).attr('id');
                            var array_field = id_text.split("_");
                            idtrabajador = array_field[1];  
                            var anticipo =  typeof(var_json["anticipo_"+idtrabajador]) != 'undefined' &&  var_json["anticipo_"+idtrabajador] != null ? var_json["anticipo_"+idtrabajador] : 0;
                            var aguinaldo =  typeof(var_json["aguinaldo_"+idtrabajador]) != 'undefined' &&  var_json["aguinaldo_"+idtrabajador] != null ? var_json["aguinaldo_"+idtrabajador] : 0;
                            $('#anticipo_'+idtrabajador).val(number_format(anticipo,0,'.','.'));
                            $('#aguinaldo_'+idtrabajador).val(number_format(aguinaldo,0,'.','.'));
                            total_anticipo += parseInt(anticipo);
                            total_aguinaldo += parseInt(aguinaldo);
                        }
                        
                    );                    

                    $('#total_anticipo').html(number_format(total_anticipo,0,'.','.')); 
                    $('#total_aguinaldo').html(number_format(total_aguinaldo,0,'.','.')); 
      });
      
});


$(document).ready(function() {

      var cerrado = false;

      $('#traspaso').attr('href',"<?php echo base_url();?>remuneraciones/traspasa_anticipos/"+$('#mes').val()+"/"+$('#anno').val());
      $('#reversa').attr('href',"<?php echo base_url();?>remuneraciones/reversa_anticipos/"+$('#mes').val()+"/"+$('#anno').val());
      $.ajax({url: "<?php echo base_url();?>remuneraciones/get_status_rem/anticipos/"+$('#mes').val()+"/"+$('#anno').val(),
        type: 'GET',
        async: false,
        success : function(data) {
            var_json = $.parseJSON(data);
           // console.log(var_json)
            $('#span_status').html(var_json["label_text"]);
            $('#span_status').attr('class',"label "+var_json["label_style"]);     
            cerrado = var_json["status"] == 'cerrado' ? true : false;
        }});



      if(cerrado){
        $('input').attr('readonly',true);
        $('#traspaso').addClass('disabled');
        if(var_json["estado"] == 3){
        	$('#reversa').removeClass('disabled');	
        }else{
        	$('#reversa').addClass('disabled');	
        }
        
      }else{
        $('input').attr('readonly',false);
      //  console.log(var_json["status"]);
        if(var_json["status"] == 'pendiente' || var_json["status"] == 'nuevo'){
          $('#traspaso').addClass('disabled');
          $('#reversa').addClass('disabled');
        }else{
          $('#traspaso').removeClass('disabled');
          $('#reversa').addClass('disabled')
          }
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
            anticipo: {
                // The children's full name are inputs with class .childFullName
                selector: '.anticipo',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Informaci&oacute;n de Anticipo es requerida'
                    },
                    callback: {
                        message: 'Anticipo debe ser menor a sueldo base',
                        callback: function (value, validator, $field) {
                            var id_text = $field.attr('id');
                            var array_field = id_text.split("_");
                            idtrabajador = array_field[1];
                            var sueldobase = $('#sueldobase_'+idtrabajador).val() == '' ? 0 : parseInt($('#sueldobase_'+idtrabajador).val());
                            var anticipo = $('#anticipo_'+idtrabajador).val() == '' ? "0" : $('#anticipo_'+idtrabajador).val();                            


                            anticipo = parseInt(replaceAll(anticipo,".",""));
                            if(anticipo < sueldobase){
                              return true;
                            }else{
                              return  {
                                    valid: false,
                                    message: 'Anticipo debe ser menor a sueldo base'
                                }
                            }
                        }
                    }                    

                },

            },
            anno: {
                row: '.form-group',
                validators: {

                    remote: {
                        url: '<?php echo base_url();?>remuneraciones/estado_periodo/anticipo',
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
    .formValidation('revalidateField', 'anno')
});


$(document).ready(function(){
 $('.miles').mask('000.000.000.000.000', {reverse: true})        

});


$(".anticipo").on('input',function(event){

    // SUMA DE ANTICIPO
    var total_anticipo = 0;
    $(".anticipo").each(
        function(index,value){
          total_anticipo += parseFloat(replaceAll($(this).val(),".",""));
        }
        
    );   
    $('#total_anticipo').html(number_format(total_anticipo,0,'.','.')); 

});   


$(".aguinaldo").on('input',function(event){
    // SUMA DE ANTICIPO
    var total_aguinaldo = 0;
    $(".aguinaldo").each(
        function(index,value){
          total_aguinaldo += parseFloat(replaceAll($(this).val(),".",""));
        }
        
    );   
    $('#total_aguinaldo').html(number_format(total_aguinaldo,0,'.','.')); 

});   


</script>


