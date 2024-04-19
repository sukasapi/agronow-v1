<script>
    function btn_like() {
        $.ajax({
            url: '<?=base_url("ajax/post_like")?>',
            type: 'post',
            data: {
                'content_id': <?= $content['content_id']?>
            },
            success: function(response) {
                location.reload();
            }
        });
    }
    function get_comments(page) {
        $.ajax({
            url: "<?= base_url('ajax/get_comment?content_id=').$content['content_id']?>&page="+page,
            type: "get",
            dataType: 'json',
            success: function (data) {
                var i;
                var comments = data.comments;
                var html = '<div class="comment-block">';
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
                        comments[i].comment_text+
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
            url: '<?= base_url("ajax/post_comment")?>',
            type: 'post',
            data: {
                'content_id'    : <?= $content['content_id'] ?>,
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