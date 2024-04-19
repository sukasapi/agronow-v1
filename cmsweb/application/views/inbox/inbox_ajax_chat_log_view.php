<div class="kt-scroll kt-scroll--pull" data-mobile-height="300">
    <div class="kt-chat__messages">

        <?php foreach ($chat as $v): ?>
            <?php if ($v['inbox_from']=='member'): ?>
                <div class="kt-chat__message">
                    <div class="kt-chat__user">
                        <!--<span class="kt-media kt-media--circle kt-media--sm">
                            <img src="assets/media/users/100_12.jpg" alt="image">
                        </span>-->
                        <a href="#" onclick="return false;" class="kt-chat__username"><?= $v['sender_name'] ?></span></a>
                        <span class="kt-chat__datetime"><?= date('d M Y, H:i',strtotime($v['inbox_create_date'])) ?></span>
                    </div>

                    <!--<a title="Remove" href="<?/*= site_url('inbox/remove_message/'.$v['inbox_id'].'/'.$inbox['inbox_id']); */?>" class="remove-soal btn text-danger" onclick="return confirm('Hapus pesan?')">
                        <i class="fa fa-trash-alt"></i>
                    </a>-->

                    <div class="kt-chat__text kt-bg-light-success">
                        <?= $v['inbox_desc'] ?>
                    </div>
                </div>

            <?php elseif ($v['inbox_from']=='admin'): ?>

                <div class="kt-chat__message kt-chat__message--right">
                    <div class="kt-chat__user">
                        <span class="kt-chat__datetime"><?= date('d M Y, H:i',strtotime($v['inbox_create_date'])) ?></span>
                        <a href="#" onclick="return false;" class="kt-chat__username"><?= $v['sender_name'] ?></span></a>
                        <!--<span class="kt-media kt-media--circle kt-media--sm">
                            <img src="assets/media/users/300_21.jpg" alt="image">
                        </span>-->
                    </div>
                    <div class="kt-chat__text kt-bg-light-brand">
                        <?= $v['inbox_desc'] ?>
                    </div>
                    <a title="Remove" href="<?= site_url('inbox/remove_message/'.$v['inbox_id'].'/'.$inbox['inbox_id']); ?>" class="remove-soal btn text-danger <?= has_access("inbox.delete",FALSE)?"":"d-none" ?>" onclick="return confirm('Hapus pesan?')"><i class="fa fa-trash-alt"></i></a>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>
</div>