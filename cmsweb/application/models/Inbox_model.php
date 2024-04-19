<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_inbox';
    var $column_order = array('_inbox.inbox_id','_inbox.inbox_create_date',NULL,'_inbox.inbox_title',NULL,NULL); //set column field database for datatable orderable
    var $column_search = array('_inbox.inbox_title'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_inbox.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_inbox.*,message.total_message');

        $this->db->from($this->table);

        $this->db->where('_inbox.inbox_id=_inbox.parent_id');

        $sql_join = "(SELECT parent_id, count(*) as total_message from _inbox group by parent_id) as message";
        $this->db->join($sql_join,'message.parent_id = _inbox.inbox_id','LEFT');

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


    function gets($id){
        $this->db->select('_inbox.*');
        $this->db->from('_inbox');
        $this->db->where('_inbox.parent_id',$id);




        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function insert($data){
        $this->db->insert('_inbox', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function delete($inbox_id){
        $this->db->where('inbox_id',$inbox_id);
        $this->db->delete('_inbox');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }



}
