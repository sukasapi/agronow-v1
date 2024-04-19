<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("kompetensi/?year=").$year; ?>" class="btn kt-subheader__btn-primary">
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

            <div class="col-lg-12">
                <!--Begin::Section-->
                <div class="kt-portlet">

                    <?php
                    $attributes = array('id'=>'form-validator','class' => 'm-form m-form--fit m-form--label-align-right');
                    echo form_open($form_action, $attributes);
                    ?>

                    <div class="kt-portlet__body">

                        <div class="form-group m-form__group row">

                            <input type="hidden" name="year_from" value="<?php echo $year; ?>" />

                            <div class="col-lg-6">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tahun Asal</label>
                                        <input type="number" class="form-control bg-secondary" disabled readonly value="<?= $year ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Tahun Tujuan *</label>
                                        <input type="number" id="year_to" class="form-control" required name="year_to">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-6">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Tanggal Mulai (Default) *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="cr_date_start" value="<?php
                                        if (validation_errors()) {echo set_value('cr_date_start');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['cr_date_start'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Tanggal Selesai (Default) *</label>
                                        <input type="text" class="form-control date-picker" placeholder="dd/mm/yyyy" required name="cr_date_end" value="<?php
                                        if (validation_errors()) {echo set_value('cr_date_end');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['cr_date_end'])), ENT_QUOTES) : NULL;} ?>">
                                    </div>
                                </div>

                            </div>


                        </div>



                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p style="font-size:18px">Anda yakin ingin menyalin semua kompetensi di tahun <b><?php echo $year; ?></b>?</p>


                                    <button type="submit" id="btn-save" class="btn btn-info pl-5 pr-5">Duplikat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <!--End::Section-->
            </div>

        </div>

    </div>

</div>


<!--begin::Page Resources -->
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
        });
    });
</script>


<script>
    // SKIP HISTORY
    var stateObj = {};
    history.replaceState(stateObj, "", "");
</script>



<script>


    function validate_year_to() {

        var year_to = $("#year_to").val();

        var url = '<?= site_url('kompetensi/is_year_available/') ?>'+year_to;

        $.get( url, function( data ) {
            var obj = JSON.parse(data);

            //console.log(obj);
            if (obj.status==false){
                //$("#year_to").val('');
                alert(obj.message);

                $('#btn-save').prop('disabled', true);


            } else{

                $('#btn-save').prop('disabled', false);

            }

        });

    }


    $( "#year_to" ).keyup(function() {
        validate_year_to();
    });


</script>


<!--end::Page Resources -->