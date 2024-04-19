<?php
switch ($tab_id) {
    case 'document':
        $default_image = PATH_ASSETS.'icon/dglib_ico_doc.png';
        break;
    case 'audio':
        $default_image = PATH_ASSETS.'icon/dglib_ico_audio.png';
        break;
    default:
        $default_image = PATH_ASSETS.'icon/main_icon.png';
        break;
}
?>
<div class="tab-pane fade <?php echo $tab_class; ?>" id="<?php echo $tab_id; ?>" role="tabpanel">
    <div class="section full mb-1">
<!--        <div class="section-title">--><?//= ($is_search?'Search Result':'Latest '.$tab_name); ?><!--</div>-->
        <?php if ($tab_data): ?>
        <div class="carousel-<?=$tab_id=='document'||$tab_id=='audio'||$tab_id=='ebook'?'document-audio':($tab_id=='video'?'video':'multiple')?> owl-carousel owl-theme card-group">
            <?php foreach ($tab_data as $data) { ?>
                <?php 
                    $image = $default_image;
                    if(@$data['content_cover']['media_value'] != ''){
                        $image = URL_MEDIA_IMAGE.$data['content_cover']['media_value'];
                    }

//                    if($tab_id == 'video'){
//                        $u = explode('embed', $data['media']['media_value']);
//                        if(isset($u[1])){
//                            $video_id = @str_replace('/', '', $u[1]);
//                            $image = 'https://img.youtube.com/vi/'.$video_id.'/0.jpg';
//                        }
//                    }
                ?>
                <div class="item">
                    <?php if($tab_id == 'video'){ ?>
                        <a href="<?=base_url('learning/digital_library/detail/'.$data['content_alias'])?>"  title="<?php echo $data['content_name'] ?>">
                            <div class="card">
                                <img src="<?= $image ?>" style="object-fit: cover;width: 100%; height: 20vh;">
                                <div class="card-body p-1">
                                    <h4 class="mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $data['content_name'] ?></h4>
                                    <small style="color: #4F5050;"><?= $this->function_api->date_indo($data['content_publish_date'], 'datetime'); ?></small>
                                    <!-- <p class="card-text">09 Nov 2019 13:00</p> -->
                                </div>
                            </div>
                        </a>
					<?php }else if($tab_id == 'document' || $tab_id == 'audio'){ ?>
						<a href="<?=base_url('learning/digital_library/detail/'.$data['content_alias'])?>"  title="<?php echo $data['content_name'] ?>">
							<div class="text-center">
								<img src="<?=$image?>" alt="alt" class="imaged w100" style="display: inline">
							</div>
							<p class="text-double text-center" style="color: black;"><?php echo $data['content_name'] ?></p>
							<!-- <p><?= $image ?></p> -->
						</a>
                    <?php }else{ ?>
                        <?php $style = ''; if($tab_id == 'ebook') $style = 'object-fit: contain;width: 100%; height: 250px; background-color: #EEE;'; ?>
                        <a href="<?=base_url('learning/digital_library/detail/'.$data['content_alias'])?>"  title="<?php echo $data['content_name'] ?>">
                            <img src="<?=$image?>" alt="alt" class="imaged w-100" style="<?= $style ?>">
                            <p class="text-double text-center" style="color: black;"><?php echo $data['content_name'] ?></p>
                            <!-- <p><?= $image ?></p> -->
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <?php else: ?>
            <div class="section-title font-weight-light">Data tidak ditemukan</div>
        <?php endif; ?>
    </div>
    <div class="section full">
        <div class="section-title">Recommended</div>

        <div class="carousel-<?=$tab_id=='document'||$tab_id=='audio'||$tab_id=='ebook'?'document-audio':($tab_id=='video'?'video':'multiple')?> owl-carousel owl-theme mb-2">
            <?php foreach ($recommended as $rmd) { ?>
                <?php 
                    $image = $default_image;
                    if(@$rmd['image']['media_image_link'] != ''){
                        $image = $rmd['image']['media_image_link'];
                    }

//                    if(@$rmd['video']['media_value'] != '' && $tab_id == 'video'){
//                        $u = explode('embed', $rmd['video']['media_value']);
//                        if(isset($u[1])){
//                            $video_id = @str_replace('/', '', $u[1]);
//                            $image = 'https://img.youtube.com/vi/'.$video_id.'/0.jpg';
//                        }
//                    }
                ?>
                <?php if($tab_id == 'video'){ ?>
                    <a href="<?=base_url('learning/digital_library/detail/'.$rmd['content_alias'])?>"  title="<?php echo $rmd['content_name'] ?>">
                        <div class="card">
                            <img src="<?= $image ?>" style="object-fit: cover;width: 100%; height: 20vh;">
                            <div class="card-body p-1">
                                <h4 class="mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $rmd['content_name'] ?></h4>
                                <small style="color: #4F5050;"><?= $this->function_api->date_indo($rmd['content_publish_date'], 'datetime'); ?></small>
                            </div>
                        </div>
                    </a>
				<?php }else if($tab_id == 'document' || $tab_id == 'audio'){ ?>
					<a href="<?=base_url('learning/digital_library/detail/'.$rmd['content_alias'])?>"  title="<?= $rmd['content_name'] ?>">
						<div class="text-center">
							<img src="<?=$image?>" alt="alt" class="imaged w100" style="display: inline">
						</div>
						<p class="text-double text-center" style="color: black;"><?php echo $rmd['content_name'] ?></p>
					</a>
                <?php }else{ ?>
                    <?php $style = ''; if($tab_id == 'ebook') $style = 'object-fit: contain;width: 100%; height: 250px; background-color: #EEE;'; ?>
                    <a href="<?= base_url('learning/digital_library/detail/'.$rmd['content_alias']) ?>">
                        <div class="item">
                            <img src="<?=$image?>" alt="alt" class="imaged w-100 mb-1" style="<?= $style ?>">
                            <p class="text-double text-center" style="color: black;"><?= $rmd['content_name']; ?></p>
                        </div>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="divider pb-2 mb-1"></div>
    <div class="section full mb-3">
        <div class="section-title">Last Opened</div>
        <?php if ($latest): ?>
        <ul class="listview image-listview media">
            <?php foreach ($latest as $lt) { ?>
                <?php
                    $image = $default_image;
                    if(@$lt['image']['media_image_link'] != ''){
                        $image = $lt['image']['media_image_link'];
                    }

//                    if(@$lt['video']['media_value'] != '' && $tab_id == 'video'){
//                        $u = explode('embed', $lt['video']['media_value']);
//                        if(isset($u[1])){
//                            $video_id = @str_replace('/', '', $u[1]);
//                            $image = 'https://img.youtube.com/vi/'.$video_id.'/0.jpg';
//                        }
//                    }
                ?>
                <li>
                    <a href="<?= base_url('learning/digital_library/detail/'.$lt['content_alias']) ?>">
                        <div class="item">
                            <div class="imageWrapper">
                                <img src="<?=$image?>" alt="image" class="imaged w64">
                            </div>
                            <div class="in">
                                <div>
                                    <p class="mb-1 text-double" style="color: black; font-size: larger;"><b><?= $lt['content_name'] ?></b></p>
                                    <p class="mb-1 text-muted"><?= $lt['cat_name'] ?></p>
                                    <p class="mb-0" style="color: gray; font-size: small;"><?= $this->function_api->date_indo($lt['content_hits_date'], 'datetime'); ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <?php else: ?>
        <div class="section-title font-weight-light">Data tidak ditemukan</div>
        <?php endif; ?>
    </div>
</div>