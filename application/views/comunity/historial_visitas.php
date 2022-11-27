<!-- Main content -->
<section class="content">
    <!-- Mensaje -->
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
    <!-- Limpiar Historial -->
    <?php if ($permite_editar) : ?>
        <div>
            <a href="#" data-href="<?php echo base_url(); ?>comunity/clean_historial_visitas" class="btn btn-primary" data-toggle="modal" data-target="#confirm-clean">Limpiar Historial</a>
        </div>
        <br>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box  box-primary">
                <!-- /.box-header -->
                <div class="box-header">
                    <h3 class="box-title">Listado de Estacionamientos</h3>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Rut</th>
                                <th>Propiedad</th>
                                <th>Estacionamiento</th>
                                <th>Patente Vehiculo</th>
                                <th>Entrada</th>
                                <?php if ($this->session->userdata('level') != 1) : ?>
                                    <th>Salida</th>
                                <?php endif; ?>
                                <?php if ($permite_editar) : ?>
                                    <th>&nbsp;&nbsp;</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($registros) > 0) { ?>
                                <?php $i = 1; ?>
                                <?php foreach ($registros as $registro) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $registro->nombre; ?></td>
                                        <td><?php echo $registro->apellidos; ?></td>
                                        <td><?php echo number_format($registro->rut, 0, ".", ".") . "-" . $registro->dv ?></td>
                                        <td><?php echo $registro->propiedad; ?></td>
                                        <td><?php echo $registro->estacionamiento; ?></td>
                                        <td><?php echo $registro->patente; ?></td>
                                        <td><?php echo $registro->entrada; ?></td>
                                        <?php if ($this->session->userdata('level') != 1) : ?>
                                            <td><?php echo $registro->salida; ?></td>
                                        <?php endif; ?>
                                        <?php if ($permite_editar) : ?>
                                            <td>
                                                <a href="#" data-href="<?php echo base_url(); ?>comunity/delete_registro_visita/<?php echo $registro->id; ?>" title="Eliminar" data-toggle="modal" data-target="#confirm-delete"><span class="glyphicon glyphicon-trash"></span></a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php $i++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>comunity/libro_visitas" class="btn btn-default">Volver</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal confirma eliminar registro -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmar Eliminar</h4>
            </div>
            <div class="modal-body">
                <p>La bitacora se eliminara definitivamente. Desea continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal confirma limpiar Historial -->
<div class="modal fade" id="confirm-clean" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmar Eliminar</h4>
            </div>
            <div class="modal-body">
                <p>El Historial se eliminara definitivamente. Desea continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

    });
    $('#confirm-clean').on('show.bs.modal', function(e) {
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
