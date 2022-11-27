<section class="content invoice">
    <div class="row">
        <div class="col-12">
            <h2 class="page-header"><strong>¡Bienvenid@! </strong><span><?php echo $personal->nombre . " " . $personal->apaterno . " " . $personal->amaterno; ?></span></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 text-center">
            <h2><strong>Comunidad</strong></h2>
            <span><?php echo $this->session->userdata('comunidadnombre'); ?></span>
        </div>
        <div class="col-xs-6 text-center">
            <h2><strong>Cargo</strong></h2>
            <span><?php echo $personal->cargo; ?></span>
        </div>
        <a href="<?php echo base_url(); ?>main/destroy_data_session" class="btn btn-app">
            <i class="fa fa-repeat"></i> Cambiar Comunidad
        </a>
    </div>
</section>
<style>
    .col-xs-6 span {
        font-size: 1.4em;
    }

    .col-xs-6 h2 {
        border-bottom: 1px solid #000;
        margin: 20px 80px;
    }

    .btn-app {
        margin-top: 20px;
    }
</style>
