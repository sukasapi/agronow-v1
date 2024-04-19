<?php $this->load->view('learning/app_header'); ?>
<?php $surveyData = json_decode($survey['survey_data'],true); ?>

<style type="text/css">
    .alert p{
        margin-bottom:5px;
    }
    table.que td{
        vertical-align: top;
    }
</style>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="m-2">
            <h3 class="text-center"><?=$survey['survey_name'];?></h3>
            <div class="alert alert-info mb-2"><?=$survey['survey_desc'];?></div>
            <form name="addSurveyMember" class="form-horizontal" method="post" action="<?= base_url('home/survey/save?surveyId='.$surveyId); ?>">
                <?php for($i=0;$i<count($surveyData);$i++){?>
                    <div class="card mb-2">
                        <div class="card-header p-1"><?=$surveyData[$i]['Question'];?></div>
                        <div class="card-body p-1" style="background-color:#f5f5f5;">
                            <?php if($surveyData[$i]['Model']=="essay"){ ?>
                                <textarea class="form-control" name="answer[<?=$i;?>]" rows="4" required></textarea>
                            <?php }else{ ?>
                                <table class="que" style="width:100%;">
                                    <?php if($surveyData[$i]['Type'] == "text"){ ?>
                                        <tr>
                                            <td width="5%"><input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>0" value="0" required></td>
                                            <td style="padding-left:5px;"><label for="answer<?=$i;?>0"><?=$surveyData[$i]['ChoiceText'][0];?></label></td>
                                        </tr>
                                        <tr>
                                            <td width="5%"><input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>1" value="1" required></td>
                                            <td style="padding-left:5px;"><label for="answer<?=$i;?>1"><?=$surveyData[$i]['ChoiceText'][1];?></label></td>
                                        </tr>
                                        <tr>
                                            <td width="5%"><input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>2" value="2" required></td>
                                            <td style="padding-left:5px;"><label for="answer<?=$i;?>2"><?=$surveyData[$i]['ChoiceText'][2];?></label></td>
                                        </tr>
                                        <tr>
                                            <td width="5%"><input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>3" value="3" required></td>
                                            <td style="padding-left:5px;"><label for="answer<?=$i;?>3"><?=$surveyData[$i]['ChoiceText'][3];?></label></td>
                                        </tr>
                                    <?php }elseif($surveyData[$i]['Type']=="image"){?>
                                        <tr align="center">
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>0" value="0" required> <br/><br/>
                                                <label for="answer<?=$i;?>0">
                                                    <img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][0];?>" style="width:70px;height:70px;" />
                                                </label>
                                            </td>
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>1" value="1" required> <br /><br />
                                                <label for="answer<?=$i;?>1"><img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][1];?>" style="width:70px;height:70px;" /></label>
                                            </td>
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>2" value="2" required> <br /><br />
                                                <label for="answer<?=$i;?>2"><img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][2];?>" style="width:70px;height:70px;" /></label>
                                            </td>
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]"  id="answer<?=$i;?>3" value="3" required> <br /><br />
                                                <label for="answer<?=$i;?>3"><img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][3];?>" style="width:70px;height:70px;" /></label>
                                            </td>
                                        </tr>
                                    <?php }else{?>
                                        <tr align="center">
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>0" value="0" required> <br /><br />
                                                <label for="answer<?=$i;?>0"><img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][0];?>" style="width:70px;height:70px; margin-bottom:10px;" /><br />
                                                    <?=$surveyData[$i]['ChoiceText'][0];?>
                                                </label>
                                            </td>
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>1" value="1" required> <br /><br />
                                                <label for="answer<?=$i;?>1">
                                                    <img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][1];?>" style="width:70px;height:70px; margin-bottom:10px;" /><br />
                                                    <?=$surveyData[$i]['ChoiceText'][1];?>
                                                </label>
                                            </td>
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>2" value="2" required> <br /><br />
                                                <label for="answer<?=$i;?>2">
                                                    <img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][2];?>" style="width:70px;height:70px; margin-bottom:10px;" /><br />
                                                    <?=$surveyData[$i]['ChoiceText'][2];?>
                                                </label>
                                            </td>
                                            <td width="25%">
                                                <input type="radio" name="answer[<?=$i;?>]" id="answer<?=$i;?>3" value="3" required> <br /><br />
                                                <label for="answer<?=$i;?>3">
                                                    <img src="<?=URL_MEDIA_IMAGE;?>/<?=$surveyData[$i]['ChoiceImage'][3];?>" style="width:70px;height:70px; margin-bottom:10px;" /><br />
                                                    <?=$surveyData[$i]['ChoiceText'][3];?>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <center>
                    <button type="submit" name="submitSurvey" value="1" class="btn btn-lg btn-success">
                        Kirim Penilaian Anda
                    </button>
                </center>
            </form>
        </div>
    </div>
</div>
<!-- # App Capsule -->