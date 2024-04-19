<script type="text/javascript">
    function checkSecond(sec) {
        if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
        if (sec < 0) {sec = "59"};
        return sec;
        if(sec == 0 && m == 0){ alert('stop it')};
    }

    function startTimer() {
        var presentTime = document.getElementById('timer').innerHTML;
        var timeArray = presentTime.split(/[:]+/);
        var m = timeArray[0];
        var s = checkSecond((timeArray[1] - 1));
        if(s==59){m=m-1;}
        if(m==0 && s==0){
            document.getElementById("form").submit();
        }
        document.getElementById('timer').innerHTML = m + ":" + s;
        setTimeout(startTimer, 1000);
    }

    function startEvaluasiTest() {
        var x = document.getElementById("divSoal");
        var b = document.getElementById("button_start_evaluasitest");

        if (x.style.display === "none") {
            b.style.display = 'none';
            x.style.display = "block";
            startTimer();
            let totalPage = $('#soalPage').val();
            let soalCurrent = $("#soalShow").val();
            if(soalCurrent === totalPage){
                $("#next").hide();
                $("#submitConfirm").show();
            }
        }
    }

    window.onload = function() {
        if (document.getElementById('divSoal')){
            document.getElementById('divSoal').style.display = 'none';
        }
    };

    $(function(){
        // hide tombol submit
		$('#sendAnswerEvaluasi').hide();
		
        $("#submitConfirm").click(function(){
			$("#confirmTest").show();
            window.scrollTo(0,0);
			
			var juml_soal = $('#jumlah_soal').val();
			var juml_dijawab = $('input:radio:checked').length;
			if(juml_dijawab<juml_soal) {
				var juml_blm_dijawab = juml_soal-juml_dijawab;
				$('#err_juml_jawaban_ui').html('Ada '+juml_blm_dijawab+' pertanyaan belum dijawab.').show();
				$('#sendAnswerEvaluasi').hide();
			} else {
				$('#err_juml_jawaban_ui').html('').hide();
				$('#sendAnswerEvaluasi').show();
			}
        });
		$("#backToTest").click(function(){
            $("#confirmTest").hide();
        });

        $("div#divSoal #next").click(function(){
            let totalPage = $('#soalPage').val();
            let soalCurrent = $("#soalShow").val();
            let soalNext = parseInt(soalCurrent)+1;
            if(soalCurrent === totalPage){
                $("#next").hide();
                $("#submitConfirm").show();
            } else {
                $("#submitConfirm").hide();
                $("#next").show();
                $("#soalShow").val(soalNext);
                $("ul").hide();
                $(".soal"+soalNext).show();
                $("#prev").show();
                $("#next").attr("disabled","disabled");
                if($('.radiosoal'+soalCurrent).is(':checked')) {
                    $("#next").removeAttr("disabled");
                }
                if(soalCurrent >= totalPage-1){
                    $("#next").hide();
                    $("#submitConfirm").show();
                }
            }
            window.scrollTo(0,0);
        });

        $("div#divSoal #prev").click(function(){ 
            let soalCurrent = $("#soalShow").val();
            let soalNext = parseInt(soalCurrent)-1;
            $("#soalShow").val(soalNext);
            $("ul").hide();
            $(".soal"+soalNext).show();
            $("#next").removeAttr("disabled","disabled");
            $("#next").show();
            $("#submitConfirm").hide();
			if (soalCurrent <= 2) {
				$("#prev").hide();
			}
        });

        $("div#divSoal td").click(function(){       
            let soalCurrent = $("#soalShow").val();
            if($('.radiosoal'+soalCurrent).is(':checked')) {
                $("#next").removeAttr("disabled");
            }
        });
    })
</script>
<script language="javascript">
    var noPrint=true;
	var noCopy=true;
	var noScreenshot=true;
	var autoBlur=true;
	var ele_blur = "appCapsule";
	var ele_unblur = "blur_note";
	document.getElementById(ele_unblur).style.display = "none";
</script>

<script type="text/javascript" src="<?= PATH_ASSETS; ?>js/noprint_v2.js"></script>
