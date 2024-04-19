<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_ads_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_ads';
    var $column_order = array('_ads.ads_id',NULL,'_ads.ads_position','_ads.ads_sponsor','_ads.ads_start','_ads.ads_end','_ads.ads_status','ads_click.total','ads_click.web','ads_click.android','ads_click.ios'); //set column field database for datatable orderable
    var $column_search = array('_ads.ads_sponsor'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_ads.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_ads.*,ads_click.*');

        $this->db->from($this->table);

        $sql_click = "
            SELECT ads_id, sum(total) as total, sum(web) as web, sum(android) as android, sum(ios) as ios FROM  
            (
            select _ads.*, click_ads.total, null as web,null as android,null as ios from _ads
            left join (select ads_id,count(*) as total from _ads_klik group by ads_id) as click_ads on _ads.ads_id = click_ads.ads_id
            
            UNION 
            
            select _ads.*, NULL, click_ads.web,null,null from _ads
            left join (select ads_id,count(*) as web from _ads_klik where ak_channel = 'web' group by ads_id,ak_channel) as click_ads on _ads.ads_id = click_ads.ads_id
            
            UNION 
            
            select _ads.*, NULL, null,click_ads.android,null from _ads
            left join (select ads_id,count(*) as android from _ads_klik where ak_channel = 'android' group by ads_id,ak_channel) as click_ads on _ads.ads_id = click_ads.ads_id
            
            UNION 
            
            select _ads.*, NULL, null,null,click_ads.ios from _ads
            left join (select ads_id,count(*) as ios from _ads_klik where ak_channel = 'ios' group by ads_id,ak_channel) as click_ads on _ads.ads_id = click_ads.ads_id
            
            ) total_click
            GROUP BY ads_id
        ";
        $this->db->join('('.$sql_click.') as ads_click','_ads.ads_id=ads_click.ads_id','LEFT');


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

        $this->db->where('_ads_id', $id);


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

        $this->db->insert('ads', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;


    }

    function update($data){
        $id = $data['ads_id'];
        unset($data['ads_id']);
        $this->db->where('_ads_id', $id);
        $this->db->update('ads' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($ads_id){
        $this->db->where('_ads_id',$ads_id);
        $this->db->delete('ads');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
