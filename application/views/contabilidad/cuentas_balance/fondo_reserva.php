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
                            <th>Nro. Transacci&oacute;n</th>
                            <th>Monto</th>
                            <!--th>Saldo</th-->                          
                          </tr>
                        </thead>
                        <tbody>
                          <?php $monto_total = 0; ?>
                          <?php if(count($detalle_cuenta[0]) > 0 ){ ?>
                            <?php $saldo_fr = $detalle_cuenta[1]['saldo_fr']; ?>
                            <?php foreach ($detalle_cuenta[0] as $detalle) { ?>
                             <tr >
                              <td><?php echo $detalle->fecha;?></td>
                              <td><?php echo $detalle->glosa;?></td>
                              <td><?php echo trackid($detalle->id);?></td>
                              <td><?php echo number_format($detalle->monto,0,".",".");?></td>                              
                              <!--td><?php echo number_format($saldo_fr,0,".",".");?></td-->                              
                            </tr>
                            <?php $monto_total += $detalle->monto; ?>
                            <?php $saldo_fr -= $detalle->monto; ?>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                          <th>Total Movimientos Per&iacute;odo</th>
                          <th colspan="2">&nbsp;</th>
                          <th><?php echo "$ ".number_format($monto_total,0,".","."); ?></th>
                          </tr>
                          <tr>
                          <th>Saldo Anterior</th>
                          <th colspan="2">&nbsp;</th>
                          <th><?php echo "$ ".number_format($saldo_fr,0,".","."); ?></th>
                          </tr>  
                          <tr>
                          <th>Total</th>
                          <th colspan="2">&nbsp;</th>
                          <th><?php echo "$ ".number_format($monto_total+$saldo_fr,0,".","."); ?></th>
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