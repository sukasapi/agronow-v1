<div class="col-lg-3">
    <div class="list-group">
        <a href="<?php echo site_url('kompetensi/detail/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='detail')?'active':NULL; ?>">DASHBOARD</a>
        <a href="<?php echo site_url('kompetensi/jabatan/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='jabatan')?'active':NULL; ?>">JABATAN</a>
        <a href="<?php echo site_url('kompetensi/group/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='group')?'active':NULL; ?>">GROUP</a>
        <a href="<?php echo site_url('kompetensi/member/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='member')?'active':NULL; ?>">PESERTA</a>
        <a href="<?php echo site_url('kompetensi/prasyarat/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='prasyarat')?'active':NULL; ?>">PRASYARAT</a>
        <a href="<?php echo site_url('kompetensi/progress_member/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='progress_member')?'active':NULL; ?>">PROGRESS PESERTA</a>
        <a href="#" class="list-group-item list-group-item-action disabled text-muted">Push Notif :</a>
        <a href="<?php echo site_url('kompetensi/notif/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='notif')?'active':NULL; ?>">NOTIFIKASI</a>
    </div>

    <!-- START PORTLET DELETE -->
    <div class="kt-portlet mt-5 <?= has_access("kompetensi.delete",FALSE)?"":"d-none" ?>">

        <div class="kt-portlet__body">

            <div class="row">

                <div class="col-lg-12">

                    <div class="alert alert-outline-info fade show" role="alert">
                        <div class="alert-icon">
                            <small><i class="flaticon-warning"></i></small>
                        </div>
                        <div class="alert-text"><small>Kompetensi yang dihapus tidak dapat dikembalikan</small></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>

                    <center>
                        <a href="<?php echo site_url('kompetensi/delete/'.$cr_id); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                            <i class="flaticon2-trash"></i> Hapus Kompetensi
                        </a>
                    </center>

                </div>


            </div>


        </div>
    </div>
    <!-- END PORTLET DELETE -->
</div>
