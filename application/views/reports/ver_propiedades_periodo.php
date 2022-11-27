        <!-- Main content -->
        <section class="content" >
        <?php if($datosperiodo->publica == ''){ ?>
         <div class="row">
            <div class="col-md-12">
                    <div class="callout callout-warning">
                     <h4><i class="icon fa fa-info"></i>&nbsp;Atenci&oacute;n!</h4>
                      El per&iacute;odo visualizado no ha sido publicado ni considerado en los saldos del per&iacute;odo.
                    </div>
              </div>
          </div>
        <?php }else{ ?>
        <div class="row">
          <div class="col-md-12">
            <button class="btn btn-success" id="envio_comprobantes">Volver a Enviar Comprobantes</button>
          </div>
        </div>

        <?php } ?>
          <br>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
              <h3 class="box-title">Listado de Propiedades.  <?php echo date2string($datosperiodo->mes,$datosperiodo->anno);?></h3>    <?php if(count($propiedades) > 0 ){ ?>
                  <div class="pull-right box-tools">
                      <h4><a href="<?php echo base_url(); ?>reports/export_mensual_data/cgc/<?php echo $datosperiodo->mes;?>/<?php echo $datosperiodo->anno;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                  </div>
                  <?php } ?> 
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nro.</th>
                        <th>Responsable</th>
                        <th>Prorrateo</th>
                        <?php if($idperiodo != $periodo_inicial->id){ ?>
                        <th>Cobro del Mes</th>
                        <?php } ?>
                        <th>Saldo Anterior</th>
                        <th>Cobro Total</th>
                        <!--th>Abonado</th>
                        <th>Saldo</th-->
                        <?php if($idperiodo != $periodo_inicial->id){ ?>
                        <th>Detalle Gasto Com&uacute;n</th>
                        <th>Pagos</th>                        
                        <th>Comprobante</th>
                        <?php if($datosperiodo->publica != ''){ ?>
                        <th>Reenviar Comprobante</th>
                        <?php } ?>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($propiedades as $propiedad) { ?>
                         <tr >
                          <td><?php echo $i;?></td>
                          <td><?php echo $propiedad->numero;?></td>
                          <td><?php echo $propiedad->responsable;?></td>
                          <td><?php echo $propiedad->prorrateo;?>&nbsp;<span style="font-size: 10px">%</span></td>
                          <?php if($idperiodo != $periodo_inicial->id){ ?>
                          <td>$&nbsp;<?php echo number_format($propiedad->monto,0,".",".");?></td>
                          <?php } ?>
                          <td>$&nbsp;<?php echo number_format($propiedad->saldo_anterior,0,".",".");?></td>
                          <td>$&nbsp;<?php echo $idperiodo != $periodo_inicial->id ? number_format($propiedad->monto+$propiedad->saldo_anterior > 0 ? $propiedad->monto+$propiedad->saldo_anterior : 0,0,".",".") : number_format($propiedad->saldo_anterior > 0 ? $propiedad->saldo_anterior : 0,0,".",".");?></td>
                          <!--td>$&nbsp;<?php echo number_format($propiedad->abonado,0,".",".");?></td>
                          <td>$&nbsp;<?php echo number_format($propiedad->saldo,0,".",".");?></td-->
                          <?php if($idperiodo != $periodo_inicial->id){ ?>
                          <td><center><a href="<?php echo base_url();?>payments/ver_detalle/<?php echo $propiedad->ggccid;?>/1"  ><span class="glyphicon glyphicon-search" data-toggle="tooltip" title="Ver Detalle GGCC"></span></a></center></td>                      
                          <td><center><a href="<?php echo base_url();?>payments/ver_cartola/<?php echo $propiedad->id;?>/<?php echo $propiedad->idperiodo;?>" data-toggle="tooltip" title="Ver Pagos" ><span class="glyphicon glyphicon-search"></span></a></center></td>
                          <td><center><a href="<?php echo base_url(); ?>payments/download_ggcc/<?php echo $propiedad->id."/".$propiedad->idperiodo;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a></center></td>
                          <?php if($datosperiodo->publica != ''){ ?>
                          <td>
                            <center><a href="#" class="reenvia_comprobante" id="reenvia-<?php echo $propiedad->id;?>" data-toggle="tooltip" title="Reenviar"  ><span class="fa fa-mail-forward input-sm"></span></a></center>
                          </td> 
                          <?php } ?>
                          <?php } ?>

                        </tr>
                        <?php $i++; } ?>
                     </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                  </div>                  
                </div>
              </div>
          </div>
        </section><!-- /.content -->

  <script>
      $(function () {
        $('.table').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[15,30,45,100,-1],[15,30,45,100,'All']],
          "iDisplayLength": 15,
          "oLanguage": {
              "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
              "sZeroRecords": "No se encontraron registros",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
              "sInfoEmpty": "Mostrando 0 de 0 registros",
              "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
              "sSearch":        "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":    "Último",
                "sNext":    "Siguiente",
                "sPrevious": "Anterior"
            }              
          }          
        });
      });
</script>  
<script>

$(function(){
  $('.reenvia_comprobante').on('click',function(){
      var id = $(this).attr('id');
      var array_id = id.split("-");
      var idpropiedad = array_id[1];
      var idperiodo = <?php echo $idperiodo;?>;
      $.LoadingOverlay("show",{
        color           : "rgba(255, 255, 255, 0.8)", 
      });
      $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>reports/reenviar_comprobante/'+ idperiodo + '/' + idpropiedad,
            dataType: 'json'
        }).success(function(response) {
            $.LoadingOverlay("hide");
            $.gritter.add({
              title: 'Atenci&oacute;n!',
              text: 'Se ha reenviado el comprobante de Gastos Comunes. ',
              image: '<?php echo base_url(); ?>img/send_mail.png',
              sticky: false,
              time: 3000
            });           
        });
  

  })


  $('#envio_comprobantes').click(function(){


      var idperiodo = <?php echo $idperiodo;?>;
      $.LoadingOverlay("show",{
        color           : "rgba(255, 255, 255, 0.8)", 
      });
      $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>reports/reenviar_comprobante/'+ idperiodo,
            dataType: 'json'
        }).success(function(response) {
            $.LoadingOverlay("hide");
            $.gritter.add({
              title: 'Atenci&oacute;n!',
              text: 'Se han reenviado los comprobante de Gastos Comunes. ',
              image: '<?php echo base_url(); ?>img/send_mail.png',
              sticky: false,
              time: 3000
            });           
        });

  })

});




    </script>