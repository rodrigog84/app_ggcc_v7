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
              <a href="<?php echo base_url();?>accounts/add_adm_esp_comunes" type="submit" class="btn btn-primary">Agregar Cuenta Espacios Comunes</a>
          </div> 
          <br>
          <form action="<?php echo base_url();?>accounts/delete_adm_esp_comunes_massive" method="post">
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Cuentas de Espacios Comunes sin Cobrar&nbsp;&nbsp;<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Eliminar cuentas espacios comunes marcadas"><span class="glyphicon glyphicon-trash"></span></button></h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th width="20px"><input type="checkbox" class="minimal-red" id="cadmespcomunes_all" /></th>
                      <th>Nro. Propiedad</th>
                      <th>Concepto</th>
                      <th>Fecha Vencimiento</th>
                      <th>Per&iacute;odo Cobro</th>
                      <th>Monto</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($cuentas_espacios_comunes) > 0 ){ ?>
                      <?php foreach ($cuentas_espacios_comunes as $cuenta_espacio_comun) { ?>
                       <tr >
                        <td><input type="checkbox" class="minimal-red cadmespcomunes" name="cadmespcomunes-<?php echo $cuenta_espacio_comun->id;?>" id="cadmespcomunes-<?php echo $cuenta_espacio_comun->id;?>" /></td>
                        <td><?php echo $cuenta_espacio_comun->numero;?></td>
                        <td><?php echo $cuenta_espacio_comun->concepto;?></td>
                        <td><?php echo $cuenta_espacio_comun->fechadeuda;?></td>
                        <td><?php echo date2string($cuenta_espacio_comun->mes,$cuenta_espacio_comun->anno); ?></td>
                        <td><?php echo number_format($cuenta_espacio_comun->monto,0,".",".");?></td>
                        <td>
                        <a href="<?php echo base_url(); ?>reports/ver_cuenta_esp_comunes/<?php echo $cuenta_espacio_comun->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;   
                        <a href="<?php echo base_url(); ?>accounts/delete_cuenta_individual/<?php echo $cuenta_espacio_comun->id;?>/ec" data-toggle="tooltip" title="Eliminar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>                          
                          &nbsp;
                          &nbsp;                                                       
                          <?php if($cuenta_espacio_comun->nombrearchivo != ''){ ?>                         
                          <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta_espacio_comun->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
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

<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });

</script>

<script>

$("#cindividuales_all").on('ifChecked',function(event){
   $(".cindividuales").iCheck('check');
});

$("#cindividuales_all").on('ifUnchecked',function(event){
   $(".cindividuales").iCheck('uncheck');
});


$("#cadmespcomunes_all").on('ifChecked',function(event){
   $(".cadmespcomunes").iCheck('check');
});

$("#cadmespcomunes_all").on('ifUnchecked',function(event){
   $(".cadmespcomunes").iCheck('uncheck');
});

</script>