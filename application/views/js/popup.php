<?php if(count($popup_list) > 0){ ?>
    <div class="modal fade dialogbox" id="popupModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid #E1E1E1;">
<!--                    <h3 class="modal-title">#</h3>-->
					<div class="d-flex justify-content-between">
						<a href="#" class="btn" data-dismiss="modal"><ion-icon name="close"></ion-icon> TUTUP</a>
						<a href="#" id="read" class="btn btn-success"><ion-icon name="book"></ion-icon> BACA</a>
					</div>
                </div>
                <div class="modal-body pt-1 pb-0 px-0 m-0">
                    <div class="carousel-modal owl-carousel owl-theme" id="popupContent">
                        <?php foreach ($popup_list as $i => $pop) { ?>
                            <div class="item" data-url="<?= $pop['url']; ?>">
                                <h3 class="text-double px-1 pt-1 pb-0"><?= $pop['name'] ?></h3>
                                <?php if ($pop['image']): ?>
                                <img src="<?= $pop['image']; ?>" class="mb-1">
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let title = [];
        let url = [];

        <?php foreach ($popup_list as $i => $pop): ?>
            title[<?= $i; ?>] = '<?= strtoupper($pop['head']); ?>';
            url[<?= $i; ?>] = '<?= $pop['url']; ?>';
        <?php endforeach; ?>

        owl = $('.carousel-modal').owlCarousel({
            loop: false,
            margin: 0,
            nav: false,
            items: 1,
            dots: true,
        });

        owl.on('changed.owl.carousel', function(event) {
            let index = event.item.index;
            $('#popupModal h3.modal-title').html(title[index]);
            $('#popupModal a#read').attr('href', url[index]);
        })
    </script>
    <style type="text/css">
        .owl-carousel .owl-stage-outer {
            padding-bottom: 0px; 
            margin-bottom: 0px; 
        }
        .owl-theme .owl-nav.disabled + .owl-dots{
            margin-top:0px;
        }
    </style>
<?php } ?>
<?php if(isset($show_reward)){ ?>
    <?php if($show_reward){ ?>
        <script type="text/javascript">
            $(function(){
                $('#rewardModal').on('hidden.bs.modal', function (e) {
                    $('#popupModal').modal('show');
                });
            })
        </script>
    <?php }elseif(count($popup_list) > 0){ ?>
        <script type="text/javascript">
            $(function(){
                setTimeout(function(){
                    $('#popupModal').modal('show');
                },1000)
            })
        </script>
    <?php } ?>
<?php }elseif(count($popup_list) > 0){ ?>
    <script type="text/javascript">
        $(function(){
            setTimeout(function(){
                $('#popupModal').modal('show');
            },1000)
        })
    </script>
<?php } ?>
