<?php
/**
 * Created by VSCODE.
 * User: sukasapi
 * Date: 14/02/23
 * Time: 10:30
 * @property CI_DB_query_builder db
 */

class Module_assignment_model extends CI_Model
{

    public function get_module($id){}

   public function get_ma($filter=null){
        $this->db->select('*')
                 ->from('_classroom_modul_member')
                 ->where($filter);
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
      
   }
   

   public function add_ma($data=null){
        $query=$this->db->insert('_classroom_modul_member',$data);
        if($query){
            return $this->db->insert_id();
        }else{
            return $this->db->error();
        }
    }

    public function update_ma($data=null,$param=null){
        $this->db->where($param);
        $query=$this->db->update('_classroom_modul_member',$data);
        if($query){
            return "ok";
        }else{
            return $this->db->error();
        }
    }
    

    public function upload_ma($folder=null,$file=null){
        $dir=FCPATH."/media/module_assignment/".$folder."/";
        if(!is_dir($dir)) mkdir($dir, 0777, TRUE);
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
		$file_name = $file.$file_ext;
		$config['upload_path']          = $dir;
		$config['allowed_types']        = 'pdf|xlsx|xls|dox|docx|ppt|pptx';
		$config['file_name']            = $file_name;
		$config['overwrite']            = true;
		$config['max_size']             = 5120; // 1MB
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            ///update filepath pa
           return $this->upload->display_errors();
        }else{
        
           return  "ok";
        }
    }
    
}