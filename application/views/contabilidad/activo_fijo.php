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
                  <h3 class="box-title">Listado de Cuentas Activo Fijo</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <form action="<?php echo base_url();?>comunity/generar_ggcc" method="post">
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th><small>Proveedor</small></th>
                      <th><small>Concepto</small></th>
                      <th><small>Fecha Vencimiento</small></th>
                      <th><small>Monto Cuenta</small></th>
                      <th><small>Vida &Uacute;til</small></th>
                      <th><small>Vida &Uacute;til Residual</small></th> 
                      <th><small>Monto Depreciaci&oacute;n</small></th>
                      <th><small>Depreciaci&oacute;n Acumulada</small></th>
                      <th><small>Valor Residual</small></th>
                      <th><small>Dar de baja</small></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($cuentas) > 0 ){ ?>
                      <?php foreach ($cuentas as $cuenta) { ?>
                       <tr >
                        <td><small><?php echo $cuenta->proveedor;?></small></td>
                        <td><small><?php echo $cuenta->concepto;?></small></td>
                        <td><small><?php echo $cuenta->fecvencimiento;?></small></td>
                        <td><small>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></small></td>
                        <td><small>
                          <?php if($cuenta->vidautil == 0 || $cuenta->vidautil == $cuenta->vidautilresidual){ ?>
                            <a href="<?php echo base_url(); ?>contabilidad/put_vida_util/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="<?php echo $cuenta->vidautil == 0 ? 'Agregar Vida &Uacute;til' : 'Editar Vida &Uacute;til';?>">
                                <?php echo $cuenta->vidautil == 0 ? "Sin Vida &uacutetil" : "<center>".$cuenta->vidautil."</center>"; ?>
                            </a>
                          <?php }else{ ?>
                            <center><?php echo $cuenta->vidautil;?></center>
                          <?php } ?>
                          </small>
                        </td>
                        <td><small><center><?php echo $cuenta->vidautilresidual == 0 ? "-" : $cuenta->vidautilresidual; ?></center></small></td>
                        <td><small><?php echo $cuenta->vidautil == 0 ? "-" : "$  ".number_format((int)$cuenta->depreciacion,0,".","."); ?></small></td>
                        <td><small><?php echo "$  ".number_format((int)$cuenta->depacum,0,".","."); ?></small></td>
                        <td><small><?php echo "$  ".number_format((int)$cuenta->valorresidual,0,".","."); ?></small></td>
                        <td><small><input type="checkbox" class="minimal cuentas" name="cuenta-<?php echo $cuenta->id;?>" id="cuenta-<?php echo $cuenta->id;?>" <?php echo $cuenta->vidautil == 0 ? "disabled" : ""; ?> <?php if($cuenta->baja == 1) { echo "checked"; }?>/></small></td>
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

<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-green, input[type="radio"].minimal-green').iCheck({
          checkboxClass: 'icheckbox_minimal-green',
          radioClass: 'iradio_minimal-green'
        });


$(document).ready(function() {

  $(".cuentas").on('ifChecked',function(event){
      
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("-");
        var id_cuenta = array_elem[1];

        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>accounts/put_baja_activo_fijo/'+id_cuenta+'/1',
        }).success(function(response) {
        });

  });


  $(".cuentas").on('ifUnchecked',function(event){
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("-");
        var id_cuenta = array_elem[1];

        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>accounts/put_baja_activo_fijo/'+id_cuenta+'/0',
        }).success(function(response) {

        });

  });


});        

</script>