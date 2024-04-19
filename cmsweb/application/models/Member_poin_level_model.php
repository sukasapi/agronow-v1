<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_level_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_member_poin_level';
    var $column_order = array('_member_poin_level.mpl_id','_member_poin_level.mpl_name','_member_poin_level.mpl_poin_min','_member_poin_level.mpl_poin_max','_member_poin_level.mpl_reward_saldo'); //set column field database for datatable orderable
    var $column_search = array('_member_poin_level.mpl_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        if($this->input->get('cat_id')){
            $this->db->where('_member_poin_level.cat_id',$this->input->get('cat_id'));
        }

        $this->db->select('_member_poin_level.*');

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



    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){
        $this->db->select('SQL_CALC_FOUND_ROWS _member_poin_level.*',FALSE);
        $this->db->from('_member_poin_level');

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
            $this->db->where('_member_poin_level.status',$param_query['filter_status']);
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
        $this->db->select('_member_poin_level.*');
        $this->db->from('_member_poin_level');

        $this->db->where('mpl_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_member_poin_level.*');
        $this->db->from('_member_poin_level');

        $this->db->where_in('mpl_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }



    function insert($data){

        $this->db->insert('_member_poin_level', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['mpl_id'];
        unset($data['mpl_id']);
        $this->db->where('mpl_id', $id);
        $this->db->update('_member_poin_level' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($mpl_id){
        $this->db->where('mpl_id',$mpl_id);
        $this->db->delete('_member_poin_level');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
