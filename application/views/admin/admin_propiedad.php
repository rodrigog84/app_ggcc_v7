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
          <?php if($this->session->userdata('level') == 4){ ?>

           <form class="form-horizontal" action="<?php echo base_url();?>admins/admin_propiedad" method="post" >
            <div class="col-md-12">
              <div class="row">
                 <div class="form-group">
                      
                      <div class="col-md-3">
                          <label for="estadoConsulta" class="control-label">Comunidad</label>
                       
                          <select name="comunidad" id="comunidad" class="form-control">
                               <option value="">Seleccione Comunidad</option>

                              <?php foreach($comunidades as $comunidad){ 
                                  $estado_selected = $comunidad->id == $idcomunidad ? 'selected' : '';
                                  ?>
                                  <option value="<?php echo $comunidad->id; ?>" <?php echo $estado_selected; ?>><?php echo $comunidad->nombre; ?></option>
                              <?php } ?>                        
                          </select>
                      </div>    
                      <div class="col-md-1">
                          <label for="estadoConsulta" class="control-label">&nbsp;</label>
                       
                         <input type="submit" class="btn btn-info form-control" value="buscar">
                      </div>                                    
                                        
                 </div>
                </div>
            </div>
            </form>
          <?php } ?>
          <form action="<?php echo base_url();?>admins/admin_propiedad" method="post">
          <div>
              <a href="<?php echo base_url();?>admins/add_propiedad" type="submit" class="btn btn-primary">Agregar Propiedad</a>
              &nbsp;&nbsp;
              <a href="<?php echo base_url();?>admins/carga_propiedades" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Carga Masiva</a>
              &nbsp;&nbsp;
              <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;Registrar Suscripci&oacute;n</button>
              &nbsp;&nbsp;
              <a href="#" class="" id="susc_all"><span class="glyphicon glyphicon-check"></span>&nbsp;&nbsp;<span id="suscripcion_text">Marcar Todos</span></a>
          </div>


          <br>
          <?php //} ?>

          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Propiedades</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                      <table id="listado" class="table table-bordered table-striped dt-responsive">
                      <thead>
                        <tr>
                          <th><small>#</small></th>
                          <th><small>Nro.</small></th>
                          <th><small>Responsable</small></th>
                          <th><small>Email</small></th>
                          <th><small>Prorr. Propiedad</small></th>
                          <th><small>Prorr. Total</small></th>
                          <th><small>Comunidad</small></th>
                          <th><small>Unid. Asociadas</small></th>
                          <th><small>Suscribir Correo</small></th>
                          <th>&nbsp;</th>                        
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($propiedades) > 0 ){ ?>
                          <?php $i = 1; ?>
                          <?php foreach ($propiedades as $propiedad) { ?>
                           <tr >
                            <td><small><?php echo $i ;?></small></td>
                            <td><small><?php echo $propiedad->numero;?></small></td>
                            <td><small><?php echo $propiedad->responsable;?></small></td>
                            <td><small><?php echo $propiedad->mail;?></small></td>
                            <td><small><?php echo round($propiedad->prorrateo_propiedad,6)."%";?></small></td>
                            <td><small><?php echo round($propiedad->prorrateo,6)."%";?></small></td>
                            <td><small><?php echo $propiedad->comunidad;?></small></td>

                            <td><small><center><a href="<?php echo base_url();?>admins/ver_unidades_asociadas/<?php echo $propiedad->id;?>" data-toggle="tooltip" title="Ver Unidades Asociadas"><?php echo $propiedad->unidades_asociadas; ?></a></center></small></td>
                            <td><center><input type="checkbox" data-idprop="<?php echo $propiedad->id;?>" name="suscribe-<?php echo $propiedad->id;?>" class="minimal suscribe" id="suscribe-<?php echo $propiedad->id;?>" value="<?php echo $propiedad->id;?>" <?php echo $propiedad->suscrito == 1 ? 'checked' : '';?>><input type='hidden' class="insuscribe" name='insuscribe-<?php echo $propiedad->id;?>' id='insuscribe-<?php echo $propiedad->id;?>' value="<?php echo $propiedad->suscrito;?>"></center></td>
                            <td>
                            <a href="<?php echo base_url();?>admins/add_propiedad/<?php echo $propiedad->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                            &nbsp;
                            &nbsp;
                            <?php if($this->session->userdata('level') == 4){ ?>
                            <a href="<?php echo base_url();?>admins/delete_propiedad/<?php echo $propiedad->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
                            
                            <?php } ?>
                            </td>                          
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
          </form>
        </section><!-- /.content -->

<script>
      $(function () {
        $('#listado').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,20,30,45,100,-1],[10,20,30,45,100,'Todos']],
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
        $('input[type="checkbox"].minimal-green, input[type="radio"].minimal-green').iCheck({
          checkboxClass: 'icheckbox_minimal-green',
          radioClass: 'iradio_minimal-green'
        });



$('#susc_all').on('click',function(){

    if($('#suscripcion_text').html() == 'Marcar Todos'){
        $('#suscripcion_text').html('Desmarcar Todos')
         $(".suscribe").iCheck('check');
         $('.insuscribe').val(1);
    }else{
        $('#suscripcion_text').html('Marcar Todos')
        $(".suscribe").iCheck('uncheck');
        $('.insuscribe').val(0);
    }

  $('.suscribe').each(function () {
    console.log($(this).attr('id'))
  });      

})


$(".suscribe").on('ifToggled',function(event){
    $('.suscribe').each(function () {
      var idprop = $(this).data('idprop');

      if($(this).is(':checked')){
        $('#insuscribe-' + idprop).val(1);  
      }else{
        $('#insuscribe-' + idprop).val(0);  
      }

    }); 
  });
</script>