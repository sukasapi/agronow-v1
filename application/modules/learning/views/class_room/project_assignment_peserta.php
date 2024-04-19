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

<div id="appCapsule">
    <div class="section full prelearning">
    <?php
            if(!isset($pa_id) || $pa_id==""){
              
                ?>
                <div class="p-3 pb-5">
                <input type="hidden" name="token" id="pa_token" value="<?=$pa_id?>">
                    <div class="text-center pb-4">
                        <p> Data project assignment tidak ditemukan</p>
                        <a href="<?=base_url('learning/Class_room/project_assignment_pk')?>" class="btn btn-success btn-sm"><< kembali</a>
                    </div>
                </div>
                <?php
            }else{
                
                ?>
        <form  method="POST" name="fr_pa" enctype="multipart/form-data">
            <div class="p-3 pb-5">
                <div class="text-center pb-4">

                    <h3>Project Assignment</h3>
                    <h5 class="text-muted">Participant Dashboard</h5>
                   
                    <?php
                    $data_pa[0]->pa_status =="open" || $data_pa[0]->pa_status =="draft"  || $data_pa[0]->pa_status ==""? $stat="":$stat="readonly"; ?>
                    <input type="hidden" name="token" id="pa_token" value="<?=$pa_id?>">
                    <input type="hidden" name="tclass" id="pa_class" value="<?=$data_pa[0]->cr_id?>">
                    <?php 
                        
                        if($data_pa[0]->pa_status =="open" || $data_pa[0]->pa_status =="draft"|| $data_pa[0]->pa_status ==""){
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
                    <div class="col-6">
                        <img src="<?=base_url("assets/img/avatar.png")?>" style=" max-width: 100%;height:auto" class="img-resonsive rounded float-left" alt="...">
                        
                    </div>
                    <div class="col-6" style="max-height: 200px;">      
                        <p class="mb-1"><strong>Nama : </strong> <?=$_SESSION['member_name'];?></p>
                        <input type="hidden" name="idm" id="idm" value="<?=$_SESSION['member_id']?>">
                        <p class="mb-1"> <strong>Perusahaan : </strong><?=$_SESSION['member_group']?></p>
                        <p class="mb-1"> <strong>Program : </strong><?=$class['cr_name']?></p>
                        <input type="hidden" name="idcrm" id="idcrm" value="<?=$class['cr_id']?>">
                        <p class="mb-1"> <strong>Jabatan : </strong><input type="text" name="jabatan"  <?=$stat?> class="form-control" id="jabatan" value='<?=$data_pa[0]->pa_jabatan?>'> </p>
                    </div>
                </div>
                <div class="row pb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="atasan" class="centertext"><strong>Atasan</strong></label>
                        
                            <select class="form-control" <?=$stat?> id="atasan" name="atasan">
                                <option selected readonly> Pilih atasan</option>
                                <?php
                                    foreach($atasan as $a){
                                        if($data_pa[0]->atasan_id==$a->member_id && $data_pa[0]->pa_status=="open"){
                                            echo "<option value='".$a->member_id."' selected>".$a->member_name." (".$a->member_nip.")"."</option>";
                                        }else if($data_pa[0]->pa_status=="open"){
                                            echo "<option value='".$a->member_id."'>".$a->member_name." (".$a->member_nip.")"."</option>";
                                        }else if($data_pa[0]->atasan_id==$a->member_id && $data_pa[0]->pa_status!="open"){
                                            echo "<option value='".$a->member_id."' selected readonly>".$a->member_name." (".$a->member_nip.")"."</option>";
                                        }else{
                                            echo "<option value='".$a->member_id."'>".$a->member_name." (".$a->member_nip.")"."</option>";
                                        }
                                    
                                    }
                                ?>
                            </select>
                        </div>
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
                                            <td><input type="text" class="form-control" id="problem"name="problem" <?=$stat?> value="<?=$data_pa[0]->pa_problem?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="txtlabel" style="vertical-align: middle;"><strong>Solution</strong></td>
                                            <td><input type="text" class="form-control" id="solution" name="solution" <?=$stat?> value="<?=$data_pa[0]->pa_solution?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="txtlabel" style="vertical-align: middle;"><strong>Time Frame</strong></td>
                                            <td><input type="text" class="form-control" name="timeframe" id="timeframe" <?=$stat?> value="<?=$data_pa[0]->pa_timeframe?>"></td>
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
                            <div class="card-header">
                                <?php 
                                        if($stat=="readonly"){
                                            ?>
                                           
                                            <?php
                                        }else{
                                            ?>
                                                <button type="button" class="btn btn-sm btn-primary float-right btaddtask">Tambah Task</button>
                                            <?php
                                        }
                                ?>
                           
                            </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="asgn_list" >
                                                <thead >
                                                    <tr>
                                                    <th scope="col" class="txtlabel text-white">Program</th>
                                                    <th scope="col" class="txtlabel text-white">Deliverable</th>
                                                    <th scope="col" class="txtlabel text-white">Outcome</th>
                                                    <th scope="col" class="txtlabel text-center text-white">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach($detail_pa as $da){
                                                            echo "<tr>";
                                                            echo "<td>".$da->pad_program."</td>";
                                                            echo "<td>".$da->pad_deliverable."</td>";
                                                            echo "<td>".$da->pad_outcome."</td>";
                                                           echo "<td> <button class='btn-sm btn-warning bttaskedit' data-task='".$da->pad_id."' 
                                                            data-program='".$da->pad_program."'
                                                            data-deliverable='".$da->pad_deliverable."'
                                                            data-outcome='".$da->pad_outcome."'>edit</button>
                                                            <button class='btn-sm btn-danger bttaskdelete' data-task='".$da->pad_id."'>hapus</button></td>";
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
                            <h4 class="text-white"> File Project Assignment</h4>
                        </div>
                        <div class="card-body text-center">
                            <p class=""> </p>
                            <p><?php
                            if($data_pa[0]->pa_status == "open" || $data_pa[0]->pa_status == "draft"){
                                if ($data_pa[0]->pa_file=="") {
                                    echo "* Pilih file project assignment anda";
                                    
                                }else{
                                    $namafile=explode("/",$data_pa[0]->pa_file);
                                    
                                    ?><p> File Tersimpan   <span class="badge badge-pill badge-info"><?=$namafile[1]?></span></p>
                              
                                       <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#mfile">
                                       Lihat File
                                        </button>
                                       
                                    <?php
                                }
                                ?>
                                 <p id="fileassingment"></p>
                                        <input type="file" id="filepa" name="filepa" class="form-control">
                                        <small><i>* file berformat PDF dan maksimum 5MB</i></small>
                                <?php
							
                            }else{
                                if ($data_pa[0]->pa_file=="") {
                                    echo "* Tidak ada file terunggah";
                                }else{
                                    ?>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#mfile">
                                       Lihat File
                                        </button>
                                    <?php
                                }
                            }
                           
                                ?></p>
                           
                        </div>
                    </div>
                    <?php // print_r($data_pa) ?>
                </div>
                <div class="row text-center">
                             
                            <?php 
                            	if($data_pa[0]->pa_status =="open" || $data_pa[0]->pa_status =="draft" ){
                            		?>
                            		<div class="col-md-3 col-xs-6">
		                                <button class="btn btn-rounded btn-success" id="btview">Dashboard PA</button>
		                            </div>
                                    <div class="col-md-3 col-xs-6">
	                            		<button class="btn btn-rounded btn-secondary" id="btdraft">Simpan Draft</button>
	                            	</div>
                                    <div class="col-md-3 col-xs-6">
                                        <button class="btn btn-warning" id="btdashboard">Scoreboard Kelas</button>
	                            	</div>
		                            <div class="col-md-3 col-xs-6">
	                                	<button class="btn btn-rounded btn-primary" id="btsave">Simpan Assignment</button>
	                            	</div>
                           <?php 		
                            	}else{
                            	?>
                            	 	<div class="col-md-3 col-xs-6">
		                               
                                       </div>
                                       <div class="col-md-3 col-xs-6">
                                           <button class="btn btn-rounded btn-success" id="btview">Dashboard PA</button>
                                       </div>
                                       <div class="col-md-3 col-xs-6">
                                           <button class="btn btn-warning" id="btdashboard">Scoreboard Kelas</button>
                                       </div>
                                       <div class="col-md-3 col-xs-6">
                                  
                                       </div>
                            	<?php
                            	}
                            ?>
                           
                            
                
                        
                    </div>
                </div>
            </div>
        </form>

        <?php } ?>
    </div>
</div>

<!-- * App Capsule -->

<!--- Modal add detail --> 

<!-- Modal -->
<div class="modal fade" id="madd_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tambah Task Project Assignment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  enctype="multipart/form-data" id="frmtask">
            <input type="hidden" name="tokenpa" id="tokenpa">
                 <div class="form-group">
                    <label for="program">Program</label>
                    <textarea class="form-control" name="program" id="program"></textarea>
                </div>
                <div class="form-group">
                    <label for="program">Deliverable</label>
                    <textarea class="form-control" name="deliverable" id="deliverable"> </textarea>
                </div>
                <div class="form-group">
                    <label for="program">Outcome</label>
                    <textarea class="form-control" name="outcome" id="program"></textarea>
                </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btsavetask ">Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content" style="height: 90vh;" >
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">File Project Assignment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php 
            $filep=base_url("media/project_assignment/".$data_pa[0]->pa_file);
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

<div class="modal fade" id="medit_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Task Project Assignment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form  enctype="multipart/form-data" id="frmedittask">
            <input type="hidden" name="tokenpad" id="tokenpad">
                 <div class="form-group">
                    <label for="program">Program</label>
                    <textarea class="form-control" name="programed" id="programed"></textarea>
                </div>
                <div class="form-group">
                    <label for="program">Deliverable</label>
                    <textarea class="form-control" name="deliverabled" id="deliverabled"> </textarea>
                </div>
                <div class="form-group">
                    <label for="program">Outcome</label>
                    <textarea class="form-control" name="outcomed" id="outcomed"></textarea>
                </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btsaveedittask ">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
   $(document).ready(function () {
    var baseUrl='<?=base_url()?>';
    var pa_token=$("#pa_token").val();
    if(pa_token==""){
        location.reload();
    }else if($("#problem").val() !=""){

    } else{
        Swal.fire({
            title: '<strong>Informasi</strong>',
            icon: 'info',
            html:
                'Data akan tersimpan jika anda sudah menekan tombol <b>SIMPAN</b>, ',
            focusConfirm: false,
            confirmButtonText:
                '<i class="fa fa-thumbs-up"></i> Saya mengerti!',
            confirmButtonAriaLabel: 'Thumbs up, great!'
        })
    }

    function cekisian(){
        if( $("#atasan").val()=="" && $("#jabatan").val()=="" && $("#problem").val()=="" && $("#solution").val()=="" || $("#timeframe").val()==""){
            return false;
        }else{
            return true;
        }
    }

    var atasan=$("#atasan").val();
    if(atasan=="Pilih atasan" || atasan==""){
        $("#btsave").attr("disabled",true);
        $("#atasan").focus();
    }else{
        $("#btsave").removeAttr("disabled")
    }

    $("#atasan").on("change",function(e){
        e.preventDefault();
        if($(this).val()=="Pilih atasan"){
            $("#btsave").attr("disabled",true);
            $(this).focus();
        }else{
            $("#btsave").removeAttr("disabled");
        }
     
       
    });


    $(".btaddtask").on("click",function(){
        $("#madd_detail").modal("show");
        $("#tokenpa").val(pa_token);
    })
    $(".btsavetask").on("click",function(e){
      e.preventDefault();
      var form = $('#frmtask')[0];
      var data = new FormData(form);
      data.append("act", "addtask");
      $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: baseUrl+"" + "learning/Class_room/pa_ajax",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        success: function (response) {
        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah menyimpan Task',
                            }).then(function(){
                            location.reload();
                        });
            },
        error: function (e) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Periksa kembali inputan anda',
                }).then(function(){
                            location.reload();
                })
        }
      })
    })

    $(".btsaveedittask").on("click",function(e){
      e.preventDefault();
      var form = $('#frmedittask')[0];
      var data = new FormData(form);
      data.append("act", "updatetaskpeserta");
      $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: baseUrl+"" + "learning/Class_room/pa_ajax",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        success: function (response) {
        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah mengubah Task',
                            }).then(function(){
                            location.reload();
                        });
            },
        error: function (e) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Periksa kembali inputan anda',
                }).then(function(){
                            location.reload();
                })
        }
      })
    })

    $("#filepa").on('change',function(e){
        e.preventDefault();
        var datafile = new FormData();
        const filedoc=$('#filepa').prop('files')[0];
        var pa_token=$('#pa_token').val();
        datafile.append("act","uploadfile");
        datafile.append("file",filedoc);
        datafile.append("paid",pa_token);
        var imgsize=Math.round(parseInt(filedoc.size)/1024);
        myfile= $( this ).val();
        var ext = myfile.split('.').pop();

        console.log(imgsize +" kb");
        if(imgsize < 5120 && ext == "pdf"){
             $.ajax({
                url: baseUrl+"" + "learning/Class_room/pa_ajax",
                type:"post",
                data:datafile,
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){ 
                    var respon=JSON.parse(data);
                    if(respon = "ok"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah mengupload file pendukung project assignment',
                            }).then(function(){
                                location.reload();
                            });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda gagal mengupload file, silahkan cek format dan besar file untuk melakukan reupload',
                            }).then(function(){
                                location.reload();
                            });
                    }
                  
                }
                 });
        }else{
            Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'Maksimum besar file adalah 5 Mb dan berformat PDF',
                            });
        }
        /*
      
                 */
      
    })
   

    $("#btdraft").on("click",function(e){
        e.preventDefault();
        var datapa = new FormData();
        var pa_token=$('#pa_token').val();
        datapa.append("jabatan",$("#jabatan").val());
        datapa.append("atasan",$("#atasan").val());
        datapa.append("solusi",$("#solution").val());
        datapa.append("problem",$("#problem").val());
        datapa.append("timeframe",$("#timeframe").val());
        datapa.append("act","simpandraft");
        datapa.append("paid",pa_token);
        if($("#atasan").val()=="" || $("#atasan").val()=="Pilih atasan"){
            $("#btsave").attr("disabled",true);
            $(this).focus();
            Swal.fire({
                            icon: 'warning',
                            title: 'Data Atasan belum diisi',
                            text: 'Lengkapi data atasan agar Atasan anda bisa menilai Project Assignment anda',
                            });
        }else{
            $.ajax({
                url: baseUrl+"" + "learning/Class_room/pa_ajax",
                type:"post",
                data:datapa,
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                    var respon=JSON.parse(data);
                    if( respon== "ok"){
            
                       Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah menyimpan draft Project assignment',
                            }).then(function(){
                            location.reload();
                        });
                    }else{
                      
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda gagal menyimpan draft project assignment, silahkan cek isian kembali',
                            }).then(function(){
                                location.reload();
                            });
                            
                    }
                }
                
            
            })
        }
       

    


    })


    
    $("#btsave").on("click",function(e){
        e.preventDefault();
      
        var datapa = new FormData();
        var pa_token=$('#pa_token').val();
        datapa.append("jabatan",$("#jabatan").val());
        datapa.append("atasan",$("#atasan").val());
        datapa.append("solusi",$("#solution").val());
        datapa.append("problem",$("#problem").val());
        datapa.append("timeframe",$("#timeframe").val());
        datapa.append("act","simpanfinal");
        datapa.append("paid",pa_token);
        if($("#atasan").val()=="" || $("#atasan").val()=="Pilih atasan"){
            $("#btsave").attr("disabled",true);
            $(this).focus();
            Swal.fire({
                            icon: 'warning',
                            title: 'Data Atasan belum diisi',
                            text: 'Lengkapi data atasan agar Atasan anda bisa menilai Project Assignment anda',
                            });
        }else if(cekisian() == false){
            $(this).focus();
            Swal.fire({
                            icon: 'warning',
                            title: 'Data Input Wajib belum diisi',
                            text: 'Lengkapi data isian wajib (atasan, jabatan, problem, solution dan timeframe) sebelum menyimpan final',
                            });
        }else{
           $.ajax({
                url: baseUrl+"" + "learning/Class_room/pa_ajax",
                type:"post",
                data:datapa,
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                    var respon=JSON.parse(data);
                    if( respon== "ok"){
            
                       Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah menyimpan final Project assignment',
                            }).then(function(){
                            location.reload();
                        });
                    }else{
                      
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda gagal menyimpan final project assignment, silahkan cek isian kembali',
                            }).then(function(){
                                location.reload();
                            });
                            
                    }
                }
                
            
            })
        }
        
    })


    $("#btview").on("click",function(e){
        e.preventDefault();
        var idpa='<?=$this->encryption->decrypt($pa_id)?>';
        location.replace(baseUrl+"learning/class_room/individual_scoreboard?pa_id="+idpa);
    })

    $('#btdashboard').on('click',function(e){
        e.preventDefault();
        var crid=$('#pa_class').val();
        console.log(crid);
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

    $(".bttaskedit").on("click",function(e){
        e.preventDefault();
        var task=$(this).data('task');
        var program=$(this).data('program');
        var deliverable=$(this).data('deliverable');
        var outcomed=$(this).data('outcome');
        $("#medit_detail").modal('show');
        $("#programed").val(program);
        $("#tokenpad").val(task);
        $("#deliverabled").val(deliverable);
        $("#outcomed").val(outcomed);

    })
       
       
    $(".bttaskdelete").on("click",function(e){
        e.preventDefault();
        var data = new FormData();
        var tokenpad=$(this).data('task');
        data.append("act", "hapustask");
        data.append("tokenpad",tokenpad);
        $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: baseUrl+"" + "learning/Class_room/pa_ajax",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        success: function (response) {
            console.log(response);
        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah menghapus Task',
                            }).then(function(){
                            location.reload();
                        });
            
            },
        error: function (e) {
           Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'penghapusan gagal',
                }).then(function(){
                            location.reload();
                })
            
        }
      })
    })
   });
</script>
