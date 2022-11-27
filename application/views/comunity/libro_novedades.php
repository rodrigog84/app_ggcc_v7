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
    <!-- Agrega Novedad -->
    <div>
        <?php if ($permite_editar) : ?>
            <a href="<?php echo base_url(); ?>comunity/add_bitacora" type="submit" class="btn btn-primary">Agregar Bitacora</a>
        <?php endif; ?>
        <a href="<?php echo base_url(); ?>comunity/historial_novedades" type="submit" class="btn btn-primary"><?php echo $this->session->userdata('level') != 1 ? 'Historial' : 'Pendientes de Traspaso' ?></a>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="box  box-primary">
                <!-- /.box-header -->
                <div class="box-header">
                    <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="listado" class="table table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Accion</th>
                                <th>Descripcion</th>
                                <th>Fecha Ocurrencia</th>
                                <?php if ($this->session->userdata('level') === '1') { ?>
                                    <th>Fecha Archivado</th>
                                <?php } else { ?>
                                    <th>Fecha Actualizacion</th>
                                <?php } ?>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($bitacoras) > 0) { ?>
                                <?php $i = 1; ?>
                                <?php foreach ($bitacoras as $bitacora) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $bitacora->nombre . ' ' . $bitacora->apellidos; ?></td>
                                        <td><?php echo $bitacora->accion; ?></td>
                                        <td><?php echo substr(strip_tags($bitacora->descripcion), 0, 30); ?></td>
                                        <td><?php echo $bitacora->created_at; ?></td>
                                        <?php if ($this->session->userdata('level') === '1') { ?>
                                            <td><?php echo $bitacora->archived_at; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $bitacora->updated_at; ?></td>
                                        <?php } ?>
                                        <!-- Edita y borra bitacora -->
                                        <td>
                                            <a href="#" data-fecha="<?php echo $bitacora->created_at; ?>" data-accion="<?php echo $bitacora->accion; ?>" data-descripcion="<?php echo $bitacora->descripcion; ?>" data-nombre="<?php echo $bitacora->nombre . ' ' . $bitacora->apellidos; ?>" data-toggle="modal" data-target="#read-bitacora"><span class="glyphicon glyphicon-file"></span></a>
                                            <?php if ($permite_editar && $bitacora->iduser == $this->session->userdata('user_id')) : ?>
                                                &nbsp;
                                                &nbsp;
                                                <a href="<?php echo base_url(); ?>comunity/add_bitacora/<?php echo $bitacora->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                                                &nbsp;
                                                &nbsp;
                                                <a href="<?php echo base_url(); ?>comunity/archive_bitacora/<?php echo $bitacora->id; ?>" data-toggle="tooltip" title="Archivar"><span class="glyphicon glyphicon-folder-open"></span></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Lectura Bitacora -->
<div class="modal fade" id="read-bitacora" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Bitacora <small>por </small><small id="nombre"></small></h4>
            </div>
            <div class="modal-body">
                <div class="text-center" id="accion"></div>
                <div class="text-center" id="fecha"></div>
                <br><br>
                <div id="descripcion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Volver</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#read-bitacora').on('show.bs.modal', function(e) {
        document.getElementById('nombre').innerHTML = $(e.relatedTarget).data('nombre');
        document.getElementById('accion').innerHTML = $(e.relatedTarget).data('accion');
        document.getElementById('descripcion').innerHTML = $(e.relatedTarget).data('descripcion');
        document.getElementById('fecha').innerHTML = $(e.relatedTarget).data('fecha');
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
