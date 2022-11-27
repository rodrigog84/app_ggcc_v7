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
              <a href="<?php echo base_url();?>accounts/add_cuenta" type="submit" class="btn btn-primary">Agregar Cuenta</a>
          </div> 
          <br>         
          <div class="row">
            
              <div class="col-md-12">
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#cuentas" data-toggle="tab">Listado Cuentas Sin Cuotas&nbsp;&nbsp;<?php echo "(".count($cuentas).")"; ?></a></li>
                    <li class=""><a href="#cuentas_cuotas" data-toggle="tab">Listado Cuentas En Cuotas&nbsp;&nbsp;<?php echo "(".count($cuentas_cuotas).")"; ?></a></li>
                    <li class=""><a href="#individuales" data-toggle="tab">Lecturas Individuales&nbsp;&nbsp;<?php echo "(".count($lecturas_individuales).")"; ?></a></li>
                  </ul>

                  <div class="tab-content"><!-- espacio de contenido -->
                    <div class="tab-pane active" id="cuentas" >
                        <section id="new">
                        <h4 class="box-title">Listado de Cuentas&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="1.- S&oacute;lo es posible eliminar cuentas que no est&eacute;n autorizadas y no tengan abonos.<br>2.- No se muestran en esta opci&oacute;n cuentas de lectura individual.  Estas se muestran en la edici&oacute;n de lecturas individuales" title="Atenci&oacute;n"></i></h4>  
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
                            <?php if(count($cuentas) > 0 ){ ?>
                              <?php foreach ($cuentas as $cuenta) { ?>
                               <tr >
                                <td><?php echo $cuenta->proveedor;?></td>
                                <td><?php echo $cuenta->concepto;?></td>
                                <td><?php echo $cuenta->fecvencimiento;?></td>
                                <td>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></td>
                                <td>
                                <?php if($cuenta->abonado == 0){ ?>   
                                <a href="<?php echo base_url();?>accounts/edit_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                                <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
                                  &nbsp;
                                  &nbsp;         
                                <?php if($cuenta->abonado == 0){ ?>               
                                <a href="<?php echo base_url(); ?>accounts/delete_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Eliminar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
                                  &nbsp;
                                  &nbsp;                        
                                <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                                  &nbsp;
                                  &nbsp;                        
                                  <?php if($cuenta->nombrearchivo != ''){ ?>
                                <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                <?php } ?>
                                </td>
                              </tr>
                              <?php } ?>
                            <?php } ?>
                          </tbody>
                          </table>
 



                    </div> <!-- tab content -->
                    <div class="tab-pane" id="cuentas_cuotas" >
                        <section id="new">
                        <h4 class="box-title">Listado de Cuentas en Cuotas</h4>  
                          <table class="table table-bordered table-striped dt-responsive">
                          <thead>
                            <tr>
                              <th>Proveedor</th>
                              <th>Concepto</th>
                              <th>Fecha Vencimiento</th>
                              <th>Num. Cuotas</th>
                              <th>Monto</th>
                              <th>&nbsp;</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(count($cuentas_cuotas) > 0 ){ ?>
                              <?php foreach ($cuentas_cuotas as $cuenta) { ?>
                               <tr >
                                <td><?php echo $cuenta->proveedor;?></td>
                                <td><?php echo $cuenta->concepto;?></td>
                                <td><?php echo $cuenta->fecvencimiento;?></td>
                                <td><?php echo $cuenta->numcuotas;?></td>
                                <td>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?></td>
                                <td>
                                <a href="<?php echo base_url();?>accounts/edit_cuenta_cuotas/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Editar Cuenta" ><span class="glyphicon glyphicon-edit"></span></a>
                                &nbsp;
                                  &nbsp;     
                                <a href="<?php echo base_url(); ?>accounts/delete_cuenta_cuotas/<?php echo $cuenta->id;?>" data-toggle="tooltip" title="Eliminar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                                  &nbsp;
                                  &nbsp;                        
                                                      
                                  <?php if($cuenta->nombrearchivo != ''){ ?>
                                <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                <?php } ?>
                                </td>
                              </tr>
                              <?php } ?>
                            <?php } ?>
                          </tbody>
                          </table>
 



                  </div> <!-- tab content -->

                  <div class="tab-pane " id="individuales" >
                    <section id="new">
                       <h4 class="box-title">Listado de Lecturas Individuales&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="S&oacute;lo es posible editar y eliminar cuentas asociadas a gastos comunes sin generar" title="Atenci&oacute;n"></i></h4>                      
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
                        <?php if(count($lecturas_individuales) > 0 ){ ?>
                          <?php foreach ($lecturas_individuales as $lectura_individual) { ?>
                           <tr >
                            <td><?php echo $lectura_individual->proveedor;?></td>
                            <td><?php echo $lectura_individual->concepto;?></td>
                            <td><?php echo $lectura_individual->fecvencimiento;?></td>
                            <td><?php echo date2string($lectura_individual->mes,$lectura_individual->anno); ?></td>
                            <td>$&nbsp;<?php echo number_format($lectura_individual->monto,0,".",".");?></td>
                            <td>
                            <?php if(is_null($lectura_individual->genera)){ ?>   
                            <!--a href="<?php echo base_url();?>accounts/edit_cuenta/<?php echo $lectura_individual->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a-->
                            <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>
                              &nbsp;
                              &nbsp;         
                            <a href="<?php echo base_url(); ?>reports/ver_cuenta/<?php echo $lectura_individual->id;?>" data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                              &nbsp;
                              &nbsp;                        
                            <a href="<?php echo base_url(); ?>reports/ver_detalle_lectura/<?php echo $lectura_individual->id;?>" data-toggle="tooltip" title="Ver Detalle Lectura Individual"><span class="fa fa-align-justify"></span></a>
                              &nbsp;
                              &nbsp;        
                            <a href="<?php echo base_url();?>accounts/edit_cobro_individual/<?php echo $lectura_individual->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>                  
                              &nbsp;
                              &nbsp;        
                              <?php if($lectura_individual->nombrearchivo != ''){ ?>
                                <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$lectura_individual->nombrearchivo;?>" target="_blank" data-toggle="tooltip" title="Abrir Comprobante"><span class="glyphicon glyphicon-paperclip"></span></a>
                                &nbsp;
                                &nbsp;                        
                              <?php } ?>
                            <?php if(is_null($lectura_individual->genera)){ ?>             
                            <a href="<?php echo base_url(); ?>accounts/delete_cobro_individual/<?php echo $lectura_individual->id;?>" data-toggle="tooltip" title="Eliminar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                            <?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?>

                            </td>
                          </tr>
                          <?php } ?>
                        <?php } ?>
                      </tbody>
                      </table>   
                     </section>
                  </div><!-- ab-pane active-->                        


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