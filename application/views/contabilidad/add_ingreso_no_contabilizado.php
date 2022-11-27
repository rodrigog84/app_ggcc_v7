        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Agregar Ingreso No Contabilizado</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>contabilidad/submit_ingreso_no_contabilizado" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                        <label for="fecdocumento">Fecha Ingreso</label>
                         <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            <input class="form-control" size="16" type="text" readonly name="fecingreso" id="fecingreso" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/aaaa">
                             
                         </div>
                    </div>
                    <div class="form-group">
                        <label for="monto">Monto</label>    
                        <div class="input-group">
                          <span class="input-group-addon">$</span>
                          <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Monto">
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="descripcion">Descripci&oacute;n</label>    
                        <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                    </div>                    

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>contabilidad/ingresos_no_contabilizados" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>
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
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                }
            },
        }
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true});          

});


</script>  

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
    });

  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })      

  </script>