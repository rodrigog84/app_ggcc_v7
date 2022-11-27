        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_afp" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="nombre">Nombre AFP</label>
                          <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese Nombre AFP" value="<?php echo $datos_form['nombre']; ?>" >
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="porc">Porcentaje</label>  
                             <input type="text" id="porc" name="porc" class="form-control" placeholder="Ingrese Porcentaje AFP" value="<?php echo $datos_form['porc']; ?>">
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <br>
                            <label for="exregimen">Ex-R&eacute;gimen</label> 
                            &nbsp;&nbsp;<input type="checkbox" name="exregimen" id="exregimen" <?php if($datos_form['exregimen'] == 1) echo "checked"; ?> class="minimal" />  
                        </div>   
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <input type="hidden" name="idafp" value="<?php echo $datos_form['idafp']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>remuneraciones/afp" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>
        </section><!-- /.content -->


<script>

$(document).ready(function() {

    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            nombre: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Nombre AFP es requerido'
                    }
                }
            },
            porc: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Porcentaje AFP es requerido'
                    },
                    between: {
                        min: 0,
                        max: 100,
                        message: 'El porcentaje debe estar entre 0 y 100'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },                  
                }
            } 
        }
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true});  

});

        $('.miles').keypress(function(event){
          if ((event.keyCode < 48)||(event.keyCode > 57)){
            event.preventDefault();
          } 
        })   

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });             
</script>  