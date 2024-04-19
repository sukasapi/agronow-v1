<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_media';
    var $column_order = array('media_id','_media.section_id','_media.data_id','_media.media_name','_media.media_type','_media.media_value','_media.media_size','_media.media_primary','_media.media_status'); //set column field database for datatable orderable
    var $column_search = array('media_id','_media.section_id','_media.data_id','_media.media_name','_media.media_type','_media.media_value'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        if($this->input->get('section_id')){
            $this->db->where('_media.section_id',$this->input->get('section_id'));
        }

        $this->db->select('_media.*');

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
        $this->db->select('SQL_CALC_FOUND_ROWS _media.*',FALSE);
        $this->db->from('media');

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
            $this->db->where('_media.status',$param_query['filter_status']);
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


    function get_by_section_data_type($section_id,$data_id,$media_type){
        $this->db->select('_media.*');
        $this->db->from('_media');

        $this->db->where('section_id', $section_id);
        $this->db->where('data_id', $data_id);
        $this->db->where('media_type', $media_type);
        $this->db->order_by('media_id', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }


    // Not Image
    function get_by_section_data_file_only($section_id,$data_id){
        $this->db->select('_media.*');
        $this->db->from('_media');

        $this->db->where('section_id', $section_id);
        $this->db->where('data_id', $data_id);
        $this->db->where('media_type !=', 'image');
        $this->db->order_by('media_id', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_media.*');
        $this->db->from('media');

        $this->db->where_in('media_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function search($q,$limit=NULL){
        $this->db->select('_media.*,media_type.name as media_type_name,project.name as project_name,location.name as location_name,
        parent.media_number as parent_media_number');
        $this->db->from('media');

        $this->db->where('_media.deleted_at IS NULL');

        $this->db->join('media as parent','_media.parent=parent.id','LEFT');
        $this->db->join('media_type','_media.media_type_id=media_type.id','LEFT');
        $this->db->join('location','_media.location_id=location.id','LEFT');
        $this->db->join('project','_media.project_id=project.id','LEFT');

        $this->db->like('_media.media_number',$q);

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

        $this->db->insert('_media', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['media_id'];
        unset($data['media_id']);
        $this->db->where('media_id', $id);
        $this->db->update('_media' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($media_id){
        $this->db->where('media_id',$media_id);
        $this->db->delete('_media');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
