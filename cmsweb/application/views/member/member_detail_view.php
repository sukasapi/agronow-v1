<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-4">

                <!-- START PORTLET PERSONAL -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Personal
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('member/edit_personal/'.$member['member_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted">Nama</label>
                                <h5><?php echo $member['member_name']; ?></h5>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Telp / HP</label>
                                <p><?php echo $member['member_phone']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Provinsi</label>
                                <p><?php echo $member['member_province']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Kota / Kab</label>
                                <p><?php echo $member['member_city']; ?></p>
                            </div>

                        </div>
                        

                    </div>
                </div>
                <!-- END PORTLET PERSONAL -->


                <!-- START PORTLET EXPERT -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Expert
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <?php if ($member['is_expert']=="1"): ?>
                                <a target="_blank" href="<?php echo site_url('expert_member/detail/'.$expert_member['em_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-user"></i> Lihat Detail Expert
                                </a>
                                <?php else: ?>
                                <a href="<?php echo site_url('member/add_as_expert/'.$member['member_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-plus"></i> Jadikan Expert
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">


                            <dt class="col-5">Expert</dt>
                            <dd class="col-7">:
                                <?php if ($member['is_expert']=="1"): ?>
                                    <span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Expert</span>
                                <?php else: ?>
                                    <span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">Tidak</span>
                                <?php endif; ?>
                            </dd>


                            <dt class="col-5">Expert ID</dt>
                            <dd class="col-7">:
                                <?php if ($member['is_expert']=="1"): ?>
                                <?= $expert_member['em_id'] ?>
                                <?php endif; ?>
                            </dd>


                        </dl>


                    </div>
                </div>
                <!-- END PORTLET EXPERT -->


            </div>


            <div class="col-lg-4">

                <!-- START PORTLET AKSES -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Akses
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('member/edit_password/'.$member['member_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Change Password
                                </a>
                                <a href="<?php echo site_url('member/edit_access/'.$member['member_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12">
                                <label class="text-muted">NIP</label>
                                <p><?php echo $member['member_nip']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Email</label>
                                <p><?php echo $member['member_email']; ?></p>
                            </div>


                            <div class="col-12 mt-3">
                                <label class="text-muted">Group</label>
                                <p><?php echo $member['group_name']; ?></p>
                            </div>


                            <div class="col-12 mt-3">
                                <label class="text-muted">Jabatan</label>
                                <p><?php echo $member['jabatan_name']; ?> <small class="text-muted"><?= '('.$member['jabatan_group_name'].')' ?></small></p>

                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Level Karyawan</label>
                                <p><?php echo $member['nama_level_karyawan']; ?></p>
                            </div>
							
							<div class="col-12 mt-3">
                                <label class="text-muted">Level</label>
                                <p><?php echo $member['mlevel_name']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Bidang</label>
                                <p><?php echo $member['member_desc']; ?></p>
                            </div>

                        </div>


                    </div>
                </div>
                <!-- END PORTLET AKSES -->

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
                                <a href="<?php echo site_url('member/edit_picture/'.$member['member_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <?php if($member['member_image'] && $member['member_image']!='#'): ?>
                        <?php
                            if(strpos($member['member_image'], "http://") !== false || strpos($member['member_image'], "https://") !== false){
                                $media_url = $member['member_image'];
                            }else{
                                $media_url = URL_MEDIA_IMAGE.$member['member_image'];
                            }
                        ?>
                        <center>
                        <img style="object-fit: cover" src="<?php echo $media_url; ?>" class="rounded-circle" width="128px" height="128px">
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
                                <a href="<?php echo site_url('member/edit_status/'.$member['member_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= (has_access("member.edit",FALSE) OR has_access_manage_all_member())?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">

                            <dt class="col-5">Status</dt>
                            <dd class="col-7">:
                                <?php if ($member['member_status']=="active"): ?>
                                <span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Active</span>
                                <?php elseif ($member['member_status']=="block"): ?>
                                <span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">Block</span>
                                <?php endif; ?>
                            </dd>

                            <dt class="col-5">CEO Notes / BOD Share</dt>
                            <dd class="col-7">:
                                <?php if ($member['member_ceo']=="1"): ?>
                                    <span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">CEO Notes</span>
                                <?php elseif ($member['member_ceo']=="2"): ?>
                                    <span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">BOD Share</span>
                                <?php elseif ($member['member_ceo']=="0"): ?>
                                    <span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">Not Allow</span>
                                <?php endif; ?>
                            </dd>


                            <dt class="col-5">Poin</dt>
                            <dd class="col-7">:
                                <?= $member['member_poin'] ?>
                            </dd>

                            <dt class="col-5">Saldo</dt>
                            <dd class="col-7">:
                                <?= $member['member_saldo'] ?>
                            </dd>

                        </dl>                      


                    </div>
                </div>
                <!-- END PORTLET STATUS -->


            </div>


        </div>
    </div>

</div>
