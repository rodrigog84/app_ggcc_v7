        <!-- Main content -->
        <section class="content" >
        <?php if(isset($message)): ?>
         <div class="row">
            
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>

          </div>
          <br>
          <?php endif; ?> 
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Pago Cuenta</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="monto">Monto</label>  
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="monto" name="monto" class="form-control" readonly  value="<?php echo $existe ? number_format($datos_movimiento->monto,0,".",".") : '';?>" >
                          </div>  
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="proveedor">Proveedor</label>
                            <input type="text" id="proveedor" name="proveedor" class="form-control" readonly value="<?php echo $existe ? $datos_movimiento->proveedor : '';?>">
                        </div> 
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fechapago">Fecha de Pago</label>
                            <input type="text" id="fechapago" name="fechapago" class="form-control" readonly value="<?php echo $existe ? $datos_movimiento->fechapago : '';?>">
                        </div>  
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="periodo">Forma de Pago</label>
                            <input type="text" id="formas_pago" name="formas_pago" class="form-control" readonly value="<?php echo $existe ? $datos_movimiento->forma_pago : '';?>">
                        </div>              
                      </div>
                    </div>


                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="banco">Banco</label>
                            <input type="text" id="banco" name="banco" class="form-control" readonly value="<?php echo $existe ? $datos_movimiento->banco : '';?>">
                        </div>  
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="cheque">Nro. Cheque</label>  
                           <input type="text" id="cheque" name="cheque" class="form-control" readonly value="<?php echo $existe ? $datos_movimiento->cheque : '';?>">
                        </div>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="ruttitular">Rut Titular</label>  
                          <input type="text" id="ruttitular" name="ruttitular" class="form-control" readonly value="<?php echo $existe && $datos_movimiento->idformapago == 2 ? format_rut($datos_movimiento->ruttitular.$datos_movimiento->dvtitular) : '';?>">                           
                        </div> 
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="ruttitular">&nbsp;</label>                        
                          <center><a href="<?php echo base_url(); ?>reports/<?php echo $datos_movimiento->tipocuenta == 'cargo' ? 'ver_cargo' : 'ver_cuenta';?>/<?php echo $datos_movimiento->idcuenta;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a></center>    
                        </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>                    
                  </div>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->