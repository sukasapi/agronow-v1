<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Learning_wallet_model extends CI_Model {
	
	var $filter_data = array("nama_pelatihan"=>"","id_sekolah"=>"","metode"=>"","harga_max"=>"","id_level_karyawan"=>"","cari_ecolearning"=>"");
	var $page = 0;
	var $perPage = 0;
	
    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }
	
	function getOpsiTahun() {
		$tahun = 2023;
		$tahun_max = date("Y")+2;
		
		$arrTahun = array();
		for($i=$tahun;$i<$tahun_max;$i++) {
			$arrTahun[$i] = $i;
		}
		
		return $arrTahun;
	}
	
	function getTahunTerpilih($tahun_terpilih) {
		$tahun_terpilih = (int) $tahun_terpilih;
		
		$arrTahun = $this->getOpsiTahun();
		
		if(!in_array($tahun_terpilih,$arrTahun)) {
			$tahun_terpilih = date("Y"); // reset($arrTahun);
		}
		
		return $tahun_terpilih;
	}
	
	function getTanggalKonfig($kategori) {
		$arr = array();
		
		$prefix = '';
		if($kategori=="pengajuan") $prefix = 'pengajuan';
		else if($kategori=="approval") $prefix = 'approval';
		
		$this->db->select('nama, nilai');
        $this->db->from('_learning_wallet_konfigurasi');
        $this->db->where('kategori', 'umum');
		
		$this->db->group_start();
		$this->db->where('nama', $prefix.'_mulai');
		$this->db->or_where('nama', $prefix.'_selesai');
		$this->db->group_end();
		
		$this->db->order_by('nilai','asc');
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$kat = '';
			if($val['nama']==$prefix."_mulai") $kat = 'mulai';
			else if($val['nama']==$prefix."_selesai") $kat = 'selesai';
			$arr[$kat] = $val['nilai'];
		}
		
		return $arr;
	}
	
	function getKonfigurasi($nama) {
		$arr = array();
		
		$this->db->select('nilai, nama, catatan');
        $this->db->from('_learning_wallet_konfigurasi');
        $this->db->where('kategori', 'umum');
		$this->db->where('nama', $nama);
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$arr[$val['nama']]['nilai'] = $val['nilai'];
			$arr[$val['nama']]['catatan'] = $val['catatan'];
		}
		
		return $arr;
	}
	
	function getDaftarStatusPengajuan() {
		$arr = array();
		
		$this->db->select('nilai, catatan');
        $this->db->from('_learning_wallet_konfigurasi');
        $this->db->where('kategori', 'umum');
		$this->db->where('nama', 'status_pengajuan');		
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$json = json_decode($val['catatan'],true);
			$arr[$val['nilai']]['label'] = $json['label'];
			$arr[$val['nilai']]['warna'] = $json['warna'];
		}
		
		return $arr;
	}
	
	function getDaftarKategoriMetode() {
		$arr = array();
		
		$this->db->select('nilai, catatan');
        $this->db->from('_learning_wallet_konfigurasi');
        $this->db->where('kategori', 'umum');
		$this->db->where('nama', 'kategori_metode');
		$this->db->order_by('nilai', 'asc');
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$arr[$val['nilai']] = $val['catatan'];
		}
		
		return $arr;
	}
	
	function getDaftarSekolah() {
		$arr = array();
		
		$this->db->select('id, nama');
        $this->db->from('_learning_wallet_sekolah');
        $this->db->where('status', 'active');
		$this->db->order_by('nama', 'asc');		
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$arr[$val['id']] = $val['nama'];
		}
		
		return $arr;
	}
	
	function getDaftarLevelKaryawan($id_klien) {
		$arr = array();
		
		$this->db->select('id, nama');
        $this->db->from('_member_level_karyawan');
        $this->db->where('status', 'active');
		$this->db->where('id_klien', $id_klien);
		$this->db->order_by('nama', 'asc');		
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$arr[$val['id']] = $val['nama'];
		}
		
		return $arr;
	}
	
	function getDaftarKonfigGroup($id_group,$tahun) {
		$arr = array();
		
		$this->db->select('nama, nilai');
        $this->db->from('_learning_wallet_konfigurasi');
        $this->db->where('kategori', 'group');
		$this->db->group_start();
		$this->db->where('tahun', 0);
		$this->db->or_where('tahun', $tahun);
		$this->db->group_end();
		$this->db->where('id_group', $id_group);
		
        $query      = $this->db->get();
        $result     = $query->result_array();
		
		foreach($result as $key => $val) {
			$arr[$val['nama']] = $val['nilai'];
		}
		
		return $arr;
	}
	
	function getDetailPengajuanPelatihan($tahun,$member_id,$id_pelatihan) {
		$this->db->select('*, date_format(tgl_request, "%Y-%m") as tb_request');
        $this->db->from('_learning_wallet_pengajuan');
		$this->db->where('status','aktif');
		$this->db->where('tahun',$tahun);
        $this->db->where('id_member',$member_id);
		$this->db->where('id_lw_classroom', $id_pelatihan);
		
		$query      = $this->db->get();
        $result     = $query->result_array();
		
		return $result[0];
	}
	
	function getDetailPelatihan($tahun,$id_pelatihan) {
		$this->db->select('*');
        $this->db->from('_learning_wallet_classroom');
        $this->db->where('status', 'aktif');
		$this->db->where('tahun', $tahun);
		$this->db->where('id', $id_pelatihan);
		
		// khusus test admin bisa lihat semua pelatihan
		// $member_id = $this->session->userdata('member_id');
		// if($member_id=="6020" || $member_id=="8684") $this->filter_data['id_level_karyawan'] = '';
		
		// check level karyawan dl
		// if(!empty($this->filter_data['id_level_karyawan'])) $this->db->like('daftar_level_karyawan', '['.$this->filter_data['id_level_karyawan'].']', 'both');
		
		$query      = $this->db->get();
        $result     = $query->result_array();
		
		return $result;
	}
	
	function getDetailPenyelenggara($id) {
		$this->db->select('*');
        $this->db->from('_learning_wallet_penyelenggara');
		$this->db->where('status','aktif');
		$this->db->where('id',$id);
		
		$query      = $this->db->get();
        $result     = $query->result_array();
		
		return $result[0];
	}
	
	function getPelatihan($tahun) {
		$offset = (int) $this->page;
		$limit = (int) $this->perPage;
		$offset--;
		if($offset>0) $offset *= $limit;
		
		// khusus test admin (atau flag filtering data) bisa lihat semua pelatihan
		$member_id = $this->session->userdata('member_id');
		$bypassLv = false;
		if(!empty($this->filter_data['show_all_lv'])) {
			$bypassLv = true;
		}
		if($bypassLv==true) $this->filter_data['id_level_karyawan'] = '';
		
		// $bulan_depan = date('Y-m-d', strtotime('first day of next month'));
		$tgl_mulai = date('Y-m-d', strtotime('today'));
		
		$this->db->select('*');
        $this->db->from('_learning_wallet_classroom');
        $this->db->where('status', 'aktif');
		$this->db->where('status_penyelenggaraan!=', 'batal');
		$this->db->where('tahun', $tahun);
		$this->db->where('tgl_mulai >=', $tgl_mulai);
		
		// filtering pencarian
		if(!empty($this->filter_data['nama_pelatihan'])) {
			$this->db->group_start();
			$this->db->like('nama', $this->filter_data['nama_pelatihan'], 'both');
			$this->db->or_like('kata_kunci', $this->filter_data['nama_pelatihan'], 'both');
			$this->db->group_end();
		}
		if(!empty($this->filter_data['id_sekolah'])) $this->db->where('id_sekolah', $this->filter_data['id_sekolah']);
		if(!empty($this->filter_data['metode'])) $this->db->where('metode', $this->filter_data['metode']);
		if(!empty($this->filter_data['harga_max'])) $this->db->where('harga <= "'.$this->filter_data['harga_max'].'"');
		if(!empty($this->filter_data['id_level_karyawan'])) $this->db->like('daftar_level_karyawan', '['.$this->filter_data['id_level_karyawan'].']', 'both');
		if(!empty($this->filter_data['cari_ecolearning'])) $this->db->where_in('is_ecolearning', array('ecolearning','ecowebinar'));
		
		$this->db->order_by('tgl_mulai', 'asc');
		$this->db->limit($limit, $offset);
		
		$query      = $this->db->get();
		$result     = $query->result_array();
		
		// echo $this->db->last_query();
		
		return $result;
	}
	
	function getJumlahPelatihan($tahun) {
		// khusus test admin (atau flag filtering data) bisa lihat semua pelatihan
		$member_id = $this->session->userdata('member_id');
		$bypassLv = false;
		if(!empty($this->filter_data['show_all_lv'])) {
			$bypassLv = true;
		}
		if($bypassLv==true) $this->filter_data['id_level_karyawan'] = '';
		
		// $bulan_depan = date('Y-m-d', strtotime('first day of next month'));
		$tgl_mulai = date('Y-m-d', strtotime('today'));
		
		$this->db->select('count(id) as jumlah');
        $this->db->from('_learning_wallet_classroom');
        $this->db->where('status', 'aktif');
		$this->db->where('status_penyelenggaraan!=', 'batal');
		$this->db->where('tahun', $tahun);
		$this->db->where('tgl_mulai >=', $tgl_mulai);
		
		// filtering pencarian
		if(!empty($this->filter_data['nama_pelatihan'])) {
			$this->db->group_start();
			$this->db->like('nama', $this->filter_data['nama_pelatihan'], 'both');
			$this->db->or_like('kata_kunci', $this->filter_data['nama_pelatihan'], 'both');
			$this->db->group_end();
		}
		if(!empty($this->filter_data['id_sekolah'])) $this->db->where('id_sekolah', $this->filter_data['id_sekolah']);
		if(!empty($this->filter_data['metode'])) $this->db->where('metode', $this->filter_data['metode']);
		if(!empty($this->filter_data['harga_max'])) $this->db->where('harga <= "'.$this->filter_data['harga_max'].'"');
		if(!empty($this->filter_data['id_level_karyawan'])) $this->db->like('daftar_level_karyawan', '['.$this->filter_data['id_level_karyawan'].']', 'both');
		if(!empty($this->filter_data['cari_ecolearning'])) $this->db->where_in('is_ecolearning', array('ecolearning','ecowebinar'));
		
		$query      = $this->db->get();
        $result     = $query->result_array();
		
		return $result[0]['jumlah'];
	}
	
	function getDaftarPelatihanDiajukan($tahun,$memberId,$kode_status) {
		$addSql = "";
		
		if($kode_status=="disetujui") {
			$addSql .= " and p.kode_status_current='40' and is_final_sevp='1' ";
		} else if($kode_status=="ditolak") {
			$addSql .= " and ((p.kode_status_current='-20' and is_final_sevp='1') or p.kode_status_current='-50') ";
		} else if($kode_status=="dibatalkan") {
			$addSql .= " and p.kode_status_current='-40' ";
		} else if($kode_status=="dalam_proses") {
			$addSql .= " and (p.kode_status_current='20' or p.kode_status_current='30') ";
		}
		
		$sql =
			"select 
				c.tahun, c.id as id_program, c.id_sekolah, c.nama, c.tgl_mulai, c.tgl_selesai, c.status_penyelenggaraan, c.catatan_penyelenggaraan, 
				p.jumlah_jam, p.id_group,
				p.id as id_pengajuan, p.harga, p.alasan_request, p.tgl_request, p.berkas, p.kode_status_current, p.tgl_update_status, p.is_final_sdm, p.catatan_approval,
				date_format(tgl_request, '%Y-%m') as tb_request
			 from _learning_wallet_pengajuan p, _learning_wallet_classroom c
			 where p.status='aktif' and c.tahun='".$tahun."' and c.tahun=p.tahun and c.id=p.id_lw_classroom and c.status='aktif' and p.id_member='".$memberId."' ".$addSql."
			 order by c.tgl_mulai asc, c.nama asc ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		
		return $row;
	}
	
	function getSaldoAwal($tahun,$memberId,$group_id,$id_level_karyawan) {
		$nominal_awal = 0;
		
		$sql =
			"select nilai
			 from _learning_wallet_konfigurasi
			 where kategori='group' and tahun='".$tahun."' and id_group='".$group_id."' and nama='lv_kary_".$id_level_karyawan."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$nominal_level = $row[0]['nilai'];
		
		$sql =
			"select nominal
			 from _learning_wallet_konfig_group
			 where kategori='member_total_topup' and tahun='".$tahun."' and id_group='".$group_id."' and id_member='".$memberId."' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$nominal_topup = $row[0]['nominal'];
		
		$nominal_awal = $nominal_level + $nominal_topup;
		
		return $nominal_awal;
	}
	
	function getSaldoTerpakai($tahun,$memberId) {
		$saldo_terpakai = 0;
		
		$sql =
			"select sum(p.harga) as harga_total
			 from _learning_wallet_pengajuan p, _learning_wallet_classroom c
			 where c.id=p.id_lw_classroom and p.kode_status_current>0 and p.tahun='".$tahun."' and p.id_member='".$memberId."' and p.status='aktif' and c.status='aktif' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		
		$saldo_terpakai = $row[0]['harga_total'];
		
		return $saldo_terpakai;
	}
	
	function getRealisasiJPL($tahun,$memberId) {
		$jpl = 0;
		
		$sql =
			"select sum(p.jumlah_jam) as jpl
			 from _learning_wallet_pengajuan p, _learning_wallet_classroom c
			 where c.id=p.id_lw_classroom and p.kode_status_current>0 and p.tahun='".$tahun."' and p.id_member='".$memberId."' and p.status='aktif' and c.status='aktif' ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		
		$jpl = $row[0]['jpl'];
		
		if(empty($jpl)) $jpl = 0;
		
		return $jpl;
	}
	
	function hitungPersen($target,$realisasi,$isReformat,$isShowSimbolPersen) {
		$persen = ($target==0)? 0 : ($realisasi/$target)*100;
		if($isReformat) $persen = number_format($persen,2,',','.');
		if($isShowSimbolPersen) $persen = $persen.'%';
		return $persen;
	}
	
	function reformatHarga($harga) {
		return "Rp.&nbsp;".number_format($harga,0,',','.');
	}
	
	function getInfoPengajuanEntitas($tahun,$group_id,$tahun_bulan,$id_pelatihan) {
		$arr = array();
		$arr['total'] = 0;
		$arr['disetujui'] = 0;
		$arr['menunggu'] = 0;
		
		$addSql = "";
		if(!empty($tahun_bulan)) {
			$addSql .= " and p.tgl_request like '".$tahun_bulan."-%' ";
		}
		if($id_pelatihan>0) {
			$addSql .= " and c.id='".$id_pelatihan."' ";
		}
		
		$sql =
			"select
				p.harga, p.kode_status_sdm
			 from _learning_wallet_pengajuan p, _learning_wallet_classroom c, _member m
			 where c.id=p.id_lw_classroom and p.id_member=m.member_id and p.id_group='".$group_id."' and p.tahun='".$tahun."' and p.status='aktif' and c.status='aktif' ".$addSql;
		$res = $this->db->query($sql);
		$row = $res->result_array();
		foreach($row as $key => $val) {
			$arr['total'] += $val['harga'];
			if($val['kode_status_sdm']=="1") $arr['disetujui'] += $val['harga'];
			else if($val['kode_status_sdm']=="0") $arr['menunggu'] += $val['harga'];
		}
		
		return $arr;
	}
	
	public function status_penyelenggaraan() {
		$arrSP = array();
		$arrSP['-'] = 'Belum Diputuskan';
		$arrSP['jalan'] = 'Diselenggarakan';
		$arrSP['batal'] = 'Dibatalkan';
		
		return $arrSP;
	}
	
	public function get_url_berkas_approval($folder,$namafile,$include_anchor_tag,$is_newtab=false) {
		$url_berkas = '';
		if(!empty($namafile)) {
			$url_berkas = URL_BASE.AGROWALLET_MEDIA_APPROVAL_PATH.$folder.'/'.$namafile;
			if($include_anchor_tag) {
				$target = ($is_newtab==true)? " target='_blank' " : "";
				$url_berkas = '<a '.$target.' href="'.$url_berkas.'">[lihat berkas]</a>';
			}
		}
		return $url_berkas;
	}
	
	public function daftar_level_karyawan2label($id_klien,$daftar_level_karyawan) {
		$arrLevelKaryawan = $this->getDaftarLevelKaryawan($id_klien);
		
		$info_level_karyawan = '';
		$daftar_level_karyawan = str_replace(']','',$daftar_level_karyawan);
		$arrAllowedLv = explode('[',$daftar_level_karyawan);
		unset($arrAllowedLv['0']);
		$juml = count($arrAllowedLv); $i = 0;
		foreach($arrAllowedLv as $keyLv => $valLv) {
			$i++;
			$info_level_karyawan .= $arrLevelKaryawan[$valLv];
			if($i<$juml) $info_level_karyawan .= ",";
		}
		
		return $info_level_karyawan;
	}
}
