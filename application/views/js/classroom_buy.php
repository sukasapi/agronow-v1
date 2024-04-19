<script type="text/javascript">
    function numberWithCommas(number) {
        if(number != null){
            var parts = number.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return parts.join(",");
        }else{
            return number;
        }
    }

    $(function(){
        $('button#buy_button').click(function(event) {
            price = $(this).data('price');
            id = $(this).data('id');
            $('button#buy_confirm').data('id', id);

            $('div#buyModal #price').html(numberWithCommas(price));
        });

        $('button#buy_confirm').click(function(event) {
            id = $(this).data('id');
            $('button#buy_confirm').attr('disabled', 'disabled');
            $('#buyModal').modal('hide');
            $.get('<?= base_url('learning/class_room/process_classroom?cr_id=') ?>'+id, function(data) {
                if(data.status == '0'){
                    $('#failModal div.modal-body').html(data.msg);
                    $('#failModal').modal('show');
                    $('button#buy_confirm').removeAttr('disabled');
                }else{
                    $('#successModal').modal('show');
                    $('#rewardModal span#poin').html(data.reward.poin);
                    $('#rewardModal span#cause').html(data.reward.cause);
                }
            },'json');
        });

        $('#successModal').on('hidden.bs.modal', function (e) {
            $('#rewardModal').modal('show');
            // location.reload();
        })

        $('#rewardModal').on('hidden.bs.modal', function (e) {
            location.reload();
        })
    })
</script>