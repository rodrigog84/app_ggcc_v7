        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Conciliaci&oacute;n de Movimiento</h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_conciliacion_movimiento" method="post" role="form">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="fr"><h4><span><?php echo $movimiento->glosa;?></span></h4></label><br>
                          <label for="fr"><h4><span class="label label-danger">MONTO MOVIMIENTO $ <?php echo number_format(abs($movimiento->monto),0,".",".");?></span></h4></label>
                          
                        </div>

                        
                          <div class="form-group">
                            <label for="fecprotesto">Fecha Conciliaci&oacute;n</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fecconciliacion" id="fecconciliacion" value="<?php echo is_null($movimiento->fechaconciliacion) ? date("d/m/Y") : $movimiento->fechaconciliacion;?>" placeholder="dd/mm/aaaa" >
                               
                            </div>
                          </div>
                          
                        <div id="datos_eliminacion" style="display:<?php echo is_null($movimiento->fechaconciliacion) ? 'none' : 'block';?>">
                          <div class="form-group">
                            <label for="monto">Eliminar  conciliaci&oacute;n</label> 
                            &nbsp;&nbsp;<input type="checkbox" name="eliminar" id="eliminar" class="minimal" />   
                          </div>
                        </div>
                        
                        <input type="hidden" name="fecha_actual" id="fecha_actual" value="<?php echo is_null($movimiento->fechaconciliacion) ? date("d/m/Y") : $movimiento->fechaconciliacion;?>" >
                        <input type="hidden" name="movimientoid" value="<?php echo $movimiento->id;?>" >
                        <input type="hidden" name="tipo_movimiento" value="<?php echo $movimiento->tipo_movimiento;?>" >                                                                                           
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Conciliar</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>payments/conciliacion" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->


  <script>


$(".form_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        weekStart: true,
        startView: 2,
        minView: 2,
        forceParse: 0,
        language:  'es',
        endDate: '+1d'

    })    


  </script>
<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });



$("#eliminar").on('ifChecked',function(event){
   $("#fecconciliacion").val('');


});


$("#eliminar").on('ifUnchecked',function(event){
  $("#fecconciliacion").val($('#fecha_actual').val());
});

</script>