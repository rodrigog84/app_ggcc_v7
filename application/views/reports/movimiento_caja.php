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
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($movimientos) > 0 ){ ?>
                      <?php $saldo_caja = $datoscomunidad->caja; ?>
                      <?php foreach ($movimientos as $movimiento) { ?>
                       <tr >
                        <td><small><?php echo $movimiento->fechapago;?></small></td>
                        <td><small><?php echo $movimiento->glosa;?></small></td>
                        <td><small><?php echo trackid($movimiento->id);?></small></td>
                        <td class="text-right"><small>$&nbsp;<?php echo number_format($movimiento->monto,0,".","."); ?></small></td>
                        <td class="text-right"><small>$&nbsp;<?php echo number_format($saldo_caja,0,".",".");?></small></td>
                        <td>
                          <?php 
                              $estado = "";
                              $class = "";
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

                              ?>

                              <span class="<?php echo $class;?> input-sm" id="estado-<?php echo $movimiento->id;?>" ><?php echo $estado; ?></span>
                        </td>
                        <td>
                                <a href="<?php echo base_url(); ?>admins/conciliar_movimiento/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="<?php echo is_null($movimiento->fechaconciliacion) ? 'Conciliar' : 'Editar Conciliacion';?>">
                                    <small><?php echo is_null($movimiento->fechaconciliacion) ? "Conciliar" : $movimiento->fechaconciliacion; ?></small>
                                </a>
                        </td>

                        <!--td>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
                                      <input class="form-control input-sm" size="16" type="text" readonly name="fecconciliacion" id="fecconciliacion-<?php echo $movimiento->id; ?>" value="<?php echo $movimiento->fechaconciliacion; ?>" placeholder="Fecha Conciliaci&oacute;n">
                                 </div>
                                 
                        </td-->
                        <td><span class="glyphicon glyphicon-ok-sign text-success text-green " data-toggle="tooltip" title="Conciliaci&oacute;n Realizada" id="icono-<?php echo $movimiento->id;?>" style="display:<?php echo is_null($movimiento->fechaconciliacion) ? 'none':'block';?>"></span></td>

                        <td>
                            <a href="<?php echo base_url(); ?>reports/ver_movimiento/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Ver Detalle Movimiento"><span class="glyphicon glyphicon-search input-sm"></span></a>
                           
                        </td>
                        <td>
                          <?php if(strpos($movimiento->glosa,'Protesto') === false && $movimiento->protesto == 0 && is_null($movimiento->idingreso)){  // si es protesto no se puede eliminar ?>
                            <a style="display:<?php echo $delete_movimiento; ?>" id="delete-<?php echo $movimiento->id;?>" href="<?php echo base_url();?>admins/delete_movimiento/<?php echo $movimiento->id;?>" data-toggle="tooltip" title="Eliminar Movimiento" ><span class="glyphicon glyphicon-trash input-sm"></span></a>
                          <?php } ?>
                        </td>
                        
                        <td>
                          <?php if($movimiento->tipo_movimiento == 'pago' && $movimiento->monto_listado > 0 && strpos($movimiento->glosa,'Protesto') === false && $movimiento->protesto == 0){ ?>
                          <center><a href="<?php echo base_url(); ?>accounts/download_egreso/<?php echo $movimiento->idlistado;?>" data-toggle="tooltip" title="Comprobante Egreso"  target="_blank"><span class="glyphicon glyphicon-paperclip input-sm"></span></a></center>
                          <?php } ?>
                        </td>  
                      </tr>
                        <?php $saldo_caja -= $movimiento->monto; ?>
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
<script type="text/javascript">
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
        endDate: '+1d'

    })

    .on('changeDate', function(ev){
     var date_elem = $(this).context.lastElementChild.id;
     var array_elem = date_elem.split("-");
     var id_elem = array_elem[1];

     var fecha_sel = $('#fecconciliacion-'+id_elem).val();
     fecha_format = fecha_sel.substring(6,10)+fecha_sel.substring(3,5)+fecha_sel.substring(0,2);
      $.post("<?php echo base_url();?>admins/put_conciliacion/",{
        fecha_conciliacion : fecha_format,
        movimiento : id_elem
        },function(data){
              var_json = $.parseJSON(data);
              var estado_elem = var_json == 0 ? 'Pendiente Cobro' : 'Cobrado';
              var class_elem = var_json == 0 ? 'text-yellow input-sm' : 'text-green input-sm';


              $('#estado-'+id_elem).text(estado_elem);
              $('#estado-'+id_elem).prop('class',class_elem);
              $('#icono-'+id_elem).show();
              if($('#delete-'+id_elem).length){ // muestra u ocula sólo si existe elemento
                if(var_json == 0){
                    $('#delete-'+id_elem).show();                
                  
                }else{
                  $('#delete-'+id_elem).hide();                
                }
              }

        });


    });

</script> 
