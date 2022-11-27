<section class="content" >
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

<?php 
//echo "<h2>Step: " . $tx_step . "</h2>";

if (!isset($request) || !isset($result) || !isset($message) || !isset($next_page)) {

    $result = "Ocurri&oacute; un error al procesar tu solicitud";
    echo "<div style = 'background-color:lightgrey;'><h3>result</h3>$result;</div><br/><br/>";
    echo "<a href='.'>&laquo; volver a index</a>";
    die;
}

/* Respuesta de Salida - Vista WEB */
?>

<!--div style="background-color:lightyellow;">
    <h3>request</h3>
    <?php  var_dump($request); ?>
</div-->
<!--div style="background-color:lightgrey;">
    <h3>result</h3>
    <?php  var_dump($result); ?>
</div-->
<!--p><samp><?php  echo $message; ?></samp></p-->

<?php if (strlen($next_page) && $post_array) { ?>
        <form action="<?php echo $next_page; ?>" method="post">
            <input type="hidden" name="authorizationCode" id="authorizationCode" value="">
            <input type="hidden" name="amount" id="amount" value="">
            <input type="hidden" name="buyOrder" id="buyOrder" value="">
        <?php if($tx_step != 'End'){ ?>            
            <input type="submit" class="btn btn-primary" value="<?php echo $button_name; ?>">
         &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>            
<?php }else{ ?>
            <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>        

    <?php } ?>
        </form>

        <script>
            
            var authorizationCode = localStorage.getItem('authorizationCode');
            document.getElementById("authorizationCode").value = authorizationCode;
            
            var amount = localStorage.getItem('amount');
            document.getElementById("amount").value = amount;
            
            var buyOrder = localStorage.getItem('buyOrder');
            document.getElementById("buyOrder").value = buyOrder;
            
            localStorage.clear();
            
        </script>
        
<?php } elseif (strlen($next_page)) { ?>
    <form action="<?php echo $next_page; ?>" method="post">
    
    <input type="hidden" name="token_ws" value="<?php echo ($token); ?>">
    <!--a href=".">&laquo; volver a index</a-->
    <?php if($tx_step != 'End'){ ?>
        <input type="submit" class="btn btn-primary" value="<?php echo $button_name; ?>">
         &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>        
    <?php }else{ ?>
            <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>        

    <?php } ?>
    
</form>
<?php }else{ ?>

            <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>        
<?php } ?>

<br>
<!--a href=".">&laquo; volver a index</a-->

       </div>
    </div>

</section>

