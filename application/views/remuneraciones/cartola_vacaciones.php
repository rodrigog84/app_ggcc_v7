        <!-- Main content -->


        <?php if(!isset($oculta)){ ?>
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

            <div class="col-md-9">
              <div class="box box-solid ">
                <div class="box-header">
                  <h3 class="box-title">Informaci&oacute;n Trabajador</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table">
                    <tr>
                    <td>
                    <p><b>Nombre</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $personal->nombre." ".$personal->apaterno." ".$personal->amaterno; ?></p>
                    <p><b>D&iacute;as Legales Devengados</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo number_format($dias_vacaciones,2,",","."); ?></p>                    
                    <p><b>D&iacute;as Tomados</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $personal->diasvactomados; ?></p>               
                   
                    </td>
                    <td>
                    <p><b>Fecha Ingreso</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $personal->fecingreso; ?></p>
                  <p><b>D&iacute;as Progresivos Devengados</b></p>
                  <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo number_format($num_dias_progresivos,2,",","."); ?></p>               
                  <p><b>Saldo Vacaciones</b></p>
                  <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo number_format($dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados,2,",","."); ?></p>               

                    </td>
                    <td>
                    <p><b>Fecha Inicio C&aacute;lculo</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo formato_fecha($personal->fecinicvacaciones,'Y-m-d','d/m/Y'); ?></p>
                  <p><b>D&iacute;as Totales Devengados</b></p>
                  <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo number_format($dias_vacaciones + $num_dias_progresivos,2,",","."); ?></p>               
                    </td>                    
                    </tr>
                    </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (left) -->
          </div>

        <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Cartola Detallada Vacaciones Devengada</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                  <div class="box-body">
                        <table id="ggcc" class="table  table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Fecha Desde</th>
                            <th>Fecha Hasta</th>
                            <th>D&iacute;as Legales</th>
                            <th>D&iacute;as Progresivos</th>
                            <th>D&iacute;as Totales</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                          <?php $dias_legales = 0; ?>
                          <?php $dias_progresivos = 0; ?>
                          <?php $dias_total = 0; ?>
                         <?php foreach($cartola_devengada as $linea_cartola_dev){ ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $linea_cartola_dev['fecinicio'] != 'Saldo Inicial' ?  formato_fecha($linea_cartola_dev['fecinicio'],'Y-m-d','d/m/Y') : $linea_cartola_dev['fecinicio']; ?></td>
                              <td><?php echo $linea_cartola_dev['fecfin'] != '' ? formato_fecha($linea_cartola_dev['fecfin'],'Y-m-d','d/m/Y') : $linea_cartola_dev['fecinicio']; ?></td>
                              <td><?php echo number_format($linea_cartola_dev['dias'],2,",","."); ?></td>
                              <td><?php echo number_format($linea_cartola_dev['diasprogresivos'],2,",","."); ?></td>
                              <td><?php echo number_format($linea_cartola_dev['dias'] + $linea_cartola_dev['diasprogresivos'],2,",","."); ?></td>
                              <?php $dias_legales += $linea_cartola_dev['dias']; ?>
                              <?php $dias_progresivos += $linea_cartola_dev['diasprogresivos']; ?>
                              <?php $dias_total += $linea_cartola_dev['dias'] + $linea_cartola_dev['diasprogresivos']; ?>
                            </tr>
                           <?php $i++; ?>  
                         <?php } ?>

                        </tbody>
                        <tfoot>
                          <tr>
                          <th colspan="2">Total d&iacute;as devengados</th>
                          <th >&nbsp;</th>
                          <th ><?php echo number_format($dias_legales,2,",",".");?></th>
                          <th ><?php echo number_format($dias_progresivos,2,",",".");?></th>
                          <th ><?php echo number_format($dias_total,2,",",".");?></th>

                          </tr>
                        </tfoot>

                        </table>

                  </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>
          </div> 

        <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Vacaciones Progresivas Autorizadas</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                  <div class="box-body">
                        <table id="ggcc" class="table table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Per&iacute;odo</th>
                            <th>D&iacute;as</th>
                            <?php if($this->session->userdata('level') != 5){ ?>
                            <th>Acci&oacute;n</th>
                            <?php } ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                          <?php $dias_total = 0; ?>
                          <?php if(count($cartola_dias_progresivos) > 0){ ?>
                           <?php foreach($cartola_dias_progresivos as $dia_progresivo){ ?>

                              <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $dia_progresivo->fechainicio; ?></td>
                                <td><?php echo $dia_progresivo->dias; ?></td>
                                <?php if($this->session->userdata('level') != 5){ ?>
                                <td>
                           <a href="<?php echo base_url();?>remuneraciones/add_dia_progresivo/<?php echo $personal->id;?>/<?php echo $dia_progresivo->id;?>" data-toggle="tooltip" title="Editar D&iacute;as Progresivos Autorizados" ><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                           <a href="<?php echo base_url();?>remuneraciones/delete_dias_progresivos/<?php echo $personal->id;?>/<?php echo $dia_progresivo->id;?>" data-toggle="tooltip" title="Eliminar D&iacute;as Progresivos Autorizados" ><span class="glyphicon glyphicon-trash"></span></a>                           
                                </td>
                                 <?php } ?>
                                <?php $dias_total += $dia_progresivo->dias; ?>
                              </tr>
                             <?php $i++; ?>  
                           <?php } ?>

                          <?php }else{ ?>
                              <tr>
                                <td colspan="4">No existen d&iacute;as progresivos autorizados</td>
                              </tr>
                          <?php } ?>


                        </tbody>
                        <tfoot>
                          <tr>
                          <th colspan="2">Total d&iacute;as autorizados</th>
                          <th ><?php echo $dias_total;?></th>

                          </tr>
                        </tfoot>

                        </table>
                  </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>
          </div>           

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Cartola de Vacaciones Solicitadas</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                  <div class="box-body">
                        <table id="ggcc" class="table table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Fecha Desde</th>
                            <th>Fecha Hasta</th>
                            <th>D&iacute;as</th>
                            <th>Comentario</th>
                            <th>Comprobante</th>
                            <?php if($this->session->userdata('level') != 5){ ?>
                            <th>Acci&oacute;n</th>
                            <?php } ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                          <?php $dias_total = 0; ?>
                          <?php if(count($cartola) > 0){ ?>
                           <?php foreach($cartola as $linea_cartola){ ?>

                              <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo formato_fecha($linea_cartola->fecinicio,'Y-m-d','d/m/Y'); ?></td>
                                <td><?php echo formato_fecha($linea_cartola->fecfin,'Y-m-d','d/m/Y'); ?></td>
                                <td><?php echo $linea_cartola->dias; ?></td>
                                <td><?php echo $linea_cartola->comentarios; ?></td>
                                <td><a href="<?php echo base_url();?>remuneraciones/comprobante_solicitud/<?php echo $personal->id;?>/<?php echo $linea_cartola->id;?>" data-toggle="tooltip" title="Editar Solicitud" target="_blank" ><i class="fa  fa-file-o"></i></a></td>
                                 <?php if($i == count($cartola) && $personal->active == 1){ ?>
                                  <?php if($this->session->userdata('level') != 5){ ?>
                                <td>
                           <a href="<?php echo base_url();?>remuneraciones/solicita_vacaciones/<?php echo $personal->id;?>/<?php echo $linea_cartola->id;?>" data-toggle="tooltip" title="Editar Solicitud" ><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                           <a href="<?php echo base_url();?>remuneraciones/delete_vacaciones/<?php echo $personal->id;?>/<?php echo $linea_cartola->id;?>" data-toggle="tooltip" title="Eliminar Solicitud" ><span class="glyphicon glyphicon-trash"></span></a>                           
                                </td>
                                <?php } ?>
                                <?php }else{ ?>
                                  <td>&nbsp;</td>

                                <?php } ?>
                                <?php $dias_total += $linea_cartola->dias; ?>
                              </tr>
                             <?php $i++; ?>  
                           <?php } ?>

                          <?php }else{ ?>
                              <tr>
                                <td colspan="5">No existen vacaciones solicitadas</td>
                              </tr>
                          <?php } ?>


                        </tbody>
                        <tfoot>
                          <tr>
                          <th colspan="2">Total d&iacute;as tomados</th>
                          <th >&nbsp;</th>
                          <th ><?php echo $dias_total;?></th>

                          </tr>
                        </tfoot>

                        </table>

                    <input type="hidden" name="idpersonal" id="idpersonal" value="<?php echo $personal->id;?>" >

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <?php if($this->session->userdata('level') != 5){ ?>
                    <a href="<?php echo base_url();?>remuneraciones/vacaciones/<?php echo $link;?>" class="btn btn-default">Volver</a> 
                    <?php } ?>
                  </div>
              </div><!-- /.box -->

            </div>
          </div> 
        </section><!-- /.content -->
 
      <?php }else{ ?>
        <section class="content">
        <div class="row">

            <div class="col-md-9">
              <div class="box box-solid ">
                <div class="box-header">
                  <h3 class="box-title">Informaci&oacute;n Trabajador</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table">
                    <tr>
                    <td>
                    No existe Informaci&oacute;n de Trabajador asociada
                    </td>
                    </tr>
                    </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (left) -->
          </div>
        </section>
      <?php } ?>
  