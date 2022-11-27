        <!-- Main content -->
        <section class="content">
            <?php if (isset($message)) : ?>
                <div class="row">

                    <div class="alert alert-<?php echo $classmessage; ?> alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa <?php echo $icon; ?>"></i> Alerta!</h4>
                        <?php echo $message; ?>
                    </div>
                </div>
                <br>
            <?php endif; ?>
            <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_clave" id="basicBootstrapForm" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Cambio de Clave</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="password">Clave Actual</label>
                                    <input type="password" class="form-control" name="password_actual" id="password_actual" placeholder="Ingrese Password Actual">
                                </div>

                                <div class="form-group">
                                    <label for="password">Nueva Clave</label>
                                    <input type="password" class="form-control" name="password_nueva" id="password_nueva" placeholder="Ingrese Nueva Password">
                                </div>

                                <div class="form-group">
                                    <label for="repassword">Repetir Nueva Clave</label>
                                    <input type="password" class="form-control" onpaste="return false" name="repassword" id="repassword" placeholder="Repetir Nueva Password">
                                </div>

                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Actualiza Clave</button>
                            </div>
                        </div><!-- /.box -->
                    </div>
            </form>
        </section><!-- /.content -->
        <script>
            $(document).ready(function() {
                $('#basicBootstrapForm').formValidation({
                        framework: 'bootstrap',
                        excluded: ':disabled',
                        icon: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {

                            password_actual: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Clave actual es requerida'
                                    },
                                    stringLength: {
                                        min: 6,
                                        max: 20,
                                        message: 'La Password debe contener entre 6 y 20 caracteres'
                                    },
                                    blank: {}
                                }
                            },


                            password_nueva: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Clave nueva es requerida'
                                    },
                                    stringLength: {
                                        min: 6,
                                        max: 20,
                                        message: 'La Password debe contener entre 6 y 20 caracteres'
                                    }
                                }
                            },

                            repassword: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Confirmaci&oacute;n de Clave nueva es requerido'
                                    },
                                    identical: {
                                        field: 'password_nueva',
                                        message: 'Password y su confirmaci&oacute;n no son iguales'
                                    }
                                }
                            },
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
                            url: '<?php echo base_url(); ?>admins/validate_password_user',
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
                    });

            });
        </script>
