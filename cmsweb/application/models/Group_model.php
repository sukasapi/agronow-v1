<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_group';
    var $column_order = array('_group.group_id','_group.group_name','_group.silsilah','_group.aghris_company_code','_klien.nama','count_member.total_member','_group.group_has_level','_group.group_portal','_group.group_status'); //set column field database for datatable orderable
    var $column_search = array('_group.group_name','_klien.nama'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_group.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_group.*,_klien.nama as klien_nama, count_member.total_member');

        $this->db->from($this->table);

        $this->db->join('_klien', '_klien.id = _group.id_klien', 'left');

        $sql_count_member = 'SELECT COUNT(*) as total_member, group_id from _member group by group_id';
        $this->db->join('( '.$sql_count_member.' ) as count_member','_group.group_id=count_member.group_id','LEFT');


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


    function search($q,$limit=NULL){
        $this->db->select('_group.*');
        $this->db->from('_group');

        $this->db->like('_group.group_name',$q);

        if ($limit==NULL) {
            $this->db->limit(50);
        }else{
            $this->db->limit($limit);
        }


        $query = $this->db->get();
        $result  = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){
        $this->db->select('SQL_CALC_FOUND_ROWS _group.*',FALSE);
        $this->db->from('_group');

        // Keyword By
        if ($keyword!=NULL) {
            if (is_array($param_query['keyword_by'])) {
                foreach ($param_query['keyword_by'] as $k => $v) {
                    $this->db->like($k,$v);
                }
            } else{
                $this->db->like($param_query['keyword_by'],$keyword);
            }
        }
		
		if($param_query['filter_non_aghris_only']=="1") {
			$this->db->group_start();
			$this->db->where('_group.aghris_company_code=""');
			$this->db->or_where('_group.aghris_company_code IS NULL');
			$this->db->group_end();
		}

        if (!empty($param_query['filter_active'])) {
            $this->db->where('_group.group_status',$param_query['filter_active']);
        }
		
		if (!empty($param_query['silsilah'])) {
			$this->db->group_start();
			$this->db->where('_group.silsilah="'.$param_query['silsilah'].'"');
			$this->db->or_where('_group.silsilah like "'.$param_query['silsilah'].'%"');
			$this->db->group_end();
        }

        if (isset($param_query['filter_klien'])) {
            $this->db->where('_group.id_klien',$param_query['filter_klien']);
        }

        $this->db->limit($limit,$offset);
        if (isset($param_query['sort'])) {
            $this->db->order_by($param_query['sort'],$param_query['sort_order']);
        }

        $query = $this->db->get();
		$result['data']     = $query->result_array();
        $result['count']    = $query->num_rows();
        $result['count_all']= $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function get($id){
        $this->db->select('_group.*, _klien.nama as klien_nama');
        $this->db->from('_group');

        $this->db->join('_klien', '_klien.id = _group.id_klien', 'left');

        $this->db->where('group_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_klien($id_klien){
        $this->db->select('_group.*, _klien.nama as klien_nama');
        $this->db->from('_group');

        $this->db->join('_klien', '_klien.id = _group.id_klien', 'left');

        $this->db->where('id_klien', $id_klien);
		
		$this->db->order_by('_group.group_name','asc');
		
        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function gets($ids){
        $this->db->select('_group.*');
        $this->db->from('_group');

        $this->db->where_in('group_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function insert($data){

        $this->db->insert('_group', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['group_id'];
        unset($data['group_id']);
        $this->db->where('group_id', $id);
        $this->db->update('_group' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($group_id){
        $this->db->where('group_id',$group_id);
        $this->db->delete('_group');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
	
	function select_group_by_code($company_code=""){
		// $sql = "SELECT * FROM _group WHERE aghris_company_code = '".$this->recData['aghris_company_code']."' ";
		$sql = "SELECT * FROM _group WHERE aghris_company_code like '%[".trim($company_code)."]%' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		if(empty($data[0])) {
			// masukkan ke kelompok aghris unknown code
			$sql = "SELECT * FROM _group WHERE group_id='34' ";
			$query = $this->db->query($sql);
			$data = $query->result_array();
		}
		
		return @$data[0];
    }
	
	function get_silsilah($group_id) {
		$group_id = (int) $group_id;
		$sql = "SELECT silsilah FROM _group WHERE group_id='".$group_id."' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		return @$data[0]['silsilah'];
	}
	
	function get_company_by_silsilah($silsilah) {
		$sql = "SELECT * FROM _group WHERE silsilah='".$silsilah."' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		
		return @$data[0];
	}
	
	function get_child_company($id_entitas,$mode) {
		$addSql = "";
		$id_entitas = (int) $id_entitas;
		$session_group_id = $this->session->userdata('group_id');
		
		// super admin?
		if(empty($session_group_id)) {
			// do nothing
		} else {
			if($mode=="self") { // get data self
				if($id_entitas!=$session_group_id) $addSql .= " 1=2 ";
			} else if($mode=="self_child") { // get data self dan child lv 1
				$silsilah = $this->get_silsilah($session_group_id);
				if(empty($silsilah)) {
					$addSql .= " 1=2 ";
				} else {
					$addSql .= " and (silsilah='".$silsilah."' or silsilah like '".$silsilah."%') ";
				}
			}
		}
		
		if($id_entitas>0) {
			$addSql .= " and group_id='".$id_entitas."' ";
		}
		
		$sql = "select group_id, group_name, silsilah, id_klien from _group where group_status='active' ".$addSql." order by silsilah, group_name ";
		$query = $this->db->query($sql);
		$arrH = $query->result_array();
		
		return $arrH;
	}
}
