<?php if(strlen($this->footer) > 0):
    $this->load->view('footer/'.$this->footer);
?>
<?php else: ?>
    <!-- App Bottom Menu -->
    <div class="appBottomMenu d-print-none <?=($this->session->userdata('kategori_klien')=="classroom_only")?'d-none':''?>">
        <a id="mPortal" href="<?=base_url('portal')?>" class="item <?=$this->menu=='portal'?'active':''?>">
            <div class="col">
                <ion-icon class="mt-0 mb-0" style="content: url('<?=PATH_ASSETS?>icon/menu_btn_portal_<?=$this->menu=='portal'?'on':'off'?>.png'); font-size: 35px !important;"></ion-icon>
                <strong>Portal</strong>
            </div>
        </a>
        <a id="mLearning" href="<?=base_url('learning')?>" class="item <?=$this->menu=='learning'?'active':''?>">
            <div class="col">
                <ion-icon class="mt-0 mb-0" style="content: url('<?=PATH_ASSETS?>icon/menu_btn_learningroom_<?=$this->menu=='learning'?'on':'off'?>.png'); font-size: 35px !important;"></ion-icon>
                <strong>Learning Room</strong>
            </div>
        </a>
        <a id="mHome" href="<?=base_url('home')?>" class="item <?=$this->menu=='home'?'active':''?>">
            <div class="col">
                <div class="action-button large bg-white btn btn-outline-secondary p-0">
                    <div class="col p-0 m-0">
                        <span class="iconedbox">
                            <ion-icon class="mt-0 mb-0" style="content: url('<?=PATH_ASSETS?>icon/menu_btn_portal_home_<?=$this->menu=='home'?'on':'off'?>.png'); font-size: 35px !important;"></ion-icon>
                        </span>
                        <strong>Home</strong>
                    </div>
                </div>
            </div>
        </a>
        <a id="mWhatsNew" href="<?=base_url('whatsnew')?>" class="item <?=$this->menu=='whatsnew'?'active':''?>">
            <div class="col">
                <ion-icon class="mt-0 mb-0" style="content: url('<?=PATH_ASSETS?>icon/menu_btn_whatsnew_<?=$this->menu=='whatsnew'?'on':'off'?>.png'); font-size: 35px !important;"></ion-icon>
                <strong>What's New</strong>
            </div>
        </a>
        <a id="mAccount" href="<?=base_url('account')?>" class="item <?=$this->menu=='account'?'active':''?>">
            <div class="col">
                <ion-icon class="mt-0 mb-0" style="content: url('<?=PATH_ASSETS?>icon/menu_btn_account_<?=$this->menu=='account'?'on':'off'?>.png'); font-size: 35px !important;"></ion-icon>
                <strong>Account</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->
<?php endif ?>

    <!-- iOS Add to Home Action Sheet -->
    <div class="modal inset fade action-sheet ios-add-to-home" id="ios-add-to-home-screen" tabindex="-1"
    role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Home Screen</h5>
                    <a href="javascript:;" class="close-button" data-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content text-center">
                        <div class="mb-1"><img src="<?=PATH_ASSETS?>icon/main_icon.png" alt="image" class="imaged w48">
                        </div>
                        <h4>Agronow</h4>
                        <div>
                            Install Agronow on your iPhone's home screen.
                        </div>
                        <div>
                            Tap <ion-icon name="share-outline"></ion-icon> and Add to homescreen.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * iOS Add to Home Action Sheet -->
    <!-- Android Add to Home Action Sheet -->
    <div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1"
        role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Home Screen</h5>
                    <a href="javascript:;" class="close-button" data-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content text-center">
                        <div class="mb-1"><img src="<?=PATH_ASSETS?>icon/main_icon.png" alt="image" class="imaged w48">
                        </div>
                        <h4>Agronow</h4>
                        <div>
                            Install Agronow on your Android's home screen.
                        </div>
                        <div>
                            Tap <ion-icon name="ellipsis-vertical"></ion-icon> and Add to homescreen.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Android Add to Home Action Sheet -->

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
   <!-- <script src="<?=PATH_ASSETS?>js/lib/jquery-3.4.1.min.js"></script>-->
    <!-- Bootstrap-->
    <script src="<?=PATH_ASSETS?>js/lib/popper.min.js"></script>
    <script src="<?=PATH_ASSETS?>js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="<?=PATH_ASSETS?>js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="<?=PATH_ASSETS?>js/plugins/jquery-circle-progress/circle-progress.min.js"></script>    
    <!--- Plugins -->
    <script src="<?=PATH_ASSETS?>plugins/datatables/datatables.bundle.js"></script>
	<script src="<?=PATH_ASSETS;?>plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?=PATH_ASSETS;?>plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?=PATH_ASSETS;?>plugins/jspdf/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.1/html2pdf.bundle.min.js" integrity="sha512-vDKWohFHe2vkVWXHp3tKvIxxXg0pJxeid5eo+UjdjME3DBFBn2F8yWOE0XmiFcFbXxrEOR1JriWEno5Ckpn15A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?=PATH_ASSETS?>plugins/gauge/gauge.min.js"></script>
    <!-- Base Js File -->
    <script src="<?=PATH_ASSETS?>js/base.js"></script>
    <script src="<?=PATH_ASSETS?>js/custom.js?v=0.1"></script>
    <script src="<?=PATH_ASSETS?>js/bottom-menu.js"></script>
    <script src="<?=PATH_ASSETS?>js/project_assignment.js"></script>
	

   

    <script>
        AddtoHome(3000, 'once'); 
        $('.carousel').carousel();
    </script>

    <?php
        foreach($this->customjs as $c){
            $this->load->view('js/'.$c);
        }
    ?>
</body>

</html>