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



            <div class="row">

                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">Ingreso de Movimientos</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                          <table  class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th >#</th>
                              <th >Rut</th>
                              <th >Nombre Trabajador</th>
                              <th ><center>Ver</center></th>
                              <!--th ><center>Movimientos</center></th-->
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
                                <td >
                                    <center><a href="<?php echo base_url();?>remuneraciones/ver_movimiento_personal/<?php echo $trabajador->id;?>" data-toggle="tooltip" title="Ver Movimientos" ><i class="glyphicon glyphicon-search"></i></a></center>
                                </td>                                
                                <!--td >
                                    <center><a href="<?php echo base_url();?>remuneraciones/add_movimiento_personal/<?php echo $trabajador->id;?>" data-toggle="tooltip" title="Agregar Movimiento" ><i class="fa fa-plus-square"></i></a></center>
                                </td-->
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

     
        </section><!-- /.content -->