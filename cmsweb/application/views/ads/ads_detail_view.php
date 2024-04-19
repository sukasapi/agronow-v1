<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("ads"); ?>" class="btn kt-subheader__btn-primary">
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

                <!-- START PORTLET PERSONAL -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Materi Ads
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('ads/edit/'.$ads['ads_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access('ads.edit',FALSE)?'':'d-none' ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                                <a href="<?php echo site_url('ads/edit_picture/'.$ads['ads_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access('ads.edit',FALSE)?'':'d-none' ?>">
                                    <i class="flaticon2-edit"></i> Ganti Gambar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted">Gambar</label>
                                <center>
                                    <img src="<?php echo URL_MEDIA_IMAGE.$ads['ads_image']; ?>" class="img-fluid" width="100%">
                                </center>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Sponsor</label>
                                <h5><?php echo $ads['ads_sponsor']; ?></h5>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Link</label>
                                <p><?php echo $ads['ads_link']; ?></p>
                            </div>


                        </div>
                        

                    </div>
                </div>
                <!-- END PORTLET PERSONAL -->

            </div>



            <div class="col-lg-4">


                <!-- START PORTLET STATUS -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Pengaturan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('ads/edit/'.$ads['ads_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access('ads.edit',FALSE)?'':'d-none' ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-4">Status</dt>
                            <dd class="col-8">:
                                <?php if ($ads['ads_status']=="active"): ?>
                                <span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Active</span>
                                <?php elseif ($ads['ads_status']=="block"): ?>
                                <span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">Block</span>
                                <?php elseif ($ads['ads_status']=="expired"): ?>
                                <span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">Expired</span>
                                <?php endif; ?>
                            </dd>

                            <dt class="col-4">Mulai</dt>
                            <dd class="col-8">:
                                <?= parseDateReadable($ads['ads_start']) ?>
                            </dd>

                            <dt class="col-4">Selesai</dt>
                            <dd class="col-8">:
                                <?= parseDateReadable($ads['ads_end']) ?>
                            </dd>

                            <dt class="col-4">Posisi</dt>
                            <dd class="col-8">:
                                <?= $ads['ads_position'] ?>
                            </dd>

                            <dt class="col-4">Urutan</dt>
                            <dd class="col-8">:
                                <?= $ads['ads_order'] ?>
                            </dd>

                            <dt class="col-4">Klien</dt>
                            <dd class="col-8">: <?php echo $ads['klien']; ?></dd>



                        </dl>                      


                    </div>
                </div>
                <!-- END PORTLET STATUS -->

                <!-- START PORTLET DELETE -->
                <div class="kt-portlet <?= has_access("ads.delete",FALSE)?"":"d-none" ?>">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hapus Ads
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
                                    <div class="alert-text"><small>Ads yang dihapus tidak dapat dikembalikan</small></div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>

                                <center>
                                    <a href="<?php echo site_url('ads/delete/'.$ads['ads_id']); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                                        <i class="flaticon2-trash"></i> Hapus Ads
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
