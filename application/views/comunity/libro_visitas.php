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
    <!-- Agrega Registro -->
    <div>
        <?php if ($permite_editar) : ?>
            <a href="<?php echo base_url(); ?>comunity/add_registro_visita" type="submit" class="btn btn-primary">Agregar Registro Visita</a>
        <?php endif; ?>
        <a href="<?php echo base_url(); ?>comunity/historial_visitas" type="submit" class="btn btn-primary"><?php echo $this->session->userdata('level') == 1 ? 'Pendientes de Traspaso' : 'Historial' ?></a>
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
                                <th>Apellidos</th>
                                <th>Rut</th>
                                <th>Propiedad</th>
                                <th>Estacionamiento</th>
                                <th>Patente Vehiculo</th>
                                <th>Entrada</th>
                                <?php if ($this->session->userdata('level') === '1') { ?>
                                    <th>Salida</th>
                                <?php } ?>
                                <?php if ($permite_editar) : ?>
                                    <th>Acciones</th>
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
                                        <?php if ($this->session->userdata('level') == 1) : ?>
                                            <td><?php echo $registro->salida; ?></td>
                                        <?php endif; ?>
                                        <!-- Edita registro y marcha salida -->
                                        <?php if ($permite_editar) : ?>
                                            <td>
                                                <a href="<?php echo base_url(); ?>comunity/add_registro_visita/<?php echo $registro->id; ?>" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                                                &nbsp;
                                                &nbsp;
                                                <a href="<?php echo base_url(); ?>comunity/add_salida_visita/<?php echo $registro->id; ?>" data-toggle="tooltip" title="Marcar Salida"><span class="glyphicon glyphicon-ok"></span></a>
                                            </td>
                                        <?php endif; ?>
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
