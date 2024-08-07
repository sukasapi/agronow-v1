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
            
                <div class="card">
                    <div class="card-body text-center">
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
                        <div class="text-center">
                                <div class="d-flex justify-content-center">
                                    <p class="my-4">
                                        <?php 
                                            if($kelas['desc2']!=""){
                                                echo $kelas['desc2'];
                                            }else{
                                                echo   $kelas['cr_desc'];
                                            }
                                        ?>
                                    </p>
                                </div>
                     
                            <div class="d-flex justify-content-center">
                                        <button class="btn btn-rounded btn-primary mx-2" id="bsasaran">Sasaran</button>
                                        <button class="btn btn-rounded btn-warning mx-2" id="bsilabus">Silabus</button>
                                        <button class="btn btn-rounded btn-success mx-2" id="bdetil">Detail</button>
                            </div>
                    
                        </div>
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
                <div class="card shadow" style="border-radius:20px">
                                <div class="card-header bg-primary" style="border-top-left-radius:20px;border-top-right-radius:20px">
                                    <div class="card-title text-white"><h4>Peserta</h4></div>
                                </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3 col-xs-12">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Peserta</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$daftar_peserta[0]->jp?> Orang</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-warning"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-gray-600 text-uppercase mb-1" style="color:#95afc0">
                                                   Hari Penyelenggaraan</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jumlah_hari[0]->hari?> Hari</div>    
                                                  
                                            </div>
                                            <div class="col-auto">
                                                <button id='bpreseni' class="btn btn-sm btn-rounded" ><i class="fas fa-calendar fa-2x" style="color:#95afc0"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                 Jam Pelatihan</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$kelas['cr_date_detail']?> jam</div>    
                                                  
                                            </div>
                                            <div class="col-auto">
                                                <button id='bpreseni' class="btn btn-sm btn-rounded" ><i class="fas fa-clock fa-2x text-primary"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Lulus</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$lulus_peserta[0]->lulus?></div>
                                            </div>
                                            <div class="col-auto">
                                            <button type="button" class="btn" data-container="body" data-toggle="popover" data-placement="top" data-content="* Acuan kelulusan sementara adalah nilai  > 70 ">
                                            <i class="fas fa-check-square fa-2x text-success"></i>
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 border border-primary">
                            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" id="one-tab" data-toggle="tab" href="#TabTest" role="tab" aria-controls="One" aria-selected="true">Hasil Ujian</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="three-tab" data-toggle="tab" href="#TabPresensi" role="tab" aria-controls="Three" aria-selected="false">Presensi</a>
                                        </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active p-3" id="TabTest" role="tabpanel" aria-labelledby="one-tab">
                                    <h5 class="card-title">Hasil Tes Peserta</h5>
                                    <div class="table-responsive">
                                                    <table class="table" id="tbTest" style="width:100%">
                                                        <thead>
                                                            <tr class='text-center'>
                                                                <th>No</th>
                                                                <th>Nip</th>
                                                                <th>Nama</th>
                                                                <th>Entitas</th>
                                                                <th>Pre-Test</th>
                                                                <th>Post-Test</th>
                                                                <th>Status</th>
                                                                <th>Progress</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                $no=1;
                                                                $str="";
                                                                foreach($peserta['test'] as $tes){
                                                                    $pre=isset($tes['prescore']) && $tes['prescore']!=""?$tes['prescore']:0;
                                                                    $score=isset($tes['score']) && $tes['score']!=""?$tes['score']:0;
                                                                
                                                                    if($score > 70 && $tes['score']!=""){
                                                                        $status="<span class='badge badge-success badge-pill'>Lulus</span>";
                                                                    }else if($tes['score']==""){
                                                                        $status="<span class='badge badge-info badge-pill'>Tes tidak ditemukan</span>";
                                                                    }else{
                                                                        $status="<span class='badge badge-danger badge-pill'>Tidak lulus</span>";
                                                                    }

                                                                    if($score > $pre){
                                                                        $progress="<i class='fa-solid fa-arrow-up text-success'></i>";
                                                                    }else if($score < $pre){
                                                                        $progress="<i class='fa-solid fa-arrow-down text-danger'></i>";
                                                                    }else{
                                                                        $progress="<i class='fa-solid fa-minus text-warning'></i>";
                                                                    }
                                                                    $str .="<tr>";
                                                                    $str .="<td>".$no."</td>";
                                                                    $str .="<td>".$tes['nip']."</td>";
                                                                    $str .="<td>".$tes['nama']."</td>";
                                                                    $str .="<td>".$tes['perusahaan']."</td>";
                                                                    $str .="<td class='text-center'>".$pre."</td>";
                                                                    $str .="<td class='text-center'>".$score."</td>";
                                                                    $str .="<td class='text-center'>".$status."</td>";
                                                                    $str .="<td class='text-center'>".$progress."</td>";
                                                                    $str .="</tr>";
                                                                    $no++;
                                                                }
                                                                echo $str;
                                                            ?>
                                                        </tbody>
                                                    </table>
                                    </div>                  
                                </div>
                                <div class="tab-pane fade p-3" id="TabPresensi" role="tabpanel" aria-labelledby="three-tab">
                                            <h5 class="card-title">Presensi Peserta</h5>
                                            <div class="table-responsive">
                                                    <table class="table" id="tbPresensi" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nip</th>
                                                                <th>Nama</th>
                                                                <th>Entitas</th>
                                                                <th>Tanggal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $no=1;
                                                            $str="";
                                                                foreach($absen as $ab){
                                                                    $str.="<tr>";
                                                                    $str.="<td>".$no."</td>";
                                                                    $str.="<td>".$ab['member_nip']."</td>";
                                                                    $str.="<td>".$ab['member_name']."</td>";
                                                                    $str.="<td>".$ab['group_name']."</td>";
                                                                    $str.="<td>".parseDateShortReadable($ab['cra_create_date']).",". parseTimeReadable($ab['cra_create_date'])."</td>";
                                                                    $str.="</tr>";
                                                                    $no++;
                                                                }
                                                                echo $str;
                                                            ?>  
                                                        </tbody>
                                                    </table>
                                                </div>          
                                            </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PESERTA -->
        <!-- EVALUASI --> 
        <div class="row">
            <div class="col-md-12 col-xs-12 my-2">
                <div class="card shadow" style="border-radius:20px">
                    <div class="card-header" style="background-color:#0a3d62;border-top-left-radius:20px;border-top-right-radius:20px">
                        <div class="card-title text-white"><h4>Evaluasi</h4></div>
                    </div>
                    <div class="card-body">
                          <div class="row">
                            <div class="col-md-4 col-xs-12">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Penyelenggaraan</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?= isset($NPS['penyelenggaraan']['total'])?$NPS['penyelenggaraan']['total']:"0"?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-sign-language fa-2x text-warning"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                   Sarana</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">   <?= isset($NPS['sarana']['total'])?$NPS['sarana']['total']:"0"?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-building fa-2x text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="card border-left-secondary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                   Narasumber</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">   <?= isset($NPS['narasumber']['total'])?$NPS['narasumber']['total']:"0"?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-info"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>      
                          <div class="row">
                            <div class="col-md-12 col-xs-12 mt-4">
                                <ul class="nav nav-tabs card-header-tabs" id="evaluasiTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" id="one-tab" data-toggle="tab" href="#TabPenyelenggaraan" role="tab" aria-controls="One" aria-selected="true">Penyelenggaraan</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="two-tab" data-toggle="tab" href="#TabSarana" role="tab" aria-controls="Two" aria-selected="false">Sarana</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="three-tab" data-toggle="tab" href="#TabNarasumber" role="tab" aria-controls="Three" aria-selected="false">Narasumber</a>
                                        </li>
                                </ul>
                                <div class="tab-content" id="evaluasiTab">
                                    <div class="tab-pane fade show active p-3" id="TabPenyelenggaraan" role="tabpanel" aria-labelledby="one-tab">
                                        <div class="table-responsive">
                                            <table class="table" id="tbp">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Komponen</th>
                                                        <th>Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    $str="";
                                                    $no=1;
                                                    if(isset($NPS['penyelenggaraan'])){
                                                        foreach($NPS['penyelenggaraan'] as $p){
                                                            $str.="<tr>";
                                                            $str.="<td>".$no."</td>";
                                                            $str.="<td>".$p['soal']."</td>";
                                                            $str.="<td>".$p['score']."</td>";
                                                            $str.="</tr>";
                                                            $no++;
                                                        }
                                                    }
                                                    
                                                    echo $str;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade p-3" id="TabSarana" role="tabpanel" aria-labelledby="one-tab">
                                        <div class="table-responsive">
                                            <table class="table" id="tbp">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Komponen</th>
                                                        <th>Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $str="";
                                                    $no=1;
                                                    if(isset($NPS['sarana'])){
                                                        foreach($NPS['sarana'] as $p){
                                                            $str.="<tr>";
                                                            $str.="<td>".$no."</td>";
                                                            $str.="<td>".$p['soal']."</td>";
                                                            $str.="<td>".$p['score']."</td>";
                                                            $str.="</tr>";
                                                            $no++;
                                                        }
                                                    }
                                                 
                                                    echo $str;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade p-3" id="TabNarasumber" role="tabpanel" aria-labelledby="one-tab">
                                        <div class="table-responsive">
                                            <table class="table" id="tbp">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Komponen</th>
                                                        <th>Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                    $str="";
                                                    $no=1;
                                                    if(isset($NPS['narasumber'])){
                                                        foreach($NPS['narasumber'] as $p){
                                                            $str.="<tr>";
                                                            $str.="<td>".$no."</td>";
                                                            $str.="<td>".$p['soal']."</td>";
                                                            $str.="<td>".$p['score']."</td>";
                                                            $str.="</tr>";
                                                            $no++;
                                                        }
                                                    }
                                                   
                                                    echo $str;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>                                      
                    </div>
                </div>                               
            </div>
        </div>
        <!-- END EVALUASI -->

        <!-- DOKUMENTASI --> 
        <div class="row">
            <div class="col-md-6 col-xs-12 my-2">
                <div class="card shadow" style="border-radius:20px;">
                   <div class="card-body">
                    <div class="box-part text-center">
                            
                            <i class="fas fa-calendar fa-4x" style="color:#487eb0"></i>
                            
                            <div class="title mt-4">
                                <h4>Dokumentasi Absensi</h4>
                            </div>
                            
                            <div class="text mb-4">
                                <span>Absensi secara hardprint digunakan sebagai bukti kehadiran peserta secara <i>realtime</i></span>
                            </div>
                            <?php
                                if($kelas['lk_absen']!="-" && $kelas['lk_absen']!=""){
                                    ?>
                                        <a href="<?=$kelas['lk_absen']?>" class="btn btn-rounded btn-sm" style="background-color:#40739e"><i class="fa-solid fa-chevron-right text-white"></i></a>
                                    <?php
                                }else{
                                    ?>
                                        <span class="badge badge-secondary">Tidak tersedia</span>
                                    <?php
                                }
                            ?>
                         
                            
					 </div>
                   </div>
                </div>
            
            </div>
            <div class="col-md-6 col-xs-12 my-2">
                    <div class="card shadow" style="border-radius:20px;">
                        <div class="card-body">
                            <div class="box-part text-center">
                                    
                                    <i class="fas fa-folder fa-4x" style="color:#f0932b"></i>
                                    
                                    <div class="title mt-4">
                                        <h4>Dokumentasi Kelas</h4>
                                    </div>
                                    
                                    <div class="text mb-4">
                                        <span>File dokumentasi pelaksanaan kelas baik foto, video maupun dokumen lain.</span>
                                    </div>

                                    <?php
                                if($kelas['lk_dokumentasi']!="-" && $kelas['lk_dokumentasi']!=""){
                                    ?>
                                            <a href="<?=$kelas['lk_dokumentasi']?>" class="btn btn-rounded btn-sm" style="background-color:#f9ca24"><i class="fa-solid fa-chevron-right text-white"></i></a>
                                    <?php
                                }else{
                                    ?>
                                        <span class="badge badge-secondary">Tidak tersedia</span>
                                    <?php
                                }
                            ?>
                                    
                               
                                    
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- END DOKUMENTASI -->
</div>
      

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
         

            Swal.fire({
                position: "top-end",
                type: "warning",
                title: "Fitur ini masih dalam tahap pengembangan",
                showConfirmButton: false,
                timer: 1500
                });
    });
</script>