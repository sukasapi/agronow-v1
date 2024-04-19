<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_level_karyawan_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }
	
	function getAllLevelKaryawan($id_klien) {
		$this->db->select('*');
        $this->db->from('_member_level_karyawan');
        $this->db->where('id_klien', $id_klien);
		$this->db->where('status', 'active');
		$this->db->order_by("nama", "asc");

        $query      = $this->db->get();
        $result     = $query->result_array();
		
		$arr = array();
		foreach($result as $key => $val) {
			$arr[$val['id']] = $val['nama'];
		}
		// default, untuk nampung ga BOD-minus blm disetel
		$arr['unknown'] = 'unknown';
		
		return $arr;
	}
	
	function getIDLevelKaryawan($id_klien,$bod_minus) {
		$nama_bod = '';
		$addSql = '';
		if(is_numeric($bod_minus)) {
			if($bod_minus>=0) $nama_bod = 'BOD-'.$bod_minus;
			else $nama_bod = 'BOD';
			$addSql = " and status='active' ";
		} else {
			$nama_bod = 'bod-unknown';
		}
		
		$this->db->select('id');
        $this->db->from('_member_level_karyawan');
        $this->db->where('id_klien', $id_klien);
		$this->db->where('nama', $nama_bod);		
		if($nama_bod!='bod-unknown') {
			$this->db->where('status', 'active');
		}

        $query      = $this->db->get();
        $result     = $query->result_array();
		
		return $result[0]['id'];
	}
}
