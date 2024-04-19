<?php $this->load->view('learning/app_header'); ?>

<?php $default_image = PATH_ASSETS.'icon/main_icon.png'; ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if($data['content_type_id'] == 4){ ?>
            <!-- audio -->
            <?php
                // - basic audio without visualizer -
                // $audio = URL_MEDIA_AUDIO.$data['media']['media_value'];

                // - audio with visualizer
                $media = $data['media'];
                $targetFilePath = getcwd().'/'.MEDIA_AUDIO_PATH.basename($media['media_value']);
                if (!is_file($targetFilePath)) {
                    file_put_contents($targetFilePath, file_get_contents(URL_MEDIA_AUDIO.$media['media_value']));
                }

                $audio = base_url().MEDIA_AUDIO_PATH.'/'.$media['media_value'];
            ?>
            <div class="wide-block text-center py-3 px-2">
                <div class="p-0 m-0 audio-container">
                    <canvas id="audio_canvas" height="250" style="width: 100%;"></canvas>
                </div>
                <audio id="audio" controls controlsList="nodownload" style="width: 100%;" src="<?= $audio; ?>">
                    <!-- <source src="" type="audio/mpeg" /> -->
                    Your browser does not support the audio element.
                </audio>
            </div>
        <?php }elseif($data['content_type_id'] == 3){ ?>
            <!-- video -->
            <?php if (isset($data['media']['media_value'])): ?>
                <?php if(strpos($data['media']['media_value'], 'youtube.com') === false){ ?>
                    <video controls controlsList="nodownload" class="card-img-top" preload="none">
                        <source src="<?php echo URL_MEDIA_VIDEO.$data['media']['media_value']; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php }else{ ?>
                    <!-- from youtube -->
                    <iframe src="<?php echo $data['media']['media_value']; ?>" style="width: 100%;height: 60vh;" frameborder="0" allowfullscreen></iframe>
                <?php } ?>
            <?php else:
                if (isset($data['content_cover']['media_value'])):
                    $image = URL_MEDIA_IMAGE.$data['content_cover']['media_value'];
                ?>
                    <img src="<?=$image?>" style="width: 100%;" alt="image">
                <?php endif; ?>
            <?php endif; ?>
        <?php }else{ ?>
            <?php 
                $image = PATH_ASSETS.'icon/main_icon.png';
                if(@$data['content_cover']['media_value'] != ''){
                    $image = URL_MEDIA_IMAGE.$data['content_cover']['media_value'];
                }
            ?>
            <img src="<?=$image?>" style="width: 100%;" alt="image">
        <?php } ?>
        <div class="wide-block p-2">
            <h2><?= $data['content_name'] ?></h2>
            <!-- <p class="mb-0">Penulis: Aan Rukmana</p> -->
            <div class="d-flex justify-content-start">
                <div class="align-self-center">
                    <?= $this->function_api->date_indo($data['content_publish_date'], 'datetime'); ?>
                    <!-- 23 Oktober 2019 16:00:00 -->
                </div>
                <div class="align-self-center ml-2 text-primary">
                    <span class="iconedbox iconedbox-sm" style="width: 60px;">
                        <ion-icon name="eye"></ion-icon>
                        <span style="font-size: 15px;">&nbsp;<?= $this->function_api->number($data['content_hits']); ?></span>
                    </span>
                </div>
            </div>
            <p class="mt-2">
                <?= $data['content_desc']; ?>
            </p>
            <?php if(count($data['media']) > 0 && $data['content_type_id'] != 3 && $data['content_type_id'] != 4){ ?>
                <?php 
                    $media = $data['media'];
                    $media_preview = base_url('learning');
                    if($data['content_alias'] != ''){
                        $media_preview = base_url('learning/digital_library/preview/'.$data['content_alias']);
                    }

                    $media_download = '';
                    if($media['media_value'] != ''){
                        $media_download = URL_MEDIA_DOCUMENT.$media['media_value'];
                    }
                ?>
                <div class="card bg-light mb-2">
                    <div class="card-body p-2">
                        <p class="card-text text-double"><?= $media['media_name'] ?> (<?= $media['media_size'] ?>)</p>
                        <div class="d-flex">
                            <div class="mr-auto">
                                <a href="<?= $media_preview ?>" class="btn btn-warning">Preview</a>
                            </div>
                            <?php if($data['content_type_id'] != '' &&  $data['content_type_id'] != 1){ ?>
                                <div class="p-1">
                                    <a href="<?= $media_download ?>" class="btn btn-success" download>Download</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="section full mt-2">
        <div class="section-title"><?= $title_terkait; ?></div>
        <div class="carousel-<?=$data['content_type_id']==2||$data['content_type_id']==4||$data['content_type_id']==1?'document-audio':($data['content_type_id']==3?'video':'multiple')?> owl-carousel owl-theme">
            <?php foreach ($terkait as $i => $tk) { ?>
                <?php 
                    $image = $default_image;
                    if(@$tk['image'] != ''){
                        if(strpos($tk['image'], 'img.youtube') !== false){
                            $image = $tk['image'];
                        }else{
                            $image = URL_MEDIA_IMAGE.$tk['image'];
                        }
                    }
                ?>
                <div class="item">
                    <a href="<?= base_url('learning/digital_library/detail/'.$tk['content_alias']); ?>">
                        <?php 
                            $height = 40;
                            if($tk['content_type_id'] == 3){
                                $height = 20;
                            }
                        ?>
                        <img src="<?= $image; ?>" alt="alt" class="imaged w-100" style="object-fit: cover; width: 100%; height: <?= $height ?>vh;">
                        <p class="text-double text-center" style="color: black;"><?= $tk['content_name'] ?></p>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->