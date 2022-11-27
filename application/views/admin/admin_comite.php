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
        <br>
    <?php endif; ?>
    <?php if ($permite_editar) : ?>
        <div>
            <a href="<?php echo base_url(); ?>admins/add_miembro_comite" type="submit" class="btn btn-primary">Agregar Miembro</a> <!-- Funcion pendiente -->
            &nbsp;&nbsp;
            <!-- <a href="<?php echo base_url(); ?>admins/carga_estacionamientos_visitas" type="submit" class="btn btn-success" hidden><span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Carga Masiva</a> -->
        </div>
        <br>
    <?php endif; ?>

    <div class="row">

        <div class="col-md-12">
            <div class="box  box-primary">
                <!-- /.box-header -->
                <div class="box-header">
                    <h3 class="box-title">Listado de Miembros</h3>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Cargo</th>
                                <th>Estado</th>
                                <?php if ($permite_editar) : ?>
                                    <th>&nbsp;</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($comite) > 0) { ?>
                                <?php $i = 1; ?>
                                <?php foreach ($comite as $miembro) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $miembro->first_name . ' ' . $miembro->last_name; ?></td>
                                        <td><?php echo $miembro->email; ?></td>
                                        <td><?php echo $miembro->cargo; ?></td>
                                        <td><?php echo $miembro->active === '1' ? 'Activo' : 'Inactivo'; ?></td>
                                        <?php if ($permite_editar) : ?>
                                            <td>
                                                <a href="<?php echo base_url(); ?>admins/add_miembro_comite/<?php echo $miembro->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                                                &nbsp;
                                                &nbsp;
                                                <?php if ($miembro->active === 1) : ?>
                                                    <a href="<?php echo base_url(); ?>admins/delete_miembro_comite/<?php echo $miembro->id; ?>" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php $i++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

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
