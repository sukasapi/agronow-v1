<?php $this->load->view('learning/app_header');?>
<style>
    .centertext {
        display: block;
        text-align: center;
        font-size: 1em;
    }
    .txtlabel{
        color : white;
       background-color: #008000;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full prelearning">
        <div class="p-3 pb-5">
            <div class="text-center pb-4">
                <h3>Daftar Peserta</h3>
            </div>
         
            <div class="row py-2">          
                <div class="col-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card rounded" style="box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbmember" class="table table-border">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th></th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                    
                                        $i=1;
                                    
                                            foreach($personel as $p){

                                                $openstat="";
                                                switch($p['status']){
                                                    case 'draft' :
                                                        if($p['progres'] >= 100){
                                                           $progress= "<span class='badge badge-success'><small>Selesai</small></span>";
                                                        }else{
                                                            $progress= "<span class='badge badge-warning'><small>Pengisian</small></span>";
                                                        }
                                                       
                                                    break;
                                                    case 'progress' :
                                                        $openstat="
                                                       <button class='btn btn-success btn-sm btn-rounded btopen'  data-token='".$p['pa_id']."'><i class='fa fa-unlock' aria-hidden='true'></i></button>";
                                                        if($p['progres'] >= 100){
                                                            $progress= "<span class='badge badge-success'><small>Selesai</small></span>";
                                                        }else{
                                                            $progress= "<span class='badge badge-primary'><small>Penilaian</small></span>";
                                                        }
                                                    break;
                                                    case 'open' :
                                                        if($p['progres'] >= 100){
                                                            $progress= "<span class='badge badge-success'><small>Selesai</small></span>";
                                                        }else{
                                                            $progress= "<span class='badge badge-warning'><small>pengisian</small></span>";
                                                        }
                                                    break;
                                                    default:
                                                        if($p['progres'] >= 100){
                                                            $progress= "<span class='badge badge-success'><small>Selesai</small></span>";
                                                        }else{
                                                            $progress= "<span class='badge badge-danger'><small>belum</small></span>";
                                                        }
                                                    break;
                                                }
                                              
                                                ?>
                                                 <tr>
                                                    <td><?=$i?></td>
                                                    <td><?=$openstat?></td>
                                                    <td><?=$p['nip']?></td>
                                                    <td><?=$p['nama']?></td>
                                                    <td><?=$p['progres']?> %</td>
                                                    <td><?=$progress?></td>
                                                    <?php 
                                                        if($p['file']!="" || !empty($p['file'])){
                                                            $file= "<a class='dropdown-item' href='#'>File</a>";
                                                        }else{
                                                            $file= "";
                                                        }
                                                    
                                                    ?>
                                                
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Aksi
                                                            </button>
                                                            <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="<?=base_url('learning/class_room/cek_detail_pa_bypk/').$p['pa_id'];?>">Detail</a>
                                                                <?=$file?>
                                                              
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                                <?php
                                                $i++;
                                            }
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
<!-- * App Capsule -->

<script>
   $(document).ready(function () {
    var baseUrl='<?=base_url()?>';
    var pa_token=$("#pa_token").val();
    $("#tbmember").DataTable({
        responsive :true,
        //scrollX: true,
        pageLength: 20,
        dom: 'Bfrtip',
			buttons: [
				{
                extend: 'excel'
            	},
				{
                extend: 'pdf'
            },

			]
    });
    $('#tbmember tbody').on('click', '.btopen', function () {
        var dpaid=$(this).data("token");
        var statupd="draft";
        var datapa = new FormData();
        datapa.append("token",dpaid);
        datapa.append("stat",statupd);
        datapa.append("act","updatestatus");
        console.log(dpaid);
        $.ajax({
                url: baseUrl+"" + "learning/Class_room/pa_ajax",
                type:"post",
                data:datapa,
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                    Swal.fire({
                        icon: 'info',
                        title: 'Membuka Kunci',
                        text: 'Pengunci Project Assignment Peserta telah dibuka.',
                        }).then(function(){
                                    location.reload();
                        })
                }
                
            
            })
       
    });
   

    });
</script>
