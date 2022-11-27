        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_um_esp_comunes" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="tipocuenta">Unidad de Medida</label>
                      <input type="text" class="form-control" name="unidadmedida" id="unidadmedida" placeholder="Ingrese Nombre Unidad de Medida" value="<?php echo $datos_form['nombre']; ?>" >
                    </div>
                  </div><!-- /.box-body -->
                  <input type="hidden" name="idunidadmedida" value="<?php echo $datos_form['idunidadmedida']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success"><?php echo $boton;?></button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_um_esp_comunes" class="btn btn-default">Volver</a>
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
            tipocuenta: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo de Cuenta es requerido'
                    }
                }
            }
        }
    })

});
</script>  