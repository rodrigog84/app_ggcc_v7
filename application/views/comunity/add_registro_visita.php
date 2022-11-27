<!-- Main content -->
<section class="content">
    <!-- form start -->
    <form id="basicBootstrapForm" action="<?php echo base_url(); ?>comunity/submit_registro_visita" id="basicBootstrapForm" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $titulo; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese Nombre Completo" value="<?php echo $datos_form['nombre']; ?>">
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="apellidos">Apellidos</label>
                                    <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Ingrese Apellidos" value="<?php echo $datos_form['apellidos']; ?>" />
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="rut">Rut</label>
                                    <input type="text" class="form-control" name="rut" id="rut" placeholder="Ingrese Rut" value="<?php echo $datos_form['rut']; ?>" />
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="propiedad">Propiedad</label>
                                    <select name="propiedad" id="propiedad" class="form-control">
                                        <option value="">Seleccione Propiedad</option>
                                        <?php foreach ($propiedades as $propiedad) { ?>
                                            <?php $pselected = $datos_form['idpropiedad'] == $propiedad->id ? 'selected' : ''; ?>
                                            <option value="<?php echo $propiedad->id; ?>" <?php echo $pselected; ?>><?php echo $propiedad->numero . ' - ' . $propiedad->responsable; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-header">
                        <h3 class="box-title">Agregar Estacionamiento Visita</h3>
                    </div>
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="estvisita">Estacionamiento Visita</label>
                                    <select name="estvisita" id="estvisita" class="form-control">
                                        <option value="0">Seleccione Estacionamiento Visita</option>
                                        <?php if ($datos_form['idregistro'] !== 0) : ?>
                                            <option value="<?php echo $datos_form['idestacionamiento']; ?>" selected><?php echo $datos_form['estacionamiento']; ?></option>
                                        <?php endif; ?>
                                        <?php if (count($estacionamientos) > 1) { ?>
                                            <?php foreach ($estacionamientos as $estacionamiento) { ?>
                                                <option value="<?php echo $estacionamiento->id; ?>"><?php echo $estacionamiento->nombre; ?></option>
                                            <?php } ?>
                                        <?php } else if ($estacionamientos) { ?>
                                            <option value="<?php echo $estacionamientos->id ?>"><?php echo $estacionamientos->nombre; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="patente">Patente</label>
                                    <input type="text" class="form-control" name="patente" id="patente" placeholder="Ingrese Patente Vehiculo" value="<?php echo $datos_form['patente']; ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="hidden" name="idregistro" id="idregistro" value="<?php echo $datos_form['idregistro']; ?>">
                        <input type="hidden" name="idcomunidad" id="idcomunidad" value="<?php echo $datos_form['idcomunidad']; ?>">
                        <button type="submit" class="btn btn-success"><?php echo $datos_form['idregistro'] === 0 ? 'Agregar' : 'Editar' ?></button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>comunity/libro_visitas" class="btn btn-default">Volver</a>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </form>
</section><!-- /.content -->

<script>
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
    $(document).ready(function() {
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

        function replaceAll(text, busca, reemplaza) {
            while (text.toString().indexOf(busca) != -1)
                text = text.toString().replace(busca, reemplaza);
            return text;
        }

        $('#basicBootstrapForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                nombre: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Nombre es requerido'
                        }
                    }
                },

                apellidos: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Nombre es requerido'
                        }
                    }
                },

                rut: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Rut es requerido'
                        },
                        stringLength: {
                            min: 0,
                            max: 12,
                            message: 'El largo del Rut es Incorrecto'
                        },
                        validateRut: {
                            message: 'Rut Incorrecto. Escribir sin guion ni puntos.'
                        }

                    }
                },

                propiedad: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Propiedad es requerida'
                        }
                    }
                },

                patente: {
                    row: '.form-group',
                    validators: {
                        stringLength: {
                            min: 5,
                            max: 10,
                            message: 'El largo de la Patente es Incorrecto'
                        },
                        notEmpty: {
                            message: 'Patente es requerida'
                        }
                    }
                },
            }
        })

        const estVisita = document.getElementById("estvisita");

        if (estVisita.value == 0) {
            $("#patente").attr('disabled', true);
            $("#patente").val('');
        } else {
            $("#patente").attr('disabled', false);
        }

        $("#estvisita").on('change', function(event) {
            if (estVisita.value == 0) {
                $("#patente").attr('disabled', true);
                $("#patente").val('');
            } else {
                $("#patente").attr('disabled', false);
            }
        });
    });
</script>
