
        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h4><?php echo $datos_dashboard['numpropiedades'];?></h4>
                  <p><b>Propiedades Condominio</b></p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-home"></i>
                </div>
                <a href="<?php echo base_url();?>admins/admin_propiedades" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <!--h4>20<sup style="font-size: 10px">%</sup></h4-->
                  <h4>$<?php echo number_format($datos_dashboard['deuda_sin_cancelar'],0,".",".");?>&nbsp;<sub style="font-size: 15px">(&nbsp;<?php echo $datos_dashboard['porc_sin_cancelar'];?>%)</sub></h4>
                  <p>Gasto Com&uacute;n sin Cancelar</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?php echo base_url();?>payments/abonar_ggcc" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h4>$<?php echo number_format($datos_dashboard['deudatotal'],0,".",".");?></h4>
                  <p>Deuda Total Condominio</p>
                </div>
                <div class="icon">
                  <i class="ion ion-arrow-graph-down-right"></i>
                </div>
                <a href="<?php echo base_url();?>reports/cuentas_impagas" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-light-blue">
                <div class="inner">
                  <h4>$<?php echo number_format($datos_dashboard['fondo_reserva'],0,".",".");?></h4>
                  <p>Fondo de Reserva Cobrado</p>
                </div>
                <div class="icon">
                  <i class="ion ion-locked"></i>
                </div>
                <a href="<?php echo base_url();?>reports/fondo_reserva" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>


            </div><!-- ./col -->
   
               
          </div><!-- /.row -->


          <div class="row">
            <div class="col-lg-3 col-xs-6 ">
              

              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h4>$<?php echo number_format($datos_dashboard['saldo_contable'],0,".",".");?></h4>
                  <p>Saldo Contable Banco</p>
                </div>
                <div class="icon">
                  <i class="ion ion-arrow-graph-up-right"></i>
                </div>
                <a href="<?php echo base_url();?>reports/flujo_caja" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>              

            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h4>$<?php echo number_format($datos_dashboard['saldo_disponible'],0,".",".");?></h4>
                  <p>Saldo Contable Disponible</p>
                </div>
                <div class="icon">
                  <i class="ion ion-cash"></i>
                </div>
                <a href="<?php echo base_url();?>reports/flujo_caja" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          <div class="col-lg-2  ">
                    <a href="http://tugastocomun.cl/home/wp-content/uploads/2015/06/Manual-Usuario-TuGastoComun.pdf" target="_blank" class="btn btn-info">
                      <i class="fa fa-file-pdf-o"></i> Manual de Uso
                    </a>
                    <br> <br> 
                    <?php //if($this->session->userdata('comunidadid') == 17 || $this->session->userdata('comunidadid') == 19 || $this->session->userdata('comunidadid') == 25 || $this->session->userdata('comunidadid') == 71){ ?>
                    <?php //if($this->session->userdata('user_id') == 1){ ?>
                    <a href="<?php echo base_url();?>payments/pay_webpay"  class="btn bg-purple">
                      <i class="fa fa-money"></i> Pago Transbank
                    </a>&nbsp;<br><br>
                    <img src="<?php echo base_url();?>img/webpay_una_fila.png" height="25" ><br><br>
                    <?php //} ?>
              </div>            
            <?php if($num_comunidades > 1){ ?>
              
              <div class="col-lg-2  pull-right">
                    <a href="<?php echo base_url();?>main/destroy_data_session" class="btn btn-app">
                      <i class="fa fa-repeat"></i> Cambiar Comunidad
                    </a>
              </div>
            <?php } ?>

          </div><!-- /.row -->

          <div class="row">
            <div class="col-md-7">
              <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Ranking Morosos</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                        <tr>
                          <th>Propiedad</th>
                          <th>Responsable</th>
                          <th>Monto Adeudado</th>
                          <th>Num. Cuotas Impagas</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($datos_dashboard['ranking_morosos']) > 0){ ?>
                          <?php foreach ($datos_dashboard['ranking_morosos'] as $moroso) { ?>
                          <tr>
                            <td><a href="<?php echo base_url();?>payments/ver_ggcc_adm/<?php echo $moroso->id;?>"><?php echo $moroso->numero;?></a></td>
                            <td><?php echo $moroso->responsable;?></td>
                            <td><span class="label label-danger text-right">$&nbsp;<?php echo number_format($moroso->saldo,0,".",".");?></span></td>
                            <td><center><?php echo $moroso->cuentas_impagas;?></center></td>
                          </tr>
                          <?php } ?>
                        <?php }else{ ?>
                          <tr >
                            <td colspan="4"><center>No existe Informaci&oacute;n</center></td>

                          </tr>

                        <?php } ?>
                      </tbody>
                    </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                  <a href="<?php echo base_url();?>payments/abonar_ggcc" class="btn btn-sm btn-default btn-flat pull-right">Ver todas las deudas</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-5">
              <!-- Bar chart -->
              <div class="box box-primary">
                <div class="box-header">
                  <i class="fa fa-bar-chart-o"></i>
                  <h3 class="box-title">Medios de Pago</h3>
                </div>
                <div class="box-body">
                  <div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div><!-- /.box-body-->
              </div><!-- /.box -->
            </div><!-- /.col -->  
          </div><!-- /.row -->


          <div class="row">
            <div class="col-md-12">
              <!-- Bar chart -->
              <div class="box box-primary">
                <div class="box-header">
                  <i class="fa fa-bar-chart-o"></i>
                  <h3 class="box-title">Gasto Com&uacute;n &Uacute;ltimos 12 Per&iacute;odos</h3>
                </div>
                <div class="box-body">
                  <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div><!-- /.box-body-->
              </div><!-- /.box -->
            </div><!-- /.col -->          
          </div>


        </section><!-- /.content -->


<script>

$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Consolidado'
        },
        subtitle: {
            text: 'Fuente: tugastocomun.cl'
        },   
        credits: {
      enabled: false
  },     
        xAxis: {
            categories: ['<?php echo $listado_meses; ?>']
        },
        yAxis: {
            min: 0,
            ceiling: Number,
            title: {
                text: 'Valor en Pesos ($)'
            },
            stackLabels: {
                enabled: false,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            },
          labels: { /** muestra numero completo ***/
              formatter: function () {
                  return number_format(this.value);
              }
          }            
        },
       /* legend: {
            align: 'right',
            x: -10,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: true
        },*/
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': $ ' + number_format(this.y) + '<br/>' +
                    'Total: $ ' + number_format(this.point.stackTotal,0,'.','.');
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: [{
            name: 'Gastos Individuales',
            data: [<?php echo $listado_individual; ?>],
            color: 'yellow'
        },
        {
            name: 'Fondo de Reserva',
            data: [<?php echo $listado_reserva; ?>],
            color: 'green'
        },        
        {
            name: 'Deuda Condominio',
            data: [<?php echo $listado_deuda; ?>],
            color: 'red'
        }]
    });


  $('#container2').highcharts({
         chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Medios de Pago'
    },
    subtitle: {
            text: 'Fuente: tugastocomun.cl'
        },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    credits: {
      enabled: false
  },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Tasa',
        colorByPoint: true,
        data: [
        <?php foreach ($datos_dashboard['medios_pago'] as $medio) { ?>
          {
            name: "<?php echo $medio->nombre;?>",
            y: <?php echo $medio->monto; ?>
          },
        <?php } ?>
]
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