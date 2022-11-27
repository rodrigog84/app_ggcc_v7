        <!-- Main content -->
        <section class="content invoice">
          <!-- title row -->

          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                <i class="fa fa-globe"></i> Perfil Usuario.
                <small class="pull-right">Fecha: <?php echo date("d/m/Y");?></small>
              </h2>
            </div><!-- /.col -->
          </div>
          <!-- info row -->
          <form class="text-center" action="<?php echo base_url();?>admins/submit_profile" method="post" enctype="multipart/form-data">
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
              Datos Usuario
              <address>
                <strong><?php echo $user->nombre;?></strong><br>
                Email: <?php echo $user->email;?><br>
                Perfil: <?php echo $user->levelname;?><br>
              </address>
            </div><!-- /.col -->
            <div class="col-sm-4 col-sm-offset-4 invoice-col">
              <!-- the avatar markup -->
              <div id="kv-avatar-errors" class="center-block" style="width:800px;display:none"></div>
              <div class="kv-avatar center-block" style="width:200px">
                  <input id="avatar" name="avatar" type="file" class="file-loading">
              </div>              
            </div><!-- /.col -->
          </div><!-- /.row -->

          <!-- Table row -->
          <div class="row">
            <div class="col-xs-6 table-responsive">
              <table class="table table-striped">
              <?php if($user->level == 1){ ?>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Comunidades Asociadas</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                <?php foreach ($listado_comunidades as $comunidad_sel) { ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $comunidad_sel; ?></td>
                  </tr>
                  <?php $i++; ?>
                <?php } ?>
                </tbody>
              <?php }else if($user->level == 3){ ?>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Comunidades Asociadas</th>
                    <th>Propiedades Asociadas</th>
                  </tr>
                </thead>
                <tbody>                
                <?php $i = 1; ?>
                <?php foreach ($listado_propiedades as $propiedad_sel) { ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $propiedad_sel['comunidad']; ?></td>
                    <td><?php echo $propiedad_sel['propiedadnumero']; ?></td>
                  </tr>
                  <?php $i++; ?>
                <?php } ?>
                </tbody>
              <?php } ?>
              </table>
            </div><!-- /.col -->
          </div><!-- /.row -->
         
          <!-- this row will not appear when printing -->
          <input type="hidden" name="userid" id="userid" value="<?php echo $user->id;?>" />
          <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-success pull-right">Actualizar</button>
            </div>
          </div>
           </form>
        </section><!-- /.content -->


<style>
.kv-avatar .file-preview-frame,.kv-avatar .file-preview-frame:hover {
    margin: 0;
    padding: 0;
    border: none;
    box-shadow: none;
    text-align: center;
}
.kv-avatar .file-input {
    display: table-cell;
    max-width: 220px;
}
</style>        

<script>
var btnCust = '<button type="button" class="btn btn-default" title="Add picture tags" ' + 
    'onclick="alert(\'Call your custom code here.\')">' +
    '<i class="glyphicon glyphicon-tag"></i>' +
    '</button>'; 
$("#avatar").fileinput({
    overwriteInitial: true,
    maxFileSize: 1500,
    showClose: false,
    showCaption: false,
    browseLabel: '',
    removeLabel: '',
    browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
    removeTitle: 'Volver a imagen inicial',
    elErrorContainer: '#kv-avatar-errors',
    msgErrorClass: 'alert alert-block alert-danger',
    defaultPreviewContent: '<img src="<?php echo $path_photo;?>" alt="Your Avatar" style="width:160px">',
    layoutTemplates: {main2: '{preview} ' + ' {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png"]
});
</script>