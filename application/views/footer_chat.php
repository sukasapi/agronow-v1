    <!-- chat footer -->
    <div class="chatFooter">
        <form action="<?=base_url('account/inbox/add')?>" autocomplete="off" class="needs-validation" novalidate method="post" accept-charset="utf-8">
            <a href="javascript:;" class="btn btn-icon btn-secondary rounded" data-toggle="modal" data-target="#addActionSheet">
                <ion-icon name="add"></ion-icon>
            </a>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="hidden" name="parent_id" value="<?= $data['parent_id']; ?>">
                    <input type="hidden" name="inbox_title" value="<?= $data['inbox_title']; ?>">
                    <input type="text" class="form-control" name="inbox_desc" placeholder="Type a message...">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>
            <button type="submit" class="btn btn-icon btn-primary rounded">
                <ion-icon name="send"></ion-icon>
            </button>
        </form>
    </div>
    <!-- * chat footer -->

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
    <script src="<?=PATH_ASSETS?>js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="<?=PATH_ASSETS?>js/lib/popper.min.js"></script>
    <script src="<?=PATH_ASSETS?>js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="<?=PATH_ASSETS?>js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="<?=PATH_ASSETS?>js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <!-- Base Js File -->
    <script src="<?=PATH_ASSETS?>js/base.js"></script>
    <script src="<?=PATH_ASSETS?>js/custom.js"></script>


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