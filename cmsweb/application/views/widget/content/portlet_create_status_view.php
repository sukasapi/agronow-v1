<!-- START PORTLET STATUS -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Status
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

                <div class="kt-radio-inline">
                    <label class="kt-radio kt-radio--solid">
                        <input type="radio" name="content_status" required checked="" value="draft" <?php
                        echo set_value('content_status', ($editable)?$request['content_status']:'') == 'draft' ? "checked" : "checked";
                        ?>> Draft
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--solid">
                        <input type="radio" name="content_status" required value="publish" <?php
                        echo set_value('content_status', ($editable)?$request['content_status']:'') == 'publish' ? "checked" : "";
                        ?>> Publish
                        <span></span>
                    </label>
                </div>

                <label class="mt-3">Publish</label>
                <input type="text" class="form-control date-time-picker" placeholder="dd/mm/yyyy hh:ii" name="content_publish_date" value="<?php
                if (validation_errors()) {echo set_value('content_publish_date');}else{echo isset($request) ? htmlentities(date('d/m/Y',strtotime($request['content_publish_date'])), ENT_QUOTES) : '';} ?>">



            </div>
        </div>

    </div>
</div>
<!-- END PORTLET STATUS -->