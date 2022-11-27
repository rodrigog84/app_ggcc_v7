        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Abonar Deuda Propiedad Nro <?php echo $datosdeuda->numero;?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>payments/webpay_prop" method="post" role="form"  enctype="multipart/form-data">
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

                                                                                
                       <input type="hidden" id="numero" name="numero" value="<?php echo $datosdeuda->numero;?>">
                       <input type="hidden" id="idpropiedad" name="idpropiedad" value="<?php echo $datosdeuda->id;?>">
                       <input type="hidden" id="deudatotal" name="deudatotal" value="<?php echo $datosdeuda->saldo_publicado;?>">
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Abonar</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>payments/ver_ggcc" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->
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
                    callback: {
                          message: 'Ahorro voluntario debe estar entre 0 y 100',
                          callback: function (value, validator, $field) {
                                    var monto = parseInt(replaceAll(value,'.',''));
                                    var monto_max = parseInt($('#deudatotal').val());
                                    console.log(monto+" -- "+monto_max);
                                      if(monto > monto_max){
                                        return  {
                                              valid: false,
                                              message: 'Abono no puede ser mayor a deuda total'
                                          }

                                      }else{
                                        return true;
                                      }
                            }
                    }
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

