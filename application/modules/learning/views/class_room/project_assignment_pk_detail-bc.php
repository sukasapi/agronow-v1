<?php $this->load->view('learning/app_header'); ?>
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
            <div id="content">
                <div class="text-center pb-4">
                    <h3>Project Assignment</h3>
                    <h5 class="text-muted">Progress Form</h5>
                    <?php $pa[0]->pa_status =="open" || $pa[0]->pa_status =="draft" ? $stat="":$stat="readonly"; ?>
                        <input type="hidden" name="token" id="pa_token" value="<?=$this->encryption->encrypt($pa[0]->pa_id)?>">
                        <?php 
                            if($pa[0]->pa_status =="open" || $pa[0]->pa_status =="draft"){
                                ?>
                                <span class="badge badge-pill badge-primary">Draft</span>
                                
                                <?php
                            }else{
                                ?>
                                    <span class="badge badge-pill badge-success">Final</span>
                                <?php
                            }
                            ?>
                </div>
            
                <div class="row pb-2" >
                    <div class="col-6 col-sm-6 col-xs-6">
                        <img src="<?=base_url("assets/img/avatar.png")?>" style=" max-width: 100%;height:auto" class="img-resonsive rounded float-left" alt="...">
                        
                    </div>
                    <div class="col-6 col-sm-6 col-xs-6" style="max-height: 200px;">      
                        <p class="mb-1"><strong>Nama : </strong><?=$pa[0]->member_name?></p>
                        <p class="mb-1"> <strong>Perusahaan : </strong><?=$pa[0]->group_name?></p>
                        <p class="mb-1"> <strong>Program : </strong><?=$pa[0]->cr_name?> </p>
                        <p class="mb-1"> <strong>Jabatan : </strong><?=$pa[0]->pa_jabatan?></p>
                        <p class="mb-1"> <strong>Atasan : </strong><?=$pa[0]->nama_atasan?></p>
                    </div>
                </div>
                
                <div class="row pb-4">
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="card" style="width:100%">
                            
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" style="border:none">
                                            <tr>
                                                <td class="txtlabel" style="vertical-align: middle;"><strong>Problem</strong></td>
                                                <td><input type="text" class="form-control" id="problem"name="problem" <?=$stat?> value="<?=$pa[0]->pa_problem?>"></td>
                                                <td rowspan="3">
                                                    <label for="progress_outcome">% Progress Outcome</label>
                                                    <input readonly class="form-control" name="progress_outcome" id="progress_outcome" value="<?=$pa[0]->pa_progress?>">
                                                    <div class="text-center py-4">
                                                        <button class="btn btn-warning" id="btv_progress">Lihat progress</button>
                                                    </div>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="txtlabel" style="vertical-align: middle;"><strong>Solution</strong></td>
                                                <td><input readonly type="text" class="form-control" id="solution" name="solution" <?=$stat?> value="<?=$pa[0]->pa_solution?>"></td>
                                            
                                            </tr>
                                            <tr>
                                                <td class="txtlabel" style="vertical-align: middle;"><strong>Time Frame</strong></td>
                                                <td><input readonly type="text" class="form-control" name="timeframe" id="timeframe" <?=$stat?> value="<?=$pa[0]->pa_timeframe?>"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
            
                    <div class="row pb-4">
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="card" style="width:100%">
                            <div class="card-header bg-info ">
                                <h4 class="text-white"> List Task</h4>
                            </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table" id="asgn_list" >
                                                    <thead >
                                                        <tr>
                                                        <th scope="col" class="txtlabel text-white">Program</th>
                                                        <th scope="col" class="txtlabel text-white">Deliverable</th>
                                                        <th scope="col" class="txtlabel text-white">Outcome</th>
                                                        <th scope="col" class="txtlabel text-white">Progress</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            foreach($detail_pa as $da){
                                                                $progresstask=$da->pad_progress;
                                                                echo "<tr>";
                                                                echo "<td>".$da->pad_program."</td>";
                                                                echo "<td>".$da->pad_deliverable."</td>";
                                                                echo "<td>".$da->pad_outcome."</td>";
                                                                echo "<td><div class='input-group'>
                                                                        <input readonly type='number' min='0' max='100' class='form-control taskpg' value='".$progresstask."'  data-token='".$this->encryption->encrypt($da->pad_id)."'>
                                                                        <div class='input-group-append'>
                                                                            <span class='input-group-text'>
                                                                                %
                                                                            </span>
                                                                        </div>
                                                                    </td>";
                                                                echo "</tr>";
                                                            }

                                                        ?>
                                                    </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                
                <div class="row pb-4">
                    <div class="card" style="width:100%">
                        <div class="card-header bg-info ">
                            <h4 class="text-white">File Project Assignment</h4>
                        </div>
                        <div class="card-body text-center">
                            <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#mfile">
                                            Lihat File
                                                </button>
                        </div>
                    </div>            
                </div>
                <div class="row pb-4">
                    <div class="card" style="width:100%">
                        <div class="card-header bg-info ">
                            <h4 class="text-white">Catatan Khusus</h4>
                        </div>
                        <div class="card-body text-center">
                        <textarea readonly class="form-control" name="catatan" id="catatan"><?=$pa[0]->pa_catatan;?></textarea>
                        </div>
                    </div>            
                </div>
            </div>
            <div class="row pb-4">
                <div class="card" style="width:100%">
                    <div class="card-header bg-info ">
                        
                        <h4 class="text-white"> Cetak / unduh </h4>
                    </div>
                    <div class="card-body text-center">
                        <p><small>*Screen shoot layar </small></p>
                        <input type="hidden" id="dokname" value="<?=$pa[0]->member_name."-".$pa[0]->cr_name?>">
                        <div id="editor"></div>
                       <button class="btn btn-primary btn-rounded btn_block" id="btdownload">Unduh</button>
                    </div>
                </div>            
            </div>

        </div>
    </div>
</div>
<!-- * App Capsule -->

<!--- MODAL --> 
<div class="modal fade" id="mfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog   modal-lg" role="document" style="height:100%">
    <div class="modal-content" style="height:80%">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">File Project Assignment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php 
            $filep=base_url("media/project_assignment/".$pa[0]->pa_file);
        ?>  
       <object
	data="<?=$filep?>"
	type="application/pdf"
	width="100%"
	height="100%"
>
	<p>
		Perangkat tidak mendukung tampilan  PDFs.
		<a href="<?=$filep?>">Download file</a>
		.
	</p>
</object>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


<!-- END MODAL -->
<script>
    $(document).ready(function () {
        var baseUrl='<?=base_url()?>';
        
        $("#goPA").on("click",function(e){
            e.preventDefault();
            var pa_tkn=$("#daftar").val();
            var goto =baseUrl+"learning/class_room/isi_progress_pa?tkn="+pa_tkn;
           location.replace(goto);
            // alert("redirect to "+ baseUrl +"/isi_progress_ta?tkn="+pa_tkn);
        });

   
       


        $("#btdownload").on("click",function(e){
            var tidok=$("#dokname").val() + ".pdf";
            var element = document.getElementById('content');
            var opt = {
                    margin:       1,
                    filename:     tidok,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2 },
                    jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                    };

                    // New Promise-based usage:
                   // html2pdf().set(opt).from(element).save();

                    // Old monolithic-style usage:
                   html2pdf(element, opt);
        })

        $("#btv_progress").on("click",function(e){
        e.preventDefault();
        var idpa='<?=$pa[0]->pa_id?>';
        location.replace(baseUrl+"learning/class_room/individual_scoreboard?pa_id="+idpa);
    })
     

    })
</script>