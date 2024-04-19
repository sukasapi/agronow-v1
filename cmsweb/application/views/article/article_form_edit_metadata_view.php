<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("article/detail/").$content['content_id']; ?>" class="btn kt-subheader__btn-primary">
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
        <input type="hidden" name="content_id" value="<?php echo $content['content_id']; ?>" >
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <?php if (!my_klien()): ?>
                <!-- Superadmin -->
                <div class="col-lg-4">
                    <!-- START PORTLET KLIEN -->
                    <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title text-primary">
                                    Klien
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-group">
                                    <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                                </div>
                            </div>
                        </div>

                        <div class="kt-portlet__body pt-3 pb-0">

                            <div class="row">
                                <div class="col-12">

                                    <div class="form-group">
                                        <div class="kt-checkbox-list">
                                            <?php
                                            $arr_value = getKlienBySectionData($content['section_id'],$content['content_id'],'content');
                                            if (validation_errors()){
                                                $arr_value = set_value('klien')?set_value('klien'):array();
                                            }
                                            ?>

                                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                                <input type="checkbox" id="check-klien-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="klien[]" onchange="checkAll(this,'check-klien')"> SEMUA
                                                <span></span>
                                            </label>
                                            <?php foreach($klien as $k => $v):?>
                                                <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                                    <input type="checkbox" class="check-klien" <?php echo in_array($v['id'],$arr_value)==TRUE?'checked':''; ?>  value="<?php echo $v['id']; ?>" name="klien[]"> <?php echo $v['nama']; ?>
                                                    <span></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- END PORTLET KLIEN -->
                </div>
            <?php endif; ?>

            <div class="col-lg-4">
                <!-- START PORTLET MEMBER LEVEL -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Level Member
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body pt-3 pb-0">

                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <div class="kt-checkbox-list">
                                        <?php
                                        $arr_value = $content['mlevel_id']?explode(',',$content['mlevel_id']):array();
                                        if (validation_errors()){
                                            $arr_value = set_value('member_level')?set_value('member_level'):array();
                                        }
                                        ?>

                                        <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                            <input type="checkbox" id="check-mlevel-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="member_level[]" onchange="checkAll(this,'check-mlevel')"> SEMUA
                                            <span></span>
                                        </label>
                                        <?php foreach($member_level as $k => $v):?>
                                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                                <input type="checkbox" class="check-mlevel" <?php echo in_array($v['mlevel_id'],$arr_value)==TRUE?'checked':''; ?>  value="<?php echo $v['mlevel_id']; ?>" name="member_level[]"> <?php echo $v['mlevel_name']; ?>
                                                <span></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET MEMBER LEVEL -->
            </div>

            <div class="col-lg-4">
                <!-- START PORTLET BIDANG MEMBER -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Bidang Member
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body pt-3 pb-0">

                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <div class="kt-checkbox-list">
                                        <?php
                                        $arr_value = $content['content_bidang']?explode(',',$content['content_bidang']):array();
                                        if (validation_errors()){
                                            $arr_value = set_value('bidang')?set_value('bidang'):array();
                                        }
                                        ?>

                                        <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                            <input type="checkbox" id="check-bidang-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="bidang[]" onchange="checkAll(this,'check-bidang')"> SEMUA
                                            <span></span>
                                        </label>

                                        <?php foreach($bidang as $k => $v): ?>
                                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                                <input type="checkbox" class="check-bidang" <?php echo in_array($v['bidang_id'],$arr_value)==TRUE?'checked':''; ?>
                                                       value="<?php echo $v['bidang_id']; ?>" name="bidang[]"> <?php echo $v['bidang_name']; ?>
                                                <span></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- END PORTLET BIDANG MEMBER -->
            </div>


            <div class="col-lg-12">
                <!--Begin::Section-->
                <div class="kt-portlet">

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-info pl-5 pr-5">Update</button>
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