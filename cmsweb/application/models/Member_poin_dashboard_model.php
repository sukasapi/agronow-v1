<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_dashboard_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_member_poin_saldo_yearly_view';
    var $column_order = array('_member.member_id','_member.member_name','_member.member_nip','_group.group_name',NULL,'_member_poin_saldo_yearly_view.poin','_member_poin_saldo_yearly_view.saldo','_member_poin_saldo_yearly_view.year'); //set column field database for datatable orderable
    var $column_search = array('_member.member_name','_member.member_nip'); //set column field database for datatable searchable
    var $order = array('_member.member_id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        if($this->input->get('group_ids')){
            $this->db->where_in('_member_poin_saldo_yearly_view.group_id',$this->input->get('group_ids'));
        }

        if($this->input->get('year')){
            $this->db->where_in('_member_poin_saldo_yearly_view.year',$this->input->get('year'));
        }

        $this->db->select('_member_poin_saldo_yearly_view.*,_member.member_nip');

        $this->db->from($this->table);

        $this->db->join('_member','_member.member_id=_member_poin_saldo_yearly_view.member_id','LEFT');

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
        $this->db->select('SQL_CALC_FOUND_ROWS _member.*,_member_level.mlevel_id,_member_level.mlevel_name,_group.group_id,_group.group_name, _jabatan.jabatan_name',FALSE);
        $this->db->from('_member');

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_member_level','_member.mlevel_id=_member_level.mlevel_id','LEFT');
        $this->db->join('_group','_member.group_id=_group.group_id','LEFT');

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

        if (isset($param_query['filter_status'])) {
            $this->db->where('_member.status',$param_query['filter_status']);
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
        $this->db->select('_member.*,_group.group_id,_group.group_name ,_member_level.mlevel_id,_member_level.mlevel_name, _jabatan.jabatan_name, jabatan_group.group_name as jabatan_group_name');
        $this->db->from('_member');

        $this->db->where('member_id', $id);

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_group as jabatan_group','jabatan_group.group_id=_jabatan.group_id','LEFT');
        $this->db->join('_group','_group.group_id=_member.group_id','LEFT');
        $this->db->join('_member_level','_member_level.mlevel_id=_member.mlevel_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_member.*');
        $this->db->from('_member');

        $this->db->where_in('member_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_group($group_id){
        $this->db->select('_member.*,_group.group_id,_group.group_name ,_member_level.mlevel_id,_member_level.mlevel_name, _jabatan.jabatan_name');
        $this->db->from('_member');

        $this->db->where('_member.group_id', $group_id);

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_group','_group.group_id=_member.group_id','LEFT');
        $this->db->join('_member_level','_member_level.mlevel_id=_member.mlevel_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_group_nip($group_id,$nip){
        $this->db->select('_member.*,_group.group_id,_group.group_name ,_member_level.mlevel_id,_member_level.mlevel_name, _jabatan.jabatan_name');
        $this->db->from('_member');

        $this->db->where('_member.group_id', $group_id);
        $this->db->where('_member.member_nip', $nip);

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_group','_group.group_id=_member.group_id','LEFT');
        $this->db->join('_member_level','_member_level.mlevel_id=_member.mlevel_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }


    function get_by_mlevel($mlevel_id){
        $this->db->select('_member.*,_group.group_id,_group.group_name ,_member_level.mlevel_id,_member_level.mlevel_name, _jabatan.jabatan_name');
        $this->db->from('_member');

        $this->db->where('_member.mlevel_id', $mlevel_id);

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_group','_group.group_id=_member.group_id','LEFT');
        $this->db->join('_member_level','_member_level.mlevel_id=_member.mlevel_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function get_by_jabatan($jabatan_id){
        $this->db->select('_member.*,_group.group_id,_group.group_name ,_member_level.mlevel_id,_member_level.mlevel_name, _jabatan.jabatan_name');
        $this->db->from('_member');

        $this->db->where('_member.jabatan_id', $jabatan_id);

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_group','_group.group_id=_member.group_id','LEFT');
        $this->db->join('_member_level','_member_level.mlevel_id=_member.mlevel_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function search($q,$limit=NULL){
        $this->db->select('_member.*,_group.group_id,_group.group_name ,_member_level.mlevel_id,_member_level.mlevel_name');
        $this->db->from('_member');

        $this->db->like('_member.member_name',$q);

        $this->db->join('_group','_group.group_id=_member.group_id','LEFT');
        $this->db->join('_member_level','_member_level.mlevel_id=_member.mlevel_id','LEFT');

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

        $this->db->insert('_member', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['member_id'];
        unset($data['member_id']);
        $this->db->where('member_id', $id);
        $this->db->update('_member' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($member_id){
        $this->db->where('member_id',$member_id);
        $this->db->delete('_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }



    public function select_member_device_token($opt="",$recData=array(),$is_active=''){
        if ($is_active){
            $sql_is_active = " AND is_active='".$is_active."'";
        } else {
            $sql_is_active = "";
        }
        if ($opt == "byMemberId"){
            $sql = "SELECT device_token FROM _member_device_token WHERE member_id='".$recData['memberId']."'";
            $sql .= $sql_is_active;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data;
        } elseif ($opt == "byToken"){
            $sql = "SELECT * FROM _member_device_token WHERE device_token='".$recData['token']."' AND member_id='".$recData['memberId']."'";
            $sql .= $sql_is_active;
            $query = $this->db->query($sql);
            $data = $query->result_array();
            return $data[0]?$data[0]:NULL;
        }
    }




}
