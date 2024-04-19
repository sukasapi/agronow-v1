<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("classroom"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $classroom;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Peserta (<?= isset($member_count['total'])?$member_count['total']:0 ?>)
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <div class="kt-portlet__head-actions">
                                <a  href="<?php echo site_url('classroom/member_aghris_search/'.$classroom['cr_id']); ?>" class="btn btn-outline-info btn-sm">
                                   Cari di Aghris
                                </a>

                                <button data-backdrop="static"
                                        data-remote="<?php echo site_url('member/l_modal_ajax'); ?>" type="button"
                                        class="btn btn-outline-info btn-sm ml-2" data-toggle="modal"
                                        data-target="#classroomMemberPicker">Pilih Member
                                </button>

                                <!--begin::Modal-->
                                <div class="modal fade" id="classroomMemberPicker" tabindex="-1" role="dialog"
                                     aria-labelledby="classroomMemberPickerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="classroomMemberPickerModalLabel">Member</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-center">
                                                    <img src="<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif'); ?>"/>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end modal -->
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">
						<div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered table-hover nowrap" id="kt_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Group</th>
										<th>Klien</th>
                                        <th>Status</th>
                                        <th>Teregistrasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($member): ?>
                                <?php $no=0; foreach ($member as $v): ?>
                                    <tr>
                                        <td><?php $no++; echo $no; ?></td>
                                        <td><?= $v['member_name'] ?><br><small>NIP: <?= $v['member_nip'] ?></small></td>
                                        <td><?= $v['group_name'] ?></td>
										<td><?= $v['nama_klien'] ?></td>
                                        <td>
                                            <?php 
                                                if($v['is_pk']=="" || $v['is_pk']=='0'){
                                                    echo " <button class='btn btn-warning btn-sm doPK' data-ispk='".$v['cr_id']."-".$v['member_id']."-1'>peserta</button>";
                                                }else{
                                                    echo " <button class='btn btn-success btn-sm doPK' data-ispk='".$v['cr_id']."-".$v['member_id']."-0'>PK</button>";
                                                }
                                            ?>
                                           
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                 $mstat=memberstat($v['member_id'],$v['cr_id']);
                                              
                                                 if($mstat[0]->member_status > 0 ){
                                                     $kode="batal-".$v['member_id']."-".$v['cr_id'];
                                                     ?>
                                                         <button class="btn btn-sm btn-rounded btn-success btact"  data-act='<?=$kode?>' >Ya</button>
                                                     <?php
                                                     
                                                 }else{
                                                     $kode="daftar-".$v['member_id']."-".$v['cr_id'];
                                                     ?>
                                                         <button class="btn btn-sm btn-rounded btn-danger btact" data-act='<?=$kode?>'>Batal</button>
                                                     <?php
                                                 }
                                                 
                                                 ?>
                                            <!--<a title="Remove" href="<?= site_url('classroom/member_remove/'.$classroom['cr_id'].'/'.$v['crm_id']); ?>" class="btn text-danger <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>" onclick="return confirm('Anda yakin?')"><i class="fa fa-trash"></i></a>-->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
					<div class="kt-portlet__footer border-top">
						<?php
						$id_lw_classroom = $classroom['id_lw_classroom'];
						if($id_lw_classroom>0) {
							$uiT = '';
							$arrT = $this->learning_wallet_model->getNIKPesertaPelatihan($id_lw_classroom);
							$t = 0;
							$jumlT = count($arrT);
							foreach($arrT as $key => $val) {
								$t++;
								$uiT .= "^".$val['member_nip']."$";
								if($t<$jumlT) {
									$uiT .= "|";
								}
							}
						} else {
							$uiT = 'class room tidak terhubung dengan AgroWallet';
							$t = 0;
						}
						?>
						<div class="p-4 mb-4">
							Langkah-langkah mencari peserta yang mendaftar melalui AgroWallet (<?=$t?> peserta):<br/>
							<div class="alert alert-info">
								<ol>
									<li>Pastikan kode AgroWallet telah dihubungkan dengan Classroom.</li>
									<li>Salin (copy) semua teks yang muncul di bawah ini.</li>
									<li>Klik menu <b>Pilih Member</b>.</li>
									<li>Tempelkan (paste) teks ke dalam kotak isian <b>Pendaftar dari AgroWallet</b>.</li>
									<li>Klik tombol <b>cari</b>.</li>
                                    <li>5. Untuk pelatihan yang dikelola di luar AgroNow, jadikan salah satu APK/personil dari bagian pemasaran sebagai PK untuk QC data.</li>
								</ol>
							</div>
							<code><?=$uiT?></code>
						</div>
					</div>
                </div>
                <!-- END PORTLET MEMBER -->
                 <!-- PORTLET MEMBER GENERATOR --> 
                 <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="alert alert-danger" role="alert">
                            <p>Fitur ini masih dalam tahap pengembangan</p>
                        </div>
                            <h4>Member list Generator</h4>
                            <div class="alert alert-warning" role="alert">
                                <ul style="list-style-type: none;">
                                    <li>1. Copy daftar NIP dari excel.</li>
                                    <li>2. Paste ke dalam text input dibawah ini.</li>
                                    <li>3. Tekan tombol generate untuk mendapatkan pola member yang akan diinput.</li>
                                    <li>4. tekan tombol pilih member untuk memasukkan pola member yang dihasilkan.</li>
                                </ul>    
                            </div>
                            <div class="form-group">
                                <label for="input">Salin daftar member dari excel anda</label>
                                <textarea class="form-control" id="memberlist" name="memberlist" rows="8" placeholder="salin daftar member dari excel anda">

                                </textarea>
                                <button id="bgen" disabled class="btn btn-rounded btn-primary"> Generate !!</button>
                            </div>      
                            <div class="p4 mb-4">
                                    <code>
                                        <div id="gen_result">

                                        </div>
                                    </code>
                            </div>
                    </div>
                   
                </div>
                <!-- END  PORTLET MEMBER GENERATOR -->
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                searchDelay: 500,
                processing: true,
                serverSide: false,
                stateSave: true,

                language: {
                    "infoFiltered": ""
                },
                order: [[ 0, "asc" ]],
            });
        };

        return {
            //main function to initiate the module
            init: function() {
                initTable1();
            },
        };

    }();

    jQuery(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
    });
</script>



<script>
    function addSelectedItem(selectedItems)
    {
        // console.log(selectedItems);
        var ids = [];
        $.each(selectedItems, function( index, value ) {
            ids.push(value.member_id);
        });

        $.post("<?= site_url('classroom/member_add_picker/'.$classroom['cr_id']); ?>", {member_ids: ids}, function(result){
            var r = $.parseJSON(result);
            alert('Berhasil menambahkan '+r.succ+' member.');
            location.reload();
        });
    }
</script>

<script>
     $(document).ready(function() {
        var baseUrl='<?=base_url()?>';
       
        $("#kt_table").on("click", ".doPK", function(e){
            e.preventDefault();
            var kelas=$(this).data('ispk');
            var urlact=baseUrl+"classroom/upd_pk";
            var dt_pk = new FormData();
           dt_pk.append("kode",kelas);
            $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: urlact,
                    data: dt_pk,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 800000,
                    success: function (response) {
                        var respon=JSON.parse(response);
                        if(respon =="ok"){
                            Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah melakukan update PK',
                            }).then(function(){
                                location.reload();
                            });
                        }else{
                            Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda gagal mengupdate PK',
                            }).then(function(){
                                location.reload();
                            });
                        }
                    }

                })
            
        })

        $("#kt_table").on("click", ".btact", function(e){
            var kode=$(this).data('act');
            var dt = new FormData();
            dt.append("kode",kode);
            var urlact=baseUrl+"classroom/upd_mstat";
            $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: urlact,
                    data: dt,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 800000,
                    success: function (response) {
                        var respon=JSON.parse(response);
                        if(respon =="ok"){
                            Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'anda telah melakukan update status member kelas',
                            }).then(function(){
                                location.reload();
                            });
                        }else{
                            Swal.fire({
                            icon: 'error',
                            title: 'Upss',
                            text: 'anda gagal melakukan update status member kelas',
                            }).then(function(){
                                location.reload();
                            });
                        }
                    }

            })
         
        })

        $("#memberlist").on('keyup',function(e){
            e.preventDefault();
            var list=$(this).val();
            if(list==""){
                
            }else{
                $("#bgen").removeAttr('disabled',true)
            }
        })
        
        $("#bgen").on('click',function(e){
            var list=$("#memberlist").val();
            var lines=[];
            var textlist="";
            if(list==""){
                alert("list masih kosong");    
            }else{
                $.each(list.split(/\n/), function(i, line){
                    if(line!="" && line.trim().length > 0){
                        lines.push(line.trim());
                        textlist +="^"+line.trim()+"$|";
                    } else {
                    }
                });
                
            }
          $("#gen_result").html(textlist);
        })
     })
</script>