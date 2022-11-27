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
            <?php if ($permite_editar) : ?>
                <div>
                    <a href="<?php echo base_url(); ?>admins/add_bodega" type="submit" class="btn btn-primary">Agregar Bodega</a>
                    &nbsp;&nbsp;
                    <a href="<?php echo base_url(); ?>admins/carga_bodegas" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Carga Masiva</a>
                </div>
                <br>
            <?php endif; ?>

            <div class="row">

                <div class="col-md-12">
                    <div class="box  box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Listado de Bodegas</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <table id="listado" class="table table-bordered table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Bodega</th>
                                        <th>Comunidad</th>
                                        <th>Propiedad</th>
                                        <th>Prorrateo</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($bodegas) > 0) { ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($bodegas as $bodega) { ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $bodega->nombre; ?></td>
                                                <td><?php echo $bodega->comunidad; ?></td>
                                                <td><?php echo $bodega->propiedad; ?></td>
                                                <td><?php echo $bodega->prorrateo . "%"; ?></td>
                                                <td>&nbsp;</td>
                                                <?php if ($permite_editar) : ?>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>admins/add_bodega/<?php echo $bodega->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                                                        &nbsp;
                                                        &nbsp;
                                                        <a href="<?php echo base_url(); ?>admins/delete_bodega/<?php echo $bodega->id; ?>" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
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
        </script>
