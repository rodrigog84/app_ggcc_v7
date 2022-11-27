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
                      <select name="mes" id="mes"  class="form-control periodo"  >
                          <option value="">Seleccione un Mes</option>
                          <?php foreach ($meses as $idmes => $mes) { ?>
                            <?php //$selected_mes = $idmes == $datos_form['mes'] ? "selected" : ""; ?>
                            <option value="<?php echo $idmes;?>" <?php //echo $selected_mes;?> ><?php echo $mes;?></option>
                          <?php } ?>
                      </select>     
                    </div>

                    <div class="form-group">
                      <label for="anno">A&ntilde;o</label>  
                      <select name="anno" id="anno"  class="form-control periodo"  >
                          <option value="">Seleccione un A&ntilde;o</option>
                          <?php foreach ($annos as $anno) { ?>
                            <?php //$selected_anno = $anno == $datos_form['anno'] ? "selected" : ""; ?>
                            <option value="<?php echo $anno;?>" <?php //echo $selected_anno;?> ><?php echo $anno;?></option>
                          <?php } ?>
                      </select>  
                    </div> 
                    
                    <div class="form-group">
                      <label for="fecvencimiento">Fecha Vencimiento</label>
                      <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input class="form-control" size="16" type="text" readonly name="fecvencimiento" id="fecvencimiento" value="<?php echo date("d/m/Y");?>" placeholder="dd/mm/aaaa">
                         
                      </div>
                    </div> 

                  </div><!-- /.box-body -->
                  <input type="hidden" name="idperiodo" value="0" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
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
        //startDate: new Date()  
    });

  </script>

<script>
$(document).ready(function() {


$('.periodo').change(function(){
    $('#basicBootstrapForm').formValidation('revalidateField', 'anno');

});

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
                    },
                    /*remote: {
                        url: '<?php echo base_url();?>admins/permite_periodo/',
                        // Send { email: 'its value', username: 'its value' } to the back-end
                        data: function(validator, $field, value) {
                            return {
                                mes: $('#mes').val()
                            };
                        },
                        message: 'Per&iacute;odo no permitido para esta comunidad',
                        type: 'POST'
                    } */                   
                }
            }
        }
    })
});
</script>  