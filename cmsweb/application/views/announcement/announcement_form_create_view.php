<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("announcement"); ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-8">

                <!-- CONTENT -->
                <?php $this->load->view('widget/content/portlet_create_content_view', $data['request'] = isset($request)?$request:NULL); ?>

                <!-- SEO -->
                <?php $this->load->view('widget/content/portlet_create_seo_view', $data['request'] = isset($request)?$request:NULL); ?>

            </div>

            <div class="col-lg-4">

                <!-- IMAGE -->
                <?php $this->load->view('widget/content/portlet_create_image_view', $data['request'] = isset($request)?$request:NULL); ?>

                <!-- STATUS -->
                <?php $this->load->view('widget/content/portlet_create_status_view', $data['request'] = isset($request)?$request:NULL); ?>

                <!-- NOTIFICATION -->
                <?php $this->load->view('widget/content/portlet_create_notification_view', $data['request'] = isset($request)?$request:NULL); ?>

                <!-- KLIEN -->
                <?php if (!my_klien()): ?>
                    <?php $this->load->view('widget/content/portlet_create_klien_view', $data['request'] = isset($request)?$request:NULL); ?>
                <?php endif; ?>

                <!-- MEMBER LEVEL -->
                <?php $this->load->view('widget/content/portlet_create_member_level_view', $data['request'] = isset($request)?$request:NULL); ?>

                <!-- MEMBER BIDANG -->
                <?php $this->load->view('widget/content/portlet_create_member_bidang_view', $data['request'] = isset($request)?$request:NULL); ?>


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

    $(".check-klien").click(function() {
        if ($(".check-klien").prop(":checked")) {

        } else {
            $('#check-klien-all').prop("checked", false);
        }
    });
</script>

<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-time-picker').datetimepicker({
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

            // Handle TinyMce Blank
            var content = tinymce.get("content").getContent();
            if (content){

            } else{
                alert('Deskripsi tidak boleh kosong!')
                return false;
            }

            <?php if(!my_klien()): ?>
            // Handle Klien Blank
            checkedKlien = $(".check-klien:checked").length;
            if(!checkedKlien) {
                alert("Anda harus mencentang setidaknya satu Klien.");
                return false;
            }
            <?php endif; ?>

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