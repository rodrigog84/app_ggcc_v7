        <!-- Main content -->
        <section class="content">

          <div class="row">

            <div class="col-md-6">
              <div class="box box-solid ">
                <div class="box-header">
                  <h3 class="box-title">Informaci&oacute;n Trabajador</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table">
                    <tr>
                    <td>
                    <p><b>Nombre</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $personal->nombre." ".$personal->apaterno." ".$personal->amaterno; ?></p>
                    <p><b>Rut</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $personal->rut == '' ? '' : number_format($personal->rut,0,".",".")."-".$personal->dv;?></p>               
                   
                    </td>
                                       
                    </tr>
                    </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (left) -->
          </div>


          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Movimiento del Personal <?php echo date2string($mes,$anno); ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_movimiento_personal" method="post" role="form" >
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-8'>
                        <div class="form-group">
                            <label for="periodo">Movimiento</label>
                            <div class="input-group">
                                  <select class="form-control" name="movimientos" id="movimientos">
                                      <option value="">Seleccione un movimiento</option>
                                      <?php foreach ($movimientos as $movimiento) { ?>
                                          <?php if(isset($movimiento_realizado->idmovimiento)){
                                                  $selected_movimiento = $movimiento_realizado->idmovimiento == $movimiento->id ? 'selected' : '';
                                          }else{
                                                  $selected_movimiento = "";
                                          } ?>
                                          <option value="<?php echo $movimiento->id;?>" data-rango="<?php echo $movimiento->rango; ?>" <?php echo $selected_movimiento; ?> ><?php echo $movimiento->nombre;?></option>
                                      <?php } ?>
                                  </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="periodo">Fecha(s)</label>
                            <div class="input-group">
                                   <a href="#" class="btn btn-default pull-right" id="daterange-btn" name="periodo" id="periodo" >
                                    <span class="glyphicon glyphicon-calendar"></span><span id="label_rango"><?php echo $fechadesde == '1900-01-01' ? 'Seleccionar Rango de Fechas' : month2string(substr($fechadesde,5,2)). ' ' . substr($fechadesde,8,2) . ', '.substr($fechadesde,0,4) . ' - ' . month2string(substr($fechahasta,5,2)). ' ' . substr($fechahasta,8,2) . ', '.substr($fechahasta,0,4); ?></span>
                                    <i class="fa fa-caret-down"></i>
                                  </a>
                            </div>
                        </div>                         
                        <!--div class="form-group">
                                <label for="fecmovimiento">Fecha Movimiento</label>
                                 <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input class="form-control" size="16" type="text" readonly name="fecmovimiento" id="fecmovimiento" value="<?php echo isset($movimiento_realizado->fecmovimiento) ? formato_fecha($movimiento_realizado->fecmovimiento,'Y-m-d','d/m/Y') : date('d/m/Y') ;?>" placeholder="dd/mm/aaaa">
                                     
                                 </div>
                        </div-->                         
                        <div class="form-group">
                            <label for="descripcion">Comentarios</label>    
                            <textarea class="form-control" rows="3" name="comentarios" id="comentarios" placeholder="Comentarios"><?php echo isset($movimiento_realizado->comentario) ? $movimiento_realizado->comentario : "";?></textarea>
                        </div>  

                      </div> 
                    </div>                 



                    <input type="hidden" name="idpersonal" id="idpersonal" value="<?php echo $personal->id;?>" >
                    <input type="hidden" name="idmovimiento" id="idmovimiento" value="<?php echo isset($movimiento_realizado->id) ? $movimiento_realizado->id : 0;?>" >
                    <input type="hidden" id="fechadesde" name="fechadesde" value="<?php echo $fechadesde;?>" />
                    <input type="hidden" id="fechahasta" name="fechahasta" value="<?php echo $fechahasta;?>" />                    

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success"><?php echo isset($movimiento_realizado->id) ? 'Editar' : 'Agregar'; ?></button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url().$url_back;?>" class="btn btn-default">Volver</a> 
                                       
                  </div>
                </form>
              </div><!-- /.box -->

            </div>
          </div> 
        </section><!-- /.content -->
 
  <!--script>

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

  </script-->
<script>
$('#movimientos').change(function(){

$('#basicBootstrapForm').formValidation('updateStatus', 'comentarios','NOT_VALIDATED');
var start = "<?php echo formato_fecha($fechadesde,'Y-m-d','d/m/Y');?>";
var end = "<?php echo formato_fecha($fechahasta,'Y-m-d','d/m/Y');?>";     
var idmovimiento = $(this).val();
var rango = $('#movimientos option[value="' + idmovimiento + '"]').data('rango');

if(rango == 1){

   $('#daterange-btn').daterangepicker(
                {

              singleDatePicker: false,    
              locale: {
                  format: 'DD/MM/YYYY',
                  "applyLabel": "Aceptar",
                  "cancelLabel": "Cancelar",
                  "fromLabel": "Desde",
                  "toLabel": "Hasta",
                  "customRangeLabel": "Custom",
                  "daysOfWeek": [
                      "Do",
                      "Lu",
                      "Ma",
                      "Mi",
                      "Ju",
                      "Vi",
                      "Sa"
                  ],
                  "monthNames": [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",                      
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre"
                  ],
                  "firstDay": 1
              },                  
                  /*isInvalidDate: function(date) {
                      for(var ii = 0; ii < some_date_range.length; ii++){
                        if (date.format('YYYY-MM-DD') == some_date_range[ii]){
                          return true;
                        }
                      }
                      if(date.day() == 0 || date.day() == 6)
                          return true;
                          
                      return false;
                  },*/
                  startDate: start,
                  endDate: end,
                  minDate: "<?php echo $minDate;?>",
                  maxDate: "<?php echo $maxDate;?>",
                  opens: "right",
                  drops: "up",
                },
        function (start, end ) {

          $('#fechadesde').val(start.format('YYYY-MM-DD'));
          $('#fechahasta').val(end.format('YYYY-MM-DD'));
          $('#label_rango').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#basicBootstrapForm').formValidation('revalidateField', 'comentarios');
        }
    );

    $('#fechadesde').val("<?php echo $fechadesde;?>");
    $('#fechahasta').val("<?php echo $fechahasta;?>");
    $('#label_rango').html("<?php echo $fechadesde == '1900-01-01' ? 'Seleccionar Rango de Fechas' : month2string(substr($fechadesde,5,2)). ' ' . substr($fechadesde,8,2) . ', '.substr($fechadesde,0,4) . ' - ' . month2string(substr($fechahasta,5,2)). ' ' . substr($fechahasta,8,2) . ', '.substr($fechahasta,0,4); ?>");
    $('#basicBootstrapForm').formValidation('revalidateField', 'comentarios');        

}else{

   $('#daterange-btn').daterangepicker(
                {

              singleDatePicker: true,    
              locale: {
                  format: 'DD/MM/YYYY',
                  "applyLabel": "Aceptar",
                  "cancelLabel": "Cancelar",
                  "fromLabel": "Desde",
                  "toLabel": "Hasta",
                  "customRangeLabel": "Custom",
                  "daysOfWeek": [
                      "Do",
                      "Lu",
                      "Ma",
                      "Mi",
                      "Ju",
                      "Vi",
                      "Sa"
                  ],
                  "monthNames": [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",                      
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre"
                  ],
                  "firstDay": 1
              },                  
                  /*isInvalidDate: function(date) {
                      for(var ii = 0; ii < some_date_range.length; ii++){
                        if (date.format('YYYY-MM-DD') == some_date_range[ii]){
                          return true;
                        }
                      }
                      if(date.day() == 0 || date.day() == 6)
                          return true;
                          
                      return false;
                  },*/
                  startDate: start,
                  endDate: end,
                  minDate: "<?php echo $minDate;?>",
                  maxDate: "<?php echo $maxDate;?>",
                  opens: "right",
                  drops: "up",
                },
        function (start, end ) {

          $('#fechadesde').val(start.format('YYYY-MM-DD'));
          $('#fechahasta').val(end.format('YYYY-MM-DD'));
          $('#label_rango').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#basicBootstrapForm').formValidation('revalidateField', 'comentarios');
        }
    );
    $('#fechadesde').val("<?php echo $fechadesde;?>");
    $('#fechahasta').val("<?php echo $fechadesde;?>");
    $('#label_rango').html("<?php echo $fechadesde == '1900-01-01' ? 'Seleccionar Rango de Fechas' : month2string(substr($fechadesde,5,2)). ' ' . substr($fechadesde,8,2) . ', '.substr($fechadesde,0,4) . ' - ' . month2string(substr($fechadesde,5,2)). ' ' . substr($fechadesde,8,2) . ', '.substr($fechadesde,0,4); ?>");
    $('#basicBootstrapForm').formValidation('revalidateField', 'comentarios');  


}

});


</script>
  


<script>


    $(function(){

            //var start = moment().add(1, 'day');
            //var end = moment().add(1, 'day');

            //var start = moment().format("<?php echo $fechadesde;?>", "YYYY-MM-DD");
            //var end = moment().format("<?php echo $fechadesde;?>", "YYYY-MM-DD");         

            var start = "<?php echo formato_fecha($fechadesde,'Y-m-d','d/m/Y');?>";
            var end = "<?php echo formato_fecha($fechahasta,'Y-m-d','d/m/Y');?>";         
            var min = moment();
          var some_date_range = [];            
        //Date range picker
        $('#daterange-btn').daterangepicker(
                {

              singleDatePicker: <?php echo isset($movimiento_realizado->rango) ? $movimiento_realizado->rango == 1 ? 'false' : 'true' : 'true'; ?>,    
              locale: {
                  format: 'DD/MM/YYYY',
                  "applyLabel": "Aceptar",
                  "cancelLabel": "Cancelar",
                  "fromLabel": "Desde",
                  "toLabel": "Hasta",
                  "customRangeLabel": "Custom",
                  "daysOfWeek": [
                      "Do",
                      "Lu",
                      "Ma",
                      "Mi",
                      "Ju",
                      "Vi",
                      "Sa"
                  ],
                  "monthNames": [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",                      
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre"
                  ],
                  "firstDay": 1
              },                  
                  /*isInvalidDate: function(date) {
                      for(var ii = 0; ii < some_date_range.length; ii++){
                        if (date.format('YYYY-MM-DD') == some_date_range[ii]){
                          return true;
                        }
                      }
                      if(date.day() == 0 || date.day() == 6)
                          return true;
                          
                      return false;
                  },*/
                  startDate: start,
                  endDate: end,
                  minDate: "<?php echo $minDate;?>",
                  maxDate: "<?php echo $maxDate;?>",
                  opens: "right",
                  drops: "up",
                },
        function (start, end ) {

          $('#fechadesde').val(start.format('YYYY-MM-DD'));
          $('#fechahasta').val(end.format('YYYY-MM-DD'));
          $('#label_rango').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#basicBootstrapForm').formValidation('revalidateField', 'comentarios');
        }




        );  

      });

  </script>
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
            movimientos: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar movimiento'
                    },
                }
            },

            comentarios: {
                row: '.form-group',
                validators: {



                    callback: {
                        message: 'Fecha Inicial y Final deben ser del mismo per&iacute;odo',
                        callback: function (value, validator, $field) {

                            var mesdesde = $('#fechadesde').val().substr(0,7);
                            var meshasta = $('#fechahasta').val().substr(0,7);

                            if(mesdesde != meshasta){
                                return  {
                                    valid: false,
                                    message: 'Fecha Inicial y Final deben ser del mismo per&iacute;odo'
                                } 
                            }else{
                              return true;
                            }
                          }    

                    }

                }
            }            
        }
    });      

});



</script>    