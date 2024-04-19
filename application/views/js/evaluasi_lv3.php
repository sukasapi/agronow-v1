<script>
    $(document).ready(function() {
        $('#ss').click(function(){
			$('#act').val('ss');
			$('#dform').submit();
		});
		$('#sf').click(function(){
			var flag = confirm('Anda yakin ingin menyimpan final? Setelah disimpan final, data tidak dapat diedit.');
			if(flag==false) {
				return ;
			}
			$('#act').val('sf');
			$('#dform').submit();
		});
    });
</script>
