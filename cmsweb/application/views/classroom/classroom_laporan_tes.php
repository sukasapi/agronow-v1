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
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        </div>


    </div>
    <!-- end:: Subheader -->

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <!-- FILTERS -->
        <div class="row py-4">
            <div class="col-md-12 col-xs-12">
                <form id="filter" action="<?=base_url('classroom_report/laporan_tes_peserta')?>" method="POST" enctype="multipart/form-data">
                    <div class="card" style="background-color:#303952">
                        <div class="card-body">
                            <div class="text-center">
                            <h5 class="text-white">Pencarian Kelas</h5>
                            </div>
                            <div class="form-group">
                                <select name="kelas" class="form-control">
                                    <option selected value="all">Semua</option>  
                                    <?php 
                                       
                                        foreach($kelas as $k){
                                            $text=
                                            $txt = $k->cr_name;
                                            if($k->cr_id == $kelaspilih){
                                                echo "<option selected value='".$k->cr_id."'>".$txt."</option>";
                                            }else{
                                                echo "<option value='".$k->cr_id."'>".$txt."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                  
                            <div class="text-center">
                            <h5 class="text-white">Bulan Tahun</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="bulan" class="form-control">
                                            <option selected value="all">Semua</option>  
                                                <?php 
                                                        foreach(arrayMonth() as $id=>$bulan){
                                                            $idx=$id;
                                                            if($id==$bulanpilih){
                                                                echo "<option selected value='".$idx."'>".$bulan."</option>";
                                                            }else{
                                                                echo "<option value='".$idx."'>".$bulan."</option>";
                                                            }
                                                          
                                                        }
                                                ?>
                                            </select>
                                        </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text"  value ="<?=$tahunpilih?>"placeholder="Tahun kelas diselenggarakan" name="tahun" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <input type="submit" value="pencarian" name="bcari" class="btn btn-warning btn-rounded">
                            </div>
                        </div>
                    </div>         
                </form>
            </div>
        </div>
        <div class="row py-auto">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <?php 

                        ?>
                        <div class="text-center">
                            <h4 >Post Tes Peserta</h4>
                        </div>
                        <div class="table-responsive">
                            <table id="listpeserta" class="table table-striped table-bordered" style="width:100%" >
                                <thead> 
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Perusahaan</th>
                                        <th>Tanggal mulai</th>
                                        <th>Soal</th>
                                        <th>Jawaban Benar</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php 
                                        $i=1;
                                        $str ="";
                                        foreach($peserta as $p){
                                            $soal=$p['soal']!=""?$p['soal']:"<span class='badge badge-pill badge-danger'>tidak ada</span>";
                                            $benar=$p['benar']!=""?$p['benar']:"<span class='badge badge-pill badge-danger'>tidak ada</span>";
                                            $score=$p['score']!=""?$p['score']:"<span class='badge badge-pill badge-danger'>0</span>";
                                            $str.="<tr>";
                                            $str.="<td>".$i."</td>";
                                            $str.="<td>".$p['kelas']."</td>";
                                            $str.="<td>".$p['nip']."</td>";
                                            $str.="<td>".$p['nama']."</td>";
                                            $str.="<td>".$p['perusahaan']."</td>";
                                            $str.="<td>".$p['tanggal']."</td>";
                                            $str.="<td>".$soal."</td>";
                                            $str.="<td>".$benar."</td>";
                                            $str.="<td>".$score."</td>";
                                            $str.="</tr>";

                                            $i++;
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

    <script>
    $(document).ready(function(){
    
        $('#listpeserta').DataTable();
    
    });
    </script>