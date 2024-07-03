        <section class="content" >
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Detalle GGCC</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Item</th>
                        <th>Descripci&oacute;n</th>
                        <th>Consumo</th>
                        <th>Consumo Edificio</th>
                        <th>Monto Unidad</th>
                        <th>Monto</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; ?>
                    <?php foreach($datosdetalle as $detalle){ ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $detalle->item;?></td>
                        <td><?php echo $detalle->descripcion;?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>$&nbsp;<?php echo number_format($detalle->monto,0,".",".");?></td>
                      </tr>
                    <?php $i++; ?>
                    <?php } ?>
                    <?php foreach($datosindividual as $individual){
                        $unidadmedida = is_null($individual->unidadmedida) ? "unidad" : $individual->unidadmedida;
                     ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $individual->idtipodeudadetalle == 0 ? $individual->nombrefondo : $individual->item;?></td>
                        <td><?php echo $individual->descripcion;?></td>
                        <td><?php echo $individual->itemid == 8 || $individual->itemid == 9 || $individual->itemid == 12 || $individual->idtipodeudadetalle == 0 ? "" : $individual->consumo." " . $unidadmedida ." [" . $individual->valor ." " . $unidadmedida . " - ". $individual->valor_ant . " " . $unidadmedida . "]";?></td>
                        <td><?php echo $individual->itemid == 8 || $individual->itemid == 9 || $individual->itemid == 12 || $individual->idtipodeudadetalle == 0 ? "" : $individual->consumo_total." " . $unidadmedida;?></td>
                        <td><?php echo $individual->itemid == 8 || $individual->itemid == 9 || $individual->itemid == 12 || $individual->idtipodeudadetalle == 0 ? "" : "$&nbsp;" . number_format($individual->montounidad,4,",",".");?></td>
                        <td>$&nbsp;<?php echo number_format($individual->monto,0,".",".");?></td>
                      </tr>
                    <?php $i++; ?>
                    <?php } ?>
                      <?php if($muestra_saldo == 1){ ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td>Saldo Anterior</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>$<?php echo number_format($saldoanterior,0,".",".");?></td>
                      </tr>        
                      <?php } ?>
                      <tr>
                        <td colspan="6"><b>Total Gasto Com&uacute;n</b></td>
                        <td><b>$&nbsp;<?php echo number_format($muestra_saldo == 1 ? $totalggcc->monto+$saldoanterior : $totalggcc->monto,0,".",".");?></b></td>
                      </tr>                       
                  </tbody>
                  </table>
                </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                  </div>

              </div>
            </div>
          </div>
       
        </section><!-- /.content -->

