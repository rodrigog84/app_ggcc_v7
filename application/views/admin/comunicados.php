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
            <div>
            </div>
            <?php if ($agrega) { ?>
                <a href="<?php echo base_url(); ?>admins/add_comunicado" type="submit" class="btn btn-primary">Agregar Comunicado</a>
            <?php } ?>
            <br>
            <br>

            <div class="row">

                <div class="col-md-12">
                    <div class="box  box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Listado de Comunicados</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <table id="listado" class="table table-bordered table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titulo</th>
                                        <th>Texto</th>
                                        <?php if ($agrega) { ?>
                                            <th>Fecha Solicitud Env&iacute;o</th>
                                        <?php } ?>
                                        <th>Fecha Env&iacute;o</th>
                                        <?php if ($agrega) { ?>
                                            <th>Estado</th>
                                        <?php } ?>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($comunicados) > 0) { ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($comunicados as $comunicado) { ?>
                                            <?php
                                            if ($agrega) {
                                                $mostrar = true;
                                            } else {
                                                $mostrar =  $comunicado->estadoid == 3 ? true : false;
                                            }

                                            ?>

                                            <?php if ($mostrar) { ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $comunicado->titulo; ?></td>
                                                    <td><?php echo substr(strip_tags($comunicado->txt_comunicado), 0, 30); ?></td>
                                                    <?php if ($agrega) { ?>
                                                        <td><?php echo $comunicado->fec_marca_envio; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $comunicado->fec_envio; ?></td>

                                                    <?php

                                                    if ($comunicado->estadoid == 1) {
                                                        $class_estado = 'primary';
                                                    } else if ($comunicado->estadoid == 2) {
                                                        $class_estado = 'warning';
                                                    } else if ($comunicado->estadoid == 3) {
                                                        $class_estado = 'success';
                                                    } else {
                                                        $class_estado = 'info';
                                                    }


                                                    ?>
                                                    <?php if ($agrega) { ?>
                                                        <td><span class="label label-<?php echo $class_estado; ?>"><?php echo $comunicado->estado; ?></span></td>
                                                    <?php } ?>
                                                    <td>
                                                        <?php if ($comunicado->estadoid == 1) { ?>
                                                            <a href="<?php echo base_url(); ?>admins/add_comunicado/<?php echo $comunicado->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>

                                                            &nbsp;
                                                            &nbsp;
                                                            <a href="<?php echo base_url(); ?>admins/delete_comunicado/<?php echo $comunicado->id; ?>" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></a>
                                                            &nbsp;
                                                            &nbsp;
                                                            <a href="#" data-href="<?php echo base_url(); ?>admins/send_comunicado/<?php echo $comunicado->id; ?>" title="Publicar" data-toggle="modal" data-target="#confirm-send"><span class="fa fa-envelope-o"></span></a>
                                                        <?php } else if ($comunicado->estadoid == 2) { ?>
                                                            <a href="<?php echo base_url(); ?>admins/anular_envio_comunicado/<?php echo $comunicado->id; ?>" data-toggle="tooltip" title="Anular Env&iacute;o">Anular</span></a>
                                                        <?php } else if ($comunicado->estadoid == 3) { ?>
                                                            <a href="<?php echo base_url(); ?>admins/ver_envio_comunicado/<?php echo $comunicado->id; ?>" data-toggle="tooltip" title="Ver Comunicado Enviado"><span class="glyphicon glyphicon-search"></span></a>

                                                        <?php } ?>

                                                    </td>
                                                </tr>

                                                <?php $i++; ?>

                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div>
                </div>


            </div>
        </section><!-- /.content -->
        <div class="modal fade" id="confirm-send" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Confirmar Env&iacute;o</h4>
                    </div>

                    <div class="modal-body">
                        <p>Su comunicado ser&aacute; enviado en los pr&oacute;ximos 10 minutos. Desea continuar?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <a class="btn btn-success btn-ok">Enviar</a>
                    </div>
                </div>
            </div>
        </div>


        <script>
            $('#confirm-send').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

            });
        </script>

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
        </script>
