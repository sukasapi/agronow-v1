<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom_category_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = 'classroom_category';
    var $column_order = array('classroom_category.id','classroom_category.name','classroom_category.description','parent.name'); //set column field database for datatable orderable
    var $column_search = array('classroom_category.name'); //set column field database for datatable searchable
    var $order = array('classroom_category.id' => 'asc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here
        if($this->input->post('name')){
            $this->db->where('name', $this->input->post('name'));
        }


        $this->db->select('classroom_category.*,parent.name as parent_name');

        $this->db->from($this->table);
        $this->db->where('classroom_category.deleted_at IS NULL');

        $this->db->join('classroom_category as parent','classroom_category.parent=parent.id','LEFT');
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
        $this->db->select('SQL_CALC_FOUND_ROWS classroom_category.*,parent.name as parent_name',FALSE);
        $this->db->from('classroom_category');

        $this->db->where('classroom_category.deleted_at IS NULL');
        $this->db->join('classroom_category as parent','classroom_category.parent=parent.id','LEFT');


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


        if (!empty($param_query['filter_active'])) {
            if (is_array($param_query['filter_active'])) {
                foreach ($param_query['filter_active'] as $k => $v) {
                    $this->db->or_having('classroom_category.is_active',$v['parameter']);
                }
            } else{
                $this->db->where('classroom_category.is_active',$param_query['filter_active']);
            }
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
        $this->db->select('classroom_category.*,parent.name as parent_name');
        $this->db->from('classroom_category');

        $this->db->where('classroom_category.id', $id);
        $this->db->where('classroom_category.deleted_at IS NULL');
        $this->db->join('classroom_category as parent','classroom_category.parent=parent.id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('classroom_category.*,parent.name as parent_name');
        $this->db->from('classroom_category');

        $this->db->where_in('classroom_category.id', $ids);
        $this->db->where('classroom_category.deleted_at IS NULL');

        $this->db->join('classroom_category as parent','classroom_category.parent=parent.id','LEFT');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function search($q,$limit=NULL){
        $this->db->select('classroom_category.*,parent.name as parent_name');
        $this->db->from('classroom_category');

        $this->db->where('classroom_category.deleted_at IS NULL');

        $this->db->join('classroom_category as parent','classroom_category.parent=parent.id','LEFT');

        $this->db->like('classroom_category.name',$q);

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
        $this->db->insert('classroom_category', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        $this->db->where('classroom_category.deleted_at IS NULL');
        $this->db->update('classroom_category' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($classroom_category_id,$soft_delete=TRUE){
        if ($soft_delete==TRUE) {
            $user_id = NULL;
            $data = array(
                'classroom_category.deleted_at' => date('Y-m-d H:i:s'),
                'classroom_category.deleted_by' => $user_id
            );
            $this->db->where('id', $classroom_category_id);
            $this->db->where('classroom_category.deleted_at IS NULL');
            $this->db->update('classroom_category' ,$data);
        }else{
            $this->db->where('id',$classroom_category_id);
            $this->db->where('classroom_category.deleted_at IS NULL');
            $this->db->delete('classroom_category');
        }

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }



    // TREE STRUCTURE

    function get_classroom_category_tree(){
        $tree = array();

        $this->db->select('*');
        $this->db->from('classroom_category');
        $this->db->where('parent IS NULL');
        $this->db->order_by('sort','ASC');

        $query = $this->db->get();
        $result  = $query->result_array();

        foreach ($result as $k => $v) {
            $tree[$k] = $v;
            $tree[$k]['child'] = $this->get_childs_recursive($v['id']);
        }

        return !empty($result)?$tree:FALSE;
    }


    function get_classroom_category_by_parent($parent_id=NULL){
        $tree = array();

        $this->db->select('*');
        $this->db->from('classroom_category');
        if ($parent_id==NULL) {
            $this->db->where('parent IS NULL');
        }else{
            $this->db->where('parent', $parent_id);
        }
        $this->db->order_by('sort','ASC');

        $query = $this->db->get();
        $result  = $query->result_array();

        foreach ($result as $k => $v) {
            $tree[$k] = $v;
            $tree[$k]['child'] = $this->get_childs_recursive($v['id']);
        }
        return !empty($result)?$tree:FALSE;
    }


    public function get_childs($parent_id = false){
        if (!$parent_id || empty($parent_id)){
            return false;
        }

        $query = $this->db->get_where('classroom_category', array('parent' => $parent_id));
        if ($query->num_rows() == 0){
            return false;
        }else{
            $result =  $query->result_array();
            foreach ($result as $k => $v) {
                $result[$k]['child'] = array();
            }
            return $result;
        }
    }

    public function get_childs_recursive($parent_id = false){
        if (!$parent_id || empty($parent_id)){
            return false;
        }
        $tree = array();
        $childs = $this->get_childs($parent_id);

        if (!empty($childs)) {
            foreach($childs as $k => $v) {
                $tree[$k] = $v;
                if ($this->get_childs($v['id']) === false) {
                    $tree[$k] = $v;
                } else {
                    $tree[$k]['child'] = $this->get_childs_recursive($v['id']);
                }
            }
        }
        return $tree;
    }



    function set_classroom_category_batch($data){
        if ($data){
            $this->db->trans_start();
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->update_batch('classroom_category' ,$data,'id');
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            $this->db->trans_complete();
            return ($this->db->trans_status() === FALSE)? FALSE:TRUE;
        }else{
            return FALSE;
        }

    }

}
