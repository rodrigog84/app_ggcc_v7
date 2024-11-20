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

      <?php if($desautoriza){ ?>
        <div class="row">
          <div class="col-md-3">
            <div class="info-box bg-aqua">
              <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Monto Desautoriza</span>
                <span class="info-box-number" id="monto_desautoriza_span">0</span>
                <input type="hidden" id="monto_desautoriza" value="0">
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->             
          <div class="col-md-3">
            <div class="info-box bg-green">
              <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Monto Deuda</span>
                <span class="info-box-number" id="monto_deuda_span"><?php echo number_format($datosdeuda->monto,0,".",".");?></span>
                <input type="hidden" id="monto_deuda" value="<?php echo $datosdeuda->monto;?>">
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->             
        </div>
      <?php } ?>
          <form action="<?php echo base_url();?>accounts/desautoriza_cuenta_masivo" method="post">
          <div class="row">
            
              <div class="col-md-12">
                <div class="box box-primary">
                  <div class="box-header">
                    <h3 class="box-title">Cuentas Gasto Com&uacute;n <?php echo isset($periodo->mes) ? date2string($periodo->mes,$periodo->anno) : ''; ?><?php if($desautoriza){ ?>&nbsp;&nbsp;<button type="submit" class="btn btn-default" data-toggle="tooltip" title="Desautorizar cuentas marcadas"><span class="glyphicon glyphicon-trash"></span></button><?php } ?></h3>  
                  </div><!-- /.box-header -->

                  <div class="box-body" >
                    
                    <table class="table table-bordered table-striped dt-responsive">
                    <thead>
                      <tr>
                      <?php if($desautoriza){ ?>
                        <th width="20px"><input type="checkbox" class="minimal-red" id="descuenta_all" /></th>
                      <?php } ?>
                        <th >Proveedor</th>
                        <th >Concepto</th>
                        <th >Tipo Documento</th>
                        <th> Monto Deuda</th>
                        <th >Abonado</th>
                        <th >Saldo</th>
                        <th >&nbsp;</th>
                        <!--th rowspan="2">Acci&oacute;n</th-->
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($cuentas) > 0 ){ ?>
                        <?php foreach ($cuentas as $cuenta) { ?>
                         <tr >
                         <?php if($desautoriza){ ?>
                          <td><input type="checkbox" class="minimal-red descuenta" name="descuenta-cuenta-<?php echo $cuenta->id;?>" id="descuenta-cuenta-<?php echo $cuenta->id;?>" /></td>
                        <?php } ?>
                          <td><?php echo $cuenta->proveedor;?></td>
                          <td><?php echo $cuenta->concepto;?></td>
                          <td><?php echo $cuenta->tipodocumentotributario;?></td>
                          <td>$&nbsp;<?php echo number_format($cuenta->monto,0,".",".");?><input type="hidden" id="des_cuenta_monto-<?php echo $cuenta->id;?>" value="<?php echo $cuenta->idtipodoctrib == 4 || $cuenta->idtipodoctrib == 18  ? $cuenta->monto*(-1) : $cuenta->monto;?>" /></td>
                          <td>$&nbsp;<?php echo number_format($cuenta->abonado,0,".",".");?></td>
                          <td><span class="label <?php echo $cuenta->saldo <= 0 ? 'label-success' : 'label-danger'; ?>"><i class="fa fa-dollar">&nbsp;<?php echo number_format($cuenta->saldo,0,".",".");?></i></span></td>
                          <td>

                          <a href="<?php echo base_url(); ?>reports/<?php echo $cuenta->tipocuenta == 'cargo' ? 'ver_cargo' : 'ver_cuenta';?>/<?php echo $cuenta->id;?>"  data-toggle="tooltip" title="Ver Cuenta"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;
                          <?php if($desautoriza){ ?>
                          <a href="<?php echo base_url(); ?>accounts/desautoriza_cuenta/cuenta/<?php echo $ggccid;?>/<?php echo $cuenta->id;?>"  data-toggle="tooltip" title="Desautorizar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                          &nbsp;
                          &nbsp;                    
                          <?php } ?>                                 
                          <?php if($cuenta->nombrearchivo != ''){ ?>
                          <a href="<?php echo base_url(); ?>uploads/cuentas/<?php echo $this->session->userdata('comunidadid')."/".$cuenta->nombrearchivo;?>" data-toggle="tooltip" title="Ver Comprobante" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php }else{ ?>
                            <?php if($upload){ ?>
                              <a href="<?php echo base_url(); ?>accounts/upfile/<?php echo $cuenta->id;?>/<?php echo $cuenta->tipocuenta;?>/<?php echo $origen;?>/<?php echo $periodo->id;?>" data-toggle="tooltip" title="Adjuntar Comprobante"><i class="fa fa-upload"></i></a>
                            <?php } ?>
                          <?php } ?>
                          </td>
                        </tr>
                        <?php } ?>
                      <?php } ?>



                      <?php if(count($ingresos) > 0 ){ ?>
                        <?php foreach ($ingresos as $ingreso) { ?>
                         <tr >
                         <?php if($desautoriza){ ?>
                          <td><input type="checkbox" class="minimal-red descuenta" name="descuenta-ingreso-<?php echo $ingreso->id;?>" id="descuenta-ingreso-<?php echo $ingreso->id;?>" /></td>
                        <?php } ?>                         
                          <td><?php echo $ingreso->proveedor;?></td>
                          <td><?php echo $ingreso->concepto;?></td>
                          <td><?php echo $ingreso->tipodocumentotributario;?></td>
                          <td>$&nbsp;<?php echo " - ".number_format($ingreso->monto,0,".",".");?><input type="hidden" id="des_ingreso_monto-<?php echo $ingreso->id;?>" value="<?php echo $ingreso->monto;?>" /></td>
                          <td>-</td>
                          <td><span class="label label-success"><i class="fa fa-dollar">&nbsp;<?php echo " - ".number_format($ingreso->monto,0,".",".");?></i></span></td>
                          <td>

                          <a href="<?php echo base_url(); ?>reports/ver_ingreso/<?php echo $ingreso->id;?>"  data-toggle="tooltip" title="Ver Ingreso"><span class="glyphicon glyphicon-search"></span></a>
                          &nbsp;
                          &nbsp;
                          <?php if($desautoriza){ ?>
                          <a href="<?php echo base_url(); ?>accounts/desautoriza_cuenta/ingreso/<?php echo $ggccid;?>/<?php echo $ingreso->id;?>"  data-toggle="tooltip" title="Desautorizar Cuenta"><span class="glyphicon glyphicon-trash"></span></a>
                          &nbsp;
                          &nbsp;                    
                          <?php } ?>                                 
                          <?php if($ingreso->nombrearchivo != ''){ ?>
                          <a href="<?php echo base_url(); ?>uploads/ingresos/<?php echo $this->session->userdata('comunidadid')."/".$ingreso->nombrearchivo;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php }else{ ?>
                            <?php if($upload){ ?>
                              <a href="<?php echo base_url(); ?>accounts/upfile/<?php echo $ingreso->id;?>/ingreso/<?php echo $origen;?>/<?php echo $periodo->id;?>" data-toggle="tooltip" title="Adjuntar Comprobante"><i class="fa fa-upload"></i></a>
                            <?php } ?>
                          <?php } ?>
                          </td>
                        </tr>
                        <?php } ?>
                      <?php } ?>                      
                    </tbody>
                    </table>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo $back == '' ? 'javascript:history.back(1)' : base_url().$back; ?>" class="btn btn-default">Volver</a>
                  </div>                  

                </div>
              </div>

            
          </div>
          <?php if($desautoriza){ ?>
            <input type="hidden" name="ggccid" value="<?php echo $ggccid;?>" />
          <?php } ?>
          </form>
        </section><!-- /.content -->


<script>
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });

</script>        

<script>

$("#descuenta_all").on('ifChecked',function(event){
   $(".descuenta").iCheck('check');
});

$("#descuenta_all").on('ifUnchecked',function(event){
   $(".descuenta").iCheck('uncheck');
});



$(".descuenta").on('ifChecked',function(event){

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var tipo = array_elem[1];
  var id_cuenta = array_elem[2];

  var valor_cuenta = parseInt($('#des_' + tipo + '_monto-'+id_cuenta).val());
  var monto_deuda = parseInt($('#monto_deuda').val());
  var monto_desautoriza = parseInt($('#monto_desautoriza').val());

  monto_deuda = tipo == 'cuenta' ? monto_deuda - valor_cuenta : monto_deuda + valor_cuenta;
  monto_desautoriza = tipo == 'cuenta' ? monto_desautoriza + valor_cuenta : monto_desautoriza - valor_cuenta;
  
  $('#monto_deuda').val(monto_deuda);
  $('#monto_deuda_span').html(number_format(monto_deuda,0,'.','.'));

  $('#monto_desautoriza').val(monto_desautoriza);
  $('#monto_desautoriza_span').html(number_format(monto_desautoriza,0,'.','.'));
});


$(".descuenta").on('ifUnchecked',function(event){

  var id_elem = $(this).attr('id');
  var array_elem = id_elem.split("-");
  var tipo = array_elem[1];
  var id_cuenta = array_elem[2];


  var valor_cuenta = parseInt($('#des_' + tipo + '_monto-'+id_cuenta).val());
  var monto_deuda = parseInt($('#monto_deuda').val());
  var monto_desautoriza = parseInt($('#monto_desautoriza').val());

  monto_deuda = tipo == 'cuenta' ? monto_deuda + valor_cuenta : monto_deuda - valor_cuenta;
  monto_desautoriza = tipo == 'cuenta' ? monto_desautoriza - valor_cuenta : monto_desautoriza + valor_cuenta;

  $('#monto_deuda').val(monto_deuda);
  $('#monto_deuda_span').html(number_format(monto_deuda,0,'.','.'));

  $('#monto_desautoriza').val(monto_desautoriza);
  $('#monto_desautoriza_span').html(number_format(monto_desautoriza,0,'.','.'));  
});


</script>