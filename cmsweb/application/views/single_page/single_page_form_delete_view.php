<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("single_page/detail/").$request['content_id']; ?>" class="btn kt-subheader__btn-primary">
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

                            <input type="hidden" name="content_id" value="<?php echo $request['content_id']; ?>" />

                            <div class="col-lg-6">

                                <p style="font-size:18px">Anda yakin ingin menghapus <b><?php echo $request['content_name']; ?></b>?</p>

                            </div>


                        </div>



                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions m-form__actions--solid">
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-danger pl-5 pr-5">Hapus</button>
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


<!--end::Page Resources -->