        <!-- Main content -->
        <section class="content" >

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title"><?php echo $cuenta->nombre;?>&nbsp;-&nbsp;<?php echo date2string($balance->mes,$balance->anno); ?></h3>  
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th>Fecha</th>
                            <th>Descripci&oacute;n</th>
                            <th>Acci&oacute;n</th>
                            <th>Fecha Eliminaci&oacute;n</th>
                            <th>Monto</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $monto_total = 0; ?>
                          <?php if(count($detalle_cuenta[0]) > 0 ){ ?>
                            <?php foreach ($detalle_cuenta[0] as $detalle) { ?>
                             <tr >
                              <td><?php echo $detalle->fechaingreso;?></td>
                              <td><?php echo $detalle->descripcion;?></td>
                              <td><span class="label label-success">Ingreso</span></td>
                              <td>&nbsp;</td>
                              <td><?php echo "$ ".number_format($detalle->monto,0,".",".");?></td>
                            </tr>
                            <?php $monto_total += $detalle->monto; ?>
                            <?php } ?>
                          <?php } ?>

                          <?php if(count($detalle_cuenta[1]) > 0 ){ ?>
                            <?php foreach ($detalle_cuenta[1] as $detalle) { ?>
                             <tr >
                              <td><?php echo $detalle->fechaingreso;?></td>
                              <td><?php echo $detalle->descripcion;?></td>
                              <td><span class="label label-danger">Eliminaci&oacute;n</span></td>
                              <td><?php echo $detalle->fechaelimina?></td>
                              <td><?php echo "$ -".number_format($detalle->monto,0,".",".");?></td>
                            </tr>
                            <?php $monto_total -= $detalle->monto; ?>
                            <?php } ?>
                          <?php } ?>                          
                        </tbody>
                        <tfoot>
                          <tr>
                          <th>Subtotal</th>
                          <th colspan="3">&nbsp;</th>
                          <th><?php echo "$ ".number_format($monto_total,0,".","."); ?></th>
                          </tr>
                          <tr>
                          <th>Saldo Anterior</th>
                          <th colspan="3">&nbsp;</th>
                          <th><?php echo "$ ".number_format($detalle_cuenta[2]->valor,0,".","."); ?></th>
                          </tr> 
                          <tr>
                          <th>Total</th>
                          <th colspan="3">&nbsp;</th>
                          <th><?php echo "$ ".number_format($detalle_cuenta[2]->valor+$monto_total,0,".","."); ?></th>
                          </tr>                                                     
                        </tfoot>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <a href="<?php echo base_url();?>contabilidad/ver_balance/<?php echo $balance->idperiodo;?>" class="btn btn-default">Volver</a>
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