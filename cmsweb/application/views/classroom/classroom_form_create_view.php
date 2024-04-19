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

        <?php
        $attributes = array('autocomplete'=>"off");
        echo form_open_multipart($form_action, $attributes);
        ?>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-7">

                <!-- START PORTLET CONTENT -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Pelatihan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">
							<div class="col-12 mb-2">
								<label>Pengelola Data Pelatihan *</label>
                                <select class="form-control kt-input" name="cr_kelola" id="cr_kelola" required>
                                    <option value=""></option>
									<option value="dalam_app">dikelola di dalam AgroNow (AgroWallet dan Non AgroWallet)</option>
									<option value="lms_ext_agrowallet">dikelola di luar AgroNow (AgroWallet)</option>
									<option value="luar_app">dikelola di luar AgroNow (100% peserta Dari Luar PTPN Group)</option>
                                </select>
							</div>
							
                            <div class="col-12">
								<label>Kategori *</label>
                                <select class="form-control kt-input" name="cat_id" required>
                                    <?php
                                    foreach ($option_tree as $v){
                                        echo $v;
                                    }
                                    ?>
                                </select>
                                <?php
                               /* if (validation_errors()) {$val = set_value('cat_id');}else{$val = isset($request) ? htmlentities($request['cat_id'], ENT_QUOTES) : NULL;}

                                $attr = 'class="form-control" id="select2_cat"';
                                echo form_dropdown('cat_id', $form_opt_cat, $val, $attr);*/
                                ?>


                                <label class="mt-3 mb-0">Nama Pelatihan *</label>
								<br/><small>rekomendasi jumlah karakter supaya tidak terpotong di sertifikat: max 60 karakter</small>
                                <input type="text" class="form-control kt_input" placeholder="" name="cr_name" id="cr_name" required value="<?php
                                if (validation_errors()) {echo set_value('cr_name');}else{echo isset($request) ? htmlentities($request['cr_name'], ENT_QUOTES) : '';} ?>">
							</div>
							
							<div id="det_ui" class="col-12">
                                <label class="mt-3">Type *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_type" required checked="" value="elearning" <?php
                                        echo set_value('cr_type', ($editable)?$request['cr_type']:'') == 'elearning' ? "checked" : "checked";
                                        ?>> e-LEARNING
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_type" required value="inclasstraining" <?php
                                        echo set_value('cr_type', ($editable)?$request['cr_type']:'') == 'inclasstraining' ? "checked" : "";
                                        ?>> IN CLASS TRAINING
                                        <span></span>
                                    </label>
                                </div>
								
                                <label class="mt-3">Ada Sertifikat *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_certificate" required checked="" value="1" <?php
                                        echo set_value('cr_has_certificate', ($editable)?$request['cr_has_certificate']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_certificate" required value="0" <?php
                                        echo set_value('cr_has_certificate', ($editable)?$request['cr_has_certificate']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>

                                <label class="mt-3">Ada Prelearning *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_prelearning" required checked="" value="1" <?php
                                        echo set_value('cr_has_prelearning', ($editable)?$request['cr_has_prelearning']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_prelearning" required value="0" <?php
                                        echo set_value('cr_has_prelearning', ($editable)?$request['cr_has_prelearning']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>


                                <label class="mt-3">Ada Pretest *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_pretest" required checked="" value="1" <?php
                                        echo set_value('cr_has_pretest', ($editable)?$request['cr_has_pretest']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_pretest" required value="0" <?php
                                        echo set_value('cr_has_pretest', ($editable)?$request['cr_has_pretest']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>


                                <label class="mt-3">Ada Learning Point *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_learning_point" required checked="" value="1" <?php
                                        echo set_value('cr_has_learning_point', ($editable)?$request['cr_has_learning_point']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_learning_point" required value="0" <?php
                                        echo set_value('cr_has_learning_point', ($editable)?$request['cr_has_learning_point']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>


                                <label class="mt-3">Ada Competency Test *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_kompetensi_test" required checked="" value="1" <?php
                                        echo set_value('cr_has_kompetensi_test', ($editable)?$request['cr_has_kompetensi_test']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_kompetensi_test" required value="0" <?php
                                        echo set_value('cr_has_kompetensi_test', ($editable)?$request['cr_has_kompetensi_test']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>


                                <label class="mt-3">Ada Knowledge Management *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_knowledge_management" required checked="" value="1" <?php
                                        echo set_value('cr_has_knowledge_management', ($editable)?$request['cr_has_knowledge_management']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_knowledge_management" required value="0" <?php
                                        echo set_value('cr_has_knowledge_management', ($editable)?$request['cr_has_knowledge_management']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>
								
								<label class="mt-3">Ada Project Assignment *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_project_assignment" required value="1" <?php
                                        echo set_value('cr_has_project_assignment', ($editable)?$request['cr_has_project_assignment']:'') == '1' ? "checked" : "";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_has_project_assignment" required value="0" <?php
                                        echo set_value('cr_has_project_assignment', ($editable)?$request['cr_has_project_assignment']:'') == '0' ? "checked" : "checked";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>
                                
                                <label class="mt-3">Tampilkan nilai ke peserta *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_show_nilai" required checked="" value="1" <?php
                                        echo set_value('cr_show_nilai', ($editable)?$request['cr_show_nilai']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_show_nilai" required value="0" <?php
                                        echo set_value('cr_show_nilai', ($editable)?$request['cr_show_nilai']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>
                                <small>Catatan: Jika 'Tidak' maka nilai pretest, evaluasi modul dan competency test tidak ditampilkan ke peserta</small>
								<br/>

                                <label class="mt-3">Modul Harus Urut *</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_modul_harus_urut" required value="1" <?php
                                        echo set_value('cr_modul_harus_urut', ($editable)?$request['cr_modul_harus_urut']:'') == '1' ? "checked" : "checked";
                                        ?>> Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="cr_modul_harus_urut" required value="0" <?php
                                        echo set_value('cr_modul_harus_urut', ($editable)?$request['cr_modul_harus_urut']:'') == '0' ? "checked" : "";
                                        ?>> Tidak
                                        <span></span>
                                    </label>
                                </div>
                                <small>Catatan: Jika 'Ya' maka peserta harus mengerjakan modul sesuai dengan no urut modul</small>
                                <br/>

                                <label class="mt-3">Keterangan</label>
                                <textarea id="content" name="cr_desc" style="min-height: 500px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('cr_desc');}else{echo isset($request) ? htmlentities($request['cr_desc'], ENT_QUOTES) : '';} ?></textarea>
                                
                                <label class="mt-3">Penanggung Jawab Kelas (PIC)</label>
                                <input type ="text" name="cr_pic" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET CONTENT -->


            </div>

            <div class="col-lg-5">
				<!-- START PORTLET LEARNING WALLET -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Sinkronisasi Data
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
						<div class="row">
                            <div class="col-12">
                                <label>Kode Project SuperApp</label>
								<select class="form-control kt-input" name="id_superapp_manpro" id="ajax_superapp_manpro"></select>
								<input type="hidden" name="kode_superapp_manpro" id="kode_superapp_manpro" value=""/>
                            </div>
                           
							<div class="col-12" id="detail_superapp_manpro"></div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Kode AgroWallet</label>
								<select class="form-control kt-input" name="id_lw_classroom" id="ajax_lw"></select>
                            </div>
							<div class="col-12" id="detail_lw_classroom"></div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET LEARNING WALLET -->

                <!-- START PORTLET STATUS -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Waktu
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">
                            <div class="col-12">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tanggal Mulai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="cr_date_start" required value="<?php
                                        if (validation_errors()) {echo set_value('cr_date_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['cr_date_start'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Tanggal Selesai *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" name="cr_date_end" required value="<?php
                                        if (validation_errors()) {echo set_value('cr_date_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['cr_date_end'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="mt-3">Jam Mulai *</label>
                                        <input type="text" class="form-control timepicker" placeholder="" name="cr_time_start" required value="<?php
                                        if (validation_errors()) {echo set_value('cr_time_start');}else{echo isset($request) ? htmlentities($request['cr_time_start'], ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="mt-3">Jam Selesai *</label>
                                        <input type="text" class="form-control timepicker" placeholder="" name="cr_time_end" required value="<?php
                                        if (validation_errors()) {echo set_value('cr_time_end');}else{echo isset($request) ? htmlentities($request['cr_time_end'], ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small>Ketika waktu menunjukkan 00:00 tekan panah bawah pada bagian detik untuk menjadi ke jam 23:59</small>
                                    </div>
                                </div>

                                <label class="mt-3">Jumlah Jam Pelatihan *</label>
                                <input type="number" class="form-control" placeholder="" name="cr_date_detail" required value="<?php
                                if (validation_errors()) {echo set_value('cr_date_detail');}else{echo isset($request) ? htmlentities($request['cr_date_detail'], ENT_QUOTES) : '';} ?>">
								<small><a href="<?=base_url('assets').'/media/misc/classroom_jam_efektif.jpeg'?>" target="_blank">lihat standar jumlah jam pelatihan efektif</a></small>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET STATUS -->

                <!-- START PORTLET PRICE -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Harga
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">
                            <div class="col-12">

                                <label class="mt-3">Harga Class Room (Poin) *</label>
                                <input type="number" class="form-control" placeholder="" name="cr_price" value="<?php
                                if (validation_errors()) {echo set_value('cr_price');}else{echo isset($request) ? htmlentities($request['cr_price'], ENT_QUOTES) : '';} ?>">

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET PRICE -->
 

                 <!-- START PORTLET NPS 27012024 -->
                 <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Evaluasi NPS
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="row mb-4">
                            <div class="col-md-12 col-xs-12">
                                <div id="penyelenggaraan" >
                                <label class="mt-3">Evaluasi Penyelenggaraan</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ev_penyelenggaraan" required checked="" value="1" > Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ev_penyelenggaraan" required checked="" value="0" > Tidak
                                        <span></span>
                                    </label>
                                </div>
                                </div>
                                <div id="sarana" >
                                <label class="mt-3">Evaluasi Sarana</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ev_sarana" required checked="" value="1" > Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ev_sarana" required checked="" value="0" > Tidak
                                        <span></span>
                                    </label>
                                </div>
                                </div>
                                <div id="narsum" >
                                <label class="mt-3">Evaluasi Narasumber</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ev_narsum" required checked="" value="1" > Ya
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="ev_narsum" required checked="" value="0" > Tidak
                                        <span></span>
                                    </label>
                                </div>
                                </div>
                            </div>
                            <div hidden="hidden" id="reg_narsum" class="col-md-12 col-xs-12" >
                            <label class="mt-3">Pengajar <small><i>(* ketikkan nama narasumber kelas)</i></small></label>
                                <div id="f_narsum">
                                    <input class="form-control" name="narsum[]">
                                   <br>
                                </div>
                                <button class="btn btn-primary" id="addnarsum"> tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET NPS -->
                 <!-- START PIN ENTRY --> 
                 <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                               PIN
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-12 col-xs-12 text-center">   
                                <input class="form-control" value="<?=$pin?>" type="hidden" readonly name="cr_pin" id="cr_pin" value="">
                                <h4><?=$pin?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PIN ENRTY -->
            </div>

            <div class="col-lg-12">
                <!--Begin::Section-->
                <div class="kt-portlet">

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--solid">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <!--End::Section-->
            </div>

        </div>

        <?php echo form_close(); ?>

    </div>


</div>

<!--begin::Page Resources -->

<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        language: 'id',
        todayBtn: 'linked',
        todayHighlight:true,
        autoclose:true
    });
</script>

<script>
    // Class definition
    var KTBootstrapTimepicker = function() {
        // Private functions
        var timePickerInit = function() {

            $('.timepicker').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: false,
                showMeridian: false,
                snapToStep: true
            });


        };
        return {
            // public functions
            init: function() {
                timePickerInit();
            }
        };
    }();
    jQuery(document).ready(function() {
        KTBootstrapTimepicker.init();
    });
</script>

<script>
var kode_wallet = '';
</script>

<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
        window.onbeforeunload = function(e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.",
                    e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        $("form").submit(function() {
            var nil = $("#cr_kelola").find(":selected").val();
			if(nil=="lms_ext_agrowallet" && kode_wallet=="") {
				alert('Kode AgroWallet wajib diisi untuk opsi pelatihan yang dikelola di LMS Vendor AgroWallet External');
				return false;
			} else if(nil=="luar_app" && kode_wallet!="") {
				alert('Kode AgroWallet wajib dikosongkan untuk opsi pelatihan yang dikelola di luar AgroNow (100% peserta dari Luar PTPN Group)');
				return false;
			}
			
            submitted = true;

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>



<script>
    /*
	var Select2 = {
        init: function() {
            $("#select2_cat").select2({
                placeholder: "Cari..",
                allowClear: !0,
                multiple: false,
                ajax: {
                    url: "<?php echo site_url('classroom_category/ajax_search'); ?>",
                    dataType: "json",
                    delay: 50,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: 0
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0
            }).on('select2:unselecting', function() {
                $(this).data('unselecting', true);
            }).on('select2:opening', function(e) {
                if ($(this).data('unselecting')) {
                    $(this).removeData('unselecting');
                    e.preventDefault();
                }
            });
		}
	};
	*/
	
	function setupUI_detail(kategori) {
		if(kategori=="dalam_app") {
			$('#det_ui').show();
		} else {
			$('#det_ui').hide();
		}
	}
	
    jQuery(document).ready(function() {
        // Select2.init();
		
		setupUI_detail($("#cr_kelola").find(":selected").val());
		$("#cr_kelola").change(function(){
			setupUI_detail( $(this).find(":selected").val() );
		});
		
		// ajax superapp manpro
		$("#ajax_superapp_manpro").select2({
			placeholder: "...",
			multiple: false,
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: "<?php echo site_url('superapp_helper/ajax_search_manpro'); ?>",
				dataType: "json",
				delay: 50,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.results,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: 0
			},
			templateSelection: function (repo) {
				
				if (repo.kode === undefined) {
					return 'masukkan kode/nama project superapp';
				}
				
				var data = 
					'<table class="mt-1 table table-sm table-bordered">'+
					'<tr><td>kode produk:<br/>'+repo.nama_produk+'</td></tr>'+
					'<tr><td>kode proyek:<br/>'+repo.kode+'</td></tr>'+
					'<tr><td>nama proyek:<br/>'+repo.nama+'</td></tr>'+
					'</table>';
				
				$('#detail_superapp_manpro').html(data);
				$('#kode_superapp_manpro').val(repo.kode);
				$('#cr_name').val(repo.nama);
				$('#cr_name').attr('readonly',true);
				return repo.kode;
			},
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
		});
		// clear select2 value
		$("#ajax_superapp_manpro").on("select2:unselecting", function(e) {
			$('#detail_superapp_manpro').html("");
			$('#kode_superapp_manpro').val("");
		});
		
		// ajax learning wallet
		$("#ajax_lw").select2({
			placeholder: "...",
			multiple: false,
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: "<?php echo site_url('learning_wallet/ajax_search_agrowallet_pelatihan'); ?>",
				dataType: "json",
				delay: 50,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.results,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: 0
			},
			templateSelection: function (repo) {
				if (repo.kode === undefined) {
					return 'masukkan kode/nama agrowallet';
				}
				
				var data = 
					'<table class="mt-1 table table-sm table-bordered">'+
					'<tr><td style="width:20%">status</td><td>'+repo.status_penyelenggaraan+'</td></tr>'+
					'<tr><td>kode</td><td>'+repo.kode+'</td></tr>'+
					'<tr><td>nama</td><td>'+repo.nama+'</td></tr>'+
					'<tr><td>jpl</td><td>'+repo.jumlah_jam+'</td></tr>'+
					'<tr><td>tgl</td><td>'+repo.tgl_mulai+' sd '+repo.tgl_selesai+'</td></tr>'+
					'<tr><td>catatan</td><td>'+repo.catatan_penyelenggaraan+'</td></tr>'+
					'</table>';
				
				$('#detail_lw_classroom').html(data);
				  
				return repo.kode;
			},
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
		});
		// clear select2 value
		$("#ajax_lw").on("select2:unselecting", function(e) {
			$('#detail_lw_classroom').html("");
			kode_wallet = '';
		});
		// get selected kode wallet
		$("#ajax_lw").on("select2:select", function (e) {
			kode_wallet = $(e.currentTarget).val();
		});
    });
</script>


<script>
    $( document ).ready(function() {
        $("input[name=ev_narsum]").on("change",function(){
            var evaluasi=$("input[name=ev_narsum]:checked").val();
            if(evaluasi == 1){
               $("#reg_narsum").removeAttr('hidden');
            }else{
                $("#reg_narsum").attr("hidden",true);
            }
        })

        $("#addnarsum").on('click',function(e){
            e.preventDefault();
            var field="<input class='form-control' name='narsum[]'><br>";
            $("#f_narsum").append(field);
        })


        
       
    });
</script>
<!--end::Page Resources -->