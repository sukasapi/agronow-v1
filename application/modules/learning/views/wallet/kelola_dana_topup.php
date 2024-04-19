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
	} else if(
		$this->session->userdata('member_nip')==$arrK['verifikator_sdm']
	) { // data ditemukan
		$CI =& get_instance();
		
		$strError = '';
		$enable_simpan_ui = true;
		$status_verifikator = '';
		if($this->session->userdata('member_nip')==$arrK['verifikator_sdm']) {
			$status_verifikator = 'SDM';
		}
		
		$arrKat = array(
			'topup'=>'Tambah Saldo ke Karyawan',
			'withdraw'=>'Tarik Saldo dari Karyawan'
		);
		
		$member_id = $this->session->userdata('member_id');
		$nama_karyawan = $this->session->userdata('member_name');
		$id_level_karyawan = $this->session->userdata('id_level_karyawan');
		$group_id = $this->session->userdata('group_id');
		$group_name = $CI->group_model->get_group_name($group_id);
		
		$post = $this->input->post();
		if(!empty($post)) {
			$member = $post['karyawan'];
			$id_member = (int) $post['id_member'];
			$kategori = $post['kategori'];
			$nominal = (int) $post['nominal'];
			$catatan = $post['catatan'];
			
			$nominal = abs($nominal);
			
			if($kategori=="topup") {
				$nominal = $nominal;
			} else if($kategori=="withdraw") {
				$nominal = $nominal * -1;
			}
			
			if(empty($id_member)) $strError .= '<li>Karyawan masih kosong.</li>';
			if(empty($kategori)) $strError .= '<li>Kategori masih kosong.</li>';
			if(empty($nominal)) $strError .= '<li>Nominal masih kosong.</li>';
			if(empty($catatan)) $strError .= '<li>Catatan masih kosong.</li>';
			
			if(strlen($strError)<=0) {
				$CI->db->trans_start();
				
				$catatan = "[".$arrKat[$kategori]."] ".$catatan;
				
				// get id level karyawan
				$kueri = "select id_level_karyawan from _member where member_id='".$id_member."' ";
				$res = $this->db->query($kueri);
				$row = $res->result_array();
				$id_level_karyawan = $row[0]['id_level_karyawan'];
				
				$kueri = "select id, nominal from _learning_wallet_konfig_group where tahun='".$tahun_terpilih."' and id_group='".$group_id."' and id_member='".$id_member."' and kategori='member_total_topup' ";
				$res = $this->db->query($kueri);
				$row = $res->result_array();
				$idn = $row[0]['id'];
				$nominal_current = $row[0]['nominal'];
				$nominal_total = $nominal_current + $nominal;
				
				// insert topup baru
				$did = uniqid('LW',true);
				$kueri = "insert into _learning_wallet_konfig_group set id='".$did."', tahun='".$tahun_terpilih."', id_group='".$group_id."', id_member='".$id_member."', nominal='".$nominal."', kategori='member_topup', catatan=".$this->db->escape($catatan).", last_update=now() ";
				$CI->db->query($kueri);
				
				// insert/update total dana pengembangan individu
				if(empty($idn)) {
					$did = uniqid('LW',true);
					$kueri = "insert into _learning_wallet_konfig_group set id='".$did."', tahun='".$tahun_terpilih."', id_group='".$group_id."', id_member='".$id_member."', nominal='".$nominal_total."', kategori='member_total_topup', catatan='', last_update=now() ";
					$CI->db->query($kueri);
				} else {
					$kueri = "update _learning_wallet_konfig_group set nominal='".$nominal_total."', last_update=now() where id='".$idn."' ";
					$CI->db->query($kueri);
				}
				
				// update id_level_karyawan di semua history topup
				$kueri = "update _learning_wallet_konfig_group set id_level_karyawan='".$id_level_karyawan."' where tahun='".$tahun_terpilih."' and id_group='".$group_id."' and id_member='".$id_member."' ";
				$CI->db->query($kueri);
				
				$CI->db->trans_complete();
				
				if($CI->db->trans_status()===false) {
					$strError .= "<li>Tidak dapat menyimpan data. Silahkan coba lagi beberapa saat lagi.</li>";
				} else {
					$this->session->set_flashdata('str_info', 'Informasi: Data berhasil disimpan.');
					
					redirect(base_url('learning/wallet/kelola_dana_topup/'.$tahun_terpilih));
					exit;
				}
			}
		}
		
		// get nama karyawan
		if(!empty($id_member)) {
			$CI->member_model->recData['memberId'] = $id_member;
			$arrM = $CI->member_model->select_member('byId');
			$member = "[".$arrM['member_nip']."] ".$arrM['member_name'];
		}
	?>
	
	<div class="pt-2 pl-3 pr-3">
		<div class="row rounded lw_bg_hijau">
			<div class="col-3 text-center align-self-center">
				<img src="<?=PATH_ASSETS.'icon/lw_kelola_dana.png'?>" alt="image" class="imaged w86 img-fluid">
			</div>
			<div class="col-9 align-self-center">
				<h2 class="text-white pt-1 pr-1">Top Up Dana Pengembangan Tahun <?=$tahun_terpilih?></h2>
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
				<div class="alert alert-info mb-1">
					<b>Panduan</b><br/>
					<ul>
						<li>Kolom <b>Karyawan</b> terhubung dengan database karyawan, ketikkan nik/nama untuk mencari data karyawan.</li>
						<li>Apabila data tidak ditemukan, kemungkinan karyawan yg hendak dicari belum pernah login ke AgroNow.</li>
						<li>Kolom nominal diisi dengan angka tanpa format apapun (misal <b>4000000</b> untuk empat juta rupiah).</li>
					</ul>
				</div>
				
				<form action="<?=base_url('learning/wallet/kelola_dana_topup/'.$tahun_terpilih)?>" id="form" method="post" class="form-horizontal">
					<div class="small"><span class="text-danger">*</span> wajib diisi</div>
					
					<div class="form-group">
						<label for="member">Karyawan <span class="text-danger">*</span></label>
						<input type="text" class="form-control border border-primary" id="member" name="member" value="<?=$member?>"/>
						<input type="hidden" name="id_member" value="<?=$id_member?>"/>
					</div>
					
					<div class="form-group mb-1">
						<label for="kategori" class="mb-0">Kategori <span class="text-danger">*</span></label>
						<select class="form-control" id="kategori" name="kategori">
						  <option></option>
							<?php
							foreach($arrKat as $key => $val) {
								$seld = ($kategori==$key)? "selected" : "";
								$uiT = '<option value="'.$key.'" '.$seld.'>'.$val.'</option>';
								echo $uiT;
							}
							?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="nominal">Nominal <span class="text-danger">*</span>&nbsp;<span class="badge badge-primary" id="label_nominal"></span></label>
						<input type="text" class="form-control format_harga" id="nominal" name="nominal" value="<?=$nominal?>" autocomplete="off"/>
					</div>
					
					<div class="form-group">
						<label for="catatan">Catatan <span class="text-danger">*</span></label>
						<textarea class="form-control" id="catatan" rows="3" name="catatan" onkeypress="javascript: if(event.keyCode == 13) event.preventDefault();"><?=$catatan?></textarea>
					</div>
					
					<button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="divider pb-2 mb-1 mt-2"></div>
	
	<div class="p-2">
		<div class="row">
			<div class="col-12">
				<!--begin: Datatable -->
				<table id="dt" class="table table-striped table-bordered" width="100%"></table>
				<!--end: Datatable -->
			</div>
        </div>
	</div>

	<?php
	} else {
		$ui =
			'<div class="pt-2 pl-3 pr-3">
				<div class="alert alert-info">
					Menu ini dikhususkan untuk admin SDM.
				</div>
			 </div>';
		echo $ui;
	}
	?>
    
	<div class="mb-2">&nbsp;</div>
</div>

<script type="text/javascript">
var datatable = null;

function reformatHarga(nilai) {
	return Number(nilai).toLocaleString('id');
}

function reformatNilai(ele,nilai,prefix) {
	var harga = Number(nilai).toLocaleString('id');
	$('#label_'+ele).html(prefix+harga);
}

window.onload = function() {
	<?=$addJS?>
	
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
			url  : '<?=base_url('learning/wallet/ajax_dp/'.$tahun_terpilih)?>',
			type : "POST"
		},
		language: {
			searchPlaceholder: "cari berdasarkan nik/nama",
			"infoFiltered": ""
		},
		order: [[ 3, "desc" ]],

		columns: [
			{data: 'member_nip', title:'nik', orderable: false},
			{data: 'member_name', title:'nama', orderable: true},
			{data: 'nominal', title:'nominal', orderable: false},
			{data: 'last_update', title:'tanggal', orderable: true},
		],
		columnDefs: [
			{
				targets: 2,
				className: 'text-right',
				render: function(data, type, full, meta) {
					var html = reformatHarga(full["nominal"]);
					
					return html;
				},
			},
			{
				targets: 3,
				render: function(data, type, full, meta) {
					var html = full["last_update"]+'<br/>'+full["catatan"];
					
					return html;
				},
			},
		],
	});
	
	$('.format_harga').keyup(function(){
		var id = $(this).attr('id');
		reformatNilai(id,$(this).val(),'Rp.&nbsp;');
	});
	
	$('#member').autocomplete({
		source:'<?=base_url('learning/wallet/ajax_member')?>',
		minLength:1,
		change:function(event,ui) { if($(this).val().length==0) $('input[name=id_member]').val(''); },
		select:function(event,ui) { $('input[name=id_member]').val(ui.item.id); }
	});
};
</script>