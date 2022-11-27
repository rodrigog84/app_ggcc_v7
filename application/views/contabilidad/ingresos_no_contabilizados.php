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
              <a href="<?php echo base_url();?>contabilidad/add_ingreso_no_contabilizado" type="submit" class="btn btn-primary">Agregar Ingreso</a>
          </div>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Listado de Ingresos No Identificados</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Descripci&oacute;n</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha Eliminaci&oacute;n</th>
                        <?php if($this->session->userdata('level') == 1){ ?>
                        <th>&nbsp;</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($ingresos) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($ingresos as $ingreso) { ?>
                        <?php if($ingreso->estado == 'Activo'){ ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $ingreso->fechaingreso;?></td>
                          <td><?php echo $ingreso->descripcion;?></td>
                          <td>$&nbsp;&nbsp;<?php echo number_format($ingreso->monto,0,".",".");?></td>
                          <td><span class="label <?php echo $ingreso->estado == 'Activo' ? 'label-success' : 'label-danger'; ?>"><?php echo $ingreso->estado?></span></td>
                          <td><?php echo $ingreso->fechaelimina?></td>
                          <?php if($this->session->userdata('level') == 1){ ?>
                          <td>
                          <?php if($ingreso->estado == 'Activo'){ ?>
                          <a href="<?php echo base_url();?>contabilidad/delete_ingreso_no_contabilizado/<?php echo $ingreso->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
                          <?php }else{ ?>
                            <span class="fa fa-ban text-red"></span>
                          <?php } ?>
                          </td>
                          <?php } ?>
                        </tr>
                        <?php $i++;?>
                          <?php } ?>
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