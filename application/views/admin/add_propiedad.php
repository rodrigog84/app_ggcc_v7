        <!-- Main content -->
        <section class="content" >
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $titulo; ?> Propiedad</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_propiedad" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="comunidad">Comunidad</label>   
                            
                            <?php 
                            //$this->session->userdata('id') ?>
                            <?php if($permite_editar){ ?>
                            <select name="comunidad" id="comunidad"  class="form-control">
                                <option value="">Seleccione Comunidad</option>
                                <?php foreach ($comunidades as $comunidad) { ?>
                                  <?php $comunidadselected = $comunidad->id == $datos_form['idcomunidad'] ? "selected" : ""; ?>
                                  <option value="<?php echo $comunidad->id;?>" <?php echo $comunidadselected;?> ><?php echo $comunidad->nombre;?></option>
                                <?php } ?>
                            </select> 
                            <?php }else{ ?>
                                <input type="text" class="form-control" value="<?php echo $this->session->userdata('comunidadnombre'); ?>" readonly>
                                <input type="hidden" id="comunidad" name="comunidad" value="<?php echo $this->session->userdata('comunidadid');?>" >

                            <?php } ?>
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="numpropiedad">N&uacute;mero Propiedad</label>  
                             <input type="text" id="numpropiedad" name="numpropiedad" class="form-control" placeholder="Ingrese N&uacute;mero Propiedad" value="<?php echo $datos_form['numero']; ?>" <?php echo $permite_editar_prop ? '' : 'readonly'; ?> >
                        </div>
                      </div>   
                    </div>                 

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="responsable">Direcci&oacute;n</label>    
                              <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese Direcci&oacute;n Propiedad" value="<?php echo $datos_form['direccion']; ?>">
                        </div>   
                      </div>   
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="responsable">Rut Responsable</label>    
                              <input type="text" class="form-control" name="rutresponsable" id="rutresponsable" placeholder="Ingrese Rut Responsable" value="<?php echo $datos_form['rutresponsable']; ?>">
                        </div>   
                      </div>                                       

                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="responsable">Nombre Responsable</label>    
                              <input type="text" class="form-control" name="responsable" id="responsable" placeholder="Ingrese Nombre Responsable" value="<?php echo $datos_form['responsable']; ?>">
                        </div>   
                      </div>                      
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="email">Email</label>    
                              <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese Email" value="<?php echo $datos_form['mail']; ?>">
                              </div>
                        </div> 
                      </div>



                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                                <label for="fono">Fono</label>
                                <div class="input-group">
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>                              
                                  <input type="text" class="form-control" name="fono" id="fono" placeholder="Ingrese Fono" value="<?php echo $datos_form['fono']; ?>">
                                </div>
                        </div> 
                      </div>                       
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="prorrateo">Prorrateo</label>    
                              <div class="input-group">
                                <span class="input-group-addon">%</span>
                                <input type="text" class="form-control" name="prorrateo" id="prorrateo" placeholder="Ingrese Porcentaje Prorrateo" value="<?php echo $datos_form['prorrateo']; ?>">
                              </div>
                        </div>   
                      </div>                    


 
                    </div>

                    <div class="row">
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="saldo">Saldo Inicial Propiedad</label>    
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control miles" name="saldo" id="saldo"   placeholder="Ingrese Saldo inicial Propiedad" value="<?php echo $datos_form['idpropiedad'] == 0 ? '' : number_format($datos_form['saldoinicial'],0,".","."); ?>" <?php if($permite_editar_saldo == 0){ echo "disabled"; } ?>>
                              </div>
                              <p class="help-block">(*) Saldo inicial s&oacute;lo es modificable en caso no de existir Gastos Comunes generados ni abonos.</p> 
                        </div> 
                      </div>                        
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="email">Agregar Email</label>    
                              <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" name="emailnuevo[]" placeholder="Ingrese Email" value="<?php echo isset($array_email[0]) ? $array_email[0] : '';?>">
                                 <span class="input-group-addon"><span class="addButton"><i class="fa fa-plus"></i></span></span>
                              </div> 
                        </div>   
                      </div>
                    
                    </div>                     



                    <div class="row">

                      <div class='col-md-6'>
                        <div class="form-group">
                            <br>
                            <label for="suscrito">Suscrito a Env&iacute;os mediante correo electr&oacute;nico</label> 
                            &nbsp;&nbsp;<input type="checkbox" name="suscrito" id="suscrito" <?php if($datos_form['suscrito'] == 1) echo "checked"; ?> class="minimal" />  
                        </div>   
                      </div>                        
                      <div class='col-md-6'>
                      <?php $i = 0; ?>
                        <?php foreach ($array_email as $data_email) { ?>
                          <?php if($i > 0){ ?>
                          <div class="form-group " id="optionTemplate">
                                <label for="email">Agregar Email</label>    
                                <div class="input-group">
                                  <span class="input-group-addon">@</span>
                                  <input type="text" class="form-control" name="emailnuevo[]" placeholder="Ingrese Email" value="<?php echo $data_email;?>">
                                   <span class="input-group-addon"><span class="removeButton"><i class="fa fa-minus"></i></span></span>
                                </div> 
                          </div>
                          <?php } ?>
                            <?php $i++;?> 
                        <?php } ?>
                                     
                        <div class="form-group hide" id="optionTemplate">
                              <label for="email">Agregar Email</label>    
                              <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" name="emailnuevo[]" placeholder="Ingrese Email" >
                                 <span class="input-group-addon"><span class="removeButton"><i class="fa fa-minus"></i></span></span>
                              </div> 
                        </div>   


                      </div>
                    </div> 


                  

                    <!--div class="row">
                      <div class='col-md-6'>
                        <div class="form-group">
                              <label for="email">Agregar Mail</label>    
                              <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" name="emailnuevo" id="emailnuevo" placeholder="Ingrese Email" value="<?php echo $datos_form['mail']; ?>">
                                 <span class="input-group-addon"><a href="#" id="masmail"><i class="fa fa-plus"></i></a></span>
                                 <input type="hidden" name="otromail" id="otromail">
                              </div>
                        </div> 
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <br>
                            <label for="suscrito">Suscrito a Env&iacute;os mediante correo electr&oacute;nico</label> 
                            &nbsp;&nbsp;<input type="checkbox" name="suscrito" id="suscrito" <?php if($datos_form['suscrito'] == 1) echo "checked"; ?> class="minimal" />  
                        </div>   
                      </div>
                    </div-->


                    <!--div class="row" id="tabla_mail">


                    </div-->

                  </div><!-- /.box-body -->
                  <input type="hidden" name="idpropiedad" value="<?php echo $datos_form['idpropiedad']; ?>" >
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success"><?php echo $titulo; ?></button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_propiedad" class="btn btn-default">Volver</a>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>
        </section><!-- /.content -->


<script>

$(document).ready(function() {


   /* $('#masmail').on('click',function(){

        



    })*/

        FormValidation.Validator.validateRut = {
            validate: function(validator, $field, options) {
                var validador = true;
                $field.Rut();
                var rut = $field.val();

                if(rut != ''){

                  var cleanRut = replaceAll(rut, ".", "");
                  var cleanRut = replaceAll(cleanRut, "-", "");
                  if (VerificaRut(cleanRut)) {
                      return true;

                  } else {
                      return {
                          valid: false
                      }

                  }


                }else{
                  return true;

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
                        message: 'Comunidad es requerido'
                    }
                }
            },


            numpropiedad: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'N&uacute;mero de Propiedad es requerido'
                    },
                    /*integer: {
                        message: 'N&uacute;mero de Propiedad s&oacute;lo puede contener n&uacute;meros'
                    },*/
                    blank: {}                 
                }
            }, 

            rutresponsable: {
                row: '.form-group',
                validators: {
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

            responsable: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Responsable es requerido'
                    }
                }
            },

            email: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Email Propiedad es requerido'
                    },
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }                    
                }
            },  


            prorrateo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Prorrateo es requerido'
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
            } , 

            saldo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Saldo Inicial es requerido'
                    },
                /*    integer: {
                        message: 'Saldo Inicial s&oacute;lo puede contener n&uacute;meros'
                    }                    
                    */

                      /*integer: {
                          message: 'Saldo Inicial s&oacute;lo puede contener n&uacute;meros',
                          // The default separators
                          thousandsSeparator: '.',
                          decimalSeparator: ',' 
                      }   */                
                }
            },
            'emailnuevo[]': {
                validators: {
                   /* notEmpty: {
                        message: 'Email Propiedad es requerido'
                    },*/
                    emailAddress: {
                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                    }  
                }
            }                                        
        }
    })
  .on('click', '.addButton', function() {
            var $template = $('#optionTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .insertBefore($template),
                $option   = $clone.find('[name="emailnuevo[]"]');

            // Add new field
            $('#basicBootstrapForm').formValidation('addField', $option);
        })   

        // Remove button click handler
        .on('click', '.removeButton', function() {
            var $row    = $(this).parents('.form-group'),
                $option = $row.find('[name="emailnuevo[]"]');

            // Remove element containing the option
            $row.remove();

            // Remove field
            $('#basicBootstrapForm').formValidation('removeField', $option);
        })


    .on('success.form.fv', function(e) { /**** VALIDAR EN SERVIDOR VIA AJAX ******/

       /* if($('#saldo').val() == ''){
          $('#basicBootstrapForm').formValidation('revalidateField', 'saldo');
        }*/
        // Prevent default form submission
        e.preventDefault();

        var $form = $(e.target),                    // The form instance
            fv    = $form.data('formValidation');   // FormValidation instance

        // Send data to back-end
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admins/validate_property_number',
            data: $form.serialize(),
            dataType: 'json'
        }).success(function(response) {
            // We will display the messages from server if they're available

            // If there is error returned from server

              if (response.result === 'error') {
                  //console.log(response.fields);
                  for (var field in response.fields) {

                      fv
                          // Show the custom message
                          .updateMessage(field, 'blank', response.fields[field])
                          // Set the field as invalid
                          .updateStatus(field, 'INVALID', 'blank');
                  }
              } else {
                  // Do whatever you want here
                  // such as showing a modal ...
                  fv.defaultSubmit();
              }            
        });
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true});        

});
</script>  


<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        $('.miles').keypress(function(event){
          if ((event.keyCode < 48)||(event.keyCode > 57)){
            event.preventDefault();
          } 
        })


</script>


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
            } else if (dv == 11) {
                dv = 0;
            }

            if (dv.toString().trim().toUpperCase() == dig.toString().trim().toUpperCase())
                return true;
            else
                return false;
        } else {
            return false;
        }
    }


    function replaceAll(text, busca, reemplaza) {
        while (text.toString().indexOf(busca) != -1)
            text = text.toString().replace(busca, reemplaza);
        return text;
    }
</script>