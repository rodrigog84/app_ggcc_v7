        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar Balance  &nbsp;<?php echo date2string($datos_periodo->mes,$datos_periodo->anno); ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>contabilidad/submit_cuenta_balance" method="post" role="form">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="fr"><h4><span class="label label-success">CUENTA: <?php echo $cuenta->nombre;?>.&nbsp;&nbsp;SALDO BALANCE ANTERIOR: $ <?php echo number_format($saldo_cuenta,0,".",".");?></span></h4></label>
                          
                        </div>

                        
                          <div class="form-group">
                            <label for="fecprotesto">Monto Cuenta Contable</label>
                            <!--input type="text" class="form-control miles" name="monto" id="monto" placeholder="Ingresar Monto" value="<?php echo number_format($cuenta->valor,0,".",".");?>"-->
                            <input type="text" class="form-control miles" name="monto" id="monto" placeholder="Ingresar Monto" value="<?php echo number_format($cuenta->valor - $saldo_cuenta,0,".",".");?>">
                          </div>
                          
                        <input type="hidden" name="cuentaid" value="<?php echo $cuenta->id;?>" >
                        <input type="hidden" name="idperiodo" value="<?php echo $datos_periodo->id;?>" >
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Editar</button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>contabilidad/ver_balance/<?php echo $datos_periodo->id; ?>" class="btn btn-default">Volver</a>                    
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div>
        </section><!-- /.content -->


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
                }
            }
        }
    })
});

        $('.miles').on('input',function(){
          $(this).val(numberFormatNegative($(this).val()));

        });

         $('.miles').keypress(function(event){
          if (!(event.keyCode == 45 || (event.keyCode >= 48  && event.keyCode <= 57)) ){
            event.preventDefault();
          } 
        });    
 

  </script>    

