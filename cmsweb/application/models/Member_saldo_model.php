<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_saldo_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_member_saldo';
    var $column_order = array('_member_saldo.ms_id','_member.member_name','_group.group_name','_member.member_nip','_member_saldo.ms_section','_member_saldo.ms_saldo','_member_saldo.ms_name','_member_saldo.ms_source','_classroom.cr_name','_member_saldo.ms_create_date'); //set column field database for datatable orderable
    var $column_search = array('_member.member_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_member.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_member_saldo.*, _member.member_name, _member.member_nip, _group.group_id,_group.group_name, _classroom.cr_id,_classroom.cr_name');

        $this->db->from($this->table);

        $this->db->join('_classroom','_classroom.cr_id=_member_saldo.cr_id','LEFT');

        $this->db->join('_member','_member.member_id=_member_saldo.member_id','LEFT');
        $this->db->join('_group','_member.group_id=_group.group_id','LEFT');

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


    function insert_batch($data){
        $this->db->insert_batch('_member_saldo', $data);
        return $this->db->affected_rows() > 0 ?  TRUE : FALSE;
    }

    function check_reward_given($member_id,$year){
        $this->db->select('_member_saldo.*');
        $this->db->from('_member_saldo');
        $this->db->where('ms_type', 'IN');
        $this->db->where('ms_source', 'Reward');

        $this->db->where('member_id', $member_id);
        $this->db->where('YEAR(ms_create_date)', $year);

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function member_saldo_sync(){
        $this->db->trans_begin();

        $this->db->select('_member_saldo_yearly_view.*');
        $this->db->from('_member_saldo_yearly_view');
        $this->db->where('year', date('Y'));
        $query               = $this->db->get();
        $member_saldo_yearly = $query->result_array();

        foreach ($member_saldo_yearly as $v){
            $updateArray[] = array(
                'member_id'    => $v['member_id'],
                'member_saldo' => $v['saldo'],
            );
        }
        $this->db->update_batch('_member',$updateArray, 'member_id');


        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }
        else{
            $this->db->trans_commit();
        }
    }

}
