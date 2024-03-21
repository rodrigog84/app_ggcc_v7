
        <!-- Main content -->
        <section class="content" >

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
                        <th>#</th>
                        <th>Mes</th>
                        <th>A&ntilde;o</th>
                        <th>Remuneraci&oacute;n Imponible</th>
                        <th>Remuneraci&oacute;n Total (L&iacute;quido)</th>
                        <th>Liquidacion</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; 
                        $back_button = false;
                        ?>
                        <?php if(count($datosperiodo) > 0){ ?>
                          <?php foreach ($datosperiodo as $periodo) { ?>
                            <?php if($idperiodo == $periodo->id){ 
                                $class_color = "class = 'success'";
                                $back_button = true;
                            }else{
                                $class_color = "";

                              }?>                          
                           <tr <?php echo $class_color; ?> >
                            <td><?php echo $i;?></td>
                            <td><?php echo date2string($periodo->mes,$periodo->anno) == 'Saldo Inicial' ? 'Saldo' : month2string($periodo->mes);?></td>
                            <td><?php echo date2string($periodo->mes,$periodo->anno) == 'Saldo Inicial' ? 'Inicial' : $periodo->anno;?></td>
                            <td>$&nbsp;<?php echo number_format($periodo->sueldoimponible,0,".",".");?></td>
                            <td>$&nbsp;<?php echo number_format($periodo->sueldoliquido,0,".",".");?></td>
                              <?php if(!is_null($periodo->aprueba)){ ?>
                              <td><center><a href="<?php echo base_url(); ?>remuneraciones/liquidacion/<?php echo $periodo->idremuneracion;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a></center></td> 
                              <?php } ?>                  
                          </tr>
                          <?php $i++; } ?>
                        <?php }else{ ?>
                            <tr>
                              <td colspan="9">No existe historial de remuneraciones en la comunidad</td>
                            </tr>
                        <?php } ?>
                     </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <?php if($back_button){ ?>
                    <div class="box-footer">
                      <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                    </div> 
                  <?php } ?>                   
                </div>
              </div>
          </div>
        </section><!-- /.content -->

