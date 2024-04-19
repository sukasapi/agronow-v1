<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("ceo_notes"); ?>" class="btn kt-subheader__btn-primary">
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
                                <a href="<?php echo site_url('ceo_notes/edit'); ?>" class="btn btn-clean btn-sm  btn-icon-md">
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
                                <div>
                                    <?php echo $content['content_desc']; ?>  
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Sumber CEO Notes</label>
                                <p><?php echo $content['content_source']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Pengarang CEO Notes</label>
                                <p><?php echo $content['content_author']; ?></p>
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
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('ceo_notes/edit'); ?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
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
                                Cover Image
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('ceo_notes/edit'); ?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80" width="100%">

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
                                <a href="<?php echo site_url('ceo_notes/edit'); ?>" class="btn btn-clean btn-sm  btn-icon-md">
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
                                <a href="<?php echo site_url('ceo_notes/edit'); ?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-3">Group</dt>
                            <dd class="col-9">: <?php echo $content['group_id']; ?></dd>

                            <dt class="col-3">Level</dt>
                            <dd class="col-9">: <?php echo $content['mlevel_id']; ?></dd>

                            <dt class="col-3">Bidang</dt>
                            <dd class="col-9">: <?php echo $content['content_bidang']; ?></dd>

                            <dt class="col-3">Tags</dt>
                            <dd class="col-9">: <?php echo $content['content_tags']; ?></dd>

                            <dt class="col-3">Dilihat</dt>
                            <dd class="col-9">: <?php echo $content['content_hits']; ?></dd>

                        </dl>


                    </div>
                </div>
                <!-- END PORTLET OPTION -->



            </div>


        </div>
    </div>

</div>