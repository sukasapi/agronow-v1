<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
            <input type="hidden" id="namapage" value="<?= $page_sub_name;?>">
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("classroom"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <!-- body -->
        <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="row">
                <?php
                    $this->load->view('flash_notif_view');
                 
                ?>
            
                <div class="col-md-12 col-xs-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter</h4>
                        </div>
                        <div class="card-body">
                            <?php 
                                $dstart=$start!=""?$start:"";
                                $dend=$end!=""?$end:"";
                            ?>
                            <form id="filter" action="<?=base_url('classroom/test_result')?>" method="POST" enctype="multipart/form-data">
                                <div class="row mb-2">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="start">Tanggal Mulai</label>
                                            <input type="date"class="form-control" id="startDate" name="startDate" value="<?=$start?>"> 
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="end">Tanggal Selesai</label>
                                            <input type="date"class="form-control" id="endDate" name="endDate" value="<?=$end?>"> 
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" name="filter" value="Filter" class="btn btn-primary btn-rounded btn-block">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="card-header">
                    <div class="row">
                            <div class="col-md-8 col-xs-12">
                                <ul class="nav nav-tabs card-header-tabs" id="menubar" style="margin-bottom:0px;">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Kelas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Peserta</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="three-tab" data-toggle="tab" href="#three" role="tab" aria-controls="Three" aria-selected="false">Feedback</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div id="menu" class="float-right mb-2"></div>
                                <div id="menu2" class="float-right mb-2" hidden></div>
                                <div id="menu3" class="float-right mb-2" hidden></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                                <div class="table-responsive">
                                        <table class="table" id="tbDatakelas">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Kelas.</th>
                                                    <th>Peserta</th>
                                                    <th>Pre Test</th>
                                                    <th>Post Test</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $no=1;
                                                    $str1="";
                                                    foreach($kelas as $nkelas=>$data){
                                                        $jpeserta =count((array)$data['score']);
                                                        $pretes=array_sum(array_column($data['score'], 'pre')); 
                                                        $posttes=array_sum(array_column($data['score'], 'post')); 
                                                        $idkelas=$data['peserta'][0]['cr_id'];
                                                        if($idkelas==""){

                                                        }else{
                                                            $str1.="<tr>";
                                                            $str1.="<td>".$no.""."</td>";
                                                            $str1.="<td>".$nkelas."(".$idkelas.")</td>";
                                                            $str1.="<td>".$jpeserta."</td>";
                                                            $str1.="<td>".round($pretes/$jpeserta,2)."</td>";
                                                            $str1.="<td>".round($posttes/$jpeserta,2)."</td>";
                                                            $str1.="</tr>";
                                                            $no++;
                                                        }                                                      
                                                     
                                                    }
                                                    echo $str1;
                                                ?>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                            <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
                                    <div class="table-responsive">
                                        <table class="table" id="tbDatapeserta">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Member</th>
                                                    <th>Perusahaan</th>
                                                    <th>Kelas</th>
                                                    <th>Tanggal</th>
                                                    <th>Pre Test</th>
                                                    <th>Post Test</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $no=1;
                                                    $str="";
                                                    foreach($peserta as $p){
                                                        $dtmember="(".$p['nip'].") ".$p['nama'];
                                                        if($p['soal']!="" && $p['benar']!='' && $p['score']!=""){
                                                            $postresult=$p['soal']." | ".$p['benar']." | ".$p['score'];
                                                        }else{
                                                            $postresult="-";
                                                        }

                                                        if($p['presoal']!="" && $p['prebenar']!='' && $p['prescore']!=""){
                                                            $preresult=$p['presoal']." | ".$p['prebenar']." | ".$p['prescore'];
                                                        }else{
                                                            $preresult="-";
                                                        }
                                                      
                                                        $str.="<tr>";
                                                        $str.="<td>".$no."</td>";
                                                        $str.="<td>".$dtmember."</td>";
                                                        $str.="<td>".$p['perusahaan']."</td>";
                                                        $str.="<td>".$p['kelas']."</td>";
                                                        $str.="<td>".$p['tanggal']."</td>";
                                                        $str.="<td>".$preresult."</td>";
                                                        $str.="<td>".$postresult."</td>";

                                                        $str.="</tr>";
                                                        $no++;
                                                    }
                                                    echo $str;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>

                            <div class="tab-pane fade p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
                                    <div class="table-responsive">
                                        <table class="table" id="tbDataFeedback">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Member</th>
                                                    <th>Perusahaan</th>
                                                    <th>Kelas</th>
                                                    <th>Tanggal</th>
                                                    <th>Feedback</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $no=1;
                                                    $str="";
                                                    foreach($feedback as $f){
                                                        $fval="";
                                                        $dtmember="(".$f['nip'].") ".$f['nama'];
                                                        $feed=$f['feedback'];
                                                        if(count((array)$feed)>0){
                                                            foreach($feed as $fe){
                                                                $fval.=$fe."  || ";
                                                            }
                                                        }else{
                                                            $fval= "tidak ada";
                                                        }
                                                        $str.="<tr>";
                                                        $str.="<td>".$no."</td>";
                                                        $str.="<td>".$dtmember."</td>";
                                                        $str.="<td>".$f['perusahaan']."</td>";
                                                        $str.="<td>".$f['kelas']."</td>";
                                                        $str.="<td>".$f['tanggal']."</td>";
                                                        $str.="<td>".rtrim($fval,"||")."</td>";

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
    <!-- End Body -->
</div>
<script>
    $( document ).ready(function() {
        
        $("#startDate").on("change", function(){
            $("#endDate").attr("min", $(this).val());
        });

        $("#tbDatakelas").DataTable({
            dom: 'lBfrtip',
            buttons: [
                    {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn btn-success btn-md mr-2 btn-excel'
                    },
                    {
                        extend:    'pdf',
                        text:      '<i class="fas fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        className: 'btn btn-danger btn-md mr-2 btn-pdf'
                    },
            ]
        }).buttons().container().appendTo("#menu");
        $("#tbDatapeserta").DataTable({
            dom: 'lBfrtip',
            buttons: [
                    
                
                    {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn btn-success btn-md mr-2 btn-excel'
                    },
                    {
                        extend:    'pdf',
                        text:      '<i class="fas fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        className: 'btn btn-danger btn-md mr-2 btn-pdf'
                    },
            ]
        }).buttons().container().appendTo("#menu2");

        $("#tbDataFeedback").DataTable({
            dom: 'lBfrtip',
            buttons: [
                    {
                        extend:    'excel',
                        text:      '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        className: 'btn btn-success btn-md mr-2 btn-excel'
                    },
                    {
                        extend:    'pdf',
                        text:      '<i class="fas fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        className: 'btn btn-danger btn-md mr-2 btn-pdf'
                    },
            ]
        }).buttons().container().appendTo("#menu3");

        $('#menubar li').on('click', function () {
           
           var menuklik=$(this).find('a').attr('id');
           console.log(menuklik);
            if(menuklik=="two-tab"){
                $("#menu2").removeAttr("hidden");
                $("#menu").attr("hidden",true);
                $("#menu3").attr("hidden",true);
            }else if(menuklik=="three-tab"){
                $("#menu3").removeAttr("hidden");
                $("#menu2").attr("hidden",true);
                $("#menu").attr("hidden",true);
            } else{
                $("#menu").removeAttr("hidden");
                $("#menu2").attr("hidden",true);
                $("#menu3").attr("hidden",true);
            }
        });
    });
</script>