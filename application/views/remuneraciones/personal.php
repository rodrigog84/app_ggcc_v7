<!-- Main content -->
<section class="content">
    <?php if (isset($message)) : ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa <?php echo $icon; ?>"></i> Alerta!</h4>
                    <?php echo $message; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">

                <!-- definicion de pestañas ---->
                <ul class="nav nav-tabs">
                    <li class="<?php echo $mantencion_personal; ?>"><a href="#personal" data-toggle="tab">Mantenci&oacute;n de Personal</a></li>
                    <li class="<?php echo $leyes_sociales; ?>"><a href="#leyes_sociales" data-toggle="tab">Previsi&oacute;n Afp</a></li>
                    <li class="<?php echo $apv; ?>"><a href="#apv" data-toggle="tab">A.P.V.</a></li>
                    <li class="<?php echo $salud; ?>"><a href="#cotizacion_salud" data-toggle="tab">Cotizaci&oacute;n de Salud</a></li>
                    <li class="<?php echo $otros; ?>"><a href="#otros" data-toggle="tab">Otros</a></li>
                </ul>


                <div class="tab-content">
                    <!-- espacio de contenido -->

                    <div class="tab-pane <?php echo $mantencion_personal; ?>" id="personal">
                        <section id="new">
                            <h3 class="page-header">Listado de Trabajadores&nbsp;&nbsp;<a href="<?php echo base_url(); ?>remuneraciones/add_trabajador" data-toggle="tooltip" title="Agregar Trabajador"><span class="glyphicon glyphicon-plus-sign text-primary"></span></a>
                                &nbsp;&nbsp;&nbsp;
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default"><?php echo $title_button; ?></button>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo base_url(); ?>remuneraciones/personal/" />Mostrar Todos</a></li>
                                        <li><a href="<?php echo base_url(); ?>remuneraciones/personal/activos" />Mostrar Activos</a></li>
                                        <li><a href="<?php echo base_url(); ?>remuneraciones/personal/inactivos" />Mostrar Inactivos</a></li>
                                    </ul>
                                </div>

                            </h3>

                            <table id="listado" class="table table-bordered table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Trabajador</th>
                                        <th>Rut</th>
                                        <th>Direcci&oacute;n</th>
                                        <th>Estado</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($personal) > 0) { ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($personal as $trabajador) { ?>

                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno; ?></td>
                                                <td><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut, 0, ".", ".") . "-" . $trabajador->dv; ?></td>
                                                <td><?php echo $trabajador->direccion; ?></td>
                                                <td><?php echo $trabajador->active == 1 ? "Activo" : "Inactivo"; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>remuneraciones/add_trabajador/<?php echo $trabajador->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    <div class="tab-pane <?php echo $leyes_sociales; ?>" id="leyes_sociales">

                        <form id="formprevafp" action="<?php echo base_url(); ?>remuneraciones/submit_personal_afp" method="post" role="form" enctype="multipart/form-data">
                            <section id="new">
                                <h3 class="page-header">Listado de Trabajadores</h3>
                                <table class="table table-bordered table-striped dt-responsive">
                                    <thead>
                                        <tr>
                                            <th rowspan="2"><small>#</small></th>
                                            <th rowspan="2"><small>Rut</small></th>
                                            <th rowspan="2"><small>Nombre Trabajador</small></th>
                                            <th colspan="3"><small>AFP</small></th>
                                            <th colspan="2"><small>Ahorro Voluntario</small></th>
                                        </tr>
                                        <tr>
                                            <th><small>Nombre</small></th>
                                            <th><small>% Obligatorio&nbsp;&nbsp;&nbsp;</small></th>
                                            <th><small>% Adicional</small></th>
                                            <th><small>Tipo Cotizaci&oacute;n</small></th>
                                            <th><small>Valor</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($personal) > 0) { ?>
                                            <?php $i = 1; ?>
                                            <?php foreach ($personal as $trabajador) { ?>
                                                <?php if ($trabajador->active == 1) { ?>

                                                    <tr>
                                                        <td><small><?php echo $i; ?></small></td>
                                                        <td><small><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut, 0, ".", ".") . "-" . $trabajador->dv; ?></small></td>
                                                        <td><small><?php echo $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno; ?></small></td>
                                                        <td class="form-group">
                                                            <?php $exregimen_afp = ""; ?>
                                                            <?php $porc_afp = 0; ?>

                                                            <select name="afp_<?php echo $trabajador->id; ?>" id="afp_<?php echo $trabajador->id; ?>" class="form-control input-sm afp_list">
                                                                <option value="">Seleccione AFP</option>
                                                                <?php foreach ($afps as $afp) { ?>
                                                                    <?php if ($afp->exregimen != $exregimen_afp) {
                                                                        if ($exregimen_afp != '') {
                                                                            echo "</optgroup>";
                                                                        }

                                                                        $tipo_sistema =  $afp->exregimen == 0 ? "Sistema Actual" : "Antiguo Sistema de Pensiones";
                                                                        if ($afp->exregimen == 0) {
                                                                            $tipo_sistema = "Sistema Actual";
                                                                        } else if ($afp->exregimen == 1) {
                                                                            $tipo_sistema = "Antiguo Sistema de Pensiones";
                                                                        } else if ($afp->exregimen == 2) {
                                                                            $tipo_sistema = "Sin Cotizaci&oacute;n";
                                                                        }
                                                                        echo "<optgroup label='" . $tipo_sistema . "'>";
                                                                        $exregimen_afp = $afp->exregimen;
                                                                    } ?>
                                                                    <?php $afpselected = $afp->id == $trabajador->idafp ? "selected" : ""; ?>
                                                                    <?php $porc_afp = $afp->id == $trabajador->idafp ? $afp->porc : $porc_afp; ?>
                                                                    <option value="<?php echo $afp->id; ?>" <?php echo $afpselected; ?>><?php echo $afp->nombre; ?></option>
                                                                <?php }
                                                                if ($exregimen_afp != '') {
                                                                    echo "</optgroup>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-right"><b><span id="cotobligatoria_<?php echo $trabajador->id; ?>" class="text-right input-sm"><?php echo $porc_afp; ?> %</span></b></td>
                                                        <td class="form-group">
                                                            <input type="text" name="cotadic_<?php echo $trabajador->id; ?>" id="cotadic_<?php echo $trabajador->id; ?>" class="form-control input-sm cot_adic" value="<?php echo $trabajador->adicafp; ?>" />
                                                        </td>
                                                        <td class="form-group">
                                                            <select name="tipcotvol_<?php echo $trabajador->id; ?>" id="tipcotvol_<?php echo $trabajador->id; ?>" class="form-control  input-sm tipcotvol_list">
                                                                <option value="pesos" <?php echo $trabajador->tipoahorrovol == 'pesos' ? 'selected' : ''; ?>>($) Pesos</option>
                                                                <option value="porcentaje" <?php echo $trabajador->tipoahorrovol == 'porcentaje' ? 'selected' : ''; ?>>(%) Porcentaje</option>
                                                            </select>
                                                        </td>
                                                        <td class="form-group">
                                                            <?php if ($trabajador->tipoahorrovol == 'pesos' && !is_null($trabajador->ahorrovol)) {
                                                                $ahorrovol = number_format($trabajador->ahorrovol, 0, ".", ".");
                                                                $class1 = "miles";
                                                                $class2 = "cot_vol";
                                                            } else {
                                                                $ahorrovol = $trabajador->ahorrovol;
                                                                $class1 = "";
                                                                $class2 = "cot_vol";
                                                            } ?>
                                                            <input type="text" name="cotvol_<?php echo $trabajador->id; ?>" id="cotvol_<?php echo $trabajador->id; ?>" class="form-control <?php echo $class1 . " " . $class2; ?> input-sm numeros" value="<?php echo $ahorrovol; ?>" />
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="10">No existen trabajadores en la comunidad</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary <?php echo count($personal) == 0 ? 'disabled' : ''; ?>">Guardar</button>&nbsp;&nbsp;
                            </section>
                        </form>
                    </div>


                    <div class="tab-pane <?php echo $apv; ?>" id="apv">

                        <form id="formapv" action="<?php echo base_url(); ?>remuneraciones/submit_personal_apv" method="post" role="form" enctype="multipart/form-data">
                            <section id="new">
                                <h3 class="page-header">Listado de Trabajadores</h3>
                                <table class="table table-bordered table-striped dt-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%;"><small>#</small></th>
                                            <th style="width: 8%;"><small>Rut</small></th>
                                            <th style="width: 21%;"><small>Nombre Trabajador</small></th>
                                            <th style="width: 20%;"><small>Instituci&oacute;n</small></th>
                                            <th style="width: 9%;"><small>Nro. Contrato</small></th>
                                            <th style="width: 9%;"><small>Tipo Cotizaci&oacute;n</small></th>
                                            <th style="width: 10%;"><small>Valor</small></th>
                                            <th style="width: 10%;"><small>Forma Pago</small></th>
                                            <th style="width: 10%;"><small>Dep&oacute;sitos Convenidos ($)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($personal) > 0) { ?>
                                            <?php $i = 1; ?>
                                            <?php foreach ($personal as $trabajador) { ?>
                                                <?php if ($trabajador->active == 1) { ?>

                                                    <tr>
                                                        <td><small><?php echo $i; ?></small></td>
                                                        <td><small><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut, 0, ".", ".") . "-" . $trabajador->dv; ?></small></td>
                                                        <td><small><?php echo $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno; ?></small></td>
                                                        <td class="form-group">
                                                            <select name="instapv_<?php echo $trabajador->id; ?>" id="instapv_<?php echo $trabajador->id; ?>" class="form-control input-sm dapv_list">
                                                                <option value="">Seleccione Instituci&oacute;n</option>
                                                                <?php foreach ($apvs as $dapv) { ?>
                                                                    <?php $apvselected = $dapv->id == $trabajador->instapv ? "selected" : ""; ?>
                                                                    <option value="<?php echo $dapv->id; ?>" <?php echo $apvselected; ?>><?php echo $dapv->nombre; ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </td>
                                                        <td class="form-group">
                                                            <input type="text" name="nrocontratoapv_<?php echo $trabajador->id; ?>" id="nrocontratoapv_<?php echo $trabajador->id; ?>" class="form-control input-sm numeros nrocontratoapv" value="<?php echo $trabajador->nrocontratoapv; ?>" <?php echo is_null($trabajador->instapv) ? 'disabled' : ''; ?> />
                                                        </td>
                                                        <td class="form-group ">
                                                            <select name="tipoapv_<?php echo $trabajador->id; ?>" id="tipoapv_<?php echo $trabajador->id; ?>" class="form-control input-sm apv_list" <?php echo is_null($trabajador->instapv) ? 'disabled' : ''; ?>>
                                                                <option value="pesos" <?php echo $trabajador->tipocotapv == 'pesos' ? 'selected' : ''; ?>>($) Pesos</option>
                                                                <option value="uf" <?php echo $trabajador->tipocotapv == 'uf' ? 'selected' : ''; ?>>U.F.</option>
                                                                <option value="porcentaje" <?php echo $trabajador->tipocotapv == 'porcentaje' ? 'selected' : ''; ?>>(%) Porc.</option>
                                                            </select>
                                                        </td>
                                                        <td class="form-group">
                                                            <?php if ($trabajador->tipocotapv == 'pesos' && !is_null($trabajador->cotapv)) {
                                                                $cotapv = number_format($trabajador->cotapv, 0, ".", ".");
                                                                $class1 = "miles";
                                                                $class2 = "";
                                                            } else if ($trabajador->tipocotapv == 'uf' && !is_null($trabajador->cotapv)) {
                                                                $cotapv = number_format($trabajador->cotapv, 2, ",", "");
                                                                $class1 = "";
                                                                $class2 = "miles_decimales";
                                                            } else {
                                                                $cotapv = $trabajador->cotapv;
                                                                $class1 = "";
                                                                $class2 = "";
                                                            } ?>
                                                            <input type="text" name="apv_<?php echo $trabajador->id; ?>" id="apv_<?php echo $trabajador->id; ?>" class="form-control input-sm numeros cot_apv <?php echo $class1 . " " . $class2; ?>" value="<?php echo $cotapv; ?>" <?php echo is_null($trabajador->instapv) ? 'disabled' : ''; ?> />
                                                        </td>
                                                        <td class="form-group">
                                                            <select name="formapagoapv_<?php echo $trabajador->id; ?>" id="formapagoapv_<?php echo $trabajador->id; ?>" class="form-control input-sm" <?php echo is_null($trabajador->instapv) ? 'disabled' : ''; ?>>
                                                                <option value="1" <?php echo is_null($trabajador->formapagoapv) || $trabajador->formapagoapv == 1 ? 'selected' : ''; ?>>Directa</option>
                                                                <option value="2" <?php echo $trabajador->formapagoapv == 2 ? 'selected' : ''; ?>>Indirecta</option>
                                                            </select>
                                                        </td>
                                                        <td class="form-group">
                                                            <?php $depconvapv = is_null($trabajador->depconvapv) ? 0 : number_format($trabajador->depconvapv, 0, ".", "."); ?>
                                                            <input type="text" name="depconvapv_<?php echo $trabajador->id; ?>" id="depconvapv_<?php echo $trabajador->id; ?>" class="form-control input-sm miles depconvapv" value="<?php echo $depconvapv; ?>" <?php echo is_null($trabajador->instapv) ? 'disabled' : ''; ?> />
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="10">No existen trabajadores en la comunidad</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary <?php echo count($personal) == 0 ? 'disabled' : ''; ?>">Guardar</button>&nbsp;&nbsp;
                            </section>
                        </form>
                    </div>

                    <div class="tab-pane <?php echo $salud; ?>" id="cotizacion_salud">
                        <form id="formsalud" action="<?php echo base_url(); ?>remuneraciones/submit_salud" method="post" role="form" enctype="multipart/form-data">
                            <section id="new">
                                <h3 class="page-header">Listado de Trabajadores</h3>
                                <table class="table table-bordered table-striped dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Rut</th>
                                            <th>Nombre Trabajador</th>
                                            <th>Isapre/Fonasa</th>
                                            <th>Sueldo Base</th>
                                            <th>7% Imponible</th>
                                            <th>Pactado (UF)</th>
                                            <!--th ><small>Valor Plan</small></th>
                            <th ><small>Monto Descuento</small></th-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($personal) > 0) { ?>
                                            <?php $i = 1; ?>
                                            <?php foreach ($personal as $trabajador) { ?>
                                                <?php if ($trabajador->active == 1) { ?>

                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $trabajador->rut == '' ? '' : number_format($trabajador->rut, 0, ".", ".") . "-" . $trabajador->dv; ?></td>
                                                        <td><?php echo $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno; ?></td>
                                                        <td class="form-group">
                                                            <select name="isapre_<?php echo $trabajador->id; ?>" id="isapre_<?php echo $trabajador->id; ?>" class="form-control isapre_list">
                                                                <option value="">Seleccione Instituci&oacute;n</option>
                                                                <?php foreach ($isapres as $isapre) { ?>
                                                                    <?php $isapreselected = $isapre->id == $trabajador->idisapre ? "selected" : ""; ?>
                                                                    <option value="<?php echo $isapre->id; ?>" <?php echo $isapreselected; ?>><?php echo $isapre->nombre; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td>$&nbsp;<?php echo number_format($trabajador->sueldobase, 0, ".", "."); ?></td>
                                                        <td>$&nbsp;<?php echo number_format((int)$trabajador->sueldobase * 0.07, 0, ".", "."); ?></td>
                                                        <td><input type="text" name="pactado_<?php echo $trabajador->id; ?>" id="pactado_<?php echo $trabajador->id; ?>" class="form-control valor_pactado miles_decimales_isapre" value="<?php echo !is_null($trabajador->valorpactado) && $trabajador->valorpactado != 0 ? number_format($trabajador->valorpactado, 4, ",", "") : ""; ?>" <?php echo is_null($trabajador->idisapre) || $trabajador->idisapre == 1 ? "disabled" : ""; ?> /></td>
                                                        <!--td><b><span id="valorplan_<?php echo $trabajador->id; ?>"  class="text-right input-sm" >$&nbsp;0</span></b></td>
                              <td><b><span id="montodescuento_<?php echo $trabajador->id; ?>"  class="text-right input-sm" >$&nbsp;0</span></b></td-->
                                                    </tr>
                                                    <?php $i++; ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="7">No existen trabajadores en la comunidad</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary <?php echo count($personal) == 0 ? 'disabled' : ''; ?>">Guardar</button>&nbsp;&nbsp;
                            </section>
                        </form>
                    </div>



                    <div class="tab-pane <?php echo $otros; ?>" id="otros">
                        <form id="formotros" action="<?php echo base_url(); ?>remuneraciones/submit_otros" method="post" role="form" enctype="multipart/form-data">
                            <section id="new">
                                <div class="box-body">
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label for="caja">Caja de Compensaci&oacute;n Comunidad</label>
                                                <select name="caja" id="caja" class="form-control">
                                                    <option value="">Sin Caja de Compensaci&oacute;n</option>
                                                    <?php foreach ($cajas as $caja) { ?>
                                                        <?php $cajaselected = $caja->id == $comunidad->idcaja ? "selected" : ""; ?>
                                                        <option value="<?php echo $caja->id; ?>" <?php echo $cajaselected; ?>><?php echo $caja->nombre; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label for="mutual">Mutual de Seguridad</label>
                                                <select name="mutual" id="mutual" class="form-control">
                                                    <option value="">Seleccione Mutual de Seguridad</option>
                                                    <?php foreach ($mutuales as $mutual) { ?>
                                                        <?php $mutualselected = $mutual->id == $comunidad->idmutual ? "selected" : ""; ?>
                                                        <option value="<?php echo $mutual->id; ?>" <?php echo $mutualselected; ?>><?php echo $mutual->nombre; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-group">
                                                <label for="porcmutual">Porcentaje</label>
                                                <input type="text" class="form-control" name="porcmutual" id="porcmutual" placeholder="Ingrese Porcentaje" value="<?php echo is_null($comunidad->idmutual)  ? '' : $comunidad->porcmutual; ?>" <?php echo is_null($comunidad->idmutual)  ? 'disabled' : ''; ?>>

                                                <!--input type="text" class="form-control" name="porcmutual" id="porcmutual" placeholder="Ingrese Porcentaje" value="<?php echo is_null($comunidad->idmutual) || $comunidad->idmutual == 1 ? '' : $comunidad->porcmutual; ?>"  <?php echo is_null($comunidad->idmutual) || $comunidad->idmutual == 1 ? 'disabled' : ''; ?> -->
                                            </div>
                                        </div>


                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Guardar</button>&nbsp;&nbsp;
                                </div>
                            </section>
                        </form>
                    </div>



                </div>


            </div>
        </div>
    </div>
</section><!-- /.content -->

<script>
    $(function() {
        $('#listado').dataTable({
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "aLengthMenu": [
                [10, 20, 30, 45, 100, -1],
                [10, 20, 30, 45, 100, 'All']
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sLengthMenu": "_MENU_ Registros por p&aacute;gina",
                "sZeroRecords": "No se encontraron registros",
                "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });
    });



    $('#mutual').change(function() {

        if ($(this).val() == '') { // sin selección o marcó sin mutual
            $('#porcmutual').val('');
            $('#porcmutual').attr('disabled', true);
        } else {
            $('#porcmutual').attr('disabled', false);
            $('#porcmutual').val(0);
        }

    });

    $('.afp_list').change(function() {

        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("_");
        var idtrabajador = array_elem[1];

        if ($(this).val() != '') {


            $.get("<?php echo base_url(); ?>remuneraciones/get_cot_obligatoria/" + $(this).val(), function(data) {
                // Limpiamos el select
                var_json = $.parseJSON(data);
                $('#cotobligatoria_' + idtrabajador).html(var_json.porc + " %")
            });

        } else {
            $('#cotobligatoria_' + idtrabajador).html("0 %")
        }
    });

    $('.dapv_list').change(function() {

        var apv_select = $(this).val();
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("_");
        var idtrabajador = array_elem[1];

        if (apv_select != '') { //seleccionó institución

            $('#nrocontratoapv_' + idtrabajador).attr('disabled', false);
            $('#tipoapv_' + idtrabajador).attr('disabled', false);
            $('#apv_' + idtrabajador).attr('disabled', false);
            $('#formapagoapv_' + idtrabajador).attr('disabled', false);
            $('#depconvapv_' + idtrabajador).attr('disabled', false);



        } else {
            $('#nrocontratoapv_' + idtrabajador).val(0);
            $('#tipoapv_' + idtrabajador).val('pesos');
            $('#apv_' + idtrabajador).addClass("miles");
            $('#apv_' + idtrabajador).removeClass("miles_decimales");
            $('#apv_' + idtrabajador).mask('000.000.000.000.000', {
                reverse: true
            }); // agrega mascara
            $('#apv_' + idtrabajador).val(0);
            $('#formapagoapv_' + idtrabajador).val(1);
            $('#depconvapv_' + idtrabajador).val(0);

            $('#nrocontratoapv_' + idtrabajador).attr('disabled', true);
            $('#tipoapv_' + idtrabajador).attr('disabled', true);
            $('#apv_' + idtrabajador).attr('disabled', true);
            $('#formapagoapv_' + idtrabajador).attr('disabled', true);
            $('#depconvapv_' + idtrabajador).attr('disabled', true);
        }


    });

    $('.apv_list').change(function() {
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("_");
        var idtrabajador = array_elem[1];
        $('#apv_' + idtrabajador).val("");

        if ($(this).val() == 'porcentaje') {
            $('#apv_' + idtrabajador).removeClass("miles");
            $('#apv_' + idtrabajador).removeClass("miles_decimales");
            //$('#cotvol_'+idtrabajador).addClass("cot_vol");
            $('#apv_' + idtrabajador).unmask(); //quita mascara

            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', true, 'between'); //agregar validacion
            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', true, 'numeric'); //agregar validacion
        } else if ($(this).val() == 'uf') {
            $('#apv_' + idtrabajador).removeClass("miles");
            $('#apv_' + idtrabajador).addClass("miles_decimales");
            $('#apv_' + idtrabajador).mask('#.##0,00', {
                reverse: true
            })

        } else {
            $('#apv_' + idtrabajador).addClass("miles");
            $('#apv_' + idtrabajador).removeClass("miles_decimales");
            //$('#cotvol_'+idtrabajador).removeClass("cot_vol");
            $('#apv_' + idtrabajador).mask('000.000.000.000.000', {
                reverse: true
            }); // agrega mascara
            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', false, 'between'); //quitar validacion
            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', false, 'numeric'); //quitar validacion
        }


    });



    $('.tipcotvol_list').change(function() {
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("_");
        var idtrabajador = array_elem[1];
        $('#cotvol_' + idtrabajador).val("");

        if ($(this).val() == 'porcentaje') {
            $('#cotvol_' + idtrabajador).removeClass("miles");
            //$('#cotvol_'+idtrabajador).addClass("cot_vol");
            $('#cotvol_' + idtrabajador).unmask(); //quita mascara

            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', true, 'between'); //agregar validacion
            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', true, 'numeric'); //agregar validacion
        } else {
            $('#cotvol_' + idtrabajador).addClass("miles");
            //$('#cotvol_'+idtrabajador).removeClass("cot_vol");
            $('#cotvol_' + idtrabajador).mask('000.000.000.000.000', {
                reverse: true
            }); // agrega mascara
            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', false, 'between'); //quitar validacion
            //$('#formprevafp').formValidation('enableFieldValidators', 'cotvol', false, 'numeric'); //quitar validacion
        }

        //$('#formprevafp').formValidation('updateStatus', 'cotvol', 'NOT_VALIDATED').formValidation('revalidateField', 'cotvol')
        //formValidation('revalidateField', 'cotvol');

    });



    $('.isapre_list').change(function() {
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("_");
        var idtrabajador = array_elem[1];

        if ($(this).val() == 1 || $(this).val() == '') { // si es fonasa o sin isapre, no se ingresa monto pactado
            $('#pactado_' + idtrabajador).attr('disabled', true);
            $('#pactado_' + idtrabajador).val('');
        } else {
            $('#pactado_' + idtrabajador).attr('disabled', false);
        }

    });

    /*$(".valor_pactado").on('input',function(event){
        var id_elem = $(this).attr('id');
        var array_elem = id_elem.split("_");
        var idtrabajador = array_elem[1];
        recalcula_montos(($(this).cleanVal()/100),idtrabajador);
    });


    function recalcula_montos(valor,idtrabajador){

      console.log(valor+" - "+idtrabajador);

    }*/
</script>

<script>
    $(document).ready(function() {
        $('#formprevafp').formValidation({
                framework: 'bootstrap',
                excluded: ':disabled',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    afp_list: {
                        selector: '.afp_list',
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Selecci&oacute;n de Afp es requerida'
                            }
                        }
                    },
                    cotadic: {
                        // The children's full name are inputs with class .childFullName
                        selector: '.cot_adic',
                        // The field is placed inside .col-xs-6 div instead of .form-group
                        row: '.form-group',
                        validators: {
                            between: {
                                min: 0,
                                max: 100,
                                message: 'Cotizaci&oacute;n adicional debe estar entre 0 y 100'
                            },
                            numeric: {
                                separator: '.',
                                message: 'Cotizaci&oacute;n adicional s&oacute;lo puede contener n&uacute;meros'
                            },

                        }
                    },

                    cotvol: {
                        // The children's full name are inputs with class .childFullName
                        selector: '.cot_vol',
                        // The field is placed inside .col-xs-6 div instead of .form-group
                        row: '.form-group',
                        validators: {
                            numeric: {
                                //enabled: false,
                                separator: '.',
                                message: 'Ahorro voluntario s&oacute;lo puede contener n&uacute;meros'
                            },
                            callback: {
                                message: 'Ahorro voluntario debe estar entre 0 y 100',
                                callback: function(value, validator, $field) {
                                    var id_text = $field.attr('id');
                                    var array_field = id_text.split("_");
                                    idtrabajador = array_field[1];
                                    if ($('#tipcotvol_' + idtrabajador).val() == 'porcentaje') {
                                        cotvol = parseFloat(value);
                                        cotvol = parseInt(cotvol);
                                        if (cotvol > 100) {
                                            return {
                                                valid: false,
                                                message: 'Ahorro voluntario debe estar entre 0 y 100'
                                            }

                                        } else {
                                            return true;
                                        }
                                    } else {
                                        return true;
                                    }
                                }
                            }

                        }
                    },
                }
            })
            .find('.miles').mask('000.000.000.000.000', {
                reverse: true
            });


        $('#formapv').formValidation({
                framework: 'bootstrap',
                excluded: ':disabled',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    nrocontratoapv: {
                        selector: '.nrocontratoapv',
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nro. de contrato es requerido'
                            }
                        }
                    },
                    depconvapv: {
                        selector: '.depconvapv',
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Monto Dep&oacute;sitos Convenidos es requerido'
                            }
                        }
                    },
                    cot_apv: {
                        // The children's full name are inputs with class .childFullName
                        selector: '.cot_apv',
                        // The field is placed inside .col-xs-6 div instead of .form-group
                        row: '.form-group',
                        validators: {
                            /*numeric: {
                                //enabled: false,
                                separator: '.',
                                message: 'Ahorro voluntario s&oacute;lo puede contener n&uacute;meros'
                            },  */
                            callback: {
                                message: 'Ahorro APV debe estar entre 0 y 100',
                                callback: function(value, validator, $field) {
                                    var id_text = $field.attr('id');
                                    var array_field = id_text.split("_");
                                    idtrabajador = array_field[1];
                                    if ($('#tipoapv_' + idtrabajador).val() == 'porcentaje') {
                                        var array_value = value.split(".");
                                        if (array_value.length > 2) {
                                            return {
                                                valid: false,
                                                message: 'Ahorro APV s&oacute;lo puede contener n&uacute;meros'
                                            }

                                        } else {
                                            cot_apv = parseFloat(value);
                                            cot_apv = parseInt(cot_apv);
                                            if (cot_apv > 100) {
                                                return {
                                                    valid: false,
                                                    message: 'Ahorro APV debe estar entre 0 y 100'
                                                }

                                            } else {
                                                return true;
                                            }

                                        }



                                    } else {
                                        return true;
                                    }
                                }
                            }

                        }
                    },
                }
            })
            .find('.miles').mask('000.000.000.000.000', {
                reverse: true
            });


        $('#formsalud').formValidation({
            framework: 'bootstrap',
            excluded: ':disabled',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                isapre_list: {
                    selector: '.isapre_list',
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Selecci&oacute;n de Instituci&oacute;n de Salud es requerida'
                        }
                    }
                }
            }
        });


        $('#formotros').formValidation({
            framework: 'bootstrap',
            excluded: ':disabled',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                porcmutual: {
                    // The field is placed inside .col-xs-6 div instead of .form-group
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Porcentaje Mutual es requerido'
                        },
                        between: {
                            min: 0,
                            max: 100,
                            message: 'Porcentaje Mutual debe estar entre 0 y 100'
                        },
                        numeric: {
                            separator: '.',
                            message: 'Porcentaje Mutual s&oacute;lo puede contener n&uacute;meros'
                        },

                    }
                },
            }
        })
    });

    $(document).ready(function() {
        $('.miles_decimales').mask('#.##0,00', {
            reverse: true
        });

        $('.miles_decimales_isapre').mask('#.####0,0000', {
            reverse: true
        });

    });

    $('.numeros').keypress(function(event) {
        if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 46) {
            event.preventDefault();
        }
    })
</script>
