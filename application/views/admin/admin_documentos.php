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
    <div>
        <?php if ($permite_editar) : ?>
            <a href="<?php echo base_url(); ?>admins/add_documento" type="submit" class="btn btn-primary">Agregar documento</a>
            &nbsp;&nbsp;
            <!-- <a href="<?php echo base_url(); ?>admins/carga_estacionamientos_visitas" type="submit" class="btn btn-success" hidden><span class="glyphicon glyphicon-upload"></span>&nbsp;&nbsp;Carga Masiva</a> -->
        <?php endif; ?>
        <a href="<?php echo base_url(); ?>admins/historial_documentos" type="submit" class="btn btn-primary">Historial</a>
    </div>
    <br>

    <div class="row">

        <div class="col-md-12">
            <div class="box  box-primary">
                <!-- /.box-header -->
                <div class="box-header">
                    <h3 class="box-title">Listado de documentos</h3>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Documento</th>
                                <th>Fecha Creacion</th>
                                <th>Fecha Actualizacion</th>
                                <?php if ($permite_editar) : ?>
                                    <th>&nbsp;</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($documentos) > 0) { ?>
                                <?php $i = 1; ?>
                                <?php foreach ($documentos as $documento) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $documento->tipo; ?></td>
                                        <td><?php echo $documento->descripcion; ?></td>
                                        <td><a target="_blank" href="<?php echo base_url() . $documento->path; ?>"><span class="glyphicon glyphicon-download"></span></a></td>
                                        <td><?php echo $documento->created_at; ?></td>
                                        <td><?php echo $documento->updated_at; ?></td>
                                        <?php if ($permite_editar) : ?>
                                            <td>
                                                <a href="<?php echo base_url(); ?>admins/add_documento/<?php echo $documento->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                                                &nbsp;
                                                &nbsp;
                                                <a href="<?php echo base_url(); ?>admins/delete_documento/<?php echo $documento->id; ?>" data-toggle="tooltip" title="Archivar"><span class="glyphicon glyphicon-folder-open"></span></a>
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
