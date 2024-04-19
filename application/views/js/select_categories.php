<script>
    const max_limit = 4; // Max Limit
    $(document).ready(function (){
        const submit = $("#btnSubmit");
        $("#selectCategories input:checkbox").each(function (index){
            this.checked = ("#selectCategories input:checkbox" < max_limit);
            submit.attr('disabled','disabled');
        }).change(function (){
            let count = $("#selectCategories input:checkbox:checked").length;
            if (count === max_limit){
                submit.text('Submit');
                submit.removeAttr('disabled');
            }else if(count > max_limit){
                this.checked = false;
            }else{
                submit.attr('disabled','disabled');
                submit.text(count+'/4');
            }
        });
    });
</script>