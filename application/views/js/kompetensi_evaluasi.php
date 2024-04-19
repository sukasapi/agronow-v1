<script type="text/javascript">
    function detik2Jam(detik) {
        var sec_num = parseInt(detik, 10); // don't forget the second param
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return hours+':'+minutes+':'+seconds;
    }
    function startTimer() {
        var dur = <?=$durasi_detik?>;
        var now = new Date();
        now.setSeconds(now.getSeconds() + dur);
        var cd = now.getTime();
        $('#timer').html(detik2Jam(dur));
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = ((cd - now) / 1000).toFixed(0);
            $('#timer').html(detik2Jam(distance));
            if(distance < 0) {
                clearInterval(x);
                $('#forcedSubmit').val('1');
                $("#dform").submit();
            }
        }, 1000);
    }
    function mulaiUjian() {
        $('#mulaiUjian').hide();
        $('#divSoal').show();
        startTimer();
    }
    $('document').ready(function(){
        <?=$addJS?>
        $('#mulaiUjian').click(function(){
            mulaiUjian();
        });
        $("#dform input[name='choice']").change(function(){
            $('#kirim').removeAttr('disabled');
        });
        $('#kirim').click(function(){
            $("#dform").submit();
        });
    });
</script>