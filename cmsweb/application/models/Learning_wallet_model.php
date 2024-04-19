<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Learning_wallet_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_learning_wallet_classroom';
    var $column_order = array('_learning_wallet_classroom.id','_learning_wallet_classroom.kode','_learning_wallet_classroom.nama','_learning_wallet_classroom.tgl_mulai','_learning_wallet_classroom.tgl_selesai','_learning_wallet_classroom.status'); //set column field database for datatable orderable
    var $column_search = array('_learning_wallet_classroom.nama'); //set column field database for datatable searchable
    var $order = array('_learning_wallet_classroom.id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_group.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_learning_wallet_classroom.*');

        $this->db->from($this->table);

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
	
	function getDetailPelatihan($kategori,$params) {
		$hasil = null;
		
		if($kategori=="kode_nama") {
			$id = (int) $params['id'];
			
			if(!empty($id)) {
				$sql = "select kode, nama from _learning_wallet_classroom where id='".$id."' ";
				$res = $this->db->query($sql);
				$row = $res->result_array();
				$hasil = "[".$row[0]['kode']."] ".$row[0]['nama'];
			}
		}
		else if($kategori=="detail") {
			$id = (int) $params['id'];
			
			if(!empty($id)) {
				$sql = "select * from _learning_wallet_classroom where id='".$id."' ";
				$res = $this->db->query($sql);
				$row = $res->result_array();
				$hasil = $row[0];
			}
		}
		
		return $hasil;
	}
	
	function getNIKPesertaPelatihan($id_lw_classroom) {
		$hasil = null;
		
		$sql =
			"select m.member_id, m.member_nip
			 from _learning_wallet_pengajuan p, _member m
			 where p.id_member=m.member_id and p.id_lw_classroom='".$id_lw_classroom."' and p.kode_status_current='40' and p.status='aktif'
			 order by m.group_id, m.member_name ";
		$res = $this->db->query($sql);
		$hasil = $res->result_array();
		
		return $hasil;
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
	
	public function get_noWA($idm=null){
        $this->db->select('no_wa')
                 ->from('_learning_wallet_pengajuan')
                 ->where('id_member',$idm)
                 ->limit(1);
         $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            $res=$query->result();
            return $res;
        }else{
            return array();
        }
        
    }

       //author 	: KDW
	//date 		: 15.05.2023
	//function  : tambah kelas agrowallet

    public function create_kelas($data=null){
        $query=$this->db->insert("_learning_wallet_classroom",$data);
        $syn=$this->db->last_query();
        if($query){
            $insert_id = $this->db->insert_id();
        }else{
            $insert_id = "0";
        }
        return $insert_id;//$syn;

    }

    public function upload_cover($file=null,$wlid=null){
        $this->load->library('image_lib');
        $dir=FCPATH."/media/image_agrowallet/";
        if(!is_dir($dir)) mkdir($dir, 0777, TRUE);
		$file_name = $wlid.".jpg";
		$config['upload_path']          = $dir;
		$config['allowed_types']        = 'jpg|png';
		$config['file_name']            = $file_name;
		$config['overwrite']            = true;
		$config['max_size']             = 5120; // 1MB
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('cover')) {
            ///resize
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $configer = array(
                'image_library' => 'gd2',
                'source_image' => $upload_data['full_path'],
                'create_thumb' => FALSE,//tell the CI do not create thumbnail on image
                'maintain_ratio' => TRUE,
                'quality' => '80%', //tell CI to reduce the image quality and affect the image size
                'width' => 150,//new size of image
                'height' => 150,//new size of image
            );
            $this->image_lib->clear();
            $this->image_lib->initialize($configer);
            $this->image_lib->resize();

            $this->db->where('id',$wlid);
            $dataupdate=array('berkas'=>$file_name);
            $this->db->update('_learning_wallet_classroom',$dataupdate);
            return  "ok";
        }else{
            return $this->upload->display_errors();
       
        }


    }

    function get_sekolah(){
        $this->db->select("*")
                 ->from("_learning_wallet_sekolah")
                 ->where("status","active")
                 ->order_by("id") ;
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }

    }
    function get_klien(){
        $this->db->select("*")
        ->from("_klien")
        ->where("status","active")
        ->order_by("id") ;
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
        return $query->result();
        }else{
        return array();
        }
    }

     //author 	: KDW
	//date 		: 23.05.2023
	//function  : view kelas untuk edit kelas agrowallet
    function get_penyelenggara(){
        $this->db->select("*")
        ->from("_learning_wallet_penyelenggara")
        ->where("status","aktif")
        ->order_by("id") ;
        $query=$this->db->get();
        if($this->db->count_all_results() > 0){
            return $query->result();
        }else{
            return array();
        }
    }
    function get_kelas_lw($param=null){
       
            $this->db->select("*,lwc.kode as kodekelas,lwc.id as idkelas,lwc.nama as nama_kelas,lwp.nama as nama_penyelenggara,lws.nama as nama_sekolah,kl.nama as nama_klien,
                                lwc.tgl_mulai as tgl_mulai,lwc.tgl_selesai as tgl_selesai,lwc.status as status")
            ->from("_learning_wallet_classroom as lwc")
            ->join("_learning_wallet_sekolah as lws","lws.id=lwc.id_sekolah")
            ->join("_learning_wallet_penyelenggara as lwp","lwp.id=lwc.id_penyelenggara")
            ->join("_klien as kl","kl.id=lwc.id_klien")
            ->where($param)
            ->order_by("lwc.id","ASC");
            $sql=$this->db->get();
        if($this->db->count_all_results() > 0){
            $res=$sql->result();
            return $res;
        }else{
            return array();
        }
     
    }

    public function edit_kelas($data=null,$where=null){
        $this->db->where($where);
        $query=$this->db->update("_learning_wallet_classroom",$data);
        if($query){
            $update_id = $this->db->affected_rows('id');
        }else{
            $update_id = "0";
        }
        return $update_id;

    }

     //author 	: KDW
	//date 		: 25.05.2023
	//function  : Saldo pengajuan

    public function saldo_pengajuan($where=null){
        if(count((array)$where) > 0){
            $this->db->select("lwc.id_member as idm,m.member_name as nama,lwc.kode_status_current as stat_lw,m.member_nip as nip,g.group_name as entitas,SUM(lwc.harga) as pengajuan")
            ->from('_learning_wallet_pengajuan as lwc')
            ->join('_member as m','m.member_id=lwc.id_member')
            ->join('_group as g','g.group_id=m.group_id')
            ->where('(lwc.kode_status_current = 40 or lwc.kode_status_current=20)')
            ->where($where)
            ->group_by('lwc.id_member');
        }else{
            $this->db->select("lwc.id_member as idm,m.member_name as nama,lwc.kode_status_current as stat_lw,m.member_nip as nip,g.group_name as entitas,SUM(lwc.harga) as pengajuan")
            ->from('_learning_wallet_pengajuan as lwc')
            ->join('_member as m','m.member_id=lwc.id_member')
            ->join('_group as g','g.group_id=m.group_id')
            ->where('(lwc.kode_status_current = 40 or lwc.kode_status_current=20)')
            ->group_by('lwc.id_member');
        }
     
        $data=$this->db->get();
        if($this->db->count_all_results() > 0){
           return $data->result();
        }else{
           return array();
        }
    }

    //author 	: KDW
	//date 		: 26.05.2023
	//function  : Saldo modal

    public function saldo_modal($where=null){
        if(count((array)$where) > 0){
            $this->db->select("id_member,SUM(nominal) as saldo")
            ->from('_learning_wallet_konfig_group')
            ->where($where);

        }else{
            $this->db->select("id_member,SUM(nominal) as saldo")
            ->from('_learning_wallet_konfig_group');
           
        }
     
        $data=$this->db->get();
        $sql=$this->db->last_query();
        if($this->db->count_all_results() > 0){
           return $data->result();
        }else{
           return array();
        }
    }


    //author 	: KDW
	//date 		: 06.06.2023
	//function  : Saldo function


    function getmember(){
        $hasil = null;
		
		$sql =
			"select m.member_id, m.member_nip,m.member_name,g.group_id,g.group_name
			 from _member m
             JOIN _group as g ON g.group_id=m.group_id 
			 where m.member_status='active'
			 order by m.group_id, m.member_name ";
		$res = $this->db->query($sql);
		$hasil = $res->result_array();
		
		return $hasil;
    }

    function modal_list($where=null){
        $this->db->select("SUM(nominal) as saldo")
        ->from('_learning_wallet_konfig_group')
        ->where($where)
        ->group_by('id_member');
        $res=$this->db->get();

        return $res->result();
    }

    function pengajuan_list($where=null){
        $this->db->select("SUM(harga) as pengajuan")
        ->from('_learning_wallet_pengajuan')
        ->where($where)
        ->group_by('id_member');
        $res=$this->db->get();

        return $res->result();
    }
}
