<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="<?=base_url('learning/expert_directory/list_chat/'.$data['expert_member']['em_id'])?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
<!--        <img src="--><?//= $data['chat_starter']['member_image']; ?><!--" alt="avatar" class="imaged rounded mr-2" style="width:45px">-->
        <div><?= $data['expert_name']; ?></div>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule" class="chat-container">
    <div class="message-divider">
        <?= $this->function_api->convert_datetime($data['expert_create_date'], 'l, M d'); ?>
    </div>
    <div class="message-item<?= $data['expert_member']['is_current']?' user':''; ?>">
        <div class="content">
            <div class="bubble" style="background: #faf9cb; color:black">
                <div class="d-flex align-items-center">
                    <div>
                        <ion-icon name="person" style="font-size:20px"></ion-icon>
                    </div>
                    <div class="ml-1">
                        <b>Selamat Datang di Konsultasi Expert</b>
                    </div>
                </div>
                <hr class="mt-1 mb-1" style="border: 1px solid black; width: 100%">
                Hello, <b><?= $data['chat_starter']['member_name']; ?></b>. kami akan menjawab pertanyaan kamu disini. Silahkan tuliskan apa yang ingin kamu tanyakan
            </div>
            <div class="footer"><?= date('H:m', strtotime($data['expert_create_date'])); ?></div>
        </div>
    </div>
</div>
<!-- * App Capsule -->