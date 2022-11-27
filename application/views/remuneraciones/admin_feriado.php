        <!-- Main content -->
        <section class="content" >
          <?php if(isset($message)): ?>
            <div class="col-md-12">
              <div class="row">

                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
              </div>
            </div>
          <?php endif; ?>
        
          <div>
              <a href="<?php echo base_url();?>remuneraciones/add_feriado" type="submit" class="btn btn-primary">Agregar Feriado</a>
          </div>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Listado de Feriados</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Fecha Feriado</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($feriados) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($feriados as $feriado) { ?>
                           <tr >
                            <td><?php echo $i ;?></td>
                            <td><?php echo $feriado->fecha;?></td>
                            <td>
                            <a href="<?php echo base_url();?>remuneraciones/add_feriado/<?php echo $feriado->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                            &nbsp;
                            &nbsp;
                            <a href="<?php echo base_url();?>remuneraciones/delete_feriado/<?php echo $feriado->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
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
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[15,30,45,100,-1],[15,30,45,100,'All']],
          "iDisplayLength": 15,
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