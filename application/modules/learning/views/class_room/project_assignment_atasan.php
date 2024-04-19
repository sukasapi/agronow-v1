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
            <div class="text-center pb-4">
                <h3>Project Assignment</h3>
                <h5 class="text-muted">Participant Dashboard</h5>
            </div>
            <div class="row py-4">
                
                <div class="col-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card rounded" style="box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                        <div class="card-header text-center " style="background-color:#ff9f43">
                            <h4 class="text-white">PROJECT ASSIGNMENT</h4>
                        </div>
                        <div class="card-body">
                       
                        <div class="form-group">
                            <label for="nama">Daftar Assignment</label>
                            <select name="daftar" id="daftar" class="form-control">
                                <?php
                                $datasearch=""; 
                                
                                echo "<option value=''>* Pilih nama pemilik Project Assignment</option>";
                                    foreach($project_assignment as $pa){
                                        echo "<option value='".$pa->pa_id."'>".$pa->nama." - Kelas : ".$pa->cr_name."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="act" value="isiprogress">
                        <div class="text-center">
                            <input type="submit" class="btn btn-rounded btn-block btn-primary text-center" id="goPA" value="Periksa Project Assignment">
                        </div>
                     
                        </div>
                    </div>
                  
                </div>
            </div>
            <div class="row py-4">
                <div class="col-12 col-sm-12 text-center">
                    <h2><strong>ATAU</strong></h2>
                </div>
            </div>
            <div class="row py-4">
                
                <div class="col-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card rounded" style="box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                        <div class="card-header text-center " style="background-color:#ff9f43">
                            <h4 class="text-white">SCOREBOARD</h4>
                        </div>
                        <div class="card-body">
                       
                        <div class="form-group">
                            <label for="nama">Daftar Project</label>
                            <select name="kelas" id="kelas" class="form-control">
                                <?php
                                $datasearch=""; 
                                
                                echo "<option value=''>* Pilih Kelas / Project</option>";
                                    foreach($project_assignment as $pa){
                                        echo "<option value='".$pa->cr_id."'> PROJECT : ".$pa->cr_name."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="act" value="isiprogress">
                        <div class="text-center">
                            <input type="submit" class="btn btn-rounded btn-block btn-primary text-center" id="goSB" value="lihat Scoreboard">
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
        
        $("#goPA").on("click",function(e){
            e.preventDefault();
            var pa_tkn=$("#daftar").val();
            console.log(pa_tkn);
            if(pa_tkn==''){
            	Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda harus memilih kelas dan peserta dahulu',
                            });
            }else{
            	var goto =baseUrl+"learning/class_room/isi_progress_pa?tkn="+pa_tkn;
            	location.replace(goto);
            	
            }
            
            // alert("redirect to "+ baseUrl +"/isi_progress_ta?tkn="+pa_tkn);
        })
        $("#goSB").on("click",function(e){
            e.preventDefault();
            var pa_tkn=$("#kelas").val();
            if(pa_tkn==''){
            	 Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda harus memilih kelas terlebih dahulu',
                            });
            }else{
            var goto =baseUrl+"learning/class_room/class_scoreboard?tkn="+pa_tkn;
            location.replace(goto);
            }
            // alert("redirect to "+ baseUrl +"/isi_progress_ta?tkn="+pa_tkn);
        })

    })
</script>