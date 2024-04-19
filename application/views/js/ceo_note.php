<script>
    function get_list(page) {
        $('.loading').show();
        $.ajax({
            url: "<?= site_url('whatsnew/ceo_note/ajax_get_ceo_note')?>?page="+page,
            type: "get",
            dataType: 'json',
            success: function (data) {
                $('.loading').hide();
                var i;
                var contents = data.contents;
                var html = '';
                if (contents.length >= 1){
                    for (i=0; i<contents.length; i++){
                        html += '<div class="section mt-2">'+
                            '<div class="card text-center">'+
                            '<div class="card-header">'+
                            '<div class="d-flex flex-row p-0">'+
                            '<div class="d-flex align-items-center">'+
                            '<img src="'+contents[i].author_image+'" alt="image" class="imaged w76" style="object-fit: fill;">'+
                            '</div>'+
                            '<div class="d-flex flex-column ml-1">'+
                            '<div class="text-left">'+
                            '<p class="mb-0"><b>'+contents[i].author+'</b></p>'+
                            '</div>'+
                            '<div class="text-left">'+
                            '<p class="mb-1 text-success">&nbsp;</p>'+
                            '</div>'+
                            '<div class="text-left">'+
                            '<p class="mb-0">'+contents[i].date+'</p>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '<div class="card-body p-2">'+
                            '<div class="text-left">'+
                            '<p class="mb-1 text-single" style="color: black; font-size: larger;"><b>'+contents[i].title+'</b></p>'+
                            '</div>';
                        if (contents[i].image){
                            html += '<img src="'+contents[i].image+'" alt="image" class="img-fluid mb-1" style="object-fit: fill;">';
                        }
                        html += '<div class="text-left">'+
                            '<div class="text-double">'+contents[i].isi+'</div>'+
                            '</div>'+
                            '</div>'+
                            '<div class="card-footer text-muted">'+
                            '<div class="d-flex justify-content-between">'+
                            '<div class="d-flex justify-content-between">'+
                            '<div>'+
                            '<span class="iconedbox iconedbox-sm" style="width: 60px;">'+
                            '<ion-icon name="eye"></ion-icon>'+
                            '<span style="font-size: 15px;">&nbsp;'+contents[i].viewed+'</span>'+
                            '</span>'+
                            '</div>'+
                            '<div>'+
                            '<span class="iconedbox iconedbox-sm" style="width: 60px;">'+
                            '<ion-icon style="content: url(\'<?=PATH_ASSETS?>icon/love-grey.png\')"></ion-icon>'+
                            '<span style="font-size: 15px;">&nbsp;'+contents[i].like_count+'</span>'+
                            '</span>'+
                            '</div>'+
                            '<div>'+
                            '<span class="iconedbox iconedbox-sm" style="width: 60px;">'+
                            '<ion-icon name="chatbox-ellipses"></ion-icon>'+
                            '<span style="font-size: 15px;">&nbsp;'+contents[i].comment_count+'</span>'+
                            '</span>'+
                            '</div>'+
                            '</div>'+
                            '<div class="text-left">'+
                            '<a href="<?=base_url("whatsnew/ceo_note/detail/")?>'+contents[i].id+'" class="btn btn-success btn-sm mr-1">SELENGKAPNYA</a>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'
                    }
                } else {
                    html += '<div class="section mt-2">\n' +
                        '<div class="d-flex justify-content-center mt-3">\n' +
                        '<p>Data tidak ditemukan</p>\n' +
                        '</div>\n' +
                        '</div>'
                }
                html += '</dev>'+
                    '<div class="pt-2 pb-2">'+
                    '<nav>'+
                    '<ul class="pagination pagination-rounded">';
                if (data.prev_page>0){
                    html += '<li class="page-item"><button class="page-link" onclick="get_list('+data.prev_page+')">Previous</a></li>'
                }
                if (data.next_page>1){
                    html += '<li class="page-item"><button class="page-link" onclick="get_list('+data.next_page+')">Next</button></li>'
                }
                html += '</ul>'+
                    '</nav>'+
                    '</div>';
                $('#ceoContents').html(html);
                $("html, body").animate({ scrollTop: 0 }, 200);
            }
        });
    }
    function get_my_list(page) {
        $.ajax({
            url: "<?= site_url('whatsnew/ceo_note/ajax_get_my_ceo_note')?>?page="+page,
            type: "get",
            dataType: 'json',
            success: function (data) {
                var i;
                var contents = data.contents;
                var html = '';
                if (contents.length >= 1){
                    for (i=0; i<contents.length; i++){
                        html += '<div class="section mt-2">'+
                            '<div class="card text-center">'+
                            '<div class="card-header">'+
                            '<div class="d-flex flex-row p-0">'+
                            '<div class="d-flex align-items-center">'+
                            '<img src="'+contents[i].author_image+'" alt="image" class="imaged w76" style="object-fit: fill;">'+
                            '</div>'+
                            '<div class="d-flex flex-column ml-1">'+
                            '<div class="text-left">'+
                            '<p class="mb-0"><b>'+contents[i].author+'</b></p>'+
                            '</div>'+
                            '<div class="text-left">'+
                            '<p class="mb-1 text-success">&nbsp;</p>'+
                            '</div>'+
                            '<div class="text-left">'+
                            '<p class="mb-0">'+contents[i].date+'</p>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '<div class="card-body p-2">'+
                            '<div class="text-left">'+
                            '<p class="mb-1 text-single" style="color: black; font-size: larger;"><b>'+contents[i].title+'</b></p>'+
                            '</div>';
                        if (contents[i].image){
                            html += '<img src="'+contents[i].image+'" alt="image" class="img-fluid mb-1" style="object-fit: fill;">';
                        }
                        html += '<div class="text-left">'+
                            '<div class="text-double">'+contents[i].isi+'</div>'+
                            '</div>'+
                            '</div>'+
                            '<div class="card-footer text-muted">'+
                            '<div class="d-flex justify-content-between">'+
                            '<div class="d-flex justify-content-between">'+
                            '<div>'+
                            '<span class="iconedbox iconedbox-sm" style="width: 60px;">'+
                            '<ion-icon name="eye" class="text-success"></ion-icon>'+
                            '<span style="font-size: 15px;">&nbsp;'+contents[i].viewed+'</span>'+
                            '</span>'+
                            '</div>'+
                            '<div>'+
                            '<span class="iconedbox iconedbox-sm" style="width: 60px;">'+
                            '<ion-icon style="content: url(\'<?=PATH_ASSETS?>icon/love-green.png\')"></ion-icon>'+
                            '<span style="font-size: 15px;">&nbsp;'+contents[i].like_count+'</span>'+
                            '</span>'+
                            '</div>'+
                            '<div>'+
                            '<span class="iconedbox iconedbox-sm" style="width: 60px;">'+
                            '<ion-icon name="chatbox-ellipses" class="text-success"></ion-icon>'+
                            '<span style="font-size: 15px;">&nbsp;'+contents[i].comment_count+'</span>'+
                            '</span>'+
                            '</div>'+
                            '</div>'+
                            '<div class="text-left">';
                            if (contents[i].status === 'draft'){
                                html += '<a href="<?=base_url("whatsnew/ceo_note/edit/")?>'+contents[i].id+'" class="btn btn-outline-secondary btn-sm mr-1">DRAFT</a>';
                            } else {
                                html += '<a href="<?=base_url("whatsnew/ceo_note/edit/")?>'+contents[i].id+'" class="btn btn-outline-primary btn-sm mr-1">PUBLISHED</a>';
                            }
                            html += '<a href="<?=base_url("whatsnew/ceo_note/detail/")?>'+contents[i].id+'" class="btn btn-success btn-sm mr-1">SELENGKAPNYA</a>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'
                    }
                } else {
                    html += '<div class="section mt-2">'+
                        '<div class="d-flex justify-content-center mt-3">'+
                        '<p>Data tidak ditemukan</p>'+
                        '</div>'+
                        '</div>'
                }
                html += '</dev>'+
                    '<div class="pt-2 pb-2">'+
                    '<nav>'+
                    '<ul class="pagination pagination-rounded">';
                if (data.prev_page>0){
                    html += '<li class="page-item"><button class="page-link" onclick="get_my_list('+data.prev_page+')">Previous</a></li>'
                }
                if (data.next_page>1){
                    html += '<li class="page-item"><button class="page-link" onclick="get_my_list('+data.next_page+')">Next</button></li>'
                }
                html += '</ul>'+
                    '</nav>'+
                    '</div>';
                $('#myContents').html(html);
            }
        });
    }
    $(document).ready(function() {
        get_list(1);
        get_my_list(1);
    });
</script>