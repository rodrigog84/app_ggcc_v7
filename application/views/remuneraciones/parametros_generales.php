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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>remuneraciones/submit_parametros_generales" id="basicBootstrapForm" method="post">   
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Par&aacute;metros</h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                        <div class="form-group">
                              <label for="sueldominimo">Sueldo M&iacute;nimo</label>    
                               <input type="text" class="form-control miles" name="sueldominimo" id="sueldominimo" placeholder="Ingrese Sueldo M&iacute;nimo" value="<?php echo $parametros_generales->sueldominimo; ?>">
                        </div> 
                        <div class="form-group">
                              <label for="uf">Valor UF</label>    
                               <input type="text" class="form-control miles_decimales" name="uf" id="uf" placeholder="Ingrese Valor UF" value="<?php echo number_format($parametros_generales->uf,2,",","."); ?>">
                        </div> 
                        <div class="form-group">
                              <label for="uf">Tope Imponible AFP (UF)</label>    
                               <input type="text" class="form-control miles_decimales" name="topeimponible" id="topeimponible" placeholder="Ingrese Tope Imponible" value="<?php echo number_format($parametros_generales->topeimponible,2,",",""); ?>">
                        </div>       
                        <div class="form-group">
                              <label for="uf">Tope Imponible IPS (EX - INP) (UF)</label>    
                               <input type="text" class="form-control miles_decimales" name="topeimponibleips" id="topeimponibleips" placeholder="Ingrese Tope Imponible" value="<?php echo number_format($parametros_generales->topeimponibleips,2,",",""); ?>">
                        </div>     
                        <div class="form-group">
                              <label for="uf">Tope Imponible AFC (UF)</label>    
                               <input type="text" class="form-control miles_decimales" name="topeimponibleafc" id="topeimponibleafc" placeholder="Ingrese Tope Imponible" value="<?php echo number_format($parametros_generales->topeimponibleafc,2,",",""); ?>">
                        </div>                                           
                        <div class="form-group">
                              <label for="uf">Tasa Seguro de Invalidez y Sobrevivencia (SIS)</label>    
                               <input type="text" class="form-control" name="tasasis" id="tasasis" placeholder="Ingrese Valor SIS" value="<?php echo $parametros_generales->tasasis; ?>">
                        </div> 
                        <div class="form-group">
                              <label for="cf_simple">Cargas Familiares Simples</label>    
                               <input type="text" class="form-control miles" name="cf_simple" id="cf_simple" placeholder="Ingrese Valor Cargas Familiares Simples" value="<?php echo $parametros_generales->csimples; ?>">
                        </div> 

                        <div class="form-group">
                              <label for="cf_invalidas">Cargas Familiares Inv&aacute;lidas</label>    
                               <input type="text" class="form-control miles" name="cf_invalidas" id="cf_invalidas" placeholder="Ingrese Valor Cargas Familiares Inv&aacute;lidas" value="<?php echo $parametros_generales->cinvalidas; ?>">
                        </div> 

                        <div class="form-group">
                              <label for="cf_maternales">Cargas Familiares Maternales</label>    
                               <input type="text" class="form-control miles" name="cf_maternales" id="cf_maternales" placeholder="Ingrese Valor Cargas Familiares Maternales" value="<?php echo $parametros_generales->cmaternales; ?>">
                        </div>                         


                        <div class="form-group">
                              <label for="cf_maternales">Envio Mail</label>    
                               <input type="text" class="form-control miles" name="envio_mail" id="envio_mail" placeholder="Envio de Mail" value="<?php echo $parametros_generales->envio_mail; ?>">
                        </div>      

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">Actualiza Datos</button>
                  </div>                  
              </div><!-- /.box -->
              </div>
          </div>
          </form>         
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
            sueldominimo: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Sueldo M&iacute;nimo requerido'
                    }
                }
            },


            uf: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Valor UF requerido'
                    }
                }
            },

            cf_simple: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Valor Cargas Familiares Simples es requerido'
                    }
                }
            },    


            cf_invalidas: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Valor Cargas Familiares Inv&aacute;lidas es requerido'
                    }
                }
            },                        


            cf_maternales: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Valor Cargas Familiares Maternales es requerido'
                    }
                }
            },  

            tasasis: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Tasa SIS es requerida'
                    },
                    between: {
                        min: 0,
                        max: 100,
                        message: 'Tasa debe estar entre 0 y 100'
                    },
                    numeric: {
                        separator: '.',
                        message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                    },                  
                }
            }                
        }
    })
    .find('.miles').mask('000.000.000.000.000', {reverse: true})

});

$(document).ready(function(){
 $('.miles_decimales').mask('#.##0,00', {reverse: true})        

});
</script>  

