<div class="col-lg-3">
    <div class="list-group">
        <a href="<?php echo site_url('survey/detail/').$survey_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='detail')?'active':NULL; ?>">INFORMASI</a>
        <a href="<?php echo site_url('survey/question/').$survey_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='question')?'active':NULL; ?>">PERTANYAAN</a>
        <a href="<?php echo site_url('survey/member/').$survey_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='member')?'active':NULL; ?>">PARTISIPAN</a>
    </div>

    <!-- START PORTLET EXPORT -->
    <div class="kt-portlet mt-5">

        <div class="kt-portlet__body">

            <div class="row">

                <div class="col-lg-12">

                    <center>
                        <a href="<?php echo site_url('survey/export/'.$survey_id); ?>" class="btn btn-success btn-sm btn-block  btn-icon-md">
                            <i class="flaticon2-download"></i> Download Laporan
                        </a>
                    </center>

                </div>


            </div>


        </div>
    </div>
    <!-- END PORTLET EXPORT -->

    <!-- START PORTLET DELETE -->
    <div class="kt-portlet mt-5 <?= has_access("survey.delete",FALSE)?"":"d-none" ?>">

        <div class="kt-portlet__body">

            <div class="row">

                <div class="col-lg-12">

                    <div class="alert alert-outline-info fade show" role="alert">
                        <div class="alert-icon">
                            <small><i class="flaticon-warning"></i></small>
                        </div>
                        <div class="alert-text"><small>Survey yang dihapus tidak dapat dikembalikan</small></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>

                    <center>
                        <a href="<?php echo site_url('survey/delete/'.$survey_id); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                            <i class="flaticon2-trash"></i> Hapus Survey
                        </a>
                    </center>

                </div>


            </div>


        </div>
    </div>
    <!-- END PORTLET DELETE -->
</div>
