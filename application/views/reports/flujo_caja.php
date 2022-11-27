        <!-- Main content -->
        <section class="content" >
          <?php if(isset($message)): ?>
         <div class="row">
            
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
          </div>
          <br>
          <?php endif; ?>


          <form id="basicBootstrapForm"  action="<?php echo base_url();?>reports/flujo_caja" method="post">
          <div class="row">
            <div class="col-md-8">
              <div class="box box-primary ">
                <div class="box-header ">
                  <h3 class="box-title">B&uacute;squeda</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class='row'>
                      <div class='col-md-4'>
                        <div class="form-group">
                          <label for="periodo">Estado Conciliaci&oacute;n</label>
                            <select name="tipoconciliacion" class="btn btn-default" id="tipoconciliacion">
                            <option value="" <?php echo $tipoconciliacion == '' ? 'selected': '';?>>Mostrar Sin Conciliaci&oacute;n</option>
                            <option value="conciliado" <?php echo $tipoconciliacion == 'conciliado' ? 'selected': '';?>>Mostrar Conciliados</option>
                            <option value="anulado" <?php echo $tipoconciliacion == 'anulado' ? 'selected': '';?>>Mostrar Anulados</option>
                            <option value="todos" <?php echo $tipoconciliacion == 'todos' ? 'selected': '';?>>Mostrar Todos</option>
                            </select>
                            <!--div class="input-group">
                              <div class="btn-group">
                                <button type="button" class="btn btn-default"><?php //echo $title_button;?></button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <span class="caret"></span>
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="<?php echo base_url();?>reports/flujo_caja" />Mostrar Sin Conciliaci&oacute;n</a></li>
                                  <li><a href="<?php echo base_url();?>reports/flujo_caja/conciliado" />Mostrar Conciliados</a></li>
                                  <li><a href="<?php echo base_url();?>reports/flujo_caja/todos" />Mostrar Todos</a></li>
                                </ul>
                              </div>                        
                            </div--> 
                        </div>
                      </div>
                      <div class='col-md-4'>
                        <div class="form-group">
                            <label for="periodo">Fecha de Pago</label>
                            <div class="input-group">

                                   <button class="btn btn-default pull-right" id="daterange-btn">
                                    <span class="glyphicon glyphicon-calendar"></span><span id="label_rango"><?php echo $fechadesde == '1900-01-01' ? 'Seleccionar Rango de Fechas' : month2string(substr($fechadesde,5,2)). ' ' . substr($fechadesde,8,2) . ', '.substr($fechadesde,0,4) . ' - ' . month2string(substr($fechahasta,5,2)). ' ' . substr($fechahasta,8,2) . ', '.substr($fechahasta,0,4); ?></span>
                                    <i class="fa fa-caret-down"></i>
                                  </button>
                            </div>
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
              </div><!-- /.box -->
            </div><!-- /.col (left) -->          
          

          </div>
          <input type="hidden" id="fechadesde" name="fechadesde" value="<?php echo $fechadesde;?>" />
          <input type="hidden" id="fechahasta" name="fechahasta" value="<?php echo $fechahasta;?>" />

          </form>
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
                      <th><small>Fecha</small></th>
                      <th><small>Descripci&oacute;n</small></th>
                      <th><small>Nro. Transacci&oacute;n</small></th>
                      <th><small>Monto</small></th>
                      <th><small>Saldo Contable</small></th>
                      <th><small>Estado Conciliaci&oacute;n</small></th>
                      <th><small>Fecha Cobro</small></th>  
                      <th><small>Comprobante</small></th>                    
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($movimientos) > 0 ){ ?>
                      <?php $saldo_caja = $datoscomunidad->caja; ?>
                      <?php foreach ($movimientos as $movimiento) { ?>
                       <tr >
                        <td><small><?php echo $movimiento->fechapago_format;?></small></td>
                        <td><small><?php echo $movimiento->glosa;?></small></td>
                        <td><small><?php echo  trackid($movimiento->folio);?></small></td>
                        <td class="text-right"><small>$&nbsp;<?php echo number_format($movimiento->activo == 1 ? $movimiento->monto : 0,0,".","."); ?></small></td>
                        <td class="text-right"><small>$&nbsp;<?php echo number_format($saldo_caja,0,".",".");?></small></td>
                        <td>
                          <?php 
                              $estado = "";
                              $class = "";
                              if($movimiento->activo == 1){
                                if(is_null($movimiento->fechaconciliacion)){ 
                                  $estado = "Pendiente Conciliaci&oacute;n";
                                  $class = "text-red";
                                  $delete_movimiento = "block";
                                }else{

                                    $fecha_actual = strtotime(date("Y-m-d"));
                                    $fechaconciliacion = substr($movimiento->fechaconciliacion,6,4)."-".substr($movimiento->fechaconciliacion,3,2)."-".substr($movimiento->fechaconciliacion,0,2);
                                    $fecha_entrada = strtotime($fechaconciliacion);
                                    if($fecha_actual < $fecha_entrada){
                                        $estado = "Pendiente Cobro";
                                        $class = "text-yellow";
                                        $delete_movimiento = "block";
                                    }else{
                                        $estado = "Cobrado";
                                        $class = "text-green";
                                        $delete_movimiento = "none";
                                    }
                                }
                              }else{
                                        $estado = "Anulado";
                                        $class = "text-red";
                                        $delete_movimiento = "none";                                
                              }


                              ?>

                              <small><span class="<?php echo $class;?>" id="estado-<?php echo $movimiento->id;?>" ><?php echo $estado; ?></span></small>
                        </td>
                        <td><small><?php echo $movimiento->fechaconciliacion; ?></small></td>
                        <td><small>
                          <?php if(strpos($movimiento->glosa,'Protesto') === false && $movimiento->protesto == 0){ ?>
                              <?php if($movimiento->tipo_movimiento == 'p'){ ?>
                                  <center><a href="<?php echo base_url(); ?>accounts/download_egreso/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Comprobante Egreso"  target="_blank"><span class="glyphicon glyphicon-paperclip input-sm"></span></a></center>
                              <?php }else if($movimiento->tipo_movimiento == 'a'){ ?>
                                  <center><a href="<?php echo base_url(); ?>payments/download_ingreso/<?php echo $movimiento->idpropiedad;?>/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Comprobante Ingreso"  target="_blank"><span class="glyphicon glyphicon-paperclip input-sm"></span></a></center>
                              <?php } ?>
                          <?php } ?>
                        </small></td>                        
                      </tr>
                        <?php $saldo_caja -= $movimiento->activo == 1 ? $movimiento->monto : 0; ?>
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

      $(function () {
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#daterange-btn').daterangepicker(
                {
                  ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Últimos 7 Días': [moment().subtract('days', 6), moment()],
                    'Últimos 30 Días': [moment().subtract('days', 29), moment()],
                    'Últimos 60 Días': [moment().subtract('days', 59), moment()],
                    'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes Anterior': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                    'Este Año': [moment().startOf('year'), moment().endOf('year')]
                  },
                  //startDate: moment().subtract('days', 29),
                  //endDate: moment(), 
                  startDate: moment().format("<?php echo substr($fechadesde,8,2).'/'.substr($fechadesde,5,2).'/'.substr($fechadesde,0,4);?>", "DD/MM/YYYY"),
                  endDate: moment().format("<?php echo substr($fechahasta,8,2).'/'.substr($fechahasta,5,2).'/'.substr($fechahasta,0,4);?>", "DD/MM/YYYY"),

                },
        function (start, end) {
          //console.log(start.format('YYYY-MM-DD')+" -- "+end.format('YYYY-MM-DD'))
          $('#fechadesde').val(start.format('YYYY-MM-DD'));
          $('#fechahasta').val(end.format('YYYY-MM-DD'));


          $('#label_rango').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          //$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }




        );  

$('#daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
  //do something, like clearing an input
  //$('#daterange').val('');
  console.log("asdadasd");
});

      });    
</script>    