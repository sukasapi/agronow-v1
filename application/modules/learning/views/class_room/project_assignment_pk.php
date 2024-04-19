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
                <h3>Project Assignment</h3>
                <h5 class="text-muted">Pilih Kelas dan peserta untuk melihat project assignment</h5>
            </div>
            <div class="row py-2">          
                <div class="col-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card rounded" style="box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                        <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Daftar Kelas</label>
                            <select name="kelas" id="kelas" class="form-control">
                                <?php
                                    echo "<option readonly value=''>* Pilih kelas dahulu sebelum memilih peserta</option>";
                                    foreach($kelas as $d){
                                        echo "<option value='".$d['id']."'>".$d['nama']."</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nama">Daftar Peserta</label>
                            <select name="peserta" id="peserta" class="form-control">
                            </select>
                        </div>
                        <input type="hidden" name="act" value="isiprogress">
                        <div class="text-center">
                                <button class="btn btn-primary" id="btgopk">Cek Assignment</button>
                                <button class="btn btn-warning" id="btdashboard">Dashboard Kelas</button>
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

    $('#kelas').on('change',function(e){
          var urlact=baseUrl+"learning/Class_room/pa_ajax";
          var crid=$(this).val();
          var data = new FormData();
          data.append("act", "searchpeserta");
          data.append("crid", crid);
          $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: urlact,
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 800000,
                    success: function (response) {
                        console.log(response);
                        var res=JSON.parse(response);
                        var isi="";
                        isi+="<option value='all'>semua</option>";
                        for (i = 0; i < res.length; i++) {
                                
                                isi+= "<option value='"+res[i].member_id+"'>"+res[i].member_name+"</option>";
                        }
                        
                        $('#peserta').html(isi);
                    }
                })
    })

    $('#btgopk').on('click',function(e){
        var crid=$('#kelas').val();
        var mid=$('#peserta').val();
        if(crid==="" || mid==="" || mid==null){
            Swal.fire({
                icon: 'error',
                title: 'Upss',
                text: 'Anda harus memilih kelas dan member terlebih dahulu',
                }).then(function(){
                                    location.reload();
                                });
        }else{
             var urlgo=baseUrl+"learning/Class_room/cek_pa_bypk?cr_id=" + crid + "&mid=" + mid;
             location.replace(urlgo);
        }
      

      
    })

    $('#btdashboard').on('click',function(e){
        var crid=$('#kelas').val();
        if(crid==""){
            Swal.fire({ 
                icon: 'error',
                title: 'Upss',
                text: 'Anda harus memilih kelas terlebih dahulu',
                }).then(function(){
                                    location.reload();
                                });
        }else{
            var urldash=baseUrl+"learning/class_room/class_scoreboard?tkn="+crid;
            location.replace(urldash);
        }
       
    })

});

</script>