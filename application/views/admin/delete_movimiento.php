        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Eliminaci&oacute;n de Movimiento <?php echo trackid($movimiento->folio); ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_delete_movimiento" method="post" role="form">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="fr"><h4><span class="label label-danger">MONTO MOVIMIENTO $ <?php echo number_format(abs($movimiento->monto),0,".",".");?></span></h4></label>
                          
                        </div>

                        <div class="form-group">
                          <input type="radio" name="motivo" id="error" class="minimal"  value='error' checked />&nbsp;
                          <label for="fr">Error de Digitaci&oacute;n</label>
                          
                        </div>
                        <div class="form-group">
                          <input type="radio" name="motivo" id="error" class="minimal"  value='reemplazo'  />&nbsp;
                          <label for="fr">Reemplazo de Documento</label>
                          
                        </div>
                        <div class="form-group">
                          
                          <input type="radio" name="motivo" id="protesto" class="minimal"  value='protesto'/>&nbsp;
                          <label for="fr">Protesto</label>
                        </div>
                        <div id="datos_protesto" style="display:none">
                          <div class="form-group">
                            <label for="fecprotesto">Fecha Protesto</label>
                            <div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                              <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                              <input class="form-control" size="16" type="text" readonly name="fecprotesto" id="fecprotesto" value="<?php echo date("d/m/Y");?>" placeholder="dd/mm/aaaa" >
                               
                            </div>
                          </div>
                          <div class="form-group">
                              <label for="descripcion">Descripci&oacute;n</label>    
                              <textarea class="form-control" rows="3" name="descripcion" id="descripcion" placeholder="Descripcion" ></textarea>
                          </div>   
                        </div>                      

                        <input type="hidden" name="movimientoid" value="<?php echo $movimiento->id;?>" >
                        <input type="hidden" name="folio" value="<?php echo $movimiento->folio;?>" >                                                                                           
                        <input type="hidden" name="tipo_movimiento" value="<?php echo $movimiento->tipo_movimiento;?>" > 
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Eliminar</button>

                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>payments/conciliacion" class="btn btn-default">Volver</a>                    
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
        startDate: new Date()
    });


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
            monto: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Monto es requerido'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },
 

                }
            },            
            porc: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Porcentaje es requerido'
                    },
                    between: {
                        min: 0,
                        max: 100,
                        message: 'El porcentaje debe estar entre 0 y 100'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },

                }
            },
            interes: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Inter&eacute;s Gasto Com&uacute;n es requerido'
                    },
                    between: {
                        min: 0,
                        max: 100,
                        message: 'El porcentaje debe estar entre 0 y 100'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },                  
                }
            }            
        }
    })

});

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });


        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').on('ifChecked',function(event){
          var fieldid = $(this).attr('id');

          if(fieldid == 'protesto'){
            $('#datos_protesto').show();
          }else{
            $('#datos_protesto').hide()
          }

        });


  </script>    