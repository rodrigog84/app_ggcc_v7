        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Cartola de Pagos.  <?php echo $texto_propiedad;?>  <?php echo $texto_periodo;?></h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Monto</th>
                      <th>Fecha de Pago</th>
                      <th>Fecha Conciliaci&oacute;n</th>
                      <th>Documento Adjunto</th>
                      <?php if($periodoid == 0){ ?>
                        <?php if($this->session->userdata('level') == 1){ ?> 
                        <th>Reenviar Comprobante</th>
                        <?php } ?>
                        <th>Comprobante Abono</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; ?>
                    <?php foreach($datoscartola as $cartola){ ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($cartola->monto,0,".",".");?></td>
                        <td><?php echo $cartola->fechapago;?></td>
                        <td><?php echo $cartola->created_at;?></td>
                        <td>
                          <?php if($cartola->nombrearchivo != ''){ ?>
                            <a href="<?php echo base_url(); ?>uploads/abonos/<?php echo $cartola->idpropiedad."/".$cartola->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php } ?>
                        </td>
                        <?php if($periodoid == 0){ ?>
                          <?php if($this->session->userdata('level') == 1){ ?> 
                          <td>
                            <center><a href="#" class="reenvia_comprobante" id="reenvia-<?php echo $cartola->idpropiedad;?>-<?php echo $cartola->id;?>" data-toggle="tooltip" title="Reenviar"  ><span class="fa fa-mail-forward input-sm"></span></a></center>
                          </td>   
                          <?php } ?>                     
                          <td>
                            <center><a href="<?php echo base_url(); ?>payments/download_ingreso/<?php echo $cartola->idpropiedad;?>/<?php echo $cartola->id;?>" data-toggle="tooltip" title="Comprobante Ingreso"  target="_blank"><span class="glyphicon glyphicon-paperclip input-sm"></span></a></center>
                          </td>
                        <?php } ?>
                      </tr>
                    <?php $i++; ?>
                    <?php } ?>
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


    <script type="text/javascript">
      $(function () {
        $('.table').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": true,
          "aLengthMenu" : [[5,10,50,100,-1],[5,10,50,100,'All']],
          "iDisplayLength": 5,
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
      var idcartola = array_id[2];
      $.LoadingOverlay("show",{
        color           : "rgba(255, 255, 255, 0.8)", 
      });
      $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>payments/reenviar_comprobante/'+ idpropiedad + '/' + idcartola,
            dataType: 'json'
        }).success(function(response) {
            $.LoadingOverlay("hide");
            $.gritter.add({
              title: 'Atenci&oacute;n!',
              text: 'Se ha reenviado el comprobante de abono al propietario al mail <b><?php echo $mail; ?></b>. ',
              image: '<?php echo base_url(); ?>img/send_mail.png',
              sticky: false,
              time: 3000
            });           
        });
  

  })



});




    </script>