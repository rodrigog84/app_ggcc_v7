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
              <a href="<?php echo base_url();?>admins/add_user" type="submit" class="btn btn-primary">Agregar Usuario</a>
          </div>
          <br>

          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Usuarios</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <?php if($this->session->userdata('comunidadid') == ''){ ?>
                        <th>&nbsp;</th>   
                        <?php } ?>                     
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($users) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($users as $user) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $user->nombre;?></td>
                          <td><?php echo $user->email;?></td>
                          <td><?php echo $user->levelname;?></td>
                          <?php if($this->session->userdata('comunidadid') == ''){ ?>
                          <td>

                          <a href="<?php echo base_url();?>admins/add_user/<?php echo $user->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;
                          <a href="<?php echo base_url();?>admins/delete_user/<?php echo $user->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
                          
                          </td>                          
                          <?php } ?>
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

 <?php if(count($users_sin_notificar) > 0 ){ ?>          
<form id="basicBootstrapForm" action="<?php echo base_url();?>admins/envio_masivo_mails_usuarios" method="post">
          <div class="row">
            
              <div class="col-md-12">
                <div class="box  box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Usuarios Sin Recepci&oacute;n de Datos de Acceso</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="listado2" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th width="20px"><input type="checkbox" class="minimal-green" id="usuarios_all" /></th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <?php if($this->session->userdata('comunidadid') == ''){ ?>
                        <th>&nbsp;</th>   
                        <?php } ?>                     
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($users_sin_notificar) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($users_sin_notificar as $user) { ?>
                         <tr >
                          <td><input type="checkbox" class="minimal usuarios" name="user-<?php echo $user->id;?>" id="user-<?php echo $user->id;?>" /></td>
                          <td><?php echo $user->nombre;?></td>
                          <td><?php echo $user->email;?></td>
                          <td><?php echo $user->levelname;?></td>
                          <?php if($this->session->userdata('comunidadid') == ''){ ?>
                          <td>

                          <a href="<?php echo base_url();?>admins/add_user/<?php echo $user->id;?>" data-toggle="tooltip" title="Editar" ><span class="glyphicon glyphicon-edit"></span></a>
                          &nbsp;
                          &nbsp;
                          <a href="<?php echo base_url();?>admins/delete_user/<?php echo $user->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a>
                          
                          </td>                          
                          <?php } ?>
                        </tr>
                        <?php $i++;?>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <?php if(count($users_sin_notificar) > 0 ){ ?>
                  <div class="box-footer">
                  <button type="submit" class="btn btn-success" id="enviacredsel">Enviar Credenciales a Seleccionados</button>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>admins/envio_masivo_mails_usuarios/0/1" id="enviacred" class="btn btn-primary ">Enviar Credenciales a Todos</a>
                  </div>
                  <?php } ?>
                </div>
              </div>

            
          </div>   

</form>
<?php } ?>
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


      $(function () {
        $('#listado2').dataTable({
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

</script>

<script>
$("#usuarios_all").on('ifChecked',function(event){
   $(".usuarios").iCheck('check');
});



$("#usuarios_all").on('ifUnchecked',function(event){
   $(".usuarios").iCheck('uncheck');
});


        $('#enviacred').on('click',function(){
            $('#enviacred').attr('disabled','disabled');
            $('#enviacredsel').attr('disabled','disabled');          

        })

        $("#basicBootstrapForm").submit(function () {
            $("#enviacredsel").attr("disabled", true);
            $('#enviacred').attr('disabled','disabled');
            return true;
        });


</script>