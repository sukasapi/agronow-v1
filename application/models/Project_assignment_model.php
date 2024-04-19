<?php
/**
 * Created by VSCODE.
 * User: sukasapi
 * Date: 26/01/23
 * Time: 10:30
 * @property CI_DB_query_builder db
 */

class Project_assignment_model extends CI_Model
{

    public function get_personelgroup($idgrup=null){
        if(is_null($idgrup)){
            $sql="SELECT member_id,member_name,member_nip,member_jabatan FROM _member  WHERE member_status='active' ORDER BY member_name";
        }else{
            $sql="SELECT member_id,member_name,member_nip,member_jabatan FROM _member  WHERE group_id='".$idgrup."' AND member_status='active' ORDER BY member_name";
        }
      
       $query=$this->db->query($sql);
      if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }

    public function get_membercr($idcr=null){
        $sql="SELECT m.member_id,m.member_name,m.member_nip,m.member_jabatan 
              FROM _member as m
              JOIN _classroom_member as cm
              ON cm.member_id=m.member_id
              WHERE cm.cr_id='".$idcr."'  ORDER BY m.member_name";
       $query=$this->db->query($sql);
      if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }

    public function get_palist($idatasan=null){
       $query="SELECT *, m.member_name as nama FROM _project_assignment as p
               JOIN _member as m ON m.member_id=p.member_id
               JOIN _classroom as c ON c.cr_id=p.cr_id
               WHERE p.atasan_id='".$idatasan."' ORDER BY m.member_name ASC";
        $query=$this->db->query($query);
        if($this->db->count_all_results() > 0 ){
            return $query->result();
        }else{
            return array();
        }
    }

    public function get_pa($paid=null){
        $query="SELECT *, m.member_name as nama,a.member_name as nama_atasan FROM _project_assignment as p
                JOIN _member as m ON m.member_id=p.member_id
                JOIN _classroom as c ON c.cr_id=p.cr_id
                JOIN _group as g ON g.group_id=m.group_id
                LEFT JOIN _member as a ON a.member_id=p.atasan_id
                WHERE p.pa_id='".$paid."' ORDER BY m.member_name ASC";
         $query=$this->db->query($query);
         if($this->db->count_all_results() > 0 ){
             return $query->result();
         }else{
             return array();
         }
     }

     public function getpa_all(){
        $this->db->select('*, m.member_name as nama, a.member_name as nama_atasan')
        ->from('_project_assignment as p')
        ->join('_member as m','m.member_id=p.member_id')
        ->join('_classroom as c','c.cr_id=p.cr_id')
        ->join('_group as g','g.group_id=m.group_id')
        ->join('_member as a','a.member_id=p.atasan_id')
        ->order_by('c.cr_name');
        $query=$this->db->get();
         if($this->db->count_all_results() > 0 ){
             return $query->result();
         }else{
             return array();
         }
     }
     public function getpa_parameter($param=null){
        $this->db->select('*, m.member_name as nama, a.member_name as nama_atasan')
        ->from('_project_assignment as p')
        ->join('_member as m','m.member_id=p.member_id')
        ->join('_classroom as c','c.cr_id=p.cr_id')
        ->join('_group as g','g.group_id=m.group_id')
        ->join('_member as a','a.member_id=p.atasan_id','LEFT')
        ->where($param)
        ->order_by('c.cr_name');
        $query=$this->db->get();
        $sql=$this->db->last_query();
         if($this->db->count_all_results() > 0 ){
             return $query->result();
         }else{
             return array();
         }
     }

     public function getpa_paramtes($param=null){
        $this->db->select('*, m.member_name as nama, a.member_name as nama_atasan')
        ->from('_project_assignment as p')
        ->join('_member as m','m.member_id=p.member_id')
        ->join('_classroom as c','c.cr_id=p.cr_id')
        ->join('_group as g','g.group_id=m.group_id')
        ->join('_member as a','a.member_id=p.atasan_id','LEFT')
        ->where($param)
        ->order_by('c.cr_name');
        $query=$this->db->get();
        $sql=$this->db->last_query();
         if($this->db->count_all_results() > 0 ){
             return $sql;//$query->result();
         }else{
             return array();
         }
     }


    public function get_pabyparam($param=null){
        $this->db->select('*')
                 ->from('_project_assignment')
                 ->where($param);
        $query=$this->db->get();
        // return $this->db->last_query();
    	if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
       
    }


    public function getdetail_pabyparam($param=null){
        $this->db->select('*')
                 ->from('_project_assignment_detail')
                 ->where($param)
                 ->order_by('pad_id','ASC');
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }


    public function insert_pa($data=null){
        $adddata=array(
                    "cr_id"=>$data['cr_id'],
                    "member_id"=>$data['member_id'],
                    "pa_date_create"=>date('Y-m-d H:i:s'),
                    "pa_status"=>"open");
        $this->db->insert("_project_assignment",$adddata);
       // return $this->db->last_query();
        $insert_id = $this->db->insert_id();
         return $insert_id;
    }

    public function update_pa($data=null,$pa_id=null){
        $this->db->where("pa_id",$pa_id);
        $update=$this->db->update("_project_assignment",$data);
       
        if($update){
            return "ok";
        }else{
            return "gagal";
        }
    }

    public function insert_task($data=null){
        $adddata=array(
                    "pa_id"=>$data['pa'],
                    "pad_program"=>$data['pad_program'],
                    "pad_deliverable"=>$data['pad_deliverable'],
                    "pad_outcome"=>$data['pad_outcome'],
                    "pad_date_create"=>date('Y-m-d H:i:s')
                    );
        $this->db->insert("_project_assignment_detail",$adddata);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function upload_filepa($file=null,$paid=null){

        $dir=FCPATH."/media/project_assignment/".$paid."/";
        if(!is_dir($dir)) mkdir($dir, 0777, TRUE);
		$file_name = date("mY")."-".$paid.".pdf";
		$config['upload_path']          = $dir;
		$config['allowed_types']        = 'pdf';
		$config['file_name']            = $file_name;
		$config['overwrite']            = true;
		$config['max_size']             = 5120; // 1MB
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            
           return "file tidak sesuai ketentuan";
        }else{
            ///update filepath pa
            $filepath=$paid."/".$file_name;
            $this->db->where('pa_id',$paid);
            $dataupdate=array('pa_file'=>$filepath,'pa_date_change'=>date('Y-m-d H:i:s'));
            $this->db->update('_project_assignment',$dataupdate);
           return  "ok";
        }


    }

    public function updatetask($data=null,$pad_id=null){
        $this->db->where("pad_id",$pad_id);
        $update=$this->db->update("_project_assignment_detail",$data);
        if($update){
            return "ok";
        }else{
            return "gagal";
        }

    }

    public function updatelog_pa($data=null){
        $adddata=array(
            "pa_id"=>$data['pa_id'],
            "pap_progress"=>$data['pap_progress'],
            "pap_date"=>date('Y-m-d H:i:s')
            );
            $this->db->insert("_project_assignment_progress_log",$adddata);
            $insert_id = $this->db->insert_id();
            return $insert_id;
    }

    public function getpa_log($pa_id=null){
        $this->db->select('*')
                 ->from('_project_assignment_progress_log')
                 ->where('pa_id',$pa_id)
                 ->limit('2')
                 ->order_by('pap_date','DESC');
        $query=$this->db->get();
        if($this->db->count_all_results() > 0 ){
            return $query->result();
        }else{
            return array();
        }
    }

    public function getcr_byparam($param=null,$select=null){
        $this->db->select($select)
                 ->from('_classroom_member as cm')
                 ->join('_classroom as c','c.cr_id=cm.cr_id')
                 ->join('_member as m','m.member_id=cm.member_id')
                 ->where($param)
                 ->order_by("cm.cr_id","ASC");
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }
    
 //UPDATE HAPUS TASK LIST
    public function hapustask($pad_id=null){
        $filter=array('pad_id'=>$pad_id);
        ///hapus task 
        $this->db->delete('_project_assignment_detail',$filter);
        $q2="ALTER TABLE _project_assignment_detail  AUTO_INCREMENT=0";
        $this->db->query($q2);

        return "ok";
    }
    
}