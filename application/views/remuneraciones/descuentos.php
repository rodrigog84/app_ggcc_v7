        <!-- Main content -->
        <section class="content" >
         <?php if(isset($message)): ?>
         <div class="row">

                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            
          </div>  
          <?php endif; ?>
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/add_descuento" id="basicBootstrapForm" method="post"> 
            <div class="row">

                <div class="col-md-6">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">Per&iacute;odo&nbsp;&nbsp;</h3>  
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
                      <div class='row'>
                        <div class='col-md-3'>
                            <button type="submit" class="btn btn-success btn-block">Agregar</button>
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
                      <h3 class="box-title">Descuentos/Prestamos del Per&iacute;odo </h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                          <!--table  class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Rut</th>
                              <th>Nombre Trabajador</th>
                              <th>Tipo de descuento</th>
                              <th>Monto</th>
                            </tr>
                          </thead>
                          <tbody>
                            <div id="tabla_descuentos"></div>
                          </tbody>
                          </table-->
                          <div id="tabla_descuentos"></div>
                    </div><!-- /.box-body -->
                  </div>
                </div>


            </div>

          </form>          
        </section><!-- /.content -->


<script>

$('.periodo').change(function(){
    $('#basicBootstrapForm').formValidation('revalidateField', 'anno');
      $.get("<?php echo base_url();?>remuneraciones/get_datos_descuentos/"+$('#mes').val()+"/"+$('#anno').val(),function(data){
               // Limpiamos el select
                    var_json = $.parseJSON(data);
                    console.log(var_json);
                    var head = "<table  class='table table-bordered table-striped dt-responsive'> \
                          <thead> \
                            <tr> \
                              <th>#</th> \
                              <th>Rut</th> \
                              <th>Nombre Trabajador</th> \
                              <th>Tipo de descuento</th> \
                              <th>Monto</th> \
                              <th>&nbsp;</th> \
                            </tr> \
                          </thead> \
                          <tbody>";

                    var body = "<tr> \
                                <td colspan='5'>No existen descuentos/prestamos en el per&iacute;odo</td> \
                                </tr>";

                    $.each(var_json,function(i,item){
                      if(i == 0){
                        body = "";
                      }
                      var tipo_descuento = item.tipo == 'P' ? 'Pr&eacute;stamo' : "Descuento";



                      body += "<tr> \
                              <td>"+(i+1)+"</td> \
                              <td>"+number_format(item.rut,0,'.','.')+"-"+item.dv+"</td> \
                              <td>"+item.nombre+" "+item.apaterno+" "+item.amaterno+"</td> \
                              <td>"+tipo_descuento+"  | "+item.nombre_tipo+"</td> \
                              <td>$ "+number_format(item.monto,0,'.','.')+"</td> \
                              <td> \
                              <a href='<?php echo base_url(); ?>remuneraciones/ver_descuento/"+item.id+"' data-toggle='tooltip' title='Ver Descuento'><span class='glyphicon glyphicon-search'></span></a>&nbsp;&nbsp; \
                              <a href='<?php echo base_url(); ?>remuneraciones/edit_descuento/"+item.id+"' data-toggle='tooltip' title='Editar Descuento'><span class='glyphicon glyphicon-edit'></span></a>&nbsp;&nbsp; \
                              <a href='<?php echo base_url(); ?>remuneraciones/delete_descuento/"+item.id+"' data-toggle='tooltip' title='Eliminar Descuento'><span class='glyphicon glyphicon-trash'></span></a>&nbsp;&nbsp; \
                              </td> \
                              </tr>";
                    });

                    var foot = "</tbody></table>";

                    $("#tabla_descuentos").html(head+body+foot);

      });
      
});


$(document).ready(function() {

      $.get("<?php echo base_url();?>remuneraciones/get_datos_descuentos/"+$('#mes').val()+"/"+$('#anno').val(),function(data){
               // Limpiamos el select
                    var_json = $.parseJSON(data);
                    var head = "<table  class='table table-bordered table-striped dt-responsive'> \
                          <thead> \
                            <tr> \
                              <th>#</th> \
                              <th>Rut</th> \
                              <th>Nombre Trabajador</th> \
                              <th>Tipo de descuento</th> \
                              <th>Monto</th> \
                            </tr> \
                          </thead> \
                          <tbody>";

                    var body = "<tr> \
                                <td colspan='5'>No existen descuentos/prestamos en el per&iacute;odo</td> \
                                </tr>";

                    $.each(var_json,function(i,item){
                      if(i == 0){
                        body = "";
                      }
                      var tipo_descuento = item.tipo == 'P' ? 'Pr&eacute;stamo' : "Descuento";

                      body += "<tr> \
                              <td>"+(i+1)+"</td> \
                              <td>"+number_format(item.rut,0,'.','.')+"-"+item.dv+"</td> \
                              <td>"+item.nombre+" "+item.apaterno+" "+item.amaterno+"</td> \
                              <td>"+tipo_descuento+"  | "+item.nombre_tipo+"</td> \
                              <td>$ "+number_format(item.monto,0,'.','.')+"</td> \
                              <td> \
                              <a href='<?php echo base_url(); ?>remuneraciones/ver_descuento/"+item.id+"' data-toggle='tooltip' title='Ver Descuento'><span class='glyphicon glyphicon-search'></span></a>&nbsp;&nbsp; \
                              <a href='<?php echo base_url(); ?>remuneraciones/edit_descuento/"+item.id+"' data-toggle='tooltip' title='Editar Descuento'><span class='glyphicon glyphicon-edit'></span></a>&nbsp;&nbsp; \
                              <a href='<?php echo base_url(); ?>remuneraciones/delete_descuento/"+item.id+"' data-toggle='tooltip' title='Eliminar Descuento'><span class='glyphicon glyphicon-trash'></span></a>&nbsp;&nbsp; \
                              </td> \
                              </tr>";
                    });

                    var foot = "</tbody></table>";

                    $("#tabla_descuentos").html(head+body+foot);

      });
        
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
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
    .formValidation('revalidateField', 'anno')
});


</script>


