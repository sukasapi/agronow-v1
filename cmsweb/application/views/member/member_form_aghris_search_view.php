<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-12">

                <!-- START PORTLET SEARCH AGHRIS -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__body">

                        <?php
                        $attributes = array('autocomplete'=>"off");
                        echo form_open($form_action, $attributes);
                        ?>
                        <input autocomplete="false" name="hidden" type="text" style="display:none;">

                        <div class="row">
                            <div class="col-3">

                                <label class="mt-3">Berdasarkan *</label>
                                <?php
                                if (validation_errors()) {$val = set_value('search_by');}else{$val = isset($search_by) ? $search_by : '';}

                                $attr = 'id="search_by" class="form-control" required';
                                echo form_dropdown('search_by', $form_opt_search_by, $val, $attr);
                                ?>

                            </div>

                            <div class="col-6">

                                <label class="mt-3">Kata Kunci *</label>
                                <input type="text" class="form-control" placeholder="" name="keyword" required value="<?php
                                if (validation_errors()) {echo set_value('keyword');}else{echo isset($keyword) ? htmlentities($keyword, ENT_QUOTES) : '';} ?>">

                            </div>

                            <div class="col-3">
                                <button type="submit" name="search" class="btn btn-outline-info pl-5 pr-5 mt-5"><i class="fa fa-search"></i> Cari</button>
                            </div>

                        </div>

                        <?php echo form_close(); ?>

                    </div>


                </div>
                <!-- END PORTLET SEARCH AGHRIS -->


                <!-- START PORTLET SEARCH AGHRIS RESULT -->
                <?php if (isset($result)): ?>
                <?php if ($result): ?>
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Hasil Pencarian
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>


                    <div class="kt-portlet__body">

                        <?php
                            //print_r($result);
                        ?>

                        <p class="text-center">
                            Silahkan klik kolom untuk memilih member, kemudian klik tombol 'Tambahkan'
                        </p>

                        <!--begin: Datatable -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm nowrap" id="dt_member_selector">
                                <thead>
                                <tr>
                                    <th class="text-center">Nama</th>         <!--index 0-->
                                    <th class="text-center">Group</th>        <!--index 1-->
                                    <th class="text-center">NIP</th>          <!--index 2-->
                                    <th class="text-center">Jabatan</th>      <!--index 3-->
                                    <th class="text-center">No. HP</th>       <!--index 4-->

                                    <th class="text-center d-none">Group ID</th>          <!--index 5-->
                                    <th class="text-center d-none">Company Code</th>      <!--index 6-->
                                    <th class="text-center d-none">Position Code</th>     <!--index 7-->
                                    <th class="text-center d-none">Token</th>             <!--index 8-->
                                    <th class="text-center d-none">Image</th>             <!--index 9-->
                                    <th class="text-center d-none">Personel Area</th>     <!--index 10-->
                                    <th class="text-center d-none">Jenis Kelamin</th>     <!--index 11-->
                                    <th class="text-center d-none">Birth Place</th>       <!--index 12-->
                                    <th class="text-center d-none">Birth Date</th>        <!--index 13-->
                                    <th class="text-center d-none">Address</th>           <!--index 14-->
                                    <th class="text-center d-none">City</th>              <!--index 15-->
                                    <th class="text-center d-none">Province</th>          <!--index 16-->
                                    <th class="text-center d-none">Post Code</th>         <!--index 17-->
                                    <th class="text-center d-none">CEO Code</th>          <!--index 18-->
                                    <th class="text-center d-none">Create Date</th>       <!--index 19-->

                                    <th class="text-center d-none">Email</th>             <!--index 20-->
                                    <th class="text-center d-none">Position Name</th>     <!--index 21-->
									
									<th class="text-center d-none">Level Karyawan</th>     <!--index 22-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($result as $k => $v):
									if($v['company_code']=="N003" && $v['personnel_area_code']=="1HOL") { // holding?
										$arrG = $this->group_model->gets('15');
										$group_name = $arrG[0]['group_name'];
										$group_id = $arrG[0]['group_id'];
									} else {
										$group_name = aghris_group_name_by_code($v['company_code']);
										$group_id = aghris_group_id_by_code($v['company_code']);
									}
									
									if(empty($group_id) || $group_id=="34") continue;
								?>
                                <tr>
                                    <td><?= $v['employee_name'] ?></td>
                                    <td><?= $group_name ?></td>
                                    <td><?= $v['nik_sap'] ?></td>
                                    <td><?= $v['job_descr'] ?></td>
                                    <td><?= $v['phone'] ?></td>

                                    <td class="d-none"><?= $group_id ?></td>
                                    <td class="d-none"><?= $v['company_code'] ?></td>
                                    <td class="d-none"><?= $v['position_code'] ?></td>
                                    <td class="d-none"><?= $v['token'] ?></td>
                                    <td class="d-none"><?= $v['employee_foto'] ?></td>
                                    <td class="d-none"><?= $v['personnel_area_descr'] ?></td>
                                    <td class="d-none"><?= $v['jenis_kelamin'] ?></td>
                                    <td class="d-none"><?= $v['birth_place'] ?></td>
                                    <td class="d-none"><?= $v['birth_date'] ?></td>
                                    <td class="d-none"><?= $v['address'] ?></td>
                                    <td class="d-none"><?= $v['city'] ?></td>
                                    <td class="d-none"><?= $v['province'] ?></td>
                                    <td class="d-none"><?= $v['postcode'] ?></td>
                                    <td class="d-none"><?= $v['ceo_code'] ?></td>
                                    <td class="d-none"><?= $v['create_date'] ?></td>

                                    <td class="d-none"><?= $v['email'] ?></td>
                                    <td class="d-none"><?= $v['position_descr'] ?></td>
									
									<td class="d-none"><?= $v['bod_minus'] ?></td>

                                </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                        <!--end: Datatable -->


                        <?php
                        $attributes = array('id'=>'form-json', 'autocomplete'=>"off");
                        echo form_open(site_url('member/aghris_sync'), $attributes);
                        ?>
                        <div class="col-12 mt-3">
                            <br>
                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                <input type="checkbox" value="1" name="update_existing_data"> Update data jika terdapat NIP yang sudah terdaftar di Agronow
                                <span></span>
                            </label>
                            <br>
                            <input type="hidden" id="raw_data_json" name="raw_data_json">
                            <button type="submit" class="btn btn-success mt-2" id="picker-member" title="Tambahkan">Tambahkan <i class="fa fa-chevron-right"></i> </button>
                        </div>
                        <?php form_close(); ?>

                    </div>

                </div>
                <?php endif; ?>
                <?php endif; ?>
                <!-- END PORTLET SEARCH AGHRIS RESULT -->


                <!-- START PORTLET SEARCH AGHRIS RESULT NOT FOUND -->
                <?php if (!isset($result) && isset($keyword)): ?>
                    <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">

                        <div class="kt-portlet__body">

                            <h4 class="text-center text-muted">Data Tidak Ditemukan</h4>

                        </div>

                    </div>
                <?php endif; ?>
                <!-- END PORTLET SEARCH AGHRIS RESULT NOT FOUND -->

            </div>


        </div>

        <?php echo form_close(); ?>

    </div>


</div>



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



<style type="text/css">
    #dt_member_selector tbody tr{
        cursor:pointer;
    }
    .selected td {
        background-color: #c2f2f7 !important; /* Add !important to make sure override datables base styles */
    }
</style>


<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {


            var table = $('#dt_member_selector');

            // begin first table
            table.DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
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



<script type="text/javascript">
    $(document).ready(function() {

        $('#picker-member').prop('disabled', true);

        $('#dt_member_selector tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');

            var data = $('#dt_member_selector').DataTable().rows('.selected').data();

            var dataRaw = [];
            $.each(data, function( index, value ) {
                dataRaw.push(value);
            });

            $('#raw_data_json').val(JSON.stringify(dataRaw));

            // Cek Value
            if ($('#raw_data_json').val() === '' || $('#raw_data_json').val() === '[]'){
                $('#picker-member').prop('disabled', true);
            } else{
                $('#picker-member').prop('disabled', false);
            }

        } );


        $('#picker-member').click( function () {

            var data = $('#dt_member_selector').DataTable().rows('.selected').data();

            if(data.length>0) {
                //addSelectedItem(data);
                //console.log(data);
            } else{
                alert('Anda belum memilih data.');
            }
        });


    } );
</script>



<!--end::Page Resources -->