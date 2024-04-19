<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari cat_parent
        $this->load->database();
    }



    function get_all($keyword=NULL,$limit=NULL,$offset=NULL,$param_query=NULL){
        $this->db->select('SQL_CALC_FOUND_ROWS _category.*,cat_parent.cat_name as cat_parent_name',FALSE);
        $this->db->from('_category');


        $this->db->join('_category as cat_parent','_category.cat_parent=cat_parent.cat_id','LEFT');


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
                    $this->db->or_having('_category.is_active',$v['parameter']);
                }
            } else{
                $this->db->where('_category.is_active',$param_query['filter_active']);
            }
        }

        if (!empty($param_query['filter_section'])) {
            if (is_array($param_query['filter_section'])) {
                foreach ($param_query['filter_section'] as $k => $v) {
                    $this->db->or_having('_category.section_id',$v['parameter']);
                }
            } else{
                $this->db->where('_category.section_id',$param_query['filter_section']);
            }
        }

        if (!empty($param_query['filter_desc'])) {
            $this->db->where('_category.cat_desc',$param_query['filter_desc']);
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

    function get($id,$section_id=NULL){
        $this->db->select('_category.*,cat_parent.cat_name as cat_parent_name');
        $this->db->from('_category');

        $this->db->where('_category.cat_id', $id);
        if ($section_id!=NULL){
            $this->db->where('_category.section_id', $section_id);
        }


        $this->db->join('_category as cat_parent','_category.cat_parent=cat_parent.cat_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_group($group_id,$order_by=NULL){
        $this->db->select('_category.*,cat_parent.cat_name as cat_parent_name');
        $this->db->from('_category');

        $this->db->where('_category.cat_desc', $group_id);
		if($order_by=="nama_asc") {
			$this->db->order_by('_category.cat_name','ASC');
		} else {
			$this->db->order_by('_category.cat_order','ASC');
		}

        $this->db->join('_category as cat_parent','_category.cat_parent=cat_parent.cat_id','LEFT');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_category.*,cat_parent.cat_name as cat_parent_name');
        $this->db->from('_category');

        $this->db->where_in('_category.cat_id', $ids);


        $this->db->join('_category as cat_parent','_category.cat_parent=cat_parent.cat_id','LEFT');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function search($q,$section_id,$limit=NULL,$publish_only=FALSE){
		$this->db->select('_category.*,cat_parent.cat_name as cat_parent_name');
		$this->db->from('_category');
		$this->db->where('_category.section_id', $section_id);
		
		if($publish_only==TRUE) $this->db->where('_category.cat_status', '1');
		
		$this->db->join('_category as cat_parent','_category.cat_parent=cat_parent.cat_id','LEFT');
		$this->db->like('_category.cat_name',$q);
		
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
        $this->db->insert('_category', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['cat_id'];
        unset($data['cat_id']);
        $this->db->where('cat_id', $id);

        $this->db->update('_category' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($category_id){
        $this->db->where('cat_id',$category_id);
        $this->db->delete('_category');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }



    // TREE STRUCTURE

    function get_category_tree_group($section_id=NULL,$group_id=NULL){
        $tree = array();

        $this->db->select('*');
        $this->db->from('_category');
        $this->db->where('cat_parent',0);
        $this->db->where('section_id',$section_id);
        $this->db->where('cat_desc',$group_id);
        $this->db->order_by('cat_order','ASC');

        $query = $this->db->get();
        $result  = $query->result_array();

        foreach ($result as $k => $v) {
            $tree[$k] = $v;
            $tree[$k]['child'] = $this->get_childs_recursive($v['cat_id']);
        }

        return !empty($result)?$tree:FALSE;
    }


    function get_category_tree($section_id=NULL,$order_by=NULL,$param_query=NULL,$publish_only=FALSE){
        $tree = array();

        // Handle Filter Content Section
        $cluster_section_one = [
            19, // Forum
            37, // Expert
            35, // Digital Library
            31, // Knowledge Sharing
        ];
        if (in_array($section_id,$cluster_section_one)){

            // Filter jika akun memiliki Klien
            if (my_klien()){
                $my_klien = my_klien();
                $this->db->where('cat_id IN (SELECT distinct data_id from _section_klien where source = "category" AND section_id = '.$section_id.' AND id_klien = '.$my_klien.') ');
            }else{
                // Superadmin
                if($this->input->get('id_klien')){
                    $my_klien = $this->input->get('id_klien');
                    $this->db->where('cat_id IN (SELECT distinct data_id from _section_klien where source = "category" AND section_id = '.$section_id.' AND id_klien = '.$my_klien.') ');
                }

            }

        }


        if (isset($param_query['klien'])){
            $this->db->where('id_klien',$param_query['klien']);
        }

        $this->db->select('*');
        $this->db->from('_category');
        $this->db->where('cat_parent',0);
        $this->db->where('section_id',$section_id);
		
		if($publish_only==TRUE) $this->db->where('cat_status','1');
		
		if($order_by=="nama_asc") {
			$this->db->order_by('cat_name','ASC');
		} else {
			$this->db->order_by('cat_order','ASC');
		}

        $query = $this->db->get();
        $result  = $query->result_array();

        foreach ($result as $k => $v) {
            $tree[$k] = $v;
            $tree[$k]['child'] = $this->get_childs_recursive($v['cat_id']);
        }

        return !empty($result)?$tree:FALSE;
    }


    function get_category_by_cat_parent($cat_parent_id=NULL){
        $tree = array();

        $this->db->select('*');
        $this->db->from('_category');
        if ($cat_parent_id==NULL) {
            $this->db->where('cat_parent',0);
        }else{
            $this->db->where('cat_parent', $cat_parent_id);
        }
        $this->db->order_by('cat_order','ASC');

        $query = $this->db->get();
        $result  = $query->result_array();

        foreach ($result as $k => $v) {
            $tree[$k] = $v;
            $tree[$k]['child'] = $this->get_childs_recursive($v['cat_id']);
        }
        return !empty($result)?$tree:FALSE;
    }


    public function get_childs($cat_parent_id = false){
        if (!$cat_parent_id || empty($cat_parent_id)){
            return false;
        }

        $query = $this->db->get_where('_category', array('cat_parent' => $cat_parent_id));
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

    public function get_childs_recursive($cat_parent_id = false){
        if (!$cat_parent_id || empty($cat_parent_id)){
            return false;
        }
        $tree = array();
        $childs = $this->get_childs($cat_parent_id);

        if (!empty($childs)) {
            foreach($childs as $k => $v) {
                $tree[$k] = $v;
                if ($this->get_childs($v['cat_id']) === false) {
                    $tree[$k] = $v;
                } else {
                    $tree[$k]['child'] = $this->get_childs_recursive($v['cat_id']);
                }
            }
        }
        return $tree;
    }



    function set_category_batch($data){
        //print_r($data);
        if ($data){
            $this->db->trans_start();
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->update_batch('_category' ,$data,'cat_id');
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            $this->db->trans_complete();
            return ($this->db->trans_status() === FALSE)? FALSE:TRUE;
        }else{
            return FALSE;
        }

    }

}
