<script>
    function bookmark() {
        const icon = $("#btnBookmark");
        $.ajax({
            url: "<?=base_url("ajax/toggle_bookmark/{$content['content_id']}")?>",
            type: "get",
            dataType: 'json',
            success: function(response) {
            if(response.status){
                document.querySelector("#response_bookmark").innerText = response.msg;
                if (icon.attr("name") === "bookmark"){
                    icon.attr("name", "bookmark-outline");
                }  else {
                    icon.attr("name", "bookmark");
                }
            }
            toastbox('toast-bookmark', 3000);
        }
        });
    }
</script>