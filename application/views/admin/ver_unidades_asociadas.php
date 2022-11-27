        <!-- Main content -->
        <section class="content" >
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Unidades de Propiedad <?php echo $propiedad->numero;?></h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre Unidad</th>
                        <th>Tipo Unidad</th>
                        <th>Propiedad</th>
                        <th>Prorrateo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if((count($estacionamientos) + count($bodegas)) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php $prorrateo = 0; ?>
                        <?php foreach ($estacionamientos as $estacionamiento) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td>Estacionamiento</td>
                          <td><?php echo $estacionamiento->nombre;?></td>
                          <td><?php echo $estacionamiento->propiedad;?></td>
                          <td><?php echo $estacionamiento->prorrateo."%";?></td>
                        </tr>
                        <?php $prorrateo += $estacionamiento->prorrateo; ?>
                        <?php $i++;?>
                        <?php } ?>
                        <?php foreach ($bodegas as $bodega) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td>Bodega</td>
                          <td><?php echo $bodega->nombre;?></td>
                          <td><?php echo $bodega->propiedad;?></td>
                          <td><?php echo $bodega->prorrateo."%";?></td>
                        </tr>
                        <?php $prorrateo += $bodega->prorrateo; ?>
                        <?php $i++;?>
                        <?php } ?>
                        <tr>
                          <tr>
                            <th colspan="4">Totales Prorrateo Unidades Asociadas</th>
                            <th ><?php echo $prorrateo."%";?></th>
                          </tr>
                        </tr>  
                        <tr>
                          <tr>
                            <th colspan="4">Prorrateo Propiedad</th>
                            <th ><?php echo $propiedad->prorrateo_propiedad."%";?></th>
                          </tr>
                        </tr>                                                                        
                        
                        <tr>
                          <tr>
                            <th colspan="4">Total Prorrateo Propiedad</th>
                            <th ><?php echo $propiedad->prorrateo."%";?></th>
                          </tr>
                        </tr>                                                                        



                      <?php }else{ ?>
                         <tr >
                          <td colspan="5">No existe unidades asociadas a la propiedad</td>
                         </tr>
                      <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo base_url();?>admins/admin_propiedad" class="btn btn-default">Volver</a>
                  </div>                  
                </div>
              </div>

            
          </div>
        </section><!-- /.content -->