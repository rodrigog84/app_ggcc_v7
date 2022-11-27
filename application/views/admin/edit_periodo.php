        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_periodo" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="meses">Mes</label>  
                      <input type="text" class="form-control" name="mesm" id="mesm" value="<?php echo month2string($datos_form['mes']); ?>" readonly >
                      
                    </div>

                    <div class="form-group">
                      <label for="anno">A&ntilde;o</label>  
                      <input type="text" class="form-control" name="anno" id="anno" value="<?php echo $datos_form['anno']; ?>" readonly >
                    </div> 
                    
                    <div class="form-group">
                      <label for="fecvencimiento">Fecha Vencimiento</label>
                      <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input class="form-control" size="16" type="text" readonly name="fecvencimiento" id="fecvencimiento" value="<?php echo $datos_form['fecvencimiento'];?>" placeholder="dd/mm/aaaa">
                         
                      </div>
                    </div> 

                  </div><!-- /.box-body -->
                  <input type="hidden" name="mes" id="mes" value="<?php echo $datos_form['mes']; ?>" >
                  <input type="hidden" name="idperiodo" value="<?php echo $datos_form['idperiodo']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Editar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_periodo" class="btn btn-default">Volver</a>
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
    });

  </script>

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
            mes: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Mes es requerido'
                    }
                }
            },
            anno: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'A&ntilde;o es requerido'
                    }
                }
            }
        }
    })
});
</script>  