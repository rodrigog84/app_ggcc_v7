        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Comprobante</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>accounts/submit_cuenta" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="proveedor">Proveedor</label>    
                              <input type="text" class="form-control" name="provee" id="provee"  readonly value="<?php echo $existe ? $cuenta->proveedor : '';?>">
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="tipodoc">Tipo Documento Tributario</label>    
                              <input type="text" class="form-control" name="tipodoctrib" id="tipodoctrib"  readonly value="<?php echo $existe ? $cuenta->tipodocumentotributario : '';?>">
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="documento">Nro. Documento</label>    
                              <input type="text" class="form-control" name="documento" id="documento"  readonly value="<?php echo $existe ? $cuenta->nrodocumento : '';?>">
                        </div>   
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Documento</label>
                                <input type="text" class="form-control" name="fecdocumento" id="fecdocumento"  readonly value="<?php echo $existe ? $cuenta->fecdocumento : '';?>">
                        </div> 
                      </div>  
                    </div>
                    
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="concepto">Concepto</label>  
                              <input type="text" class="form-control" name="concep" id="concep"  readonly value="<?php echo $existe ? $cuenta->concepto : '';?>">
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

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fecvencimiento">Fecha Vencimiento</label>
                            <input type="text" class="form-control" name="fecvencimiento" id="fecvencimiento"  readonly value="<?php echo $existe ? $cuenta->fecvencimiento : '';?>">                        </div>   
                      </div>
                      <div class='col-md-6'> 
                        <div class="form-group"> 
                          <label for="descripcion">Descripci&oacute;n</label>    
                          <textarea class="form-control" rows="3" name="descripcion" id="descripcion" readonly><?php echo $existe ? $cuenta->descripcion : '';?></textarea>
                        </div>
                      </div>                          
                    </div> 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Adjuntar Comprobante</label>
                                  <input type="file" id="userfile" name="userfile">
                                </div>
                        </div>  
                      </div> 
                      <div class='col-md-6'>
                          <?php if($cuenta->nombrearchivo != ''){ ?>
                            <center><a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><h4 class="glyphicon glyphicon-paperclip"></h4></a></center>
                          <?php } ?>
                      </div>                      
                      <input type="hidden" name="idcuenta" id="idcuenta" value="<?php echo $cuenta->id; ?>" >
                      <input type="hidden" name="idperiodo" id="idperiodo" value="<?php echo $idperiodo; ?>" >
                      <input type="hidden" name="tipoguarda" id="tipoguarda" value="<?php echo $tipoguarda; ?>" >
                      <input type="hidden" name="proveedor" id="proveedor"  value="<?php echo $existe ? $cuenta->idproveedor : '';?>">
                      <input type="hidden" name="concepto" id="concepto"  value="<?php echo $existe ? $cuenta->idtipodeudadetalle : '';?>">
                      <input type="hidden" name="formapago" id="formapago"  value="<?php echo $existe ? $cuenta->formapago : '';?>">
                      <input type="hidden" name="tipodoc" id="tipodoc"  value="<?php echo $existe ? $cuenta->idtipodoctrib : '';?>">
                     
                    </div>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success" <?php if (!$existe){ echo "disabled";} ?> >Agregar</button>
                    &nbsp;&nbsp;
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->


