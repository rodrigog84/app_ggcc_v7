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
          <br>
          <?php endif; ?>

      <form id="basicBootstrapForm"  action="<?php echo base_url();?>reports/reporte_egresos" method="post">
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
                            <label for="periodo">Fecha de Pago</label>
                            <div class="input-group">
                                   <a href="#" class="btn btn-default pull-right" id="daterange-btn">
                                    <span class="glyphicon glyphicon-calendar"></span><span id="label_rango"><?php echo $fechadesde == '1900-01-01' ? 'Seleccionar Rango de Fechas' : month2string(substr($fechadesde,5,2)). ' ' . substr($fechadesde,8,2) . ', '.substr($fechadesde,0,4) . ' - ' . month2string(substr($fechahasta,5,2)). ' ' . substr($fechahasta,8,2) . ', '.substr($fechahasta,0,4); ?></span>
                                    <i class="fa fa-caret-down"></i>
                                  </a>
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
                  <h3 class="box-title">Reporte Egresos</h3>  
                  <?php if(count($movimientos) > 0 ){ ?>
                  <div class="pull-right box-tools">
                      <h4><a href="<?php echo base_url(); ?>reports/export_egresos/<?php echo $fechadesde; ?>/<?php echo $fechahasta;?>" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                  </div>
                  <?php } ?>                  
                </div><!-- /.box-header -->

                <div class="box-body" >
                  <table class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th><small>Fecha</small></th>
                      <th><small>Glosa</small></th>
                      <th><small>Cheque</small></th>
                      <th><small>Nro. Transacci&oacute;n</small></th>
                      <th><small>Monto</small></th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($movimientos) > 0 ){ ?>
                      <?php foreach ($movimientos as $movimiento) { ?>
                       <tr >
                        <td><small><?php echo $movimiento->fechapago_format;?></small></td>
                        <td><small><?php echo $movimiento->proveedor;?></small></td>
                        <td><small><?php echo $movimiento->cheque;?></small></td>
                        <td><small><?php echo trackid($movimiento->folio);?></small></td>
                        <td class="text-right"><small>$&nbsp;<?php echo number_format($movimiento->monto,0,".","."); ?></small></td>
                      
                        <td>
                              <?php if(!is_null($movimiento->idcaja)){ ?>
                              <center><a href="<?php echo base_url(); ?>accounts/download_egreso/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Comprobante Egreso"  target="_blank"><span class="glyphicon glyphicon-paperclip input-sm"></span></a></center>
                              <?php }else{ ?>
                                <center><span class="fa fa-ban text-red" data-toggle="tooltip" title="Documento Eliminado"></span></center>
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


    $(function(){

            //var start = moment().add(1, 'day');
            //var end = moment().add(1, 'day');

            //var start = moment().format("<?php echo $fechadesde;?>", "YYYY-MM-DD");
            //var end = moment().format("<?php echo $fechadesde;?>", "YYYY-MM-DD");         

            var start = "<?php echo formato_fecha($fechadesde,'Y-m-d','d/m/Y');?>";
            var end = "<?php echo formato_fecha($fechahasta,'Y-m-d','d/m/Y');?>";         
            var min = moment().add(1, 'day');
       
        //Date range picker
        $('#daterange-btn').daterangepicker(
                {
              locale: {
                  format: 'DD/MM/YYYY',
                  "applyLabel": "Aceptar",
                  "cancelLabel": "Cancelar",
                  "fromLabel": "Desde",
                  "toLabel": "Hasta",
                  "customRangeLabel": "Seleccionar Fechas",
                  "daysOfWeek": [
                      "Do",
                      "Lu",
                      "Ma",
                      "Mi",
                      "Ju",
                      "Vi",
                      "Sa"
                  ],
                  "monthNames": [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",                      
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre"
                  ],
                  "firstDay": 1
              },                  
                  startDate: start,
                  endDate: end,
                  //minDate: min,
                  opens: "right",
                  drops: "down",
                  ranges: {
                      'Hoy':  [moment(), moment()],
                      'Ayer':  [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                      'Ultimos 7 dias':  [moment().subtract(6, 'days'), moment()],
                      'Ultimos 30 dias':  [moment().subtract(29, 'days'), moment()],
                      'Este Mes':  [moment().startOf('month'), moment().endOf('month')],
                      'Mes pasado':  [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                      //'Dos Meses':  [moment().subtract(1,'month').startOf('month'), moment().endOf('month')]
                  }                  
                },
        function (start, end ) {

          
          $('#fechadesde').val(start.format('YYYY-MM-DD'));
          $('#fechahasta').val(end.format('YYYY-MM-DD'));
          $('#label_rango').html(start.format('MMMM DD, YYYY') + ' - ' + end.format('MMMM DD, YYYY'));
        }




        );  

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
