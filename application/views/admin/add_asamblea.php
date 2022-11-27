<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <!--<h3 class="box-title"><?php echo $titulo; ?></h3>-->
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_asamblea" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="tipo_asamblea">Tipo asamblea</label>
                                    <select name="tipo_asamblea" id="tipo_asamblea" class="form-control">
                                        <option value="">Seleccione Tipo de Asamblea</option>
                                        <?php foreach ($tipos_asamblea as $tipo) { ?>
                                            <?php $selected = $datos_form['idtipoasamblea'] === $tipo->id ? 'selected' : ''; ?>
                                            <option value="<?php echo $tipo->id; ?>" <?php echo $selected; ?>><?php echo $tipo->tipo; ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <input type="hidden" id="idcargo" value="<?php echo $datos_form['idpropiedad']; ?>"> -->
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="asunto">Asunto</label>
                                    <input type="text" id="asunto" name="asunto" class="form-control" placeholder="Descripción breve o motivo de la asamblea" value="<?php echo $datos_form['asunto']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo $datos_form['fecha']; ?>">
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="documento">Subir documento</label>
                                    <input type="file" id="documento" name="documento" class="form-control" accept=".xlsx, .xls, image/*, .doc, .docx, .ppt, .pptx, .txt, .pdf">
                                    <?php if ($idasamblea > 0) { ?>
                                        <span><strong>Nota: </strong>Subir un archivo, reemplazará el existente</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <input type="hidden" name="idasamblea" id="idasamblea" value="<?php echo $idasamblea; ?>">
                        <button type="submit" class="btn btn-success">Agregar</button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>admins/admin_asambleas" class="btn btn-default">Volver</a>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section><!-- /.content -->
<script>
    const minFecha = new Date().getTime() - (1 * 24 * 60 * 60 * 1000);
    const maxFecha = new Date().getTime() + (2 * 365 * 24 * 60 * 60 * 1000);
    $(document).ready(function() {
        $('#basicBootstrapForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                tipo_asamblea: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Tipo de Asamblea es requerido'
                        }
                    }
                },

                asunto: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Asunto es requerido'
                        }
                    }
                },

                fecha: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Fecha es requerida'
                        },
                        date: {
                            message: 'Campo incorrecto. Debe ser una fecha valida',
                            min: new Date(minFecha),
                            max: new Date(maxFecha),
                        }
                    }
                },

                documento: {
                    row: '.form-group',
                    validators: {
                        file: {
                            maxSize: 9900000,
                            message: 'Por favor, elija un archivo valido'
                        },
                    }
                },
            }
        })

    });
</script>
