<div class="modal fade dialogbox" id="popupKompetensiModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-1" style="border-bottom: 1px solid #E1E1E1;">
                <h3 class="modal-title">#</h3>
            </div>
            <div class="modal-body pt-1 pb-0 px-0 m-0">
                <div class="carousel-modal owl-carousel owl-theme" id="popupContent">
                    <?php foreach ($popup_kompetensi as $i => $pop) { ?>
                        <div class="item p-3" data-url="<?= $pop['url']; ?>">
                            <p class="px-1 pt-1 pb-0"><?= $pop['name'] ?></p>
                            <?php if ($pop['image']): ?>
                                <img src="<?= $pop['image']; ?>" class="mb-1">
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-dismiss="modal">CLOSE</a>
                    <a href="#" id="doTask" class="btn btn-primary">KERJAKAN</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let title_comp = [];
    let url_comp = [];

    <?php foreach ($popup_kompetensi as $i => $pop) { ?>
    title_comp[<?= $i; ?>] = '<?= strtoupper($pop['head']); ?>';
    url_comp[<?= $i; ?>] = '<?= $pop['url']; ?>';
    <?php } ?>

    owl = $('.carousel-modal').owlCarousel({
        loop: false,
        margin: 0,
        nav: false,
        items: 1,
        dots: true,
    });

    owl.on('changed.owl.carousel', function(event) {
        index = event.item.index;
        $('#popupKompetensiModal h3.modal-title').html(title_comp[index]);
        $('#popupKompetensiModal a#doTask').attr('href', url_comp[index]);
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

<?php if(count($popup_kompetensi) > 0): ?>
<script type="text/javascript">
    $(function(){
        setTimeout(function(){
            $('#popupKompetensiModal').modal('show');
        },1000)
    })
</script>
<?php endif; ?>