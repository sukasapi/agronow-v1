<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();


        if($this->input->get('withcategory')=='yes'){
            $this->column_order = array('content_id','_content.content_create_date','_content.content_publish_date',NULL,'_category.cat_name','_content.content_name',NULL,'_content.content_hits',NULL,'_content.content_status'); //set column field database for datatable orderable
            $this->column_search = array('_content.content_name','_category.cat_name'); //set column field database for datatable searchable
        }else if($this->input->get('withcategorytype')=='yes'){
            $this->column_order = array('content_id','_content.content_create_date','_content.content_publish_date',NULL,'_category.cat_name','_content.content_name',NULL,'_content.content_hits',NULL,'_content_type.content_type_name','_content.content_status'); //set column field database for datatable orderable
            $this->column_search = array('_content.content_name','_category.cat_name'); //set column field database for datatable searchable
        }else if($this->input->get('withgroup')=='yes'){
            $this->column_order = array('content_id','_content.content_create_date','_content.content_publish_date',NULL,'_category.cat_name','_content.content_name',NULL,'_content.content_hits','_group.group_name','_content.content_status'); //set column field database for datatable orderable
            $this->column_search = array('_content.content_name','_category.cat_name'); //set column field database for datatable searchable
        }else{
            $this->column_order = array('content_id','_content.content_create_date','_content.content_publish_date',NULL,'_content.content_name',NULL,'_content.content_hits','_content.content_status'); //set column field database for datatable orderable
            $this->column_search = array('_content.content_name'); //set column field database for datatable searchable
        }
    }


    /* DATATABLE BEGIN */
    var $table = '_content';
    //var $column_order = array('content_id','_content.content_create_date','_content.content_publish_date',NULL,'_content.content_name',NULL,'_content.content_hits','_content.content_status'); //set column field database for datatable orderable
    //var $column_search = array('_content.content_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here
        $section_id = NULL;
        if($this->input->get('section_id')){
            $section_id = $this->input->get('section_id');
            $this->db->where('_content.section_id',$this->input->get('section_id'));
        }

        if($this->input->get('category_id')){
            $this->db->where('_content.cat_id',$this->input->get('category_id'));
        }

        if($this->input->get('group_id')){
            $this->db->where('_content.group_id',$this->input->get('group_id'));
        }

        // Handle Filter Content Section
        $cluster_section_one = [
            40, // Popup
            34, // CEO Notes
            42, // BOD Share
            22, // Announcement
            12, // News
            13, // Article
            35, // Digital Library
        ];
        if (in_array($section_id,$cluster_section_one)){

            // Filter jika akun memiliki Klien
            if (my_klien()){
                $my_klien = my_klien();
                $this->db->where('content_id IN (SELECT distinct data_id from _section_klien where source = "content" AND section_id = '.$section_id.' AND id_klien = '.$my_klien.') ');
            }else{
                // Superadmin
                if($this->input->get('id_klien')){
                    $my_klien = $this->input->get('id_klien');
                    $this->db->where('content_id IN (SELECT distinct data_id from _section_klien where source = "content" AND section_id = '.$section_id.' AND id_klien = '.$my_klien.') ');
                }

            }

        }


        $cluster_section_two = [
            31, // Knowledge Sharing
        ];
        if (in_array($section_id,$cluster_section_two)){

            // Filter jika akun memiliki Klien
            if (my_klien()){
                $my_klien = my_klien();
                $this->db->join('_member', '_member.member_id = _content.member_id','LEFT');
                $this->db->join('_group as _group_member', '_group_member.group_id = _member.group_id','LEFT');
                $this->db->where('_group_member.id_klien',$my_klien);
            }else{
                // Superadmin
                if($this->input->get('id_klien')){
                    $my_klien = $this->input->get('id_klien');
                    $this->db->join('_member', '_member.member_id = _content.member_id','LEFT');
                    $this->db->join('_group as _group_member', '_group_member.group_id = _member.group_id','LEFT');
                    $this->db->where('_group_member.id_klien',$my_klien);
                }

            }

        }

        $this->db->select('_content.*,_media.media_id,_media.media_value,_media.media_type,_category.cat_name,_category.cat_id,
        _content_type.content_type_id, _content_type.content_type_name , _group.group_name');

        $this->db->from($this->table);

        $this->db->join('_category','_category.cat_id=_content.cat_id','LEFT');
        $this->db->join('_content_type','_content_type.content_type_id=_content.content_type_id','LEFT');
        $this->db->join('_group','_group.group_id=_content.group_id','LEFT');

        $sql_media = "select * from _media where section_id = ".$section_id." and media_primary = '1' and media_status = '1'";
        $this->db->join('('.$sql_media.') as _media','_media.data_id=_content.content_id','LEFT');


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
        $this->db->select('SQL_CALC_FOUND_ROWS content.*',FALSE);
        $this->db->from('_content');

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
            $this->db->where('_content.status',$param_query['filter_status']);
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


    function get($id,$section_id){
        $this->db->select('_content.*,_media.media_id,_media.media_value,_media.media_type,_category.cat_name,_category.cat_id,
        _content_type.content_type_id,_content_type.content_type_name');

        $this->db->from('_content');

        $this->db->join('_category','_category.cat_id=_content.cat_id','LEFT');
        $this->db->join('_content_type','_content_type.content_type_id=_content.content_type_id','LEFT');

        $sql_media = "select * from _media where section_id = ".$section_id." and media_primary = '1' and media_status = '1'";
        $this->db->join('('.$sql_media.') as _media','_media.data_id=_content.content_id','LEFT');

        $this->db->where('content_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_content.*');
        $this->db->from('_content');

        $this->db->where_in('content_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function search($q,$limit=NULL){
        $this->db->select('_content.*,_media.media_id,content_type.name as content_type_name,project.name as project_name,location.name as location_name,
        parent.content_number as parent_content_number');
        $this->db->from('_content');

        $this->db->like('content.content_number',$q);

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

        $this->db->insert('_content', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['content_id'];
        unset($data['content_id']);
        $this->db->where('content_id', $id);
        $this->db->update('_content' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($content_id){
        $this->db->where('content_id',$content_id);
        $this->db->delete('_content');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
