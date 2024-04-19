<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("culture"); ?>" class="btn kt-subheader__btn-primary">
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

            <!-- Navigation -->
            <?php
                $submenu_data = $culture;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET KONTEN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Dashboard
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('culture/edit/'.$culture['cr_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md  <?= has_access("culture.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="text-muted">Nama Pelatihan</label>
                                <h5><?php echo $culture['cr_name']; ?></h5>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Kategori</label>
                                <p><?php echo $culture['cat_name']; ?></p>
                            </div>

                            <div class="col-lg-3 mt-3">
                                <label class="text-muted">Ada Sertifikat</label>
                                <p><?php echo $culture['cr_has_certificate']==1?'Ya':'Tidak'; ?></p>
                            </div>


                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Tanggal</label>
                                <p><?php echo parseDateReadable($culture['cr_date_start']); ?> - <?php echo parseDateReadable($culture['cr_date_end']); ?></p>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Jam</label>
                                <p><?php echo parseTimeReadable($culture['cr_time_start']); ?> - <?php echo parseTimeReadable($culture['cr_time_end']); ?></p>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <label class="text-muted">Keterangan</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$culture['cr_desc']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET KONTEN -->
            </div>


        </div>
    </div>

</div>
