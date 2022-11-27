        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Cuentas&nbsp;&nbsp;</h3>
                </div><!-- /.box-header -->
                  <div class="box-body ">
                    <table class="table table-bordered">
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>C&oacute;digo</th>
                        <th>Cuenta</th>
                        <th>Monto ($)</th>
                      </tr>
                      <tr class="success">
                        <td><b>1.</b></td>
                        <td><b>1</b></td>
                        <td><b>Activo</b></td>
                        <td>&nbsp;</td>
                      </tr>
                      <?php $i = 2; ?>
                      <?php foreach ($activos as $activo) { ?>
                      <tr>
                        <td><?php echo $i;?>.</td>
                        <td>&nbsp;&nbsp;<?php echo $activo->codigo; ?></td>
                        <td>&nbsp;&nbsp;<?php echo $activo->nombre; ?></td>
                        <td><?php echo number_format($activo->valor,0,".","."); ?></td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>
                      <tr class="success">
                        <td><b><?php echo $i;?>.</b></td>
                        <td><b>2</b></td>
                        <td><b>Pasivo</b></td>
                        <td>&nbsp;</td>
                      </tr>   
                      <?php $i++; ?>   
                      <?php foreach ($pasivos as $pasivo) { ?>
                      <tr>
                        <td><?php echo $i;?>.</td>
                        <td>&nbsp;&nbsp;<?php echo $pasivo->codigo; ?></td>
                        <td>&nbsp;&nbsp;<?php echo $pasivo->nombre; ?></td>
                        <td><?php echo number_format($pasivo->valor,0,".","."); ?></td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>    
                      <tr class="success">
                        <td><b><?php echo $i;?>.</b></td>
                        <td><b>3</b></td>
                        <td><b>Patrimonio</b></td>
                        <td>&nbsp;</td>
                      </tr>   
                      <?php $i++; ?>                      
                      <?php foreach ($patrimonio as $patrim) { ?>
                      <tr>
                        <td><?php echo $i;?>.</td>
                        <td>&nbsp;&nbsp;<?php echo $patrim->codigo; ?></td>
                        <td>&nbsp;&nbsp;<?php echo $patrim->nombre; ?></td>
                        <td><?php echo number_format($patrim->valor,0,".","."); ?></td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>                                                          
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">&nbsp;
                  </div>                  
              </div><!-- /.box -->
              </div>
          </div>
        </section><!-- /.content -->
