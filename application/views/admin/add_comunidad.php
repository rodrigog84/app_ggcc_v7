        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_comunidades" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="comunidad">Nombre Comunidad</label>
                          <input type="text" class="form-control" name="comunidad" id="comunidad" placeholder="Ingrese Nombre Comunidad" value="<?php echo $datos_form['nombre']; ?>" >
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="rutcomunidad">Rut Comunidad</label>  
                             <input type="text" id="rutcomunidad" name="rutcomunidad" class="form-control" placeholder="Ingrese Rut Comunidad" value="<?php echo $datos_form['rut']; ?>">
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="direccion">Direcci&oacute;n</label>    
                              <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese Direcci&oacute;n" value="<?php echo $datos_form['direccion']; ?>">
                        </div>   
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="region">Region</label>   
                            <select name="region" id="region"  class="form-control">
                                <option value="">Seleccione Regi&oacute;n</option>
                                <?php foreach ($regiones as $region) { ?>
                                  <?php $regionselected = $region->idregion == $datos_form['idregion'] ? "selected" : ""; ?>
                                  <option value="<?php echo $region->idregion;?>" <?php echo $regionselected;?> ><?php echo $region->nombre;?></option>
                                <?php } ?>
                            </select> 
                        </div> 
                      </div>  
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          
                          <label for="comuna">Comuna</label> 
                            <select name="comuna" id="comuna"  class="form-control">
                              <option value="">Seleccione Comuna</option>
                            </select>
                            <input type="hidden" id="idcomuna" value="<?php echo $datos_form['idcomuna']; ?>" >
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fono">Fono</label>
                                <div class="input-group">
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>                              
                                  <input type="text" class="form-control" name="fono" id="fono" placeholder="Ingrese Fono" value="<?php echo $datos_form['fono']; ?>">
                                </div>
                        </div> 
                      </div>  
                    </div>


                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fono2">Fono 2</label>
                                <div class="input-group">
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>                              
                                  <input type="text" class="form-control" name="fono2" id="fono2" placeholder="Ingrese Fono 2" value="<?php echo $datos_form['fono2']; ?>">
                                </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="email">Email</label>    
                              <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese Email" value="<?php echo $datos_form['email']; ?>">
                              </div>
                        </div>   
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="saldo">Saldo Inicial Condominio</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="saldo" id="saldo" placeholder="Ingrese Saldo inicial Condominio" value="<?php echo empty($datos_form['saldo']) ? '' : number_format($datos_form['saldo'],0,".","."); ?>" <?php if($datos_form['idcomunidad'] != 0){ echo "disabled"; } ?> >
                              </div>
                        </div>   
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="caja">Fondo de Reserva Inicial</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="fondoreserva" id="fondoreserva" placeholder="Ingrese Monto Fondo Reserva inicial"  value="<?php echo empty($datos_form['fondoreserva']) ? '' : number_format($datos_form['fondoreserva'],0,".","."); ?>" <?php if($datos_form['idcomunidad'] != 0){ echo "disabled"; } ?>>
                              </div>
                        </div>   

                      </div>
                    </div> 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="caja">Monto Caja Inicial</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="caja" id="caja" placeholder="Ingrese Monto Caja inicial"  value="<?php echo empty($datos_form['caja']) ? '' : number_format($datos_form['caja'],0,".","."); ?>" <?php if($datos_form['idcomunidad'] != 0){ echo "disabled"; } ?>>
                              </div>
                        </div>   

                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fecinicio">Fecha Inicio</label>
                              <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>                              
                                <input class="form-control" size="16" type="text" readonly name="fecinicio" id="fecinicio" value="<?php echo $datos_form['fecinicio']; ?>" placeholder="dd/mm/aaaa">

                              </div>
                        </div>   
                      </div>                      
                    </div>                     

                  </div><!-- /.box-body -->
                  <input type="hidden" name="idcomunidad" value="<?php echo $datos_form['idcomunidad']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_comunidades" class="btn btn-default">Volver</a>
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

$('#region').change(function(){

    if($(this).val() != ''){

      $.get("<?php echo base_url();?>admins/get_comunas/"+$(this).val(),function(data){
               // Limpiamos el select
                    $('#comuna option').remove();
                    var_json = $.parseJSON(data);
                    $('#comuna').append('<option value="">Seleccione Comuna</option>');
                    for(i=0;i<var_json.length;i++){
                      $('#comuna').append('<option value="' + var_json[i].idcomuna + '">' + var_json[i].nombre + '</option>');
                    }
                    $('#basicBootstrapForm').formValidation('revalidateField', 'comuna');
      });
      
    }
});


$(document).ready(function() {

    if($('#region').val() != ''){
      $.get("<?php echo base_url();?>admins/get_comunas/"+$('#region').val(),function(data){
               // Limpiamos el select
                    $('#comuna option').remove();
                    var_json = $.parseJSON(data);
                    $('#comuna').append('<option value="">Seleccione Comuna</option>');
                    for(i=0;i<var_json.length;i++){
                      $('#comuna').append('<option value="' + var_json[i].idcomuna + '">' + var_json[i].nombre + '</option>');
                    }
                    $("#comuna").val($('#idcomuna').val()); 
      });
      // seleccionar comuna

    }

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
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            comunidad: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Nombre Comunidad es requerido'
                    }
                }
            },
            rutcomunidad: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Rut Comunidad es requerido'
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
                        message: 'Direcci&oacute;n Comunidad es requerido'
                    }
                }
            },

            region: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Regi&oacute;n Comunidad es requerido'
                    }
                }
            },

            comuna: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Comuna es requerida'
                    }
                }
            },

            fono: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Fono es requerido'
                    },
                    integer: {
                        message: 'Fono s&oacute;lo puede contener n&uacute;meros'
                    }                
                }
            },            
            fono2: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Fono 2 Comunidad es requerido'
                    },
                    integer: {
                        message: 'Fono 2 s&oacute;lo puede contener n&uacute;meros'
                    }                    
                }
            },            

            email: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Email Comunidad es requerido'
                    },
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }                    
                }
            },  

            saldo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Saldo Inicial es requerido'
                    },
                    /*integer: {
                        message: 'Saldo Inicial s&oacute;lo puede contener n&uacute;meros'
                    } */                   
                }
            },

            caja: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto Caja es requerido'
                    },
                    /*integer: {
                        message: 'Monto Caja s&oacute;lo puede contener n&uacute;meros'
                    } */                   
                }
            },   
            fondoreserva: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Fondo de Reserva es requerido'
                    },
                    /*integer: {
                        message: 'Monto Caja s&oacute;lo puede contener n&uacute;meros'
                    } */                   
                }
            },                                          
        }
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true});  

});

        $('.miles').keypress(function(event){
          if ((event.keyCode < 48)||(event.keyCode > 57)){
            event.preventDefault();
          } 
        })        
</script>  
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