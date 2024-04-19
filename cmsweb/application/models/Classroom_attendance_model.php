<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom_attendance_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function get_all($limit=100){
        $this->db->select('_classroom_attendance.*,
        _group.group_name, _member.member_name,_member.member_nip, _member.member_image,
        _classroom.cr_name,');
        $this->db->from('_classroom_attendance');

        $this->db->join('_classroom', '_classroom.cr_id = _classroom_attendance.cr_id', 'left');
        $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->order_by('cra_create_date', 'desc');
        $this->db->limit($limit);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }
	
	function get_all_today(){
        $this->db->select('_classroom_attendance.*,
        _group.group_name, _member.member_name,_member.member_nip, _member.member_image,
        _classroom.cr_name,');
        $this->db->from('_classroom_attendance');

        $this->db->join('_classroom', '_classroom.cr_id = _classroom_attendance.cr_id', 'left');
        $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');
		
		$this->db->like('cra_create_date', date('Y-m-d'), 'both');
		// $this->db->like('cra_create_date', '2023-06-16', 'both');

        $this->db->order_by('cra_create_date', 'desc');
        // $this->db->limit($limit);

        $query      = $this->db->get();
        $result     = $query->result_array();
		
		// echo $this->db->last_query();exit;

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get($classroom_member_id){

        $this->db->select('_classroom_attendance.*,
        _group.group_name, _member.member_name,_member.member_nip, _member.member_image,
        _classroom.cr_name,');
        $this->db->from('_classroom_attendance');

        $this->db->join('_classroom', '_classroom.cr_id = _classroom_attendance.cr_id', 'left');
        $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cra_id', $classroom_member_id);
        $this->db->order_by('cra_create_date', 'desc');

        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }



    function count_by_classroom($classroom_id){
        $this->db->select('COUNT(*) as total');
        $this->db->from('_classroom_attendance');

        $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $classroom_id);


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result[0]; } else { return FALSE; }
    }

    function get_by_classroom($classroom_id,$filter=NULL){
        $this->db->select('_classroom_attendance.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_classroom_attendance');

        $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $classroom_id);
        $this->db->order_by('cra_create_date', 'desc');

        if($filter)
        {
            if($filter['date-begin']){
                $this->db->where('DATE(cra_create_date) >=', parseDate($filter['date-begin']));
            }

            if($filter['date-end']){
                $this->db->where('DATE(cra_create_date) <=', parseDate($filter['date-end']));
            }
        }


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function get_by_classroom_member($classroom_id,$member_id){
        $this->db->select('_classroom_attendance.*,_group.group_name, _member.member_name,_member.member_nip, _member.member_image');
        $this->db->from('_classroom_attendance');

        $this->db->join('_member', '_member.member_id = _classroom_attendance.member_id', 'left');
        $this->db->join('_group', '_group.group_id = _member.group_id', 'left');

        $this->db->where('cr_id', $classroom_id);
        $this->db->where('_classroom_attendance.member_id', $member_id);
        $this->db->order_by('cra_create_date', 'desc');


        $query      = $this->db->get();
        $result     = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }

    function insert($data){
        $this->db->insert('_classroom_attendance', $data);
        return $this->db->affected_rows() > 0 ?  $this->db->insert_id() : FALSE;
    }

    function update($data){
        $id = $data['cra_id'];
        unset($data['cra_id']);
        $this->db->where('cra_id', $id);
        $this->db->update('_classroom_attendance' ,$data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    function delete($classroom_member_id){
        $this->db->where('cra_id',$classroom_member_id);
        $this->db->delete('_classroom_attendance');

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }




}
