        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-4">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Par&aacute;metros</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                    <table class="table">
                      <tr>
                      <td>
                      <p><b>Sueldo M&iacute;nimo</b></p>
                      <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;$&nbsp;<?php echo number_format($parametros_generales->sueldominimo,0,".","."); ?></p>
                      <p><b>Valor UF</b></p>
                      <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;$&nbsp;<?php echo number_format($parametros_generales->uf,2,".","."); ?></p>                   
                      </td>
                      </tr>
                      </table>
                  </div><!-- /.box-body -->
              </div><!-- /.box -->

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Tabla Asignaci&oacute;n Familiar</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                    <table class="table table-bordered">
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Monto</th>
                      </tr>
                      <?php $i = 1; ?>
                      <?php foreach ($tabla_asig_familiar as $asig_familiar) { ?>
                        <tr>
                          <td><?php echo $asig_familiar->tramo;?></td>
                          <td>$&nbsp;<?php echo number_format($asig_familiar->desde,0,".","."); ?></td>
                          <td><?php echo $asig_familiar->hasta != 999999999 ? "$ ".number_format($asig_familiar->hasta,0,".",".") : "Y más"; ?></td>
                          <td>$&nbsp;<?php echo number_format($asig_familiar->monto,0,".","."); ?></td>
                        </tr>
                        <?php $i++; ?>
                      <?php } ?>
                    </table>

                  </div><!-- /.box-body -->
              </div><!-- /.box -->              
              </div>
            <div class="col-md-8">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Tabla Impuesto &Uacute;nico</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Desde</th>
                      <th>Hasta</th>
                      <th style="width: 40px">Factor</th>
                      <th>Rebaja</th>
                    </tr>
                    <?php $i = 1; ?>
                    <?php foreach ($tabla_impuesto as $impuesto) { ?>
                      <tr>
                        <td><?php echo $i;?></td>
                        <td>$&nbsp;<?php echo number_format($impuesto->desde,0,".","."); ?></td>
                        <td><?php echo $impuesto->hasta != 999999999 ? "$ ".number_format($impuesto->hasta,0,".",".") : "Y más"; ?></td>
                        <td><?php echo $impuesto->factor; ?></td>
                        <td>$&nbsp;<?php echo number_format($impuesto->rebaja,0,".","."); ?></td>
                      </tr>
                      <?php $i++; ?>
                    <?php } ?>
                  </table>
                  </div><!-- /.box-body -->
              </div><!-- /.box -->
              </div>              
          </div>
        </section><!-- /.content -->