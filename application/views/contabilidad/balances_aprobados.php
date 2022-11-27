        <!-- Main content -->
        <section class="content" >

         <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Balances no aprobados</h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Per&iacute;odo</th>
                      <th>Debe</th>
                      <th>Haber</th>
                      <th>Fecha Corte</th>
                      <th>Fecha C&aacute;lculo</th>
                      <th>Fecha Aprobaci&oacute;n</th>
                      <th>Ver</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($balances) > 0){ ?>

                      <?php foreach($balances as $balance){ ?>
                      <tr>
                        <td><?php echo date2string($balance->mes,$balance->anno); ?></td>
                        <td>$&nbsp;<?php echo number_format($balance->debe,0,".",".");?></td>
                        <td>$&nbsp;<?php echo number_format($balance->haber,0,".",".");?></td>
                        <td><?php echo $balance->corte;?></td>
                        <td><?php echo $balance->calculo;?></td>
                        <td><?php echo $balance->aprueba;?></td>
                        <td>
                          <center><a href="<?php echo base_url(); ?>contabilidad/ver_balance/<?php echo $balance->idperiodo; ?>" data-toggle="tooltip" title="Ver Balance"><span class="glyphicon glyphicon-search"></span></a></center>
                        </td>
                      </tr>
                      <?php } ?>

                    <?php }else{ ?>
                    <tr>
                      <td colspan="7">No existen balances aprobados</td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  </table>
                </div><!-- /.box-body -->


              </div><!-- /.box -->

            </div>
          </div>          
        </section><!-- /.content -->
