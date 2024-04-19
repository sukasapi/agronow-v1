<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    table.prelearningtable{
        font-size: 14px;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="p-3">
            <?php
			$juml = count($row);
			if($juml<=0) {
				echo '<div class="text-center">Data tidak ditemukan.</div>';
			} else {
				foreach($row as $key => $val) {
					// $this->member_model->recData['memberId']= $val['id_dinilai'];
					// $detailDinilai = $this->member_model->select_member("byId");
			?>
                    <div class="card">
                        <div class="card-body">
                            <h4 style="font-size: 14px;"><?=$val['cr_name']?></h4>
                            <small>Tanggal Evaluasi: <?=$this->function_api->date_indo($val['tanggal_mulai'],'datetime');?> sd <?=$this->function_api->date_indo($val['tanggal_selesai'],'datetime');?></small>
                            <table class="table table-bordered mt-2 prelearningtable">
                                <tbody>
                                    <tr>
                                        <td><?=ucwords($val['status_penilai'])?> dari <?=$val['member_name']?></td>
                                        <td class="text-center" style="width:10%">
											<?=$val['progress'].'%'?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="<?=base_url('learning/class_room/evaluasi_lv3_detail/'.$val['id_pairing'])?>" class="btn btn-primary mt-2">Detail Evaluasi</a>
                        </div>
                    </div>
                    <br/>
            <?php
				}
			}
            ?>
        </div>
    </div>
</div>
<!-- * App Capsule -->