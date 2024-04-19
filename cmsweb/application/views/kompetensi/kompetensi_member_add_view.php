<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("kompetensi"); ?>" class="btn kt-subheader__btn-primary">
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
            $this->load->view('validation_notif_view');
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $kompetensi;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET KONTEN -->
                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $kompetensi['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Tambah Peserta
                            </h3>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">
                                <label>Member</label>
                                <?php
                                $form_opt_member = array();
                                $selected_value = '';

                                $attr = 'class="form-control" id="select2_member" required multiple="multiple"';
                                echo form_dropdown('member_ids[]', $form_opt_member, $selected_value, $attr);

                                ?>
                            </div>

                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('kompetensi/member/').$kompetensi['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->


            </div>


        </div>
    </div>

</div>




<script>
    var Select2 = {
        init: function() {

            $("#select2_member").select2({
                placeholder: "Cari..",
                allowClear: !0,
                multiple: true,
                ajax: {
                    url: "<?php echo site_url('member/ajax_search'); ?>",
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
    jQuery(document).ready(function() {
        Select2.init()
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

