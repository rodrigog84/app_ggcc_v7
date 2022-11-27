<script>

$(document).ready(function() {


const request = async () => {
  const response = await fetch('https://<?php echo BASE_URL_PAYKU;?>/api/transaction/trx0e0391e2cf2d1f32e', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer <?php echo TOKEN_PUBLICO_PAYKU; ?>'
    },
  });
  const result = await response.json();
  console.log(result)
}

request();

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