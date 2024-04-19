<?php $this->load->view('learning/app_header'); ?>
<style type="text/css">
	#desc * { color: #FFFFFF !important; } 
    div.alert p{
        margin-bottom: 0px;
    }
    .btn-label{
        background: rgba(0, 0, 0, 0.05);
        display: inline-block;
    }
    #appCapsule a.btn{
        height: auto;
    }
  
</style> 

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-2" >
            <?php 
                if(isset($npsdata) && count((Array)$npsdata)> 0){
            ?>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 mb-4">
                            <h3 class="text-center">Pengisian Evaluasi NPS</h3>
                            <div class="card card-margin">
                                <div class="card-body">
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?=$persen?>%" aria-valuenow="<?=$persen?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="text-center"><?php echo $terjawab." Dari ".$tersedia?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 mt-4 mb-2">
                            <h3 class="text-center">Daftar Evaluasi</h3>
                        </div>
                    <?php 
           
                    $pengajar="";
                        foreach($npsdata as $nd){
                          
                            switch($nd['jenis']){
                                case 'penyelenggaraan':
                                    $bg ="";
                                    $text="text-primary";
                                    $btn ="btn-outline-primary";
                                break;
                                case 'sarana':
                                    $bg ="";
                                    $text="text-warning";
                                    $btn ="btn-outline-warning";
                                break;
                                case 'narasumber': 
                                    $bg ="";
                                    $text="text-info";
                                    $btn ="btn-outline-info";
                                break;
                            }
                    ?>
                       
                        <div class="col-md-4 col-xs-12 mb-2">
                     
                            <div class="card card-margin <?=$bg?>" style="height:200px">
                                <div class="card-body py-auto">   
                                    <h5 class="card-title text-center <?=$text?>"><?=ucfirst($nd['jenis'])?></h5>   
                                     <?php
                                        if($nd['jenis']=="narasumber"){
                                    ?>
                                        <div class="card-text text-center">feedback terhadap <strong><?=isset($nd['pengajar'])&&$nd['pengajar']?ucfirst($nd['pengajar']):""?> </strong> sebagai pengajar</div>
                                    <?php
                                        }else{
                                            ?>
                                        <div class="card-text text-center">feedback terkait dengan <?=$nd['jenis']?> kelas</div>
                                    <?php  
                                        }
                                     ?>
                               </div>
                               <div class="card-footer text-muted mx-auto">
                                    <div class="text-center">
                                        <?php
                                          
                                            $kode=$kelas."-".$nd['jenis']."-".$nd['pengajar'];
                                            if($nd['jawab']==""){
                                                echo "<a class='btn btn-rounded ".$btn."' href='".base_url('evaluasi/evaluasi_mulai/').$kode."'>Evaluasi</a>";    
                                            }else{
                                                echo "<a class='btn btn-success' disable>Done</a>";    
                                            }
                                        ?>
                                    </div>
                               </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            <?php
                }else{
            ?>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 mb-4">
                            <h3 class="text-center">Pengisian Evaluasi NPS</h3>
                            <div class="card card-margin">
                                <div class="card-body text-center">
                                 <h2> Pelatihan ini tidak membutuhkan evaluasi NPS</h2>
                                 <div class="row">
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <a href="<?=base_url('evaluasi')?>" class="btn btn-rounded btn-warning">Kembali</a>
                                    </div>
                                 </div>
                                </div>
                            </div>
                        </div>

                    </div>
            <?php
                }
            ?>
                
               
        </div>
    </div>
</div>