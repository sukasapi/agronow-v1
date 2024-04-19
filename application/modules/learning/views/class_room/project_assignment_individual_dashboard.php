<?php $this->load->view('learning/app_header'); ?>
<style>
    .centertext {
        display: block;
        text-align: center;
        font-size: 1em;
    }
    .txtlabel{
        color : white;
       background-color: #008000;
    }


    .circles {
  margin-bottom: -10px;
}

.circle {
  width: 100px;
  margin: 6px 6px 20px;
  display: inline-block;
  position: relative;
  text-align: center;
  line-height: 1.2;
}

.circle canvas {
  vertical-align: middle; 
  max-width: 100%;
    max-height: 100%; 
}


.circle strong {
  position: absolute;
  top: 30px;
  left: 0;
  width: 100%;
  text-align: center;
  line-height: 40px;
  font-size: 30px;
}

.circle strong i {
  font-style: normal;
  font-size: 0.6em;
  font-weight: normal;
}

.circle span {
  display: block;
  color: #aaa;
  margin-top: 12px;
}
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full prelearning">
        <div class="p-3 pb-5">
            <div id="content">
                <div class="text-center pb-4">
                    <h2>INDIVIDUAL SCOREBOARD</h2>
                </div>
                <div class="row pb-4 mx-4" >
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                    <h3 class="text-success font-weight-bold"><strong>Solution Progress</strong></h3>
                    </div>
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                        <div class="progress" style="height: 40px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?=$pa[0]->pa_progress;?>%; height:40px" aria-valuenow="<?=$pa[0]->pa_progress;?>" aria-valuemin="0" aria-valuemax="100"><?=$pa[0]->pa_progress;?>%</div>
                        </div>
                    </div>
                </div>
                <div class="row pb-4 mx-4">
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                        <h3 class="text-success font-weight-bold" ><strong>Program Progress</strong></h3>
                    </div>               
                </div>
                <div class="row pb-4 mx-4">
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                        <?php 
                        $pacounter=0;
                       
                            foreach($detail_pa as $dap){
   
                        ?>
                            <div class="row">
                                <div class="col-6 col-md-6 col-xs-6 justify-content-center text-center">
                                    <div class="circles">
                                        <div class="second circle">
                                            <canvas></canvas>
                                            <input type="hidden"id="progress<?=$pacounter?>" value='<?=$dap->pad_progress?>'>
                                            <strong><i>%</i></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 col-xs-6 justify-content-center">
                                    <h4><strong><?=strtoupper($dap->pad_program)?></strong></h4>
                                    <p>Deliverable :<strong><?=$dap->pad_deliverable?></strong></p>
                                    <p>Outcome :<strong><?=$dap->pad_outcome?></strong></p>
                                </div>
                            </div>
                        <?php
                        $pacounter++;   
                            }
                        ?>
                    </div>               
                </div>
               
                   
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->

<script>
    $(document).ready(function () {
        var baseUrl='<?=base_url()?>';

        function gradecolor(progress){
            var rescol;
            if(progress <=25){
                rescol='#fb4b4b';
            }else if(progress > 25 && progress <=50 ){
                rescol='#ffc163';
            }else if(progress > 50 && progress <=75 ){
                rescol='#feff5c';
            }else if(progress > 75 ){
                rescol='#c0ff33';
            }            
            else{
                rescol='#000';
            }
            return rescol;
        }
       
        var cpro=0;
        $('.second.circle').each(function(index,item){
           
            var prog=$("#progress"+cpro).val();
            var zprog=prog/100;
            var cols=gradecolor(prog);

         $(this).circleProgress({
                value: zprog,  
                fill: {color: cols},
                thickness:20,
                animationStartValue:1,
            });
            $(this).find('strong').html(Math.round(prog) + '<i>%</i>');
            cpro++;
        })
    })
</script>