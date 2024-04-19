<style type="text/css">
    div.result{
        background-color: <?= ($type == 'passed') ? '#0BCD0F' : '#CC1417'; ?>
    }
    div.result img{
        width: 80px;
    }
    div.result h2{
        color: #FFFFFF;
    }
</style>

<h3 class="text-center m-2"><?= $result_title; ?></h3>
<div class="text-center p-2 result">
    <?php if ($data['cr_show_nilai']): ?>
        <?php if($type == 'passed'): ?>
            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
            <h2>LULUS</h2>
        <?php else: ?>
            <img src="<?= PATH_ASSETS ?>icon/class_room_icon_gagal.png" class="mb-1">
            <h2>ANDA BELUM BERHASIL</h2>
        <?php endif; ?>
    <?php else: ?>
        <img src="<?= PATH_ASSETS ?>icon/class_room_icon_lulus.png" class="mb-1">
        <h2>SELESAI</h2>
    <?php endif; ?>
</div>
<div class="text-left"><small>&nbsp;uid:&nbsp;<?=$data['crm_id'].'.'.base64_encode($data['crm_id'])?></small></div>
<?php if ($data['cr_show_nilai']): ?>
<div class="m-1 mt-2">
    <table class="table table-bordered text-center table-sm">
        <thead class="thead-light" style="background-color: #e5e5e5;">
            <?php foreach ($result_head as $i => $value) { ?>
                <td><?= $value ?></td>
            <?php } ?>
        </thead>
        <tbody>
            <tr>
                <?php foreach ($result_body as $i => $value) { ?>
                    <td><?= $value ?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php foreach ($buttons as $btn) { ?>
    <div class="m-1 mt-2">
        <a href="<?= $btn['url']; ?>" type="button" class="btn btn-block btn-lg <?= $btn['btn_color']; ?>"><?= $btn['title']; ?></a>
    </div>
<?php } ?>