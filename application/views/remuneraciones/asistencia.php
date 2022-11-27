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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_asistencia" id="basicBootstrapForm" method="post"> 
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
                      <h3 class="box-title">Ingreso de Asistencia</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                          <table  class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th >#</th>
                              <th >Rut</th>
                              <th >Nombre Trabajador</th>
                              <th >D&iacute;as a Trabajar</th>
                              <th >Dias Trabajados</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(count($personal) > 0 ){ ?>
                              <?php $i = 1; ?>
                              <?php foreach ($personal as $trabajador) { ?>

                               <tr >
                                <td><?php echo $i ;?></td>
                                <td><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut,0,".",".")."-".$trabajador->dv;?></td>
                                <td><?php echo $trabajador->nombre." ".$trabajador->apaterno." ".$trabajador->amaterno;?></td>
                                <td>
                                    <b><span id="diasatrabajar_<?php echo $trabajador->id;?>"  class="text-right" ><?php echo $trabajador->diastrabajo;?></span></b>   
                                </td>
                                <td class="form-group">
                                  <input type="text" name="diastrabajo_<?php echo $trabajador->id;?>" id="diastrabajo_<?php echo $trabajador->id;?>" class="diastrabajo" value="<?php echo isset($datos_remuneracion[$trabajador->id]) ? $datos_remuneracion[$trabajador->id] : $trabajador->diastrabajo; ?>"  />   
                                </td>
                              </tr>
                              <?php $i++;?>
                              <?php } ?>
                            <?php }else{ ?>
                            <tr>
                              <td colspan="4">No existen trabajadores en la comunidad</td>
                            </tr>
                          <?php } ?>
                          </tbody>
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
      $.ajax({url: "<?php echo base_url();?>remuneraciones/get_status_rem/asistencia/"+$('#mes').val()+"/"+$('#anno').val(),
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
                    $(".diastrabajo").each(
                        function(index,value){
                            var id_text = $(this).attr('id');
                            var array_field = id_text.split("_");
                            idtrabajador = array_field[1];  

                            var diastrabajo =  typeof(var_json["diastrabajo_"+idtrabajador]) != 'undefined' && var_json["diastrabajo_"+idtrabajador] != null ? var_json["diastrabajo_"+idtrabajador] : parseInt($('#diasatrabajar_'+idtrabajador).html());
                            $(this).val(diastrabajo);
                        }
                        
                    );                    
      });
      
});


$(document).ready(function() {

      var cerrado = false;
      $.ajax({url: "<?php echo base_url();?>remuneraciones/get_status_rem/asistencia/"+$('#mes').val()+"/"+$('#anno').val(),
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
                selector: '.diastrabajo',
                // The field is placed inside .col-xs-6 div instead of .form-group
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Informaci&oacute;n de Asistencia es requerida'
                    },
                    integer: {
                        separator: '.',
                        message: 'Asistencia s&oacute;lo puede contener n&uacute;meros'
                    },
                    callback: {
                        message: 'Asistencia debe ser menor o igual a d&iacute;as a trabajar',
                        callback: function (value, validator, $field) {
                            var id_text = $field.attr('id');
                            var array_field = id_text.split("_");
                            idtrabajador = array_field[1];
                            var asistencia_trabajador = $('#diasatrabajar_'+idtrabajador).html() == '' ? 0 : parseInt($('#diasatrabajar_'+idtrabajador).html());
                            var asistencia_actual = $('#diastrabajo_'+idtrabajador).val() == '' ? 0 : parseInt($('#diastrabajo_'+idtrabajador).val());                            
                            if(asistencia_actual <= asistencia_trabajador){
                              return true;
                            }else{
                              return  {
                                    valid: false,
                                    message: 'Asistencia debe ser menor o igual a d&iacute;as a trabajar'
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

</script>


