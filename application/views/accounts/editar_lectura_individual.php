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
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Cuentas&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="S&oacute;lo es posible editar y eliminar cuentas asociadas a gastos comunes sin generar" title="Atenci&oacute;n"></i></h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <form  method="post">
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Proveedor</th>
                      <th>Concepto</th>
                      <th>Fecha Vencimiento</th>
                      <th>Per&iacute;odo Cobro</th>
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
                        <td><?php echo date2string($cuenta->mes,$cuenta->anno); ?></td>
                        <td>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></td>
                        <td>
                        <?php if(is_null($cuenta->genera)){ ?>   
                        <!--a href="<?php echo base_url();?>accounts/edit_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a-->
                        <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
                          &nbsp;
                          &nbsp;         
                        <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;                        
                        <a href="<?php echo base_url(); ?>reports/ver_detalle_lectura/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Detalle Lectura Individual"><span class="fa fa-align-justify"></span></a>
                          &nbsp;
                          &nbsp;        
                        <a href="<?php echo base_url();?>accounts/edit_cobro_individual/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>                  
                          &nbsp;
                          &nbsp;        
                          <?php if($cuenta->nombrearchivo != ''){ ?>
                            <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                            &nbsp;
                            &nbsp;                        
                          <?php } ?>
                        <?php if(is_null($cuenta->genera)){ ?>             
                        <a href="<?php echo base_url(); ?>accounts/delete_cobro_individual/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Eliminar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                        <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>

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