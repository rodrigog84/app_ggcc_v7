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
          <div>
              <a href="<?php echo base_url();?>admins/add_proveedor" type="submit" class="btn btn-primary">Agregar Proveedor</a>
          </div>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Listado de Proveedores de Comunidad</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre Proveedor</th>
                        <th>Rut</th>
                        <th>Direcci&oacute;n</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($proveedores_comunidad) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($proveedores_comunidad as $proveedor_comunidad) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $proveedor_comunidad->nombre;?></td>
                          <td><?php echo $proveedor_comunidad->rut == '' ? '' : $proveedor_comunidad->rut."-".$proveedor_comunidad->dv;?></td>
                          <td><?php echo $proveedor_comunidad->direccion;?></td>
                          <td>
                          <a href="<?php echo base_url();?>admins/add_proveedor/<?php echo $proveedor_comunidad->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;
                          <a href="<?php echo base_url();?>admins/delete_proveedor/<?php echo $proveedor_comunidad->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
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