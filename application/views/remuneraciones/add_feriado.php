        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_feriado" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="feriado">Feriado</label>
                      <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input class="form-control" size="16" type="text" readonly name="fecha"  value="<?php echo $datos_form['fecha']; ?>" placeholder="dd/mm/aaaa">
                         
                      </div>                      
                    </div>
                  </div><!-- /.box-body -->
                  <input type="hidden" name="idferiado" value="<?php echo $datos_form['idferiado']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>remuneraciones/feriados" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>
        </section><!-- /.content -->

<script type="text/javascript">
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



</script> 



