<?php $this->load->view('learning/app_header'); ?>
<div id="appCapsule">
    <div class="section full">
        <div class="p-2 mb-1">
            <div class="d-flex mt-2 justify-content-center">
                <div class="card">
                    <article class="card-body">
                        <h4 class="card-title text-center mb-4 mt-1">Profil Peserta</h4>
                        <span class="text-center">Masukkan data peserta sebelum mengisi evaluasi</span>
                        <hr>
                        <?php 
                                if($this->session->flashdata('info')){
                                    echo $this->session->flashdata('info');
                                }else{

                                }
                        ?>
                      
                    <br>
                        <form method="post" action="<?=base_url('evaluasi/profil_add')?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="masukkan nama karyawan">
                            </div>
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" name="nip" id="nip" placeholder="masukkan nomor induk karyawan">
                            </div>
                            <div class="form-group">
                                <label for="entitas">Perusahaan <br><small>* jika tidak ada pilih umum</small></label>
                                <select name="perusahaan" class="form-control" id="perusahaan">
                                    <?php 
                                        echo "<option disabled selected>Pilih asal perusahaan</option>";
                                        foreach($perusahaan as $p){
                                            echo "<option value='".$p["group_id"]."'>".$p["group_name"]."</option>";
                                        }
                                        echo "<option value='umum'>Umum</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas yang diikuti</label>
                                <input id="autokelas" name="kelas" class="form-control" readonly value="<?=$kelas['0']->cr_name?>">
                                <input type="hidden" name="autokelas_id" id="autokelas-id" value="<?=$kelas['0']->cr_id?>">
                                <input type="hidden" name="pin" id="pin" value="<?=$kelas['0']->cr_pin?>">
                            </div>

                            
                            <div class="form-group">
                                <input type="submit" id="bsubmit" class="btn btn-primary btn-block" value="Konfirmasi">
                            </div> <!-- form-group// -->
                        </form>
                    </article>
                    </div> <!-- card.// -->
            </div>  
            
        </div>
    </div>

</div>

<script> 
    $(document).ready( function(){
        $("#bsubmit").attr("disabled",true);
        var urlkelas='<?=base_url('evaluasi/get_extclass')?>';
        $("#autokelas").autocomplete({
		  source: urlkelas,
          minLength:3,
          select: function (event, ui) {
            $( "#autokelas" ).val( ui.item.label );
            $( "#autokelas-id" ).val( ui.item.value );
            return false;
         }
		});

        $("#nip").on("keyup",function(e){
            console.log($("#perusahaan").val())
            checkinput();
        })

        $("#name").on("keyup",function(e){
            checkinput();
            console.log($("#perusahaan").val())
        })

        $("#perusahaan").on("change",function(e){
            checkinput();
            console.log($("#perusahaan").val())
        })


        function checkinput(){
            var nip=$("#nip").val();
            var nama=$("#nama").val();
            var perusahaan=$("#perusahaan").val();
            if(nip=="" && nama =="" && is_null(perusahaan)){
                $("#bsubmit").attr("disabled",true);
            }else{
                $("#bsubmit").removeAttr("disabled");
            }
        }

    })
</script>