        <!-- Main content -->
        <section class="content" >
          <form id="basicBootstrapForm" action="<?php echo base_url();?>reports/fondo_reserva" id="basicBootstrapForm" method="post"> 
            <div class="row">

                <div class="col-md-9">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h3 class="box-title">B&uacute;squeda</h3>  
                    </div><!-- /.box-header -->

                    <div class="box-body" >
                      <div class='row'>
                          <div class='col-md-4'>
                            <div class="form-group">
                                <label for="anno">Tipo Cartola</label>
                                <select name="tiporeporte" id="tiporeporte" class="form-control">
                                  <option value="">Seleccione tipo reporte</option>
                                  <?php $fr_selected = $tiporeporte == 'fr' ? 'selected' : ''; ?>
                                  <option value="fr" <?php echo $fr_selected;?> >Fondo de Reserva</option>
                                  <?php foreach($fondos as $fondo){ ?>
                                      <?php $fondo_selected = $tiporeporte == $fondo->id ? 'selected' : ''; ?>
                                  <option value="<?php echo $fondo->id;?>" <?php echo $fondo_selected;?>><?php echo $fondo->nombre;?></option>
                                  <?php } ?>
                                </select>
                            </div>
                          </div>                                                   
                      </div>
                      <div class='row'>
                          <div class='col-md-3'>
                            <div class="form-group ">
                            <label for="ruttitular">&nbsp;</label> 
                            <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                          </div>
                          </div>                  
                      </div>                                           
                    </div><!-- /.box-body -->
                  </div>
                </div>


            </div>    



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
                      <?php $sumasaldo = false; ?>
                      <?php $saldo_siguiente = 0; ?>
                      <?php foreach ($movimientos as $movimiento) { ?>
                       <tr >
                        <?php if($tiporeporte == 'fr'){

                                $saldo = $movimiento->saldo;
                              }else{
                                if($sumasaldo){
                                  $saldo = $saldo_siguiente;
                                  $saldo_siguiente =  $saldo + $movimiento->monto*(-1);
                                }else{
                                  $saldo = $saldo_total;
                                  $saldo_siguiente =  $saldo + $movimiento->monto*(-1);
                                }

                                $sumasaldo = true;

                              }
                          ?>
                        <td><?php echo $movimiento->fecha;?></td>
                        <td><?php echo $movimiento->glosa;?></td>
                        <td><?php echo trackid($movimiento->id);?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($movimiento->monto,0,".","."); ?></td>
                        <td class="text-right">$&nbsp;<?php echo number_format($saldo,0,".",".");?></td>
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

        </form>
       
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