<style type="text/css">
    input#upload{
        position: absolute;
        /*font-size: 50px;*/
        opacity: 0;
        right: 0;
        top: 0;
    }
    div.upload-demo-wrap{
        width: 100%;
        height: 200px;
        border: 1px solid #aaa;
    }
    div.upload-demo-wrap{
        display: none;
    }
    div#croppieModal .modal-body.ready div.upload-demo-wrap {
        display: block;
    }
</style>
<div class="modal fade dialogbox" id="croppieModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-1">
                <h3 class="modal-title">Change Photo</h3>
            </div>
            <div class="modal-body p-1 m-0 text-center">
                <div class="file btn btn-lg btn-primary mb-2">
                    Pick a Photo
                    <input type="file" id="upload" accept="image/*" class="p-2" />
                </div>
                <div class="upload-demo-wrap" style="margin-bottom:35px;">
                    <div id="upload-demo"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-dismiss="modal"><ion-icon name="close"></ion-icon> TUTUP</a>
                    <button class="btn btn-success upload-result"><ion-icon name="save"></ion-icon> UBAH</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Croppie JS -->
<link rel="stylesheet" href="<?=PATH_ASSETS?>plugins/croppie/croppie.css?version=2.6.4" />
<script src="<?=PATH_ASSETS?>plugins/croppie/croppie.js?version=2.6.4"></script>
<script type="text/javascript">
    function demoUpload() {
        var $uploadCrop;

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('div#croppieModal .modal-body').addClass('ready');
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }

                reader.readAsDataURL(input.files[0]);
            }
            else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop = $('#upload-demo').croppie({
            viewport: {
                width: 170,
                height: 170,
                type: 'circle'
            },
            enableExif: true
        });

        $('#upload').on('change', function () { readFile(this); });
        $('.upload-result').on('click', function (ev) {            
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $('#photo_profile').attr('src', resp);
                $('input#image').val(resp);
                // popupResult({
                    // src: resp
                // });
            });
            $('#croppieModal').modal('hide');
        });
    }

    $(function(){
        $('#change_photo').click(function(event) {
            $('div#croppieModal .modal-body').removeClass('ready');
        });

        demoUpload();
    })
</script>