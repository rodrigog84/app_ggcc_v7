<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_estacionamiento" id="basicBootstrapForm" method="post">
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="comunidad">Comunidad</label>
                                    <?php
                                    //$this->session->userdata('id') 
                                    ?>
                                    <?php if ($permite_editar) { ?>
                                        <select name="comunidad" id="comunidad" class="form-control">
                                            <option value="">Seleccione Comunidad</option>
                                            <?php foreach ($comunidades as $comunidad) { ?>
                                                <?php $comunidadselected = $comunidad->id == $datos_form['idcomunidad'] ? "selected" : ""; ?>
                                                <option value="<?php echo $comunidad->id; ?>" <?php echo $comunidadselected; ?>><?php echo $comunidad->nombre; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input type="text" class="form-control" value="<?php echo $this->session->userdata('comunidadnombre'); ?>" readonly>
                                        <input type="hidden" id="comunidad" name="comunidad" value="<?php echo $this->session->userdata('comunidadid'); ?>">

                                    <?php } ?>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="propiedad">Propiedad</label>
                                    <select name="propiedad" id="propiedad" class="form-control">
                                        <option value="">Seleccione Propiedad</option>
                                    </select>
                                    <input type="hidden" id="idpropiedad" value="<?php echo $datos_form['idpropiedad']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="nombre">Nombre Estacionamiento</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese Nombre Estacionamiento" value="<?php echo $datos_form['nombre']; ?>">
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="prorrateo">Prorrateo</label>
                                    <input type="text" class="form-control" name="prorrateo" id="prorrateo" placeholder="Ingrese Porcentaje Prorrateo" value="<?php echo $datos_form['prorrateo']; ?>">
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <input type="hidden" name="idestacionamiento" value="<?php echo $datos_form['idestacionamiento']; ?>">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Agregar</button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>admins/admin_estacionamiento" class="btn btn-default">Volver</a>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section><!-- /.content -->


<script>
    $('#comunidad').change(function() {
        $.get("<?php echo base_url(); ?>admins/get_propiedades_by_comunidad/" + $(this).val(), function(data) {
            // Limpiamos el select
            $('#propiedad option').remove();
            var_json = $.parseJSON(data);
            $('#propiedad').append('<option value="">Seleccione Propiedad</option>');
            for (i = 0; i < var_json.length; i++) {
                $('#propiedad').append('<option value="' + var_json[i].id + '">' + var_json[i].numero + '</option>');
            }
            $('#basicBootstrapForm').formValidation('revalidateField', 'propiedad');
        });
    });


    $(document).ready(function() {
        $.get("<?php echo base_url(); ?>admins/get_propiedades_by_comunidad/" + $('#comunidad').val(), function(data) {
            // Limpiamos el select
            $('#propiedad option').remove();
            var_json = $.parseJSON(data);
            $('#propiedad').append('<option value="">Seleccione Propiedad</option>');
            for (i = 0; i < var_json.length; i++) {
                $('#propiedad').append('<option value="' + var_json[i].id + '">' + var_json[i].numero + '</option>');
            }
            $("#propiedad").val($('#idpropiedad').val());
            $('#basicBootstrapForm').formValidation('revalidateField', 'propiedad');
        });


        $('#basicBootstrapForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                comunidad: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Comunidad es requerido'
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

                nombre: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Nombre Estacionamiento es requerido'
                        }
                    }
                },


                prorrateo: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Prorrateo es requerido'
                        },
                        between: {
                            min: 0,
                            max: 100,
                            message: 'El porcentaje debe estar entre 0 y 100'
                        },
                        numeric: {
                            separator: '.',
                            message: 'Monto s&oacute;lo puede contener n&uacute;meros'
                        },
                    }
                }
            }
        })

    });
</script>
