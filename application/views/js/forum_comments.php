<script>
    function get_comments(page) {
        $.ajax({
            url: "<?= base_url('ajax/get_forum_comment?forum_id=').$content['id']?>&page="+page,
            type: "get",
            dataType: 'json',
            success: function (data) {
                let i;
                let comments = data.comments;
                let html = '<div class="comment-block">';
                for (i=0; i<comments.length; i++){
                    html += '<div class="item">'+
                        '<div class="avatar">'+
                        '<img src="'+comments[i].member_image+'" alt="avatar" class="imaged w32 rounded">'+
                        '</div>'+
                        '<div class="in">'+
                        '<div class="comment-header">'+
                        '<h4 class="title">'+comments[i].member_name+'</h4>'+
                        '<span class="time">'+comments[i].comment_time+'</span>'+
                        '</div>'+
                        '<div class="text">'+
                        comments[i].fc_text+
                        '</div>'+
                        '</div>'+
                        '</div>';
                }
                html += '</dev>'+
                    '<div class="pt-2 pb-2">'+
                    '<nav>'+
                    '<ul class="pagination pagination-rounded">';
                if (data.prev_page>0){
                    html += '<li class="page-item"><button class="page-link" onclick="get_comments('+data.prev_page+')">Previous</a></li>'
                }
                if (data.next_page>1){
                    html += '<li class="page-item"><button class="page-link" onclick="get_comments('+data.next_page+')">Next</button></li>'
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
            url: '<?= base_url("ajax/post_forum_comment")?>',
            type: 'post',
            data: {
                'forum_id'    : <?= $content['id'] ?>,
                'comment_text'  : comment_text,
            },
            success: function (comment_id) {
                if (comment_id>0){
                    $('#comment_text').val('');
                    $("#send").attr("disabled", false);
                }
                get_comments(1);
            }
        })
    });
    $(document).ready(function() {
        get_comments(1);
    });

</script>