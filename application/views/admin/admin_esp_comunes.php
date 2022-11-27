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
          <div>
              <a href="<?php echo base_url();?>admins/add_esp_comun" type="submit" class="btn btn-primary">Agregar Espacio Com&uacute;n</a>
          </div>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Listado de Espacios Comunes de Comunidad</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre Espacio Com&uacute;n</th>
                        <th>Unidad Medida Uso</th>
                        <th>Valor Unidad Medida ($)</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($espacios_comunes) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($espacios_comunes as $espacio_comun) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $espacio_comun->nombre;?></td>
                          <td><?php echo $espacio_comun->unidadmedida;?></td>
                          <td><?php echo number_format($espacio_comun->monto,0,".",".");?></td>
                          <td>
                          <a href="<?php echo base_url();?>admins/add_esp_comun/<?php echo $espacio_comun->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;
                          <a href="<?php echo base_url();?>admins/delete_esp_comun/<?php echo $espacio_comun->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
                          </td>
                        </tr>
                        <?php $i++;?>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                </div>
              </div>

            
          </div>
        </section><!-- /.content -->


<script>
      $(function () {
        $('#listado').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,20,30,45,100,-1],[10,20,30,45,100,'All']],
          "iDisplayLength": 10,
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