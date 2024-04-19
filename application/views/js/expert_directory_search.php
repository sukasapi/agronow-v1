<script>
    $(document).ready(function(){

        load_data();

        function load_data()
        {
            $.ajax({
                url:"<?= base_url('learning/expert_directory/ajax_search') ?>",
                method:"POST",
                dataType: 'json',
                data:{
                    'keyword': $('#search_text').val(),
                    'opsi': $('input[name="opsi"]:checked').val(),
                },
                success:function(data){
                    let html = '<ul class="listview image-listview media search-result mb-2">';
                    for (i=0; i<data.length; i++){
                        html += '<li>' +
                                    '<a href="'+data[i].detail_url+'" class="item">' +
                                        '<div class="imageWrapper">' +
                                            '<img src="'+data[i].em_image+'" alt="image" class="imaged w64">' +
                                        '</div>' +
                                        '<div class="in">' +
                                            '<div>' +
                                                '<h4 class="mb-05">'+data[i].em_name+'</h4>' +
                                                '<div class="text-muted">'+ data[i].institution +
                                                    '<div class="mt-05"><strong>'+data[i].title+'</strong></div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</li>'
                    }
                    html += '</ul>';
                    $('#result').html(html);
                },
                error: function (data) {
                    console.log(data);
                }
            })
        }

        $('input[name="opsi"]').change(function () {
            load_data()
        });
        $('#search_text').keyup(function(){
            load_data()
        });
    });
</script>