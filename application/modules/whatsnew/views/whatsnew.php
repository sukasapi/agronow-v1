<?php $this->load->view('application_header', ['header_title' => 'What\'s New', 'header_type' => 2]); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-3">
            <div class="card">
                <a href="<?=base_url('whatsnew/news');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_news.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>News</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><?php echo $data['news'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card">
                <a href="<?=base_url('whatsnew/article');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_article.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>Article</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo $data['article'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card">
                <a href="<?=base_url('whatsnew/commodity');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_comodity.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>Commodity</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo $data['commodity'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card">
                <a href="<?=base_url('whatsnew/exchange_rate');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_exchrate.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>Exchange Rate</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo $data['exchange_rate'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card">
                <a href="<?=base_url('whatsnew/announcement');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_announ.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>Announcement</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo $data['announcement'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card">
                <a href="<?=base_url('whatsnew/ceo_note');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_ceonote.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>CEO Note</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo $data['ceo_note'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br/>
            <div class="card">
                <a href="<?=base_url('whatsnew/bod_share');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_ceonote.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>BOD Share</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo $data['bod_share'] ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <br>
            <div class="card">
                <a href="<?=base_url('whatsnew/popup');?>">
                    <div class="d-flex flex-row p-2">
                        <div class="d-flex align-items-center">
                            <img src="<?=PATH_ASSETS?>icon/whatsnew_ico_announ.png" alt="image" class="imaged w76" style="object-fit: fill;">
                        </div>
                        <div class="d-flex flex-column ml-2">
                            <div>
                                <p class="mb-1" style="color: black; font-size: larger;"><b>Popup List</b></p>
                            </div>
                            <div>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><b><?php echo isset($data['pup_up'])?$data['pup_up']:'' ?></b></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>