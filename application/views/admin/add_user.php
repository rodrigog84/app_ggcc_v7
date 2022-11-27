        <!-- Main content -->
        <section class="content">
            <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_user" id="basicBootstrapForm" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title"><?php echo $titulo; ?></h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese Nombre" value="<?php echo $datos_form['nombre']; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="apellido">Apellido</label>
                                            <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ingrese Apellido" value="<?php echo $datos_form['apellido']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">@</span>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese Email" value="<?php echo $datos_form['email']; ?>" <?php if ($titulo == "Editar Usuario") {
                                                                                                                                                                                            echo "disabled";
                                                                                                                                                                                        } ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="perfil">Perfil</label>
                                            <select name="perfil" id="perfil" class="form-control">
                                                <option value="">Seleccione Perfil</option>
                                                <?php foreach ($perfiles as $perfil) { ?>
                                                    <?php $perfilselected = $perfil->id == $datos_form['perfil'] ? "selected" : ""; ?>
                                                    <option value="<?php echo $perfil->id; ?>" <?php echo $perfilselected; ?> <?php echo $perfil->id == 5 ? 'hidden' : ''; ?>><?php echo $perfil->description; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!--div class='row'>
                      <div class='col-md-6'>
                        <div class="form-group">
                            <label for="comunidad">Comunidad</label>
                            <select name="comunidad" id="comunidad"  class="form-control comunidad" disabled>
                                <option value="">Seleccione Comunidad</option>
                                <?php foreach ($comunidades as $comunidad) { ?>
                                  <?php $comunidadselected = $comunidad->id == $datos_form['idcomunidad'] ? "selected" : ""; ?>
                                  <option value="<?php echo $comunidad->id; ?>" <?php echo $comunidadselected; ?> ><?php echo $comunidad->nombre; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                      </div>

                      <div class='col-md-6'>
                        <div class="form-group">

                          <label for="propiedad">Propiedad</label>
                            <select name="propiedad" id="propiedad"  class="form-control propiedad" disabled>
                              <option value="">Seleccione Propiedad</option>
                            </select>
                            <input type="hidden" id="idpropiedad" value="<?php echo $datos_form['idpropiedad']; ?>" >
                        </div>
                      </div>
                    </div-->


                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese Password" <?php if ($titulo == "Editar Usuario") {
                                                                                                                                                            echo "disabled";
                                                                                                                                                        } ?>>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="repassword">Repetir Password</label>
                                            <input type="password" class="form-control" onpaste="return false" name="repassword" id="repassword" placeholder="Repetir Password" <?php if ($titulo == "Editar Usuario") {
                                                                                                                                                                                    echo "disabled";
                                                                                                                                                                                } ?>>
                                        </div>
                                    </div>
                                </div>


                            </div><!-- /.box-body -->
                            <input type="hidden" name="iduser" value="<?php echo $datos_form['iduser']; ?>">
                        </div><!-- /.box -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Asociar Comunidad y Propiedades</h3>
                                <!-- tools box -->
                                <div class="pull-right box-tools">
                                    <a id="add_row" class="btn btn-info btn-sm" data-toggle="tooltip" title="Agregar"><i class="fa fa-plus"></i></a>
                                </div><!-- /. tools -->
                            </div><!-- /.box-header -->
                            <!-- form start -->

                            <div class="box-body">

                                <!--a id="add_row" class="btn btn-primary">Agregar</a-->
                                <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Comunidad</th>
                                            <th class="text-center">Propiedad</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id='addr0' data-id="0" class="hidden">
                                            <td class="form-group" data-name="comunidad-">
                                                <!-- DEFINE EL NOMBRE QUE TENDRAN LOS ELEMENTOS CREADOS -->
                                                <select name="comunidad-0" id="comunidad-0" class="form-control comunidad">
                                                    <option value="">Seleccione Comunidad</option>
                                                    <?php foreach ($comunidades as $comunidad) { ?>
                                                        <?php $comunidadselected = $comunidad->id == $datos_form['idcomunidad'] ? "selected" : ""; ?>
                                                        <?php $muestracomunidad = $this->session->userdata('comunidadid') == '' || $this->session->userdata('comunidadid') == $comunidad->id ? true : false; ?>

                                                        <?php if ($muestracomunidad) { ?>
                                                            <option value="<?php echo $comunidad->id; ?>" <?php echo $comunidadselected; ?>><?php echo $comunidad->nombre; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="form-group" data-name="propiedad-">
                                                <select name="propiedad-0" id="propiedad-0" class="form-control propiedad" disabled>
                                                    <option value="">Seleccione Propiedad</option>
                                                </select>
                                            </td>
                                            <td data-name="del">
                                                <!--button nam"del0" class='btn btn-danger glyphicon glyphicon-remove row-remove'></button-->
                                                <center><a href="#" data-toggle="tooltip" title="Eliminar" class="row-remove"><span class="glyphicon glyphicon-trash"></span></a></center>
                                            </td>
                                        </tr>
                                        <?php if ($datos_form['perfil'] == 1) { // administrador comunidad 
                                        ?>
                                            <?php $i = 1; ?>
                                            <?php foreach ($listado_comunidades as $comunidad_sel) { ?>

                                                <tr id='addr<?php echo $i; ?>' data-id="<?php echo $i; ?>" class="delement">
                                                    <td class="form-group" data-name="comunidad-">
                                                        <!-- DEFINE EL NOMBRE QUE TENDRAN LOS ELEMENTOS CREADOS -->
                                                        <select name="comunidad-<?php echo $i; ?>" id="comunidad-<?php echo $i; ?>" class="form-control comunidad">
                                                            <option value="">Seleccione Comunidad</option>
                                                            <?php foreach ($comunidades as $comunidad) { ?>
                                                                <?php $comunidadselected = $comunidad->id == $comunidad_sel ? "selected" : ""; ?>
                                                                <?php $muestracomunidad = $this->session->userdata('comunidadid') == '' || $this->session->userdata('comunidadid') == $comunidad->id ? true : false; ?>

                                                                <?php if ($muestracomunidad) { ?>
                                                                    <option value="<?php echo $comunidad->id; ?>" <?php echo $comunidadselected; ?>><?php echo $comunidad->nombre; ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td class="form-group" data-name="propiedad-">
                                                        <select name="propiedad-<?php echo $i; ?>" id="propiedad-<?php echo $i; ?>" class="form-control propiedad" disabled>
                                                            <option value="">Seleccione Propiedad</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="del" class="form-group">
                                                        <center><a href="#" data-toggle="tooltip" title="Eliminar" class="row-remove"><span class="glyphicon glyphicon-trash"></span></a></center>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php } ?>
                                        <?php } else if ($datos_form['perfil'] == 3 || $datos_form['perfil'] == 2) { ?>
                                            <?php $i = 1; ?>
                                            <?php foreach ($listado_propiedades as $propiedad_sel) { ?>

                                                <tr id='addr<?php echo $i; ?>' data-id="<?php echo $i; ?>" class="delement">
                                                    <td class="form-group" data-name="comunidad-">
                                                        <!-- DEFINE EL NOMBRE QUE TENDRAN LOS ELEMENTOS CREADOS -->
                                                        <select name="comunidad-<?php echo $i; ?>" id="comunidad-<?php echo $i; ?>" class="form-control comunidad">
                                                            <option value="">Seleccione Comunidad</option>
                                                            <?php foreach ($comunidades as $comunidad) { ?>
                                                                <?php $comunidadselected = $comunidad->id == $propiedad_sel['idcomunidad'] ? "selected" : ""; ?>
                                                                <?php $muestracomunidad = $this->session->userdata('comunidadid') == '' || $this->session->userdata('comunidadid') == $comunidad->id ? true : false; ?>

                                                                <?php if ($muestracomunidad) { ?>
                                                                    <option value="<?php echo $comunidad->id; ?>" <?php echo $comunidadselected; ?>><?php echo $comunidad->nombre; ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td class="form-group" data-name="propiedad-">
                                                        <select name="propiedad-<?php echo $i; ?>" id="propiedad-<?php echo $i; ?>" class="form-control propiedad" disabled>
                                                            <option value="">Seleccione Propiedad</option>
                                                        </select>
                                                        <input type="hidden" id="idpropiedad-<?php echo $i; ?>" value="<?php echo $propiedad_sel['idpropiedad']; ?>">
                                                    </td>
                                                    <td data-name="del" class="form-group">
                                                        <center><a href="#" data-toggle="tooltip" title="Eliminar" class="row-remove"><span class="glyphicon glyphicon-trash"></span></a></center>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php } ?>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div><!-- /.box-body -->
                            <input type="hidden" name="iduser" value="<?php echo $datos_form['iduser']; ?>">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Guardar Usuario</button>
                                &nbsp;&nbsp;
                                <a href="<?php echo base_url(); ?>admins/admin_users" class="btn btn-default">Volver</a>
                            </div>

                        </div><!-- /.box -->
                    </div>
                </div>
            </form>
        </section><!-- /.content -->

        <script>
            $("#tab_logic").on('change', '.comunidad', function(event) {
                var select_comunidad = $(this);

                if ($('#perfil').val() == 3 || $('#perfil').val() == 2) { // si es propietario o comité, debo mostrar propiedades
                    $.get("<?php echo base_url(); ?>admins/get_propiedades_by_comunidad/" + $(this).val(), function(data) {

                        var elem_id = select_comunidad.attr('id');

                        var array_elem = elem_id.split("-");

                        // Limpiamos el select
                        $('#propiedad-' + array_elem[1] + ' option').remove();


                        $('#propiedad-' + array_elem[1]).append('<option value="">Seleccione Propiedad</option>');
                        if (select_comunidad.val() != '') { // solo en caso de seleccionar una comunidad
                            var_json = $.parseJSON(data);
                            for (i = 0; i < var_json.length; i++) {
                                $('#propiedad-' + array_elem[1]).append('<option value="' + var_json[i].id + '">' + var_json[i].numero + '</option>');
                            }
                        }
                        //$('#basicBootstrapForm').formValidation('revalidateField', 'propiedad');
                    });
                }
            });



            $("#perfil").on('change', function(event) {
                $(".comunidad").prop('selectedIndex', 0);
                $(".propiedad").prop('selectedIndex', 0);
                if ($(this).val() == 1) {
                    // $('#basicBootstrapForm').formValidation('updateStatus', 'propiedad','NOT_VALIDATED'); //quita validacion
                    $(".comunidad").prop("disabled", false);
                    $(".propiedad").prop("disabled", true);
                    $("#add_row").prop("disabled", false);
                    // $('#basicBootstrapForm').formValidation('revalidateField', 'comunidad');
                } else if ($(this).val() == 3 || $(this).val() == 2) {
                    $(".comunidad").prop("disabled", false);
                    $(".propiedad").prop("disabled", false);
                    $("#add_row").prop("disabled", false);
                    // $('#basicBootstrapForm').formValidation('revalidateField', 'comunidad');
                } else if ($(this).val() == 4) {
                    // $('#basicBootstrapForm').formValidation('updateStatus', 'comunidad','NOT_VALIDATED'); //quita validacion
                    // $('#basicBootstrapForm').formValidation('updateStatus', 'propiedad','NOT_VALIDATED'); //quita validacion
                    $(".comunidad").prop("disabled", true);
                    $(".propiedad").prop("disabled", true);
                    $("#add_row").prop("disabled", true);
                    $("#tab_logic tr.delement").remove();
                }
            });
        </script>



        <script>
            $(document).ready(function() {

                if ($('#perfil').val() == 1) {
                    $(".comunidad").prop("disabled", false);
                    $(".propiedad").prop("disabled", true);

                    $.each($("#tab_logic tr"), function() {
                        $(this).find("td a.row-remove").on("click", function() {
                            $(this).closest("tr").remove();
                        });
                    });

                } else if ($('#perfil').val() == 3 || $('#perfil').val() == 2) {
                    $(".comunidad").prop("disabled", false);
                    $(".propiedad").prop("disabled", false);
                    $.each($(".comunidad"), function() {
                        var select_comunidad = $(this);
                        $.get("<?php echo base_url(); ?>admins/get_propiedades_by_comunidad/" + $(this).val(), function(data) {
                            var elem_id = select_comunidad.attr('id');
                            var array_elem = elem_id.split("-");

                            // Limpiamos el select
                            $('#propiedad-' + array_elem[1] + ' option').remove();


                            $('#propiedad-' + array_elem[1]).append('<option value="">Seleccione Propiedad</option>');
                            if (select_comunidad.val() != '') { // solo en caso de seleccionar una comunidad
                                var_json = $.parseJSON(data);
                                for (i = 0; i < var_json.length; i++) {
                                    $('#propiedad-' + array_elem[1]).append('<option value="' + var_json[i].id + '">' + var_json[i].numero + '</option>');
                                }
                            }

                            $("#propiedad-" + array_elem[1]).val($('#idpropiedad-' + array_elem[1]).val());
                        });

                        /*if (parseInt($(this).data("id")) > newid) {
                            newid = parseInt($(this).data("id"));
                        }*/



                    });

                    $.each($("#tab_logic tr"), function() {
                        $(this).find("td a.row-remove").on("click", function() {
                            $(this).closest("tr").remove();
                        });
                    });




                } else if ($('#perfil').val() == 4) {
                    $(".comunidad").prop("disabled", true);
                    $(".propiedad").prop("disabled", true);
                }


                /********************** TABLA DINAMICA **********/


                $("#add_row").on("click", function() {

                    if ($('#perfil').val() != 4) { // debe ser distinto a administrador de sistemas
                        // Dynamic Rows Code

                        // Get max row id and set new id
                        var newid = 0;
                        $.each($("#tab_logic tr"), function() {
                            if (parseInt($(this).data("id")) > newid) {
                                newid = parseInt($(this).data("id"));
                            }
                        });
                        newid++;

                        var tr = $("<tr></tr>", {
                            id: "addr" + newid,
                            "data-id": newid,
                            class: 'delement'
                        });

                        // loop through each td and create new elements with name of newid
                        $.each($("#tab_logic tbody tr:nth(0) td"), function() {
                            var cur_td = $(this);

                            var children = cur_td.children();

                            // add new td and element if it has a nane
                            if ($(this).data("name") != undefined) {
                                var td = $("<td></td>", {
                                    "data-name": $(cur_td).data("name"),
                                    class: 'form-group'
                                });

                                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                                c.attr("name", $(cur_td).data("name") + newid);
                                c.attr("id", $(cur_td).data("name") + newid);
                                c.appendTo($(td));
                                td.appendTo($(tr));
                            } else {
                                var td = $("<td></td>", {
                                    'text': $('#tab_logic tr').length
                                }).appendTo($(tr));
                            }
                        });

                        // add delete button and td
                        /*
                        $("<td></td>").append(
                            $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                                .click(function() {
                                    $(this).closest("tr").remove();
                                })
                        ).appendTo($(tr));
                        */

                        // add the new row
                        $(tr).appendTo($('#tab_logic'));
                        $(tr).find("td a.row-remove").on("click", function() {
                            $(this).closest("tr").remove();
                        });
                    }


                });




                // Sortable Code
                var fixHelperModified = function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();

                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width())
                    });

                    return $helper;
                };

                /* $(".table-sortable tbody").sortable({
                     helper: fixHelperModified
                 }).disableSelection();*/

                //$(".table-sortable thead").disableSelection();



                $("#add_row").trigger("click");



                /***************************************************/

                $('#basicBootstrapForm').formValidation({
                        framework: 'bootstrap',
                        excluded: ':disabled',
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

                            apellido: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Apellido es requerido'
                                    }
                                }
                            },

                            email: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Email Propiedad es requerido'
                                    },
                                    emailAddress: {
                                        message: 'El valor ingresado no es una direcci&oacute; de email valida'
                                    },
                                    blank: {}
                                }
                            },

                            perfil: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Perfil es requerido'
                                    }
                                }
                            },


                            password: {
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Password es requerido'
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
                                        message: 'Confirmaci&oacute;n de Password es requerido'
                                    },
                                    identical: {
                                        field: 'password',
                                        message: 'Password y su confirmaci&oacute;n no son iguales'
                                    }
                                }
                            },


                            /*comunidad: {
                                // The children's full name are inputs with class .childFullName
                                selector: '.comunidad',
                                // The field is placed inside .col-xs-6 div instead of .form-group
                                row: '.form-group',
                                validators: {
                                    notEmpty: {
                                        message: 'Comunidad es requerida'
                                    },
                                },

                            }*/

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
                            url: '<?php echo base_url(); ?>admins/validate_email_user',
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
