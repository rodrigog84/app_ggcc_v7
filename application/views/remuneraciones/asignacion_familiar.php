        <!-- Main content -->
        <section class="content" >
         <?php if(isset($message)): ?>
         <div class="row">

                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            
          </div>  
          <?php endif; ?>        
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_asignacion_familiar" id="basicBootstrapForm" method="post"> 
                <div class="box-header">
                  <h3 class="box-title">Tabla Asignaci&oacute;n Familiar</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                  <table class="table table-bordered table-striped dt-responsive">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Desde ($)</th>
                      <th>Hasta ($)</th>
                      <th>Monto Asignaci&oacute;n Familiar ($)</th>
                    </tr>
                    <?php $i = 1; ?>
                    <?php foreach ($tabla_asig_familiar as $asig_familiar) { ?>
                      <tr>
                        <td><?php echo $i;?></td>
                        <td class="form-group"><input type="text" class="form-control miles desde" name="desde_<?php echo $asig_familiar->id;?>" id="desde_<?php echo $asig_familiar->id;?>" placeholder="Ingrese Monto Desde" value="<?php echo $asig_familiar->desde; ?>"></td>
                        <td class="form-group"><?php if($asig_familiar->hasta != 999999999){ ?><input type="text" class="form-control miles hasta" name="hasta_<?php echo $asig_familiar->id;?>" id="hasta_<?php echo $asig_familiar->id;?>" placeholder="Ingrese Monto Hasta" value="<?php echo $asig_familiar->hasta; ?>"><?php }else{ ?>Y m&aacute;s<?php } ?></td>
                        <td class="form-group"><input type="text" class="form-control miles monto" name="monto_<?php echo $asig_familiar->id;?>" id="monto_<?php echo $asig_familiar->id;?>" placeholder="Ingrese Monto Asignaci&oacute;n Familiar" value="<?php echo $asig_familiar->monto; ?>"></td>
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