<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">FORUM</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mt-2 mb-2 ml-2 mr-2">
        <div class="wide-block pt-2 pb-2">
            <form id="selectCategories" method="post" action="<?=base_url('learning/forum/select_category')?>">
                <div class="form-group mt-2 mb-3">
                    <div class="input-wrapper">
                        <div class="input-list">
                            <?php
                            $i = 1;
                            foreach ($options as $o): ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="categories[]" id="selectCat<?= $i; ?>" value="<?= $o['cat_id']; ?>">
                                    <label class="custom-control-label" for="selectCat<?= $i; ?>"><?= $o['cat_name']; ?></label>
                                </div>
                                <?php
                                $i++;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-warning btn-block" id="btnSubmit" type="submit" disabled>0/4</button>
                </div>
            </form>
        </div>
    </div>
</div>