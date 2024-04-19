<script>
    const chatContainer = $(".chat-container");
    let latest_read_id, prev_page, page;

    function fetch_chat(page) {
        $('.loading').show();
        $.ajax({
            url: "<?= base_url('learning/expert_directory/ajax_fetch_chat?expert_id=').$data['expert_id']?>&page="+page,
            type: "get",
            dataType: 'json',
            success: function (data) {
                let chats = data.chats;
                let html, item_class, title, bubble_style;
                let prev = '';
                let curr = '';
                latest_read_id = data.latest_read_id;
                $.each(chats, function (index, chat) {
                    html = '';
                    item_class = '';
                    title = '';
                    bubble_style = '';
                    if (chat.member_status === 'current'){
                        item_class = 'user';
                    } else if (chat.member_status === 'starter'){
                        title = '<div class="title">'+chat.member_name+'</div>';
                        bubble_style = 'style="background: #9FFA65; color:black"';
                    } else if (chat.member_status === 'expert'){
                        title = '<div class="title">'+chat.member_name+'</div>';
                        bubble_style = 'style="background: #faf9cb; color:black"';
                    } else {
                        title = '<div class="title">'+chat.member_name+'</div>';
                    }
                    curr = chat.divider_date;
                    if (prev !== curr){
                        prev = curr;
                        html += '<div class="message-divider">'+chat.divider_date+'</div>';
                    }
                    if (chat.ec_image){
                        html += '<div class="message-item pb-2 '+item_class+'" data-chat-id="'+chat.ec_id+'">\n' +
                            '    <div class="content">\n' +
                            title +
                            '        <div class="bubble" '+bubble_style+'>\n' +
                            '            <img src="'+chat.ec_image+'" alt="photo" class="imaged w160">\n' +
                            '            <div class="mt-1">'+chat.ec_desc+'</div> \n' +
                            '        </div>\n' +
                            '        <div class="footer">'+chat.time+'</div> \n'+
                            '    </div>\n' +
                            '</div>'
                    } else if (chat.ec_desc){
                        html += '<div class="message-item pb-2 '+item_class+'" data-chat-id="'+chat.ec_id+'">\n' +
                            '    <div class="content">\n' +
                            title +
                            '        <div class="bubble" '+bubble_style+'>\n' +
                            '            '+chat.ec_desc+'\n' +
                            '        </div>\n' +
                            '        <div class="footer">'+chat.time+'</div> \n'+
                            '    </div>\n' +
                            '</div>'
                    }
                    chatContainer.append(html);
                });
                scroll_to_chat_id(latest_read_id);
                if (chats.length >= 100){
                    prev = page;
                    page++;
                }
            },
        }).done(function () {
            $('.loading').hide();
        });
    }

    function scroll_to_chat_id(ec_id){
        let read_from = $('[data-chat-id="'+ec_id+'"]');
        // console.log('scroll to: '+ec_id);
        if (read_from.length){
            read_from.get(0).scrollIntoView();
        }
    }

    $(document).ready(function () {
        page = 1;
        fetch_chat(page);
        $(window).scroll(function () {
            if ($(this).scrollTop() === 0){
                // console.log('on top of msg')
            }
            if($(this).scrollTop() + $(this).height() === $(document).height()) {
                // console.log('on bottom of msg');
                if (page > prev_page){
                    fetch_chat(page);
                }
            }
        });
    })
</script>