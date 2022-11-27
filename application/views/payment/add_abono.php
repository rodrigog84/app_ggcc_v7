        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Abonar Deuda Propiedad Nro <?php echo $datosdeuda->numero;?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>payments/submit_abono" method="post" role="form"  enctype="multipart/form-data">
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-12'>
                        <div class="form-group">
                          <label for="monto">Pago Deuda Total</label> 
                          &nbsp;&nbsp;<input type="checkbox" name="pagototal" id="pagototal" class="minimal" <?php echo $datosdeuda->saldo_publicado <= 0 ? 'disabled' : ''; ?>/>   
                        </div>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="monto">Monto</label>  
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="monto" name="monto" class="form-control miles" placeholder="Deuda Actual: <?php echo $datosdeuda->saldo_publicado > 0 ? "$ ".number_format($datosdeuda->saldo_publicado,0,".",".") : 0;?>">
                          </div>  
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="periodo">Per&iacute;odo</label>
                            <select name="periodo" id="periodo" class="form-control" <?php echo $datosdeuda->saldo_publicado <= 0 ? 'disabled' : ''; ?> >
                              <option value=""> <?php echo $datosdeuda->saldo_publicado <= 0 ? 'Sin Per&iacute;odos con deuda' : 'Seleccione Periodo'; ?></option>
                              <?php $selected = true; ?>
                              <?php foreach($datosperiodo as $periodo){ ?>
                              <option value="<?php echo $periodo->id;?>" <?php echo $selected ? 'selected' : ''; ?>><?php echo date2string($periodo->mes,$periodo->anno)." : $ ".number_format($periodo->saldo,0,".",".");?></option>
                              <?php $selected = false; ?>
                              <?php } ?>
                            </select>
                        </div> 
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fechapago">Fecha de Pago</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fechapago"  value="<?php echo date("d/m/Y"); ?>" placeholder="dd/mm/aaaa">
                               
                            </div>
                        </div>  
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="periodo">Forma de Pago</label>
                            <select name="formas_pago" id="formas_pago" class="form-control">
                              <option value="">Seleccione Forma de Pago</option>
                              <?php foreach($formas_pago as $forma_pago){ ?>
                              <option value="<?php echo $forma_pago->id;?>"><?php echo $forma_pago->nombre;?></option>
                              <?php } ?>
                            </select>
                        </div>              
                      </div>
                    </div>


                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="banco">Banco</label>
                            <select name="banco" id="banco" class="form-control" disabled>
                              <option value="">Seleccione Banco</option>
                              <?php foreach($bancos as $banco){ ?>
                              <option value="<?php echo $banco->id;?>"><?php echo $banco->nombre;?></option>
                              <?php } ?>
                            </select>
                        </div>  
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="cheque">Nro. Cheque</label>  
                           <input type="text" id="cheque" name="cheque" class="form-control" placeholder="Nro. Cheque" disabled>
                        </div>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="ruttitular">Rut Titular</label>  
                           <input type="text" id="ruttitular" name="ruttitular" class="form-control" placeholder="Rut Titular" disabled>
                        </div> 
                      </div>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="fechadeposito">Fecha de Dep&oacute;sito</label>
                            <div class="input-group date "  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" id="div_fec_deposito">
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fechadeposito"  id="fechadeposito" value="" placeholder="dd/mm/aaaa" >
                               
                            </div>
                        </div>  
                      </div>                      
                    </div>

                    <div class='row'>
                    
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="exampleInputFile">Comprobante</label>
                            <input type="file" id="userfile" name="userfile">
                        </div>              
                      </div>
                    </div>                    
                                                                                
                       <input type="hidden" id="numero" name="numero" value="<?php echo $datosdeuda->numero;?>">
                       <input type="hidden" id="idpropiedad" name="idpropiedad" value="<?php echo $datosdeuda->id;?>">
                       <input type="hidden" id="deudatotal" name="deudatotal" value="<?php echo $datosdeuda->saldo_publicado;?>">
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Abonar</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>payments/abonar_ggcc" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->
<script type="text/javascript">
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
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

</script>
<script>
$("#pagototal").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'monto','NOT_VALIDATED'); //quita validacion
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
   $("#monto").val('');
   $("#monto").prop('disabled',true);
   //$("#monto").val($("#deudatotal").val());
   $("#periodo").prop('disabled',true);


});


$("#pagototal").on('ifUnchecked',function(event){
   $("#monto").prop('disabled',false);
   //$("#monto").val('');
   $("#periodo").prop('disabled',false);
   //$('#basicBootstrapForm').formValidation('updateStatus', 'monto','NOT_VALIDATED'); //quita validacion
});


</script>
<script>
$("#formas_pago").on('change',function(event){
  $("#banco").prop('selectedIndex', 0);
  $("#cheque").val('');
  $("#ruttitular").val('');  

  if($(this).val() == 1){ // Transferencia
    $('#basicBootstrapForm').formValidation('updateStatus', 'cheque','NOT_VALIDATED'); //quita validacion
    $('#basicBootstrapForm').formValidation('updateStatus', 'ruttitular','NOT_VALIDATED'); //quita validacion

    $("#banco").prop("disabled",false);
    $("#cheque").prop("disabled",true);
    $("#ruttitular").prop("disabled",true);
    $("#div_fec_deposito").removeClass("form_date");  
    $('#div_fec_deposito').datetimepicker('remove');  
    $('#fechadeposito').val('');
    //$("#div_fec_deposito").datetimepicker('destroy');
    //$('#div_fec_deposito').date("DateTimePicker").destroy();
  }else if($(this).val() == 2){    // cheque
    $("#banco").prop("disabled",false);
    $("#cheque").prop("disabled",false);
    $("#ruttitular").prop("disabled",false);    

    $("#div_fec_deposito").addClass("form_date");    
    $("#div_fec_deposito").datetimepicker({
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

    $('#fechadeposito').val(moment().format('DD/MM/YYYY'));


  }else{ // efectivo y transbank
    $('#basicBootstrapForm').formValidation('updateStatus', 'banco','NOT_VALIDATED'); //quita validacion
    $('#basicBootstrapForm').formValidation('updateStatus', 'cheque','NOT_VALIDATED'); //quita validacion
    $('#basicBootstrapForm').formValidation('updateStatus', 'ruttitular','NOT_VALIDATED'); //quita validacion

    
    $("#banco").prop("disabled",true);
    $("#cheque").prop("disabled",true);
    $("#ruttitular").prop("disabled",true);    
    if($(this).val() == 3){ // EFECTIVO
      $("#div_fec_deposito").addClass("form_date");    
      $("#div_fec_deposito").datetimepicker({
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

      $('#fechadeposito').val(moment().format('DD/MM/YYYY'));
    }else if($(this).val() == 4)  {
      $("#div_fec_deposito").removeClass("form_date");  
      $('#div_fec_deposito').datetimepicker('remove');  
      $('#fechadeposito').val('');
    }else{
      $("#div_fec_deposito").removeClass("form_date");  
      $('#div_fec_deposito').datetimepicker('remove');  
      $('#fechadeposito').val('');      
    }
  }
});


</script>

<script>
$(document).ready(function() {
   // $("#div_fec_deposito").datetimepicker('destroy');

     FormValidation.Validator.validateRut = {
        validate: function(validator, $field, options) {
          var validador = true;
          $field.Rut();
          var rut = $field.val();
          if(rut != ''){ // se valida sólo si ingresa datos
            var cleanRut = replaceAll(rut,".","");
            var cleanRut = replaceAll(cleanRut,"-","");
            if(VerificaRut(cleanRut)){
                return true;

            }else{
                return {
                    valid : false
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
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                   /* regexp: {
                        regexp: /^[0-9]+$/i,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    }*/                 
                }
            },
            periodo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Per&iacute;odo es requerido'
                    }              
                }
            },   
            formas_pago: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Forma de Pago es requerido'
                    }              
                }
            },               
            banco: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Banco es requerido'
                    }              
                }
            },  
            cheque: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Cheque es requerido'
                    }              
                }
            }, 
            ruttitular: {
                row: '.form-group',
                validators: {
                   /* notEmpty: {
                        message: 'Rut Titular es requerido'
                    },*/
                    stringLength: {
                        min: 0,
                        max: 12,
                        message: 'El largo del Rut es Incorrecto'
                    },
                    validateRut: {
                      message: 'Rut Incorrecto'
                    }

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

