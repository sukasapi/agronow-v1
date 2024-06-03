<style>
    .fa{
        width: 24px;
    }
</style>

<?php
$is_show_sub = true;
$css_salin = (has_access('classroom.salin',FALSE))?'':'d-none';

if(
	$classroom['cr_kelola']=="luar_app" ||
	$classroom['cr_kelola']=="lms_ext_agrowallet"
) {
	$is_show_sub = false;
	$css_salin = 'd-none';
}
?>

<div class="col-lg-3">
    <div class="list-group">
		<a href="<?php echo site_url('classroom/detail/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='detail')?'active':NULL; ?>">DASHBOARD</a>
		<a href="<?php echo site_url('classroom/member/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='member')?'active':NULL; ?>">PESERTA</a>
		
		<?php
		if($is_show_sub==false) {
		?>
		
		<div class="alert alert-warning mt-2">Data pelatihan ini dikelola di luar LMS AgroNow</div>
		
		<?php
		} else {
		?>
		
        <a href="<?php echo site_url('classroom/progress_member/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='progress_member')?'active':NULL; ?>">PROGRESS PESERTA</a>
        <a href="<?php echo site_url('classroom/attendance/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='attendance')?'active':NULL; ?>">ABSENSI</a>
        <a href="#" class="list-group-item list-group-item-action disabled text-muted">Tahapan Pelatihan :</a>
        <a href="<?php echo site_url('classroom/pengumuman/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='pengumuman')?'active':NULL; ?>"><i class="fa fa-bullhorn"></i>PENGUMUMAN</a>

		<?php if($classroom['cr_has_project_assignment']==1): ?>
        <a href="<?php echo site_url('classroom/project_assignment/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='project_assignment')?'active':NULL; ?>"><i class="fa fa-file-lines"></i>PROJECT ASSIGNMENT</a>
        <?php endif; ?>

        <?php if ($cr_has_prelearning==1): ?>
        <a href="<?php echo site_url('classroom/prelearning/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='prelearning')?'active':NULL; ?>"><i class="fa fa-book-open-reader"></i>PRE LEARNING</a>
        <?php endif; ?>

        <?php if ($cr_has_pretest==1): ?>
        <a href="<?php echo site_url('classroom/pretest/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='pretest')?'active':NULL; ?>"><i class="fa fa-list-check"></i>PRE TEST</a>
        <?php endif; ?>

        <a href="<?php echo site_url('classroom/rencana/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='rencana')?'active':NULL; ?>"><i class="fa fa-paperclip"></i>RENCANA PEMBELAJARAN</a>
        <a href="<?php echo site_url('classroom/module/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='module')?'active':NULL; ?>"><i class="fa fa-briefcase"></i>MODUL PELATIHAN</a>

        <?php if ($cr_has_kompetensi_test==1): ?>
        <a href="<?php echo site_url('classroom/competency/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='competency')?'active':NULL; ?>"><i class="fa fa-pen"></i>COMPETENCY TEST</a>
        <?php endif; ?>
		
		<?php if($classroom['cr_has_certificate']==1): ?>
        <a href="<?php echo site_url('classroom/certificate/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='certificate')?'active':NULL; ?>"><i class="fa fa-file-signature"></i>SERTIFIKAT</a>
        <?php endif; ?>

        <a href="<?php echo site_url('classroom/feedback/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='feedback')?'active':NULL; ?>"><i class="fa fa-comments"></i>FEEDBACK</a>
		
		<a href="<?php echo site_url('classroom/evaluasi_lv3/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='evaluasi_lv3')?'active':NULL; ?>"><i class="fa fa-comments"></i>EVALUASI PELATIHAN LEVEL 3</a>
		
        <a href="#" class="list-group-item list-group-item-action disabled text-muted">Push Notif :</a>
        <a href="<?php echo site_url('classroom/notif/').$cr_id; ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='notif')?'active':NULL; ?>">NOTIFIKASI</a>
        
        
        
		<?php } ?>
        <a href="<?php echo site_url('laporan_kelas/').$cr_id; ?>" style="background-color:#192a56;color:#fbc531" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2)=='member')?'active':NULL; ?>"><i class="fas fa-desktop"></i> ONE CLICK REPORT </a>
    </div>

    <!-- START PORTLET DELETE -->
    <?php
    $deletable = TRUE;
    if(!has_access('classroom.delete',FALSE)){
        if ( has_access('classroom.delete.own',FALSE)){
            if(user_id() != $classroom['id_petugas']){
                $deletable = FALSE;
            }
        }else{
            $deletable = FALSE;
        }
    }
    ?>


    <div class="kt-portlet mt-5 <?= $deletable?"":"d-none" ?>">

        <div class="kt-portlet__body">

            <div class="row">

                <div class="col-lg-12">

                    <div class="alert alert-outline-info fade show" role="alert">
                        <div class="alert-icon">
                            <small><i class="flaticon-warning"></i></small>
                        </div>
                        <div class="alert-text"><small>Class Room yang dihapus tidak dapat dikembalikan</small></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>

                    <center>
                        <a href="<?php echo site_url('classroom/delete/'.$cr_id); ?>" class="btn btn-danger btn-sm  btn-icon-md">
                            <i class="flaticon2-trash"></i> Hapus Class Room
                        </a>
                    </center>

                </div>
            </div>


        </div>
    </div>
    <!-- END PORTLET DELETE -->


    <!-- START PORTLET DUPLICATE -->

    <!--<div class="kt-portlet mt-5 <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">-->
	<div class="kt-portlet mt-5 <?=$css_salin?> ">

        <div class="kt-portlet__body">

            <div class="row">

                <div class="col-lg-12">

                    <div class="alert alert-outline-info fade show" role="alert">
                        <div class="alert-text"><small>Class Room yang diduplikasi tidak termasuk: data agrowallet, data peserta dan evaluasi pelatihan level 3</small></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>

                    <center>
                        <a href="<?php echo site_url('classroom/duplicate/'.$cr_id); ?>" class="btn btn-warning btn-sm  btn-icon-md">
                            <i class="flaticon2-copy"></i> Duplikasi Class Room
                        </a>
                    </center>

                </div>


            </div>


        </div>
    </div>
    <!-- END PORTLET DUPLICATE -->


</div>
