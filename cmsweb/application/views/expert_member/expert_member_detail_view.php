<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?= $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?= site_url("expert_member"); ?>" class="btn kt-subheader__btn-primary">
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

                <!-- START PORTLET EXPERT -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Expert
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('expert_member/edit_personal/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("expertmember.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-3">Nama</dt>
                            <dd class="col-7">: <?= $expert_member['em_name']; ?></dd>

                            <dt class="col-3">Concern</dt>
                            <dd class="col-7">: <?= $expert_member['em_concern']; ?></dd>

                            <dt class="col-3">Profil</dt>
                            <dd class="col-7">: <?= $expert_member['em_profil']; ?></dd>

                            <dt class="col-3">Kategori</dt>
                            <dd class="col-7">: <?= $expert_member['cat_name']; ?></dd>

                            <dt class="col-3">Grup</dt>
                            <dd class="col-7">: <?= $expert_member['group_name']; ?></dd>

                            <dt class="col-3">Member</dt>
                            <dd class="col-7">: <a class="kt-badge  kt-badge--info kt-badge--outline kt-badge--inline kt-badge--pill" href="<?= site_url('member/detail/').$expert_member['member_id']; ?>" target="_blank" >Lihat Detail Member</a> </dd>

                        </dl>

                    </div>
                </div>
                <!-- END PORTLET EXPERT -->

                <!-- START PORTLET EDUCATION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Pendidikan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('expert_member/edit_education/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("expertmember.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="col-12">
                            <table class="table table-bordered table-hover table-sm nowrap" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Jenjang Pendidikan</th>
                                    <th class="text-center">Institusi</th>
                                    <th class="text-center">Tahun Lulus</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $arr_education = json_decode($expert_member['em_education'],TRUE);
                                if ($arr_education):
                                foreach ($arr_education as $k => $v):
                                ?>
                                <tr>
                                    <td><?= $v['education'] ?></td>
                                    <td><?= $v['institution'] ?></td>
                                    <td><?= $v['year'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET EDUCATION -->

                <!-- START PORTLET PENGALAMAN -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Pengalaman
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('expert_member/edit_experience/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("expertmember.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="col-12">
                            <table class="table table-bordered table-hover table-sm nowrap" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Jabatan / Keahlian</th>
                                    <th class="text-center">Perusahaan</th>
                                    <th class="text-center">Tahun Mulai</th>
                                    <th class="text-center">Tahun Akhir</th>
                                    <th class="text-center">Default</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $arr_experience = json_decode($expert_member['em_experience'],TRUE);
                                if ($arr_experience):
                                    foreach ($arr_experience as $k => $v):
                                        ?>
                                        <tr>
                                            <td><?= $v['title'] ?></td>
                                            <td><?= $v['institution'] ?></td>
                                            <td><?= $v['yearStart'] ?></td>
                                            <td><?= $v['yearEnd'] ?></td>
                                            <td><?= $v['isDefault']==1?'Ya':'' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET PENGALAMAN -->

                <!-- START PORTLET KUALIFIKASI -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Kualifikasi
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('expert_member/edit_qualification/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("expertmember.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="col-12">
                            <table class="table table-bordered table-hover table-sm nowrap" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Keahlian</th>
                                    <th class="text-center">Skor</th>
                                    <th class="text-center">Tahun</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $arr_qualification = json_decode($expert_member['em_qualification'],TRUE);
                                if ($arr_qualification):
                                    foreach ($arr_qualification as $k => $v):
                                        ?>
                                        <tr>
                                            <td><?= $v['title'] ?></td>
                                            <td><?= $v['score'] ?></td>
                                            <td><?= $v['year'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET KUALIFIKASI -->

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
                                <a href="<?= site_url('expert_member/edit_picture/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("expertmember.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <?php if($expert_member['em_image']): ?>
                        <center>
                        <img style="object-fit: cover" src="<?= URL_MEDIA_IMAGE.$expert_member['em_image']; ?>" class="rounded-circle" width="128px" height="128px">
                        </center>
                        <?php endif; ?>
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
                                <a href="<?= site_url('expert_member/edit_status/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("expertmember.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-5">Status</dt>
                            <dd class="col-7">:
                                <?php if ($expert_member['em_status']=="active"): ?>
                                <span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Active</span>
                                <?php elseif ($expert_member['em_status']=="block"): ?>
                                <span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">Block</span>
                                <?php endif; ?>
                            </dd>


                        </dl>                      


                    </div>
                </div>
                <!-- END PORTLET STATUS -->


            </div>


        </div>
    </div>

</div>
