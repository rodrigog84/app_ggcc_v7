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
      <?php if(count($lista_prop_sin_confirmar) == 0 ){ ?>             
        <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/carga_propiedades" id="basicBootstrapForm" method="post"   enctype="multipart/form-data">
         <div class="row">

            <div class="col-md-6">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Carga Masiva de Propiedades</h3>
                  <div class="pull-right box-tools">

                    <h4><a href="<?php echo base_url(); ?>uploads/ejemploCarga.xls" data-toggle="tooltip" title="Ejemplo"><i class="fa fa-file-excel-o"></i></a></h4>
                  </div><!-- /. tools -->                                        
                </div><!-- /.box-header -->
                <!-- form start -->


                  <div class="box-body">
                    <?php if($comunidad_defecto == 0){ ?>
                        <div class="form-group">
                            <label for="comunidad">Comunidad</label>   
                            <select name="comunidad" id="comunidad"  class="form-control">
                                <option value="">Seleccione Comunidad</option>
                                <?php foreach ($comunidades as $comunidad) { ?>
                                  <option value="<?php echo $comunidad->id;?>"  ><?php echo $comunidad->nombre;?></option>
                                <?php } ?>
                            </select> 
                        </div>                        
                 <?php }else{ ?>
                      <input type="hidden" name="comunidad" value="<?php echo $comunidad_defecto; ?>">
                 <?php } ?>
                        <div class="form-group">
                              <label for="exampleInputFile">Archivo de Carga  - <a href="<?php echo base_url(); ?>uploads/ejemploCarga.xls" data-toggle="tooltip" title="Ejemplo">Descargar Ejemplo</a></label>
                              <input type="file" id="userfile" name="userfile">
                        </div>  
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success" name="cargar">Cargar</button>
                    <input type="hidden" name="tipo" value="validacion">

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_propiedad" class="btn btn-default">Volver</a>
                  </div>                  
              </div><!-- /.box -->

            </div>

        
          </div>

      </form>
      <?php } ?>
       <?php if(count($lista_prop_sin_confirmar) > 0 ){ ?>
          <br>
               
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Previsualizaci&oacute; Datos</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>Nro. Propiedad</th>
                        <th>Direcci&oacute;n</th>
                        <th>Nombre Responsable</th>
                        <th>Apellido Responsable</th>
                        <th>Email</th>
                        <th>Fono</th>
                        <th>Prorrateo</th>
                        <th>Saldo Inicial</th>
                        <th>Suscrito Mail</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($lista_prop_sin_confirmar as $propiedad) { ?>
                         <tr >
                          <td><?php echo $propiedad['propiedad'];?></td>
                          <td><?php echo $propiedad['direccion'];?></td>
                          <td><?php echo $propiedad['nombre_responsable'];?></td>
                          <td><?php echo $propiedad['apellido_responsable'];?></td>
                          <td><?php echo $propiedad['email'];?></td>
                          <td><?php echo $propiedad['fono'];?></td>
                          <td><?php echo round($propiedad['prorrateo'],3)."%";?></td>
                          <td>$&nbsp;<?php echo number_format($propiedad['saldo'],0,".",".");?></td>
                          <td><?php echo $propiedad['suscrito'];?></td>
                        </tr>
                        <?php $i++;?>
                        <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="#" data-href="<?php echo base_url(); ?>admins/confirma_carga_propiedades/<?php echo $comunidad_defecto; ?>" title="Crear Propiedades" class="btn btn-success" data-toggle="modal" data-target="#confirm-publish">Confirmar Carga</a>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/carga_propiedades" class="btn btn-default">Volver</a>
                  </div>                  
                </div>
              </div>

            
          </div>
          <?php } ?>


          <?php if(!is_null($lista_propiedades)){ ?>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Propiedades Agregadas</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nro. Propiedad</th>
                        <th>Responsable</th>
                        <th>Email</th>
                        <th>Prorrateo</th>
                        <th>Saldo Inicial</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($lista_propiedades as $propiedad) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $propiedad['numpropiedad'];?></td>
                          <td><?php echo $propiedad['responsable'];?></td>
                          <td><?php echo $propiedad['email'];?></td>
                          <td><?php echo round($propiedad['prorrateo'],3)."%";?></td>
                          <td>$&nbsp;<?php echo number_format($propiedad['saldo'],0,".",".");?></td>
                        </tr>
                        <?php $i++;?>
                        <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                </div>
              </div>

            
          </div>
          <?php } ?>
         

          <?php if(!is_null($lista_usuarios)){ ?>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Usuarios Creados</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($lista_usuarios as $usuario) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $usuario['nombre'];?></td>
                          <td><?php echo $usuario['apellido'];?></td>
                          <td><?php echo $usuario['email'];?></td>
                        </tr>
                        <?php $i++;?>
                        <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                </div>
              </div>

            
          </div>
          <?php } ?>


          
        </section><!-- /.content -->
  <div class="modal fade" id="confirm-publish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmar Carga</h4>
                </div>
            
                <div class="modal-body">
                    <p>Se cargar&aacute;n las propiedades tal como se muestran en la tabla.&nbsp;&nbsp;Una vez cargado, no se podr&aacute; podr&aacute; reversar la operaci&oacute;n, y cualquier cambio se deber&aacute; realizar uno a uno.</p>
                    <p>Desea continuar?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-success btn-ok" id="botoncrear">Crear Propiedades</a>
                </div>
            </div>
        </div>
    </div>
 <script>
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
            comunidad: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Comunidad es requerida'
                    },
                }
            },
            userfile: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Archivo de Carga'
                    }              
                }
            }, 
        }
    })
});
</script>

<script>
      $(function () {
        $('.table').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[5,10,50,100,-1],[5,10,50,100,'Todos']],
          "iDisplayLength": 5,
          "oLanguage": {
              "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
              "sZeroRecords": "No se encontraron registros",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
              "sInfoEmpty": "Mostrando 0 de 0 registros",
              "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
              "sSearch":        "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":    "Último",
                "sNext":    "Siguiente",
                "sPrevious": "Anterior"
            }              
          }          
        });
      });
</script>   

   <script>
        $('#confirm-publish').on('show.bs.modal', function(e) {

            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
        });

        $('#botoncrear').on('click',function(){
            $(this).attr('disabled','disabled');

        })
    </script>
