<!-- START PORTLET GRUP -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Grup
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-group">
                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

            </div>
        </div>
    </div>

    <div class="kt-portlet__body pt-3">

        <div class="row">
            <div class="col-12">

                <?php
                if (validation_errors()) {$val = set_value('group_id');}else{$val = isset($request['group_id']) ? htmlentities($request['group_id'], ENT_QUOTES) : NULL;}

                $attr = 'class="form-control"';
                echo form_dropdown('group_id', $form_opt_group, $val, $attr);
                ?>

            </div>
        </div>

    </div>
</div>
<!-- END PORTLET GRUP -->