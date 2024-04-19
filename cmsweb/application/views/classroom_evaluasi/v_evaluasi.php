<?php 
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
    <div class="kt-subheader kt-grid__item" id="kt_subheader">

        <div class="kt-subheader__main">

            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <!-- Jika ada akses maka buka tombol tambah -->
            <button id="btadd" class="btn btn-brand kt-margin-l-10" data-toggle="modal" data-target="#addevaluasi">
                Tambah
            </button>
            <!-- End Tombol Tambah -->
        </div>

    </div>

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <?php
                $this->load->view('flash_notif_view');
                
            ?>
            <div class="col-lg-12 col-xs-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowraps" id="tblist">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Evaluasi</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $str="";
                                        $no=1;
                                        foreach($evaluasi as $e){
                                            $tool="<span> <button  data-evaluasi='".$e->id."' class='btn btn-warning btn-rounded btn-sm btedit'><i class='fa fa-edit'></i></button></span>";
                                            if($e->status > 0){
                                                $tool .=" <span><button  class='btn btn-danger btn-rounded btn-sm bthapus' data-evaluasi='".$e->id."' ><i class='fa fa-trash'></i></button></span>";
                                                $status ="<span class='text-success'><i class='fa-solid fa-circle'></i></span>";
                                            }else{
                                                $tool .=" <span><button  class='btn btn-success btn-rounded btn-sm bthapus' data-evaluasi='".$e->id."' ><i class='fa fa-upload'></i></button></span>";
                                                $status ="<span class='text-danger'><i class='fa-solid fa-circle'></i></span>";
                                            }
                                            $str.="<tr>";
                                            $str.="<td style='vertical-align: middle;'>".$no."</td>";
                                            $str.="<td style='vertical-align: middle;'>".$e->soal."</td>";
                                            $str.="<td style='vertical-align: middle;'>".$e->tipe."</td>";
                                            $str.="<td style='vertical-align: middle;'>".$e->jenis."</td>";
                                            $str.="<td class='text-center' style='vertical-align: middle;'>".$status."</td>";
                                            $str.="<td style='vertical-align: middle;'>".$tool."</td>";
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


<div class="modal fade" id="addevaluasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah data soal Evaluasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add" enctype="multipart/form-data" method="POST">
      
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-x2-12 pb-4">
                <label for='kategori'>Kategori Pertanyaan</label>
                <select id='addkategori' name='addkategori' class='form-control'>
                    <option disabled selected>Pilih kategori pertanyaan</option>
                    <?php 
                        foreach ($tipe as $t){
                            echo "<option value='".$t."'>".$t."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-12 col-x2-12 pb-4">
                <label for='kategori'>Jenis Kelas</label>
                <select id='add_crkelola' name='add_crkelola' class='form-control'>
                    <option disabled selected>Pilih Jenis Kelas</option>
                    <?php 
                        foreach ($jenis as $j){
                            echo "<option value='".$j."'>".$j."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-12 col-x2-12">
                <label for='pertanyaan'>Pertanyaan</label>
                <textarea class="form-control" rows="8" name='addpertanyaan' id='addpertanyaan' placeholder="masukkan pertanyaan"></textarea>
            </div>
        </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" id='btsimpan' class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
</div>


<div class="modal fade" id="editevaluasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit data soal Evaluasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add" enctype="multipart/form-data" method="POST">
      
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-x2-12 pb-4">
                <label for='editkategori'>Kategori Pertanyaan</label>
                <select id='editkategori' name='editkategori' class='form-control'>
                    <option disabled selected>Pilih kategori pertanyaan</option>
                    <?php 
                        foreach ($tipe as $t){
                            echo "<option value='".$t."'>".$t."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-12 col-x2-12 pb-4">
                <label for='kategori'>Jenis Kelas</label>
                <select id='edit_crkelola' name='edit_crkelola' class='form-control'>
                    <option disabled selected>Pilih Jenis Kelas</option>
                    <?php 
                        foreach ($jenis as $j){
                            echo "<option value='".$j."'>".$j."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-12 col-x2-12">
                <label for='editpertanyaan'>Pertanyaan</label>
                <textarea class="form-control" rows="8" name='editpertanyaan' id='editpertanyaan' placeholder="masukkan pertanyaan"></textarea>
            </div><input type='hidden' id="evaluasiedit" name="evaluasi">
        </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" id='btsimpanedit' class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
</div>


<script>
    $( document ).ready(function() {
        $('#tblist').DataTable();
        var url='<?=base_url()?>';
        $("#editevaluasi").prependTo("body");
        
        $("#btsimpanedit").on('click',function(e){
            e.preventDefault();
            aksi('edit','');
        })

        $("#btsimpan").on('click',function(e){
            e.preventDefault();
            aksi('add','');
        })

        $('#tblist tbody').on('click', '.btedit', function (e) {
            e.preventDefault();
            var evaluasi=$(this).data('evaluasi');
            $('#editevaluasi').modal('show');
            get(evaluasi);
        })

        $('#tblist tbody').on('click', '.bthapus', function (e) {
            e.preventDefault();
            var evaluasi=$(this).data('evaluasi');
            aksi('hapus',evaluasi);
        })
      
        function get(evaluasi){
            $.ajax({
                        url : url + 'classroom/get_evaluasi',
                        data :{evaluasi:evaluasi},
                        method: 'GET',
                        success: function(response) {
                            //menampilkan hasil GET
                            var respon=JSON.parse(response);
                            if(respon.stat =='false'){
                                Swal.fire({
                                    type: 'error',
                                    title: 'Upss',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }else{
                                $('#editkategori').val(respon.data[0].tipe);
                                $('#editpertanyaan').val(respon.data[0].soal);
                                $('#editevaluasi').val(respon.data[0].id);
                                $('#edit_crkelola').val(respon.data[0].jenis);
                            }
                        }
                    })
        } 


        function aksi(tipe,atribut){
           
            switch (tipe){
                case 'add':
                    url +='classroom/add_evaluasi';
                    var data = new FormData();
                    data.append("soal",$("#addpertanyaan").val());
                    data.append("tipe",$("#addkategori").val());
                    data.append("jenis",$("#add_crkelola").val());
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 800000,
                        success: function (response) {
                            var respon=JSON.parse(response);
                            if(respon.stat=="true"){
                                Swal.fire({
                                    type: 'success',
                                    title: 'Berhasil',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: 'Upss',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }
                        }
                    })
                break;
                case 'edit':
                    url +='classroom/edit_evaluasi';
                    var data = new FormData();
                    data.append("soal",$("#editpertanyaan").val());
                    data.append("tipe",$("#editkategori").val());
                    data.append("jenis",$("#edit_crkelola").val());
                    data.append("evaluasi",$("#editevaluasi").val());
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 800000,
                        success: function (response) {
                            var respon=JSON.parse(response);
                            if(respon.stat=="true"){
                                Swal.fire({
                                    type: 'success',
                                    title: 'Berhasil',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: 'Upss',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }//console.log(respon)
                        }
                    })

                break;
                case 'hapus':
                    url +='classroom/hapus_evaluasi';
                    var data = new FormData();
                    data.append("evaluasi",atribut);
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 800000,
                        success: function (response) {
                            var respon=JSON.parse(response);
                            if(respon.stat=="true"){
                                Swal.fire({
                                    type: 'success',
                                    title: 'Berhasil',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: 'Upss',
                                    text: respon.msg,
                                    }).then(function(){
                                        location.reload();
                                });
                            }
                        }
                    })
                break;
            }

        
        }
    });
</script>