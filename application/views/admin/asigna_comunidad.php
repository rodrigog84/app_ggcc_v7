
        <!-- Main content -->
        <section class="content">

               <div class="row">
                  <div class="span12">
                    <div class="box box-solid">
                      <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                          <?php foreach($comunidades as $comunidad): ?>
                            <li><a href="<?php echo base_url(); ?>main/dashboard/<?php echo $comunidad->id;?>"><i class="fa fa-circle-o text-light-blue"></i><?php echo $comunidad->nombre;?></a></li>
                          <?php endforeach; ?>
                        </ul>
                      </div><!-- /.box-body -->
                    </div><!-- /.box -->                  
                  </div>
                </div>

        </section><!-- /.content -->