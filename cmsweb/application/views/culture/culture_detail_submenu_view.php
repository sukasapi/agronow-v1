<div class="col-lg-3">
    <div class="list-group">
        <a href="<?php echo site_url('culture/detail/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='detail')?'active':NULL; ?>">DASHBOARD</a>
        <a href="<?php echo site_url('culture/member/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='member')?'active':NULL; ?>">PESERTA</a>
        <a href="<?php echo site_url('culture/progress_member/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='progress_member')?'active':NULL; ?>">PROGRESS PESERTA</a>
        <a href="#" class="list-group-item list-group-item-action disabled text-muted">Tahapan Pelatihan :</a>
        <a href="<?php echo site_url('culture/pengumuman/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='pengumuman')?'active':NULL; ?>">1. PENGUMUMAN</a>
        <a href="<?php echo site_url('culture/rencana/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='rencana')?'active':NULL; ?>">2. RENCANA PEMBELAJARAN</a>
        <a href="<?php echo site_url('culture/module/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='module')?'active':NULL; ?>">3. MODUL PELATIHAN</a>
        <a href="<?php echo site_url('culture/competency/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='competency')?'active':NULL; ?>">4. COMPETENCY TEST</a>
        <a href="<?php echo site_url('culture/certificate/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='certificate')?'active':NULL; ?>">5. SERTIFIKAT</a>
    </div>

    <!-- START PORTLET DELETE -->
    <div class="kt-portlet mt-5 <?= has_access("culture.delete",FALSE)?"":"d-none" ?>">

        <div class="kt-portlet__body">

            <div class="row">

                <div class="col-lg-12">

                    <div class="alert alert-outline-info fade show" role="alert">
                        <div class="alert-icon">
                            <small><i class="flaticon-warning"></i></small>
                        </div>
                        <div class="alert-text"><small>Corporate Culture yang dihapus tidak dapat dikembalikan</small></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>

                    <center>
                        <a href="<?php echo site_url('culture/delete/'.$cr_id); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                            <i class="flaticon2-trash"></i> Hapus Culture
                        </a>
                    </center>

                </div>


            </div>


        </div>
    </div>
    <!-- END PORTLET DELETE -->
</div>
