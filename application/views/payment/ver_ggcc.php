        <!-- Main content -->
        <section class="content">
        <?php if(isset($message)): ?>
          <div class="row">
            <div class="col-xs-12">
              
                      <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa <?php echo $icon;?>"></i> Alerta!</h4>
                        <?php echo $message;?>
                      </div>
            </div>
          </div>
             <?php endif; ?> 
         <div class="row">

            <div class="col-md-6">
              <div class="box box-solid ">
                <div class="box-header">
                  <h3 class="box-title">Informaci&oacute;n Propiedad</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table">
                    <tr>
                    <td>
                    <p><b>Nro. Propiedad</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $datospropiedad->numero; ?></p>
                    <p><b>Responsable</b></p>
                    <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $datospropiedad->responsable; ?></p>                   
                    </td>
                    <td>
                  <p><b>Prorrateo</b></p>
                  <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<?php echo $datospropiedad->prorrateo." %"; ?></p>
                  <p><b>Cartola de Pagos</b></p>
                  <p><i class="fa fa-circle-o text-light-blue"></i>&nbsp;&nbsp;<a href="<?php echo base_url();?>payments/ver_cartola/<?php echo $datospropiedad->id;?>/0"  >Ver Cartola</a></p>                  
                    </td>
                    </tr>
                    </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (left) -->


            <div class="col-md-3 col-md-offset-3">
              <div class="info-box <?php echo $classinfo; ?>">
                <span class="info-box-icon"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Deuda Total</span>
                  <span class="info-box-number"><?php echo number_format($datospropiedad->saldo_publicado,0,".",".");?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
           
            </div><!-- /.col -->       
            <?php if($origen == 'prop' && $datospropiedad->codigo_comercio > 0){ // si es perfil propietario y comunidad tiene codigo de comercio?>
            <div class="col-md-3 col-md-offset-0">
               <a href="<?php echo base_url();?>payments/add_abono_webpay"  class="btn bg-purple">
                      <i class="fa fa-money"></i> Pago Transbank
                    </a>&nbsp;<br><br>
                    <img src="<?php echo base_url();?>img/webpay_una_fila.png" height="25" >
           
            </div><!-- /.col -->  
          <?php } ?>


          </div>

          <div class="row">
            
            <div class="col-md-12">

              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Listado de GGCC</h3>  
                </div><!-- /.box-header -->

                <div class="box-body">
                  <table id="ggcc" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Per&iacute;odo</th>
                      <th>Monto GGCC</th>
                      <th>Monto Abonado</th>
                      <th>Saldo</th>
                      <th>Acci&oacute;n</th>
                      <th>Detalle GGCC</th>
                      <th>Cobros</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($datosggcc as $ggcc){ ?>
                    <tr>
                      <td><?php echo date2string($ggcc->mes,$ggcc->anno); ?></td>
                      <td class="text-right"><?php echo $ggcc->periodoid == $periodo_inicial->id ? "$ ".number_format($datos_inicial->monto,0,".",".") : "$ ".number_format($ggcc->monto,0,".",".");?></td>
                      <td class="text-right"><?php echo $ggcc->periodoid == $periodo_inicial->id ? "$ ".number_format($datos_inicial->abonado,0,".",".") : "$ ".number_format($ggcc->abonado,0,".",".");?></td>
                      <?php $saldo = $ggcc->periodoid == $periodo_inicial->id ? $datos_inicial->saldo : $ggcc->saldo; ?>
                      <td class="text-right"><span class="label <?php echo $saldo <= 0 ? 'label-success' : 'label-danger'; ?>"><i class="fa fa-dollar">&nbsp;<?php echo number_format($saldo,0,".",".");?></i></span></td>
                      <td>
                          <?php if($ggcc->periodoid == $periodo_inicial->id){ ?>
                            &nbsp;
                          <?php }else{ ?>
                            <a href="<?php echo base_url();?>payments/ver_cartola/<?php echo $datospropiedad->id;?>/<?php echo $ggcc->periodoid;?>" >Ver Pagos</a>
                          <?php } ?>
                      </td>
                      <td>
                        <?php if($ggcc->periodoid == $periodo_inicial->id){ ?>
                            &nbsp;
                          <?php }else{ ?>
                             <a href="<?php echo base_url();?>payments/ver_detalle/<?php echo $ggcc->ggccid;?>"  ><span class="glyphicon glyphicon-search"></span></a>
                          <?php } ?>                             
                      </td>                      
                      <td>
                          <?php if($ggcc->periodoid == $periodo_inicial->id){ ?>                      
                            &nbsp;
                          <?php }else{ ?>                        
                              <a href="<?php echo base_url(); ?>payments/download_ggcc/<?php echo $datospropiedad->id."/".$ggcc->periodoid;?>"><span class="glyphicon glyphicon-paperclip"></span></a>
                          <?php } ?>                             
                      </td>
                      <!--td><a href="<?php echo base_url(); ?>uploads/ggcc/<?php echo $datospropiedad->id."/".$ggcc->nombrearchivo;?>" target="_blank"><span class="glyphicon glyphicon-paperclip"></span></a></td-->
                    </tr>
                    <?php } ?>
                  </tbody>
                  </table>




                </div><!-- /.box-body -->
              <?php if($volver){ ?>
                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a> 
                  </div>              
                 
              <?php } ?>

              </div><!-- /.box -->

            </div>
          </div>


          <?php if(isset($is_admin)){ ?>
          <div class="row">
            <div class="col-md-12">
              <!-- Bar chart -->
              <div class="box box-primary">
                <div class="box-header">
                  <i class="fa fa-bar-chart-o"></i>
                  <h3 class="box-title">Gasto Com&uacute;n Propiedad <?php echo $this->session->userdata('comunidadnumero'); ?>. &Uacute;ltimos 12 Per&iacute;odos.  </h3>
                </div>
                <div class="box-body">
                  <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div><!-- /.box-body-->

                  <div class="box-footer">
                    <a href="javascript:history.back(1)" class="btn btn-default">Volver</a> 
                  </div>                 
              </div><!-- /.box -->
            </div><!-- /.col -->          
          </div>
          <?php } ?>
        </section><!-- /.content -->
 
 <script>

/*$('#myModal').on('hide.bs.modal', function(e) {
  $(this).removeData('bs.modal');
});*/        
 </script>

     <script>
      $(function () {
        $('#ggcc').dataTable({
          "bLengthChange": true,
          "bFilter": true,
          "bSort": false,
          "bInfo": true,
          "bAutoWidth": false,
          "aLengthMenu" : [[10,20,30,45,100,-1],[10,20,30,45,100,'All']],
          "iDisplayLength": 10,
          "oLanguage": {
              "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
              "sZeroRecords": "No se encontraron registros",
              "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
              "sInfoEmpty": "Mostrando 0 de 0 registros",
              "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
              "sSearch":        "Buscar:",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":    "Último",
                "sNext":    "Siguiente",
                "sPrevious": "Anterior"
            }              
          }          
        });
      });
    </script>

<?php if(isset($is_admin)){ ?>
<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gasto Común'
        },
        subtitle: {
            text: 'Fuente: tugastocomun.cl'
        },
        xAxis: {
            categories: ['<?php echo $listado_meses; ?>'],
            crosshair: false
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Valor en Pesos ($)'
            },
            labels: { /** muestra numero completo ***/
              formatter: function () {
                  return number_format(this.value);
              }
            }   
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: false,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Deuda',
            data: [<?php echo $listado_deuda_prop; ?>],
            color: 'red'

        }, {
            name: 'Abonos',
            data: [<?php echo $listado_abonos; ?>],
            color: 'green'

        }]
    });
});
</script>


<script>

function number_format(number, decimals, dec_point, thousands_sep) {
  //  discuss at: http://phpjs.org/functions/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56);
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ');
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '');
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.');
  //   returns 4: '67,00'
  //   example 5: number_format(1000);
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2);
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1);
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.');
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0);
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2);
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4);
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3);
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ');
  //  returns 13: '100 050.00'
  //  example 14: number_format(1e-8, 8, '.', '');
  //  returns 14: '0.00000001'

  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

</script>

<?php } ?>