<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('whatsnew/bod_share')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">EDIT BOD SHARE</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="demoPage">
    <div class="section full mt-2 mb-2 ml-2 mr-2">
        <div class="wide-block pt-2 pb-2">
            <form action="<?=base_url('whatsnew/bod_share/edit/').$content['content_id']?>" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Topik</label>
                        <input type="text" name="content_name" value="<?= $content['content_name']; ?>" class="form-control" placeholder="" required>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">OK!</div>
                        <div class="invalid-feedback">Kolom ini tidak boleh kosong!</div>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Keterangan</label>
                        <textarea id="keterangan" name="content_desc" rows="2" class="form-control" required><?= $content['content_desc']; ?></textarea>
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                        <div class="valid-feedback">OK!</div>
                        <div class="invalid-feedback">Kolom ini tidak boleh kosong!</div>
                    </div>
                </div>
                <?php if(isset($content['image']['media_value'])): ?>
                    <img src="<?=URL_MEDIA_IMAGE.$content['image']['media_value']; ?>" style="width: 100%;" alt="image">
                <?php endif ?>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label">Upload Gambar</label>
                        <div class="custom-file-upload">
                            <input type="file" id="fileuploadInput" name="content_image1" accept=".png, .jpg, .jpeg">
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
                <div class="form-group mt-2 mb-3">
                    <div class="input-wrapper">
                        <label class="label">Status</label>
                        <div class="d-flex justify-content-start mt-2">
                            <div class="custom-control custom-radio mr-1">
                                <input type="radio" id="customRadio1" name="content_status" value="draft" <?= $content['content_status']=='draft'?'checked':'' ?> class="custom-control-input" required>
                                <label class="custom-control-label" for="customRadio1">DRAFT</label>
                                <div class="valid-feedback">&nbsp;</div>
                                <div class="invalid-feedback">Kolom ini tidak boleh kosong!</div>
                            </div>
                            <div class="custom-control custom-radio ml-1">
                                <input type="radio" id="customRadio2" name="content_status" value="publish" class="custom-control-input" <?= $content['content_status']=='publish'?'checked':'' ?> >
                                <label class="custom-control-label" for="customRadio2">PUBLISH</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-warning btn-block" type="submit">Submit</button>
                </div>
           </form>
        </div>
    </div>
</div>
<!-- * App Capsule -->
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        ClassicEditor
            .create( document.querySelector('#keterangan'), {
                removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed'],
            } )
            .then( editor => {
                console.log( editor );
            })
            .catch( error => {
                console.error( error );
            });
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