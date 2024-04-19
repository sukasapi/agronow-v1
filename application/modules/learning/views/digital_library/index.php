<?php $this->load->view('learning/app_header'); ?>

<!-- Extra Header -->
<div class="extraHeader">
    <form class="search-form">
        <div class="form-group searchbox">
            <input type="text" class="form-control" placeholder="Search..." name="search" value="<?= $search ?>">
            <i class="input-icon">
                <ion-icon name="search-outline"></ion-icon>
            </i>
        </div>
    </form>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height">
    <div class="section full mb-0">
        <div class="wide-block">
            <div class="section-title p-0 mt-1">Select Category</div>
            <div class="d-flex justify-content-center flex-wrap p-1">
                <?php foreach ($categories as $cat) { ?>
                    <?php
                    $image = PATH_ASSETS.'icon/main_icon.png';
                    if($cat['cat_image'] != ''){
                        $image = URL_MEDIA_IMAGE.$cat['cat_image'];
                    }
                    $btn = '';
                    $txt = 'text-dark';
                    if($category == $cat['cat_alias']){
                        $btn = 'border border-secondary';
//                        $txt = 'text-white';
                    }
                    ?>
                    <div class="text-center p-2 <?= $btn; ?>" style="max-width: 100px">
                        <a href="<?=base_url('learning/digital_library?category='.$cat['cat_alias'])?>">
                            <img src="<?=$image?>" alt="image" class="imaged w-50 rounded mb-1">
                            <p class="m-0 <?= $txt; ?>"><?php echo $cat['cat_name']; ?></p>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="section full">
        <div class="pt-0">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#ebook" role="tab">
                        E-BOOK (<?=count($datas['ebook'])?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#document" role="tab">
                        DOCUMENT (<?=count($datas['document'])?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#video" role="tab">
                        VIDEO (<?=count($datas['video'])?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#audio" role="tab">
                        AUDIO (<?=count($datas['audio'])?>)
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-2">
                <?php $this->load->view('learning/digital_library/tab', array('tab_id' => 'ebook', 'tab_name' => 'E-Book', 'tab_data' => $datas['ebook'], 'tab_class' => 'show active', 'recommended' => $recommended?$recommended[1]:[], 'latest' => $latest?$latest[1]:[], 'is_search' => $search?true:false)); ?>
                <?php $this->load->view('learning/digital_library/tab', array('tab_id' => 'document', 'tab_name' => 'Document', 'tab_data' => $datas['document'], 'tab_class' => '', 'recommended' => $recommended?$recommended[2]:[], 'latest' => $latest?$latest[2]:[], 'is_search' => $search?true:false)); ?>
                <?php $this->load->view('learning/digital_library/tab', array('tab_id' => 'video', 'tab_name' => 'Video', 'tab_data' => $datas['video'], 'tab_class' => '', 'recommended' => $recommended?$recommended[3]:[], 'latest' => $latest?$latest[3]:[], 'is_search' => $search?true:false)); ?>
                <?php $this->load->view('learning/digital_library/tab', array('tab_id' => 'audio', 'tab_name' => 'Audio', 'tab_data' => $datas['audio'], 'tab_class' => '', 'recommended' => $recommended?$recommended[4]:[], 'latest' => $latest?$latest[4]:[], 'is_search' => $search?true:false)); ?>
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->