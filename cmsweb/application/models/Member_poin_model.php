<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_poin_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    /* DATATABLE BEGIN */
    var $table = '_member_poin';
    var $column_order = array('_member_poin.mp_id','_member.member_name','_group.group_name','_member.member_nip','_member_poin.mp_section','_member_poin.mp_poin','_member_poin.mp_name','_content.content_name','_member_poin.mp_create_date'); //set column field database for datatable orderable
    var $column_search = array('_member.member_name'); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        //add custom filter here

        /*if($this->input->get('cat_id')){
            $this->db->where('_member.cat_id',$this->input->get('cat_id'));
        }*/

        $this->db->select('_member_poin.*, _member.member_name,_member.member_nip, _group.group_id,_group.group_name, _content.content_name, _content.content_id');

        $this->db->from($this->table);

        $this->db->join('_content','_content.content_id=_member_poin.mp_content_id','LEFT');

        $this->db->join('_member','_member.member_id=_member_poin.member_id','LEFT');
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



}
