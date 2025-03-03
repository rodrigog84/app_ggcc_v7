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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/decjurada_rentas" id="basicBootstrapForm" method="post"> 
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
                                  <?php for($i=(date('Y')-3);$i<=date('Y') -1;$i++){ ?>
                                  <?php $yearselected = $i == $anno ? "selected" : ""; ?>
                                  <option value="<?php echo $i;?>" <?php echo $yearselected; ?>><?php echo $i;?></option>
                                  <?php } ?>
                                </select>
                                <b><small>(*) DJ se calcular&aacute; s&oacute;lo sobre per&iacute;odos aprobados</small></b>
                            </div>
                          </div> 
                      </div>
                      <div class='row'>
                          <div class='col-md-3'>
                            <div class="form-group ">
                            <label for="ruttitular">&nbsp;</label> 
                            <button type="submit" class="btn btn-primary btn-block">Generar</button>
                          </div>
                          </div>                  
                      
                      <?php if(count($encabezado) > 0){ ?>

                            <div class='col-md-3'>
                              <div class="form-group ">
                              <label for="ruttitular">&nbsp;</label> 
                              <a href="<?php echo base_url();?>remuneraciones/decjurada_rentas_exportar/<?php echo $anno;?>" class="btn btn-success btn-block">Exportar</a>
                            </div>
                            </div>                  

                      <?php } ?>
                      </div>
                    </div><!-- /.box-body -->
                  </div>
                </div>


            </div>     

        <?php if(count($encabezado) > 0){ ?>
              <div class="row">
                  
                  <div class="col-md-9">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Declaraci&oacute;n Jurada Anual sobre Rentas</h3> 
                        <?php if(count($encabezado) > 0 ){ ?>
                          <?php $linea = $encabezado[0]; ?>

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
                            <th >Renta Total Neta Pagada (Art. 42 Nro. 1, Ley de Renta)</th>
                            <th>$&nbsp;<?php echo number_format($linea->rentatotalsinactualizar,0,'.','.'); ?></th>
                          </tr>
                          <tr>
                            <th >Renta Total Neta Actualizada</th>
                            <th>$&nbsp;<?php echo number_format($linea->rentatotalneta,0,'.','.'); ?></th>
                          </tr>

                          <tr>
                            <th >Impuesto Unico de Segunda Categoria Retenido Por Renta Total Neta Pagada Durante el A&ntilde;o</th>
                            <th>$&nbsp;<?php echo number_format($linea->impuestorentasinactualizar,0,'.','.'); ?></th>
                          </tr>

                          <tr>
                            <th >Impuesto Unico de Segunda Categoria Retenido Por Rentas Accesorias Y/O Complementarias Pagada Entre Ene-Abr.  A&ntilde;o Sgte</th>
                            <th>$&nbsp;<?php echo number_format($linea->impuestorentaaccesoria,0,'.','.'); ?></th>
                          </tr>

                          <tr>
                            <th >Renta Total No Gravada</th>
                            <th>$&nbsp;<?php echo number_format($linea->rentanogravadasinactualizar,0,'.','.'); ?></th>
                          </tr>

                          <tr>
                            <th >Renta Total Exenta</th>
                            <th>$&nbsp;<?php echo number_format($linea->rentaexenta,0,'.','.'); ?></th>
                          </tr>

                          <tr>
                            <th >Rebaja por Zonas Extremas (FRANQUICIA D.L.889)</th>
                            <th>$&nbsp;<?php echo number_format($linea->rebajazonasextremas,0,'.','.'); ?></th>
                          </tr>

                          <tr>
                            <th >Leyes Sociales</th>
                            <th>$&nbsp;<?php echo number_format($linea->leyessociales,0,'.','.'); ?></th>
                          </tr>

                        </thead>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   

        <br>

              

                <?php } ?>
           </form>          
        </section><!-- /.content -->


