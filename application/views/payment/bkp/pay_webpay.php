        <!-- Main content -->
        <section class="content" >

          <div class="row">
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Registro de Pago de Cuenta</h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>payments/webpay" method="post" role="form">
                  <div class="box-body">

                        
                          <div class="form-group">
                            <label for="fecpago">Fecha Pago</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fecpago" id="fecpago" value="<?php echo date("d/m/Y");?>" placeholder="dd/mm/aaaa" >
                               
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="fecprotesto">M&uacute;mero de Pagos</label>
                            <select class="form-control" name="numpagos" id="numpagos">
                                <option value="">Seleccione N&uacute;mero de Pagos</option>
                                <option value="1">1&nbsp;(&nbsp;$&nbsp;<?php echo number_format($monto_cuota*1,0,".",".");?>&nbsp;)</option>
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
                    <input type="hidden" name="idcomunidad" value="<?php echo $idcomunidad;?>" >
                    <button type="submit" class="btn btn-success">Registrar Pago</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>main/dashboard" class="btn btn-default">Volver</a>                    
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

});

</script>