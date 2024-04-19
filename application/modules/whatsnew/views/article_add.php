<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('whatsnew/bod_share')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">ADD ARTICLE</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="demoPage">
<form class="needs-validation" action="<?=base_url('whatsnew/article/add')?>" method="POST" enctype="multipart/form-data" novalidate>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Konten</div>
        <div class="wide-block pb-1 pt-2">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="judul">Judul*</label>
                    <input type="text" class="form-control" id="judul" placeholder="..." name="content_name" required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="deskripsi">Deskripsi*</label>
                    <textarea id="deskripsi" rows="6" class="form-control" name="content_desc" required></textarea>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="sumber">Sumber</label>
                    <input type="text" class="form-control" id="sumber" name="content_source" placeholder="..." required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="pengarang">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="content_author" placeholder="..." required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="tag">Tag</label>
                    <input type="text" class="form-control" id="tag" placeholder="..." name="content_tags[]" required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">SEO</div>
        <div class="wide-block pb-1 pt-2">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="seo_title">SEO Title*</label>
                    <input type="text" class="form-control" name="content_seo_title" id="seo_title" placeholder="..." required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="seo_keywords">SEO Keywords*</label>
                    <input type="text" class="form-control" name="content_seo_keyword" id="seo_keywords" name="content_seo_keyword" placeholder="..." required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="seo_deskripsi">SEO Deskripsi*</label>
                    <input type="text" class="form-control" name="content_seo_desc" id="seo_deskripsi" name="content_seo_desc" placeholder="..." required>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                    <div class="invalid-feedback">Kolom ini harus diisi.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Gambar</div>
        <div class="wide-block pb-1 pt-2">
            <div class="form-group">
                <div class="input-wrapper">
                    <label class="label">Upload Gambar</label>
                    <div class="custom-file-upload">
                        <input type="file" name="content_image1" id="fileuploadInput" accept=".png, .jpg, .gif">
                        <label for="fileuploadInput">
                            <span>
                                <strong>
                                    <ion-icon name="cloud-upload-outline"></ion-icon>
                                    <i>Tap to Upload</i>
                                </strong>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Status</div>
        <div class="wide-block pb-1 pt-2">
            <div class="form-group mt-2 mb-3">
                <div class="input-wrapper">
                    <div class="d-flex justify-content-start mt-2">
                        <div class="custom-control custom-radio mr-1">
                            <input type="radio" id="status1" name="content_status" value="draft" class="custom-control-input" required>
                            <label class="custom-control-label" for="status1">DRAFT</label>
                            <div class="invalid-feedback">Kolom ini harus diisi.</div>
                        </div>
                        <div class="custom-control custom-radio ml-1">
                            <input type="radio" id="status2" name="content_status" value="publish" class="custom-control-input" required>
                            <label class="custom-control-label" for="status2">PUBLISH</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="publish">Publish</label>
                    <input type="text" class="form-control datetimepicker-input" id="datetimepicker2" data-toggle="datetimepicker" data-target="#datetimepicker2"/>
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Notifikasi Aplikasi</div>
        <div class="wide-block pb-1 pt-2">
            <div class="form-group mt-2 mb-3">
                <div class="input-wrapper">
                    <div class="d-flex justify-content-start mt-2">
                        <div class="custom-control custom-radio mr-1">
                            <input type="radio" id="notifikasi1" name="content_notif" value="ya" class="custom-control-input" required>
                            <label class="custom-control-label" for="notifikasi1">Ya</label>
                            <div class="invalid-feedback">Kolom ini harus diisi.</div>
                        </div>
                        <div class="custom-control custom-radio ml-1">
                            <input type="radio" id="notifikasi2" name="content_notif" value="tidak" class="custom-control-input" required>
                            <label class="custom-control-label" for="notifikasi2">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Level Member</div>
        <div class="wide-block">
            <div class="input-list">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level0" name="member_level[]" value="all">
                    <label class="custom-control-label" for="level0">Semua</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level1" name="member_level[]" value="1">
                    <label class="custom-control-label" for="level1">Level 1</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level2" name="member_level[]" value="2">
                    <label class="custom-control-label" for="level2">Level 2</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level3" name="member_level[]" value="3">
                    <label class="custom-control-label" for="level3">Level 3</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level4" name="member_level[]" value="4">
                    <label class="custom-control-label" for="level4">Level 4</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level5" name="member_level[]" value="5">
                    <label class="custom-control-label" for="level5">Level 5</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="level6" name="member_level[]" value="6">
                    <label class="custom-control-label" for="level6">Level 6</label>
                </div>
            </div>
        </div>
    </div>
    <div class="section full mt-2 mb-2">
        <div class="section-title">Bidang Member</div>
        <div class="wide-block">
            <div class="input-list">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bidang0" name="bidang[]" value="all">
                    <label class="custom-control-label" for="bidang0">Semua</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bidang1" name="bidang[]" value="1">
                    <label class="custom-control-label" for="bidang1">Tanaman</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bidang2" name="bidang[]" value="2">
                    <label class="custom-control-label" for="bidang2">Teknik/Pengolahan</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bidang3" name="bidang[]" value="3">
                    <label class="custom-control-label" for="bidang3">Akuntansi/Keuangan</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bidang4" name="bidang[]" value="4">
                    <label class="custom-control-label" for="bidang4">SDM dan UMUM</label>
                </div>
            </div>
        </div>
    </div>
    <div class="p-2 mt-2 mb-2 text-center">
        <button class="btn btn-warning btn-block" type="submit">Submit</button>
    </div>
</form>
</div>
<!-- * App Capsule -->
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        $('#datetimepicker2').datetimepicker({
            format: "dd/mm/yyyy hh:ii",
            language: 'id',
            todayBtn: true,
            todayHighlight:true,
            autoclose: true
        });
        ClassicEditor.create( document.querySelector( '#deskripsi' ) )
        .then( editor => {
            console.log( editor );
        } )
        .catch( error => {
            console.error( error );
        } );
    });
</script>
<script>
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>