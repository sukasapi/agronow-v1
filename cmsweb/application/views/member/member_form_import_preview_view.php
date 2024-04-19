<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("member/import"); ?>" class="btn kt-subheader__btn-primary">
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
        echo form_open($form_action, $attributes);
        ?>
        <input autocomplete="false" name="hidden" type="text" style="display:none;">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-12">

                <!-- START PORTLET IMPORT PREVIEW -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Preview
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


                            <div class="col-6">

                                <input type="hidden" name="group_id" value="<?php echo $request['group_id']; ?>" >

                                <label class="mt-3">Group</label>
                                <?php
                                if (validation_errors()) {$val = set_value('group_id');}else{$val = isset($request) ? htmlentities($request['group_id'], ENT_QUOTES) : NULL;}

                                $attr = 'id="group_id" class="form-control" disabled';
                                echo form_dropdown('group_id', $form_opt_group, $val, $attr);
                                ?>

                            </div>

                            <div class="col-12">

                                <hr>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm nowraps" id="kt_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="16px">No</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Level</th>
                                            <th class="text-center">NIK Lama</th>
											<th class="text-center">NIK Username</th>
                                            <th class="text-center">No. Telp</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Catatan</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php if ($sheet_data): ?>
                                        <?php foreach ($sheet_data as $k => $v): ?>
                                        <tr class="<?= $v['is_error']==1?'bg-secondary':'' ?>">
                                            <td>
                                                <?= $k+1 ?>
                                                <?php if ($v['is_error']!=1): ?>
                                                <input type="hidden" name="member_name[<?=$k?>]" value="<?= $v['nama'] ?>" />
                                                <input type="hidden" name="member_level[<?=$k?>]" value="<?= $v['level'] ?>" />
                                                <input type="hidden" name="member_nik_lama[<?=$k?>]" value="<?= $v['nik_lama'] ?>" />
												<input type="hidden" name="member_nik_username[<?=$k?>]" value="<?= $v['nik_username'] ?>" />
                                                <input type="hidden" name="member_phone[<?=$k?>]" value="<?= $v['no_telp'] ?>" />
                                                <input type="hidden" name="member_email[<?=$k?>]" value="<?= $v['email'] ?>" />
												<input type="hidden" name="member_id[<?=$k?>]" value="<?= $v['member_id'] ?>" />
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $v['nama'] ?></td>
                                            <td><?= $v['level'] ?></td>
                                            <td><?= $v['nik_lama'] ?></td>
											<td><?= $v['nik_username'] ?></td>
                                            <td><?= $v['no_telp'] ?></td>
                                            <td><?= $v['email'] ?></td>
                                            <td><?= $v['error_message'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <br>
                                    <p class="text-info">Baris yang terdapat pesan error akan dilewati ketika melakukan import data</p>
                                </div>

                            </div>


                        </div>

                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" name="import" class="btn btn-success pl-5 pr-5">Import</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- END PORTLET IMPORT PREVIEW -->

            </div>


        </div>

        <?php echo form_close(); ?>

    </div>


</div>




<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function() {

        var initTable1 = function() {
            var table = $('#kt_table');

            // begin first table
            table.DataTable({
				aLengthMenu: [
					[25, 50, 100, 200, -1],
					[25, 50, 100, 200, "All"]
				],
				iDisplayLength: -1,
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                responsive: false,
                stateSave: false,
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



<!--end::Page Resources -->