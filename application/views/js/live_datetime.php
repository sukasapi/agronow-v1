<script>
    $(document).ready(function() {
        clockUpdate();
        setInterval(clockUpdate, 1000);
    });

    function clockUpdate() {
        let d = new Date();
        let month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        function addZero(x) {
            if (x < 10) {
                return x = '0' + x;
            } else {
                return x;
            }
        }

        function twelveHour(x) {
            if (x > 12) {
                return x = x - 12;
            } else if (x === 0) {
                return x = 12;
            } else {
                return x;
            }
        }

        let date = d.getDate() + " " + month[d.getMonth()] + " " + d.getFullYear();

        let h = addZero(twelveHour(d.getHours()));
        let m = addZero(d.getMinutes());
        let s = addZero(d.getSeconds());

        $('#livedatetime').html(date + " - " + h + ':' + m + ':' + s);
    }
</script>