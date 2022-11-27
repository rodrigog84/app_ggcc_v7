<section class="content" >
         <div class="row">
            <div class="col-md-12">
                    <div id='alertid' class="alert alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i id='iconid' class="icon fa "></i> Alerta!</h4>
                      <span id='message'></span>
                    </div>
            </div>
          </div>  

          <div class="row">
            
            <div class="col-md-12">

                <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>    

            </div>

          </div>

</section>

<script>

$(document).ready(function() {

$('#alertid').hide();

<?php if($token == ''){ ?>
    $('#message').html('Error al realizar el pago');
    $('#alertid').addClass('alert-danger');
    $('#iconid').addClass('fa-ban');
    $('#alertid').show();

<?php }else{ ?>


const request = async () => {
  const response = await fetch('https://<?php echo BASE_URL_PAYKU;?>/api/transaction/<?php echo $token;?>', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer <?php echo TOKEN_PUBLICO_PAYKU; ?>'
    },
  });
  const result = await response.json();
  console.log(result)
  if(result.status == 'success'){

    $('#message').html('Pago ACEPTADO. Para continuar servicio de comunidad: <?php echo $datoscomunidad->nombre; ?>, hasta el d&iacute;a <?php echo $datoscomunidad->fecvencimiento; ?>');
    $('#alertid').addClass('alert-success');
    $('#iconid').addClass('fa-check');
    $('#alertid').show();

          $.ajax({
              type: "POST",
              url: '<?php echo base_url();?>payments/pagoonline/',
              dataType: 'json',
              data : {
                      "payment": result.payment,
                      "token": '<?php echo $token;?>',
                      "etapaproceso" : 2
                    },
              async: false,
          }).success(function(data) {


          }); 


  }else{

    $('#message').html('Error al realizar el pago');
    $('#alertid').addClass('alert-danger');
    $('#iconid').addClass('fa-ban');
    $('#alertid').show();

  }
  console.log(result)
  console.log(result.status)
}

request();


<?php } ?>
/*

////success
amount: "100"
created_at: "2022-06-23 16:33:23"
email: "rgonzalez@tugastocomun.cl"
full_name: null
gateway_response: {status: 'success', message: 'successful transaction'}
id: "trxde6fedbf2e78e8fea"
order: "kUPhbydFopq5QUORvLaR"
payment: {start: '2022-06-23 16:33:23', end: '2022-06-23 16:34:33', media: 'Webpay plus', transaction_id: '16560164033441', payment_key: '16560164033441', …}
status: "success"
subject: "Pago Mensualidad TGC"


/// pending
amount: "100"
created_at: "2022-06-23 16:50:19"
email: "rgonzalez@tugastocomun.cl"
full_name: null
gateway_response: {status: 'pending', message: 'waiting response'}
id: "trx0e0391e2cf2d1f32e"
order: "DszMLS3aaeZTIvQcMuil"
payment: []
status: "pending"
subject: "Pago Mensualidad TGC"

*/

});

</script>