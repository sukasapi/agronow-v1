<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('whatsnew')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="mb-4">
    <?php if ($data): ?>
    <div class="section full">
        <div class="section-title">Exchange Rate <?php echo $date ?></div>
        <div class="wide-block ml-2 mr-2 p-0">
            <div class="table-responsive">
                <table class="table p-0" style="font-size: x-small;">
                    <thead class="bg-success">
                        <tr>
                            <th class="p-1">Rates</th>
                            <th class="p-1">Unit</th>
                            <th class="p-1">Buy</th>
                            <th class="p-1">Sell</th>
                        </tr>
                    </thead>
                    <?php foreach($data as $key=>$val){?>
                        <tbody>
                        <tr>
                            <th class="p-1"><?php echo $key ?></th>
                            <th class="p-1"><?php echo $val['value'] ?></th>
                            <th class="p-1"><?php echo $val['buy'] ?></th>
                            <th class="p-1"><?php echo $val['sell'] ?></th>

                        </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>

        </div>
        <div class="m-2">
            <p>Sumber: www.bi.go.id</p>
        </div>
    </div>
    <?php else: ?>
    <div class="section full">
        <div class="d-flex justify-content-center mt-3">
            <p>Data tidak ditemukan</p>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- * App Capsule -->