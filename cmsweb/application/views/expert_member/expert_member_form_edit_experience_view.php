<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("expert_member/detail/").$request['em_id']; ?>" class="btn kt-subheader__btn-primary">
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

        <input type="hidden" name="em_id" value="<?= $request['em_id'] ?>">

        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <div class="col-lg-8">

                <!-- START PORTLET MEMBER -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Informasi Expert
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
                                <label>Nama</label>
                                <input type="text" class="form-control bg-light" placeholder="" name="em_name" disabled readonly value="<?php
                                if (validation_errors()) {echo set_value('em_name');}else{echo isset($request) ? htmlentities($request['em_name'], ENT_QUOTES) : '';} ?>">


                                <label class="mt-3">Pengalaman Kerja</label>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Jabatan / Keahlian</th>
                                        <th class="text-center">Perusahaan</th>
                                        <th class="text-center">Tahun Mulai</th>
                                        <th class="text-center">Tahun Akhir</th>
                                        <th class="text-center">Default</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="fieldsExp">
                                    <?php
                                        $arr_exp = json_decode($request['em_experience'],TRUE);
                                        if ($arr_exp):
                                        foreach ($arr_exp as $k => $v):
                                    ?>
                                    <tr id="inputFormRowExp">
                                        <td>
                                            <input type="text" class="form-control" placeholder="" required name="work_title[<?= $k ?>]" value="<?= $v['title'] ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="" required name="work_institution[<?= $k ?>]" value="<?= $v['institution'] ?>">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="" name="work_year_start[<?= $k ?>]" value="<?= $v['yearStart'] ?>">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="" name="work_year_end[<?= $k ?>]" value="<?= $v['yearEnd'] ?>">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="work_is_default" required value="<?= $k ?>" <?= $v['isDefault']==1?'checked="checked"':'' ?>>
                                        </td>

                                        <td><button id="removeRowExp" type="button" class="btn btn-outline-danger btn-icon btn-circle btn-sm"><i class="fa fa-minus"></i></button></td>
                                    </tr>
                                    <?php
                                        endforeach;
                                        endif;
                                    ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-12">
                                        <button id="addRowExp" type="button" class="btn btn-outline-info btn-sm"><i
                                                    class="fa fa-plus"></i> Tambah Pengalaman Kerja
                                        </button>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    // add row
                                    var count = <?= sizeof($arr_exp)+1 ?>;
                                    $("#addRowExp").click(function () {
                                        var html = '';
                                        count ++;
                                        html += '<tr id="inputFormRowExp">';
                                        html += '<td><input type="text" class="form-control" placeholder="" required name="work_title['+count+']"></td>';
                                        html += '<td><input type="text" class="form-control" placeholder="" required name="work_institution['+count+']"></td>';
                                        html += '<td><input type="number" class="form-control" placeholder="" name="work_year_start['+count+']"></td>';
                                        html += '<td><input type="number" class="form-control" placeholder="" name="work_year_end['+count+']"></td>';
                                        html += '<td class="text-center"><input type="radio" name="work_is_default" required value="'+count+'"></td>';
                                        html += '<td><button id="removeRowExp" type="button" class="btn btn-outline-danger btn-icon btn-circle btn-sm"><i class="fa fa-minus"></i></button></td>';
                                        html += '</tr>';

                                        $('#fieldsExp').append(html);
                                    });

                                    // remove row
                                    $(document).on('click', '#removeRowExp', function () {
                                        $(this).closest('#inputFormRowExp').remove();
                                    });

                                </script>


                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET MEMBER -->




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
<script type="text/javascript">
    function checkAll(ele,className) {
        var checkboxes = document.getElementsByClassName(className);
        if (ele.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox' ) {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }

    $(".check-mlevel").click(function() {
        if ($(".check-mlevel").prop(":checked")) {

        } else {
            $('#check-mlevel-all').prop("checked", false);
        }
    });

    $(".check-bidang").click(function() {
        if ($(".check-bidang").prop(":checked")) {

        } else {
            $('#check-bidang-all').prop("checked", false);
        }
    });
</script>

<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datetimepicker({
        format: "dd/mm/yyyy hh:ii",
        language: 'id',
        todayBtn: true,
        todayHighlight:true,
        autoclose: true
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


<script>
    var Select2 = {
        init: function() {

            $("#select2-content-tags").select2({
                tags: true,
                tokenSeparators: [',']
            });


        }
    };
    jQuery(document).ready(function() {
        Select2.init()
    });
</script>




<!--end::Page Resources -->