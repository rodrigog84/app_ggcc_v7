        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-6">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_esp_comun" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="espaciocomun">Espacio Com&uacute;n</label>
                      <input type="text" class="form-control" name="espaciocomun" id="espaciocomun" placeholder="Ingrese Nombre Espacio Com&uacute;n" value="<?php echo $datos_form['nombre']; ?>" >
                    </div>
                    <div class="form-group">
                      <label for="espaciocomun">Unidad de medida uso</label>
                      <select class="form-control" name="unidadmedida" id="unidadmedida" >
                        <option value="">Seleccione una Unidad de Medida</option>
                        <?php foreach ($unidades_medidas as $unidad_medida) { ?>
                            <?php $umselected = $unidad_medida->id == $datos_form['unidadmedida'] ? "selected" : ""; ?>
                            <option value="<?php echo $unidad_medida->id;?>" <?php echo $umselected; ?>><?php echo $unidad_medida->nombre;?></option>
                        <?php } ?>                          
                      </select>
                    </div> 
                    <div class="form-group">
                      <label for="espaciocomun">Valor por unidad de medida ($)</label>
                      <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Ingrese Monto" value="<?php echo $datos_form['monto']; ?>" >
                      </div>
                    </div>                                       
                  </div><!-- /.box-body -->
                  <input type="hidden" name="idespaciocomun" value="<?php echo $datos_form['idespaciocomun']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_esp_comunes" class="btn btn-default">Volver</a>
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
            espaciocomun: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Espacio Com&uacute;n es requerido'
                    }
                }
            },
            unidadmedida: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Unidad de medida es requerida'
                    }                  
                }
            },  
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                }
            },                      
        }
    }).find('.miles').mask('000.000.000.000.000', {reverse: true}); 

});


  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })     
</script>  
