<!-- START PORTLET GAMBAR -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Gambar
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
                <img id="image" src="<?php echo isset($request['media_value'])?URL_MEDIA_IMAGE.$request['media_value']:'' ?>" width="100%">
                <br>
                <label class="mt-3">Pilih Gambar</label>
                <br>
                <input type="file" id="files" name="file" accept="image/x-png,image/gif,image/jpeg">
                <br><br>
                <p id="uploadInfo">Gunakan gambar dengan ratio 16:9, contoh: 1280x720</p>
                <script>
                    document.getElementById("files").onchange = function () {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            // get loaded data and render thumbnail.
                            document.getElementById("image").src = e.target.result;
                        };
                        // read the image file as a data URL.
                        reader.readAsDataURL(this.files[0]);
                    };
                </script>
            </div>
        </div>

    </div>
</div>
<!-- END PORTLET GAMBAR -->