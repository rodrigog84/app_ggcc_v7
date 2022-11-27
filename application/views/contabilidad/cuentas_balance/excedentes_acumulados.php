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
                            <th>Propiedad</th>
                            <th>Responsable</th>
                            <th>Prorrateo</th>
                            <th>Deuda</th>
                            <th>Fondo Reserva</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $monto_deuda = 0; ?>
                          <?php $monto_fr = 0; ?>
                          <?php $monto_total = 0; ?>
                          <?php if(count($detalle_cuenta[0]) > 0 ){ ?>
                            <?php foreach ($detalle_cuenta[0] as $detalle) { ?>
                             <tr >
                              <td><?php echo $detalle->numero;?></td>
                              <td><?php echo $detalle->responsable;?></td>
                              <td><?php echo $detalle->prorrateo;?>&nbsp;<span style="font-size: 10px">%</span></td>
                              <td><?php echo "$ ".number_format($detalle->deuda,0,".",".");?></td>
                              <td><?php echo "$ ".number_format($detalle->fr,0,".",".");?></td>
                              <td><?php echo "$ ".number_format($detalle->deuda+$detalle->fr,0,".",".");?></td>
                            </tr>
                            <?php $monto_deuda += $detalle->deuda; ?>
                            <?php $monto_fr += $detalle->fr; ?>
                            <?php $monto_total += $detalle->deuda + $detalle->fr; ?>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="2">Gastos Comunes por cobrar del Mes</th>
                            <th >&nbsp;</th>
                            <th><?php echo "$ &nbsp;".number_format($monto_deuda,0,".","."); ?></th>
                            <th><?php echo "$ &nbsp;".number_format($monto_fr,0,".","."); ?></th>
                            <th><?php echo "$ &nbsp;".number_format($monto_total,0,".","."); ?></th>
                          </tr>
                          <tr>
                            <th colspan="2">Total Prorrateado</th>
                            <th >&nbsp;</th>
                            <th><?php echo "$ -".number_format($detalle_cuenta[1]['deuda'],0,".","."); ?></th>
                            <th><?php echo "$ -".number_format($detalle_cuenta[1]['fr'],0,".","."); ?></th>
                            <th><?php echo "$ - ".number_format($detalle_cuenta[1]['deuda']+$detalle_cuenta[1]['fr'],0,".","."); ?></th>
                          </tr>
                          <tr>
                            <th colspan="2">Excedentes del Mes</th>
                            <th >&nbsp;</th>
                            <th><?php echo "$  ".number_format($monto_deuda - $detalle_cuenta[1]['deuda'],0,".","."); ?></th>
                            <th><?php echo "$  ".number_format($monto_fr - $detalle_cuenta[1]['fr'],0,".","."); ?></th>
                            <th><?php echo "$ &nbsp;".number_format($monto_total - ($detalle_cuenta[1]['deuda']+$detalle_cuenta[1]['fr']),0,".","."); ?></th>
                          </tr>
                          <tr>
                            <th colspan="2">Saldo Anterior</th>
                            <th >&nbsp;</th>
                            <th >&nbsp;</th>
                            <th >&nbsp;</th>
                            <th><?php echo "$ &nbsp;".number_format($detalle_cuenta[2]->valor,0,".","."); ?></th>
                          </tr>                          
                          <tr>
                            <th colspan="2">Total</th>
                            <th >&nbsp;</th>
                            <th >&nbsp;</th>
                            <th >&nbsp;</th>
                            <th><?php echo "$ &nbsp;".number_format($detalle_cuenta[2]->valor+($monto_total - ($detalle_cuenta[1]['deuda']+$detalle_cuenta[1]['fr'])),0,".","."); ?></th>
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