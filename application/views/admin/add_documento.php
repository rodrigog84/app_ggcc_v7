<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <!--<h3 class="box-title"><?php echo $titulo; ?></h3>-->
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_documento" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="tipo_documento">Tipo documento</label>
                                    <select name="tipo_documento" id="tipo_documento" class="form-control">
                                        <option value="">Seleccione Tipo de Asamblea</option>
                                        <?php foreach ($tipos_documento as $tipo) { ?>
                                            <?php $selected = $datos_form['idtipodocumento'] === $tipo->id ? 'selected' : ''; ?>
                                            <option value="<?php echo $tipo->id; ?>" <?php echo $selected; ?>><?php echo $tipo->tipo; ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <input type="hidden" id="idcargo" value="<?php echo $datos_form['idpropiedad']; ?>"> -->
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Descripción breve o nombre de documento" value="<?php echo $datos_form['descripcion']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="documento">Subir documento</label>
                                    <input type="file" id="documento" name="documento" class="form-control" accept=".xlsx, .xls, image/*, .doc, .docx, .ppt, .pptx, .txt, .pdf">
                                    <?php if ($iddocumento > 0) { ?>
                                        <span><strong>Nota: </strong>Subir un archivo, reemplazará el existente</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <input type="hidden" name="iddocumento" id="iddocumento" value="<?php echo $iddocumento; ?>">
                        <button type="submit" class="btn btn-success">Agregar</button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>admins/admin_documentos" class="btn btn-default">Volver</a>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section><!-- /.content -->
<script>
    $(document).ready(function() {
        $('#basicBootstrapForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                tipo_documento: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Tipo de Documento es requerido'
                        }
                    }
                },

                descripcion: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Descripción es requerida'
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
