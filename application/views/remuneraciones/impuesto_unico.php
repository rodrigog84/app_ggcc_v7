        <!-- Main content -->
        <section class="content" >
         <?php if(isset($message)): ?>
         <div class="row">
            <div class="col-md-12">
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            </div>
            
          </div>  
          <?php endif; ?>        
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_impuesto_unico" id="basicBootstrapForm" method="post"> 
                <div class="box-header">
                  <h3 class="box-title">Tabla Impuesto &Uacute;nico</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                  <table class="table table-bordered table-striped dt-responsive">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Desde ($)</th>
                      <th>Hasta ($)</th>
                      <th>Factor</th>
                      <th>Rebaja ($)</th>
                    </tr>
                    <?php $i = 1; ?>
                    <?php foreach ($tabla_impuesto as $impuesto) { ?>
                      <tr>
                        <td><?php echo $i;?></td>
                        <td class="form-group"><input type="text" class="form-control miles desde" name="desde_<?php echo $impuesto->id;?>" id="desde_<?php echo $impuesto->id;?>" placeholder="Ingrese Monto Desde" value="<?php echo $impuesto->desde; ?>"></td>
                        <td class="form-group"><?php if($impuesto->hasta != 999999999){ ?><input type="text" class="form-control miles hasta" name="hasta_<?php echo $impuesto->id;?>" id="hasta_<?php echo $impuesto->id;?>" placeholder="Ingrese Monto Hasta" value="<?php echo $impuesto->hasta; ?>"><?php }else{ ?>Y m&aacute;s<?php } ?></td>
                        <td class="form-group"><input type="text" class="form-control  factor" name="factor_<?php echo $impuesto->id;?>" id="factor_<?php echo $impuesto->id;?>" placeholder="Ingrese Factor" value="<?php echo number_format($impuesto->factor,3,".",","); ?>"></td>
                        <td class="form-group"><input type="text" class="form-control miles rebaja" name="rebaja_<?php echo $impuesto->id;?>" id="rebaja_<?php echo $impuesto->id;?>" placeholder="Ingrese Monto Rebaja" value="<?php echo $impuesto->rebaja; ?>"></td>
                      </tr>
                      <?php $i++; ?>
                    <?php } ?>
                  </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;
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
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            desde: {
                selector: '.desde',
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto desde requerido'
                    }
                }
            },
            hasta: {
                selector: '.hasta',
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto hasta requerido'
                    }
                }
            },
            factor: {
                selector: '.factor',
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Porcentaje es requerido'
                    },
                    between: {
                        min: 0,
                        max: 100,
                        message: 'El porcentaje debe estar entre 0 y 100'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },

                }
            },            
        }
    })
    
});

$(document).ready(function(){
 $('.miles').mask('000.000.000.000.000', {reverse: true})        

});

$(document).ready(function(){
 $('.miles_decimales').mask('#.###0,000', {reverse: true})        

});
</script>          