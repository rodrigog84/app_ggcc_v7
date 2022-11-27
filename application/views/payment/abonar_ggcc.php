        <!-- Main content -->
        <section class="content">
          <?php if(isset($message)): ?>
          <div class="row">
            <div class="col-xs-12">
              
                      <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                        <?php echo $message;?>
                      </div>
            </div>
          </div>
             <?php endif; ?> 
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
             
                <div class="box-header with-border">
                  <h3 class="box-title">Listado de Propiedades de Condominio <?php echo $this->session->userdata('comunidadnombre'); ?></h3>
                  <?php if(count($datosdeuda) > 0 ){ ?>
                  <div class="pull-right box-tools">
                      <h4><a href="<?php echo base_url(); ?>reports/export_saldos_propiedad" data-toggle="tooltip" title="Exportar"><i class="fa fa-file-excel-o"></i></a></h4>
                  </div>
                  <?php } ?>                  
                </div><!-- /.box-header -->


                <div class="box-body">
                  <table id="propiedades" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nro. Propiedad</th>
                      <th>Responsable</th>
                      <th>Saldo Deuda</th>
                      <?php if($abono){ ?>
                          <th>Abonar</th>
                      <?php } ?>
                      <th>Detalle</th>
                      <th>Cartola</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $suma_saldos = 0;?>
                    <?php foreach($datosdeuda as $deuda){ ?>
                      <?php $suma_saldos += $deuda->saldo < 0 ? 0 : $deuda->saldo;?>
                    <tr>
                      <td><?php echo $deuda->numero;?></td>
                      <td><?php echo $deuda->responsable;?></td>
                      <td><span class="label <?php echo $deuda->saldo <= 0 ? 'label-success' : 'label-danger'; ?>"><i class="fa fa-dollar">&nbsp;<?php echo number_format($deuda->saldo,0,".",".");?></i></span></td>
                      <?php if($abono){ ?>
                      <td>
                      <a href="<?php echo base_url();?>payments/add_abono/<?php echo $deuda->id;?>" >Abonar</a>
                      </td>
                      <?php } ?>
                      <td><a href="<?php echo base_url();?>payments/ver_ggcc_adm/<?php echo $deuda->id;?>" data-toggle="tooltip" title="Ver Detalle"><span class="glyphicon glyphicon-search"></span></a></td>
                      <td><a href="<?php echo base_url();?>reports/export_cartola_propiedad/<?php echo $deuda->id;?>" data-toggle="tooltip" title="Exportar Cartola" target='_blank'><span class="fa fa-file-excel-o"></span></a></td>
                    </tr>

                    <?php } ?>
                    </tbody>
                    <?php if(!$abono){ ?>
                        <tfoot>
                          <th>Total</th>
                          <th>&nbsp;</th>
                          <th><?php echo "$ ".number_format($suma_saldos,0,".","."); ?></th>
                          <th>&nbsp;</th>
                        </tfoot> 
                    <?php } ?>                   
                  </table>



            <div class="modal fade" id="myModal" >
              <div class="modal-dialog">
                <div class="modal-content">

                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
        </section><!-- /.content -->
 
 <script>

$('#myModal').on('hide.bs.modal', function(e) {
  $(this).removeData('bs.modal');
});        
 </script>

<script>
      $(function () {
        $('#propiedades').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,20,30,45,100,-1],[10,20,30,45,100,'All']],
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