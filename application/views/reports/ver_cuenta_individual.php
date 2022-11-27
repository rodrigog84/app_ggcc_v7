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
                  <h3 class="box-title">Ver Cuenta Individual</h3>  
                </div><!-- /.box-header -->
                <div class="box-body">
                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="concepto">Concepto</label>    
                              <input type="text" class="form-control" name="concepto" id="concepto"  readonly value="<?php echo $existe ? $cuenta->concepto : '';?>">
                            </div>
                          </div>                        
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="propiedades">Propiedad</label>    
                              <input type="text" class="form-control" name="propiedades" id="propiedades"  readonly value="<?php echo $existe ? $cuenta->numero : '';?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class='col-md-6'>
                            <div class="form-group">
                              <label for="periodo">Per&iacute;odo Cargo</label>    
                              <input type="text" class="form-control" name="periodo" id="periodo"  readonly value="<?php echo $existe ? date2string($cuenta->mes,$cuenta->anno) : '';?>">
                            </div>
                          </div>
                          <div class='col-md-6'> 
                              <div class="form-group">                    
                              <label for="fecha">Fecha</label>
                              <input type="text" class="form-control" name="fecha" id="fecha"  readonly value="<?php echo $existe ? $cuenta->fechadeuda : '';?>">
                              </div>
                          </div>
                        </div>    


                        <div class="row">
                          <div class='col-md-6'> 
                            <div class="form-group"> 
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" name="monto" id="monto" readonly value="<?php echo $existe ? number_format($cuenta->monto,0,'.','.') : '';?>">
                              </div>
                            </div>
                          </div>   
                          <div class='col-md-6'> 
                            <div class="form-group"> 
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" readonly><?php echo $existe ? $cuenta->descripcion : '';?></textarea>
                            </div>
                          </div>    
                        </div>

                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                  </div>
                </div><!-- /.box-body -->                


              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->

