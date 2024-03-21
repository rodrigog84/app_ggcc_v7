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
                          &nbsp;&nbsp;<input type="checkbox" name="pagototal" id="pagototal" class="minimal" <?php echo $datosdeuda->saldo_publicado <= 0 ? 'disabled' : ''; ?> checked/>   
                        </div>
                      </div>
                    </div>                    
                    <div class='row'>

                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="periodo">Per&iacute;odo</label>
                            <select name="periodo" id="periodo" class="form-control" <?php echo $datosdeuda->saldo_publicado <= 0 ? 'disabled' : ''; ?> >
                              <option value=""> <?php echo $datosdeuda->saldo_publicado <= 0 ? 'Sin Per&iacute;odos con deuda' : 'Seleccione Periodo'; ?></option>
                              <?php $selected = false; ?>
                              <?php foreach($datosperiodo as $periodo){ ?>
                              <option value="<?php echo $periodo->id;?>" data-monto='<?php echo $periodo->saldo;?>' <?php echo $selected ? 'selected' : ''; ?>><?php echo date2string($periodo->mes,$periodo->anno)." : $ ".number_format($periodo->saldo,0,".",".");?></option>
                              <?php $selected = false; ?>
                              <?php } ?>
                            </select>
                        </div> 
                      </div>                      
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="monto">Monto Deuda a Pagar</label>  
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="monto" name="monto" class="form-control miles" placeholder="Deuda Actual: <?php echo $datosdeuda->saldo_publicado > 0 ? "$ ".number_format($datosdeuda->saldo_publicado,0,".",".") : 0;?>" readonly>
                          </div>  
                          <small class="help-block" id="mje_monto_abono" style="color:red">&nbsp;</small>
                        </div>
                      </div>

                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="monto">Comisi&oacute;n Pago en Linea</label>  
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="comision" name="comision" class="form-control miles"  readonly>
                          </div>  
                          <small class="help-block" id="mje_monto_abono" style="color:red">&nbsp;</small>
                        </div>
                      </div>                      
                      <div class='col-md-6'>
                        <div class="form-group">
                          <label for="monto">Total a Pagar</label>  
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="total" name="total" class="form-control miles"  readonly>
                          </div>  
                          <small class="help-block" id="mje_monto_abono" style="color:red">&nbsp;</small>
                        </div>
                      </div>
                    </div>
                                                                                
                       <input type="hidden" id="numero" name="numero" value="<?php echo $datosdeuda->numero;?>">
                       <input type="hidden" id="idpropiedad" name="idpropiedad" value="<?php echo $datosdeuda->id;?>">
                       <input type="hidden" id="deudatotal" name="deudatotal" value="<?php echo $datosdeuda->saldo_publicado;?>">
                       <input type="hidden" name="tokentgc" id="tokentgc"  value="<?php echo $token_tgc;?>" >
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <!--button type="submit" class="btn btn-success">Abonar</button-->
                    <button type="button" id='registrapago' class="btn btn-success">Registrar Pago</button>

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

function calcula_total_pagar(){
     var comision = 2.99;
     var porc_iva = 19;

     var factor_iva = (1 + porc_iva/100);
     var comision_iva = comision*factor_iva;


     var factor_deuda = (1 - (comision_iva/100));
     console.log(factor_deuda)
     var monto = parseInt(replaceAll($('#monto').val(),".",""));


      var monto_total = Math.round(monto/factor_deuda);
      var comision_total = monto_total - monto;


      $('#comision').val(number_format(comision_total,0,',','.'));
      $('#total').val(number_format(monto_total,0,',','.'));


}

// al cargar
  var deudatotal = parseInt($('#deudatotal').val()) > 0 ? $('#deudatotal').val() : 0;

   $("#monto").val(number_format(deudatotal,0,',','.'));
   $("#monto").prop('readonly','readonly');
   //$("#monto").val($("#deudatotal").val());
   $("#periodo").prop('disabled',true);
   calcula_total_pagar();



$('#periodo').on('change',function(){
      var monto = $(this).find(':selected').data('monto');
      $("#monto").val(number_format(monto,0,',','.'));
      $('#mje_monto_abono').html('');
      calcula_total_pagar()

});


$("#pagototal").on('ifChecked',function(event){
  $('#basicBootstrapForm').formValidation('updateStatus', 'monto','NOT_VALIDATED'); //quita validacion
  $('#basicBootstrapForm').formValidation('updateStatus', 'periodo','NOT_VALIDATED'); //quita validacion
  $('#mje_monto_abono').html('');
  var deudatotal = $('#deudatotal').val();

   $("#monto").val(number_format(deudatotal,0,',','.'));
   calcula_total_pagar();
  // $("#monto").prop('readonly','readonly');
   //$("#monto").val($("#deudatotal").val());
   $("#periodo").prop('disabled',true);
   $("#periodo").val('');


});


$("#pagototal").on('ifUnchecked',function(event){
  // $("#monto").prop('readonly',false);
   //$("#monto").val('');
   $("#periodo").prop('disabled',false);
   //$('#basicBootstrapForm').formValidation('updateStatus', 'monto','NOT_VALIDATED'); //quita validacion

      var monto = $('#periodo').find(':selected').data('monto');

      console.log(monto)
      $("#monto").val(number_format(monto,0,',','.'));   
      calcula_total_pagar();
});


</script>

<script>

 $('.miles').mask('000.000.000.000.000', {reverse: true});

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

$('#monto').on('input',function(){

    $('#mje_monto_abono').html('');
})





//function que genera el llamado
const request = async (data) => {
  const response = await fetch('https://<?php echo BASE_URL_PAYKU;?>/api/transaction', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer <?php echo $token_pagoonline; ?>'
    },
    body: JSON.stringify(data)
  });
  const result = await response.json();
  console.log(result)
  var id = result.id;
  var status = result.status;
  var url = result.url;
  console.log(result)
  if(status == 'register'){
      
      var monto = parseInt(replaceAll($('#monto').val(),".",""));
      var comision = parseInt(replaceAll($('#comision').val(),".",""));
      var total = parseInt(replaceAll($('#total').val(),".",""));

      var fecpago = $('#fecpago').val();
      var tokentgc = $('#tokentgc').val();   
      var periodo = $('#periodo').val(); 
     // var pagototal = $('#pagototal').val();   

      if($('#pagototal').is( ":checked" ) ){
        pagototal = 'on';
      }else{
        pagototal = 'off';
      }
      
         
      var deudatotal = parseInt($('#deudatotal').val());
      var token = result.id;

          $.ajax({
              type: "POST",
              url: '<?php echo base_url();?>payments/pagoonlineprop/',
              dataType: 'json',
              data : {
                      "monto": monto,
                      "comision": comision,
                      "total": total,
                      "fecpago": fecpago,
                      "token": token,
                      "tokentgc" : tokentgc,
                      "etapaproceso" : 1,
                      "deudatotal" : deudatotal,
                      "idperiodo" : periodo,
                      "pagototal" : pagototal,
                    },
              async: false,
          }).success(function(data) {


          }); 



      
      location.href=url;
      
     // window.open(url, '_blank');
  }
  
}

  $('#registrapago').on('click',function(){

     

     var monto = parseInt(replaceAll($('#monto').val(),".",""));
     var total = parseInt(replaceAll($('#total').val(),".",""));

     var deudatotal = parseInt($('#deudatotal').val());
     console.log(monto)
     console.log(deudatotal)
     if(monto == ''){
        $('#mje_monto_abono').html('Monto es requerido');
     }else if(isNaN(monto)){
        $('#mje_monto_abono').html('Monto es requerido');        
     }else if(monto > deudatotal){
        $('#mje_monto_abono').html('Monto no puede ser mayor a deuda total');

     }else if(monto < 1){
        $('#mje_monto_abono').html('Monto deber ser mayor a cero');

     }else{
          $(this).attr('disabled','disabled');
          //console.log('registrapago')
          
          <?php if(PAGO_ONLINE_PRUEBA){ ?>
              var montopago = 5;
          <?php }else{ ?>
              var montopago = total;
          <?php } ?>
           
          var tokentgc = $('#tokentgc').val();

          // array con datos del llamado
          let data = {
            email: "<?php echo $this->session->userdata('identity'); ?>",
            order: tokentgc,
            subject: "Pago Mensualidad Unidad Nro. <?php echo $this->session->userdata('comunidadnumero').'. Condominio  '. $this->session->userdata('comunidadnombre');?>",
            amount: montopago,
            payment: 1,
            urlreturn: "<?php echo base_url();?>payments/pagoreturnprop?orderClient=" + tokentgc, //pagina a la que se devuelve despues de pagar

            //cuando el pago es aceptado llama a esto
            // link funciona sólo en la werb (no local), ya que localmente no es capaz de llegar a esta ruta
            urlnotify: "<?php echo base_url();?>guest/pagonotifyprop?orderClient=" + tokentgc
            //http://localhost/app_ggcc/guest/pagonotifyprop?orderClient=mCXuGZaZMAPBHnzJkqm6
          };      

          //llamada a la api
          request(data);
      

     }
//



  })




</script>

