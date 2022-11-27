        <!-- Main content -->
        <section class="content" >
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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/decjurada_honorarios" id="basicBootstrapForm" method="post"> 
            <div class="row">

                <div class="col-md-9">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">B&uacute;squedas</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                      <div class='row'>
                          <div class='col-md-4'>
                            <div class="form-group">
                                <label for="anno">A&ntilde;o</label>
                                <select name="anno" id="anno" class="form-control periodo">
                                  <?php for($i=(date('Y')-3);$i<=date('Y');$i++){ ?>
                                  <?php $yearselected = $i == $anno ? "selected" : ""; ?>
                                  <option value="<?php echo $i;?>" <?php echo $yearselected; ?>><?php echo $i;?></option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div> 
                      </div>
                      <div class='row'>
                          <div class='col-md-3'>
                            <div class="form-group ">
                            <label for="ruttitular">&nbsp;</label> 
                            <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                          </div>
                          </div>                  
                      </div>                                           
                    </div><!-- /.box-body -->
                  </div>
                </div>


            </div>     

        <?php if(isset($descjurada_data)){ ?>
              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Declaraci&oacute;n Jurada Anual sobre Retenciones Efectuadas</h3> 
                        <?php if(count($descjurada_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <!--h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4-->
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th rowspan="2"><small>#</small></th>
                            <th rowspan="2"><small>Rut Proveedor</small></th>
                            <th rowspan="2"><small>Nombre Proveedor</small></th>
                            <th rowspan="2"><small>Monto Retenido Anual</small></th>
                            <th colspan="12"><small>Per&iacute;odo al cual corresponden las rentas</small></th>

                          </tr>
                          <tr>
                            <th><small>Ene</small></th>
                            <th><small>Feb</small></th>
                            <th><small>Mar</small></th>
                            <th><small>Abr</small></th>
                            <th><small>May</small></th>
                            <th><small>Jun</small></th>
                            <th><small>Jul</small></th>
                            <th><small>Ago</small></th>
                            <th><small>Sep</small></th>
                            <th><small>Oct</small></th>
                            <th><small>Nov</small></th>
                            <th><small>Dic</small></th>

                          </tr>
                        </thead>
                        <tbody>
                          <?php $retencion_total = 0; ?>
                          <?php if(count($descjurada_data) > 0 ){ ?>
                            <?php $i = 1; ?>                            
                            <?php foreach ($descjurada_data as $provee) { ?>
                             <tr >
                              <td><small><?php echo $i;?></small></td>
                              <td><small><?php echo number_format($provee->rut,0,".",".")."-".$provee->dv;?></small></td>
                              <td><small><?php echo $provee->nombre;?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($provee->retencion,0,".",".");?></small></td>
                              <td><small><?php echo $provee->renta_enero;?></small></td>
                              <td><small><?php echo $provee->renta_febrero;?></small></td>
                              <td><small><?php echo $provee->renta_marzo;?></small></td>
                              <td><small><?php echo $provee->renta_abril;?></small></td>
                              <td><small><?php echo $provee->renta_mayo;?></small></td>
                              <td><small><?php echo $provee->renta_junio;?></small></td>
                              <td><small><?php echo $provee->renta_julio;?></small></td>
                              <td><small><?php echo $provee->renta_agosto;?></small></td>
                              <td><small><?php echo $provee->renta_septiembre;?></small></td>
                              <td><small><?php echo $provee->renta_octubre;?></small></td>
                              <td><small><?php echo $provee->renta_noviembre;?></small></td>
                              <td><small><?php echo $provee->renta_diciembre;?></small></td>
                            </tr>
                            <?php $i++; ?>
                            <?php $retencion_total += $provee->retencion; ?>
                            <?php } ?>
                          <?php }else{ ?>
                              <tr>
                                <td colspan="16">No se encontraron registros</td>
                              </tr>


                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   

        <br>

        <div class="row">
                  
                  <div class="col-md-10 col-md-offset-1 ">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Cuadro Resumen Final de la Declaraci&oacute;n</h3> 
                        <?php if(count($descjurada_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <!--h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4-->
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Monto Retenido Anual Actualizado</small></th>
                            <th><small>Total Casos Informados</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                              <td><small><?php echo number_format($retencion_total,0,".","."); ?></small></td>
                              <td><small><?php echo count($descjurada_data); ?></small></td>
                          </tr> 
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div> 

                <?php } ?>
           </form>          
        </section><!-- /.content -->


