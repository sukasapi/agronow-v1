<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expert_member_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_expert_member';
    var $column_order = array('_expert_member.em_id','_expert_member.em_name','_group.group_name','_expert_member.em_concern','_category.cat_name','_expert_member.em_status','_expert_member.em_create_date'); //set column field database for datatable orderable
    var $column_search = array('_expert_member.em_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_expert_member.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_expert_member.*,_group.group_id,_group.group_name ,
        _category.cat_name,_member.*');

        $this->db->from($this->table);

        $this->db->join('_group','_group.group_id=_expert_member.group_id','LEFT');
        $this->db->join('_category','_category.cat_id=_expert_member.cat_id','LEFT');
        $this->db->join('_member','_member.member_id=_expert_member.member_id','LEFT');

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
        $this->db->select('SQL_CALC_FOUND_ROWS _expert_member.*',FALSE);
        $this->db->from('_expert_member');

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

        if ($param_query['filter_status']) {
            $this->db->where('_expert_member.status',$param_query['filter_status']);
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
        $this->db->select('_expert_member.*,_group.group_id,_group.group_name ,
        _category.cat_name,_member.*');
        $this->db->from('_expert_member');

        $this->db->where('em_id', $id);

        $this->db->join('_group','_group.group_id=_expert_member.group_id','LEFT');
        $this->db->join('_category','_category.cat_id=_expert_member.cat_id','LEFT');
        $this->db->join('_member','_member.member_id=_expert_member.member_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_member($member_id){
        $this->db->select('_expert_member.*,_group.group_id,_group.group_name ,
        _category.cat_name,_member.*');
        $this->db->from('_expert_member');

        $this->db->where('_expert_member.member_id', $member_id);

        $this->db->join('_group','_group.group_id=_expert_member.group_id','LEFT');
        $this->db->join('_category','_category.cat_id=_expert_member.cat_id','LEFT');
        $this->db->join('_member','_member.member_id=_expert_member.member_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_expert_member.*');
        $this->db->from('_expert_member');

        $this->db->where_in('em_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function search($q,$limit=NULL){
        $this->db->select('_expert_member.*,member_type.name as member_type_name,project.name as project_name,location.name as location_name,
        parent.member_number as parentmember_number');
        $this->db->from('_expert_member');

        $this->db->like('_expert_member.member_number',$q);

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

        $this->db->insert('_expert_member', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['em_id'];
        unset($data['em_id']);
        $this->db->where('em_id', $id);
        $this->db->update('_expert_member' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($em_id){
        $this->db->where('em_id',$em_id);
        $this->db->delete('_expert_member');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
