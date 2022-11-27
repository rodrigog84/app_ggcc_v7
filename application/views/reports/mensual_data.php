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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>reports/mensual_data" id="basicBootstrapForm" method="post"> 
            <div class="row">

                <div class="col-md-9">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">B&uacute;squeda</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                      <div class='row'>
                          <div class='col-md-4'>
                            <div class="form-group">
                                <label for="anno">Tipo Reporte</label>
                                <select name="tiporeporte" id="tiporeporte" class="form-control">
                                  <option value="">Seleccione tipo reporte</option>
                                  <option value="li" <?php echo $tiporeporte == 'li' ? 'selected' : ''; ?> >Lecturas Individuales</option>
                                  <option value="ri" <?php echo $tiporeporte == 'ri' ? 'selected' : ''; ?> >Intereses</option>
                                  <option value="ra" <?php echo $tiporeporte == 'ra' ? 'selected' : ''; ?> >Ajustes</option>
                                  <option value="rm" <?php echo $tiporeporte == 'rm' ? 'selected' : ''; ?> >Multas</option>
                                  <option value="rce" <?php echo $tiporeporte == 'rce' ? 'selected' : ''; ?> >Cuotas Especiales</option>
                                  <?php if($this->session->userdata('level') == 1){  ?>

                                    <option value="ric" <?php echo $tiporeporte == 'ric' ? 'selected' : ''; ?> >Ingresos Comunidad</option>
                                  
                                  <option value="rsc" <?php echo $tiporeporte == 'rsc' ? 'selected' : ''; ?> >Cuentas sin cobro</option>
                                  <option value="cgc" <?php echo $tiporeporte == 'cgc' ? 'selected' : ''; ?> >Cobro Gasto Com&uacute;n</option>
                                  <option value="rec" <?php echo $tiporeporte == 'rec' ? 'selected' : ''; ?> >Espacios Comunes</option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div>                                                  
                          <div class='col-md-4'>
                            <div class="form-group">
                                <label for="mes">Meses</label>
                                <select name="mes" id="mes" class="form-control periodo">
                                  <option value="1" <?php echo $mes == 1 ? "selected" : ""; ?>>Enero</option>
                                  <option value="2" <?php echo $mes == 2 ? "selected" : ""; ?>>Febrero</option>
                                  <option value="3" <?php echo $mes == 3 ? "selected" : ""; ?>>Marzo</option>
                                  <option value="4" <?php echo $mes == 4 ? "selected" : ""; ?>>Abril</option>
                                  <option value="5" <?php echo $mes == 5 ? "selected" : ""; ?>>Mayo</option>
                                  <option value="6" <?php echo $mes == 6 ? "selected" : ""; ?>>Junio</option>
                                  <option value="7" <?php echo $mes == 7 ? "selected" : ""; ?>>Julio</option>
                                  <option value="8" <?php echo $mes == 8 ? "selected" : ""; ?>>Agosto</option>
                                  <option value="9" <?php echo $mes == 9 ? "selected" : ""; ?>>Septiembre</option>
                                  <option value="10" <?php echo $mes == 10 ? "selected" : ""; ?>>Octubre</option>
                                  <option value="11" <?php echo $mes == 11 ? "selected" : ""; ?>>Noviembre</option>
                                  <option value="12" <?php echo $mes == 12 ? "selected" : ""; ?>>Diciembre</option>
                                </select>
                            </div> 
                          </div>
                          <div class='col-md-4'>
                            <div class="form-group">
                                <label for="anno">A&ntilde;o</label>
                                <select name="anno" id="anno" class="form-control periodo">
                                  <?php for($i=(date('Y')-2);$i<=(date('Y')+2);$i++){ ?>
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


            <?php if($tiporeporte == 'li'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Cuentas</h3>  
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Proveedor</small></th>
                            <th><small>Concepto</small></th>
                            <th><small>Fecha Vencimiento</small></th>
                            <th><small>Per&iacute;odo Cobro</small></th>
                            <th><small>Monto</small></th>
                            <th>&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cuenta) { ?>
                             <tr >
                              <td><small><?php echo $cuenta->proveedor;?></small></td>
                              <td><small><?php echo $cuenta->concepto;?></small></td>
                              <td><small><?php echo $cuenta->fecvencimiento;?></small></td>
                              <td><small><?php echo date2string($cuenta->mes,$cuenta->anno); ?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></small></td>
                              <td>
                              <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                                &nbsp;
                                &nbsp;                        
                              <a href="<?php echo base_url(); ?>reports/ver_detalle_lectura/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Detalle Lectura Individual"><span class="fa fa-align-justify"></span></a>
                                &nbsp;
                                &nbsp;        
                                <?php if($cuenta->nombrearchivo != ''){ ?>
                                  <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                  &nbsp;
                                  &nbsp;                        
                                <?php } ?>
                              </td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>                
            <?php }else if($tiporeporte == 'ri'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Cobros</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Nro. Propiedad</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Fecha Cobro</small></th>
                            <th><small>Per&iacute;odo Cobro</small></th>
                            <th><small>Monto</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cobro) { ?>
                             <tr >
                              <td><small><?php echo $cobro->numero;?></small></td>
                              <td><small><?php echo $cobro->descripcion;?></small></td>
                              <td><small><?php echo $cobro->fechadeuda;?></small></td>
                              <td><small><?php echo date2string($cobro->mes,$cobro->anno); ?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cobro->monto,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


            <?php }else if($tiporeporte == 'ra'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Ajustes</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Nro. Propiedad</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Fecha Cobro</small></th>
                            <th><small>Per&iacute;odo Cobro</small></th>
                            <th><small>Monto</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cobro) { ?>
                             <tr >
                              <td><small><?php echo $cobro->numero;?></small></td>
                              <td><small><?php echo $cobro->descripcion;?></small></td>
                              <td><small><?php echo $cobro->fechadeuda;?></small></td>
                              <td><small><?php echo date2string($cobro->mes,$cobro->anno); ?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cobro->monto,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


          <?php }else if($tiporeporte == 'rm'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Multas</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Nro. Propiedad</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Fecha Cobro</small></th>
                            <th><small>Per&iacute;odo Cobro</small></th>
                            <th><small>Monto</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cobro) { ?>
                             <tr >
                              <td><small><?php echo $cobro->numero;?></small></td>
                              <td><small><?php echo $cobro->descripcion;?></small></td>
                              <td><small><?php echo $cobro->fechadeuda;?></small></td>
                              <td><small><?php echo date2string($cobro->mes,$cobro->anno); ?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cobro->monto,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


          <?php }else if($tiporeporte == 'rce'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Cuotas Especiales</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Nro. Propiedad</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Fecha Cobro</small></th>
                            <th><small>Per&iacute;odo Cobro</small></th>
                            <th><small>Monto</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cobro) { ?>
                             <tr >
                              <td><small><?php echo $cobro->numero;?></small></td>
                              <td><small><?php echo $cobro->descripcion;?></small></td>
                              <td><small><?php echo $cobro->fechadeuda;?></small></td>
                              <td><small><?php echo date2string($cobro->mes,$cobro->anno); ?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cobro->monto,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


          <?php }else if($tiporeporte == 'ric'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Ingresos</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Proveedor</small></th>
                            <th><small>Concepto</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Tipo Ingreso</small></th>
                            <th><small>Fecha Documento</small></th>
                            <th><small>Nro Documento</small></th>
                            <th><small>Fecha Vencimiento</small></th>
                            <th><small>Monto</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $ingreso) { ?>
                             <tr >
                              <td><small><?php echo $ingreso->proveedor;?></small></td>
                              <td><small><?php echo $ingreso->concepto;?></small></td>
                              <td><small><?php echo $ingreso->descripcion;?></small></td>
                              <td><small><?php echo tipo_ingreso($ingreso->tipoingreso); ?></small></td>
                              <td><small><?php echo $ingreso->fecdocumento;?></small></td>
                              <td><small><?php echo $ingreso->nrodocumento;?></small></td>
                              <td><small><?php echo $ingreso->fecvencimiento;?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($ingreso->monto,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


          <?php }else if($tiporeporte == 'rsc'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Cuentas sin cobro</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Proveedor</small></th>
                            <th><small>Concepto</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Fecha Documento</small></th>
                            <th><small>Nro Documento</small></th>
                            <th><small>Fecha Vencimiento</small></th>
                            <th><small>Monto</small></th>
                            <th><small>Documento</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cuentas) { ?>
                             <tr >
                              <td><small><?php echo $cuentas->proveedor;?></small></td>
                              <td><small><?php echo $cuentas->concepto;?></small></td>
                              <td><small><?php echo $cuentas->descripcion;?></small></td>
                              <td><small><?php echo $cuentas->fecdocumento;?></small></td>
                              <td><small><?php echo $cuentas->nrodocumento;?></small></td>
                              <td><small><?php echo $cuentas->fecvencimiento;?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cuentas->monto,0,".",".");?></small></td>
                              <td><small><a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuentas->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


            <?php }else if($tiporeporte == 'cgc'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Gasto Com&uacute;n del Mes</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Nro.</small></th>
                            <th><small>Responsable</small></th>
                            <th><small>Prorrateo</small></th>
                            <th><small>Cobro del Mes</small></th>
                            <th><small>Saldo Anterior</small></th>
                            <th><small>Cobro Total</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $propiedad) { ?>
                             <tr >
                              <td><small><?php echo $propiedad->numero;?></small></td>
                              <td><small><?php echo $propiedad->responsable;?></small></td>
                              <td><small><?php echo $propiedad->prorrateo;?>&nbsp;<span style="font-size: 10px">%</span></small></td>
                              <td><small>$&nbsp;<?php echo number_format($propiedad->monto,0,".",".");?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($propiedad->saldo_anterior,0,".",".");?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($propiedad->monto+$propiedad->saldo_anterior > 0 ? $propiedad->monto+$propiedad->saldo_anterior : 0,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                        </table>
                        </form>
                      </div><!-- /.box-body -->
                    </div>
                  </div>
                </div>   


            <?php }else if($tiporeporte == 'rec'){ ?>

              <div class="row">
                  
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-header">
                        <h3 class="box-title">Listado de Cuentas de Espacios Comunes</h3> 
                        <?php if(count($mensual_data) > 0 ){ ?>
                        <div class="pull-right box-tools">
                            <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/<?php echo $tiporeporte; ?>/<?php echo $mes;?>/<?php echo $anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                        </div>
                        <?php } ?>
                      </div><!-- /.box-header -->

                      <div class="box-body" >
                        <form  method="post">
                        <table class="table table-bordered table-striped dt-responsive">
                        <thead>
                          <tr>
                            <th><small>Nro. Propiedad</small></th>
                            <th><small>Concepto</small></th>
                            <th><small>Descripci&oacute;n</small></th>
                            <th><small>Fecha Cobro</small></th>
                            <th><small>Per&iacute;odo Cobro</small></th>
                            <th><small>Monto</small></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if(count($mensual_data) > 0 ){ ?>
                            <?php foreach ($mensual_data as $cobro) { ?>
                             <tr >
                              <td><small><?php echo $cobro->numero;?></small></td>
                              <td><small><?php echo $cobro->concepto;?></small></td>
                              <td><small><?php echo $cobro->descripcion;?></small></td>
                              <td><small><?php echo $cobro->fechadeuda;?></small></td>
                              <td><small><?php echo date2string($cobro->mes,$cobro->anno); ?></small></td>
                              <td><small>$&nbsp;<?php echo number_format($cobro->monto,0,".",".");?></small></td>
                            </tr>
                            <?php } ?>
                          <?php } ?>
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


<script>



$(document).ready(function() {
 
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            tiporeporte: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo de reporte es requerido'
                    }
                }
            }
        }
    })

});


</script>


  <script>
      $(function () {
        $('.table').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,50,100,-1],[10,50,100,'All']],
          "iDisplayLength": 10,
          "oLanguage": {
              "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
              "sZeroRecords": "No se encontraron registros",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
              "sInfoEmpty": "Mostrando 0 de 0 registros",
              "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
              "sSearch":        "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":    "Último",
                "sNext":    "Siguiente",
                "sPrevious": "Anterior"
            }              
          }          
        });
      });
</script> 