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
          <div>
              <a href="<?php echo base_url();?>accounts/add_otros_cargos" type="submit" class="btn btn-primary">Agregar Otros Cargos</a>
          </div> 
          <br>
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Otros Cargos</h3>  
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
                          <?php if($cargo->abonado == 0){ ?>  
                          <a href="<?php echo base_url();?>accounts/edit_otros_cargos/<?php echo $cargo->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
                          &nbsp;
                          &nbsp;                            
                          <?php if($cargo->abonado == 0){ ?>  
                          <a href="<?php echo base_url();?>accounts/delete_cuenta/<?php echo $cargo->id;?>/c" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-trash"></span></a>
                          <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
                          &nbsp;
                          &nbsp;                            
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
          "aLengthMenu" : [[10,50,100,-1],[10,50,100,'All']],
          "iDisplayLength": 10,
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