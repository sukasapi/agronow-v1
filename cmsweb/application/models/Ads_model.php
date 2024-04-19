<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_ads';
    var $column_order = array('_ads.ads_id','_ads.ads_create_date','_ads.ads_name',NULL,'_ads.ads_date_start','_ads.ads_date_end','_ads.ads_status'); //set column field database for datatable orderable
    var $column_search = array('_ads.ads_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_ads.cat_id',$this->input->get('cat_id'));
        }*/

        //add custom filter here
        $section_id = 21;


        // Filter jika akun memiliki Klien
        if (my_klien()){
            $my_klien = my_klien();
            $this->db->where('ads_id IN (SELECT distinct data_id from _section_klien where source = "content" AND section_id = '.$section_id.' AND id_klien = '.$my_klien.') ');
        }else{
            // Superadmin
            if($this->input->get('id_klien')){
                $my_klien = $this->input->get('id_klien');
                $this->db->where('ads_id IN (SELECT distinct data_id from _section_klien where source = "content" AND section_id = '.$section_id.' AND id_klien = '.$my_klien.') ');
            }

        }


        $this->db->select('_ads.*');

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
        $this->db->select('SQL_CALC_FOUND_ROWS ads.*',FALSE);
        $this->db->from('_ads');

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
            $this->db->where('_ads.status',$param_query['filter_status']);
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
        $this->db->select('_ads.*');
        $this->db->from('_ads');

        $this->db->where('ads_id', $id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function gets($ids){
        $this->db->select('_ads.*');
        $this->db->from('_ads');

        $this->db->where_in('ads_id', $ids);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function search($q,$limit=NULL){
        $this->db->select('_ads.*,ads_type.name as ads_type_name,project.name as project_name,location.name as location_name,
        parent.ads_number as parentads_number');
        $this->db->from('_ads');

        $this->db->like('ads.ads_number',$q);

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

        $this->db->insert('_ads', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['ads_id'];
        unset($data['ads_id']);
        $this->db->where('ads_id', $id);
        $this->db->update('_ads' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($ads_id){
        $this->db->where('ads_id',$ads_id);
        $this->db->delete('_ads');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
