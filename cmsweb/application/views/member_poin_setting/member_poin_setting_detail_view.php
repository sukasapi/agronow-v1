<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member_poin_setting"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-6">

                <!-- START PORTLET  -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Config Poin
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('member_poin_setting/edit/'.$mps['mps_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("configpoin.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <dl class="row">
                            <dt class="col-3">Start Date</dt>
                            <dd class="col-7">: <?= parseDateShortReadable($mps['mps_start']) ?></dd>

                            <dt class="col-3">End Date</dt>
                            <dd class="col-7">: <?= parseDateShortReadable($mps['mps_end']) ?></dd>

                            <dt class="col-3">Daily</dt>
                            <dd class="col-7">: <?= $mps['mps_daily'] ?></dd>
                        </dl>

                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-4">Class Room</h6>
                            </div>
                        </div>

                        <dl class="row">
                            <dt class="col-3">Join</dt>
                            <dd class="col-7">: <?= $mps['mps_cr_join'] ?></dd>

                            <dt class="col-3">Grade A</dt>
                            <dd class="col-7">: <?= $mps['mps_cr_grade_a'] ?></dd>

                            <dt class="col-3">Grade B</dt>
                            <dd class="col-7">: <?= $mps['mps_cr_grade_b'] ?></dd>

                            <dt class="col-3">Grade C</dt>
                            <dd class="col-7">: <?= $mps['mps_cr_grade_c'] ?></dd>

                            <dt class="col-3">Grade D</dt>
                            <dd class="col-7">: <?= $mps['mps_cr_grade_d'] ?></dd>

                        </dl>


                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-4">Corporate Culture</h6>
                            </div>
                        </div>

                        <dl class="row">
                            <dt class="col-3">Join</dt>
                            <dd class="col-7">: <?= $mps['mps_cc_join'] ?></dd>

                            <dt class="col-3">Grade A</dt>
                            <dd class="col-7">: <?= $mps['mps_cc_grade_a'] ?></dd>

                            <dt class="col-3">Grade B</dt>
                            <dd class="col-7">: <?= $mps['mps_cc_grade_b'] ?></dd>

                            <dt class="col-3">Grade C</dt>
                            <dd class="col-7">: <?= $mps['mps_cc_grade_c'] ?></dd>

                            <dt class="col-3">Grade D</dt>
                            <dd class="col-7">: <?= $mps['mps_cc_grade_d'] ?></dd>

                        </dl>

                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-4">Knowledge Sharing</h6>
                            </div>
                        </div>

                        <dl class="row">
                            <dt class="col-3">Approved</dt>
                            <dd class="col-7">: <?= $mps['mps_ks_approved'] ?></dd>

                            <dt class="col-3">Reject</dt>
                            <dd class="col-7">: <?= $mps['mps_ks_reject'] ?></dd>

                            <dt class="col-3">Liked</dt>
                            <dd class="col-7">: <?= $mps['mps_ks_liked'] ?></dd>

                        </dl>
                        

                    </div>
                </div>
                <!-- END PORTLET  -->

            </div>

            <div class="col-lg-6">

                <!-- START PORTLET  -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Bulanan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('member_poin_setting_monthly/create/'.$mps['mps_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md">
                                    <i class="flaticon2-plus"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Min %</th>
                                    <th class="text-center">Max %</th>
                                    <th class="text-center">Poin</th>
                                    <th class="text-center" width="84px"></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($mps_monthly as $k => $v): ?>
                                <tr>
                                    <td><?= $v['mps_monthly_percent_min'] ?></td>
                                    <td><?= $v['mps_monthly_percent_max'] ?></td>
                                    <td><?= $v['mps_monthly_poin'] ?></td>
                                    <td>
                                        <a href="<?= site_url('member_poin_setting_monthly/edit/').$v['mps_monthly_id'] ?>" class="btn btn-outline-primary btn-icon btn-circle btn-sm <?= has_access("configpoin.edit",FALSE)?"":"d-none" ?>">
                                            <i class="la la-pencil"></i>
                                        </a>
                                        <a href="<?= site_url('member_poin_setting_monthly/delete/').$v['mps_monthly_id'] ?>" class="btn btn-outline-danger btn-icon btn-circle btn-sm <?= has_access("configpoin.delete",FALSE)?"":"d-none" ?>">
                                            <i class="la la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                        <!--end: Datatable -->

                    </div>
                </div>
                <!-- END PORTLET  -->

                <!-- START PORTLET DELETE -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hapus Config
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
                                    <div class="alert-text"><small>Config yang dihapus tidak dapat dikembalikan</small></div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>

                                <center>
                                    <a href="<?php echo site_url('member_poin_setting/delete/'.$mps['mps_id']); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                                        <i class="flaticon2-trash"></i> Hapus Config
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
