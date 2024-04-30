<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_oneclick_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }

    function get_daftar_whislist($filter=null){
     
       $q =  "SELECT g.group_name as entitas, m.member_nip as NIP, m.member_name as Nama,mlk.nama as level_member,
       wc.kode as kode_kelas, wc.nama as nama_kelas, wc.tgl_mulai as mulai,wc.tgl_selesai as selesai,wc.harga as harga,wl.status as status
      FROM _learning_wallet_wishlist as wl
      LEFT JOIN _member as m ON m.member_id = wl.id_member
      LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
      LEFT JOIN _group as g ON g.group_id=m.group_id
      LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.mlevel_id
     ".$filter."
      ORDER BY g.group_id ASC";

      $exe =$this->db->query($q)->result();
      $result = $exe;
      return $result;
      
    }

    function get_approval_peserta($filter=null){
        $q="select m.member_nip as nip,m.member_name as nama,g.group_name as entitas,wc.nama as pelatihan,wc.tgl_mulai as mulai,wc.tgl_selesai as selesai,wc.harga,MONTH(wc.tgl_mulai) as bulan,
        (CASE 
            when wp.kode_status_current='20' then 'dibatalkan'
            when wp.kode_status_current='40' then 'disetujui'
            when wp.kode_status_current='0' then 'belum diproses'
        END) as status 
        FROM _learning_wallet_pengajuan as wp
        LEFT JOIN _member as m ON m.member_id = wp.id_member
        LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wp.id_lw_classroom
        LEFT JOIN _group as g ON g.group_id=m.group_id
        LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.mlevel_id
        ".$filter."
        ORDER BY g.group_id ASC	";

        $exe =$this->db->query($q)->result();
        $result = $exe;
        return $result;
    }
   


}
