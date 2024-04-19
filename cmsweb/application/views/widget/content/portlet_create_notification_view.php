<!-- START PORTLET NOTIF -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Notifikasi Aplikasi
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
                        <input type="radio" name="content_notif" required value="1" <?php
                        echo set_value('content_notif', ($editable)?$request['content_notif']:'') == '1' ? "checked" : "";
                        ?>> Ya
                        <span></span>
                    </label>
                    <label class="kt-radio kt-radio--solid">
                        <input type="radio" name="content_notif" required checked="" value="0" <?php
                        echo set_value('content_notif', ($editable)?$request['content_notif']:'') == '0' ? "checked" : "checked";
                        ?>> Tidak
                        <span></span>
                    </label>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- END PORTLET NOTIF -->