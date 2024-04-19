<!-- START PORTLET CONTENT -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Konten
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
                <label>Judul <span class="required" aria-required="true"> * </span></label>
                <input type="text" class="form-control" placeholder="" name="content_name" required value="<?php
                if (validation_errors()) {echo set_value('content_name');}else{echo isset($request) ? htmlentities($request['content_name'], ENT_QUOTES) : '';} ?>">

                <label class="mt-3">Deskripsi <span class="required" aria-required="true"> * </span></label>
                <textarea id="content" name="content_desc" style="min-height: 500px" class="form-control"><?php
                    if (validation_errors()) {echo set_value('content_desc');}else{echo isset($request) ? htmlentities($request['content_desc'], ENT_QUOTES) : '';} ?></textarea>

                <label class="mt-3">Sumber</label>
                <input type="text" class="form-control" placeholder="" name="content_source" value="<?php
                if (validation_errors()) {echo set_value('content_source');}else{echo isset($request) ? htmlentities($request['content_source'], ENT_QUOTES) : '';} ?>">

                <label class="mt-3">Pengarang</label>
                <input type="text" class="form-control" placeholder="" name="content_author" value="<?php
                if (validation_errors()) {echo set_value('content_author');}else{echo isset($request) ? htmlentities($request['content_author'], ENT_QUOTES) : '';} ?>">

                <label class="mt-3">Tag</label>
                <?php


                if (validation_errors()) {
                    $val_tags = set_value('content_tags');
                    if($val_tags){
                        foreach ($val_tags as $k => $v) {
                            $val[$v] = $v;
                            $selected_val[$v]  = $v;
                        }
                    }

                }else{
                    $val  = array();
                    $selected_val  = NULL;
                }

                $attr = 'class="form-control" id="select2-content-tags" multiple="multiple"';
                echo form_dropdown('content_tags[]', $val, $selected_val, $attr);

                ?>


            </div>
        </div>

    </div>
</div>
<!-- END PORTLET CONTENT -->


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

