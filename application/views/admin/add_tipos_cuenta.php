        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_tipos_cuenta" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="tipocuenta">Tipo de Cuenta</label>
                      <input type="text" class="form-control" name="tipocuenta" id="tipocuenta" placeholder="Ingrese Nombre Tipo de Cuenta" value="<?php echo $datos_form['nombre']; ?>" <?php echo is_null($datos_form['idcomunidad']) ? 'readonly': '';?> >
                    </div>
                    <div class="form-group">
                          <label for="concepto">Concepto Asociado</label>    
                          <?php if(is_null($datos_form['idcomunidad'] )){ ?>

                            <input type="text" class="form-control" value="<?php echo $datos_form['nombreconcepto'];?>" readonly>
                            <input type="hidden" name="concepto" id="concepto" value="<?php echo $datos_form['concepto']; ?>" >

                          <?php }else{ ?>
                            <select name="concepto" id="concepto"  class="form-control"  >
                                <option value="">Seleccione un concepto</option>
                                <?php foreach ($conceptos as $concepto) { ?>
                                    <?php $conceptoselected = $concepto->id == $datos_form['concepto'] ? "selected" : ""; ?>
                                    <option value="<?php echo $concepto->id;?>" <?php echo $conceptoselected;?> ><?php echo $concepto->nombre;?></option>
                                <?php } ?>                                
                            </select>
                          <?php } ?>
                    </div>  
                    <div class="form-group">
                          <label for="concepto">Clasificaci&oacute;n Cuenta</label>    
                          <select name="tipo_cuenta" id="tipo_cuenta"  class="form-control" <?php echo count($tipos_cuenta) > 0  && $datos_form['concepto'] == ''  ? '' : 'disabled' ; ?>>
                              <option value="">Seleccione Clasificaci&oacute;n de Cuenta</option>
                              <?php foreach ($tipos_cuenta as $tipo_cuenta) { ?>
                                  <?php $tipo_cuentaselected = $tipo_cuenta->id == $datos_form['tipo_cuenta'] ? "selected" : ""; ?>
                                  <option value="<?php echo $tipo_cuenta->id;?>" <?php echo $tipo_cuentaselected;?> ><?php echo $tipo_cuenta->nombre;?></option>
                              <?php } ?>                                
                          </select>
                    </div>

                  </div><!-- /.box-body -->
                  <input type="hidden" name="idtipocuenta" value="<?php echo $datos_form['idtipocuenta']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_tipos_cuenta" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>
        </section><!-- /.content -->


<script>
$(document).ready(function() {

    $('#concepto').on('change',function(){

      $('#basicBootstrapForm').formValidation('revalidateField', 'tipo_cuenta');
      $('#basicBootstrapForm').formValidation('updateStatus', 'tipo_cuenta','NOT_VALIDATED');

      if($(this).val() == '' && $('#tipo_cuenta > option').length > 1){
        $('#tipo_cuenta').prop('disabled','');
      }else{
        $('#tipo_cuenta').val('');
        $('#tipo_cuenta').prop('disabled','disabled');
      }


    })

    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
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
            },
            tipo_cuenta: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Clasificaci&oacute;n de Cuenta es requerido'
                    }
                }
            }
        }
    })

});
</script>  