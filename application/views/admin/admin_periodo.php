        <!-- Main content -->
        <section class="content" >

         <div class="row">
          <div class="col-md-12">
            <?php if(isset($message)): ?>
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            <?php endif; ?>
            </div>           
          </div>
          <div>
              <a href="<?php echo base_url();?>admins/add_periodo" type="submit" class="btn btn-primary">Agregar Per&iacute;odo</a>
          </div>
          <br>

          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Per&iacute;odos</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>Mes</th>
                        <th>A&ntilde;o</th>
                        <th>Inter&eacute;s</th>
                        <th>Fecha Vencimiento</th>
                        <th>Fecha Autorizaci&oacute;n</th>
                        <th>Fecha Prorrateo</th>
                        <th>Fecha Publicaci&oacute;n</th>
                        <th>Acci&oacute;n</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($datosperiodo) > 0 ){ ?>
                        <?php foreach ($datosperiodo as $periodo) { ?>
                         <tr >
                          <td><?php echo date2string($periodo->mes,$periodo->anno) == 'Saldo Inicial' ? 'Saldo' : month2string($periodo->mes);?></td>
                          <td><?php echo date2string($periodo->mes,$periodo->anno) == 'Saldo Inicial' ? 'Inicial' : $periodo->anno;?></td>
                          <td><?php echo $periodo->interes;?>&nbsp;<span style="font-size: 10px">%</span></td>
                          <td><?php echo $periodo->fecha_vencimiento;?></td>
                          <td><?php echo $periodo->autoriza;?></td>
                          <td><?php echo $periodo->genera;?></td>
                          <td><?php echo $periodo->publica;?></td>
                          <td>
                          <?php if(($periodo->genera == '' || $periodo->id == $ultimo_periodo) && ($periodo->id != $periodo_inicial->id)){ ?>
                          <a href="<?php echo base_url();?>admins/edit_periodo/<?php echo $periodo->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;
                          <a href="<?php echo base_url();?>admins/delete_periodo/<?php echo $periodo->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>                          
                          <?php }else{ ?>
                              &nbsp;
                          <?php } ?>
                          </td>

                        </tr>
                        <?php } ?>
                      <?php }else{ ?>
                        <tr  >
                          <td colspan="6">No existen Per&iacute;odos</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                </div>
              </div>
          </div>
        </section><!-- /.content -->
