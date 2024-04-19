<?php $this->load->view('application_header', ['header_title' => 'Learning Room', 'header_type' => 3]); ?>
<!-- Extra Header -->
<div class="extraHeader">
    <div class="form-group searchbox">
        <input type="text" class="form-control" placeholder="Search..." onkeyup="search('listview')" name="keyword" id="keyword">
        <i class="input-icon">
            <ion-icon name="search-outline"></ion-icon>
        </i>
    </div>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height">
    <h4 class="my-2 mx-3">Random Glosari</h4>
    <div class="carousel-single owl-carousel owl-theme mb-2">
        <?php foreach ($random as $i => $data) { ?>
            <div class="item card text-center p-3 m-0 h-100">
                <h3 class="mb-1"><?= $data['kamus_name']; ?></h3>
                <p class="m-0"><?= $data['kamus_desc']; ?></p>
            </div>
        <?php } ?>
    </div>
    <div class="section full">
        <ul class="listview search-result mb-2" id="listview">
            <?php foreach ($datas as $i => $data) { ?>
                <li class="p-2">
                    <div class="item p-0" style="width: 100%;">
                        <h3><?= $data['kamus_name']; ?></h3>
                        <div class="text-justify"><?= $data['kamus_desc']; ?></div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<!-- # App Capsule -->
