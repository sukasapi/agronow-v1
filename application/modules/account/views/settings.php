<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">SETTING</div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="demoPage">
    <div class="listview-title mt-2 ml-3">
        <div class="row"><ion-icon name="notifications" class="mr-1 text-success" style="font-size: 25px;"></ion-icon><span class="my-auto">Notification</span></div>
    </div>
    <ul class="listview image-listview ml-5 mb-4" style="background: none;
    border-top: 1px solid transparent; border-bottom: 1px solid transparent;">
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        Push Notification
                        <div class="text-muted">Receive latest topic and announcement notification</div>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch4" checked />
                        <label class="custom-control-label" for="customSwitch4"></label>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        Sound
                        <div class="text-muted">Play sound on new notification</div>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch5" checked />
                        <label class="custom-control-label" for="customSwitch5"></label>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        Vibrate
                        <div class="text-muted">Vibrate on new notification</div>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch6" checked />
                        <label class="custom-control-label" for="customSwitch6"></label>
                    </div>
                </div>
            </div>
        </li>
    </ul>

    <ul class="listview image-listview pl-3">
        <li>
            <a href="<?= site_url('account/settings/contact_us'); ?>" class="item">
                <span class="iconedbox mr-3">
                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_contact.png')"></ion-icon>
                </span>
                <div class="in">
                    <div>CONTACT US</div>
                </div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('account/settings/faq'); ?>" class="item">
                <span class="iconedbox mr-3">
                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_faq.png')"></ion-icon>
                </span>
                <div class="in">
                    <div>FAQ</div>
                </div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('account/settings/privacy_policy'); ?>" class="item">
                <span class="iconedbox mr-3">
                    <ion-icon style="content: url('<?=PATH_ASSETS?>icon/account_ico_privacypolice.png')"></ion-icon>
                </span>
                <div class="in">
                    <div>PRIVACY POLICY</div>
                </div>
            </a>
        </li>
    </ul>
</div>
<!-- * App Capsule -->