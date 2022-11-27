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
          <form id="basicBootstrapForm" action="<?php echo base_url();?>admins/submit_accion_mora" id="basicBootstrapForm" method="post">   
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Par&aacute;metros Mora&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="popover" data-placement="bottom" data-content="Se deben ingresar los meses desde cuando se tomar&aacute;n las acciones indicadas" title="Atenci&oacute;n"></i></h3>
                </div><!-- /.box-header -->
                  <div class="box-body">
                        <div class="form-group">
                              <label for="sueldominimo">Al d&iacute;a</label>    
                              <select name="mes_aldia" id="mes_aldia"  class="form-control">
                                <option value="">Seleccione Meses</option>
                               <option value="0" <?php echo $datos_form['mes_aldia'] == 0 ? 'selected' : '';?> >0</option>
                               <option value="1" <?php echo $datos_form['mes_aldia'] == 1 ? 'selected' : '';?>>1</option>
                               <option value="2" <?php echo $datos_form['mes_aldia'] == 2 ? 'selected' : '';?>>2</option>
                               <option value="3" <?php echo $datos_form['mes_aldia'] == 3 ? 'selected' : '';?>>3</option>
                               <option value="4" <?php echo $datos_form['mes_aldia'] == 4 ? 'selected' : '';?>>4</option>
                               <option value="5" <?php echo $datos_form['mes_aldia'] == 5 ? 'selected' : '';?>>5</option>
                               <option value="6" <?php echo $datos_form['mes_aldia'] == 6 ? 'selected' : '';?>>6</option>
                            </select> 
                        </div> 
                        <div class="form-group">
                              <label for="uf">Moroso</label>    
                               <select name="mes_moroso" id="mes_moroso"  class="form-control">
                                <option value="">Seleccione Meses</option>
                                <option value="0" <?php echo $datos_form['mes_moroso'] == 0 ? 'selected' : '';?>>0</option>
                                <option value="1" <?php echo $datos_form['mes_moroso'] == 1 ? 'selected' : '';?>>1</option>
                                <option value="2" <?php echo $datos_form['mes_moroso'] == 2 ? 'selected' : '';?>>2</option>
                                <option value="3" <?php echo $datos_form['mes_moroso'] == 3 ? 'selected' : '';?>>3</option>
                                <option value="4" <?php echo $datos_form['mes_moroso'] == 4 ? 'selected' : '';?>>4</option>
                                <option value="5" <?php echo $datos_form['mes_moroso'] == 5 ? 'selected' : '';?>>5</option>
                                <option value="6" <?php echo $datos_form['mes_moroso'] == 6 ? 'selected' : '';?>>6</option>
                            </select> 
                        </div> 
                        <div class="form-group">
                              <label for="uf">Corte de Luz</label>    
                              <select name="mes_corteluz" id="mes_corteluz"  class="form-control">
                                <option value="">Seleccione Meses</option>
                               <option value="0" <?php echo $datos_form['mes_corteluz'] == 0 ? 'selected' : '';?>>0</option>
                               <option value="1" <?php echo $datos_form['mes_corteluz'] == 1 ? 'selected' : '';?>>1</option>
                               <option value="2" <?php echo $datos_form['mes_corteluz'] == 2 ? 'selected' : '';?>>2</option>
                               <option value="3" <?php echo $datos_form['mes_corteluz'] == 3 ? 'selected' : '';?>>3</option>
                               <option value="4" <?php echo $datos_form['mes_corteluz'] == 4 ? 'selected' : '';?>>4</option>
                               <option value="5" <?php echo $datos_form['mes_corteluz'] == 5 ? 'selected' : '';?>>5</option>
                               <option value="6" <?php echo $datos_form['mes_corteluz'] == 6 ? 'selected' : '';?>>6</option>
                            </select> 
                        </div>       
                        <div class="form-group">
                              <label for="uf">Cobranza Prejudicial</label>    
                               <select name="mes_prejudicial" id="mes_prejudicial"  class="form-control">
                                <option value="">Seleccione Meses</option>
                               <option value="0" <?php echo $datos_form['mes_prejudicial'] == 0 ? 'selected' : '';?>>0</option>
                               <option value="1" <?php echo $datos_form['mes_prejudicial'] == 1 ? 'selected' : '';?>>1</option>
                               <option value="2" <?php echo $datos_form['mes_prejudicial'] == 2 ? 'selected' : '';?>>2</option>
                               <option value="3" <?php echo $datos_form['mes_prejudicial'] == 3 ? 'selected' : '';?>>3</option>
                               <option value="4" <?php echo $datos_form['mes_prejudicial'] == 4 ? 'selected' : '';?>>4</option>
                               <option value="5" <?php echo $datos_form['mes_prejudicial'] == 5 ? 'selected' : '';?>>5</option>
                               <option value="6" <?php echo $datos_form['mes_prejudicial'] == 6 ? 'selected' : '';?>>6</option>
                            </select>
                        </div>     
                        <div class="form-group">
                              <label for="uf">Cobranza Judicial</label>    
                               <select name="mes_judicial" id="mes_judicial"  class="form-control">
                                <option value="">Seleccione Meses</option>
                               <option value="0" <?php echo $datos_form['mes_judicial'] == 0 ? 'selected' : '';?>>0</option>
                               <option value="1" <?php echo $datos_form['mes_judicial'] == 1 ? 'selected' : '';?>>1</option>
                               <option value="2" <?php echo $datos_form['mes_judicial'] == 2 ? 'selected' : '';?>>2</option>
                               <option value="3" <?php echo $datos_form['mes_judicial'] == 3 ? 'selected' : '';?>>3</option>
                               <option value="4" <?php echo $datos_form['mes_judicial'] == 4 ? 'selected' : '';?>>4</option>
                               <option value="5" <?php echo $datos_form['mes_judicial'] == 5 ? 'selected' : '';?>>5</option>
                               <option value="6" <?php echo $datos_form['mes_judicial'] == 6 ? 'selected' : '';?>>6</option>
                            </select>
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
<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
      trigger : 'hover',
    html: true,});   
});
</script>

