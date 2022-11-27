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
              <a href="<?php echo base_url();?>accounts/add_ingreso_comunidad" type="submit" class="btn btn-primary">Agregar Ingreso Comunidad</a>
          </div> 
          <br>          
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Ingresos&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="S&oacute;lo es posible eliminar ingresos que no est&eacute;n autorizados ni conciliados" title="Atenci&oacute;n"></i></h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
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
                    <?php if(count($ingresos) > 0 ){ ?>
                      <?php foreach ($ingresos as $ingreso) { ?>
                       <tr >
                        <td><?php echo $ingreso->proveedor;?></td>
                        <td><?php echo $ingreso->concepto;?></td>
                        <td><?php echo $ingreso->fecvencimiento;?></td>
                        <td>$&nbsp;<?php echo number_format($ingreso->monto,0,".",".");?></td>
                        <td>
                        <a href="<?php echo base_url();?>accounts/edit_ingreso/<?php echo $ingreso->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;   
                        <?php if(is_null($ingreso->fechaconciliacion)){ ?>      
                        <a href="<?php echo base_url(); ?>accounts/delete_ingreso/<?php echo $ingreso->id;?>" data-toggle="tooltip" title="Eliminar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                        <?php }else{ ?>
                          &nbsp;
                          &nbsp;
                          &nbsp;
                        <?php } ?>
                          &nbsp;
                          &nbsp;                        
                        
                        <a href="<?php echo base_url(); ?>reports/ver_ingreso/<?php echo $ingreso->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;                        
                          <?php if($ingreso->nombrearchivo != ''){ ?>
                        <a href="<?php echo base_url(); ?>uploads/ingresos/<?php echo $this->session->userdata('comunidadid')."/".$ingreso->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
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