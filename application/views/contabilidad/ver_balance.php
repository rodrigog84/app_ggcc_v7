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

                  
        <?php if($balance->aprueba == ''): ?>
         <div class="row">
            <div class="col-md-12">
                    <div class="callout callout-warning">
                     <h4><i class="icon fa fa-info"></i>&nbsp;Atenci&oacute;n!</h4>
                      El balance visualizado a&uacute;n no ha sido aprobado.
                    </div>
              </div>
          </div>
        <?php endif; ?>        
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Ver Balance &nbsp;<?php echo date2string($balance->mes,$balance->anno); ?></h3>
                </div><!-- /.box-header -->
                  <div class="box-body ">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>C&oacute;digo</th>
                          <th>Cuenta</th>
                          <th>Acci&oacute;n</th>
                          <th>Debe ($)</th>
                          <th>Haber ($)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="success">
                          <td><b>1.</b></td>
                          <td><b>1</b></td>
                          <td><b>Activo</b></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <?php $i = 2; ?>
                        <?php foreach ($activos as $activo) { ?>
                        <tr>
                          <td><?php echo $i;?>.</td>
                          <td>&nbsp;&nbsp;<?php echo $activo->codigo; ?></td>
                          <td>&nbsp;&nbsp;<?php echo $activo->nombre; ?></td>
                          <td>
                              <?php if($activo->manual == 1){ ?>
                                <?php if($balance->aprueba == ''){ ?>
                                  <a href="<?php echo base_url(); ?>contabilidad/set_cuenta_balance/<?php echo $idperiodo;?>/<?php echo $activo->id;?>" data-toggle="tooltip" title="Editar" ><i class="fa fa-edit"></i></a>
                                <?php }else{ echo "- "; }  ?>
                              <?php }else{ ?>
                                  <a href="<?php echo base_url(); ?>contabilidad/ver_cuenta_balance/<?php echo $idperiodo;?>/<?php echo $activo->id;?>" data-toggle="tooltip" title="Ver" ><i class="fa fa-caret-square-o-right"></i></a>
                              <?php } ?>
                          </td>
                          <td>&nbsp;&nbsp;<?php echo $activo->tipo == 'DEBE' ? number_format($activo->valor,0,".",".") : "0"; ?></td>
                          <td>&nbsp;&nbsp;<?php echo $activo->tipo == 'HABER' ? number_format($activo->valor,0,".",".") : "0"; ?></td>
                        </tr>
                        <?php $i++; ?>
                        <?php } ?>
                        <tr class="success">
                          <td><b><?php echo $i;?>.</b></td>
                          <td><b>2</b></td>
                          <td><b>Pasivo</b></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>   
                        <?php $i++; ?>   
                        <?php foreach ($pasivos as $pasivo) { ?>
                        <tr>
                          <td><?php echo $i;?>.</td>
                          <td>&nbsp;&nbsp;<?php echo $pasivo->codigo; ?></td>
                          <td>&nbsp;&nbsp;<?php echo $pasivo->nombre; ?></td>
                          <td>
                              <?php if($pasivo->manual == 1){ ?>
                                <?php if($balance->aprueba == ''){ ?>
                                  <a href="<?php echo base_url(); ?>contabilidad/set_cuenta_balance/<?php echo $idperiodo;?>/<?php echo $pasivo->id;?>" data-toggle="tooltip" title="Editar" ><i class="fa fa-edit"></i></span></a>
                                <?php }else{ echo "- "; }  ?>
                              <?php }else{ ?>
                                  <a href="<?php echo base_url(); ?>contabilidad/ver_cuenta_balance/<?php echo $idperiodo;?>/<?php echo $pasivo->id;?>" data-toggle="tooltip" title="Ver" ><i class="fa fa-caret-square-o-right"></i></a>
                              <?php } ?>
                          </td>
                          <td>&nbsp;&nbsp;<?php echo $pasivo->tipo == 'DEBE' ? number_format($pasivo->valor,0,".",".") : "0"; ?></td>
                          <td>&nbsp;&nbsp;<?php echo $pasivo->tipo == 'HABER' ? number_format($pasivo->valor,0,".",".") : "0"; ?></td>
                        </tr>
                        <?php $i++; ?>
                        <?php } ?>    
                        <tr class="success">
                          <td><b><?php echo $i;?>.</b></td>
                          <td><b>3</b></td>
                          <td><b>Patrimonio</b></td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>   
                        <?php $i++; ?>                      
                        <?php foreach ($patrimonio as $patrim) { ?>
                        <tr>
                          <td><?php echo $i;?>.</td>
                          <td>&nbsp;&nbsp;<?php echo $patrim->codigo; ?></td>
                          <td>&nbsp;&nbsp;<?php echo $patrim->nombre; ?></td>
                          <td>
                              <?php if($patrim->manual == 1){ ?>
                                <?php if($balance->aprueba == ''){ ?>
                                  <a href="<?php echo base_url(); ?>contabilidad/set_cuenta_balance/<?php echo $idperiodo;?>/<?php echo $patrim->id;?>" data-toggle="tooltip" title="Editar" ><i class="fa fa-edit"></i></span></a>
                                <?php }else{ echo "- "; } ?>
                              <?php }else{ ?>
                                  <a href="<?php echo base_url(); ?>contabilidad/ver_cuenta_balance/<?php echo $idperiodo;?>/<?php echo $patrim->id;?>" data-toggle="tooltip" title="Ver" ><i class="fa fa-caret-square-o-right"></i></a>
                              <?php } ?>
                          </td>
                          <td>&nbsp;&nbsp;<?php echo $patrim->tipo == 'DEBE' ? number_format($patrim->valor,0,".",".") : "0"; ?></td>
                          <td>&nbsp;&nbsp;<?php echo $patrim->tipo == 'HABER' ? number_format($patrim->valor,0,".",".") : "0"; ?></td>
                        </tr>
                        <?php $i++; ?>
                        <?php } ?>                                                          
                      </tbody>
                      <tfoot>
                        <th colspan="4">Totales</th>
                        <th><?php echo number_format($balance->debe,0,".",".");?></th>
                        <th><?php echo number_format($balance->haber,0,".",".");?></th>
                      </tfoot>
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo base_url();?>contabilidad/<?php echo $balance->aprueba == '' ? 'generar_balance' : 'balances_aprobados';?>" class="btn btn-default">Volver</a>
                  </div>                  
              </div><!-- /.box -->
              </div>
          </div>
       
        </section><!-- /.content -->



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