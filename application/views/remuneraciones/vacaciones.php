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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_asistencia" id="basicBootstrapForm" method="post"> 

            <div class="row">

                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">Detalle Vacaciones</h3>  
                    </div><!-- /.box-header -->
                     &nbsp;&nbsp;&nbsp;
                                <div class="btn-group">
                                <button type="button" class="btn btn-default"><?php echo $title_button;?></button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <span class="caret"></span>
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="<?php echo base_url();?>remuneraciones/vacaciones/todos" />Mostrar Todos</a></li>
                                  <li><a href="<?php echo base_url();?>remuneraciones/vacaciones" />Mostrar Activos</a></li>
                                  <li><a href="<?php echo base_url();?>remuneraciones/vacaciones/inactivos" />Mostrar Inactivos</a></li>
                                </ul>
                                </div> 

                    <div class="box-body" >
                          <table  class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th rowspan="2">#</th>
                              <th rowspan="2">Rut</th>
                              <th rowspan="2">Nombre Trabajador</th>
                              <th colspan="3" ><center>D&iacute;as Devengados</center></th>
                              <th rowspan="2">Tomados</th>
                              <th rowspan="2">Saldo</th>
                              <th rowspan="2">Solicitar</th>
                              <th rowspan="2">Cartola</th>
                              <th rowspan="2">D&iacute;as Progresivos</th>
                            </tr>
                            <tr>
                             <th>Legales</th>
                              <th>Progresivos</th>
                              <th >Total Devengado</th>
                            </tr>

                          </thead>
                          <tbody>
                            <?php if(count($personal) > 0 ){ ?>
                              <?php $i = 1; ?>
                              <?php foreach ($personal as $trabajador) { ?>

                               <tr >
                                <td><?php echo $i ;?></td>
                                <td><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut,0,".",".")."-".$trabajador->dv;?></td>
                                <td><?php echo $trabajador->nombre." ".$trabajador->apaterno." ".$trabajador->amaterno;?></td>
                                <td>
                                    <?php $dias_vacaciones = dias_vacaciones($trabajador->fecinicvacaciones,$trabajador->saldoinicvacaciones); ?>
                                    <center><span id="diasatrabajar_<?php echo $trabajador->id;?>"  class="text-right" ><?php echo number_format($dias_vacaciones,2,",",".");?></span></center>
                                </td>
                                <td>
                                    <center><span id="diasatrabajar_<?php echo $trabajador->id;?>"  class="text-right" ><?php echo $progresivos[$trabajador->id];?></span></center>
                                </td>     
                                <td>
                                    <center><span id="diasatrabajar_<?php echo $trabajador->id;?>"  class="text-right" ><?php echo number_format($dias_vacaciones + $progresivos[$trabajador->id],2,",",".");?></span></center> 
                                </td>  
                                <td>
                                    <center><span id="diasatrabajar_<?php echo $trabajador->id;?>"  class="text-right" ><?php echo $trabajador->diasvactomados;?></span></center>
                                </td>     
                                <td>
                                    <center><span id="diasatrabajar_<?php echo $trabajador->id;?>"  class="text-right" ><?php echo number_format($dias_vacaciones + $progresivos[$trabajador->id] - $trabajador->diasvactomados,2,",",".");?></span></center> 
                                </td>                                                                                            
                                <td >
                                    <?php if($trabajador->active == 1){ ?>
                                    <center><a href="<?php echo base_url();?>remuneraciones/solicita_vacaciones/<?php echo $trabajador->id;?>" data-toggle="tooltip" title="Solicitar" ><i class="fa fa-suitcase"></i></a></center>
                                  <?php }else{ ?>
                                      <center>-</center>
                                  <?php } ?>
                                </td>
                                <td >
                                    <center><a href="<?php echo base_url();?>remuneraciones/cartola_vacaciones/<?php echo $trabajador->id."/".$link;?>" data-toggle="tooltip" title="Cartola" ><i class="fa fa-calendar"></i></a></center>
                                </td>   
                                <td >
                                  <?php if($trabajador->active == 1){ ?>
                                    <center><a href="<?php echo base_url();?>remuneraciones/add_dia_progresivo/<?php echo $trabajador->id;?>" data-toggle="tooltip" title="Cargar D&iacute;as Progresivos" ><i class="fa fa-plus-square-o"></i></a></center>

                                    <?php }else{ ?>
                                      <center>-</center>
                                  <?php } ?>

                                </td>                                                              
                              </tr>
                              <?php $i++;?>
                              <?php } ?>
                            <?php }else{ ?>
                            <tr>
                              <td colspan="4">No existen trabajadores en la comunidad</td>
                            </tr>
                          <?php } ?>
                          </tbody>
                          </table>
                    </div><!-- /.box-body -->
                  </div>
                </div>


            </div>

          </form>          
        </section><!-- /.content -->