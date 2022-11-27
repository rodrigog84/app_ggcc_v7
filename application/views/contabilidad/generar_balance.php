        <!-- Main content -->
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
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Generaci&oacute;n de Balance&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="S&oacute;lo es posible generar un balance a la vez" title="Atenci&oacute;n"></i></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>contabilidad/submit_generar_balance" id="basicBootstrapForm" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="periodo">Per&iacute;odo Balance</label>    
                      <select name="periodo" id="periodo"  class="form-control"  <?php echo count($balances) > 0 ? '' : '';?>>
                          <option value="">Seleccione un Per&iacute;odo</option>
                          <?php foreach($periodos as $periodo){ ?>
                          <option value="<?php echo $periodo->id;?>"><?php echo date2string($periodo->mes,$periodo->anno);?></option>
                          <?php } ?>
                      </select>
                    </div>
                    <!--div class="form-group">
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Desde</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="fecdesde" id="fecdesde" placeholder="dd/mm/aaaa">
                                     
                                 </div>
                        </div> 
                    </div-->                     
                    <div class="form-group">
                        <div class="form-group">
                                <label for="fecdocumento">Fecha Corte</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="feccorte" id="feccorte" placeholder="dd/mm/aaaa">
                                     
                                 </div>
                        </div> 
                    </div>                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                       <?php foreach($periodos as $periodo){ ?>
                          <input type="hidden" id="corte_<?php echo $periodo->id;?>" value="<?php echo $periodo->genera_format;?>">
                          <?php } ?>                  
                    <button type="submit" class="btn btn-success <?php echo count($balances) > 0 ? '' : '';?>">Generar</button>
                  </div>
                </form>
              </div><!-- /.box -->
              </div>
          </div>

         <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de Balances no aprobados</h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped dt-responsive">
                  <thead>
                    <tr>
                      <th>Per&iacute;odo</th>
                      <th>Debe</th>
                      <th>Haber</th>
                      <th>Fecha Corte</th>
                      <th>Fecha C&aacute;lculo</th>
                      <th>Ver</th>
                      <th>Validar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($balances) > 0){ ?>

                      <?php foreach($balances as $balance){ ?>
                      <tr>
                        <td><?php echo date2string($balance->mes,$balance->anno); ?></td>
                        <td>$&nbsp;<?php echo number_format($balance->debe,0,".",".");?></td>
                        <td>$&nbsp;<?php echo number_format($balance->haber,0,".",".");?></td>
                        <td><?php echo $balance->corte;?></td>
                        <td><?php echo $balance->calculo;?></td>
                        <td>
                          <center><a href="<?php echo base_url(); ?>contabilidad/ver_balance/<?php echo $balance->idperiodo; ?>" data-toggle="tooltip" title="Ver Balance"><span class="glyphicon glyphicon-search"></span></a></center>
                        </td>
                        <td>
                          <a href="<?php echo base_url(); ?>contabilidad/acepta_balance/<?php echo $balance->idperiodo; ?>" data-toggle="tooltip" title="Aprobar" class="btn btn-xs btn-success"><span class="fa fa-check"></span></a>
                          <a href="<?php echo base_url(); ?>contabilidad/rechaza_balance/<?php echo $balance->idperiodo; ?>" data-toggle="tooltip" title="Rechazar" class="btn btn-xs btn-danger"><span class="fa fa-times"></span></a>
                        </td>                        
                                             
                                                    
                      </tr>
                      <?php } ?>

                    <?php }else{ ?>
                    <tr>
                      <td colspan="7">No existen balances pendientes</td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  </table>
                </div><!-- /.box-body -->


              </div><!-- /.box -->

            </div>
          </div>          
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
            periodo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Periodo es requerido'
                    }
                }
            }
        }
    })

});
</script>  
<script>


  $('#periodo').on('change',function(){
    $('#feccorte').datetimepicker('remove');  
    $("#feccorte").val('');
    if($(this).val() != ''){
        var idperiodo = $(this).val();
        var fec_corte = $('#corte_'+idperiodo).val();
        $('#feccorte').val(fec_corte);
        $('#feccorte').datetimepicker('option','startDate',fec_corte);
        $('#feccorte').datetimepicker('remove');  
        $("#feccorte").datetimepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "bottom-left",
            weekStart: true,
            startView: 2,
            minView: 2,
            forceParse: 0,
            language:  'es',    
            startDate : fec_corte,
            endDate: new Date() 
        });

    }




  });

  </script>
  <script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
      trigger : 'hover',
    html: true,});   
});
</script>
<style type="text/css">
  .bs-example{
      margin: 300px 50px;
    }
</style>