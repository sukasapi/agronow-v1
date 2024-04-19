<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("classroom"); ?>" class="btn kt-subheader__btn-primary">
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
                $submenu_data = $classroom;
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
                                <a href="<?php echo site_url('classroom/edit/'.$classroom['cr_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="text-muted">Nama Pelatihan</label>
                                <h5><?php echo $classroom['cr_name']; ?></h5>
                            </div>



                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Kategori</label>
                                <p><?php echo $classroom['cat_name']; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Type</label>
                                <p><?php echo $classroom['cr_type']; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Dibuat Oleh</label>
                                <p><?php echo $classroom['user_name']; ?></p>
                            </div>



                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Tanggal</label>
                                <p><?php echo parseDateReadable($classroom['cr_date_start']); ?> - <?php echo parseDateReadable($classroom['cr_date_end']); ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Jam</label>
                                <p><?php echo parseTimeReadable($classroom['cr_time_start']); ?> - <?php echo parseTimeReadable($classroom['cr_time_end']); ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Jumlah Jam Pelatihan</label>
                                <p><?php echo $classroom['cr_date_detail']?$classroom['cr_date_detail'].' jam':NULL; ?></p>
                            </div>


                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Sertifikat</label>
                                <p><?php echo $classroom['cr_has_certificate']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Prelearning</label>
                                <p><?php echo $classroom['cr_has_prelearning']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Pretest</label>
                                <p><?php echo $classroom['cr_has_pretest']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Learning Point</label>
                                <p><?php echo $classroom['cr_has_learning_point']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Competency Test</label>
                                <p><?php echo $classroom['cr_has_kompetensi_test']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Knowledge Management</label>
                                <p><?php echo $classroom['cr_has_knowledge_management']==1?'Ya':'Tidak'; ?></p>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Project Assignment</label>
                                <p><?php echo $classroom['cr_has_project_assignment']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Tampilkan nilai ke peserta</label>
                                <p><?php echo $classroom['cr_show_nilai']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Modul Harus Urut</label>
                                <p><?php echo $classroom['cr_modul_harus_urut']==1?'Ya':'Tidak'; ?></p>
                            </div>
							
                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Project SuperApp</label>
                                <p><?php echo $classroom['kode_superapp_manpro']; ?></p>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">AgroWallet</label>
                                <p><?php echo $this->learning_wallet_model->getDetailPelatihan('kode_nama',array('id'=>$classroom['id_lw_classroom'])); ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Harga Class Room (Poin)</label>
                                <p><?php echo $classroom['cr_price']; ?></p>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">Keterangan</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$classroom['cr_desc']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">URL Tanda Tangan Digital</label>
                                <p><?php echo str_replace('/cmsweb','',site_url('')).'ttd_digital/'.$classroom['cr_id']; ?></p>
                            </div>
                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Penanggung jawab Kelas (PIC)</label>
                                <p><?php echo $classroom['cr_pic']; ?></p>
                            </div>


                        </div>


                    </div>
                </div>
                <!-- END PORTLET KONTEN -->
            </div>


        </div>
    </div>

</div>
