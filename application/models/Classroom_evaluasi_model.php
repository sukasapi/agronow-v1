<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder db
 */
class Classroom_evaluasi_model extends CI_Model {

    public function __construct(){ 
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }

    function get_evaluasi($idevaluasi=null){
        $sql="SELECT * FROM _nps_set_soal WHERE id='".$idevaluasi."'";
        $exe=$this->db->query($sql)->result();
        if(count((array)$exe)>0){
            return $exe;
        }else{
            return array();
        }
    }
 
    function get_listsoal($idsoal = null){
        $query="SELECT * 
                FROM _nps_set_soal
                WHERE id='".$idsoal."'
                LIMIT 1";
        $exe = $this->db->query($query)->result();
        if(count((array)$exe) > 0){
            return $exe;
        }else{
            return array();
        }
        
    }
    function get_listbyFilter($filter = null){
        $query="SELECT * 
                FROM _nps_set_soal
                WHERE ".$filter."
                LIMIT 1";
        $exe = $this->db->query($query)->result();
        if(count((array)$exe) > 0){
            return $exe;
        }else{
            return array();
        }
        
    }

    function get_classbyId($idkelas=null){
        $query="SELECT * FROM _classroom where cr_id='".$idkelas."' limit 1";
        $exe=$this->db->query($query)->result();
        if(count((array)$exe)>0){
            return $exe;
        }else{
            return array();
        }

    } 

    function get_classbyFilter($filter=null){
        $query="SELECT * FROM _classroom where ".$filter." limit 1";
        $exe=$this->db->query($query)->result();
        if(count((array)$exe)>0){
            return $exe;
        }else{
            return array();
        } 

    } 

    function get_classbyFilter2($filter=null){
        $query="SELECT * FROM _classroom where ".$filter."";
        $exe=$this->db->query($query)->result();
        if(count((array)$exe)>0){
            return $exe;
        }else{
            return array();
        }

    } 

    function get_evaluasibyFilter($filter=null){
        $this->db->select('*')
                 ->from("_nps_set_soal")
                 ->where($filter)
                 ->order_by("id");
        $exe=$this->db->get()->result();
        $sql=$this->db->last_query();
        //return $sql;
        
        if(count((array)$exe)>0){
            return $exe;
        }else{
            return array();
        }//*/
    
    }

    

    function get_soal($idsoal = null){
        $query="SELECT * 
                FROM _nps_soal
                WHERE id='".$idsoal."'
                LIMIT 1";
        $exe = $this->db->query($query)->result();
        if(count((array)$exe) > 0){
            return $exe;
        }else{
            return array();
        }
        
    }

    // 16022024 - cari soal by filter
    function get_soalfilter($filter=null){
        $query="SELECT * 
        FROM _nps_soal
        WHERE ". $filter." ORDER BY id ASC";
        $exe = $this->db->query($query)->result();
        if(count((array)$exe) > 0){
            return $exe;
        }else{
            return array();
        }
    }

    function NPScalc($data=null){
        $result=0;
        $promoter=0;
        $detractor=0;
        $passive = 0;
        $dtcount=explode(",",$data);
        if(count((array)$dtcount) > 0){
            foreach($dtcount as $d){
                if($d >= 9){
                    $promoter++;
                }else if($d<9 && $d>=7){
                    $passive++;
                }else{
                    $detractor++;
                }
            }
            $result=($promoter-$detractor)/count((array)$dtcount);

        }else{
           
        }

        return $result;
    }

    function add_evaluasi($data=null){
       $exe= $this->db->insert("_nps_jawab",$data);
       if($exe){
        return $this->db->insert_id();
       }else{
        return  $this->db->error();
       }
    }

    function get_jawab($filter=null){
        $this->db->select('*')
                 ->from('_nps_jawab')
                 ->where($filter)
                 ->limit(1);
        $exe=$this->db->get()->result();
        if($exe > 0){ 
            return $exe;
        }else{
            return array();
        }
    }


    function check_member($cr_id=null,$member_id=null){
        $sql = "SELECT * 
		FROM _classroom_member a, _classroom b, _member c, _group d 
		WHERE a.cr_id = b.cr_id 
			AND a.member_id = c.member_id 
			AND c.group_id = d.group_id 
			AND a.cr_id = '".$cr_id."' 
			AND a.member_id = '".$member_id."' 
		ORDER BY crm_create_date DESC";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
}
