        <!-- Main content -->
        <section class="content" >
         <div class="row">
         <?php if(isset($message)): ?>

                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>

                   
          </div>
          <?php endif; ?>
          <br>                       
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Cuentas sin Autorizar</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <form action="<?php echo base_url();?>comunity/generar_ggcc" method="post">
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Proveedor</th>
                      <th>Concepto</th>
                      <th>Fecha Vencimiento</th>
                      <th>Monto</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($cuentas) > 0 ){ ?>
                      <?php foreach ($cuentas as $cuenta) { ?>
                       <tr >
                        <td><?php echo $cuenta->proveedor;?></td>
                        <td><?php echo $cuenta->concepto;?></td>
                        <td><?php echo $cuenta->fecvencimiento;?></td>
                        <td>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></td>
                        <td>
                        <!--a href="<?php echo base_url();?>accounts/edit_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;                        -->
                        <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;                        
                          <?php if($cuenta->nombrearchivo != ''){ ?>
                        <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                        <?PHP } ?>
                        </td>
                      </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                  </table>
                  </form>
                </div><!-- /.box-body -->
              </div>
            </div>
          </div>

          <br>
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Otros Cargos sin Autorizar</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Proveedor</th>
                      <th>Fecha de Pago</th>
                      <th>Monto</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($cargos) > 0 ){ ?>
                      <?php foreach ($cargos as $cargo) { ?>
                       <tr >
                        <td><?php echo $cargo->nombreproveedor;?></td>
                        <td><?php echo $cargo->fecpago;?></td>
                        <td>$&nbsp;<?php echo number_format($cargo->monto,0,".",".");?></td>
                        <td>
                        <!--a href="<?php echo base_url();?>accounts/edit_otros_cargos/<?php echo $cargo->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;  
                          &nbsp;                          -->
                        <a href="<?php echo base_url(); ?>reports/ver_cargo/<?php echo $cargo->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;  
                          &nbsp;    
                        <?php if($cargo->nombrearchivo != ''){ ?>                    
                        <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cargo->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                        <?php } ?>
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