<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Classroom_evaluasi_model extends CI_Model {



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

    // MASTER DATA EVALUASI //
    function getall_evaluasi(){
        $this->db->select('*')
                 ->from('_nps_soal')
                 ->order_by('create_date','DESC');
        $q=$this->db->get()->result();
        if(count((array)$q) > 0){
            return $q;
        }else{
            return array();
        }
    }

    function get_evaluasi($filter=null){
        $this->db->select('*')
                 ->from('_nps_soal')
                 ->where($filter) 
                 ->order_by('tipe','ASC');
        $q=$this->db->get()->result();
        if(count((array)$q) > 0){
            return $q;
        }else{
            return array();
        }
    }

    function get_evaluasi_result($filter=null){
        $this->db->select('*')
                 ->from('_nps_set_soal as ns')
                 ->join('_nps_jawab as nj','nj.set_id=ns.id','left')
                 ->join('_classroom as c','c.cr_id=ns.cr_id','left')
                 ->where($filter) 
                 ->order_by('ns.id','ASC');
        $q=$this->db->get()->result();
        $sql = $this->db->last_query();
        return $sql;
        if(count((array)$q) > 0){
            return $sql;
        }else{
            return "";
        }
    }


    function get_jawab($filter=null){
        $this->db->select('*')
                 ->from('_nps_jawab')
                 ->where($filter);
        $exe=$this->db->get()->result();
        $sql=$this->db->last_query();
        if(count((array)$exe)>0){
            return $exe;
        }else{
            return array();
        }
    }

    function add_evaluasi($data=null){
        $dinput=array();
        $res=0;
        //cek jika pertanyaan dan kategori sudah ada
        $jenis=isset($data['jenis']) && $data['jenis']!=""?$data['jenis']:"";
        $filter=array("tipe"=>$data['tipe'],"soal"=>$data['soal'],"jenis"=>$jenis);
        $this->db->select('count(*) as ada')
                 ->from('_nps_soal')
                 ->where($filter)
                 ->limit(1);
        $q=$this->db->get()->row('ada');
        if($q > 0){
           $res='0';
        }else{
            $input=array("tipe"=>$data['tipe'],"soal"=>$data['soal'],"status"=>'1',"create_by"=>$_SESSION['id'],"jenis"=>$jenis);
            $qadd=$this->db->insert('_nps_soal',$input);
            if($qadd){
                $res=$this->db->insert_id();
            }else{
                $res='0';
            }
        }
        return $res;
        
    }

    function edit_evaluasi($data=null){
       
        $res=0;
        //cek jika pertanyaan dan kategori sudah ada
        $jenis=isset($data['jenis']) && $data['jenis']!=""?$data['jenis']:"";
        $filter=array("id"=>$_POST['evaluasi']);
        $this->db->select('count(*) as ada')
                 ->from('_nps_soal')
                 ->where($filter)
                 ->limit(1);
        $q=$this->db->get()->row('ada');
        if($q <= 0){
           $res='0';
        }else{
            $input=array("tipe"=>$data['tipe'],"soal"=>$data['soal'],"create_by"=>$_SESSION['id'],"jenis"=>$jenis);
            $filter =array("id"=>$data['evaluasi']);
            $this->db->where($filter);
            $qadd=$this->db->update('_nps_soal',$input);
            $sql=$this->db->last_query();
            if($qadd){
                $res=$data['evaluasi'];
            }else{
                $res='0';
            }
        }
        return $res;
        
    }

    function hapus_evaluasi($data=null){
        $res=0;
        $query="";
        //cek jika pertanyaan dan kategori sudah ada
        $filter=array("id"=>$data['evaluasi']);
        $this->db->select('status')
                 ->from('_nps_soal')
                 ->where($filter)
                 ->limit(1);
        $q=$this->db->get()->row('status');
        if($q > 0){
            $input=array("status"=>'0');
            $filter =array("id"=>$data['evaluasi']);
            $this->db->where($filter);
            $qadd=$this->db->update('_nps_soal',$input);
            $query=$this->db->last_query();
            if($qadd){
                $res=$data['evaluasi'];
            }else{
                $res='0';
            }
        }else{
            $input=array("status"=>'1');
            $filter =array("id"=>$data['evaluasi']);
            $this->db->where($filter);
            $qadd=$this->db->update('_nps_soal',$input);
            $query=$this->db->last_query();
            if($qadd){
                $res=$data['evaluasi'];
            }else{
                $res='0';
            }
        }
        return $res;
        
    }

    function set_soalbyType($tipe=null,$jenis=null){
        
        $filter=array("tipe"=>$tipe,"jenis"=> $jenis);
        $soal=$this->get_evaluasi($filter);
        $setsoal="";
        foreach($soal as $s){
            $setsoal.=$s->id.",";
        }

        return rtrim($setsoal,",");
    }

    function cek_setsoal($filter=null){
        $this->db->select('*')
                 ->from('_nps_set_soal')
                 ->where($filter);
        $exe=$this->db->get()->result();
        if(count((array)$exe) > 0){
            return $exe;
        }else{
            return array();
        }
    }

    function add_setsoal($data=null){
        $cr_id=$data['cr_id'];
        $result="0";
        switch($data['jenis']){ 
            case 'penyelenggaraan':
                //cek
                $filter=array("cr_id"=>$cr_id,"tipe"=>"penyelenggaraan","status"=>"1","jenis"=>$data['tipe']);
            break;
            case 'sarana':
                $filter=array("cr_id"=>$cr_id,"jenis"=>"sarana","status"=>"1","jenis"=>$data['tipe']);
            break;
            case 'narasumber':
                $filter=array("cr_id"=>$cr_id,"jenis"=>"narasumber","pengajar"=>$data['pengajar'],"status"=>"1","tipe"=>$data['tipe']);
            break;
            case 'external':
                $filter=array("cr_id"=>$cr_id,"jenis"=>"external","status"=>"1","tipe"=>$data['tipe']);
            break;
        }
        $cek=$this->cek_setsoal($filter);
        if(count((array)$cek)>0){
            
        }else{
            $this->db->insert("_nps_set_soal",$data);
            $result = $this->db->insert_id();
        }

        return $result;
        
    }

    function edit_setsoal($data=null){
        $cr_id=$data['cr_id'];
        $result="0";
        $pengajar=isset($data['pengajar']) && $data['pengajar']!=""?$data['pengajar']:"";
        $setsoal=isset($data['setsoal']) && $data['setsoal']!=""?$data['setsoal']:"-";
        switch($data['jenis']){ 
            case 'penyelenggaraan':
                //cek jika data sudah ada, maka tinggal update, jika tidak maka insert
                $filter=array("cr_id"=>$cr_id,"jenis"=>"penyelenggaraan","tipe"=>$data['tipe']);
                $ada=$this->cek_setsoal($filter);
                if(count((array)$ada)> 0){
                    $dtupdate=array("status"=>$data['status'],"tipe"=>$data['tipe'],"setsoal"=>$setsoal);
                    $this->db->where("id",$ada[0]->id);
                    $exe=$this->db->update("_nps_set_soal",$dtupdate);
                    $result=$ada[0]->id;
                }else{
                    $dtinsert=array("cr_id"=>$data['cr_id'],"jenis"=>"penyelenggaraan","status"=>$data['status'],"setsoal"=>$setsoal,"tipe"=>$data['tipe']);
                    $result=$this->add_setsoal($dtinsert);
                }
            break;
            case 'sarana':
                //cek jika data sudah ada, maka tinggal update, jika tidak maka insert
                $filter=array("cr_id"=>$cr_id,"jenis"=>"sarana","tipe"=>$data['tipe']);
                $ada=$this->cek_setsoal($filter);
                if(count((array)$ada)> 0){
                    $dtupdate=array("status"=>$data['status'],"tipe"=>$data['tipe'],"setsoal"=>$setsoal);
                    $this->db->where($filter);
                    $exe=$this->db->update("_nps_set_soal",$dtupdate);
                    $result=$ada[0]->id;
                }else{
                    $dtinsert=array("cr_id"=>$data['cr_id'],"jenis"=>"sarana","status"=>$data['status'],"setsoal"=>$setsoal,"tipe"=>$data['tipe']);
                    $result=$this->add_setsoal($dtinsert);
                }
            break;
            case 'narasumber':
                if(isset($data['pengajar'])){
                    $filter=array("cr_id"=>$cr_id,"jenis"=>"narasumber","pengajar"=>$data['pengajar'],"tipe"=>$data['tipe']);
                    $dtupdate=array("cr_id"=>$cr_id,"jenis"=>"narasumber","pengajar"=>$data['pengajar'],"status"=>$data['status'],"setsoal"=>$setsoal,"tipe"=>$data['tipe']);
                }else{
                    $filter=array("cr_id"=>$cr_id,"jenis"=>"narasumber","tipe"=>$data['tipe']);
                    $dtupdate=array("cr_id"=>$cr_id,"jenis"=>"narasumber","status"=>$data['status'],"setsoal"=>$setsoal,"tipe"=>$data['tipe']);
                }
               
                $ada=$this->cek_setsoal($filter);
                if(count((array)$ada) > 0){
                    foreach($ada as $a){
                        $idevaluasi=$a->id;
                        $this->db->where("id",$idevaluasi);
                        $this->db->update("_nps_set_soal",$dtupdate);
                        $result=$ada[0]->id."<br>";
                    }
                
                }else{
                  $this->db->insert("_nps_set_soal",$dtupdate);
                  $result=$this->db->insert_id();
                }
            break;
            case 'eksternal':
                //cek jika data sudah ada, maka tinggal update, jika tidak maka insert
                $filter=array("cr_id"=>$cr_id,"jenis"=>"external");
                $ada=$this->cek_setsoal($filter);
                if(count((array)$ada)> 0){
                    $dtupdate=array("status"=>$data['status']);
                    $this->db->where($filter);
                    $exe=$this->db->update("_nps_set_soal",$dtupdate);
                    $result=$ada[0]->id;
                }else{
                    $dtinsert=array("cr_id"=>$data['cr_id'],"jenis"=>"external","status"=>$data['status'],"setsoal"=>$setsoal);
                    $result=$this->add_setsoal($dtinsert);
                }
                $filter=array("cr_id"=>$cr_id,"jenis"=>"external","status"=>"1");
            break;
        }
     
        return $result;
    }

    function hapus_setsoal($data=null){
        $res=0;
        $query="";
        //cek jika pertanyaan dan kategori sudah ada
        $filter=array("id"=>$data['evaluasi']);
        $this->db->select('status')
                 ->from('_nps_set_soal')
                 ->where($filter)
                 ->limit(1);
        $q=$this->db->get()->row('status');
        if($q > 0){ 
            $input=array("status"=>'0');
            $filter =array("id"=>$data['evaluasi']);
            $this->db->where($filter);
            $qadd=$this->db->update('_nps_set_soal',$input);
            $query=$this->db->last_query();
            if($qadd){
                $res=$data['evaluasi'];
            }else{
                $res='0';
            }
        }else{
            $input=array("status"=>'1');
            $filter =array("id"=>$data['evaluasi']);
            $this->db->where($filter);
            $qadd=$this->db->update('_nps_set_soal',$input);
            $query=$this->db->last_query();
            if($qadd){
                $res=$data['evaluasi'];
            }else{
                $res='0';
            }
        }
        return $res;
        
    }

    function hapus_setsoalbyParam($data=null){
        $res=0;
        $query="";
        //cek jika pertanyaan dan kategori sudah ada
        $filter=$data;
        $input=array("status"=>'0');
        $this->db->where($filter);
        $qupd=$this->db->update('_nps_set_soal',$input);
        $query=$this->db->last_query(); 
        if($qupd){
            $res=$query;
        }else{
            $res=0;
        }

        return $res;
        
    }


    function calc_nps($data=null){
        $result="";
        $this->db->select("AVG(score) as nilai")
        ->where($data)
        ->from("_nps_jawab");

        $exe=$this->db->get()->result();
        $sql=$this->db->last_query();
        if(count((array)$exe) > 0){
        $result=$exe;
        }else{
        $result=0;
        }
     

        return $result;

    }





}

