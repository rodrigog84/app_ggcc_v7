        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h4><?php echo $datos_dashboard['numcomunidades'];?></h4>
                  <p><b>Comunidades</b></p>
                </div>
                <div class="icon">
                  <i class="fa fa-building-o"></i>
                </div>
                <a href="<?php echo base_url();?>admins/admin_comunidades" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h4><?php echo $datos_dashboard['numpropiedades'];?></h4>
                  <p>Propiedades</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-home"></i>
                </div>
                <a href="<?php echo base_url();?>admins/admin_propiedad" class="small-box-footer">M&aacute;s Informaci&oacute;n <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->


          </div><!-- /.row -->
          <div class="row">
            <div class="col-md-12">
              <!-- Bar chart -->
              <div class="box box-primary">
                <div class="box-header">
                  <i class="fa fa-bar-chart-o"></i>
                  <h3 class="box-title">Comunidades con Publicaciones de GGCC &Uacute;ltimos 12 Per&iacute;odos.  </h3>
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
            text: 'Publicaciones Gasto Común'
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
                text: 'Cantidad de Comunidades'
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
            name: 'Numero de Comunidades',
            data: [<?php echo $listado_cantidad; ?>],
            color: 'red'

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