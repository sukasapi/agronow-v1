<?php $this->load->view('learning/app_header'); ?>
<style type="text/css">
    img{
        width: 100%;
        height: auto;
    }
    .btn-label{
        background: rgba(0, 0, 0, 0.05);
        display: inline-block;
    }
</style> 

<div id="appCapsule">
    <div class="section full">
    <h3 class="text-center m-2">ASSIGNMENT MODUL<?=$modul_urut+1?></h3>
    <div class="m-2">
            <div class="card mb-2 ">
                <div class="card-header p-2 bg-warning">Informasi Modul Assignment</div>
                <div class="card-body">
                    <p><?=$info?></p>
                </div>
            </div>
    </div>
        <div class="m-2">
            <div class="card mb-2">
                <div class="card-header p-2 bg-primary text-white" >File Assignment</div>
                <div class="card-body text-center ">
                
                    <?php 
                        $cr_id=$data_ma[0]->classroom_id;
                        $mb_id=$data_ma[0]->member_id;
                        $md_urut=$data_ma[0]->urut_modul;
                    ?>
                    <input type="hidden" id="crid" name="cr_id" value="<?=$cr_id?>">
                    <input type="hidden" id="mbid" name="mb_id" value="<?=$mb_id?>">
                    <input type="hidden" id="murut" name="md_urut" value="<?=$md_urut?>">
                    <?php 
                        $stat=$data_ma[0]->status_ma;
                        $isfile=$data_ma[0]->file_assignment==""?"kosong":$data_ma[0]->file_assignment;
                        if($isfile =="kosong"){
                            ?>
                                <h4 class="text-center pb-2">Upload file Assignment untuk modul ini</h4>
                                <div class="form-group">
                                    <input type="file" name="fileUpload" id="fileUpload" class="form-control" >
                                </div>
                            <?php
                        }else{
                            $path=$cr_id."_".$mb_id."_".$md_urut;
                            if($stat == "final" || $stat=="check"){
                               ?>
                                <p class="text-center pb-2">File Assignment telah disimpan final. <span>Lihat file assignment anda?</span></p>
                                
                                <button class="btn btn-info btn-rounded btview"  data-path="<?=$path?>">Lihat File</button>
                               
                               <?php
                            }else{
                              ?>
                              <p class="text-center pb-2">File Assignment telah anda upload pada module ini.</p>
                                <button class="btn btn-info btn-rounded btview" data-path="<?=$path?>">Lihat File</button>
                                <hr>
                                <h4 class="text-center py-2">Atau Re-upload Assignment</h4>
                                <div class="form-group">
                                    <input type="file" name="fileUpload" id="fileUpload" class="form-control">
                                </div>
                              <?php
                            }
                            ?>


                                
                                
                            <?php
                        }
                    ?>
                
                    
                </div>
            </div>
        </div>
        <?php
             if($stat == "final" || $stat=="check"){
                ?>

                <?php
             }else{
                ?>
        <div class="m-2">
            <div class="card mb-2 text-center">
                  <div class="card-body pt-2">
                    <p class="text-center"> Simpan final Assignment modul ini ?</p>
                    <button id="btfinal" class="btn btn-primary">Simpan Final</button>
                  </div>          
            </div>
        </div>
                <?php
             }
        ?>
       
    </div>

</div>


<script>
     $(document).ready(function () {
        var baseUrl='<?=base_url()?>';
        var urlact=baseUrl+"learning/Class_room/ma_ajax";

        $("#fileUpload").on('change',function(e){
            e.preventDefault();
            var data = new FormData();
            const filedoc=$('#fileUpload').prop('files')[0];
            var validfile=filevalid(filedoc);
            if(validfile){
                data.append("act", "uploadFile");
                data.append("crid",$('#crid').val());
                data.append("mbid",$('#mbid').val());
                data.append("murut",$('#murut').val());
                data.append("file",filedoc);
                
                
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
                        var respon=JSON.parse(response);
                        if( respon== "ok"){
            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'anda telah menyimpan Assignment untuk modul ini',
                                }).then(function(){
                                location.reload();
                            });
                        }else{
                        
                            Swal.fire({
                                icon: 'error',
                                title: 'Upss',
                                text: 'anda gagal menyimpan assignment, silahkan cek inputan anda kembali',
                                }).then(function(){
                                    location.reload();
                                });
                                
                        }
                    }

                })
                
            }else{
                Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda gagal mengupload file, silahkan cek format untuk melakukan reupload',
                            }).then(function(){
                                location.reload();
                            });
            }
           
        })


        function filevalid(file){
            //var  sel_files  = document.getElementById('upload').files;
           
            var result=false;
            var ext = file.name.split(".").pop().toLowerCase();
            if($.inArray(ext, ["doc","pdf","docx","xls","xlsx","ppt","pptx"]) == -1) {
                result =false;
                // false
            }else{
                result =true;
                // true
            }
          
            return result;
        }


        $("#btfinal").on("click",function(e){
            e.preventDefault();
            var data = new FormData();
            var stat="final";
            data.append("act", "update_ma");
            data.append("crid",$('#crid').val());
            data.append("mbid",$('#mbid').val());
            data.append("murut",$('#murut').val());
            data.append("status",stat);
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
                        var respon=JSON.parse(response);
                        if( respon== "ok"){
            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'anda telah menyimpan Assignment untuk modul ini',
                                }).then(function(){
                                location.reload();
                            });
                        }else{
                        
                            Swal.fire({
                                icon: 'error',
                                title: 'Upss',
                                text: 'anda gagal menyimpan assignment, silahkan cek inputan anda kembali',
                                }).then(function(){
                                    location.reload();
                                });
                                
                        }
                    }
                })
        })

        $(".btview").on("click",function(e){
            e.preventDefault();
            var pathnext=$(this).data('path');
            var modulno=$("#murut").val();
            var urlnext=baseUrl+"learning/Class_room/readpdf?modul="+modulno+"&act=module&pathnext="+pathnext;
            location.replace(urlnext);
        })
     })
</script>