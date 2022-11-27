        <!-- Main content -->
        <section class="content" >

         <div class="row">
            <?php if(isset($message)): ?>
                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                      <?php echo $message;?>
                    </div>
            <?php endif; ?>
            <br>             
          </div>
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Propiedades de Comunidad</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nro. Propiedad</th>
                        <th>Responsable</th>
                        <?php if($this->session->userdata('level') == 1){ // EL MAIL SOLO LO VERA EL ADMINISTRADOR DE COMUNIDAD ?>
                          <th>Email</th>
                        <?php } ?>
                        <th>Prorrateo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($propiedades) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($propiedades as $propiedad) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $propiedad->numero;?></td>
                          <td><?php echo $propiedad->responsable;?></td>
                          <?php if($this->session->userdata('level') == 1){  ?>
                          <td><?php echo $propiedad->mail;?></td>
                          <?php } ?>
                          <td><?php echo round($propiedad->prorrateo,6)."%";?></td>
                        </tr>
                        <?php $i++;?>
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
        $('#listado').dataTable({
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