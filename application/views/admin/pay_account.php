        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Registro de Pago de Cuenta</h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_pay_account" method="post" role="form">
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
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                          </div>                          

                        
                                                                                           
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <input type="hidden" name="idcomunidad" value="<?php echo $idcomunidad;?>" >
                    <button type="submit" class="btn btn-success">Registrar Pago</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>admins/admin_comunidades" class="btn btn-default">Volver</a>                    
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
        language:  'es',
        endDate: '+1d'

    })    


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