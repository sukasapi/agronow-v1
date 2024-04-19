<script type="text/javascript">
    function search(obj){
        input = document.getElementById('keyword');
        filter = input.value.toUpperCase();
        ul = document.getElementById(obj);
        li = ul.getElementsByTagName('li');

        for (i = 0; i < li.length; i++) {
            h = li[i].getElementsByTagName("h3")[0];
            txtValue = h.textContent || h.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
      }
        $(obj)
    }
</script>