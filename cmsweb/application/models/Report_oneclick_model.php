<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_oneclick_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


     //FUNCTION : fungsi mendapatkan whistlist peserta 
    //AUTH : KDW
    //DATE :01052024
    function get_daftar_whislist($filter=null){
     
       $q =  "SELECT g.group_name as entitas, m.member_nip as NIP, m.member_name as Nama,mlk.nama as level_member,
       wc.kode as kode_kelas, wc.nama as nama_kelas, wc.tgl_mulai as mulai,wc.tgl_selesai as selesai,wc.harga as harga,wl.status as status
      FROM _learning_wallet_wishlist as wl
      LEFT JOIN _member as m ON m.member_id = wl.id_member
      LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
      LEFT JOIN _group as g ON g.group_id=m.group_id
      LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.id_level_karyawan
     ".$filter."
      ORDER BY g.group_id ASC";

      $exe =$this->db->query($q)->result();
      $result = $exe;
      return $result;
      
    }

    function get_daftar_whislist_syn($filter=null){
     
        $q =  "SELECT g.group_name as entitas, m.member_nip as NIP, m.member_name as Nama,mlk.nama as level_member,
        wc.kode as kode_kelas, wc.nama as nama_kelas, wc.tgl_mulai as mulai,wc.tgl_selesai as selesai,wc.harga as harga,wl.status as status
       FROM _learning_wallet_wishlist as wl
       LEFT JOIN _member as m ON m.member_id = wl.id_member
       LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
       LEFT JOIN _group as g ON g.group_id=m.group_id
       LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.id_level_karyawan
      ".$filter."
       ORDER BY g.group_id ASC";
 
       $exe =$this->db->query($q)->result();
       $result = $exe;
       return $result;
       
     }

    //FUNCTION : fungsi mendapatkan peserta yang telah diapprove pengajuan agrowalletnya 
    //AUTH : KDW
    //DATE :01052024
    
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
   

    //FUNCTION : fungsi mendapatkan peserta kelas 
    //AUTH : KDW
    //DATE :03052024

    function get_count_peserta_kelas($filter=null){
        $q="SELECT COUNT(crm_id) as jp FROM _classroom_member ".$filter;
        $exe =$this->db->query($q)->result();
        $result = $exe;
        return $result;
    }

    function get_count_hari($filter=null){
        $q="SELECT DATEDIFF(cr_date_end,cr_date_start) as hari FROM _classroom ".$filter;
        $exe =$this->db->query($q)->result();
        $result = $exe;
        return $result;
    }

    function get_count_presensi($classroom_id){
            $this->db->select('COUNT(*) as total');
            $this->db->from('_classroom_attendance');
    
            $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
            $this->db->join('_group', '_group.group_id = _member.group_id', 'left');
    
            $this->db->where('cr_id', $classroom_id);
    
    
            $query      = $this->db->get();
            $result     = $query->result_array();
    
            if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_absensi_kelas(){

    }

    function get_jumlah_lulus($filter=null){
        $q="SELECT count(crm_id) as lulus FROM _classroom_member ".$filter;
        $exe =$this->db->query($q)->result();
        $result = $exe;
        return $result;
    }


    //FUNCTION : fungsi mendapatkan summary report 
    //AUTH : KDW
    //DATE :03052024
    
    
    function get_summary_entitas($grup=null){
        if($grup==null || $grup ==""){
            $q="SELECT group_id,group_name 
            FROM _group
            WHERE group_status='active' 
            AND id_klien='1'
            ORDER BY group_id ASC";
        }else{
            $q="SELECT group_id,group_name 
                FROM _group
                WHERE group_status='active' 
                AND id_klien='1'
                AND group_id in (".$grup.")
                ORDER BY group_id ASC";

        }
      
        $qentitas=$this->db->query($q)->result();

        foreach($qentitas as $qe){

        //count member
        $sm="SELECT g.group_name as entitas, count(m.`member_id`) as jumlah
                FROM _member as m 
                LEFT JOIN _group as g ON g.group_id = m.group_id
                WHERE m.member_status='active'
                AND m.member_name <>'Tim Developer IT'
                AND m.group_id='".$qe->group_id."' AND m.id_level_karyawan IN (1,2,3)";
        $qm=$this->db->query($sm)->row();
        if(isset($qm->jumlah) && $qm->jumlah > 0){
            $data[$qe->group_name]['peserta']=$qm->jumlah;
        }else{
            $data[$qe->group_name]['peserta']=0;
        }

        //count member x whistlist
            $smw="SELECT g.group_name as entitas, count(DISTINCT m.member_id) as jumlah
            FROM _learning_wallet_wishlist as wl
            LEFT JOIN _member as m ON m.member_id = wl.id_member
            LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
            LEFT JOIN _group as g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.id_level_karyawan
            WHERE m.member_name <>'Tim Developer IT'
            AND wl.status IN ('aktif','dihapus')
            AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan IN (1,2,3)
            limit 1          
            ";

            $qmw=$this->db->query($smw)->row();
            if(isset($qmw->jumlah) && $qmw->jumlah > 0){
                $data[$qe->group_name]['pemilih']=$qmw->jumlah;
            }else{
                $data[$qe->group_name]['pemilih']=0;
            }


        // count whislist
            $sw="SELECT g.group_name as entitas, count(wl.`id`) as jumlah
            FROM _learning_wallet_wishlist as wl
            LEFT JOIN _member as m ON m.member_id = wl.id_member
            LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
            LEFT JOIN _group as g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.mlevel_id
            WHERE m.member_name <>'Tim Developer IT'
            AND wl.status IN ('aktif','dihapus')
            AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan IN (1,2,3)
            limit 1          
            ";
            $qw=$this->db->query($sw)->row();
            if(isset($qw->jumlah) && $qw->jumlah > 0){
                $data[$qe->group_name]['whislist']=$qw->jumlah;
            }else{
                $data[$qe->group_name]['whislist']=0;
            }
           
        //count pengajuan
            $spp="SELECT g.group_name AS entitas, COUNT(wp.`id`) AS jumlah_pengajuan
            FROM _learning_wallet_pengajuan AS wp
            LEFT JOIN _member AS m ON m.member_id = wp.id_member
            LEFT JOIN _learning_wallet_classroom AS wc ON wc.id =wp.id_lw_classroom
            LEFT JOIN _group AS g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.mlevel_id
            WHERE m.member_name <>'Tim Developer IT'
            AND wp.status = 'aktif'
            AND wc.tgl_mulai LIKE '2024-%'
            AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan IN (1,2,3)
            limit 1
            ";

            $qpp=$this->db->query($spp)->row();
            $data[$qpp->entitas]['pengajuan']=$qpp->jumlah_pengajuan;

            if(isset($qpp)){
                $data[$qe->group_name]['pengajuan']=$qpp->jumlah_pengajuan;
            }else{
                $data[$qe->group_name]['pengajuan']="0";
            }

        //count pengajuan approve
        $spa="SELECT g.group_name AS entitas, COUNT(wp.`id`) AS jumlah_pengajuan
        FROM _learning_wallet_pengajuan AS wp
        LEFT JOIN _member AS m ON m.member_id = wp.id_member
        LEFT JOIN _learning_wallet_classroom AS wc ON wc.id =wp.id_lw_classroom
        LEFT JOIN _group AS g ON g.group_id=m.group_id
        LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.mlevel_id
        WHERE m.member_name <>'Tim Developer IT'
        AND wp.status = 'aktif'
        AND wc.tgl_mulai LIKE '2024-%'
        AND wp.`kode_status_current`='40'
        AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan IN (1,2,3)
        limit 1
        ";

        $qpa=$this->db->query($spa)->row();
        $data[$qpp->entitas]['approve']=$qpa->jumlah_pengajuan;

        if(isset($qpa)){
            $data[$qe->group_name]['approve']=$qpa->jumlah_pengajuan;
        }else{
            $data[$qe->group_name]['approve']="0";
        }

        //count pengajuan cancel

        $spc="SELECT g.group_name AS entitas, COUNT(wp.`id`) AS jumlah_pengajuan
        FROM _learning_wallet_pengajuan AS wp
        LEFT JOIN _member AS m ON m.member_id = wp.id_member 
        LEFT JOIN _learning_wallet_classroom AS wc ON wc.id =wp.id_lw_classroom
        LEFT JOIN _group AS g ON g.group_id=m.group_id
        LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.mlevel_id
        WHERE m.member_name <>'Tim Developer IT'
        AND wp.status = 'aktif'
        AND wc.tgl_mulai LIKE '2024-%'
        AND wp.`kode_status_current`=20
        AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan IN (1,2,3)
        limit 1
        ";

        $qpc=$this->db->query($spc)->row();
        $data[$qpp->entitas]['cancel']=$qpc->jumlah_pengajuan;

        if(isset($qpc)){
            $data[$qe->group_name]['cancel']=$qpc->jumlah_pengajuan;
        }else{
            $data[$qe->group_name]['cancel']="0";
        }

        }

        $res=$data;
    
    
        return $res;
    }

    function get_summary_entitas2($grup=null){
        if($grup==null || $grup ==""){
            $q="SELECT group_id,group_name 
            FROM _group
            WHERE group_status='active' 
            AND id_klien='1'
            ORDER BY group_id ASC";
        }else{
            $q="SELECT group_id,group_name 
                FROM _group
                WHERE group_status='active' 
                AND id_klien='1'
                AND group_id in (".$grup.")
                ORDER BY group_id ASC";
           /* $this->db->select("group_id,group_name")
            ->from("_group")
            ->where("group_status","active")
            ->where("id_klien","1")
            ->where_in("group_id",$grup)
            ->order_by('group_id','ASC');
            */
        }
      
        $qentitas=$this->db->query($q)->result();

        foreach($qentitas as $qe){
        //count member
        $sm="SELECT g.group_name as entitas, count(m.`member_id`) as jumlah
                FROM _member as m 
                LEFT JOIN _group as g ON g.group_id = m.group_id
                WHERE m.member_status='active'
                AND m.group_id='".$qe->group_id."' AND m.id_level_karyawan <=3";
        $qm=$this->db->query($sm)->row();
        if(isset($qm->jumlah) && $qm->jumlah > 0){
            $data[$qe->group_name]['peserta']=$qm->jumlah;
        }else{
            $data[$qe->group_name]['peserta']=0;
        }

        //count member x whistlist
            $smw="SELECT g.group_name as entitas, count(DISTINCT m.member_id) as jumlah
            FROM _learning_wallet_wishlist as wl
            LEFT JOIN _member as m ON m.member_id = wl.id_member
            LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
            LEFT JOIN _group as g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.mlevel_id
            WHERE m.member_name <>'Tim Developer IT'
            AND wl.status = 'aktif'
            AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan <=3
            limit 1          
            ";

            $qmw=$this->db->query($smw)->row();
            if(isset($qmw->jumlah) && $qmw->jumlah > 0){
                $data[$qe->group_name]['pemilih']=$qmw->jumlah;
            }else{
                $data[$qe->group_name]['pemilih']=0;
            }


        // count whislist
            $sw="SELECT g.group_name as entitas, count(wl.`id`) as jumlah
            FROM _learning_wallet_wishlist as wl
            LEFT JOIN _member as m ON m.member_id = wl.id_member
            LEFT JOIN _learning_wallet_classroom as wc ON wc.id =wl.id_lw_classroom
            LEFT JOIN _group as g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan as mlk ON mlk.id=m.mlevel_id
            WHERE m.member_name <>'Tim Developer IT'
            AND wl.status = 'aktif'
            AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan <=3
            limit 1          
            ";
            $qw=$this->db->query($sw)->row();
            if(isset($qw->jumlah) && $qw->jumlah > 0){
                $data[$qe->group_name]['whislist']=$qw->jumlah;
            }else{
                $data[$qe->group_name]['whislist']=0;
            }
           
        //count pengajuan
            $spp="SELECT g.group_name AS entitas, COUNT(wp.`id`) AS jumlah_pengajuan
            FROM _learning_wallet_pengajuan AS wp
            LEFT JOIN _member AS m ON m.member_id = wp.id_member
            LEFT JOIN _learning_wallet_classroom AS wc ON wc.id =wp.id_lw_classroom
            LEFT JOIN _group AS g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.mlevel_id
            WHERE m.member_name <>'Tim Developer IT'
            AND wp.status = 'aktif'
            AND wc.tgl_mulai LIKE '2024-%'
            AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan <=3
            limit 1
            ";

            $qpp=$this->db->query($spp)->row();
            $data[$qpp->entitas]['pengajuan']=$qpp->jumlah_pengajuan;

            if(isset($qpp)){
                $data[$qe->group_name]['pengajuan']=$qpp->jumlah_pengajuan;
            }else{
                $data[$qe->group_name]['pengajuan']="0";
            }

        //count pengajuan approve
        $spa="SELECT g.group_name AS entitas, COUNT(wp.`id`) AS jumlah_pengajuan
        FROM _learning_wallet_pengajuan AS wp
        LEFT JOIN _member AS m ON m.member_id = wp.id_member
        LEFT JOIN _learning_wallet_classroom AS wc ON wc.id =wp.id_lw_classroom
        LEFT JOIN _group AS g ON g.group_id=m.group_id
        LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.mlevel_id
        WHERE m.member_name <>'Tim Developer IT'
        AND wp.status = 'aktif'
        AND wc.tgl_mulai LIKE '2024-%'
        AND wp.`kode_status_current`='40'
        AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan <=3
        limit 1
        ";

        $qpa=$this->db->query($spa)->row();
        $data[$qpp->entitas]['approve']=$qpa->jumlah_pengajuan;

        if(isset($qpa)){
            $data[$qe->group_name]['approve']=$qpa->jumlah_pengajuan;
        }else{
            $data[$qe->group_name]['approve']="0";
        }

        //count pengajuan cancel

        $spc="SELECT g.group_name AS entitas, COUNT(wp.`id`) AS jumlah_pengajuan
        FROM _learning_wallet_pengajuan AS wp
        LEFT JOIN _member AS m ON m.member_id = wp.id_member 
        LEFT JOIN _learning_wallet_classroom AS wc ON wc.id =wp.id_lw_classroom
        LEFT JOIN _group AS g ON g.group_id=m.group_id
        LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.mlevel_id
        WHERE m.member_name <>'Tim Developer IT'
        AND wp.status = 'aktif'
        AND wc.tgl_mulai LIKE '2024-%'
        AND wp.`kode_status_current`=20
        AND g.group_id='".$qe->group_id."' AND m.id_level_karyawan >=3
        limit 1
        ";

        $qpc=$this->db->query($spc)->row();
        $data[$qpp->entitas]['cancel']=$qpc->jumlah_pengajuan;

        if(isset($qpc)){
            $data[$qe->group_name]['cancel']=$qpc->jumlah_pengajuan;
        }else{
            $data[$qe->group_name]['cancel']="0";
        }

        }

       
   /* */
        $res=$data;
        return $res;
    }



    function get_company($iduser=null){
        $q="SELECT user_level_id,id_klien,user_code FROM _user WHERE user_id='".$iduser."' AND user_status='active' limit 1";
        $result =$this->db->query($q)->result();
        return $result;
    }


    function get_nonwishlist_member($filter=null){
        $q="SELECT  DISTINCT m.member_id,m.member_nip as nip,m.member_name as nama,mlk.nama as level_karyawan,g.group_name as entitas
            FROM _member m 
            LEFT JOIN _learning_wallet_wishlist wl ON wl.id_member = m.member_id
            LEFT JOIN _group AS g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.id_level_karyawan"
            .$filter.
            " ORDER BY g.group_id ASC";
            $result =$this->db->query($q)->result();
            return $result;
    }

    function get_nonwishlist_member2($filter=null){
        $q="SELECT  DISTINCT m.member_id,m.member_nip as nip,m.member_name as nama,mlk.nama as level_karyawan,g.group_name as entitas
            FROM _member m 
            LEFT JOIN _learning_wallet_wishlist wl ON wl.id_member = m.member_id
            LEFT JOIN _group AS g ON g.group_id=m.group_id
            LEFT JOIN _member_level_karyawan AS mlk ON mlk.id=m.id_level_karyawan"
            .$filter.
            " ORDER BY g.group_id ASC";
            //$result =$this->db->query($q)->result();
            return $q;
    }


    ///29052024
    ///absensi
    


}
