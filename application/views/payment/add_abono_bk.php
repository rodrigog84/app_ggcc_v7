<!--script src="http://code.jquery.com/jquery-1.9.1.min.js"></script-->
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/formValidation.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>dist/js/formValidation.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>dist/js/framework/bootstrap.js"></script>
<link href="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
<link href="<?php echo base_url(); ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- Main content -->
        <section class="content">
                <form id="basicBootstrapForm" action="<?php echo base_url();?>payments/submit_abono" method="post" role="form" enctype="multipart/form-data">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Abonar Deuda Propiedad Nro <?php echo $datosdeuda->numero;?> </h4>
                  </div>
                  <div class="modal-body">
                  <div class="box box-primary">

                      <div class="box-body">
                      <div class="row">
                        <div class="col-xs-12">
                        <div class="form-group">
                          <label for="monto">Pago Deuda Total</label>  
                          &nbsp;&nbsp;<input type="checkbox" name="pagototal" id="pagototal" class="minimal" />        
                        </div>
                        </div>
                      </div>                       
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="monto">Monto</label>  
                          <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" id="monto" name="monto" class="form-control" placeholder="Deuda Actual: <?php echo $datosdeuda->saldo_publicado > 0 ? "$ ".number_format($datosdeuda->saldo_publicado,0,".",".") : 0;?>">
                          </div>         
                        </div>
                      </div>             
                      <div class="row">
                        <div class="col-xs-12">                      
                          <div class="form-group">
                            <label for="periodo">Per&iacute;odo</label>
                            <select name="periodo" id="periodo" class="form-control">
                              <option value="">Seleccione Periodo</option>
                              <?php foreach($datosperiodo as $periodo){ ?>
                              <option value="<?php echo $periodo->id;?>"><?php echo month2string($periodo->mes)." de ".$periodo->anno." : $ ".number_format($periodo->saldo,0,".",".");?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">                      
                          <div class="form-group">
                            <label for="fechapago">Fecha de Pago</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <input class="form-control" size="16" type="text" readonly name="fechapago"  value="<?php echo date("d/m/Y"); ?>" placeholder="dd/mm/aaaa">
                               <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                          </div>
                        </div>
                      </div>   
                      <div class="row">
                        <div class="col-xs-12">                      
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
                      <div class="row">
                        <div class="col-xs-12">                      
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
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="cheque">Nro. Cheque</label>  
                           <input type="text" id="cheque" name="cheque" class="form-control" placeholder="Nro. Cheque" disabled>
                        </div>
                      </div>   
                      <div class="row">
                        <div class="col-xs-12">
                          <label for="ruttitular">Rut Titular</label>  
                           <input type="text" id="ruttitular" name="ruttitular" class="form-control" placeholder="Rut Titular" disabled>
                        </div>
                      </div>                                                                                                        
                      <div class="row">
                        <div class="col-xs-12">                                            
                          <div class="form-group">
                            <label for="exampleInputFile">Comprobante</label>
                            <input type="file" id="comprobante" name="comprobante">
                          </div>
                        </div>
                      </div>
                      </div><!-- /.box-body -->
                       <input type="hidden" id="numero" name="numero" value="<?php echo $datosdeuda->numero;?>">
                       <input type="hidden" id="idpropiedad" name="idpropiedad" value="<?php echo $datosdeuda->id;?>">
                       <input type="hidden" id="deudatotal" name="deudatotal" value="<?php echo $datosdeuda->saldo_publicado;?>">
                  </div>   
                 </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
        </section><!-- /.content -->



<script>
$(document).ready(function() {
    $('#basicBootstrapForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            monto: {
                row: '.col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/i,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    }                 
                }
            },
            periodo: {
                row: '.col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'Per&iacute;odo es requerido'
                    }              
                }
            },   
            comprobante: {
                row: '.col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'Comprobante es requerido'
                    }              
                }
            }                           
        }
    })

});
</script>        

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
$("#formas_pago").on('change',function(event){
  $("#banco").prop('selectedIndex', 0);
  $("#cheque").val('');
  $("#ruttitular").val('');  
  if($(this).val() == 1){
    $("#banco").prop("disabled",false);
    $("#cheque").prop("disabled",true);
    $("#ruttitular").prop("disabled",true);
  }else if($(this).val() == 2){    
    $("#banco").prop("disabled",false);
    $("#cheque").prop("disabled",false);
    $("#ruttitular").prop("disabled",false);    
  }else{
    $("#banco").prop("disabled",true);
    $("#cheque").prop("disabled",true);
    $("#ruttitular").prop("disabled",true);    
  }
});


</script>