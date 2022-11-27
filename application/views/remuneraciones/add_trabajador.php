<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form id="basicBootstrapForm" action="<?php echo base_url(); ?>remuneraciones/submit_trabajador" id="basicBootstrapForm" method="post">
                <div class="nav-tabs-custom">
                    <!-- definicion de pestañas -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#datospersonales" data-toggle="tab">Datos Personales&nbsp;&nbsp;<i class="fa"></i></a></li>
                        <li><a href="#domiciliocontacto" data-toggle="tab">Domicilio/Contacto&nbsp;&nbsp;<i class="fa"></i></a></li>
                        <li><a href="#datosempleo" data-toggle="tab">Datos Empleo&nbsp;&nbsp;<i class="fa"></i></a></li>
                        <li><a href="#datosremuneracion" data-toggle="tab">Datos Remuneraci&oacute;n&nbsp;&nbsp;<i class="fa"></i></a></li>
                        <li><a href="#finiquito" data-toggle="tab">Finiquito&nbsp;&nbsp;<i class="fa"></i></a></li>
                        <li><a href="#bonos" data-toggle="tab">Bonos&nbsp;&nbsp;<i class="fa"></i></a></li>
                        <li><a id='linkregistro' href="#registrousuario" data-toggle="tab"><?php $usuario = $datos_form['iduser'] != 0 ? 'Editar' : 'Registrar'; ?><?php echo $usuario; ?> Usuario&nbsp;&nbsp;<i class="fa"></i></a></li>
                    </ul>

                    <!-- form start -->

                    <div class="tab-content">
                        <!-- espacio de contenido -->
                        <div class="tab-pane active" id="datospersonales">
                            <section id="new">

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="rut">Rut Trabajador</label>
                                            <input type="text" class="form-control" name="rut" id="rut" placeholder="Ingrese Rut" value="<?php echo $datos_form['rut']; ?>" <?php echo $datos_form['idtrabajador'] == 0 ? "" : "readonly" ?> />
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="nombre">Nombre Trabajador</label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese Nombre" value="<?php echo $datos_form['nombre']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="apaterno">Apellido Paterno</label>
                                            <input type="text" class="form-control" name="apaterno" id="apaterno" placeholder="Apellido Paterno" value="<?php echo $datos_form['apaterno']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="amaterno">Apellido Materno</label>
                                            <input type="text" id="amaterno" name="amaterno" class="form-control" placeholder="Apellido Materno" value="<?php echo $datos_form['amaterno']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="fechanacimiento">Fecha de Nacimiento</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                                <input type="text" class="form-control mask_date" name="fechanacimiento" value="<?php echo $datos_form['fecnacimiento']; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask />
                                            </div><!-- /.input group -->


                                            <!--label for="fechanacimiento">Fecha de Nacimiento</label>
                                <div class="input-group date mask_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                  <input class="form-control" size="16" type="text" name="fechanacimiento"  value="<?php echo $datos_form['fecnacimiento']; ?>" placeholder="dd/mm/aaaa">

                                </div-->
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="sexo">Sexo</label>
                                            <select name="sexo" id="sexo" class="form-control">
                                                <option value="">Seleccione Sexo</option>
                                                <option value="M" <?php echo $datos_form['sexo'] == 'M' ? 'selected' : ''; ?>>Masculino</option>
                                                <option value="F" <?php echo $datos_form['sexo'] == 'F' ? 'selected' : ''; ?>>Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="ecivil">Estado Civil</label>
                                            <select name="ecivil" id="ecivil" class="form-control">
                                                <option value="">Seleccione Estado Civil</option>
                                                <?php foreach ($estados_civiles as $estado_civil) { ?>
                                                    <?php $ecivilselected = $estado_civil->id == $datos_form['idecivil'] ? "selected" : ""; ?>
                                                    <option value="<?php echo $estado_civil->id; ?>" <?php echo $ecivilselected; ?>><?php echo $estado_civil->nombre; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="nacionalidad">Nacionalidad</label>
                                            <select name="nacionalidad" id="nacionalidad" class="form-control">
                                                <option value="">Seleccione Nacionalidad</option>
                                                <option value="C" <?php echo $datos_form['nacionalidad'] == 'C' ? 'selected' : ''; ?>>Chileno</option>
                                                <option value="E" <?php echo $datos_form['nacionalidad'] == 'E' ? 'selected' : ''; ?>>Extranjero</option>
                                            </select>
                                        </div>
                                    </div>




                                </div>
                                <div class="row">
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="activo">Activo</label> <br>
                                            <input type="checkbox" name="activo" id="activo" class="minimal" <?php echo $datos_form['active'] == 1 ? "checked" : ""; ?> />
                                        </div>
                                    </div>




                                </div>
                            </section>
                        </div><!-- div personal-->

                        <div class="tab-pane " id="domiciliocontacto">
                            <section id="new">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="direccion">Direcci&oacute;n</label>
                                            <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese Direcci&oacute;n" value="<?php echo $datos_form['direccion']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="region">Region</label>
                                            <select name="region" id="region" class="form-control">
                                                <option value="">Seleccione Regi&oacute;n</option>
                                                <?php foreach ($regiones as $region) { ?>
                                                    <?php $regionselected = $region->idregion == $datos_form['idregion'] ? "selected" : ""; ?>
                                                    <option value="<?php echo $region->idregion; ?>" <?php echo $regionselected; ?>><?php echo $region->nombre; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class='row'>

                                    <div class='col-md-6'>
                                        <div class="form-group">

                                            <label for="comuna">Comuna</label>
                                            <select name="comuna" id="comuna" class="form-control">
                                                <option value="">Seleccione Comuna</option>
                                            </select>
                                            <input type="hidden" id="idcomuna" value="<?php echo $datos_form['idcomuna']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="fono">Fono</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
                                                <input type="text" class="form-control" name="fono" id="fono" placeholder="Ingrese Fono" value="<?php echo $datos_form['fono']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="emailcontacto">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">@</span>
                                                <input type="text" class="form-control" name="emailcontacto" id="emailcontacto" placeholder="Ingrese Email" value="<?php echo $datos_form['email']; ?>">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>
                        </div>


                        <div class="tab-pane " id="datosempleo">
                            <section id="new">
                                <div class="row">
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="fechaingreso">Fecha Ingreso</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                                <input type="text" class="form-control mask_date" name="fechaingreso" id="fechaingreso" value="<?php echo $datos_form['fecingreso']; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask />
                                            </div>

                                            <!--div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                  <input class="form-control" size="16" type="text" readonly name="fechaingreso"  value="<?php echo $datos_form['fecingreso']; ?>" placeholder="dd/mm/aaaa">

                                </div-->
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="segcesantia">Afiliado Seguro Cesant&iacute;a</label> <br>
                                            <input type="checkbox" name="segcesantia" id="segcesantia" class="minimal" <?php echo $datos_form['segcesantia'] == 1 ? "checked" : ""; ?> />

                                        </div>
                                    </div>
                                </div>
                                <div class='row'>

                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="fechaafc">Fecha AFC</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                                <input type="text" class="form-control mask_date" name="fechaafc" id="fechaafc" <?php echo $datos_form['segcesantia'] == 1 ? "" : "disabled"; ?> value="<?php echo $datos_form['segcesantia'] == 1 ? $datos_form['fecafc'] : ""; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask />

                                            </div>
                                            <p class="help-block">(*) Fecha AFC debe ser igual o mayor a fecha ingreso.</p>

                                            <!--div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                  <input class="form-control" size="16" type="text" readonly name="fechaingreso"  value="<?php echo $datos_form['fecingreso']; ?>" placeholder="dd/mm/aaaa">

                                </div-->
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="cargo">Cargo</label>
                                            <?php $label_cargo = ""; ?>
                                            <select name="cargo" id="cargo" class="form-control">
                                                <option value="">Seleccione un Cargo</option>
                                                <?php foreach ($cargos as $cargo) { ?>
                                                    <?php if ($cargo->idpadre != $label_cargo) {
                                                        if ($label_cargo != '') {
                                                            echo "</optgroup>";
                                                        }
                                                        echo "<optgroup label='" . $cargo->nombrepadre . "''>";
                                                        $label_cargo = $cargo->idpadre;
                                                    } ?>
                                                    <?php if (!($cargo->idpadre == '' && $cargo->hijos > 0)) { ?>
                                                        <?php $cargoselected = $cargo->id == $datos_form['idcargo'] ? "selected" : ""; ?>
                                                        <option value="<?php echo $cargo->id; ?>" <?php echo $cargoselected; ?>><?php echo $cargo->nombre; ?></option>
                                                    <?php } ?>
                                                <?php }
                                                if ($label_cargo != '') {
                                                    echo "</optgroup>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>

                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="fecinicvacaciones">Fecha Inicio C&aacute;lculo Vacaciones</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                                <input type="text" class="form-control mask_date" name="fecinicvacaciones" id="fecinicvacaciones" value="<?php echo $datos_form['fecinicvacaciones']; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask />

                                            </div>
                                            <p class="help-block">(*) Fecha a partir de la cual el sistema calcular&aacute; d&iacute;as de vacaciones
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="saldoinicvacaciones">Saldo Inicial D&iacute;as Vacaciones Legales</label>
                                            <input type="text" class="form-control numeros" name="saldoinicvacaciones" id="saldoinicvacaciones" placeholder="Ingrese Saldo Inicial de Vacaciones Legales" value="<?php echo $datos_form['saldoinicvacaciones']; ?>">
                                            <p class="help-block">(*) D&iacute;as de vacaciones Legales Devengadas a la fecha de inicio de c&aacute;lculo.</p>
                                        </div>

                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="saldoinicvacaciones">Saldo Inicial D&iacute;as Vacaciones Progresivas</label>
                                            <input type="text" class="form-control numeros" name="saldoinicvacprog" id="saldoinicvacprog" placeholder="Ingrese Saldo Inicial de Vacaciones Progresivas" value="<?php echo $datos_form['saldoinicvacprog']; ?>">
                                            <p class="help-block">(*) D&iacute;as de vacaciones Progresivas Devengadas a la fecha de inicio de c&aacute;lculo.</p>
                                        </div>

                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="parttime">Pensionado</label> <br>
                                            <input type="checkbox" name="pensionado" id="pensionado" class="minimal" <?php echo $datos_form['pensionado'] == 1 ? "checked" : ""; ?> />

                                        </div>
                                    </div>
                                </div>

                            </section>
                        </div>




                        <div class="tab-pane " id="datosremuneracion">
                            <section id="new">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="tipocontrato">Tipo Contrato</label>
                                            <select name="tipocontrato" id="tipocontrato" class="form-control">
                                                <option value="">Seleccione Tipo de Contrato</option>
                                                <option value="F" <?php echo $datos_form['tipocontrato'] == 'F' ? 'selected' : ''; ?>>Plazo Fijo</option>
                                                <option value="I" <?php echo $datos_form['tipocontrato'] == 'I' ? 'selected' : ''; ?>>Indefinido</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="parttime">Part Time</label> <br>
                                            <input type="checkbox" name="parttime" id="parttime" class="minimal" <?php echo $datos_form['parttime'] == 1 ? "checked" : ""; ?> />

                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="diastrabajo">D&iacute;as de Trabajo</label>
                                            <input type="text" class="form-control" name="diastrabajo" id="diastrabajo" placeholder="Ingrese D&iacute;as de Trabajo" value="<?php echo $datos_form['diastrabajo']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="horasdiarias">Horas Diarias</label>
                                            <input type="text" class="form-control" name="horasdiarias" id="horasdiarias" placeholder="Ingrese Horas Diarias" value="<?php echo $datos_form['horasdiarias']; ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="horassemanales">Horas Semanales</label>
                                            <input type="text" class="form-control" name="horassemanales" id="horassemanales" placeholder="Ingrese Horas Semanales" value="<?php echo $datos_form['horassemanales']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="sueldobase">Sueldo Base</label>
                                            <input type="text" class="form-control miles" name="sueldobase" id="sueldobase" placeholder="Ingrese Sueldo Base" value="<?php echo $datos_form['sueldobase']; ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="tipogratificacion">Tipo Gratificaci&oacute;n</label>
                                            <select name="tipogratificacion" id="tipogratificacion" class="form-control">
                                                <option value="">Seleccione Tipo de Gratificaci&oacute;n</option>
                                                <option value="SG" <?php echo $datos_form['tipogratificacion'] == 'SG' ? 'selected' : ''; ?>>Sin Gratificaci&oacute;n</option>
                                                <option value="TL" <?php echo $datos_form['tipogratificacion'] == 'TL' ? 'selected' : ''; ?>>Tope Legal</option>
                                                <option value="MF" <?php echo $datos_form['tipogratificacion'] == 'MF' ? 'selected' : ''; ?>>Monto Fijo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="gratificacion">Monto Gratificaci&oacute;n</label>
                                            <input type="text" class="form-control miles" name="gratificacion" id="gratificacion" placeholder="Ingrese Monto Gratificaci&oacute;n" value="<?php echo $datos_form['gratificacion'] == 0 ? '' : $datos_form['gratificacion']; ?>" <?php echo $datos_form['tipogratificacion'] == 'MF' ? '' : 'disabled'; ?>>
                                        </div>
                                    </div>
                                </div>


                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="cargassimples">Nro. Cargas Simples</label>
                                            <input type="text" class="form-control cargas_familiares" name="cargassimples" id="cargassimples" placeholder="Ingrese Nro. Cargas Simples" value="<?php echo $datos_form['cargassimples']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="cargasinvalidas">Nro. Cargas Inv&aacute;lidas</label>
                                            <input type="text" class="form-control" name="cargasinvalidas" id="cargasinvalidas" placeholder="Ingrese Nro. Cargas Inv&aacute;lidas" value="<?php echo $datos_form['cargasinvalidas']; ?>">
                                        </div>
                                    </div>
                                </div>



                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="cargasmaternales">Nro. Cargas Maternales</label>
                                            <input type="text" class="form-control cargas_familiares" name="cargasmaternales" id="cargasmaternales" placeholder="Ingrese Nro. Cargas Maternales" value="<?php echo $datos_form['cargasmaternales']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="cargasretroactivas">Nro. Cargas Retroactivas</label>
                                            <input type="text" class="form-control" name="cargasretroactivas" id="cargasretroactivas" placeholder="Ingrese Nro. Cargas Retroactivas" value="<?php echo $datos_form['cargasretroactivas']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="asigfamiliar">Monto Cargas Retroactivas</label>
                                            <input type="text" class="form-control miles" name="asigfamiliar" id="asigfamiliar" placeholder="Ingrese Monto Asignaci&oacute;n Familiar" value="<?php echo $datos_form['asigfamiliar']; ?>">
                                            <p class="help-block">(*) El monto de cargas retroactivas s&oacute;lo ser&aacute; efectivo el mes en curso.</p>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="ecivil">Tramo Asignaci&oacute;n Familiar</label>
                                            <select name="tramo_asigfamiliar" id="tramo_asigfamiliar" class="form-control" <?php echo ($datos_form['cargassimples'] + $datos_form['cargasmaternales']) > 0 ? "" : "disabled"; ?>>
                                                <option value="">Seleccione Tramo</option>
                                                <?php foreach ($tramos_asig_familiar as $tramo) { ?>
                                                    <?php $tramoselected = $tramo->id == $datos_form['idasigfamiliar'] ? "selected" : ""; ?>
                                                    <option value="<?php echo $tramo->id; ?>" <?php echo $tramoselected; ?>><?php echo $tramo->tramo; ?></option>
                                                <?php } ?>

                                            </select>
                                            <p class="help-block">(*) Dato necesario en caso de tener cargas simples y maternales.</p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane " id="finiquito">
                            <section id="new">
                                <div class='row'>
                                   
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="fechanacimiento">Fecha Finiquito</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                                <input type="text" class="form-control mask_date" name="fechafiniquito" id="fechafiniquito" value="<?php echo $datos_form['fecfiniquito']; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask <?php echo $datos_form['active'] == 1 ? "disabled" : ""; ?> />
                                            </div><!-- /.input group -->


                                            <!--label for="fechanacimiento">Fecha de Nacimiento</label>
                                <div class="input-group date mask_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" >
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                  <input class="form-control" size="16" type="text" name="fechanacimiento"  value="<?php echo $datos_form['fecnacimiento']; ?>" placeholder="dd/mm/aaaa">

                                </div-->
                                        </div>
                                    </div>


                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="ecivil">Causal Finiquito</label>
                                            <select name="causalfiniquito" id="causalfiniquito" class="form-control" <?php echo $datos_form['active'] == 1 ? "disabled" : ""; ?>>
                                                <option value="">Seleccione Causal</option>
                                                <?php foreach ($causales_finiquito as $causal_finiquito) { ?>
                                                    <?php $causalselected = $causal_finiquito->id == $datos_form['causalfiniquito'] ? "selected" : ""; ?>
                                                    <option value="<?php echo $causal_finiquito->id; ?>" <?php echo $causalselected; ?>><?php echo str_pad($causal_finiquito->articulo,20,' ',STR_PAD_LEFT) . ' | ' . $causal_finiquito->motivo; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>                                    
                                    

                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="indmesaviso">Indemnizaci&oacute;n Sustitutiva Mes de Aviso</label>
                                            <input type="text" class="form-control miles indemnizacion" name="indmesaviso" id="indmesaviso" placeholder="Ingrese Indemnizaci&oacute;n Mes de Aviso" value="<?php echo $datos_form['indmesaviso']; ?>" <?php echo $datos_form['active'] == 1 ? "disabled" : ""; ?>  <?php echo $datos_form['causalfiniquito'] == 14 || $datos_form['causalfiniquito'] == '' || $datos_form['causalfiniquito'] == 0 ? "" : "readonly"; ?>>
                                        </div>
                                    </div>


                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="indannoservicio">Indemnizaci&oacute;n A&ntilde;os de Servicio</label>
                                            <input type="text" class="form-control miles indemnizacion" name="indannoservicio" id="indannoservicio" placeholder="Ingrese Indemnizaci&oacute;n A&ntilde;os de Servicio" value="<?php echo $datos_form['indannoservicio']; ?>" <?php echo $datos_form['active'] == 1 ? "disabled" : ""; ?>  <?php echo $datos_form['causalfiniquito'] == 14 || $datos_form['causalfiniquito'] == '' || $datos_form['causalfiniquito'] == 0 || $datos_form['causalfiniquito'] == 15 ? "" : "readonly"; ?>>
                                        </div>
                                    </div>
                                   
                                </div>



                                <div class='row'>
                                     <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="indferiadolegal">Indemnizaci&oacute;n Feriado Legal</label>
                                            <input type="text" class="form-control miles indemnizacion" name="indferiadolegal" id="indferiadolegal" placeholder="Ingrese Indemnizaci&oacute;n Feriado Legal" value="<?php echo $datos_form['indferiadolegal']; ?>" <?php echo $datos_form['active'] == 1 ? "disabled" : ""; ?>>
                                        </div>
                                    </div>

                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="indvoluntaria">Indemnizaci&oacute;n Voluntaria</label>
                                            <input type="text" class="form-control miles indemnizacion" name="indvoluntaria" id="indvoluntaria" placeholder="Ingrese Indemnizaci&oacute;n Voluntaria" value="<?php echo $datos_form['indvoluntaria']; ?>" <?php echo $datos_form['active'] == 1 ? "disabled" : ""; ?>>
                                        </div>
                                    </div>


                                </div>
                                 <div class='row'>
                                   
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="indtotal">Total Indemnizaci&oacute;nes</label>
                                            <input type="text" class="form-control miles" name="indtotal" id="indtotal" value="<?php echo $datos_form['indtotal']; ?>" readOnly>
                                            <p class="help-block"><small>(*) Datos requeridos para LRE.  En caso de no ingresar ser&aacute;n informados en cero .</small></p>
                                        </div>
                                    </div>

                                </div>
                            </section>
                        </div>




                        <div class="tab-pane " id="bonos">
                            <section id="new">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="movilizacion">Valor Movilizaci&oacute;n</label>
                                            <input type="text" class="form-control miles" name="movilizacion" id="movilizacion" placeholder="Ingrese Valor Movilizaci&oacute;n" value="<?php echo $datos_form['movilizacion']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="colacion">Valor Colaci&oacute;n</label>
                                            <input type="text" class="form-control miles" name="colacion" id="colacion" placeholder="Ingrese Valor Colaci&oacute;n" value="<?php echo $datos_form['colacion']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <table id="customFields" class="table table-bordered table-striped dt-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Descripci&oacute;n Bono</th>
                                                    <th>Monto</th>
                                                    <th width="20%">Fecha</th>
                                                    <th>Proporcional</th>
                                                    <th>Imponible</th>
                                                    <th>Fijo</th>
                                                    <th><a href="javascript:void(0);" data-toggle="tooltip" title="Agregar Bono" class="add_bono"><span class="glyphicon glyphicon-plus-sign"></span></a></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach ($bonos as $bono) { ?>
                                                    <tr>
                                                        <td class="form-group">
                                                            <input type="text" class="form-control descbono" name="descbono_<?php echo $i; ?>" value="<?php echo $bono->descripcion; ?>" placeholder="&nbsp;Descripci&oacute;n">
                                                        </td>
                                                        <td class="form-group">
                                                            <input type="text" class="form-control montobono miles" name="montobono_<?php echo $i; ?>" value="<?php echo $bono->monto; ?>" placeholder="&nbsp;Monto">
                                                        </td>
                                                        <td>
                                                            <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                <input class="form-control" size="16" type="text" readonly name="fecbono_<?php echo $i; ?>" value="<?php echo $bono->fecha; ?>" placeholder="Fecha">
                                                            </div>
                                                        </td>
                                                        <td class="form-group">
                                                            <input type="checkbox" name="propbono_<?php echo $i; ?>" class="minimal" <?php echo $bono->proporcional == 1 ? "checked" : ""; ?> />
                                                        </td>
                                                        <td class="form-group">
                                                            <input type="checkbox" name="impbono_<?php echo $i; ?>" class="minimal" <?php echo $bono->imponible == 1 ? "checked" : ""; ?> />
                                                        </td>
                                                        <td class="form-group">
                                                            <input type="checkbox" name="fijobono_<?php echo $i; ?>" class="minimal" <?php echo $bono->fijo == 1 ? "checked" : ""; ?> />
                                                        </td>
                                                        <td><a href="javascript:void(0);" data-toggle="tooltip" title="Eliminar Bono" class="del_bono"><span class="glyphicon glyphicon-minus-sign"></span></a></td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- FALTA AGREGAR OTROS BONOS -->
                            </section>
                        </div>
                        <div class="tab-pane " id="registrousuario">
                            <section id="new">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="registro">Desea <?php echo strtolower($usuario) ?> usuario?</label> </br>
                                            <input type="checkbox" class="minimal" name="registro" id="registro" <?php echo $datos_form['uemail'] ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="emailuser">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">@</span>
                                                <input type="text" class="form-control" name="emailuser" id="emailuser" value="<?php echo $datos_form['uemail'] ?>" placeholder="Ingrese Email de Usuario" <?php echo !$datos_form['uemail'] ? 'disabled' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($datos_form['iduser'] === 0) : ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Contraseña</label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese Contraseña" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="repassword">Repetir Contraseña</label>
                                                <input type="password" class="form-control" onpaste="return false" name="repassword" id="repassword" placeholder="Repetir Contraseña" disabled>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </section>
                        </div>

                    </div><!-- /.tab-content -->
                    <input type="hidden" name="row_bono" id="row_bono" value="<?php echo count($bonos) + 1; ?>">
                    <input type="hidden" name="idtrabajador" id="idtrabajador" value="<?php echo $datos_form['idtrabajador']; ?>">
                    <input type="hidden" name="iduser" value="<?php echo $datos_form['iduser']; ?>">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success"><?php echo $datos_form['idtrabajador'] == 0 ? "Agregar" : "Editar"; ?></button>&nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>remuneraciones/personal" class="btn btn-default">Volver</a>
                    </div>

                </div>

        </div><!-- /.box -->
        </form>
    </div>
    </div>

</section><!-- /.content -->
<script type="text/javascript">
    $(".form_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "bottom-left",
        weekStart: true,
        startView: 2,
        minView: 2,
        forceParse: 0,
        language: 'es',
    });
</script>

<script>

    function calcular_finiquito(){


        var indmesaviso = $('#indmesaviso').val();
        var indannoservicio = $('#indannoservicio').val();
        var indferiadolegal = $('#indferiadolegal').val();
        var indvoluntaria = $('#indvoluntaria').val();
        
        indmesaviso = indmesaviso == '' ? 0 : parseInt(replaceAll(indmesaviso, ".", ""));
        indannoservicio = indannoservicio == '' ? 0 : parseInt(replaceAll(indannoservicio, ".", ""));
        indferiadolegal = indferiadolegal == '' ? 0 : parseInt(replaceAll(indferiadolegal, ".", ""));
        indvoluntaria = indvoluntaria == '' ? 0 : parseInt(replaceAll(indvoluntaria, ".", ""));

        var indtotal = indmesaviso + indannoservicio + indferiadolegal + indvoluntaria;

        $('#indtotal').val(number_format(indtotal, 0, '.', '.'));        
    }


    $('.indemnizacion').on('input',function(){

                calcular_finiquito();


    })

    $('#tipogratificacion').on('change', function() {
        if ($(this).val() == 'MF') {
            $('#gratificacion').attr('disabled', false);
            $('#gratificacion').val('');
        } else {
            $('#basicBootstrapForm').formValidation('updateStatus', 'gratificacion', 'NOT_VALIDATED');
            $('#gratificacion').val('');

            $('#gratificacion').attr('disabled', 'disabled');


        }


    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });


    $(document).ready(function() {
        $(".add_bono").click(function() {
            var row_id = parseInt($('#row_bono').val());
            $("#customFields").append('<tr ><td class="form-group"><input type="text" class="form-control descbono" name="descbono_' + row_id + '" value="" placeholder="&nbsp;Descripci&oacute;n" ></td><td class="form-group"><input type="text" class="form-control montobono miles" name="montobono_' + row_id + '" value="" placeholder="&nbsp;Monto" ></td><td ><div class="input-group date form_date"  data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd" ><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" readonly name="fecbono_' + row_id + '"  value="" placeholder="Fecha"></div></td><td class="form-group"><input type="checkbox" name="propbono_' + row_id + '" class="minimal"  ></td><td class="form-group"><input type="checkbox" name="impbono_' + row_id + '" class="minimal"  ></td><td class="form-group"><input type="checkbox" name="fijobono_' + row_id + '" class="minimal"  ></td><td><a href="javascript:void(0);" data-toggle="tooltip" title="Eliminar Bono" class="del_bono" ><span class="glyphicon glyphicon-minus-sign"></span></a></td></tr>');
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $(".form_date").datetimepicker({
                format: "dd/mm/yyyy",
                autoclose: true,
                todayBtn: true,
                pickerPosition: "bottom-left",
                weekStart: true,
                startView: 2,
                minView: 2,
                forceParse: 0,
                language: 'es',
            });


            row_id = row_id + 1;
            $('#row_bono').val(row_id);
        });

        $("#customFields").on('click', '.del_bono', function() {
            $(this).parent().parent().remove();
        });
    });
</script>

<script language="Javascript">
    function VerificaRut(rut) {
        if (rut.toString().trim() != '') {

            var caracteres = new Array();
            var serie = new Array(2, 3, 4, 5, 6, 7);
            var dig = rut.toString().substr(rut.toString().length - 1, 1);
            rut = rut.toString().substr(0, rut.toString().length - 1);
            for (var i = 0; i < rut.length; i++) {
                caracteres[i] = parseInt(rut.charAt((rut.length - (i + 1))));
            }

            var sumatoria = 0;
            var k = 0;
            var resto = 0;

            for (var j = 0; j < caracteres.length; j++) {
                if (k == 6) {
                    k = 0;
                }
                sumatoria += parseInt(caracteres[j]) * parseInt(serie[k]);
                k++;
            }

            resto = sumatoria % 11;
            dv = 11 - resto;

            if (dv == 10) {
                dv = "K";
            } else if (dv == 11) {
                dv = 0;
            }

            if (dv.toString().trim().toUpperCase() == dig.toString().trim().toUpperCase())
                return true;
            else
                return false;
        } else {
            return false;
        }
    }


    function replaceAll(text, busca, reemplaza) {
        while (text.toString().indexOf(busca) != -1)
            text = text.toString().replace(busca, reemplaza);
        return text;
    }
</script>

<script>
    $('.cargas_familiares').on('input', function() {
        var num_cargas_familiares = 0;
        $(".cargas_familiares").each(function() {
            var cargas = $(this).val() == "" ? 0 : parseInt($(this).val());
            num_cargas_familiares += cargas;
            //console.log($(this).attr('id'));
        });
        console.log(num_cargas_familiares);
        if (num_cargas_familiares > 0) {
            $('#tramo_asigfamiliar').attr('disabled', false);
        } else {
            $("#tramo_asigfamiliar").prop('selectedIndex', 0);
            $('#tramo_asigfamiliar').attr('disabled', true);
        }

    });


    $('#region').change(function() {

        if ($(this).val() != '') {

            $.get("<?php echo base_url(); ?>admins/get_comunas/" + $(this).val(), function(data) {
                // Limpiamos el select
                $('#comuna option').remove();
                var_json = $.parseJSON(data);
                $('#comuna').append('<option value="">Seleccione Comuna</option>');
                for (i = 0; i < var_json.length; i++) {
                    $('#comuna').append('<option value="' + var_json[i].idcomuna + '">' + var_json[i].nombre + '</option>');
                }
                $('#basicBootstrapForm').formValidation('revalidateField', 'comuna');
            });

        }
    });


    $(document).ready(function() {

        if ($('#region').val() != '') {
            $.get("<?php echo base_url(); ?>admins/get_comunas/" + $('#region').val(), function(data) {
                // Limpiamos el select
                $('#comuna option').remove();
                var_json = $.parseJSON(data);
                $('#comuna').append('<option value="">Seleccione Comuna</option>');
                for (i = 0; i < var_json.length; i++) {
                    $('#comuna').append('<option value="' + var_json[i].idcomuna + '">' + var_json[i].nombre + '</option>');
                }
                $("#comuna").val($('#idcomuna').val());
            });
            // seleccionar comuna

        }

        FormValidation.Validator.validateRut = {
            validate: function(validator, $field, options) {
                var validador = true;
                $field.Rut();
                var rut = $field.val();
                var cleanRut = replaceAll(rut, ".", "");
                var cleanRut = replaceAll(cleanRut, "-", "");
                if (VerificaRut(cleanRut)) {
                    return true;

                } else {
                    return {
                        valid: false
                    }

                }


            }
        };

        $('#basicBootstrapForm').formValidation({
                framework: 'bootstrap',
                excluded: ':disabled',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    rut: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Rut Trabajador es requerido'
                            },
                            stringLength: {
                                min: 0,
                                max: 12,
                                message: 'El largo del Rut es Incorrecto'
                            },
                            validateRut: {
                                message: 'Rut Incorrecto'
                            }

                        }
                    },

                    nombre: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nombre Trabajador es requerido'
                            }
                        }
                    },

                    apaterno: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Apellido Paterno Trabajador es requerido'
                            }
                        }
                    },

                    amaterno: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Apellido Materno Trabajador es requerido'
                            }
                        }
                    },

                    fechanacimiento: {
                        row: '.form-group',
                        validators: {
                            date: {
                                format: 'DD/MM/YYYY',
                                message: 'El valor no es una fecha v&aacute;lida'
                            },
                            notEmpty: {
                                message: 'Fecha de Nacimiento es requerido'
                            }
                        }
                    },

                    sexo: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Sexo es requerido'
                            }
                        }
                    },

                    ecivil: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Estado Civil es requerido'
                            }
                        }
                    },


                    nacionalidad: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nacionalidad es requerida'
                            }
                        }
                    },

                    direccion: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Direcci&oacute;n Trabajador es requerida'
                            }
                        }
                    },

                    region: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Regi&oacute;n Comunidad es requerido'
                            }
                        }
                    },

                    comuna: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Comuna es requerida'
                            }
                        }
                    },

                    /*            fono: {
                                    row: '.form-group',
                                    validators: {
                                        notEmpty: {
                                            message: 'Fono es requerido'
                                        },
                                        integer: {
                                            message: 'Fono s&oacute;lo puede contener n&uacute;meros'
                                        }
                                    }
                                },
                    */

                    emailcontacto: {
                        row: '.form-group',
                        validators: {
                            emailAddress: {
                                message: 'El valor ingresado no es una direcci&oacute; de email valida'
                            }
                        }
                    },


                    fechaingreso: {
                        row: '.form-group',
                        validators: {
                            date: {
                                format: 'DD/MM/YYYY',
                                message: 'El valor no es una fecha v&aacute;lida'
                            },
                            notEmpty: {
                                message: 'Fecha de Ingreso es requerido'
                            }
                        }
                    },


                    fechaafc: {
                        row: '.form-group',
                        validators: {
                            date: {
                                format: 'DD/MM/YYYY',
                                min: 'fechaingreso',
                                message: 'El valor no es una fecha v&aacute;lida'
                            },
                            notEmpty: {
                                message: 'Fecha AFC es requerido'
                            }
                        }
                    },

                    fechafiniquito: {
                        row: '.form-group',
                        validators: {
                            date: {
                                format: 'DD/MM/YYYY',
                                min: 'fechaingreso',
                                message: 'El valor no es una fecha v&aacute;lida'
                            },
                            notEmpty: {
                                message: 'Fecha Finiquito es requerido'
                            }
                        }
                    },

                    causalfiniquito: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Causal Finiquito es requerido'
                            }
                        }
                    },                    

                    cargo: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Cargo es requerido'
                            }
                        }
                    },

                    fecinicvacaciones: {
                        row: '.form-group',
                        validators: {
                            date: {
                                format: 'DD/MM/YYYY',
                                min: 'fechaingreso',
                                message: 'El valor no es una fecha v&aacute;lida'
                            },
                            notEmpty: {
                                message: 'Fecha Inicio C&aacute;lculo de vacaciones es requerido'
                            }
                        }
                    },


                    saldoinicvacaciones: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Saldo inicial de vacaciones legales es requerido'
                            },
                            numeric: {
                                separator: '.',
                                message: 'Saldo inicial de vacaciones legales s&oacute;lo puede contener n&uacute;meros'
                            }
                        },

                    },


                    saldoinicvacprog: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Saldo inicial de vacaciones progresivas es requerido'
                            },
                            numeric: {
                                separator: '.',
                                message: 'Saldo inicial de vacaciones progresivas s&oacute;lo puede contener n&uacute;meros'
                            }
                        },

                    },


                    tipocontrato: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Tipo de Contrato es requerido'
                            }
                        }
                    },


                    diastrabajo: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'D&iacute;as de Trabajo es requerido'
                            },
                            between: {
                                min: 0,
                                max: 30,
                                message: 'D&iacute;as de Trabajo debe estar entre 0 y 30'
                            },
                        }
                    },

                    horasdiarias: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Horas Diarias es requerido'
                            },
                            integer: {
                                message: 'El valor ingresado no es num&eacute;rico',
                            },
                            between: {
                                min: 0,
                                max: 24,
                                message: 'Horas Diarias debe estar entre 0 y 24'
                            },
                        }
                    },

                    horassemanales: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Horas Semanales es requerido'
                            },
                            integer: {
                                message: 'El valor ingresado no es num&eacute;rico',
                            }
                        }
                    },


                    sueldobase: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Sueldo Base es requerido'
                            },
                            // The bank validator doesn't have any option
                            blank: {}
                        }

                    },


                    tipogratificacion: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Tipo de Gratificaci&oacute;n es requerido'
                            }
                        }
                    },

                    gratificacion: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Gratificaci&oacute;n es requerido'
                            },
                        }

                    },

                    cargassimples: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nro. de Cargas Simples es Requerido'
                            },
                            greaterThan: {
                                value: 0,
                                message: 'El valor debe ser mayor o igual a cero'
                            }
                        }
                    },


                    cargasinvalidas: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nro. de Cargas Inv&aacute;lidas es Requerido'
                            },
                            greaterThan: {
                                value: 0,
                                message: 'El valor debe ser mayor o igual a cero'
                            }
                        }
                    },


                    cargasmaternales: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nro. de Cargas Maternales es Requerido'
                            },
                            greaterThan: {
                                value: 0,
                                message: 'El valor debe ser mayor o igual a cero'
                            }
                        }
                    },

                    cargasretroactivas: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Nro. de Cargas Retroactivas es Requerido'
                            },
                            greaterThan: {
                                value: 0,
                                message: 'El valor debe ser mayor o igual a cero'
                            }
                        }
                    },

                    asigfamiliar: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Asignaci&oacute;n Familiar es requerido'
                            },
                        }

                    },


                    tramo_asigfamiliar: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Tramo Asignaci&oacute;n Familiar es requerido'
                            },
                        }

                    },


                    movilizacion: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Movilizaci&oacute;n es requerido'
                            }
                        }
                    },

                    colacion: {
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Colaci&oacute;n es requerido'
                            }
                        }
                    },

                    /*descbono: {
                        // The children's full name are inputs with class .childFullName
                        selector: '.descbono',
                        row: '.form-group',
                        validators: {
                            notEmpty: {
                                message: 'Descripci&oacute;n del Bono es requerido'
                            }
                        }
                    }*/

                    emailuser: {
                        row: '.form-group',
                        validators: {
                            emailAddress: {
                                message: 'El valor ingresado no es una direcci&oacute; de email valida'
                            },
                            notEmpty: {
                                message: 'El Email de usuario es requerido'
                            }
                        }
                    },

                    password: {
                        row: '.form-group',
                        validators: {
                            stringLength: {
                                min: 6,
                                max: 20,
                                message: 'La Password debe contener entre 6 y 20 caracteres'
                            },
                            notEmpty: {
                                message: 'La contrase? es requerida'
                            }
                        }
                    },

                    repassword: {
                        row: '.form-group',
                        validators: {
                            identical: {
                                field: 'password',
                                message: 'Password y su confirmaci&oacute;n no son iguales'
                            },
                            notEmpty: {
                                message: 'La confirmacion de la contrase? es requerida'
                            }
                        }
                    },
                }
            })

            // Called when a field is invalid
            .on('err.field.fv', function(e, data) {
                // data.element --> The field element

                var $tabPane = data.element.parents('.tab-pane'),
                    tabId = $tabPane.attr('id');

                $('a[href="#' + tabId + '"][data-toggle="tab"]')
                    .parent()
                    .find('i')
                    .removeClass('fa-check')
                    .addClass('fa-times');
            })
            // Called when a field is valid
            .on('success.field.fv', function(e, data) {
                // data.fv      --> The FormValidation instance
                // data.element --> The field element

                var $tabPane = data.element.parents('.tab-pane'),
                    tabId = $tabPane.attr('id'),
                    $icon = $('a[href="#' + tabId + '"][data-toggle="tab"]')
                    .parent()
                    .find('i')
                    .removeClass('fa-check fa-times');

                // Check if all fields in tab are valid
                var isValidTab = data.fv.isValidContainer($tabPane);
                if (isValidTab !== null) {
                    $icon.addClass(isValidTab ? 'fa-check' : 'fa-times');
                }
            })
            .on('success.form.fv', function(e) {
                /**** VALIDAR EN SERVIDOR VIA AJAX ******/
                // Prevent default form submission
                e.preventDefault();

                var $form = $(e.target), // The form instance
                    fv = $form.data('formValidation'); // FormValidation instance

                // Send data to back-end
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>remuneraciones/validate_sueldo_minimo',
                    data: $form.serialize(),
                    dataType: 'json'
                }).success(function(response) {
                    // We will display the messages from server if they're available

                    // If there is error returned from server
                    if (response.result === 'error') {
                        //console.log(response.fields);
                        for (var field in response.fields) {

                            fv
                                // Show the custom message
                                .updateMessage(field, 'blank', response.fields[field])
                                // Set the field as invalid
                                .updateStatus(field, 'INVALID', 'blank');
                        }
                    } else {
                        // Do whatever you want here
                        // such as showing a modal ...
                        fv.defaultSubmit();
                    }
                });

            })
            .find('.miles').mask('000.000.000.000.000', {
                reverse: true
            });
    });

    $(".mask_date").inputmask("dd/mm/yyyy", {
        "placeholder": "dd/mm/yyyy"
    });

    $(".mask_date").on('blur', function(event) {
        //if($(this).val() == ''){
        $('#basicBootstrapForm').formValidation('revalidateField', 'fechanacimiento');
        $('#basicBootstrapForm').formValidation('revalidateField', 'fechaingreso');
        $('#basicBootstrapForm').formValidation('revalidateField', 'fechaafc');
        //console.log( $("#fechaafc").attr('disabled'));
        if ($(this).attr('id') == 'fechaingreso' && $("#fechaafc").attr('disabled') != 'disabled' && $("#fechaafc").val() == '') { //si ingresamos fecha ingreso y aun no ingresamos fecha AFC, se copia
            $("#fechaafc").val($(this).val());
            $('#basicBootstrapForm').formValidation('updateStatus', 'fechaafc', 'NOT_VALIDATED');
        }
        //}

    });

    $("#parttime").on('ifToggled', function(event) {
        $('#basicBootstrapForm').formValidation('updateStatus', 'sueldobase', 'NOT_VALIDATED'); //quita validacion
    });


    $("#segcesantia").on('ifChecked', function(event) {
        $("#fechaafc").attr('disabled', false);
        $("#fechaafc").val($("#fechaingreso").val());
    });


    $("#segcesantia").on('ifUnchecked', function(event) {
        $('#basicBootstrapForm').formValidation('updateStatus', 'fechaafc', 'NOT_VALIDATED');
        $("#fechaafc").val('');
        $("#fechaafc").attr('disabled', true);

    });

    if (!$("#activo").is(':checked')) {
        $("#linkregistro").hide();
    }

    $("#activo").on('ifChecked', function(event) {
        $('#basicBootstrapForm').formValidation('updateStatus', 'fechafiniquito', 'NOT_VALIDATED');
        $("#fechafiniquito").attr('disabled', true);
        $("#fechafiniquito").val('');


        $("#causalfiniquito").attr('disabled', true);
        $("#causalfiniquito").val('');



        $("#indmesaviso").attr('disabled', true);
        $("#indmesaviso").val('');

        $("#indannoservicio").attr('disabled', true);
        $("#indannoservicio").val('');

        $("#indferiadolegal").attr('disabled', true);
        $("#indferiadolegal").val('');

        $("#indvoluntaria").attr('disabled', true);
        $("#indvoluntaria").val('');


        $("#indtotal").val(0);


      



        $("#linkregistro").show();



    });

    $("#activo").on('ifUnchecked', function(event) {
        $('#basicBootstrapForm').formValidation('updateStatus', 'fechafiniquito', 'NOT_VALIDATED');
        $("#fechafiniquito").attr('disabled', false);
        $("#fechafiniquito").val('');


        $("#causalfiniquito").attr('disabled', false);
        $("#causalfiniquito").val('');



        $("#indmesaviso").attr('disabled', false);
        $("#indmesaviso").val('');

        $("#indannoservicio").attr('disabled', false);
        $("#indannoservicio").val('');

        $("#indferiadolegal").attr('disabled', false);
        $("#indferiadolegal").val('');

        $("#indvoluntaria").attr('disabled', false);
        $("#indvoluntaria").val('');

        obtiene_datos_finiquito();

        $("#linkregistro").hide();
    });

    function obtiene_datos_finiquito(){
        var idtrabajador = $('#idtrabajador').val();

        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>remuneraciones/get_datos_finiquito/'+idtrabajador,
            dataType: 'json',
            async: false,
        }).success(function(data) {
              //console.log(data);
              //console.log(data.mes_aviso);

              $('#indmesaviso').val(data.mes_aviso);
              $('#indannoservicio').val(data.renta_antiguedad);
              $('#indferiadolegal').val(data.renta_vacaciones);
              if($('#indvoluntaria').val() == ''){
                    $('#indvoluntaria').val(0);  
              }
              

              sessionStorage.setItem('indmesaviso',data.mes_aviso);
              sessionStorage.setItem('indannoservicio',data.renta_antiguedad);

              
              activa_desactiva_finiquito();
              calcular_finiquito();
        });




    }

    $("#registro").on('ifChecked', function(event) {
        $("#password").attr('disabled', false);
        $("#password").val('');
        $("#repassword").attr('disabled', false);
        $("#repassword").val('');
        $("#emailuser").attr('disabled', false);
    });

    $("#registro").on('ifUnchecked', function(event) {
        $("#password").attr('disabled', true);
        $("#password").val('');
        $("#repassword").attr('disabled', true);
        $("#repassword").val('');
        $("#emailuser").attr('disabled', true);
    });

    $('#causalfiniquito').on('click',function(){

        obtiene_datos_finiquito();        
    })


    function activa_desactiva_finiquito(){

        var causalfiniquito = $('#causalfiniquito').val();

        if(causalfiniquito == 14 || causalfiniquito == ''){ //necesidades de la empresa

                $('#indmesaviso').attr('readonly',false);
                //$('#indmesaviso').val(sessionStorage.getItem('indmesaviso'));
                $('#indannoservicio').attr('readonly',false);
                //$('#indannoservicio').val(sessionStorage.getItem('indannoservicio'));

        }else if(causalfiniquito == 15){ //liquidacion de bienes

                $('#indmesaviso').attr('readonly','readonly');
                $('#indmesaviso').val(0);
                $('#indannoservicio').attr('readonly',false);
                //$('#indannoservicio').val(sessionStorage.getItem('indannoservicio'));

        }else{ //lo demas

                $('#indmesaviso').attr('readonly','readonly');
                $('#indmesaviso').val(0);


                $('#indannoservicio').attr('readonly','readonly');
                $('#indannoservicio').val(0);
        }



    }


    $(document).ready(function() {

        sessionStorage.setItem('indmesaviso',0);
        sessionStorage.setItem('indannoservicio',0);
        

        $('.numeros').keypress(function(event) {
            if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 46) {
                event.preventDefault();
            }
        })
    });
</script>
