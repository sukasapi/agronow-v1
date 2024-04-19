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

			<!--
            <?php if(has_access('member.create',FALSE) OR has_access_manage_all_member()): ?>
            <a href="<?php echo site_url("member/create/"); ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>
			<?php endif; ?>
			-->

        </div>


    </div>
    <!-- end:: Subheader -->

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <!-- FILTERS -->
        <div class="row py-4">
            <div class="col-md-12 col-xs-12">
                <form id="filter" action="<?=base_url('learning_wallet/registrasi_peserta_agrowallet')?>" method="POST" enctype="multipart/form-data">
                    <div class="card" style="background-color:#303952">
                        <div class="card-body">
                            <div class="text-center">
                            <h4 class="text-white">Pencarian Kelas</h4>
                            </div>
                            <div class="form-group">
                                <select name="filter" class="form-control">
                                    <option selected value="all">Semua</option>  
                                    <?php 
                                       
                                        foreach($kelas as $k){
                                            $txt = "<b>(".$k->kode.")</b> - ".$k->nama;
                                            if($k->id == $select){
                                                echo "<option selected value='".$k->id."'>".$txt."</option>";
                                            }else{
                                                echo "<option value='".$k->id."'>".$txt."</option>";
                                            }
                                        }
                                    ?>
                                </select>
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
                    <div class="card-header">
                        <div class="">
                        <a href="<?=base_url('learning_wallet/import_peserta_agrowallet')?>" class="btn btn-info float-right">Registrasi Peserta</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php 

                        ?>
                        <div class="text-center">
                            <h4 >Daftar Peserta Kelas Agrowallet Non Reguler</h4>
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
                                        <th>Member</th>
                                        <th>Level</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php 
                                        $i=1;
                                        $str ="";
                                        foreach($peserta as $key=>$val){
                                            foreach ($val as $p){
                                                if($p->uid == $p->id_user){
                                                    $ismember = "<a href='".base_url("member/detail/").$p->id_user."' class='badge badge-pill badge-info'>member</badge></a>";
                                                }else{
                                                    $ismember = "<a href='' class='badge badge-pill badge-danger'>non member</badge></a>";
                                                }
                                                $str.="<tr>";
                                                $str.="<td>".$i."</td>";
                                                $str.="<td>".$p->nama." (".$p->kode.")"."</td>";
                                                $str.="<td>".$p->nip."</td>";
                                                $str.="<td>".$p->npeserta."</td>";
                                                $str.="<td>".$p->group_name."</td>";
                                                $str.="<td>".$p->group_name."</td>";
                                                $str.="<td>".$ismember."</td>";
                                                $str.="<td></td>";
                                                $str.="</tr>";
                                                $i++;
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

    <script>
    $(document).ready(function(){
    
        $('#listpeserta').DataTable();
    
    });
    </script>