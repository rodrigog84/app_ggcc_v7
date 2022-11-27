        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_cargos" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="cargo">Cargos Personal</label>
                      <input type="text" class="form-control" name="cargo" id="cargo" placeholder="Ingrese Nombre Cargo" value="<?php echo $datos_form['nombre']; ?>" >
                    </div>
                    <div class="form-group">
                          <label for="tipocargo">Tipo de Cargo</label>    
                          <select name="tipocargo" id="tipocargo"  class="form-control"  >
                              <option value="">Selecciona un Tipo de Cargo</option>
                              <?php foreach ($cargos_padres as $cargo_padre) { ?>
                                  <?php $padresselected = $cargo_padre->id == $datos_form['padre'] ? "selected" : ""; ?>
                                  <option value="<?php echo $cargo_padre->id;?>" <?php echo $padresselected;?> ><?php echo $cargo_padre->nombre;?></option>
                              <?php } ?>                                
                          </select>
                    </div>                     
                  </div><!-- /.box-body -->
                  <input type="hidden" name="idcargo" value="<?php echo $datos_form['idcargo']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_cargos" class="btn btn-default">Volver</a>
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
            cargo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Cargo es requerido'
                    }
                }
            }
        }
    })

});
</script>  