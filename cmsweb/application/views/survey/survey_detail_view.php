<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("survey"); ?>" class="btn kt-subheader__btn-primary">
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
                $submenu_data = $survey;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-5">
                <!-- START PORTLET KONTEN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('survey/edit/'.$survey['survey_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("survey.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="text-muted">Nama Survey</label>
                                <h5><?php echo $survey['survey_name']; ?></h5>
                            </div>


                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Tanggal</label>
                                <p><?php echo parseDateReadable($survey['survey_date_start']); ?> - <?php echo parseDateReadable($survey['survey_date_end']); ?></p>
                            </div>


                            <div class="col-lg-12 mt-3">
                                <label class="text-muted">Keterangan</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$survey['survey_desc']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Status</label>
                                <p><?php echo $survey['survey_status']; ?></p>
                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET KONTEN -->
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
                                <a href="<?php echo site_url('survey/edit_picture/'.$survey['survey_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("survey.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <img src="<?php echo URL_MEDIA_IMAGE.$survey['survey_image']; ?>" class="img-fluid">

                    </div>
                </div>
                <!-- END PORTLET COVER -->
            </div>


        </div>
    </div>

</div>