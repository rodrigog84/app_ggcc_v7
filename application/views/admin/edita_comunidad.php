        <!-- Main content -->
        <section class="content">
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
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Informaci&oacute;n Comunidad</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_comunidad" id="basicBootstrapForm" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="comunidad">Nombre Comunidad</label>
                          <input type="text" class="form-control" name="comunidad" id="comunidad" placeholder="Ingrese Nombre Comunidad" value="<?php echo $datos_form['nombre']; ?>" readonly >
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
                              <label for="caja">Saldo Inicial</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles_negativos" name="cajainicial" id="cajainicial" placeholder="Ingrese Saldo inicial"  value="<?php echo empty($datos_form['cajainicial']) ? 0 : number_format($datos_form['cajainicial'],0,".","."); ?>" >
                              </div>
                        </div>   

                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="caja">Fondo de Reserva Inicial</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles_negativos" name="fondoreservainicial" id="fondoreservainicial" placeholder="Ingrese Fondo Reserva inicial"  value="<?php echo empty($datos_form['fondoreservainicial']) ? 0 : number_format($datos_form['fondoreservainicial'],0,".","."); ?>" >
                              </div>
                        </div>   

                      </div>                      
                    </div>                    


                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="descripcion">Observaciones en Comprobante</label>    
                            <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion"><?php echo $datos_form['descripcion']; ?></textarea>
                        </div>  
                      </div>                                         
                      

                      <div class='col-md-3'>
                        <div class="form-group">
                              <label for="caja">Logo Comunidad</label>    
                              <div class="input-group">
                                <img src="<?php echo $datos_form['logo'] == '' || is_null($datos_form['logo']) ? base_url().'img/logo4_1_80p_color.png' : base_url().'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $datos_form['logo'];?>" >
                                <br><br>
                                <a href="<?php echo base_url();?>admins/ver_comprobante_muestra" target="_blank"><b>As&iacute; se ver&aacute; tu comprobante!</b></a>
                              </div>
                        </div>   

                      </div>  

                      <div class='col-md-3'>
                        <div class="form-group">
                              <label for="caja">Firma Administrador</label>    
                              <div class="input-group">
                                <img src="<?php echo $datos_form['firma'] == '' || is_null($datos_form['firma']) ? base_url().'img/Imagen_no_disponible-0.png' : base_url().'uploads/firmas/'. $this->session->userdata('comunidadid') . '/' . $datos_form['firma'];?>" height="100" width="170" >
                              </div>
                        </div>   

                      </div>                                           
                    </div> 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Adjuntar Logo Comunidad</label>
                                  <input type="file" id="userfile" name="userfile" accept="image/*">
                                </div>
                        </div>  
                      </div>                      
                      <div class='col-md-6'>
                        <div class="form-group">
                            <br>
                            <label for="suscrito">Borrar Logo</label> 
                            &nbsp;&nbsp;<input type="checkbox" name="borrarlogo" id="borrarlogo" <?php echo $datos_form['logo'] == '' || is_null($datos_form['logo']) ? 'disabled' : '';?> class="minimal" />  
                        </div>   
                      </div>
                     
                    </div>       

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <div class="form-group">
                                  <label for="exampleInputFile">Adjuntar Firma Administrador</label>
                                  <input type="file" id="userfilesignature" name="userfilesignature" accept="image/*">
                                </div>
                        </div>  
                      </div>                      
                      <div class='col-md-6'>
                        <div class="form-group">
                            <br>
                            <label for="suscrito">Borrar Firma</label> 
                            &nbsp;&nbsp;<input type="checkbox" name="borrarfirma" id="borrarfirma" <?php echo $datos_form['firma'] == '' || is_null($datos_form['firma']) ? 'disabled' : '';?> class="minimal" />  
                        </div>   
                      </div>
                     
                    </div>                                       

                  </div><!-- /.box-body -->
                  <input type="hidden" name="idcomunidad" value="<?php echo $datos_form['idcomunidad']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Actualizar</button>
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

        $('.miles_negativos').on('input',function(){
          $(this).val(numberFormatNegative($(this).val()));

        });


         $('.miles_negativos').keypress(function(event){
          if (!(event.keyCode == 45 || (event.keyCode >= 48  && event.keyCode <= 57)) ){
            event.preventDefault();
          } 
        })   

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
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
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

            cajainicial: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Saldo inicial es requerido'
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



        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });


</script>  
