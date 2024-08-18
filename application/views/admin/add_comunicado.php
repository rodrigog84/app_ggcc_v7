        <!-- Main content -->
        <section class="content">

            <div class="row">

                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $datos_comunicado['txt_encabeza']; ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="basicBootstrapForm" action="<?php echo base_url(); ?>admins/submit_comunicados" method="post" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="row">
                                    <div class='col-md-12'>
                                        <div class="form-group">
                                            <label for="documento">T&iacute;tulo</label>
                                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Agregue un T&iacute;tulo" value="<?php echo $datos_comunicado['titulo']; ?>" <?php echo $permite ? '' : 'readonly'; ?>>
                                        </div>
                                    </div>



                                    <!-- PROBAR NUEVO -->

                                    <div class='col-md-12'>
                                        <div class="form-group">
                                            <label for="documento">&nbsp;</label>
                                            <textarea class="textarea" id="txt_comunicado" name="txt_comunicado" placeholder="Agrega Texto Aqu&iacute;" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $datos_comunicado['txt_comunicado']; ?>

                        </textarea>

                                        </div>
                                    </div>

                                </div>



                                <div class="row">
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                                <div class="form-group">
                                                  <label for="exampleInputFile">Adjuntar Archivos Comunicado</label>
                                                  <input type="file" id="userfile" name="userfile[]" accept=".pdf, image/*" multiple>
                                                </div>
                                        </div> 
                                    </div>

                                </div>

                                 <div class="row">
                                    <div class='col-md-6'>
                                <?php if(count($archivos_comunicados) > 0){ ?>

                                  <table class="table table-bordered table-striped dt-responsive">
                                  <thead>
                                    <tr>
                                      <th>Archivo</th>
                                      <th>&nbsp;</th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    <?php foreach ($archivos_comunicados as $archivo) { ?>
                                            <tr >
                                                    <td><a href='<?php echo base_url(); ?>uploads/comunicados/<?php echo $this->session->userdata('comunidadid'); ?>/<?php echo $archivo->nomtemparchivo;?>' target='_blank'><?php echo $archivo->nomarchivo;?></a></td>
                                                    <td><button data-idfile='<?php echo $archivo->id;?>' data-idcomunicado='<?php echo $archivo->idcomunicado;?>' class="btn btn-danger deleteRow" >Eliminar</button></td>
                                            </tr>
                                    

                                    <?php } ?>
                                  </tbody>
                              </table>
                                <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <input type="hidden" name="idcomunicado" id="idcomunicado" value="<?php echo $datos_comunicado['id']; ?>">
                                <?php if ($permite) { ?>
                                    <button type="submit" class="btn btn-success"><?php echo $datos_comunicado['txt_button']; ?></button>
                                    &nbsp;&nbsp;
                                <?php } ?>
                                <a href="<?php echo base_url(); ?>admins/comunicados" class="btn btn-default">Volver</a>
                            </div>
                        </form>

                    </div><!-- /.box -->

                </div>
            </div>
        </section><!-- /.content -->


<script>
    $(document).ready(function(){
        // Manejar el evento de clic en los botones "Borrar"
        $('.deleteRow').on('click', function(){
            // Encontrar la fila (<tr>) que contiene el botón clicado y eliminarla
            $(this).closest('tr').remove();
            //console.log($(this).data('idfile'))

             var idfile = $(this).data('idfile');
             var idcomunicado = $(this).data('idcomunicado');

              $.ajax({
                  type: "POST",
                  url: '<?php echo base_url();?>admins/deletefile_comunicado/',
                  dataType: 'json',
                  data : {
                          "idfile": idfile,
                          "idcomunicado" : idcomunicado
                        },
                  async: false,
              }).success(function(data) {


              });             

        });
    });
</script>

        <script type="text/javascript">
            $(function() {



                CKEDITOR.replace('txt_comunicado', {
                            <?php if (!$agrega) { ?>
                                readOnly: true,
                            <?php } ?>     
                    extraPlugins: 'easyimage',
                    removePlugins: 'image',
                    removeDialogTabs: 'link:advanced',
                              toolbar: [  
                                    <?php if ($agrega) { ?> 
                                            { name: 'clipboard', 
                                              items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] 
                                            },                      
                                            {
                                                name: 'basicstyles',
                                                items: ['Bold', 'Italic',  '-', 'RemoveFormat']
                                            },
                                             { 
                                                name: 'paragraph', 
                                                items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'BidiLtr', 'BidiRtl', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', ] }, 

                                                {
                                                    name: 'links',
                                                    items: ['Link', 'Unlink']
                                                }, 
                                                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                                {
                                                    name: 'insert',
                                                    items: ['EasyImageUpload']
                                                }
                                            <?php } ?>


                                    ],
                                height: 350,
                                cloudServices_uploadUrl: 'https://33333.cke-cs.com/easyimage/upload/',
                                // Note: this is a token endpoint to be used for CKEditor 4 samples only. Images uploaded using this token may be deleted automatically at any moment.
                                // To create your own token URL please visit https://ckeditor.com/ckeditor-cloud-services/.
                                cloudServices_tokenUrl: 'https://33333.cke-cs.com/token/dev/ijrDsqFix838Gh3wGO3F77FSW94BwcLXprJ4APSp3XQ26xsUHTi0jcb1hoBt',
                                easyimage_styles: {

                                    left: {
                                        attributes: {
                                            'class': 'left'
                                        },
                                        label: 'Align left',
                                        icon: '/my/example/icons/left.png',
                                        iconHiDpi: '/my/example/icons/hidpi/left.png'
                                    },

                                    right: {
                                        attributes: {
                                            'class': 'right'
                                        },
                                        label: 'Align right',
                                        icon: '/my/example/icons/right.png',
                                        iconHiDpi: '/my/example/icons/hidpi/right.png'
                                    },
                                    center: {
                                        attributes: {
                                            'class': 'center'
                                        },
                                        label: 'Align center',
                                        icon: '/my/example/icons/center.png',
                                        iconHiDpi: '/my/example/icons/hidpi/center.png'
                                    },
                                    gradient1: {
                                        group: 'easyimage-gradients',
                                        attributes: {
                                            'class': 'easyimage-gradient-1'
                                        },
                                        label: 'Blue Gradient',
                                        icon: 'https://sdk.ckeditor.com/samples/assets/easyimage/icons/gradient1.png',
                                        iconHiDpi: 'https://sdk.ckeditor.com/samples/assets/easyimage/icons/hidpi/gradient1.png'
                                    },
                                    gradient2: {
                                        group: 'easyimage-gradients',
                                        attributes: {
                                            'class': 'easyimage-gradient-2'
                                        },
                                        label: 'Pink Gradient',
                                        icon: 'https://sdk.ckeditor.com/samples/assets/easyimage/icons/gradient2.png',
                                        iconHiDpi: 'https://sdk.ckeditor.com/samples/assets/easyimage/icons/hidpi/gradient2.png'
                                    },
                                    noGradient: {
                                        group: 'easyimage-gradients',
                                        attributes: {
                                            'class': 'easyimage-no-gradient'
                                        },
                                        label: 'No Gradient',
                                        icon: 'https://sdk.ckeditor.com/samples/assets/easyimage/icons/nogradient.png',
                                        iconHiDpi: 'https://sdk.ckeditor.com/samples/assets/easyimage/icons/hidpi/nogradient.png'
                                    }
                                },
                                easyimage_toolbar: [
                                    'EasyImageFull',
                                    'EasyImageSide',
                                    'EasyImageAlignLeft',
                                    'EasyImageAlignRight',
                                    'EasyImageAlignCenter',
                                    'EasyImageGradient1',
                                    'EasyImageGradient2',
                                    'EasyImageNoGradient',
                                    'EasyImageAlt'
                                ]

                });


            });
            /*
                    $(".textarea").tinymce({

            // Location of TinyMCE script
             script_url : "<?php echo base_url(); ?>plugins/tiny_mce/tiny_mce.js",
             // General options
             theme : "advanced",
             plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",



             // Theme options
             theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
             theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
             theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
             theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,ibrowser",
             theme_advanced_toolbar_location : "top",
             theme_advanced_toolbar_align : "left",
             theme_advanced_statusbar_location : "bottom",
             theme_advanced_resizing : true,
             file_browser_callback : filebrowser,

             // Drop lists for link/image/media/template dialogs
             template_external_list_url : "lists/template_list.js",
             external_link_list_url : "lists/link_list.js",
             external_image_list_url : "lists/image_list.js",
             media_external_list_url : "lists/media_list.js"
             });    */


            /*CKEDITOR.replace( 'txt_comunicado', {
      extraPlugins: 'image2,uploadimage',

      toolbar: [
        { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
        { name: 'styles', items: [ 'Styles', 'Format' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
        { name: 'links', items: [ 'Link', 'Unlink' ] },
        { name: 'insert', items: [ 'Image', 'Table' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        { name: 'editing', items: [ 'Scayt' ] }
      ],

      // Configure your file manager integration. This example uses CKFinder 3 for PHP.
      filebrowserBrowseUrl: '<?php echo base_url(); ?>plugins/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: '<?php echo base_url(); ?>plugins/ckfinder/ckfinder.html?type=Images',
      filebrowserUploadUrl: '<?php echo base_url(); ?>plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: '<?php echo base_url(); ?>plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

      // Upload dropped or pasted images to the CKFinder connector (note that the response type is set to JSON).
      uploadUrl: '<?php echo base_url(); ?>plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',

      // Reduce the list of block elements listed in the Format drop-down to the most commonly used.
      format_tags: 'p;h1;h2;h3;pre',
      // Simplify the Image and Link dialog windows. The "Advanced" tab is not needed in most cases.
      removeDialogTabs: 'image:advanced;link:advanced',

      height: 300
    } );

*/
        </script>
        <script>
            $(document).ready(function() {
                $('#basicBootstrapForm').formValidation({
                    framework: 'bootstrap',
                    excluded: ':disabled',
                    icon: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        titulo: {
                            row: '.form-group',
                            validators: {
                                notEmpty: {
                                    message: 'T&iacute;tulo es requerido'
                                }
                            }
                        },

                    }
                })


            });
        </script>
