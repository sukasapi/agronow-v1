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

.success{
    border-left-width: 20px; 
    border-left-color:#008000;
    height:50px;
    color:#008000;
}

.proses{
   border-left-width: 20px; 
    border-left-color:#f0932b;
    height:50px;
    color:#f0932b;
}

.failed{
    border-left-width: 20px; 
    border-left-color:#d63031;
    height:50px;
    color:#d63031;
}
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full prelearning">
        <div class="p-3 pb-5">
            <div id="content">
                <div class="text-center pb-4">
                    <h2>PROJECT ASSIGNMENT SCOREBOARD</h2>
                </div>
                <?php 
                     if($total > 0){
                        ?>
       
                <div class="row pb-4 mx-4" >
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                    <h3 class="text-success font-weight-bold"><strong>Class Solution Progress</strong></h3>
                    </div>
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                        <div class="text-center">
                            <input type="hidden" name="sbval" id="sbval" value="<?=$total?>">
                            <canvas id="score"></canvas>
                            <h3><strong>
                            <div id="preview-textfield"></div>
                            </strong></h3>
                        </div>
                       
                    </div>
                </div>
                <div class="row pb-4 mx-4">
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                        <h3 class="text-success font-weight-bold" ><strong>Individual Solution Progress</strong></h3>
                    </div>               
                </div>
                <div class="row pb-4 mx-4">
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                        <ul class="list-group ">
                            <?php
                                foreach($histori as $key=>$h){
                                	if(count((array)$h) > 1){
                                		if($h[0] > $h[1]){
                                       ?>
	                                    <li class="list-group-item ">
	                                        <div class="row success" style="margin-left:20px;">
	                                            <div class="col-2 col-sm-2"><p style="font-size: 15px; font-weight:600;color:#000"><?=$h[0]?></p></div>
	                                            <div class="col-2 col-sm-2"> 
	                                            <p>
	                                            	<i class="fa fa-chevron-up text-success" aria-hidden="true"></i>
	                                        	</p>
	                                        	</div>
	                                            <div class="col-8 col-sm-8"><p style="font-size: 15px; font-weight:600"><?=$key?></p></div>
	                                        </div>
	                                    </li>
	                                       <?php
	                                    }else if($h[0] = $h[1]){
	                                        ?>
										<li class="list-group-item ">
	                                        <div class="row" style="margin-left:20px;">
	                                            <div class="col-2 col-sm-2"><p style="font-size: 15px; font-weight:600 ;color:#000"><?=$h[0]?></p></div>
	                                            <div class="col-2 col-sm-2"> 
	                                            <p>
	                                            	<i class="fa fa-minus text-warning" aria-hidden="true"></i>
	                                        	</p>
	                                        	</div>
	                                            <div class="col-8 col-sm-8"><p style="font-size: 15px; font-weight:600 ;color:#000"><?=$key?></p></div>
	                                        </div>
	                                    </li>
	                                        <?php
	                                    }else{
	                                        ?>
									<li class="list-group-item ">
	                                        <div class="row" style="margin-left:20px;">
	                                            <div class="col-2 col-sm-2"><p style="font-size: 15px; font-weight:600 ;color:#000"><?=$h[0]?></p></div>
	                                            <div class="col-2 col-sm-2"> 
	                                            <p>
	                                            	<i class="fa fa-chevron-down text-danger" aria-hidden="true"></i>
	                                        	</p>
	                                        	</div>
	                                            <div class="col-8 col-sm-8"><p style="font-size: 15px; font-weight:600 ;color:#000"><?=$key?></p></div>
	                                        </div>
	                                    </li>
	                                       <?php
	                                    }
                                	}else{
                                		?>
                                		<li class="list-group-item proses">
	                                        <div class="row " style="margin-left:20px;">
	                                            <div class="col-2 col-sm-2 d-flex justify-content-center"><p style="font-size: 15px; font-weight:600 ;color:#000"><?=$h[0]?></p></div>
	                                            <div class="col-2 col-sm-2"> 
	                                            <p>
	                                            	<i class="fa fa-minus text-warning" style="font-size:25px" aria-hidden="true"></i>
	                                        	</p>
	                                        	</div>
	                                            <div class="col-8 col-sm-8"><p style="font-size: 15px; font-weight:600 ;color:#000"><?=$key?></p></div>
	                                        </div>
	                                    </li>
                                		<?php
                                	}
                                    
                                   
                                }
                            ?>
                          
                        </ul>
                      </div>
                    </div>               
                </div>
               <?php 
               }else{
                ?>
                <div class="row pb-4 mx-4" >
                    <div class="col-12 col-sm-12 col-xs-12 pb-2">
                              <p class="text-success text-center font-weight-bold">Tidak ditemukan data Project Assignment pada kelas ini</p>
                    </div>
                </div>
                <?php
               }
               ?>


                   
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->

<script>
    $(document).ready(function () {
        var baseUrl='<?=base_url()?>';
        var ngraph=$("#sbval").val();
        var opts = {
        angle: -0.22, // The span of the gauge arc
        lineWidth: 0.37, // The line thickness
        radiusScale: 1, // Relative radius
        pointer: {
            length: 0.6, // // Relative to gauge radius
            strokeWidth: 0.049, // The thickness
            color: '#000000' // Fill color
        },
        limitMax: false,     // If false, max value increases automatically if value > maxValue
        limitMin: false,     // If true, the min value of the gauge will be fixed
        strokeColor: '#E0E0E0',  // to see which ones work best for you
        generateGradient: true,
        fontSize: 40,
        highDpiSupport: true,     // High resolution support
        staticZones: [
            {strokeStyle: "#F03E3E", min: 0, max: 30}, // Red from 100 to 130
            {strokeStyle: "#FFDD00", min: 30, max: 70}, // Yellow
            {strokeStyle: "#30B32D", min: 70, max: 100}, // Green
            ],
        staticLabels: {
            font: "10px sans-serif",  // Specifies font
            labels: [0,30, 70,100],  // Print labels at these values
            color: "#000000",  // Optional: Label text color
            fractionDigits: 0  // Optional: Numerical precision. 0=round off.
        },
        // renderTicks is Optional
        renderTicks: {
            divisions: 8,
            divWidth: 1.2,
            divLength: 0.44,
            divColor: '#333333',
            subDivisions: 3,
            subLength: 0.5,
            subWidth: 0.6,
            subColor: '#666666'
        }
  
};
var target = document.getElementById('score'); // your canvas element
var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
gauge.maxValue = 100; // set max gauge value
gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
gauge.animationSpeed = 32; // set animation speed (32 is default value)
gauge.set(ngraph); // set actual value

var textRenderer = new TextRenderer(document.getElementById('preview-textfield'))
textRenderer.render = function(gauge){
   percentage = gauge.displayedValue / gauge.maxValue
   this.el.innerHTML = (percentage * 100).toFixed(2) + "%"
};
gauge.setTextField(textRenderer);

     
})
</script>