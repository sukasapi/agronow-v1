<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_saldo_setting_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_member_saldo_setting';
    var $column_order = array('_member_saldo_setting.mss_id','_group.group_name','_member_saldo_setting.mss_saldo', '_member_saldo_setting.mss_start', '_member_saldo_setting.mss_end'); //set column field database for datatable orderable
    var $column_search = array('_member_saldo_setting.mss_start'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_member.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_member_saldo_setting.*, _group.group_id, _group.group_name');

        $this->db->from($this->table);

        $this->db->join('_group','_member_saldo_setting.group_id=_group.group_id','LEFT');

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



    function get($id){
        $this->db->select('_member_saldo_setting.*');
        $this->db->from('_member_saldo_setting');

        $this->db->where('mss_id', $id);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_group($group_id){
        $this->db->select('_member_saldo_setting.*');
        $this->db->from('_member_saldo_setting');

        $this->db->where('group_id', $group_id);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


    function insert($data){
        $this->db->insert('_member_saldo_setting', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['mss_id'];
        unset($data['mss_id']);
        $this->db->where('mss_id', $id);
        $this->db->update('_member_saldo_setting' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($mss_id){
        $this->db->where('mss_id',$mss_id);
        $this->db->delete('_member_saldo_setting');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }





}