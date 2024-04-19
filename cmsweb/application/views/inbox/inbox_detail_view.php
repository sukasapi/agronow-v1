<!-- begin:: Subheader -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

    </div>
    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <a href="<?php echo site_url("inbox"); ?>" class="btn kt-subheader__btn-primary">
                <i class="flaticon2-back"></i>
                Kembali
            </a>
        </div>
    </div>
</div>
<!-- end:: Subheader -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">


    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="col-12">
            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>
        </div>

        <!--Begin::App-->
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

            <!--Begin:: App Content-->
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content" id="kt_chat_content">
                <div class="kt-chat">
                    <div class="kt-portlet kt-portlet--head-lg kt-portlet--last">
                        <div class="kt-portlet__head">
                            <div class="kt-chat__head ">
                                <div class="kt-chat__center">
                                    <div class="kt-chat__label">
                                        <a href="#" class="kt-chat__title"><?= $chat[0]['inbox_title'] ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body" id="chat-log">
                            <!-- CHAT LOG -->
                            <div class="col-sm text-center">
                                <div class="kt-spinner kt-spinner--lg kt-spinner--center kt-spinner--warning"></div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">

                            <?php
                            $form_action = '';
                            $attributes = array('autocomplete'=>"off",'id'=>'form-reply');
                            echo form_open($form_action, $attributes);
                            ?>
                            <div class="kt-chat__input  <?= has_access("inbox.reply",FALSE)?"":"d-none" ?>">
                                <div class="kt-chat__editors">
                                    <textarea style="height: 72px" class="form-control" name="message" required placeholder="Ketik di sini..."></textarea>
                                </div>
                                <div class="kt-chat__toolbar">
                                    <div class="kt_chat__tools">
                                        <!--<a href="#"><i class="flaticon2-link"></i></a>
                                        <a href="#"><i class="flaticon2-photograph"></i></a>
                                        <a href="#"><i class="flaticon2-photo-camera"></i></a>-->
                                    </div>
                                    <div class="kt_chat__actions">
                                        <button type="submit" class="btn btn-brand btn-md btn-upper btn-bold kt-chat__reply"><i class="fa fa-paper-plane"></i> BALAS </button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>

                        </div>
                    </div>
                </div>
            </div>

            <!--End:: App Content-->
        </div>

        <!--End::App-->
    </div>

    <!-- end:: Content -->
</div>


<script>

    function load_chat_log() {
        $.get("<?= site_url('inbox/chat_log/').$chat[0]['inbox_id'] ?>", function(data, status){
            $('#chat-log').html(data);
        });
    }

    load_chat_log();

    // Bind to the submit event of our form
    $("#form-reply").submit(function(event){

        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();


        // setup some local variables
        var $form = $(this);

        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = $form.serialize();

        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);

        // Fire off the request to /form.php
        request = $.ajax({
            url: "<?= site_url('inbox/reply/').$chat[0]['inbox_id'] ?>",
            type: "post",
            data: serializedData
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
            // Log a message to the console
            console.log("Hooray, it worked!");

            $('#form-reply').trigger("reset");
            load_chat_log();
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );

            $inputs.prop("disabled", false);
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });

    });
</script>