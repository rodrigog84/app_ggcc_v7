<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box  box-primary">
                <!-- /.box-header -->
                <div class="box-header">
                    <h3 class="box-title">Listado de Asambleas</h3>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Asunto</th>
                                <th>Fecha</th>
                                <th>Documento</th>
                                <th>Fecha Archivado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($asambleas) > 0) { ?>
                                <?php $i = 1; ?>
                                <?php foreach ($asambleas as $asamblea) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $asamblea->tipo; ?></td>
                                        <td><?php echo $asamblea->asunto; ?></td>
                                        <td><?php echo $asamblea->fecha; ?></td>
                                        <td><a target="_blank" href="<?php echo base_url() . $asamblea->path; ?>"><span class="glyphicon glyphicon-download"></span></a></td>
                                        <td><?php echo $asamblea->archived_at; ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>admins/admin_asambleas" class="btn btn-default">Volver</a>
                </div>
            </div>
        </div>
    </div>
</section>

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
