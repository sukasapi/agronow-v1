<?php
/**
 * Created by VSCODE.
 * User: sukasapi
 * Date: 09/02/23
 * Time: 10:30
 * @property CI_DB_query_builder db
 */

class Project_assignment_model extends CI_Model
{

    public function get_personelgroup($idgrup=null){
        $sql="SELECT member_id,member_name,member_nip,member_jabatan FROM _member  WHERE group_id='".$idgrup."'  ORDER BY member_name";
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
                JOIN _member as a ON a.member_id=p.atasan_id
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
        ->join('_member as a','a.member_id=p.atasan_id')
        ->where($param)
        ->order_by('c.cr_name');
        $query=$this->db->get();
        $syn=$this->db->last_query();
         if($this->db->count_all_results() > 0 ){
             return $query->result();
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
                 ->where($param);
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }


    public function insert_pa($data=null){
        $adddata=array(
                    "cr_id"=>$data['cr'],
                    "member_id"=>$data['member'],
                    "pa_date_create"=>date('Y-m-d H:i:s'),
                    "pa_status"=>"open");
        $this->db->insert("_project_assignment",$adddata);
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
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            ///update filepath pa
            $filepath=$paid."/".$file_name;
            $this->db->where('pa_id',$paid);
            $dataupdate=array('pa_file'=>$filepath,'pa_date_change'=>date('Y-m-d H:i:s'));
            $this->db->update('_project_assignment',$dataupdate);
           return $this->upload->display_errors();
        }else{
        
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
    
}