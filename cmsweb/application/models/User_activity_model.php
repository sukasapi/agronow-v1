<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_activity_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_user_activity';
    var $column_order = array('_user_activity.user_activity_create_date','_user.user_name','_user_activity.user_activity_type','_section.section_name','_user_activity.user_activity_desc','_user_activity.data_id','_user_activity.ip_address'); //set column field database for datatable orderable
    var $column_search = array('_user.user_name','_user_activity.user_activity_type','_section.section_name','_user_activity.user_activity_desc'); //set column field database for datatable searchable
    var $order = array('_user_activity.user_activity_create_date' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        if($this->input->get('cat_id')){
            $this->db->where('_user_activity.cat_id',$this->input->get('cat_id'));
        }

        $this->db->select('_user_activity.*,_user.user_name,_group.group_name,_section.section_name');

        $this->db->from($this->table);
		
		// kl bukan super super admin hanya bisa lihat log milik sendiri
		if($this->session->user_level_id!="1") {
			$this->db->where('_user_activity.user_id',$this->session->id);
		}

        $this->db->join('_user', '_user_activity.user_id = _user.user_id', 'left');
        $this->db->join('_group', '_user.user_code = _group.group_id', 'left');

        $this->db->join('_section', '_user_activity.section_id = _section.section_id', 'left');




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
        $this->db->select('SQL_CALC_FOUND_ROWS _user_activity.*',FALSE);
        $this->db->from('_user_activity');

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
            $this->db->where('_user_activity.status',$param_query['filter_status']);
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
        $this->db->select('_user_activity.*');
        $this->db->from('_user_activity');

        $this->db->where('user_activity_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_user_activity.*');
        $this->db->from('_user_activity');

        $this->db->where_in('user_activity_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function insert($data){

        $this->db->insert('_user_activity', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['user_activity_id'];
        unset($data['user_activity_id']);
        $this->db->where('user_activity_id', $id);
        $this->db->update('_user_activity' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($user_activity_id){
        $this->db->where('user_activity_id',$user_activity_id);
        $this->db->delete('_user_activity');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
