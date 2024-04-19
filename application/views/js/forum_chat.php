<script>
    function get_chats(page) {
        $.ajax({
            url: "<?= base_url('learning/forum/ajax_get_chats?forum_id=').$forum['forum_id']?>&page="+page,
            type: "get",
            dataType: 'json',
            success: function (data) {
                console.log(data);
                var i;
                var chats = data.chat;
                var html = '<div class="comment-block">';
                for (i=0; i<chats.length; i++){
                    html += '<div class="item">'+
                        '<div class="avatar">'+
                        '<img src="'+chats[i].member_image+'" alt="avatar" class="imaged w32 rounded">'+
                        '</div>'+
                        '<div class="in">'+
                        '<div class="comment-header">'+
                        '<h4 class="title">'+chats[i].member_name+'</h4>'+
                        '<span class="time">'+chats[i].comment_time+'</span>'+
                        '</div>'+
                        '<div class="text">'+
                        chats[i].fc_desc+
                        '</div>'+
                        '</div>'+
                        '</div>';
                }
                html += '</dev>'+
                    '<div class="pt-2 pb-2">'+
                    '<nav>'+
                    '<ul class="pagination pagination-rounded">';
                if (data.prev_page>0){
                    html += '<li class="page-item"><button class="page-link" onclick="get_chats('+data.prev_page+')">Previous</a></li>'
                }
                if (data.next_page>1){
                    html += '<li class="page-item"><button class="page-link" onclick="get_chats('+data.next_page+')">Next</button></li>'
                }
                html += '</ul>'+
                    '</nav>'+
                    '</div>';
                $('#showComments').html(html);
            }
        });
    }
    $('#commentForm').submit(function (e) {
        e.preventDefault();
        $("#send").attr("disabled", true);
        const comment_text = $('#comment_text').val();
        $.ajax({
            url: '<?= base_url("learning/forum/ajax_post_chat")?>',
            type: 'post',
            data: {
                'forum_id'    : <?= $forum['forum_id'] ?>,
                'chat_text'  : comment_text,
            },
            success: function (comment_id) {
                if (comment_id>0){
                    $('#comment_text').val('');
                    $("#send").attr("disabled", false);
                }
                get_chats(1);
            }
        })
    });
    $(document).ready(function() {
        get_chats(1);
    });

</script>