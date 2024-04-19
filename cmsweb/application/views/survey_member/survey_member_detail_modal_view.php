<!--begin: Datatable -->
<div class="table-responsive">

    <table class="table table-borderless">

        <tbody>
            <tr>
                <td>Tanggal</td>
                <td width="5px">:</td>
                <td><?= parseDateShortReadable($survey_member['sm_create_date']) ?>, <?= parseTimeReadable($survey_member['sm_create_date']) ?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td width="5px">:</td>
                <td><?= $survey_member['member_name'] ?></td>
            </tr>
            <tr>
                <td>Group</td>
                <td width="5px">:</td>
                <td><?= $survey_member['group_name'] ?></td>
            </tr>

        </tbody>

    </table>

    <div class="col-12">
        <hr>
    </div>


    <table class="table table-borderless" style="width: 500px !important;">

        <tbody>
        <?php
        $json_raw = preg_replace('/[[:cntrl:]]/', '', $survey['survey_data']);
        $question = json_decode($json_raw,TRUE);

        foreach ($question as $k => $v):
            ?>

            <tr>
                <td width="50%"><?= $v['Question'] ?></td>
                <td width="5px">:</td>
                <td width="50%">
                    <?php
                    $json_sm_raw = preg_replace('/[[:cntrl:]]/', '', $survey_member['sm_data']);
                    $sm_data = json_decode($json_sm_raw,TRUE);

                    $answer = $sm_data['Q'.$k];
                    if ($v['Model']=='multiple-choice'){

                        if ($v['Type']=='text'){
                            echo $v['ChoiceText'][$answer];
                        } else if ($v['Type']=='image'){
                            echo '<img src="'.URL_MEDIA_IMAGE.$v['ChoiceImage'][$answer].'" width="96px">';
                        } else if ($v['Type']=='text-image'){
                            echo '<img src="'.URL_MEDIA_IMAGE.$v['ChoiceImage'][$answer].'" width="96px">';
                            echo '<br>';
                            echo $v['ChoiceText'][$answer];
                        }

                    }else{
                        echo $answer;
                    }
                    ?>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>
<!--end: Datatable -->
