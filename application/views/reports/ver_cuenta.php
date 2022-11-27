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
                  <h3 class="box-title">Ver Cuenta</h3>  
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="concepto">Proveedor</label>    
                              <input type="text" class="form-control" name="proveedor" id="proveedor"  readonly value="<?php echo $existe ? $cuenta->proveedor : '';?>">
                            </div>
                          </div>                        
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="tipodoctrib">Tipo Documento Tributario</label>    
                              <input type="text" class="form-control" name="tipodoctrib" id="tipodoctrib"  readonly value="<?php echo $existe ? $cuenta->tipodocumentotributario : '';?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="periodo">Nro. Documento</label>    
                              <input type="text" class="form-control" name="nrodocumento" id="nrodocumento"  readonly value="<?php echo $existe ? $cuenta->nrodocumento : '';?>">
                            </div>
                          </div>
                          <div class='col-md-6'> 
                              <div class="form-group">                    
                              <label for="fecha">Fecha Documento</label>
                              <input type="text" class="form-control" name="fechadoc" id="fechadoc"  readonly value="<?php echo $existe ? $cuenta->fecdocumento : '';?>">
                              </div>
                          </div>
                        </div>    


                        <div class="row">
                          <div class='col-md-6'> 
                            <div class="form-group">
                              <label for="concepto">Concepto</label>    
                              <input type="text" class="form-control" name="concepto" id="concepto"  readonly value="<?php echo $existe ? $cuenta->concepto : '';?>">
                            </div>

                          </div>   
                          <div class='col-md-6'> 
                            <div class="form-group"> 

                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" name="monto" id="monto" readonly value="<?php echo $existe ? number_format($cuenta->monto,0,'.','.') : '';?>">
                              </div>
                            </div>
                          </div>    
                        </div>


                        <?php if($cuenta->idtipodoctrib == 15){ #BOLETA HONORARIOS?>

                        <div class="row">

                          <div class='col-md-6'> 
                            <div class="form-group">
                              <label for="fecvencimiento">Retenci&oacute;n</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" name="monto" id="monto" readonly value="<?php echo $existe ? number_format($cuenta->retencion,0,'.','.') : '';?>">
                              </div>
                            </div>

                          </div>                           
                          <div class='col-md-6'> 
                            <div class="form-group">
                              <label for="fecvencimiento">Fecha Vencimiento</label>    
                              <input type="text" class="form-control" name="fecvencimiento" id="fecvencimiento"  readonly value="<?php echo $existe ? $cuenta->fecvencimiento : '';?>">
                            </div>

                          </div>   
  
                        </div>


                        <div class="row">

                          <div class='col-md-6'> 
                            <div class="form-group"> 
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" readonly><?php echo $existe ? $cuenta->descripcion : '';?></textarea>
                            </div>
                          </div>    
                        </div>
                        <?php }else{ ?>
                        <div class="row">
                          <div class='col-md-6'> 
                            <div class="form-group">
                              <label for="fecvencimiento">Fecha Vencimiento</label>    
                              <input type="text" class="form-control" name="fecvencimiento" id="fecvencimiento"  readonly value="<?php echo $existe ? $cuenta->fecvencimiento : '';?>">
                            </div>

                          </div>   
                          <div class='col-md-6'> 
                            <div class="form-group"> 
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" readonly><?php echo $existe ? $cuenta->descripcion : '';?></textarea>
                            </div>
                          </div>    
                        </div>

                        <?php } ?>

                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a href="<?php echo $mensual_data ? base_url().'reports/mensual_data' :   'javascript:history.back(1)'; ?>" class="btn btn-default">Volver</a>
                  </div>
           


              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->
