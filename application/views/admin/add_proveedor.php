        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_proveedor" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="proveedor">Proveedor</label>
                      <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder="Ingrese Nombre Proveedor" value="<?php echo $datos_form['nombre']; ?>" >
                    </div>
                    <div class="form-group">
                      <label for="rut">Rut</label>
                      <input type="text" class="form-control" name="rut" id="rut" placeholder="Ingrese Rut" value="<?php echo $datos_form['rut']; ?>" >
                    </div>                    
                    <div class="form-group">
                      <label for="direccion">Direcci&oacute;n</label>
                      <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese Direcci&oacute;n" value="<?php echo $datos_form['direccion']; ?>" >
                    </div>                    

                  </div><!-- /.box-body -->
                  <input type="hidden" name="idproveedor" value="<?php echo $datos_form['idproveedor']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_proveedor" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>
        </section><!-- /.content -->


<script language="Javascript">
  
  function VerificaRut(rut) {
    if (rut.toString().trim() != '') {
      
        var caracteres = new Array();
        var serie = new Array(2, 3, 4, 5, 6, 7);
        var dig = rut.toString().substr(rut.toString().length - 1, 1);
        rut = rut.toString().substr(0, rut.toString().length - 1);
        for (var i = 0; i < rut.length; i++) {
            caracteres[i] = parseInt(rut.charAt((rut.length - (i + 1))));
        }
 
        var sumatoria = 0;
        var k = 0;
        var resto = 0;
 
        for (var j = 0; j < caracteres.length; j++) {
            if (k == 6) {
                k = 0;
            }
            sumatoria += parseInt(caracteres[j]) * parseInt(serie[k]);
            k++;
        }
 
        resto = sumatoria % 11;
        dv = 11 - resto;
 
        if (dv == 10) {
            dv = "K";
        }
        else if (dv == 11) {
            dv = 0;
        }

        if (dv.toString().trim().toUpperCase() == dig.toString().trim().toUpperCase())
            return true;
        else
            return false;
    }
    else {
        return false;
    }
  }


function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}


</script>

<script>
$(document).ready(function() {
     FormValidation.Validator.validateRut = {
        validate: function(validator, $field, options) {
          var validador = true;
          $field.Rut();
          var rut = $field.val();
          var cleanRut = replaceAll(rut,".","");
          var cleanRut = replaceAll(cleanRut,"-","");
          if(VerificaRut(cleanRut)){
              return true;

          }else{
              return {
                  valid : false
              }

          }


        }
    };


    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            proveedor: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Proveedor es requerido'
                    }
                }
            },
            rut: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Rut Proveedor es requerido'
                    },
                    stringLength: {
                        min: 0,
                        max: 12,
                        message: 'El largo del Rut es Incorrecto'
                    },
                    validateRut: {
                      message: 'Rut Incorrecto'
                    }

                }
            },  
            direccion: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Direcci&oacute;n es requerida'
                    }
                }
            }
        }
    })

});
</script>  
