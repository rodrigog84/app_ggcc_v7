        <!-- Main content -->
        <section class="content">

         <div class="row">

            <div class="col-md-9">
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
                    <p><b>D&iacute;as Progresivos Agregados</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo number_format($personal->diasprogresivos,2,",","."); ?></p>                    
                   
                    </td>
                    <td>
                    <p><b>Fecha Ingreso</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $personal->fecingreso; ?></p>
                  <p><b>D&iacute;as Progresivos Devengados</b></p>
                  <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo number_format($num_dias_progresivos,2,",","."); ?></p>               
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
                  <h3 class="box-title">Agregar d&iacute;a Progresivo</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_dia_progresivo" method="post" role="form" >
                  <div class="box-body">
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="periodo">Per&iacute;odo:</label>
                            <?php if($idcartola == 0){ ?>
                            <select class="form-control" name="periodo" id="periodo">
                              <option value="">Seleccione un Per&iacute;odo</option>
                              <?php foreach ($periodos as $periodo) { ?>
                                  <?php $permite = true; ?>
                                  <?php foreach ($dias_progresivos as $dia_progresivo) { ?>
                                        <?php if(substr($periodo['fecinicio'],0,4) == $dia_progresivo->fechainicio){ ?>
                                          <?php $permite = false; ?>
                                          <?php break; ?>
                                        <?php } ?>
                                  <?php } ?>

                                  <?php if($permite){ ?>
                                  <option value="<?php echo substr($periodo['fecinicio'],0,4); ?>"><?php echo substr($periodo['fecinicio'],0,4); ?></option>
                                  <?php } ?>
                              <?php } ?>


                            </select>
                            <?php }else{ ?>
                              <input type="text" class="form-control" name="periodo" id="periodo" placeholder="" value="<?php echo $dia_prog_selec->fechainicio;?>" readonly>
                            <?php } ?>
                          </div> 
                        <div class="form-group">
                              <label for="documento">D&iacute;as Solicitados:</label>    
                              <input type="text" class="form-control" name="diassolicita" id="diassolicita" placeholder="" value="<?php echo $idcartola == 0 ? '' : $dia_prog_selec->dias; ?>">
                        </div>   
                      </div> 
                    </div>                 


                    <input type="hidden" name="idcartola" id="idcartola" value="<?php echo $idcartola;?>" >
                    <input type="hidden" name="idpersonal" id="idpersonal" value="<?php echo $personal->id;?>" >

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success"  ><?php echo $titulo_guardar; ?></button>
                    &nbsp;&nbsp;
                    <a href="<?php echo $url_back;?>" class="btn btn-default">Volver</a> 
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
                        message: 'Debe seleccionar un per&iacute;odo'
                    },
                }
            },

            diassolicita: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Debe indicar fechas'
                    },
                    integer: {
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    }, 

                }
            },
        }
    });      

});



</script>  

