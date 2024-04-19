<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("kompetensi/?year=").$kompetensi['cr_year']; ?>" class="btn kt-subheader__btn-primary">
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
                $submenu_data = $kompetensi;
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
                                <a href="<?php echo site_url('kompetensi/edit/'.$kompetensi['cr_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("kompetensi.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="text-muted">Nama Kompetensi</label>
                                <h5><?php echo $kompetensi['cr_name']; ?></h5>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Jumlah Level</label>
                                <p><?php echo $kompetensi['cr_komp_max_lv']; ?></p>

                                <label class="text-muted mt-3">Kategori</label>
                                <p><?php echo $kompetensi['cat_name']; ?></p>
                            </div>


                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Kompetensi Harian</label>
                                <p><?php echo $kompetensi['cr_is_daily']==1?'Ya':'Tidak'; ?></p>

                                <label class="text-muted mt-3">Tanggal</label>
                                <p><?php echo parseDateReadable($kompetensi['cr_date_start']); ?> - <?php echo parseDateReadable($kompetensi['cr_date_end']); ?></p>
                            </div>


                            <div class="col-lg-12 mt-3">
                                <label class="text-muted">Panduan</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$kompetensi['cr_desc']); ?>
                                </div>

                                <label class="text-muted mt-3">Prasyarat (Daftar pelatihan yang wajib diikuti jika kompetensi kurang)</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$kompetensi['cr_materi']); ?>
                                </div>

                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>


                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Status</label>
                                <p><?php echo $kompetensi['cr_status']; ?></p>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label class="text-muted">Tahun</label>
                                <p><?php echo $kompetensi['cr_year']?$kompetensi['cr_year']:'N/A'; ?></p>
                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET KONTEN -->
            </div>


        </div>
    </div>

</div>
