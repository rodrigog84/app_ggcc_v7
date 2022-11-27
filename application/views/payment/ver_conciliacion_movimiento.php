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
          <br>
          <?php endif; ?>
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Detalle Movimientos de Caja</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Descripci&oacute;n</th>
                      <th>Nro. Transacci&oacute;n</th>
                      <th>Monto</th>
                      <th>Saldo Contable</th>
                      <th>Estado Conciliaci&oacute;n</th>
                      <th>Fecha Cobro</th>
                      <th>Ver</th>                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($movimientos) > 0 ){ ?>
                      <?php $saldo_caja = $datoscomunidad->caja; ?>
                      <?php foreach ($movimientos as $movimiento) { ?>
                       <tr >
                        <td><?php echo $movimiento->fechapago;?></td>
                        <td><?php echo $movimiento->glosa;?></td>
                        <td><?php echo trackid($movimiento->id);?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($movimiento->monto,0,".","."); ?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($saldo_caja,0,".",".");?></td>
                        <td>
                          <?php 
                              $estado = "";
                              $class = "";
                              if(is_null($movimiento->fechaconciliacion)){ 
                                $estado = "Pendiente Conciliaci&oacute;n";
                                $class = "text-red";
                                $delete_movimiento = "block";
                              }else{

                                  $fecha_actual = strtotime(date("Y-m-d"));
                                  $fechaconciliacion = substr($movimiento->fechaconciliacion,6,4)."-".substr($movimiento->fechaconciliacion,3,2)."-".substr($movimiento->fechaconciliacion,0,2);
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

                              ?>

                              <span class="<?php echo $class;?>" id="estado-<?php echo $movimiento->id;?>" ><?php echo $estado; ?></span>
                        </td>
                        <td><?php echo $movimiento->fechaconciliacion; ?></td>
                        <td><a href="<?php echo base_url(); ?>reports/ver_movimiento/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Ver Detalle Movimiento"><span class="glyphicon glyphicon-search input-sm"></span></a></td>
                      </tr>
                        <?php $saldo_caja -= $movimiento->monto; ?>
                      <?php } ?>
                    <?php } ?>
                  </tbody>

                  </table>
                </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo base_url(); ?>payments/conciliacion" class="btn btn-default">Volver</a>                    
                  </div>                   
              </div>
            </div>
          </div>
       
        </section><!-- /.content -->

