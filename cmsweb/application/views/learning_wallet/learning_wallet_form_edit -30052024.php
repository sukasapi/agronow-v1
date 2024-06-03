<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>
<!-- end:: Header -->
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
                

    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
         
            ?>
            <div class="col-md-8 col-xs-12">
                <div class="kt-portlet">                
                    <div class="kt-portlet__body">
                         <div class="row mb-4">
                            <div class="col-md-12 col-xs-12 text-center">
                                <h4>Informasi Kelas</h4>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="klien">Klien</label>
                                    <select name="klien" id="klien" class="form-control">
                                        <?php 

                                            foreach($klien as $k){
                                                if($kelas[0]->id_klien == $k->id){
                                                    echo "<option selected value='".$k->id."'>".$k->nama."</option>";
                                                }else{
                                                    echo "<option value='".$k->id."'>".$k->nama."</option>";
                                                }
                                               
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6  col-sm-12">
                             
                                <div class="form-group">
                                    <label for="penyelenggara">Penyelenggara</label>
                                    <select name="penyelenggara" id="penyelenggara" class="form-control">
                                        <?php 
                                            foreach($penyelenggara as $p){
                                                if($kelas[0]->id_penyelenggara == $p->id){
                                                    echo "<option selected value='".$p->id."' selected>".$p->nama."</option>";
                                                }else{
                                                    echo "<option value='".$p->id."'>".$p->nama."</option>";
                                                }
                                            }
                                        ?>
                                        <option value='1'>LPP Agro Nusantara</option>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-12 col-xs-12"><input type="hidden" id="kelasid" name="kelasid" value="<?=$kelas[0]->idkelas?>">
                                <div class="form-group">
                                    <label for="kodekelas">Kode Kelas</label>
                                    <input readonly value="<?=$kelas[0]->kodekelas?>" placeholder= "kode dari pemasaran" required type="text" class="form-control" name="kodekelas" id="kodekelas">
                                </div>
                            </div>  
							<div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="sekolah">Sekolah</label>
                                    <select class="form-control" name="sekolah" id="sekolah">
                                    <?php 
                                            foreach($sekolah as $s){
                                              
                                                if($kelas[0]->id_sekolah ==$s->id){
                                                    echo "<option selected  value='".$s->id."'>".$s->nama."</option>";
                                                }else{
                                                    echo "<option  value='".$s->id."'>".$s->nama."</option>";
                                                }
                                              
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="namakelas">Nama Kelas</label>
                                    <input required type="text" value="<?=$kelas[0]->nama_kelas?>" placeholder="nama kelas" class="form-control" name="namakelas" id="namakelas">
                                </div>
                            </div>
							<div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="pic">Penanggung jawab kelas</label>
                                    <input type="text" class="form-control" name="pic" value="<?=$kelas[0]->pic?>" placeholder="Inisial penanggung jawab kelas (SME)" id="pic">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Kelas</label>
                                    <textarea  class="form-control" rows="4" id="deskripsi" name="deskripsi" placeholder="Deskripsi Kelas"><?=$kelas[0]->deskripsi?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="sasaran">Sasaran Kelas</label>
                                    <textarea  class="form-control" rows="4" id="sasaran" name="sasaran" placeholder="sasaran kelas"><?=$kelas[0]->sasaran_pembelajaran?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="silabus">Silabus</label>
                                    <textarea  class="form-control" rows="4" id="silabus" name="silabus"><?=$kelas[0]->silabus?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="tag">Kata kunci</label>
                                    <input  type="text" value="<?=$kelas[0]->kata_kunci?>" placeholder="Kata kunci untuk mempermudah pencarian" class="form-control" name="tag" id="tag">
                                </div>
                            </div>
                         </div>
                         <hr/>
                        
                         
                        
                    </div>
                    <!--end::Form-->
                </div>
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                    <h5 class="text-center">Konfigurasi kelas</h5>
                    <div class="row mb-4">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="metode">Metode</label>
                                    <select class="form-control" name="metode" id="metode">
                                        <?php 
                                        foreach($metode as $m){
                                            switch ($m){
                                                case 'offline':
                                                    $sm="Offline";
                                                break;
                                                case 'online':
                                                    $sm="Online";
                                                break;
                                                case 'blended offline':
                                                    $sm="Blended offline";
                                                break;
                                                case 'blended online':
                                                    $sm="Blended online";
                                                break;
                                            }
                                            if($kelas[0]->metode ==$m){
                                                $selected="selected";
                                            }else{
                                                $selected="";
                                            }
                                            echo "<option ".$selected." value='".$m."'>".$sm."</option>";
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-6 col-xs-12 ">
                                <div class="form-group">
                                    <label for="hari">Jumlah hari</label>
                                    <input readonly class="form-control" type="text" name="hari" id="hari" value="<?=$kelas[0]->durasi_hari?>">
                                </div>
                            </div>
							<div class="col-md-6 col-xs-12 ">
                                <div class="form-group">
                                    <label for="jam">Jam Pembelajaran</label>
                                    <input  class="form-control" type="number" name="jam" id="jam" value="<?=$kelas[0]->jumlah_jam?>">
                                </div>
                            </div>
							<div class="col-md-4 col-xs-12 ">
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input class="form-control" type="text" name="tahun" id="tahun" value="<?=$kelas[0]->tahun?>">
                                </div>
                            </div>
							<div class="col-md-4 col-xs-12 ">
                                <div class="form-group">
                                    <label for="mulai">Mulai</label>
                                    <input class="form-control" type="date" name="mulai" id="mulai" value="<?=$kelas[0]->tgl_mulai?>">
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 ">
                                <div class="form-group">
                                    <label for="selesai">selesai</label>
                                    <input class="form-control" type="date" name="selesai" id="selesai" value="<?=$kelas[0]->tgl_selesai?>">
                                </div>
                            </div>
							<div class="col-md-6 col-xs-12 ">
                                <div class="form-group">
                                    <label for="lokasi">Lokasi</label>
                                    <input class="form-control" value="<?=$kelas[0]->lokasi_offline?>" type="text" name="lokasi" id="lokasi" placeholder="jika online, kosongkan">
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 ">
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="text" class="form-control" value="<?=$kelas[0]->harga?>" placeholder="harga kelas per peserta" name="harga" id="harga">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan </label>
                                    <textarea  class="form-control" rows="3" id="keterangan"  name="keterangan" placeholder="keetrangan tambahan untuk penyelenggaraan kelas"><?=$kelas[0]->keterangan?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="caper">Catatan Penyelenggaraan Kelas</label>
                                    <textarea class="form-control" nama="caper" id="caper"  placeholder="catatan penyelenggaraan kelas"><?=$kelas[0]->catatan_penyelenggaraan?></textarea>
                                </div>
                            </div>
							<!--
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <?php 
                                            if($kelas[0]->is_ecolearning > 0){
                                                ?>
                                                    <input checked class="form-check-input" type="checkbox" id="eco">
                                                <?php
                                            }else{
                                                ?>
                                                    <input class="form-check-input" type="checkbox" id="eco">
                                                <?php
                                            }
                                        ?>
                                        
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Eco Learning
                                        </label>
                                    </div>

                                </div>
                            </div>
							-->
                         </div>
                         <hr/>
                    </div>
                </div>
                
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <h5 class="text-center">Gambar Kelas</h5>
                        <div class="text-center">
                            <div class="form-group">
                                <select name="kategori" id="kategori" class="form-control">
                                    <?php   
                                        foreach($kategori as $k){
                                            if($kelas[0]->berkas==$k){
                                                echo "<option selected value='".$k."'>".$k."</option>";
                                            }else{
                                                echo "<option value='".$k."'>".$k."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
							<div class="row"><?=$kelas_berkas_ui?></div>
                    </div>
                </div>

                <div class="kt-portlet">                
                    <div class="kt-portlet__body">
                        <div class="row mb-4">
                            <div class="col-md-12 col-xs-12 text-center">
                                <h4>Konfigurasi Peserta</h4>
                            </div>
                            <div class="col-md-6 col-xs-12 ">
                                <div class="form-group">
                                    <label for="level">Level karyawan peserta</label>
                                    <input  class="form-control" value="<?=$kelas[0]->daftar_level_karyawan?>" type="text" name="level" id="level">
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 ">
                                <div class="form-group">
                                    <label for="minimal">Minimal jumlah peserta</label>
                                    <input  class="form-control" value="<?=$kelas[0]->minimal_peserta?>" type="number" min="1" name="minimal" id="minimal">
                                </div>
                            </div> 
                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <label for="catatanlevel">Catatan Level Peserta</label>
                                    <textarea  class="form-control" rows="4" id="catatanlevel"  name="catatanlevel"><?=$kelas[0]->catatan_level_peserta?></textarea>
                                </div>
                            </div>
                         </div>
                         <hr/>
                    </div>
                </div>

                <div class="kt-portlet">                
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-center">
                                <h5>Update Kelas Ini.</h5><br>
                                <button class="btn btn-primary btn-block" id="btsave">Simpan data kelas</button>
                            </div>
                         </div>
                         <hr/>
                    </div>
                </div>
                
                    <!--end::Form-->
                
                </div>
               
            </div>
        </div>
    </div>
   
    <!-- end:: Content -->


</div>

<script>
    $(document).ready(function() {
        var baseUrl='<?=base_url()?>';

        $("#btsave").on('click',function(e){
            e.preventDefault();
            var data = new FormData();
            var url=baseUrl+"learning_wallet/edit_kelas";
            data.append("kodekelas",$("#kodekelas").val());
            data.append("klien",$("#klien").val());
            data.append("penyelenggara",$("#penyelenggara").val());
            data.append("namakelas",$("#namakelas").val());
            data.append("deskripsi",$("#deskripsi").val());
            data.append("sasaran",$("#sasaran").val());
            data.append("silabus",$("#silabus").val());
            data.append("tag",$("#tag").val());
            data.append("tahun",$("#tahun").val());
            data.append("sekolah",$("#sekolah").val());
            data.append("harga",$("#harga").val());
            data.append("metode",$("#metode").val());
            data.append("lokasi",$("#lokasi").val());
            data.append("mulai",$("#mulai").val());
            data.append("selesai",$("#selesai").val());
            data.append("hari",$("#hari").val());
            data.append("jam",$("#jam").val());
            data.append("keterangan",$("#keterangan").val());
            data.append("pic",$("#pic").val());
            data.append("eco",$("#eco").val());
            data.append("level",$("#level").val());
            data.append("minimal",$("#minimal").val());
            data.append("catatanlevel",$("#catatanlevel").val());
            data.append("caper",$("#caper").val());
            data.append("kelas",$("#kelasid").val());
            data.append("kategori",$("#kategori").val());
            /// image
           
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
                    //console.log(response)
                    var goto=baseUrl+'learning_wallet/pelatihan';
                    var respon=JSON.parse(response);
                    if(respon.status = "ok"){
                        Swal.fire({
                            type: 'success',
                            title: 'Berhasil',
                            text: respon.pesan,
                            }).then(function(){
                                location.replace(goto);
                            });
                    }else{
                        Swal.fire({
                            type: 'error',
                            title: 'Upss',
                            text: respon.pesan,
                            }).then(function(){
                                location.reload();
                            });
                    }
                }

            })
        })

        $("#cover").on("change",function(e){
            e.preventDefault();
            var file = this.files[0];
            var fileType = file.type;
            var match = ['image/jpeg', 'image/png', 'image/jpg'];
            if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))){
                alert('Format hanya jpeg atau PNG.');
                $("#cover").val('');
                $('#viewcover').attr('src', baseUrl+'assets/media/noimage.png');
               
            }else{
                    let reader = new FileReader();
                    reader.onload = function(event){
                        console.log(event.target.result);
                        $('#viewcover').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
            }
        })
        
        $("#kodekelas").on('keydown',function(e){

        })
    })
</script>

