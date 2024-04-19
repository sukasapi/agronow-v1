<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/forum')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="right">
<!--        <a href="--><?//=base_url('learning/forum/category_suggest')?><!--" class="headerButton p-0">-->
<!--            <ion-icon name="bulb" class="text-white"></ion-icon>-->
<!--        </a>-->
        <a href="#" data-toggle="modal" data-target="#modalFormSuggest" class="headerButton p-0">
            <ion-icon name="bulb" class="text-white"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">FORUM</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full p-3">
        <div class="row">
            <?php foreach($data as $d): ?>
            <div class="col-6 my-auto mt-2">
                <a href="<?=base_url('learning/forum/listing?cat_id='.$d['cat_id'])?>" style="color:black">
                    <div class="" style="background:white">
                        <div class="text-center p-1">
                            <img src="<?=$d['cat_image']?>" alt="image" style="object-fit: cover;width:50px;height:50px;">
                        </div>
                        <div class="p-1 text-center">
                            <p class="card-text"><small><?=$d['cat_name']?></small></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="modal fade modalbox" id="modalFormSuggest" data-backdrop="static" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usulkan Kategori</h5>
                    <a href="javascript:;" data-dismiss="modal">Close</a>
                </div>
                <div class="modal-body">
                    <div class="section mt-4 mb-5">
                        <form action="<?=base_url('learning/forum/ajax_category_suggest')?>" id="formSuggest">
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Kategori Yang Diusulkan</label>
                                    <input type="text" name="category" class="form-control" placeholder="" required autocomplete="off">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <button class="btn btn-warning btn-block" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="toastSuggest" class="toast-box toast-bottom bg-success">
        <div class="in">
            <div class="text">
                Usulan sudah disubmit.
            </div>
        </div>
    </div>
</div>