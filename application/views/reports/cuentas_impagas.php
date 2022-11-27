        <!-- Main content -->
        <section class="content" >

 
     
          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Cuentas sin Pagar</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th >Proveedor</th>
                        <th >Concepto</th>
                        <th> Monto Deuda</th>
                        <th >Abonado</th>
                        <th >Saldo</th>
                        <th >Documento</th>
                        <!--th rowspan="2">Acci&oacute;n</th-->
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($cuentas) > 0 ){ ?>
                        <?php foreach ($cuentas as $cuenta) { ?>
                         <tr >
                          <td><?php echo $cuenta->proveedor;?></td>
                          <td><?php echo $cuenta->concepto;?></td>
                          <td class="text-right">$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></td>
                          <td class="text-right">$&nbsp;<?php echo number_format($cuenta->abonado,0,".",".");?></td>
                          <td class="text-right">$&nbsp;<?php echo number_format($cuenta->saldo,0,".",".");?></td>
                          <td>

                          <a href="<?php echo base_url(); ?>reports/<?php echo $cuenta->tipocuenta == 'cargo' ? 'ver_cargo' : 'ver_cuenta';?>/<?php echo $cuenta->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;                        
                          <?php if($cuenta->nombrearchivo != ''){ ?>
                          <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php } ?>
                          </td>
                          <input type="hidden" name="saldo-<?php echo $cuenta->id;?>" id="saldo-<?php echo $cuenta->id;?>" value="<?php echo $cuenta->saldo;?>" />
                          <!--td>
                            <a href="#" id="quitar-<?php echo $cuenta->id;?>" class="quitar">X</a>
                          </td-->
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