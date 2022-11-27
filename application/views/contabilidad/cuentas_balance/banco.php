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
                            <th><small>Fecha</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Nro. Transacci&oacute;n</small></th>
                            <th><small>Estado Conciliaci&oacute;n</small></th>
                            <th><small>Fecha Cobro</small></th>  
                            <th><small>Monto</small></th>
                            <!--th>Saldo Contable</th-->                            
                          </tr>
                        </thead>
                        <tbody>
                        <?php $monto_total = 0; ?>
                          <?php if(count($detalle_cuenta[0]) > 0 ){ ?>
                          <?php $saldo_caja = $detalle_cuenta[1]['saldo_contable']; ?>
                            <?php foreach ($detalle_cuenta[0] as $detalle) { ?>
                             <tr >
                              <td><small><?php echo $detalle->fechapago_format;?></small></td>
                              <td><small><?php echo $detalle->glosa;?></small></td>
                              <td><small><?php echo trackid($detalle->folio);?></small></td>
                              <td>
                                <?php 
                                    $estado = "";
                                    $class = "";
                                    if($detalle->activo == 1){
                                      if(is_null($detalle->fechaconciliacion)){ 
                                        $estado = "Pendiente Conciliaci&oacute;n";
                                        $class = "text-red";
                                        $delete_movimiento = "block";
                                      }else{

                                          $fecha_actual = strtotime(date("Y-m-d"));
                                          $fechaconciliacion = substr($detalle->fechaconciliacion,6,4)."-".substr($detalle->fechaconciliacion,3,2)."-".substr($detalle->fechaconciliacion,0,2);
                                          $fecha_entrada = strtotime($fechaconciliacion);
                                          if($fecha_actual < $fecha_entrada){
                                              $estado = "Pendiente Cobro";
                                              $class = "text-yellow";
                                              $delete_movimiento = "block";
                                          }else{
                                              $estado = "Cobrado";
                                              $class = "text-green";
                                              $delete_movimiento = "none";
                                          }
                                      }
                                    }else{
                                              $estado = "Anulado";
                                              $class = "text-red";
                                              $delete_movimiento = "none";                                
                                    }


                                    ?>

                                    <span class="<?php echo $class;?>" id="estado-<?php echo $detalle->id;?>" ><?php echo $estado; ?></span>
                              </small></td>
                              <td><small><?php echo $detalle->fechaconciliacion; ?></small></td>
                              <td><small><?php echo number_format($detalle->activo == 1 ? $detalle->monto : 0,0,".",".");?></small></td>
                              <!--td><?php echo number_format($saldo_caja,0,".",".");?></td-->                              
                            </tr>
                            <?php $monto_total += $detalle->activo == 1 ? $detalle->monto : 0; ?>
                            <?php $saldo_caja -= $detalle->activo == 1 ? $detalle->monto : 0; ?>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                          <th><small>Total Movimientos Per&iacute;odo</small></th>
                          <th colspan="4">&nbsp;</th>
                          <th><small><?php echo "$ ".number_format($monto_total,0,".","."); ?></small></th>
                          </tr>     
                          <tr>
                          <th><small>Saldo Anterior</small></th>
                          <th colspan="4">&nbsp;</th>
                          <th><small><?php echo "$ ".number_format($saldo_caja,0,".","."); ?></small></th>
                          </tr>  
                          <tr>
                          <th><small>Documentos No Identificados</small></th>
                          <th colspan="4">&nbsp;</th>
                          <th><small><?php echo "$ ".number_format($detalle_cuenta[2],0,".","."); ?></small></th>
                          </tr>                                                                         
                          <tr>
                          <th><small>Total</small></th>
                          <th colspan="4">&nbsp;</th>
                          <th><small><?php echo "$ ".number_format($monto_total+$saldo_caja+$detalle_cuenta[2],0,".","."); ?></small></th>
                          </tr>
                        </tfoot>                        
                        <!--tfoot>
                          <th>Total</th>
                          <th colspan="4">&nbsp;</th>
                          <th><?php echo "$ ".number_format($monto_total,0,".","."); ?></th>
                        </tfoot-->
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