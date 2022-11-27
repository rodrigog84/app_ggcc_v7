        <!-- Main content -->
        <section class="content" >
        <?php if($periodo->publica == ''): ?>
         <div class="row">
            <div class="col-md-12">
                    <div class="callout callout-warning">
                     <h4><i class="icon fa fa-info"></i>&nbsp;Atenci&oacute;n!</h4>
                      El per&iacute;odo visualizado no ha sido publicado ni considerado en los saldos del per&iacute;odo.
                    </div>
              </div>
          </div>
        <?php endif; ?>
 
     
          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Detalle Gasto Com&uacute;n <?php echo date2string($periodo->mes,$periodo->anno); ?></h3>  
                    <div class="pull-right box-tools">
                      <h4><a href="<?php echo base_url(); ?>reports/export_detalle_ggcc/<?php echo $datosperiodo->id; ?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                    </div><!-- /. tools -->                      
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                  
                        <th><small>Concepto</small></th>
                        <th><small>Proveedor</small></th>
                        <th><small>Tipo Documento</small></th>
                        <th><small>Nro. Documento</small></th>
                        <th><small>Fecha Documento</small></th>
                        <th><small>Descripci&oacute;n</small></th>
                        <th><small>Deuda Total</small></th>
                        <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                          <th><small>Deuda Propiedad</small></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($padres as $key_padre => $value_padre) { ?>
                        <tr>
                          <td><small><b><?php echo $key_padre;?></b></small></td>
                          <td colspan="5"><small>&nbsp;</small></td>
                          <td  class="text-right"><small><b>$&nbsp;<?php echo number_format($value_padre,0,".",".");?></b></td>
                          <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                            <td  class="text-right"><small><b>$&nbsp;<?php echo number_format($value_padre*($prorrateo/100),0,".",".");?></b></small></td>
                          <?php } ?>
                        </tr>  
                        <?php foreach ($detalle[$key_padre] as $cuenta) { ?>
                          <tr>
                          <td><small><?php echo $cuenta->concepto;?></small></td>
                          <td><small><?php echo $cuenta->proveedor;?></small></td>
                          <td><small><?php echo $cuenta->tipodocumentotributario;?></small></td>
                          <td><small><?php echo $cuenta->nrodocumento;?></small></td>
                          <td><small><?php echo $cuenta->fecdocumento;?></small></td>
                          <td><small><?php echo $cuenta->descripcion;?></small></td>
                          <td class="text-right"><small>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></small></td>
                          <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                            <td class="text-right"><small>$&nbsp;<?php echo number_format($cuenta->monto*($prorrateo/100),0,".",".");?></small></td>
                          <?php } ?>
                          </tr>
                        <?php } ?>
                      <?php } ?>


                      <?php foreach ($padres_ingresos as $key_padre_ingreso => $value_padre_ingreso) { ?>
                        <tr>
                          <td><small><b><?php echo $key_padre_ingreso;?></b></small></td>
                          <td colspan="5"><small>&nbsp;</small></td>
                          <td  class="text-right"><small><b>$&nbsp;- <?php echo number_format($value_padre_ingreso,0,".",".");?></b></small></td>
                          <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                            <td  class="text-right"><small><b>$&nbsp;- <?php echo number_format($value_padre_ingreso*($prorrateo/100),0,".",".");?></b></small></td>
                          <?php } ?>
                        </tr>  
                        <?php foreach ($detalle_ingresos[$key_padre_ingreso] as $ingreso) { ?>
                          <tr>
                          <td><small><?php echo $ingreso->concepto;?></small></td>
                          <td><small><?php echo $ingreso->proveedor;?></small></td>
                          <td><small><?php echo $ingreso->tipodocumentotributario;?></small></td>
                          <td><small><?php echo $ingreso->nrodocumento;?></small></td>
                          <td><small><?php echo $ingreso->fecdocumento;?></small></td>
                          <td><small><?php echo $ingreso->descripcion;?></small></td>
                          <td class="text-right"><small>$&nbsp;- <?php echo number_format($ingreso->monto,0,".",".");?></small></td>
                          <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                            <td class="text-right"><small>$&nbsp;- <?php echo number_format($ingreso->monto*($prorrateo/100),0,".",".");?></small></td>
                          <?php } ?>
                          </tr>
                        <?php } ?>
                      <?php } ?>
                      <tr>
                        <th><small>Total Gasto Com&uacute;n</small></th>
                        <td colspan="5"><small>&nbsp;</small></td>
                        <th class="text-right"><small>$&nbsp;<?php echo number_format($datosperiodo->deuda,0,".",".");?></small></th>
                        <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                          <th class="text-right"><small>$&nbsp;<?php echo number_format($datosperiodo->deuda*($prorrateo/100),0,".",".");?></small></th>
                        <?php } ?>
                      </tr>
                      <tr>
                        <th><small>Fondo de Reserva</small></th>
                        <td colspan="5"><small>&nbsp;</small></td>
                        <th class="text-right"><small>$&nbsp;<?php echo number_format($datosperiodo->fondo_reserva,0,".",".");?></small></th>
                        <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                          <th class="text-right"><small>$&nbsp;<?php echo number_format($datosperiodo->fondo_reserva*($prorrateo/100),0,".",".");?></small></th>
                        <?php } ?>  
                      </tr> 
                      <tr>
                        <th><small>Total</small></th>
                        <td colspan="5"><small>&nbsp;</small></td>
                        <th class="text-right"><small>$&nbsp;<?php echo number_format($datosperiodo->deuda+$datosperiodo->fondo_reserva,0,".",".");?></small></th>
                        <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){ // PARA PROPIETARIO O COMITE, SE MUESTRA DEUDA POR PROPIEDAD ?>
                          <th class="text-right"><small>$&nbsp;<?php echo number_format(($datosperiodo->deuda+$datosperiodo->fondo_reserva)*($prorrateo/100),0,".",".");?></small></th>
                        <?php } ?>
                      </tr>                                                                 
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                  </div>                  

                </div>
              </div>

            
          </div>
        </section><!-- /.content -->