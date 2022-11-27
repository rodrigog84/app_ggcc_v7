        <?php if(isset($message)): ?>
        <section class="content" >
         <div class="row">
            
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>

          </div>

          <?php endif; ?>                 
        <!-- Main content -->
        <section class="content" >
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Ver Descuento/Prestamo</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_descuento" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="tipo_descuento">Tipo Descuento</label>  
                              <input name="tipo_descuento" id="tipo_descuento"  class="form-control" value="<?php if($existe) {echo $descuento->tipo == 'P' ? 'Pr&eacute;stamo | ' . $descuento->nombre_tipo : 'Descuento | ' . $descuento->nombre_tipo; }else{ echo '';} ?>" readOnly >
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="trabajador">Trabajador</label>    
                              <input name="trabajador" id="trabajador"  class="form-control" value="<?php echo $existe ? $descuento->nombre.' '.$descuento->apaterno.' '.$descuento->amaterno : '';?>" readOnly >
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto" value="<?php echo $existe ? number_format($descuento->monto,0,".",".") : '';?>" readOnly>
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Descripci&oacute;n</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion" readonly><?php echo $existe ? $descuento->descripcion : '';?></textarea>
                        </div>  
                      </div>                                         
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo base_url();?>remuneraciones/descuentos" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->
