<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-header">
                    <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div>
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url(); ?>comunity/submit_bitacora" id="basicBootstrapForm" method="post">
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="accion">Accion</label>
                                    <input type="text" class="form-control" name="accion" id="accion" placeholder="Ingrese Accion" value="<?php echo $datos_form['accion']; ?>" />
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="descripcion">Descripcion</label>
                                    <textarea class="textarea" id="descripcion" name="descripcion" placeholder="Agrega Texto Aqu&iacute;" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $datos_form['descripcion']; ?></textarea>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <input type="hidden" name="idbitacora" id="idbitacora" value="<?php echo $datos_form['idbitacora']; ?>">
                        <input type="hidden" name="idcomunidad" id="idcomunidad" value="<?php echo $datos_form['idcomunidad']; ?>">
                        <input type="hidden" name="iduser" id="iduser" value="<?php echo $datos_form['iduser']; ?>">
                        <button type="submit" class="btn btn-success"><?php echo $datos_form['idbitacora'] === 0 ? 'Agregar' : 'Editar' ?></button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>comunity/libro_novedades" class="btn btn-default">Volver</a>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
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
                            message: 'Rut Proveedor es requerido'
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
                            min: 0,
                            max: 6,
                            message: 'El largo de la Patente es Incorrecto'
                        },
                    }
                },
            }
        })

    });
</script>
