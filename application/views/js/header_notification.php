<script>
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('notification/ajax_notification_count')?>",
            type: "get",
            dataType: 'json',
            success: function (data) {
                let total = (data.total > 99)?'99+':data.total;
                $('#notificationCount').text(total>0?total:'');
            }
        });
    });
</script>