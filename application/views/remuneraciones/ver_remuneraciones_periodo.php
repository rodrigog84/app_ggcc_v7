        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Propiedades.  <?php echo date2string($datosperiodo->mes,$datosperiodo->anno);?></h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Trabajador</th>
                        <th>Sueldo Base</th>
                        <th>Haberes</th>
                        <th>Descuentos</th>
                        <th>Liquido a Pagar</th>
                        <th>Liquidaci&oacute;n</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($remuneraciones as $remuneracion) { ?>
                         <tr >
                          <td><?php echo $i;?></td>
                          <td><?php echo $remuneracion->nombre." ".$remuneracion->apaterno." ".$remuneracion->amaterno;?></td>
                          <td>$&nbsp;<?php echo number_format($remuneracion->sueldobase,0,".",".");?></td>
                          <td>$&nbsp;<?php echo number_format($remuneracion->totalhaberes,0,".",".");?></td>
                          <td>$&nbsp;<?php echo number_format($remuneracion->totaldescuentos,0,".",".");?></td>
                          <td>$&nbsp;<?php echo number_format($remuneracion->sueldoliquido,0,".",".");?></td>
                          <td><center><a href="<?php echo base_url(); ?>remuneraciones/liquidacion/<?php echo $remuneracion->id;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a></center></td>
                        </tr>
                        <?php $i++; } ?>
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
