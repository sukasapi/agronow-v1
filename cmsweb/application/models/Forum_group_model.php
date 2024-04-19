<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_group_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_forum_group';
    var $column_order = array('_forum_group.forum_id','fg.forum_group_name','_forum_group.forum_name','_member.member_name','_forum_group.forum_name','_forum_group.forum_create_date','_forum_group.forum_update_date',NULL,NULL); //set column field database for datatable orderable
    var $column_search = array('_forum_group.forum_name','_member.member_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_forum_group.cat_id',$this->input->get('cat_id'));
        }*/

        //Filter By Admin Access Klien / Group
        $this->db->where_in('_forum_group.group_id', my_groups());

        $this->db->select('_forum_group.*,_member.member_name,_group.group_id,_group.group_name , fg.group_name as forum_group_name');

        $this->db->from($this->table);

        $this->db->join('_group as fg', 'fg.group_id = _forum_group.group_id', 'left');
        $this->db->join('_member','_member.member_id=_forum_group.member_id','LEFT');
        $this->db->join('_group','_member.group_id=_group.group_id','LEFT');

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
        $this->db->select('SQL_CALC_FOUND_ROWS _forum_group.*',FALSE);
        $this->db->from('_forum_group');

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
            $this->db->where('_forum_group.status',$param_query['filter_status']);
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
        $this->db->select('_forum_group.*,_group.group_name, _member.member_name, _member.member_image,  _category.cat_name, fg.group_name as forum_group_name');
        $this->db->from('_forum_group');

        $this->db->join('_group as fg', 'fg.group_id = _forum_group.group_id', 'left');
        $this->db->join('_category', '_category.cat_id = _forum_group.cat_id', 'left');
        $this->db->join('_member', '_member.member_id = _forum_group.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('forum_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_forum_group.*');
        $this->db->from('_forum_group');

        $this->db->where_in('forum_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function search($q,$limit=NULL){
        $this->db->select('_forum_group.*,forum_type.name as forum_type_name,project.name as project_name,location.name as location_name,
        parent.forum_number as parentforum_number, _category.cat_name');
        $this->db->from('_forum_group');

        $this->db->like('_forum_group.forum_number',$q);

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

        $this->db->insert('_forum_group', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['forum_id'];
        unset($data['forum_id']);
        $this->db->where('forum_id', $id);
        $this->db->update('_forum_group' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($forum_id){
        $this->db->where('forum_id',$forum_id);
        $this->db->delete('_forum_group');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
