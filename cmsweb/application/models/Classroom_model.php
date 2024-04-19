<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Classroom_model extends CI_Model {



    public function __construct(){

        parent::__construct(); //inherit dari parent

        $this->load->database();

    }





    /* DATATABLE BEGIN */

    var $table = '_classroom';

    var $column_order = array('_classroom.cr_id','_category.cat_name','_classroom.cr_name','_classroom.cr_date_start','_classroom.cr_date_detail',NULL,NULL,'_classroom.cr_price','_user.user_name'); //set column field database for datatable orderable

    var $column_search = array('_classroom.cr_name','_category.cat_name'); //set column field database for datatable searchable

    var $order = array('id' => 'desc'); // default order





    private function _get_datatables_query()

    {



        $has_access_view = has_access('classroom.view.own',FALSE);

        $has_access_view_own = has_access('classroom.view',FALSE);

        $id_petugas = user_id();



        //add custom filter here

        if($this->input->get('cr_type')){

            $this->db->where('_classroom.cr_type',$this->input->get('cr_type'));

        }



        if($has_access_view AND !$has_access_view_own){

            $this->db->where('_classroom.id_petugas', $id_petugas);

        }



        //Filter By Admin Access Klien. Tidak punya klien = Superadmin

        if (my_klien()){

            $this->db->where('_classroom.id_klien', my_klien());

        }



        $this->db->select('_classroom.*,_category.cat_name,_category.cat_id, _user.user_name');



        $this->db->from($this->table);







        $this->db->join('_category','_category.cat_id=_classroom.cat_id','LEFT');

        $this->db->join('_user','_user.user_id=_classroom.id_petugas','LEFT');











        $i = 0;



        foreach ($this->column_search as $item) // loop column

        {

            if($_POST['search']['value']) // if datatable send POST for search

            {

                if($i===0) // first loop

                {

                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.

                    $this->db->like($item, $_POST['search']['value']);

                }

                else

                {

                    $this->db->or_like($item, $_POST['search']['value']);

                }



                if(count($this->column_search) - 1 == $i) //last loop

                    $this->db->group_end(); //close bracket

            }

            $i++;

        }



        if(isset($_POST['order'])) // here order processing

        {

            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

        }

        else if(isset($this->order))

        {

            $order = $this->order;

            $this->db->order_by(key($order), $order[key($order)]);

        }

    }



    public function get_datatables(){

        $this->_get_datatables_query();

        if($_POST['length'] != -1)

            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();

        return $query->result();

    }



    public function count_filtered(){

        $this->_get_datatables_query();

        $query = $this->db->get();

        return $query->num_rows();

    }



    public function count_all(){

        $this->db->from($this->table);

        return $this->db->count_all_results();

    }

    /* DATABLE END*/







    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){

        $this->db->select('SQL_CALC_FOUND_ROWS _classroom.*,_category.cat_name,_category.cat_id',FALSE);

        $this->db->from('_classroom');

        $this->db->join('_category','_category.cat_id=_classroom.cat_id','LEFT');



        if (isset($param_query['is_price'])){

            if ($param_query['is_price'] == 1){

                $this->db->where('cr_price > 0');

            }

        }

        

        $query = $this->db->get();

        $result['data']     = $query->result_array();

        $result['count']    = $query->num_rows();

        $result['count_all']= $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;



        if($query->num_rows() > 0){ return $result; } else { return FALSE; }

    }





    function get($id){

        $this->db->select('_classroom.*,_category.cat_name,_category.cat_id, _user.user_name');

        $this->db->from('_classroom');

        $this->db->join('_category','_category.cat_id=_classroom.cat_id','LEFT');

        $this->db->join('_user','_user.user_id=_classroom.id_petugas','LEFT');



        $this->db->where('cr_id', $id);





        $query      = $this->db->get();

        $result     = $query->result_array();



        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }

    }



    function gets($ids){

        $this->db->select('_classroom.*');

        $this->db->from('_classroom');



        $this->db->where_in('cr_id', $ids);



        $query      = $this->db->get();

        $result     = $query->result_array();



        if($query->num_rows() > 0){ return $result; } else { return FALSE; }

    }





    function search($q,$limit=NULL){

        $this->db->select('_classroom.*,classroom_type.name as classroom_type_name,project.name as project_name,location.name as location_name,

        parent.classroom_number as parentclassroom_number');

        $this->db->from('_classroom');



        $this->db->like('_classroom.classroom_number',$q);



        if ($limit==NULL) {

            $this->db->limit(50);

        }else{

            $this->db->limit($limit);

        }


 


        $query = $this->db->get();

        $result  = $query->result_array();



        if($query->num_rows() > 0){ return $result; } else { return FALSE; }

    }



    function insert($data){ 

        $this->db->insert('_classroom', $data);

        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;

    }



    function update($data){

        $id = $data['cr_id'];

        unset($data['cr_id']);

        $this->db->where('cr_id', $id);

        $this->db->update('_classroom' ,$data);



        return $this->db->affected_rows() > 0 ? TRUE : FALSE;

    }
 

    function delete($cr_id){

        $this->db->where('cr_id',$cr_id);

        $this->db->delete('_classroom');



        return $this->db->affected_rows() > 0 ? TRUE : FALSE;

    }


    function get_module_assignment($condition=null){
        $this->db->select('*')
                 ->from('_classroom_modul_member')
                 ->where($condition)
                 ->order_by('urut_modul','ASC');
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }

    function get_module_assignment2($condition=null){
        $this->db->select('*')
                 ->from('_classroom_modul_member as cmm')
                 ->where($condition)
                 ->join('_classroom as c','c.cr_id=cmm.classroom_id')
                 ->join('_member as m','m.member_id=cmm.member_id')
                 ->order_by('cmm.urut_modul','ASC');
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array(); 
        }
    }
    
    function update_pk($filter=null,$ispk=null){
        $this->db->where($filter);
        $query=$this->db->update('_classroom_member',$ispk);
        if($query){
            return "ok";
        }else{
            return $this->db->error();
        }
    }
    

    /* Fungsi untuk mengambil post test peserta kelas */
    // Auth : KDE
    // Date : 23.11.2023
    /* End Fungsi */

    function get_posttes($filter=null){
        $query3 ="SELECT m.member_name,m.member_nip, g.group_name,c.cr_name,c.cr_id,cm.crm_step,c.cr_date_start as tanggal_kelas, g.group_name  
        FROM _classroom_member as cm 
        LEFT JOIN _classroom as c ON c.cr_id=cm.cr_id
        LEFT JOIN _member as m ON m.member_id=cm.member_id
        LEFT JOIN _group as g ON g.group_id=m.group_id
        ".$filter ;
        $exe = $this->db->query($query3);
        $row = $exe->result_array();
        $res=array();
        foreach ($row as $r){
        $crm_step_json = $r['crm_step'];
        $result = json_decode($r['crm_step'],TRUE);
        //pre test
        if (isset($result['PT']['ptScore']) AND $result['PT']['ptScore']){
            $ptScore = explode('-',$result['PT']['ptScore']);
        }else{
            $ptScore = array('','','','');
        }
        //post test
        if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){
            $ctScore = explode('-',$result['CT']['ctScore']);
        }else{
            $ctScore = array('','','','');
        }

        //feedback
        if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){
            $ctScore = explode('-',$result['CT']['ctScore']);
        }else{
            $ctScore = array('','','','');
        }
        $preendScore = $ptScore[2] ? str_replace('.',',',number_format($ptScore[2]/$ptScore[1]*100,1))  : '';
        $endScore = $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '';
       // echo $r['cr_id'].";".$r['member_nip'].";".$r['member_name'].";".$r['group_name'].";".$r['cr_name'].";".date('d-m-Y',strtotime($r['tanggal_kelas'])).";".$ctScore[1].";".$ctScore[2].";".$endScore."<br>";
        $res[]=array("cr_id"=>$r['cr_id'],"nip"=>$r['member_nip'],"nama"=>$r['member_name'],"perusahaan"=>$r['group_name'],"kelas"=>$r['cr_name'],"tanggal"=>date('d-m-Y',strtotime($r['tanggal_kelas'])),"presoal"=>$ptScore[1],"prebenar"=>$ptScore[2],"prescore"=>$preendScore,"soal"=>$ctScore[1],"benar"=>$ctScore[2],"score"=>$endScore);
        
        }
        return $res; 
    }


    /* Fungsi untuk mengambil post test peserta kelas pre dan post tes */
    // Auth : KDE
    // Date : 20.11.2023
    /* End Fungsi */
    function get_classresult($filter=null){
        $query3 ="SELECT m.member_name,m.member_id,m.member_nip, g.group_name,c.cr_name,c.cr_id,cm.crm_step,c.cr_date_start as tanggal_kelas, g.group_name  
        FROM _classroom_member as cm 
        LEFT JOIN _classroom as c ON c.cr_id=cm.cr_id
        LEFT JOIN _member as m ON m.member_id=cm.member_id
        LEFT JOIN _group as g ON g.group_id=m.group_id
        ".$filter ;
        $exe = $this->db->query($query3);
        $row = $exe->result_array();
        $res=array();
        foreach ($row as $r){
            $crm_step_json = $r['crm_step'];
            $result = json_decode($r['crm_step'],TRUE);
            //pre test
            if (isset($result['PT']['ptScore']) AND $result['PT']['ptScore']){
                $ptScore = explode('-',$result['PT']['ptScore']);
            }else{
                $ptScore = array('','','','');
            }
            //post test
            if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){
                $ctScore = explode('-',$result['CT']['ctScore']);
            }else{
                $ctScore = array('','','','');
            } 

            $preendScore = $ptScore[2] ? str_replace('.',',',number_format($ptScore[2]/$ptScore[1]*100,1))  : '';
            $endScore = $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '';
            $res[$r['cr_name']]["peserta"][]=array("cr_id"=>$r['cr_id'],"member_id"=>$r['member_id'],"nip"=>$r['member_nip'],"nama"=>$r['member_name'],"perusahaan"=>$r['group_name']);
            $res[$r['cr_name']]["score"][]=array("pre"=>$preendScore,"post"=>$endScore);
        }
        return $res; 

    }


    /* Fungsi untuk mengambil post test peserta kelas pre dan post tes */
    // Auth : KDE
    // Date : 26.11.2023
    /* End Fungsi */

    function get_kelas($filter=null){
        $this->db->select('*')
                 ->from('_classroom as c')
                 ->where($filter);
        $query =$this->db->get();
        $syn=$this->db->last_query();
        if(count((array)$query->result()) > 0){
            $res= $query->result();
        }else{
            $res=array();
        } 
        return $res; 
    }

  /* Fungsi untuk data kelas dan jumlah peserta */
    // Auth : KDE
    // Date : 25.03.2023

    function get_kelasmember($filter=null){
        $this->db->select('*')
                 ->from('_classroom as c');
    }

    /* End Fungsi */

    /* Fungsi untuk mengambil nilai Feedback */
    // Auth : KDE
    // Date : 18.04.2024
    /* End Fungsi */
    function get_feedback($filter=null){
        $queryfeedback ="SELECT m.member_name,m.member_id,m.member_nip, g.group_name,c.cr_name,c.cr_id,cm.crm_step,cm.crm_fb as feedback,c.cr_date_start as tanggal_kelas, g.group_name  
        FROM _classroom_member as cm 
        LEFT JOIN _classroom as c ON c.cr_id=cm.cr_id
        LEFT JOIN _member as m ON m.member_id=cm.member_id
        LEFT JOIN _group as g ON g.group_id=m.group_id
        ".$filter;

        $exe = $this->db->query($queryfeedback);
        $row = $exe->result_array();
        $res=array();
        foreach ($row as $r){
            $result = json_decode($r['feedback'],TRUE);
            if($r['cr_id']!="" && $r['member_id']!=""){
                $res[]=array("cr_id"=>$r['cr_id'],"member_id"=>$r['member_id'],"nip"=>$r['member_nip'],"nama"=>$r['member_name'],"perusahaan"=>$r['group_name'],"kelas"=>$r['cr_name'],"tanggal"=>date('d-m-Y',strtotime($r['tanggal_kelas'])),"feedback"=>$result);
            }else{

            }
        } 

        return $res;  
    }

    function get_feedbackvalue($filter=null){
        $queryfeedback ="SELECT crm_step   
        FROM _classroom_member 
        ".$filter;
        $exe = $this->db->query($queryfeedback);
        $row = $exe->result_array();
        $res=array();
        foreach ($row as $r){
            $crm_step_json = $r['crm_step'];
            $result = json_decode($r['crm_step'],TRUE);
            $res[] = $result['MP'];
        }
      
        return $res;

    }

}

