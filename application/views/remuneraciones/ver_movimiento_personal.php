        <!-- Main content -->
        <section class="content">

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


        <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/add_movimiento_personal/<?php echo $personal->id; ?>" id="basicBootstrapForm" method="post"> 
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
        </form>


          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header ">
                  <h3 class="box-title">Listado de Movimientos de <?php echo primera_mayuscula($personal->nombre)." ".primera_mayuscula($personal->apaterno)." ".primera_mayuscula($personal->amaterno); ?></h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Tipo Movimiento</th>
                      <th>Fecha Desde</th>
                      <th>Fecha Hasta</th>
                      <th>Comentario</th>
                      <th>Acci&oacute;n</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($movimientos) > 0){ ?>
                      <?php $i = 1; ?>
                      <?php foreach($movimientos as $movimiento){ ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $movimiento->movimiento;?></td>
                        <td><?php echo formato_fecha($movimiento->fecmovimiento,'Y-m-d','d/m/Y');?></td>
                        <td><?php echo $movimiento->rango == 1 ? formato_fecha($movimiento->fechastamovimiento,'Y-m-d','d/m/Y') : "-";?></td>
                        <td><?php echo $movimiento->comentario; ?></td>
                        <td>
                           <a href="<?php echo base_url();?>remuneraciones/add_movimiento_personal/<?php echo $personal->id;?>/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Editar Movimiento" ><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                           <a href="<?php echo base_url();?>remuneraciones/delete_movimiento_personal/<?php echo $personal->id;?>/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Eliminar Movimiento" ><span class="glyphicon glyphicon-trash"></span></a>                           
                        </td>                        
                      </tr>
                        <?php $i++; ?>
                      <?php } ?>
                    <?php }else{ ?>
                        <tr><td colspan="4">No existen movimientos para el trabajador</td></tr>
                    <?php } ?>
                  </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <a href="<?php echo base_url();?>remuneraciones/movimientos_personal" class="btn btn-default">Volver</a> 
                  </div>

              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->


<script>

$('.periodo').change(function(){
    $('#basicBootstrapForm').formValidation('revalidateField', 'anno');
});



$(document).ready(function() {
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
