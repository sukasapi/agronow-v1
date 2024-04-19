<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("digital_sop"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-lg-8">

                <!-- START PORTLET KONTEN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Konten
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('digital_sop/edit_content/'.$content['content_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("digitalsop.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted">Judul</label>
                                <h4><?php echo $content['content_name']; ?></h4>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Deskripsi</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$content['content_desc']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Sumber</label>
                                <p><?php echo $content['content_source']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Pengarang</label>
                                <p><?php echo $content['content_author']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Tags</label>
                                <p><?php echo $content['content_tags']; ?></p>
                            </div>

                        </div>
                        

                    </div>
                </div>
                <!-- END PORTLET KONTEN -->


                <!-- START PORTLET SEO -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                SEO
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted">Alias</label>
                                <p><?php echo $content['content_alias']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">SEO Title</label>
                                <p><?php echo $content['content_seo_title']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">SEO Keywords</label>
                                <p><?php echo $content['content_seo_keyword']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">SEO Description</label>
                                <p><?php echo $content['content_seo_desc']; ?></p>
                            </div>

                        </div>


                    </div>
                </div>
                <!-- END PORTLET SEO -->

            </div>

            <div class="col-lg-4">

                <!-- START PORTLET COVER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Gambar
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('digital_sop/edit_picture/'.$content['content_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("digitalsop.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <img src="<?php echo URL_MEDIA_IMAGE.$content['media_value']; ?>" class="img-fluid">

                    </div>
                </div>
                <!-- END PORTLET COVER -->

                <!-- START PORTLET STATUS -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Status
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('digital_sop/edit_publish/'.$content['content_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("digitalsop.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-3">Status</dt>
                            <dd class="col-9">:
                                <?php if ($content['content_status']=="publish"): ?> 
                                <span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">PUBLISH</span>
                                <?php elseif ($content['content_status']=="pending"): ?> 
                                <span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">PENDING</span>
                                <?php elseif ($content['content_status']=="draft"): ?> 
                                <span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">DRAFT</span>
                                <?php endif; ?>
                            </dd>

                            <dt class="col-3">Publish</dt>
                            <dd class="col-9">: <?php echo parseDateShortReadable($content['content_publish_date']); ?>, <?php echo parseTimeReadable($content['content_publish_date']); ?></dd>

                            <dt class="col-3">Notifikasi</dt>
                            <dd class="col-9">: <?php echo $content['content_notif']=='1'?'Ya':'Tidak'; ?></dd>

                        </dl>                      


                    </div>
                </div>
                <!-- END PORTLET STATUS -->

                <!-- START PORTLET OPTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Metadata
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('digital_sop/edit_metadata/'.$content['content_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("digitalsop.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-3">Group</dt>
                            <dd class="col-9">: <?php echo $content['group_name']; ?></dd>

                            <dt class="col-3">Level</dt>
                            <dd class="col-9">: <?php echo str_replace(',',', ',$content['mlevel_id']); ?></dd>

                            <dt class="col-3">Bidang</dt>
                            <dd class="col-9">: <?php echo str_replace(',',', ',$content['content_bidang']); ?></dd>

                            <dt class="col-3">Dilihat</dt>
                            <dd class="col-9">: <?php echo $content['content_hits']; ?></dd>

                        </dl>


                    </div>
                </div>
                <!-- END PORTLET OPTION -->

                <!-- START PORTLET DELETE -->
                <div class="kt-portlet <?= has_access("digitalsop.delete",FALSE)?"":"d-none" ?>">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hapus Konten
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="alert alert-outline-info fade show" role="alert">
                                    <div class="alert-icon">
                                        <small><i class="flaticon-warning"></i></small>
                                    </div>
                                    <div class="alert-text"><small>Konten yang dihapus tidak dapat dikembalikan</small></div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>

                                <center>
                                    <a href="<?php echo site_url('digital_sop/delete/'.$content['content_id']); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                                        <i class="flaticon2-trash"></i> Hapus Konten
                                    </a>
                                </center>

                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET DELETE -->



            </div>


        </div>
    </div>

</div>
