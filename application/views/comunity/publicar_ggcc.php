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
                <div class="box-header ">
                  <h3 class="box-title">Listado de Per&iacute;odos Pendientes de Publicar</h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Per&iacute;odo</th>
                      <th>Monto Deuda</th>
                      <th>Fondo de Reserva</th>
                      <th>Fecha Vencimiento</th>
                      <th>Ver Detalle Gasto Com&uacute;n</th>
                      <th>Publicar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($datosdeuda) > 0){ ?>
                      <?php foreach($datosdeuda as $deuda){ ?>
                      <tr>
                        <td><?php echo date2string($deuda->mes,$deuda->anno); ?></td>
                        <td>$&nbsp;<?php echo number_format($deuda->monto_deuda,0,".",".");?></td>
                        <td>$&nbsp;<?php echo number_format($deuda->monto_fr,0,".",".");?></td>
                        <td><?php echo $deuda->fecha_vencimiento; ?></td>
                        <td>
                           <center><a href="<?php echo base_url(); ?>reports/periodos/<?php echo $deuda->periodoid; ?>" data-toggle="tooltip" title="Ver Per&iacute;odo"><span class="glyphicon glyphicon-search"></span></a></center>
                        </td>
                        <td>
                        <a href="#" data-href="<?php echo base_url(); ?>comunity/submit_publicar/<?php echo $deuda->periodoid;?>" title="Publicar" class="btn btn-xs btn-success" data-toggle="modal" data-target="#confirm-publish"><span  class="fa fa-check"></span></a>
                        <a href="<?php echo base_url(); ?>comunity/reversar_ggcc/<?php echo $deuda->periodoid;?>" data-toggle="tooltip" title="Reversar" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
                        </td>

                      </tr>
                      <?php } ?>
                    <?php }else{ ?>
                        <tr><td colspan="6">No existen per&iacute;odos para publicar</td></tr>
                    <?php } ?>
                  </tbody>
                  </table>
                  <input type="hidden" name="ggcc_valido" id="ggcc_valido">

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
                    <h4 class="modal-title" id="myModalLabel">Confirmar Publicaci&oacute;n</h4>
                </div>
            
                <div class="modal-body">
                    <p>Se publicar&aacute; el Gasto Com&uacute;n.&nbsp;&nbsp;Una vez publicado, no podr&aacute; reversar la transacci&oacute;n.  &Eacute;ste ser&aacute; enviado v&iacute;a mail durante los pr&oacute;ximos 10 minutos</p>
                    <p>Desea continuar?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-success btn-ok">Publicar</a>
                </div>
            </div>
        </div>
    </div>


<script>

$('#publicar').on('click',function(){
  $(this).addClass('disabled');
})

</script>

    <script>
        $('#confirm-publish').on('show.bs.modal', function(e) {

            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
        });
    </script>

        