        <!-- Main content -->
        <section class="content" >
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Detalle de Lectura Individual</h3> 
                  <div class="pull-right box-tools">
                    <h4><a href="<?php echo base_url(); ?>reports/export_detalle_lectura/<?php echo $idcuenta; ?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                  </div><!-- /. tools -->                      

                   
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Nro. Propiedad</th>
                      <th>Concepto</th>
                      <th>Fecha Vencimiento</th>
                      <th>Per&iacute;odo Cobro</th>
                      <th>Lectura Actual</th>
                      <th>Lectura Anterior</th>
                      <th>Consumo</th>
                      <th>Monto</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($detalle_lectura) > 0 ){ ?>
                      <?php foreach ($detalle_lectura as $detalle) { ?>
                       <tr >
                        <td><?php echo $detalle->numero;?></td>
                        <td><?php echo $detalle->concepto;?></td>
                        <td><?php echo $detalle->fechadeuda;?></td>
                        <td><?php echo date2string($detalle->mes,$detalle->anno); ?></td>
                        <td><?php echo $detalle->valor;?></td>
                        <td><?php echo $detalle->valor_ant;?></td>
                        <td><?php echo number_format($detalle->consumo,2,'.','');?></td>
                        <td><?php echo number_format($detalle->monto,0,".",".");?></td>
                        <td>
                        <a href="<?php echo base_url(); ?>reports/ver_lectura_individual/<?php echo $detalle->id; ?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                        </td>
                      </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>

                  </table>
                </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo $mensual_data ? base_url().'reports/mensual_data' :   'javascript:history.back(1)'; ?>" class="btn btn-default">Volver</a>
                  </div>                
              </div>
            </div>
          </div>
        
        </section><!-- /.content -->


  <script>
      $(function () {
        $('.table').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,50,100,-1],[10,50,100,'All']],
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