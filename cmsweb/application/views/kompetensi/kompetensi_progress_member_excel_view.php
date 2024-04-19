<table class="table table-sm table-striped table-bordered table-hover nowrap" id="kt_table">
    <?php $max_lv = $kompetensi['cr_komp_max_lv']; ?>
    <thead>
    <tr>
        <th class="text-center" rowspan="2">No</th>
        <th class="text-center" rowspan="2">Nama</th>
        <th class="text-center" rowspan="2">NIP</th>
        <th class="text-center" rowspan="2">Group</th>

        <?php for ($i=1 ; $i <= $max_lv ; $i++):  ?>
            <th class="text-center" colspan="2">Kompetensi Level <?= $i ?></th>
        <?php endfor; ?>

        <th class="text-center" rowspan="2">Selsai?</th>
        <th class="text-center" rowspan="2">Grade</th>
        <th class="text-center" rowspan="2">Grade OK?</th>
        <th class="text-center" rowspan="2">Tanggal Submit</th>
    </tr>
    <tr>
        <?php for ($i=1 ; $i <= $max_lv ; $i++):  ?>
            <th>Jumlah Soal</th>
            <th>Jawaban Benar</th>
        <?php endfor; ?>

    </tr>
    </thead>
    <tbody>
    <?php if ($member): ?>
        <?php
        $no=0; foreach ($member as $v):
            $result = json_decode($v['crm_step'],TRUE);
            //print_r($result);
            ?>
            <tr>
                <td><?php $no++; echo $no; ?></td>
                <td><?= $v['member_name'] ?></td>
                <td><?= $v['member_nip'] ?></td>
                <td><?= $v['group_name'] ?></td>

                <?php for ($i=1 ; $i <= $max_lv ; $i++):  ?>
                    <td><?= isset($result['level'][$i]) ? sizeof($result['level'][$i]['pertanyaan']) : '' ?></td>
                    <td>
                        <?php
                        $jawaban_benar = '';
                        if (isset($result['level'][$i])){
                            $jawaban_benar = 0;
                            foreach ($result['level'][$i]['pertanyaan'] as $v){
                                if ($v['jawaban'] == '1'){
                                    $jawaban_benar++;
                                }
                            }
                        }
                        echo $jawaban_benar;
                        ?>
                    </td>
                <?php endfor; ?>

                <td><?= isset($result['is_done_all']) ? ($result['is_done_all']==1 ? 'Ya' :'' ) : '' ?></td>
                <td><?= isset($result['hasil']) ? ($result['hasil'] ? $result['hasil'] : '') : '' ?></td>
                <td><?= isset($result['hasil']) ? ($result['hasil']==$max_lv ? 'Ya' : 'Tidak') : '' ?></td>
                <td><?= isset($result['tgl_submit']) ? ($result['tgl_submit'] ? date('d/m/Y H:i',strtotime($result['tgl_submit'])) : '') : '' ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
