        <!-- Main content -->
        <section class="content" >

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Vida &Uacute;til</h3>
                </div><!-- /.box-header -->
                <!-- form start -->


                <form id="basicBootstrapForm" action="<?php echo base_url();?>contabilidad/submit_vida_util" method="post" role="form">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="fr"><h4><span><?php echo $cuenta->proveedor;?></span></h4></label><br>
                          <label for="fr"><h4><span class="label label-danger">MONTO CUENTA $ <?php echo number_format(abs($cuenta->monto),0,".",".");?></span></h4></label>
                          
                        </div>

                        
                          <div class="form-group">
                            <label for="fecprotesto">Vida &Uacute;til (Meses)</label>
                            <input type="text" class="form-control" name="vidautil" id="vidautil" placeholder="Ingresar Vida &uacute;til" value="<?php echo $cuenta->vidautil == 0 ? '' : $cuenta->vidautil;?>">
                          </div>
                          
                        <input type="hidden" name="cuentaid" value="<?php echo $cuenta->id;?>" >
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-success"><?php echo $cuenta->vidautil == 0 ? 'Agregar' : 'Editar';?></button>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url();?>contabilidad/activo_fijo" class="btn btn-default">Volver</a>                    
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
            vidautil: {
                row: '.form-group',
                validators: {
                    notEmpty: {
                        message: 'Vida &Uacute;til es requerida'
                    },
                    greaterThan: {
                        value: 1,
                        message: 'The valor ingresado debe ser mayor o igual a 1'
                    }
 

                }
            }
        }
    })

});

  

  </script>    

