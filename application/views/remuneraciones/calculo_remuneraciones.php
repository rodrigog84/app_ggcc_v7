        <!-- Main content -->
        <section class="content">
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
                <div class="box-header">
                  <h3 class="box-title">Listado de Per&iacute;odos para C&aacute;lculo de Remuneraciones&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="Para calcular las remuneraciones de un per&iacute;odo, los datos de todos los trabajadores deben estar completos, y obligatoriamente se debe almacenar la siguiente informaci&oacute;n:<br>
                                                                                                                                                                                            <ul><li>Informaci&oacute;n Asistencia</li></ul>
                                                                                                                                                                                            <ul><li>Informaci&oacute;n Descuentos</li></ul>
                                                                                                                                                                                            <ul><li>Informaci&oacute;n Horas Extras</li></ul>
                                                                                                                                                                                            <ul><li>Informaci&oacute;n Anticipos/Aguinaldo</li></ul>" title="Atenci&oacute;n"></i></h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Per&iacute;odo</th>
                      <th>Estado</th>
                      <th>Acci&oacute;n</th>
                      <th>Ver Detalle Remuneraciones</th>
                      <th>Validar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($periodos_remuneracion) + count($periodos_remuneracion_sin_abonos) > 0){ ?>
                      <?php foreach($periodos_remuneracion as $periodo){ ?>
                      <tr>
                        <td><?php echo date2string($periodo->mes,$periodo->anno); ?></td>

                        <td><span class="<?php echo $periodo->estado == 'Informaci&oacute;n Completa' ? 'text-green' : 'text-red';?>" /><?php echo $periodo->estado; ?></span>&nbsp;&nbsp;
                        <?php if($periodo->estado == 'Falta Informaci&oacute;n'){ ?><i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="<?php echo $mensaje_html[$periodo->id];?>" title="Datos Pendientes:"></i><?php } ?>
                        </td>
                        <td>
                          <?php if($periodo->estado == 'Informaci&oacute;n Completa' && is_null($periodo->cierre)){ ?>
                          <a href="<?php echo base_url(); ?>remuneraciones/submit_calculo_remuneraciones/<?php echo $periodo->id; ?>" data-toggle="tooltip" id="btn_calculo" title="Calculo Remuneraciones" class="btn btn-block btn-xs btn-primary">Calcular</a>
                          <?php }else{ ?>
                            &nbsp;
                          <?php } ?>
                        </td>
                          <td>
                        <?php if($periodo->estado == 'Informaci&oacute;n Completa' && !is_null($periodo->cierre)){ ?>
                             <center><a href="<?php echo base_url(); ?>remuneraciones/periodos/<?php echo $periodo->id; ?>" data-toggle="tooltip" title="Ver Per&iacute;odo"><span class="glyphicon glyphicon-search"></span></a></center>
                        <?php }else{  ?>
                           &nbsp;
                        <?php } ?>
                        </td>
                        <td>
                          <?php if($periodo->estado == 'Informaci&oacute;n Completa' && !is_null($periodo->cierre)){ ?>
                            <a href="#" data-href="<?php echo base_url(); ?>remuneraciones/aprueba_remuneraciones/<?php echo $periodo->id; ?>" data-toggle="modal" data-target="#confirm-publish" title="Aprobar" class="btn btn-xs btn-success"><span class="fa fa-check"></span></a>
                            <a href="<?php echo base_url(); ?>remuneraciones/rechaza_remuneraciones/<?php echo $periodo->id; ?>" data-toggle="tooltip" title="Rechazar" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
                          <?php }else{ ?>
                            &nbsp;
                          <?php } ?>
                        </td>                        
                                             
                                                    
                      </tr>
                      <?php } ?>

                      <?php foreach($periodos_remuneracion_sin_abonos as $periodo_sin_abonos){ ?>
                      <tr>
                        <td><?php echo date2string($periodo_sin_abonos->mes,$periodo_sin_abonos->anno); ?></td>

                        <td><span class="text-green" />Informaci&oacute;n Completa</span>&nbsp;&nbsp;</td>
                        <td>&nbsp;
                        </td>
                          <td>
                             <center><a href="<?php echo base_url(); ?>remuneraciones/periodos/<?php echo $periodo_sin_abonos->id; ?>" data-toggle="tooltip" title="Ver Per&iacute;odo"><span class="glyphicon glyphicon-search"></span></a></center>
                        </td>
                        <td>
                            <a href="<?php echo base_url(); ?>remuneraciones/reversar_aprobacion_remuneraciones/<?php echo $periodo_sin_abonos->id; ?>" data-toggle="tooltip" title="Reversar Aprobaci&oacute;n" class="btn btn-xs btn-danger"><span class="fa fa-undo"></span></a>
                        </td>                        
                                             
                                                    
                      </tr>
                      <?php } ?>                      
                    <?php }else{ ?>
                    <tr>
                      <td colspan="5">No existen per&iacute;odos para C&aacute;lculo de Remuneraciones</td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  </table>
                </div><!-- /.box-body -->


              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->

    <div class="modal fade" id="confirm-publish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmar Aprobaci&oacute;n</h4>
                </div>
            
                <div class="modal-body">
                    <p>Se traspasar&aacute; la informaci&oacute;n de remuneraciones.&nbsp;&nbsp;Una vez aprobado, no podr&aacute; reversar la transacci&oacute;n.</p>
                    <p>Desea continuar?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-success btn-ok">Aprobar</a>
                </div>
            </div>
        </div>
    </div>


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


    <script>
        $('#confirm-publish').on('show.bs.modal', function(e) {

            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
        });

        $('#btn_calculo').on('click',function(){

          $(this).attr('disabled','disabled');
        })
    </script>