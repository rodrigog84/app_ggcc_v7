        <!-- Main content -->
        <section class="content">
        <?php if(isset($message)): ?>
         <div class="row">
            <div class="col-md-12">
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            </div>
          </div>
          <?php endif; ?>
         <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Per&iacute;odos Pendientes de Prorratear</h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Per&iacute;odo</th>
                      <th>Monto Deuda</th>
                      <th>Fecha Vencimiento</th>
                      <th>Detalle Deuda/Desautorizar</th>
                      <th>Acci&oacute;n</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($datosdeuda) > 0){ ?>
                      <?php foreach($datosdeuda as $deuda){ ?>
                      <tr>
                        <td><?php echo date2string($deuda->mes,$deuda->anno); ?></td>
                        <td>$&nbsp;<?php echo number_format($deuda->monto,0,".",".");?></td>
                        <td><?php echo $deuda->fecha_vencimiento; ?></td>
                        <td><a href="<?php echo base_url(); ?>reports/ver_detalle_periodo/<?php echo $deuda->ggccid; ?>" data-toggle="tooltip" title="Ver Detalle Deuda"><span class="glyphicon glyphicon-search"></span></a></td>
                        <td>
                          <a href="<?php echo base_url();?>comunity/prorrateo/<?php echo $deuda->periodoid;?>" class="btn btn-block btn-primary btn-xs" name="generar" <?php echo $pendiente_publicacion || $deuda->monto <= 0 ? "disabled" : ""; ?> >Prorrateo</a>
                        </td>

                      </tr>
                      <?php } ?>
                    <?php }else{ ?>
                    <tr>
                      <td colspan="5">No existen per&iacute;odos para prorratear</td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  </table>
                </div><!-- /.box-body -->


              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->
