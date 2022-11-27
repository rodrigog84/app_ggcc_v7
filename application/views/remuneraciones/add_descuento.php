        <!-- Main content -->
        <section class="content" >
          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Descuento/Prestamo <?php echo isset($mes) ? date2string($mes,$anno) : ''; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_descuento" method="post" role="form" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="tipo_descuento">Tipo Descuento</label>  
                              <?php $tdescuento = ""; ?>  
                              <select name="tipo_descuento" id="tipo_descuento"  class="form-control" >
                                  <option value="">Seleccione Tipo de Descuento</option>
                                    <?php foreach ($tipo_descuentos as $tipo_descuento) { ?>
                                        <?php if($tipo_descuento->tipo != $tdescuento){
                                                if($tdescuento != ''){
                                                    echo "</optgroup>";
                                                }

                                                $tipo_dcto =  $tipo_descuento->tipo == 'P' ? "Prestamo" : "Descuento";
                                                echo "<optgroup label='". $tipo_dcto . "'>";
                                                $tdescuento = $tipo_descuento->tipo;
                                        } ?>
                                          <?php //$afpselected = $afp->id == $trabajador->idafp ? "selected" : ""; ?>
                                          <?php $tdescuento_selected = ""; ?>
                                          <option value="<?php echo $tipo_descuento->id;?>" <?php echo $tdescuento_selected;?> ><?php echo $tipo_descuento->nombre;?></option>
                                    <?php } 
                                          if($tdescuento != ''){
                                            echo "</optgroup>";
                                          }
                                          ?>                                
                              </select>
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="trabajador">Trabajador</label>    
                              <select name="trabajador" id="trabajador"  class="form-control" >
                                  <option value="">Seleccione un Trabajador</option>
                                  <?php foreach ($personal as $trabajador) { ?>
                                      <option value="<?php echo $trabajador->id;?>" ><?php echo $trabajador->nombre." ".$trabajador->apaterno." ".$trabajador->amaterno;?></option>
                                  <?php } ?>
                              </select>
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="monto">Monto</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Monto">
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Descripci&oacute;n</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                            <p class="help-block">(*) El descuento/pr&eacute;stamo se visualizar&aacute; en liquidaci&oacute;n de sueldos seg&uacute;n esta descripci&oacute;n.</p>
                        </div>  
                      </div>                                         
                    </div>

                    <!--input type="hidden" name="idcuenta" id="idcuenta" value="0" -->
                    <input type="hidden" name="mes" id="mes" value="<?php echo $mes; ?>" >
                    <input type="hidden" name="anno" id="anno" value="<?php echo $anno; ?>" >

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>remuneraciones/descuentos" class="btn btn-default">Volver</a>                    
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
            tipo_descuento: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tipo de descuento es requerido'
                    }
                }
            },
            trabajador: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Trabajador es requerido'
                    }
                }
            },            
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                    /*regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    } */                   

                }
            },
            descripcion: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Descripci&oacute;n es requerida'
                    },
                    stringLength: {
                        max: 100,
                        message: 'Descripci&oacute;n no puede tener m&aacute;s de 100 caracteres'
                    }                    
                }
            }            
        }
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true});          

});

  $('.miles').keypress(function(event){
    if ((event.keyCode < 48)||(event.keyCode > 57)){
      event.preventDefault();
    } 
  })          
</script>  