<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $titulo; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_fondo" id="basicBootstrapForm" method="post">
                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="comunidad">Comunidad</label>
                                    <?php
                                    //$this->session->userdata('id') 
                                    ?>
                                    <?php if ($permite_editar) { ?>
                                        <select name="comunidad" id="comunidad" class="form-control">
                                            <option value="">Seleccione Comunidad</option>
                                            <?php foreach ($comunidades as $comunidad) { ?>
                                                <?php $comunidadselected = $comunidad->id == $datos_form['idcomunidad'] ? "selected" : ""; ?>
                                                <option value="<?php echo $comunidad->id; ?>" <?php echo $comunidadselected; ?>><?php echo $comunidad->nombre; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input type="text" class="form-control" value="<?php echo $this->session->userdata('comunidadnombre'); ?>" readonly>
                                        <input type="hidden" id="comunidad" name="comunidad" value="<?php echo $this->session->userdata('comunidadid'); ?>">

                                    <?php } ?>
                                </div>
                            </div>

                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="nombre">Nombre Fondo</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese Nombre Fondo" value="<?php echo $datos_form['nombre']; ?>">
                                </div>
                            </div>


                        </div>

                    </div><!-- /.box-body -->
                    <input type="hidden" name="idfondo" value="<?php echo $datos_form['idfondo']; ?>">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Agregar</button>
                        &nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>admins/admin_fondos" class="btn btn-default">Volver</a>
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
                comunidad: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Comunidad es requerido'
                        }
                    }
                },

                nombre: {
                    row: '.form-group',
                    validators: {
                        notEmpty: {
                            message: 'Nombre Estacionamiento es requerido'
                        }
                    }
                },

            }
        })

    });
</script>
