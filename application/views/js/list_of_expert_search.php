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
                    'kategori': $('#kategori').val(),
                    'opsi': 'auto',
                },
                success:function(data){
                    let html = '';
                    for (i=0; i<data.length; i++){
                        html +=
                            '<a href="'+data[i].detail_url+'">' +
                                '<div class="d-flex align-items-center m-1 p-1" style="background-color: #e9eaec; color: black">' +
                                    '<div class="p-1 d-flex align-items-center">' +
                                        '<img src="'+data[i].em_image+'" alt="avatar" class="imaged rounded" style="width:70px">' +
                                    '</div>' +
                                    '<div class="p-1 flex-grow-1">' +
                                        '<div>' +
                                            '<p class="m-0"><b>'+data[i].em_name+'</b></p>' +
                                            '<p class="m-0" style="color:#a1389d">'+data[i].institution+'</p>' +
                                            '<div class="d-flex flex-row">' +
                                                '<div class="d-flex align-items-center">' +
                                                    '<ion-icon name="person"></ion-icon>' +
                                                '</div>' +
                                                '<div class="flex-grow-1">' +
                                                    '<p class="m-0" style="font-size:10px">&nbsp;'+data[i].title+'</p>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</a>';
                    }
                    $('#result').html(html);
                },
                error: function (data) {
                    console.log(data);
                }
            })
        }


        $('#search_text').keyup(function(){
            load_data()
        });
    });
</script>