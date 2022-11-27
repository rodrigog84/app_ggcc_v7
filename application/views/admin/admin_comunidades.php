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
              <a href="<?php echo base_url();?>admins/add_comunidad" type="submit" class="btn btn-primary">Agregar Comunidad</a>
          </div>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Listado de Comunidades</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre Comunidad</th>
                        <th>Rut</th>
                        <th>Direcci&oacute;n</th>
                        <th>Fono</th>
                        <th>Vencimiento Suscripci&oacute;n</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($comunidades) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($comunidades as $comunidad) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $comunidad->nombre;?></td>
                          <td><?php echo $comunidad->rut."-".$comunidad->dv;?></td>
                          <td><?php echo $comunidad->direccion;?></td>
                          <td><?php echo $comunidad->fono;?></td>   
                          <td><?php echo $comunidad->fecvencimiento;?></td>   
                          <td>
                          <a href="<?php echo base_url();?>admins/add_comunidad/<?php echo $comunidad->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;
                          <a href="<?php echo base_url();?>admins/delete_comunidad/<?php echo $comunidad->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
                          &nbsp;
                          &nbsp;
                          <?php if($comunidad->envios_pendientes > 0){ ?>
                          <a href="<?php echo base_url();?>admins/envio_masivo_mails/<?php echo $comunidad->id;?>" data-toggle="tooltip" title="Enviar <?php echo $comunidad->envios_pendientes; ?> Correos Pendientes" ><i class="fa fa-location-arrow"></i></a>                
                          &nbsp;
                          &nbsp;
                          <?php } ?>          
                          <a href="<?php echo base_url();?>admins/pay_account/<?php echo $comunidad->id;?>" data-toggle="tooltip" title="Registrar Pago" ><span class="fa fa-money"></span></a>                          
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
          "aLengthMenu" : [[10,50,100,-1],[10,50,100,'Todos']],
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