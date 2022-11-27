        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Saldo y Ultimos Movimientos</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Descripci&oacute;n</th>
                      <th>Nro. Transacci&oacute;n</th>
                      <th>Monto</th>
                      <th>Saldo</th>
                      <th>Documento</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($movimientos) > 0 ){ ?>
                      <?php foreach ($movimientos as $movimiento) { ?>
                       <tr >
                        <td><?php echo $movimiento->fecha;?></td>
                        <td><?php echo $movimiento->glosa;?></td>
                        <td><?php echo trackid($movimiento->id);?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($movimiento->monto,0,".","."); ?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($movimiento->saldo,0,".",".");?></td>
                        <td class="text-right">
                          <center>
                          <?php if(!is_null($movimiento->nombrearchivo)){ ?>
                          <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$movimiento->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                        <?php }else{ ?>
                              &nbsp;

                        <?php } ?>
                          </center>
                        </td>
                        
                      </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  </table>
                </div><!-- /.box-body -->
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