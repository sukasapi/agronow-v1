<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="<?=base_url('learning/corporate_culture')?>" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full p-2">
        <div class="text-center">
            <p><b>HASIL EVALUASI MODUL 1<b></p>
            <div class="text-danger">
                <ion-icon name="close-circle-outline" style="font-size: 85px"></ion-icon>
                <p><b>ANDA BELUM EBRHASIL</b></p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th width="33%">Jumlah Soal</th>
                        <th width="33%">Benar</th>
                        <th width="33%">Salah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10</td>
                        <td>2</td>
                        <td>8</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center p-2">
            <a href="<?=base_url('home')?>" class="btn btn-block btn-success rounded mt-1">MODUL PEMBELAJARAN</a>
            <a href="<?=base_url('home')?>" class="btn btn-block btn-warning rounded mt-1">KEMBALI KE HOME</a>
        </div>
    </div>
</div>
