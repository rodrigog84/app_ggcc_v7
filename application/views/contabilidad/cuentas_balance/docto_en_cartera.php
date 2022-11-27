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
                            <th>Forma Pago</th>
                            <th>Cheque</th>
                            <th>Banco</th>
                            <th>Fecha Dep&oacute;sito</th>
                            <th>Monto</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $monto_total = 0; ?>
                          <?php if(count($detalle_cuenta) > 0 ){ ?>
                            <?php foreach ($detalle_cuenta as $detalle) { ?>
                             <tr >
                              <td><?php echo $detalle->numero;?></td>
                              <td><?php echo $detalle->formapago;?></td>
                              <td><?php echo $detalle->cheque;?></td>
                              <td><?php echo $detalle->banco;?></td>
                              <td><?php echo $detalle->fechadeposito;?></td>
                              <td><?php echo "$ ".number_format($detalle->monto,0,".",".");?></td>
                            </tr>
                            <?php $monto_total += $detalle->monto; ?>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <th>Total</th>
                          <th colspan="4">&nbsp;</th>
                          <th><?php echo "$ ".number_format($monto_total,0,".","."); ?></th>
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