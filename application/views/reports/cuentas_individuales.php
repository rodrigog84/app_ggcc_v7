        <!-- Main content -->
        <section class="content" >

          <br>
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Cuentas Individuales sin Cobrar</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Nro. Propiedad</th>
                      <th>Concepto</th>
                      <th>Fecha Vencimiento</th>
                      <th>Per&iacute;odo Cobro</th>
                      <th>Monto</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($cuentas_individuales) > 0 ){ ?>
                      <?php foreach ($cuentas_individuales as $cuenta_individual) { ?>
                       <tr >
                        <td><?php echo $cuenta_individual->numero;?></td>
                        <td><?php echo $cuenta_individual->concepto;?></td>
                        <td><?php echo $cuenta_individual->fechadeuda;?></td>
                        <td><?php echo date2string($cuenta_individual->mes,$cuenta_individual->anno); ?></td>
                        <td><?php echo number_format($cuenta_individual->monto,0,".",".");?></td>
                        <td>
                        <a href="<?php echo base_url(); ?>reports/ver_cuenta_individual/<?php echo $cuenta_individual->id; ?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;     
                        <?php if($cuenta_individual->nombrearchivo != ''){ ?>                     
                        <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta_individual->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
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

          <br>
          <div class="row">
            
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Cuentas de Espacios Comunes sin Cobrar</h3>  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
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
                        <td><?php echo $cuenta_espacio_comun->numero;?></td>
                        <td><?php echo $cuenta_espacio_comun->concepto;?></td>
                        <td><?php echo $cuenta_espacio_comun->fechadeuda;?></td>
                        <td><?php echo date2string($cuenta_espacio_comun->mes,$cuenta_espacio_comun->anno); ?></td>
                        <td><?php echo number_format($cuenta_espacio_comun->monto,0,".",".");?></td>
                        <td>
                        <a href="<?php echo base_url(); ?>reports/ver_cuenta_esp_comunes/<?php echo $cuenta_espacio_comun->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
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
        </section><!-- /.content -->


  <script>

    $(".form_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        weekStart: true,
        startView: 2,
        minView: 2,
        forceParse: 0,
        language:  'es',     
    });

  </script>


  <script>
      $(function () {
        $('.table').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bSort": false,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,50,100,-1],[5,10,50,100,'All']],
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