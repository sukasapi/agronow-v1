<?php $this->load->view('learning/app_header'); ?>

<style type="text/css">
    iframe, div#info img{
        width: 100%;
        height: auto;
    }
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">

	<?php
	if(empty($arrK)) { // data tidak ditemukan
	?>

	<div class="m-2 p-3 alert alert-info">Tidak dapat menampilkan data, PIC entitas tahun <?=$tahun_terpilih?> belum diatur.</div>

	<?php
	} else { // data ditemukan
		$CI =& get_instance();
		
		$strError = '';
		
		$member_id = $this->session->userdata('member_id');
		$group_id = $this->session->userdata('group_id');
		
		$post = $this->input->post();
		if(!empty($post)) {
			$judul = $post['judul'];
			$detail = $post['detail'];
			
			if(empty($judul)) $strError .= '<li>Judul pelatihan masih kosong.</li>';
			if(empty($detail)) $strError .= '<li>Detail usulan masih kosong.</li>';
			
			if(strlen($strError)<=0) {
				$CI->db->trans_start();
				
				$kueri = "insert into _learning_wallet_usulan set id_member='".$member_id."', id_group='".$group_id."', judul=".$this->db->escape($judul).", detail=".$this->db->escape($detail).", last_update=now() ";
				$CI->db->query($kueri);
				
				$CI->db->trans_complete();
				
				if($CI->db->trans_status()===false) {
					$strError .= "<li>Tidak dapat menyimpan data. Silahkan coba lagi beberapa saat lagi.</li>";
				} else {
					$this->session->set_flashdata('str_info', 'Informasi: Data berhasil disimpan.');
					
					redirect(base_url('learning/wallet/usulan/'.$tahun_terpilih));
					exit;
				}
			}
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_usulan.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Usulan Pelatihan</h2>
			</div>
		</div>
		
		<?php
		if($this->session->flashdata('str_info')) {
			echo '
				<div class="pt-2 pl-2 pr-2">
					<div class="alert alert-primary">
						'.$this->session->flashdata('str_info').'
					</div>
				</div>';
		}
		if(!empty($strError)) {
			echo '
				<div class="pt-2 pl-2 pr-2">
					<div class="alert alert-danger">
						<b>Tidak dapat memproses data</b>:<br/>
						<ul>'.$strError.'</ul>
					</div>
				</div>';
		}
		?>
		
		<div class="mt-2 row">
			<div class="col-12">
				<!--
				<div class="alert alert-info mb-1">
					<b>Panduan</b><br/>
					<ul>
						<li>Kolom top up dana pengembangan diisi dengan angka tanpa format apapun (misal <b>4000000</b> untuk empat juta rupiah).</li>
					</ul>
				</div>
				-->
				
				<form action="<?=base_url('learning/wallet/usulan/'.$tahun_terpilih)?>" id="form" method="post" class="form-horizontal">
					<div class="small"><span class="text-danger">*</span> wajib diisi</div>
					
					
					<div class="form-group">
						<label for="judul">Judul pelatihan yang Diusulkan <span class="text-danger">*</span></span></label>
						<input type="text" class="form-control" id="judul" name="judul" value="<?=$judul?>" autocomplete="off"/>
					</div>
					
					<div class="form-group">
						<label for="detail">Detail Usulan <span class="text-danger">*</span></label>
						<textarea class="form-control" id="detail" rows="4" name="detail" onkeypress="javascript: if(event.keyCode == 13) event.preventDefault();"><?=$detail?></textarea>
						<small>masukkan alasan, tujuan dan silabus pelatihan yang diusulkan</small>
					</div>
					
					<button type="button" id="bsimpan" class="btn btn-success pl-5 pr-5">Simpan</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="divider pb-2 mb-1 mt-2"></div>
	
	<div class="p-2">
		<div class="row">
			<div class="col-12 text-center mb-2">
				<a class="btn btn-sm btn-success" href="javascript:void(0)" onclick="unduh()">Unduh XLS</a>
			</div>
			
			<div class="col-12">
				<!--begin: Datatable -->
				<table id="dt" class="table table-striped table-bordered" width="100%"></table>
				<!--end: Datatable -->
			</div>
        </div>
	</div>

	<?php
	}
	?>
    
	<div class="mb-2">&nbsp;</div>
</div>

<script type="text/javascript">
var datatable = null;

function unduh() {
	datatable.button(0).trigger();
}

window.onload = function() {
	datatable = $('#dt').DataTable({
		// scrollY: '50vh',
		// scrollX: true,
		scrollCollapse: true,
		responsive: false,
		searchDelay: 500,
		processing: true,
		serverSide: true,
		stateSave: false,
		ajax: {
			url  : '<?=base_url('learning/wallet/ajax_usulan/'.$tahun_terpilih)?>',
			type : "POST"
		},
		language: {
			searchPlaceholder: "cari",
			"infoFiltered": ""
		},
		order: [[3, 'desc']],
		buttons: [
			{ extend: 'excelHtml5', title: 'usulan', fieldBoundary: '' },
		],
		columns: [
			{data: 'no', title:'no', orderable: false},
			{data: 'member_name', title:'nama', orderable: false},
			{data: 'judul', title:'judul', orderable: false},
			{data: 'detail', title:'detail', orderable: false},
			{data: 'last_update', title:'tanggal', orderable: true},
		],
	});
	
	$('#bsimpan').click(function() {
		var flag = confirm('Usulan yang telah disimpan tidak dapat dikoreksi. Anda yakin ingin melanjutkan?');
		
		if(flag==false) {
			return ;
		} else {
			$('form#form').submit();
		}
	});
};
</script>