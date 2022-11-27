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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>contabilidad/submit_saldo_inicial" method="post">   
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Cuentas&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="Saldos s&oacute;lo pueden ser modificados antes de generar el primer balance" title="Atenci&oacute;n"></i></h3>
                </div><!-- /.box-header -->
                  <div class="box-body ">
                    <table class="table table-bordered">
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>C&oacute;digo</th>
                        <th>Cuenta</th>
                        <th>Monto ($)</th>
                      </tr>
                      <tr class="success">
                        <td><b>1.</b></td>
                        <td><b>1</b></td>
                        <td><b>Activo</b></td>
                        <td>&nbsp;</td>
                      </tr>
                      <?php $i = 2; ?>
                      <?php foreach ($activos as $activo) { ?>
                      <tr>
                        <td><?php echo $i;?>.</td>
                        <td>&nbsp;&nbsp;<?php echo $activo->codigo; ?></td>
                        <td>&nbsp;&nbsp;<?php echo $activo->nombre; ?></td>
                        <td><input type="text" name="cuenta_<?php echo $activo->id;?>" id="cuenta_<?php echo $activo->id;?>" class="cuentas miles" value="<?php echo number_format($activo->valor,0,".","."); ?>" <?php echo $tiene_balance && $activo->edita == 0 ? 'disabled' : '';?>  /></td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>
                      <tr class="success">
                        <td><b><?php echo $i;?>.</b></td>
                        <td><b>2</b></td>
                        <td><b>Pasivo</b></td>
                        <td>&nbsp;</td>
                      </tr>   
                      <?php $i++; ?>   
                      <?php foreach ($pasivos as $pasivo) { ?>
                      <tr>
                        <td><?php echo $i;?>.</td>
                        <td>&nbsp;&nbsp;<?php echo $pasivo->codigo; ?></td>
                        <td>&nbsp;&nbsp;<?php echo $pasivo->nombre; ?></td>
                        <td><input type="text" name="cuenta_<?php echo $pasivo->id;?>" id="cuenta_<?php echo $pasivo->id;?>" class="cuentas miles" value="<?php echo number_format($pasivo->valor,0,".","."); ?>" <?php echo $tiene_balance && $activo->edita == 0 ? 'disabled' : '';?> /></td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>    
                      <tr class="success">
                        <td><b><?php echo $i;?>.</b></td>
                        <td><b>3</b></td>
                        <td><b>Patrimonio</b></td>
                        <td>&nbsp;</td>
                      </tr>   
                      <?php $i++; ?>                      
                      <?php foreach ($patrimonio as $patrim) { ?>
                      <tr>
                        <td><?php echo $i;?>.</td>
                        <td>&nbsp;&nbsp;<?php echo $patrim->codigo; ?></td>
                        <td>&nbsp;&nbsp;<?php echo $patrim->nombre; ?></td>
                        <td><input type="text" name="cuenta_<?php echo $patrim->id;?>" id="cuenta_<?php echo $patrim->id;?>" class="cuentas miles" value="<?php echo number_format($patrim->valor,0,".","."); ?>" <?php echo $tiene_balance && $activo->edita == 0 ? 'disabled' : '';?> /></td>
                      </tr>
                      <?php $i++; ?>
                      <?php } ?>                                                          
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                  </div>                  
              </div><!-- /.box -->
              </div>
          </div>
          </form>         
        </section><!-- /.content -->

<script>

$(document).ready(function() {
  //$.mask.definitions['~']='[+-]';
  /*$('.miles').mask('S000.000.000.000.000', {
              translation: {
                'S': {
                  pattern: /-/, optional: true
                }
              }
            }); 

});*/

        $('.miles').on('input',function(){
          $(this).val(numberFormatNegative($(this).val()));

        });


         $('.miles').keypress(function(event){
          if (!(event.keyCode == 45 || (event.keyCode >= 48  && event.keyCode <= 57)) ){
            event.preventDefault();
          } 
        })  
       


  });

</script>  



<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
      trigger : 'hover',
    html: true,});   
});
</script>
<style type="text/css">
  .bs-example{
      margin: 300px 50px;
    }
</style>