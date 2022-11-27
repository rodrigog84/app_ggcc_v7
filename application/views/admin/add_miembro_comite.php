<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <!--<h3 class="box-title"><?php echo $titulo; ?></h3>-->
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_miembro_comite" id="basicBootstrapForm" method="post">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="registro">Desea asociar usuario?</label> </br>
                                    <input type="checkbox" class="minimal" name="registro" id="registro">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iduser">Usuario</label>
                                    <select name="iduser" id="iduser" class="form-control" disabled>
                                        <option value="">Seleccione Usuario</option>
                                        <?php foreach ($users as $user) { ?>
                                            <?php if ($user->level === "2") : ?>
                                                <?php $selected = $user->id == $datos_form['iduser'] ? "selected" : ""; ?>
                                                <option value="<?php echo $user->id; ?>" <?php echo $selected; ?>><?php echo $user->nombre; ?></option>
                                            <?php endif; ?>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="first_name">Nombre</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Ingrese nombre" value="<?php echo $datos_form['nombre']; ?>">
                                    <!--value="<?php echo $datos_form['nombre']; ?>"-->
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="last_name">Apellido</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Ingrese apellido" value="<?php echo $datos_form['apellido']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="email">Correo electronico</label>
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Ingrese correo electronico" value="<?php echo $datos_form['email']; ?>">
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <select name="cargo" id="cargo" class="form-control">
                                        <option value="">Seleccione Cargo</option>
                                        <?php foreach ($cargos_comite as $cargo) { ?>
                                            <?php $selected = $datos_form['idcargo'] === $cargo->id ? 'selected' : ''; ?>
                                            <option value="<?php echo $cargo->id; ?>" <?php echo $selected; ?>><?php echo $cargo->cargo; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <input type="hidden" name="idmiembro" id="idmiembro" value="<?php echo $idmiembro; ?>">
                        <button type="submit" class="btn btn-success">Agregar</button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>admins/admin_comite" class="btn btn-default">Volver</a>
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
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                first_name: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Nombre es requerido'
                        },
                       /* digits: {
                            message: 'El campo Nombre no puede contener digitos'
                        }*/
                    }
                },

                last_name: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Apellido es requerido'
                        },
                        /*digits: {
                            message: 'El campo Nombre no puede contener digitos'
                        }*/
                    }
                },

                email: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Correo es requerido'
                        },
                        emailAddress: {
                            message: 'Correo incorrecto. Intenta algo asi: ejemplo@ejemplo.com'
                        }
                    }
                },

                iduser: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Usuario es requerido'
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
            }
        })

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $("#registro").on('ifChecked', function(event) {
            $("#iduser").attr('disabled', false);
            $("#first_name").attr('disabled', true);
            $("#last_name").attr('disabled', true);
            $("#email").attr('disabled', true);
        });

        $("#registro").on('ifUnchecked', function(event) {
            $("#iduser").attr('disabled', true);
            $("#first_name").attr('disabled', false);
            $("#last_name").attr('disabled', false);
            $("#email").attr('disabled', false);
        });
    })
</script>
