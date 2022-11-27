        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Prorrateo Deuda <?php echo date2string($datosdeuda->mes,$datosdeuda->anno); ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>comunity/generar_ggcc" method="post" role="form">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="fr"><h4><span class="label label-danger">MONTO DEUDA $ <?php echo number_format($datosdeuda->monto,0,".",".");?></span></h4></label>
                          
                        </div>

                        <div class="form-group">
                          <input type="radio" name="fr" id="pesos" class="minimal fondo_reserva"  value='pesos'/>&nbsp;
                          <label for="fr">Fondo de Reserva en Pesos</label>
                          
                        </div>
                        <div class="form-group">
                          
                          <input type="radio" name="fr" id="porcentaje" class="minimal fondo_reserva"  value='porcentaje'/>&nbsp;
                          <label for="fr">Fondo de Reserva en Porcentaje</label>
                        </div>
                        <div class="form-group">
                          <input type="radio" name="fr" id="sinfr" class="minimal fondo_reserva"  value='sinfr' checked/>&nbsp;
                          <label for="fr">Sin Fondo de Reserva</label>
                        </div> 

                        <div class="form-group">
                              <label for="documento">Monto</label>    
                              <input type="text" class="form-control miles" name="monto" id="monto" onpaste="return false" placeholder="Monto Fondo de Reserva" disabled>
                        </div>  
                        <div class="form-group">
                              <label for="documento">Porcentaje</label>    
                              <input type="text" class="form-control" name="porc" id="porc" onpaste="return false" placeholder="Porcentaje Fondo de Reserva" disabled>
                        </div> 

                        <hr>
                        <div class="form-group">
                          <input type="radio" name="tipo_cap" id="cm" class="minimal"  value='cm' checked />&nbsp;
                          <label for="tc">Capitalizaci&oacute;n Mensual</label>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="tipo_cap" id="cd" class="minimal"  value='cd'/>&nbsp;
                          <label for="tc">Capitalizaci&oacute;n Diaria</label>                          
                          
                        </div>
 


                        <div class="form-group">
                              <label for="documento">Tasa Inter&eacute;s Gasto Com&uacute;n</label>    
                              <input type="text" class="form-control" name="interes" id="interes" onpaste="return false" placeholder="Inter&eacute;s Gasto Com&uacute;n"  >
                        </div>  
                      <!-- #messages is where the messages are placed inside -->
                        <input type="hidden" name="ggccid" value="<?php echo $datosdeuda->ggccid;?>" >                                                                                           
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Prorratear</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>comunity/calculo_ggcc" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
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
        language:  'es'
    });

  </script>

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
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                    /*regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },*/
 

                }
            },            
            porc: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Porcentaje es requerido'
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
            },
            interes: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Inter&eacute;s Gasto Com&uacute;n es requerido'
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

        $('.fondo_reserva').on('ifChecked',function(event){

            var fieldid = $(this).attr('id');

            if(fieldid == "pesos"){
              $('#basicBootstrapForm').formValidation('updateStatus', 'porc','NOT_VALIDATED'); //quita validacion

              $('#monto').attr('disabled', false);
              $('#monto').focus();
              $('#porc').attr('disabled', true);
            

            }else if(fieldid == "porcentaje"){
              $('#basicBootstrapForm').formValidation('updateStatus', 'monto','NOT_VALIDATED'); //quita validacion
              $('#monto').attr('disabled', true);
              $('#porc').attr('disabled', false);
              $('#porc').focus();     
            }else{
              $('#basicBootstrapForm').formValidation('updateStatus', 'porc','NOT_VALIDATED'); //quita validacion
              $('#basicBootstrapForm').formValidation('updateStatus', 'monto','NOT_VALIDATED'); //quita validacion
              $('#monto').attr('disabled', true);
              $('#porc').attr('disabled', true);
            }

            $('#monto').val('');
            $('#porc').val('');

        });

        /*$("#porc").on('keypress',function(event){
            var fieldid = $(this).attr('id');
            var valor_limite = 100; 
            var new_value = String.fromCharCode(event.charCode);

            if(parseInt($(this).val()+""+new_value) > valor_limite){
                $(this).val(valor_limite);
                return false;
            }
        });*/


        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });


  </script>    