<?php $this->load->view('learning/app_header'); ?>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <?php if(count($datas) > 0){ ?>
            <ul class="listview image-listview media search-result mb-2">
                <?php foreach ($datas as $i => $data) { ?>
                    <li>
                        <div class="m-2 row">
                            <div class="col-7 p-0">
                                <h4 class="mb-0 text-double"><?= $data['cr_name'] ?></h4>
                                <div class="mb-0 text-muted">
                                    <span><?=$this->function_api->date_indo($data['cr_date_start']);?> - <?=$this->function_api->date_indo($data['cr_date_end']);?></span>
                                </div>
                            </div>
                            <div class="col-5 mt-0">
                                <button type="button" class="btn btn-warning p-1 form-control text-center" data-toggle="modal" data-target="#buyModal" data-id="<?= $data['cr_id'] ?>" data-price="<?= $data['cr_price']; ?>" id="buy_button">Beli</button>
                                <div class="d-flex justify-content-end">
                                    <div class="pr-0 pl-0">
                                        <span class="iconedbox iconedbox-sm text-danger" style="width: 60px;">
                                            <ion-icon name="wallet" role="img" class="md hydrated" aria-label="wallet"></ion-icon>
                                            <span style="font-size: 15px;">&nbsp;<?= $this->function_api->number($data['cr_price']); ?></span>
                                        </span>
                                    </div>
                                    <div class="pr-0 pl-0">
                                        <span class="iconedbox iconedbox-sm text-primary" style="width: 60px;">
                                            <ion-icon name="people" role="img" class="md hydrated" aria-label="people"></ion-icon>
                                            <span style="font-size: 15px;">&nbsp;<?= $this->function_api->number($data['cr_sold']); ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php }else{ ?>
            <div class="m-2 text-center">Tidak ada Available Classroom yang dapat dibeli.</div>
        <?php } ?>
    </div>
</div>
<div class="modal fade dialogbox" id="buyModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-primary">
                <ion-icon name="cart" role="img" class="md hydrated" aria-label="cart circle"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Beli Classroom?</h5>
            </div>
            <div class="modal-body">
                Harga : <b class="text-danger" id="price"></b><br/>
                (Saldo Anda : <b class="text-primary"><ion-icon name="wallet" role="img" aria-label="wallet"></ion-icon>&nbsp;<?= $this->function_api->number($saldo); ?></b>)
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <button type="button" class="btn btn-success" id="buy_confirm">
                        <ion-icon name="checkmark-circle" role="img" class="md hydrated" aria-label="checkmark circle"></ion-icon>BELI
                    </button>
                    <a href="#" class="btn bg-danger" data-dismiss="modal">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        BATAL
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade dialogbox" id="successModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-success">
                <ion-icon name="checkmark-circle" role="img" class="md hydrated" aria-label="checkmark circle"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Pembelian Classroom Berhasil!</h5>
            </div>
            <div class="modal-body">
                Silahkan buka menu "My Classroom" untuk melihat daftar classroom anda.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-dismiss="modal">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade dialogbox" id="failModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-danger">
                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">Pembelian Classroom Gagal!</h5>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-dismiss="modal">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</div>