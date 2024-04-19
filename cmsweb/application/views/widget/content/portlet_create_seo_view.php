<!-- START PORTLET SEO -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                SEO
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


                <label>SEO Title</label>
                <input type="text" class="form-control" placeholder="" name="content_seo_title" value="<?php
                if (validation_errors()) {echo set_value('content_seo_title');}else{echo isset($request) ? htmlentities($request['content_seo_title'], ENT_QUOTES) : '';} ?>">

                <label class="mt-3">SEO Keywords</label>
                <input type="text" class="form-control" placeholder="" name="content_seo_keyword" value="<?php
                if (validation_errors()) {echo set_value('content_seo_keyword');}else{echo isset($request) ? htmlentities($request['content_seo_keyword'], ENT_QUOTES) : '';} ?>">

                <label class="mt-3">SEO Deskripsi</label>
                <textarea name="content_seo_desc" class="form-control"><?php
                    if (validation_errors()) {echo set_value('content_seo_desc');}else{echo isset($request) ? htmlentities($request['content_seo_desc'], ENT_QUOTES) : '';} ?></textarea>

            </div>
        </div>

    </div>
</div>
<!-- END PORTLET SEO -->