<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("user"); ?>" class="btn kt-subheader__btn-primary">
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
                                <a href="<?php echo site_url('user/edit_status/'.$user['user_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("user.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit Status
                                </a>
                                <a href="<?php echo site_url('user/edit_password/'.$user['user_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("user.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Change Password
                                </a>
                                <a href="<?php echo site_url('user/edit/'.$user['user_id']); ?>" class="btn btn-clean btn-sm  btn-icon-md <?= has_access("user.edit",FALSE)?"":"d-none" ?>">
                                    <i class="flaticon2-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">
                                <label class="text-muted">Nama</label>
                                <h5><?php echo $user['user_name']; ?></h5>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Email</label>
                                <p><?php echo $user['user_email']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">User Level</label>
                                <p><?php echo $user['user_level_name']; ?></p>
                            </div>

                            <div class="col-6 mt-3">
                                <label class="text-muted">Klien</label>
                                <p><?php echo $user['klien_nama']; ?></p>
                            </div>

                            <div class="col-6 mt-3">
                                <label class="text-muted">Group</label>
                                <p><?php echo $user['group_name']; ?></p>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="text-muted">Status</label>
                                <p><?php echo $user['user_status']; ?></p>
                            </div>

                        </div>


                    </div>
                </div>
                <!-- END PORTLET AKSES -->


                 <!-- START PORTLET DELETE -->
                <div class="kt-portlet mt-5 <?= has_access("user.delete",FALSE)?"":"d-none" ?>">

                    <div class="kt-portlet__body">

                        <div class="row">

                            <div class="col-lg-12">

                                    <div class="alert alert-outline-info fade show" role="alert">
                                        <div class="alert-icon">
                                            <small><i class="flaticon-warning"></i></small>
                                        </div>
                                        <div class="alert-text"><small>Administrator yang dihapus tidak dapat dikembalikan</small></div>
                                        <div class="alert-close">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
                                        </div>
                                    </div>

                                    <center>
                                        <a href="<?php echo site_url('user/delete/'.$user['user_id']); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                                            <i class="flaticon2-trash"></i> Hapus Administrator
                                        </a>
                                    </center>
                            </div>

                        </div>

                    </div>

                </div>

            


        </div>
    </div>

</div>
