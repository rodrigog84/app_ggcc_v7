        <!-- Main content -->
        <section class="content" >

          <div class="row">
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Registro de Pago de Cuenta</h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <!--form id="basicBootstrapForm" action="<?php echo base_url();?>payments/webpay" method="post" role="form"-->
                <!--form id="basicBootstrapForm" action="<?php echo base_url();?>payments/pagoonline" method="post" role="form"-->
                  <div class="box-body">

                        
                          <div class="form-group">
                            <label for="fecpago">Fecha Pago</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fecpago" id="fecpago" value="<?php echo date("d/m/Y");?>" placeholder="dd/mm/aaaa" >
                               
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="fecprotesto">N&uacute;mero de Pagos</label>
                            <select class="form-control" name="numpagos" id="numpagos">
                                <!--option value="">Seleccione N&uacute;mero de Pagos</option-->
                                <option value="1" selected>1&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*1,0,".",".");?>&nbsp;)</option>
                                <option value="2">2&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*2,0,".",".");?>&nbsp;)</option>
                                <option value="3">3&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*3,0,".",".");?>&nbsp;)</option>
                                <option value="4">4&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*4,0,".",".");?>&nbsp;)</option>
                                <option value="5">5&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*5,0,".",".");?>&nbsp;)</option>
                                <option value="6">6&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*6,0,".",".");?>&nbsp;)</option>
                                <option value="7">7&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*7,0,".",".");?>&nbsp;)</option>
                                <option value="8">8&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*8,0,".",".");?>&nbsp;)</option>
                                <option value="9">9&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*9,0,".",".");?>&nbsp;)</option>
                                <option value="10">10&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*10,0,".",".");?>&nbsp;)</option>
                                <option value="11">11&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*11,0,".",".");?>&nbsp;)</option>
                                <option value="12">12&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*12,0,".",".");?>&nbsp;)</option>
                            </select>
                          </div>  

                      
                                                                                           
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <input type="hidden" name="idcomunidad" id='idcomunidad' value="<?php echo $idcomunidad;?>" >
                    <input type="hidden" name="montocuota" id="montocuota"  value="<?php echo $monto_cuota;?>" >
                    <input type="hidden" name="tokentgc" id="tokentgc"  value="<?php echo randomstring_mm(20);?>" >

                    <button type="button" id='registrapago' class="btn btn-success">Registrar Pago</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>                    
                  </div>
                <!--/form-->
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->

<script>

$(document).ready(function() {


//function que genera el llamado
const request = async (data) => {
  const response = await fetch('https://<?php echo BASE_URL_PAYKU;?>/api/transaction', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer <?php echo TOKEN_PUBLICO_PAYKU; ?>'
    },
    body: JSON.stringify(data)
  });
  const result = await response.json();
  console.log(result)
  var id = result.id;
  var status = result.status;
  var url = result.url;

  if(status == 'register'){
      

      var montocuota = $('#montocuota').val();
      var numpagos = $('#numpagos').val();
      var fecpago = $('#fecpago').val();
      var tokentgc = $('#tokentgc').val();      
      var token = result.id;

          $.ajax({
              type: "POST",
              url: '<?php echo base_url();?>payments/pagoonline/',
              dataType: 'json',
              data : {
                      "montocuota": montocuota,
                      "numpagos": numpagos,
                      "fecpago": fecpago,
                      "token": token,
                      "tokentgc" : tokentgc,
                      "etapaproceso" : 1
                    },
              async: false,
          }).success(function(data) {


          }); 




      location.href=url;
     // window.open(url, '_blank');
  }
  
}





  $('#registrapago').on('click',function(){

     $(this).attr('disabled','disabled');
      console.log('registrapago')
      var montocuota = $('#montocuota').val();
      var numpagos = $('#numpagos').val();
      var montopago = parseInt(montocuota*numpagos); 
      var tokentgc = $('#tokentgc').val();

      // array con datos del llamado
      let data = {
        email: "rgonzalez@tugastocomun.cl",
        order: tokentgc,
        subject: "Pago Mensualidad TGC",
        amount: montopago,
        payment: 1,
        urlreturn: "<?php echo base_url();?>payments/pagoreturn?orderClient=" + tokentgc,
        urlnotify: "<?php echo base_url();?>guest/pagonotify?orderClient=" + tokentgc
      };      

      //llamada a la api
      request(data);


  })

  /*
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            numpagos: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'N&uacute;mero de pagos es requerido'
                    }
                }
            },
        }
    })
      */
});





</script>