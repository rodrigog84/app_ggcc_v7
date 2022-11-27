        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Per&iacute;odos</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th><small>#</small></th>
                        <th><small>Mes</small></th>
                        <th><small>A&ntilde;o</small></th>
                        <th><small>Inter&eacute;s</small></th>
                        <th><small>Capitalizaci&oacute;n</small></th>
                        <th><small>Fecha Vencimiento</small></th>
                        <th><small>Fecha Autorizaci&oacute;n</small></th>
                        <th><small>Fecha Prorrateo</small></th>
                        <th><small>Fecha Publicaci&oacute;n</small></th>
                        <th><small>Deuda ($)</small></th>
                        <th><small>Fondo Reserva ($)</small></th>
                        <th><small>Pago Cuentas</small></th>
                        <th><small>Detalle GC</small></th>
                        <?php if($this->session->userdata('level') == 1 || $this->session->userdata('level') == 2){ ?>
                          <th><small>Propiedades</small></th>
                          <th><small>Comprobantes</small></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; 
                              $back_button = false;
                        ?>
                        <?php foreach ($datosperiodo as $periodo) { ?>
                          <?php if(!is_null($periodo->genera)){ ?>
                            <?php if($idperiodo == $periodo->id){ 
                                $class_color = "class = 'success'";
                                $back_button = true;
                            }else{
                                $class_color = "";

                              }?>




                         <tr <?php echo $class_color; ?>>
                          <td><small><?php echo $i;?></small></td>
                          <td><small><?php echo date2string($periodo->mes,$periodo->anno) == 'Saldo Inicial' ? 'Saldo' : month2string($periodo->mes);?></small></td>
                          <td><small><?php echo date2string($periodo->mes,$periodo->anno) == 'Saldo Inicial' ? 'Inicial' : $periodo->anno;?></small></td>
                          <td><small><?php echo $periodo->interes;?>&nbsp;<span style="font-size: 10px">%</span></small></td>
                          <td><small><?php echo $periodo->capitalizacion;?></small></td>
                          <td><small><?php echo $periodo->fecha_vencimiento;?></small></td>
                          <td><small><?php echo $periodo->autoriza;?></small></td>
                          <td><small><?php echo $periodo->genera;?></small></td>
                          <td><small><?php echo $periodo->publica;?></small></td>
                          <td><small>$&nbsp;<?php echo number_format($periodo->deuda,0,".",".");?></small></td>
                          <td><small>$&nbsp;<?php echo number_format($periodo->fondo_reserva,0,".",".");?></small></td>
                          <td>
                          <?php if($periodo->autoriza != '' && $periodo->id != $periodo_inicial->id){ ?>
                          <center><a href="<?php echo base_url(); ?>reports/ver_detalle_periodo/0/<?php echo $periodo->id; ?>" data-toggle="tooltip" title="Ver Detalle Deuda"><span class="glyphicon glyphicon-search"></span></a></center>
                          <?php } ?>
                          </td>
                          <td>
                          <?php if(($this->session->userdata('level') == 1 || $this->session->userdata('level') == 2) && $periodo->genera != ''){
                            $muestra = true;
                          }else if($this->session->userdata('level') == 3 && $periodo->publica){
                            $muestra = true;
                          }else{
                            $muestra = false;
                          } ?>

                          <?php if($muestra && $periodo->id != $periodo_inicial->id){ ?>
                          <center><a href="<?php echo base_url(); ?>reports/ver_detalle_ggcc/<?php echo $periodo->id; ?>" data-toggle="tooltip" title="Ver Detalle Deuda"><span class="glyphicon glyphicon-search"></span></a></center>
                          <?php } ?>
                          </td>               
                          <?php if($this->session->userdata('level') == 1 || $this->session->userdata('level') == 2){ ?>           
                            <td>
                            <?php if($periodo->genera != ''){ ?>
                            <center><a href="<?php echo base_url(); ?>reports/ver_propiedades_periodo/<?php echo $periodo->id; ?>" data-toggle="tooltip" title="Ver Propiedades"><span class="glyphicon glyphicon-search"></span></a></center>
                            <?php } ?>
                            </td>
                            <td>
                            <?php if($periodo->genera != '' && $periodo->id != $periodo_inicial->id){ ?>
                            <center><a href="<?php echo base_url(); ?>payments/comprobantes/<?php echo $periodo->id;?>" data-toggle="tooltip" title="Descargar Comprobantes" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;  
                            <a href="<?php echo base_url(); ?>payments/comprobante_detalle_ggcc/<?php echo $periodo->id;?>" data-toggle="tooltip" title="Descargar detalle Gasto Com&uacute;n" target="_blank"><span class="glyphicon glyphicon-list"></span></a></center>
                            <?php } ?>
                            </td>
                          <?php } ?>
                        </tr>
                          <?php $i++;} ?>
                        <?php  } ?>
                     </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <?php if($back_button){ ?>
                    <div class="box-footer">
                      <a href="javascript:history.back(1)" class="btn btn-default">Volver</a>
                    </div> 
                  <?php } ?>                  
                </div>
              </div>
          </div>
        </section><!-- /.content -->

  <script>
      $(function () {
        $('.table').dataTable({
          "responsive" : true,
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