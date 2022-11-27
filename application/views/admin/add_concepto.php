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
          <br>
          <form action="<?php echo base_url();?>admins/submit_proveedor" method="post">
          <div class="col-md-10">
            <div class="box box-solid ">
              <div class="box-body">
                <div class="row">
                  <div class="col-lg-4">
                          <label for="proveedores">Tipos de Cuenta</label>    
                          <select name="tipocuenta" id="tipocuenta"  class="form-control"  >
                              <option value="">Seleccione un Tipo de Cuenta</option>
                              <?php foreach ($tipos_concepto as $tipo_concepto) { ?>
                                <option value="<?php echo $tipo_concepto->id;?>"><?php echo $tipo_concepto->nombre;?></option>
                              <?php } ?>
                          </select>     
                  </div>
                  <div class="col-lg-4">
                          <label for="proveedores">Proveedores</label>    
                          <select name="proveedores" id="proveedores"  class="form-control"  >
                              <option value="">Seleccione un Proveedor</option>
                              <?php foreach ($proveedores as $proveedor) { ?>
                                <option value="<?php echo $proveedor->id;?>"><?php echo $proveedor->nombre;?></option>
                              <?php } ?>
                          </select>     
                  </div>
                  <div class="col-lg-4">    
                            <label for="proveedores">&nbsp;</label>
                            <div class="input-group">
                              <div class="input-group-btn">
                                <button type="button" id="nuevo" class="btn btn-primary">Nuevo</button>
                              </div><!-- /btn-group -->
                              <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder="Ingrese Nombre Proveedor" readonly>
                            </div><!-- /input-group -->                            
                            
                  </div>
                </div>         
              </div><!-- /.box-body -->
            </div><!-- /.box -->

          </div><!-- /.col (left) -->
          <div class="bottom-aligned-text col-md-2">
              <br><br><button type="submit" class="btn btn-success">Agregar Proveedor</button>
          </div>

          <div class="row">
            
              <div class="col-md-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Listado de Proveedores de Comunidad</h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre Proveedor</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($proveedores_comunidad) > 0 ){ ?>
                        <?php $i = 1; ?>
                        <?php foreach ($proveedores_comunidad as $proveedor_comunidad) { ?>
                         <tr >
                          <td><?php echo $i ;?></td>
                          <td><?php echo $proveedor_comunidad->nombre;?></td>
                          <td><a href="<?php echo base_url();?>admins/delete_proveedor/<?php echo $proveedor_comunidad->id;?>" data-toggle="tooltip" title="Eliminar" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                        <?php $i++;?>
                        <?php } ?>
                      <?php }else{ ?>
                        <tr  >
                          <td colspan="3">No existen Proveedores asociados a la Comunidad</td>
                        </tr>
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
$(document).ready(function() {
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            propiedad: {
                row: '.col-xs-3',
                validators: {
                    notEmpty: {
                        message: 'Propiedad es requerida'
                    }
                }
            },
            periodo: {
                row: '.col-xs-3',
                validators: {
                    notEmpty: {
                        message: 'Per&iacute;odo es requerido'
                    },
                }
            },
            concepto: {
                row: '.col-xs-3',
                validators: {
                    notEmpty: {
                        message: 'Concepto es requerido'
                    }                  
                }
            },
            monto: {
                row: '.col-xs-3',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                    regexp: {
                        regexp: /^[0-9]/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    }                    

                }
            },
        }
    })

});
</script>  
<script>

$("#nuevo").on('click',function(){
  $("#proveedor").prop('readonly',false);
  $("#proveedores").prop('selectedIndex',0);
});


$("#proveedores").on('change',function(){
  if($(this).val() != ''){
    $("#proveedor").val('');
    $("#proveedor").prop('readonly',true);
  }
});



</script>