        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_isapre" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="isapre">Isapre</label>
                      <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese Nombre Isapre" value="<?php echo $datos_form['nombre']; ?>" >
                    </div>
                  </div><!-- /.box-body -->
                  <input type="hidden" name="idisapre" value="<?php echo $datos_form['idisapre']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>remuneraciones/isapres" class="btn btn-default">Volver</a>
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
            nombre: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Nombre Isapre es requerido'
                    }
                }
            }
        }
    })

});
</script>  
