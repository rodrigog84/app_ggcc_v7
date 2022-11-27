
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
                        <th>N&uacute;mero Trabajadores</th>
                        <th>Remuneraci&oacute;n Total (L&iacute;quido)</th>
                        <th>Detalle Remuneraciones</th>
                        <th>Liquidaciones</th>
                        <th>Previred</th>
                        <th>Libro Remuneraciones</th>
                        <?php //if($this->session->userdata('user_id') == 1 || $this->session->userdata('user_id') == 5){ ?>
                        <th>LRE</th>
                        <?php //} ?>
                        <th>Estado</th>
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
                            <td><?php echo number_format($periodo->numtrabajadores,0,".",".");?></td>
                            <td>$&nbsp;<?php echo number_format($periodo->sueldoliquido,0,".",".");?></td>
                              <td>
                              <center>
                              <?php if(!is_null($periodo->cierre)){ ?>
                              <a href="<?php echo base_url(); ?>remuneraciones/ver_remuneraciones_periodo/<?php echo $periodo->id; ?>" data-toggle="tooltip" title="Ver Remuneraciones Personal"><span class="glyphicon glyphicon-search"></span></a>
                              <?php } ?>
                              </center>
                              </td>
                              <td>
                              <center>
                              <?php if(!is_null($periodo->cierre)){ ?>
                              <a href="<?php echo base_url(); ?>remuneraciones/liquidaciones/<?php echo $periodo->id;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>  
                              <?php } ?>
                              </center>
                              </td>                            

                              <td>
                              <center>
                              <?php if(!is_null($periodo->cierre)){ ?>
                              <a href="<?php echo base_url(); ?>remuneraciones/previred/<?php echo $periodo->id;?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a>  
                              <?php } ?>
                              </center>
                              </td>      

                              <td>
                              <center>
                              <?php if(!is_null($periodo->cierre)){ ?>
                              <a href="<?php echo base_url(); ?>remuneraciones/libro/<?php echo $periodo->id;?>" target="_blank"><span class="glyphicon glyphicon-book"></span></a>  
                              <?php } ?>
                              </center>
                              </td> 
                              <?php if($this->session->userdata('user_id') == 1 || $this->session->userdata('user_id') == 5){ ?>
                              <td>
                              <center>
                              <?php if(!is_null($periodo->cierre)){ ?>
                              <a href="<?php echo base_url(); ?>remuneraciones/lre/<?php echo $periodo->id;?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a>  
                              <?php } ?>
                              </center>
                              </td>   
                              <?php } ?>                                                   

                              <td><span class="<?php echo is_null($periodo->aprueba) ? 'text-yellow fa fa-exclamation ' : 'text-green fa fa-check';?>" data-toggle="tooltip" title="<?php echo is_null($periodo->aprueba) ? 'En revisi&oacute;n' : 'Aprobada';?>"/></span></td>                        
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

