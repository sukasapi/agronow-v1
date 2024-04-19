<script>
    $("#formSuggest").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                $('#modalFormSuggest').modal('toggle');
                toastbox('toastSuggest', 3000);
            }
        });
    })
</script>