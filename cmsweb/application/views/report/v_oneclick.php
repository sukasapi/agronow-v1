<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
        </div>
    </div>
    <!-- end:: Subheader -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <!--- KELAS -->
        <div class="row">
            <div class="col-md-12 col-xs-12 my-2">
                <div class="card" style="border-radius:20px">
                    <div class="card-body">
                        <h1><?=$kelas['cr_name']?></h1>
                        <h4><?= parseDateReadable($kelas['cr_date_start'])?> hingga <?= parseDateReadable($kelas['cr_date_end'])?></h4>
                        <h5>Wallet Code : 
                        <?php 
                            if($kelas['kode']=="")
                            {
                                echo "<span class='badge badge-pill badge-danger'>Non-wallet Classroom</span>";
                            }else
                            {
                                echo "<span class='badge badge-pill badge-success'>".$kelas['kode']."</span>";
                            } 
                        ?>
                        </h5>                       
                        <hr>
                        <p class="my-4">
                            <?php 
                                if($kelas['desc2']!=""){
                                    echo $kelas['desc2'];
                                }else{
                                    echo   $kelas['cr_desc'];
                                }
                            ?>
                        </p>
                        <div class="row">
                            <div class="col-md-4 col-xs-12">
                                    <button class="btn btn-rounded btn-primary" id="bsasaran">Sasaran</button>
                                    <button class="btn btn-rounded btn-warning" id="bsilabus">Silabus</button>
                                    <button class="btn btn-rounded btn-success" id="bdetil">Detail</button>
                            </div>
                        </div>
                        <hr>
                </div>
                <div class="card-footer">
                       
                       
                </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="mdsasaran" tabindex="-1" role="dialog" aria-labelledby="mdsasaran" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sasaran Pembelajaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?=$kelas['sasaran_pembelajaran']?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="mdsilabus" tabindex="-1" role="dialog" aria-labelledby="silabus" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Silabus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?=$kelas['silabus']?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="mddetil" tabindex="-1" role="dialog" aria-labelledby="mddetil" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Kelas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="row">
                            <div class="col-lg-12">
                                <label class="text-muted">Nama Pelatihan</label>
                                <h5><?php echo $kelas['cr_name']; ?></h5>
                            </div>



                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Kategori</label>
                                <p><?php echo $kelas['cat_name']; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Type</label>
                                <p><?php echo $kelas['cr_type']; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Dibuat Oleh</label>
                                <p><?php echo $kelas['user_name']; ?></p>
                            </div>



                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Tanggal</label>
                                <p><?php echo parseDateReadable($kelas['cr_date_start']); ?> - <?php echo parseDateReadable($kelas['cr_date_end']); ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Jam</label>
                                <p><?php echo parseTimeReadable($kelas['cr_time_start']); ?> - <?php echo parseTimeReadable($kelas['cr_time_end']); ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Jumlah Jam Pelatihan</label>
                                <p><?php echo $kelas['cr_date_detail']?$kelas['cr_date_detail'].' jam':NULL; ?></p>
                            </div>


                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Sertifikat</label>
                                <p><?php echo $kelas['cr_has_certificate']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Prelearning</label>
                                <p><?php echo $kelas['cr_has_prelearning']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Pretest</label>
                                <p><?php echo $kelas['cr_has_pretest']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Learning Point</label>
                                <p><?php echo $kelas['cr_has_learning_point']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Competency Test</label>
                                <p><?php echo $kelas['cr_has_kompetensi_test']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Knowledge Management</label>
                                <p><?php echo $kelas['cr_has_knowledge_management']==1?'Ya':'Tidak'; ?></p>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">Ada Project Assignment</label>
                                <p><?php echo $kelas['cr_has_project_assignment']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Tampilkan nilai ke peserta</label>
                                <p><?php echo $kelas['cr_show_nilai']==1?'Ya':'Tidak'; ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Modul Harus Urut</label>
                                <p><?php echo $kelas['cr_modul_harus_urut']==1?'Ya':'Tidak'; ?></p>
                            </div>
							
                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Project SuperApp</label>
                                <p><?php echo $kelas['kode_superapp_manpro']; ?></p>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">AgroWallet</label>
                                <p><?php echo $this->learning_wallet_model->getDetailPelatihan('kode_nama',array('id'=>$kelas['id_lw_classroom'])); ?></p>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Harga Class Room (Poin)</label>
                                <p><?php echo $kelas['cr_price']; ?></p>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">Keterangan</label>
                                <div class="content-view">
                                    <?php echo str_replace("&quot;",'"',$kelas['cr_desc']); ?>
                                </div>
                                <script>
                                    $(".content-view img").addClass("img-fluid");
                                </script>
                            </div>
							
							<div class="col-lg-4 mt-3">
                                <label class="text-muted">URL Tanda Tangan Digital</label>
                                <p><?php echo str_replace('/cmsweb','',site_url('')).'ttd_digital/'.$kelas['cr_id']; ?></p>
                            </div>
                            <div class="col-lg-4 mt-3">
                                <label class="text-muted">Penanggung jawab Kelas (PIC)</label>
                                <p><?php echo $kelas['cr_pic']; ?></p>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END KELAS --> 
        <!-- PESERTA --> 
        <div class="row">
            <div class="col-md-12 col-xs-12 my-2">
                <div class="card" style="border-radius:20px">
                                <div class="card-header bg-primary">
                                    <div class="card-title text-white"><h4>Peserta</h4></div>
                                </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 col-xs-12">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Peserta</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">20 Orang</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-warning"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Kehadiran</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">20</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Lulus</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check-square fa-2x text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                        <div class="row mb-4">
                            
                        </div>
                    </div>
                </div>
            </div>
    </div>
        <!-- END PESERTA -->

    </div>

  

</div>

<script>
    $( document ).ready(function() {
            $('#bsasaran').on('click',function(e){
                e.preventDefault();
                $('#mdsasaran').modal('show');
            })
            $('#bsilabus').on('click',function(e){
                e.preventDefault();
                $('#mdsilabus').modal('show');
            })
            $('#bdetil').on('click',function(e){
                e.preventDefault();
                $('#mddetil').modal('show');
            })
    });
</script>