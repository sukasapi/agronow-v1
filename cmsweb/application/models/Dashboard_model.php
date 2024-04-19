<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct(){
        parent::__construct(); //inherit dari parent
        $this->load->database();
    }


    function member_count_all($reg_channel = NULL){
        $this->db->from('_member');
        if ($reg_channel!=NULL){
            if ($reg_channel == 'web'){
                $this->db->where('member_login_web <> ""');
            }
            if ($reg_channel == 'android'){
                $this->db->where('member_login_apk <> ""');
            }
            if ($reg_channel == 'ios'){
                $this->db->where('member_login_ipa <> ""');
            }

        }
        return $this->db->count_all_results();
    }

    function member_get_all($reg_channel = NULL){
        $this->db->select('_member.*,_member_level.mlevel_id,_member_level.mlevel_name,_group.group_name, _jabatan.jabatan_name');
        $this->db->from('_member');

        $this->db->join('_jabatan','_jabatan.jabatan_id=_member.jabatan_id','LEFT');
        $this->db->join('_member_level','_member.mlevel_id=_member_level.mlevel_id','LEFT');
        $this->db->join('_group','_member.group_id=_group.group_id','LEFT');

        if ($reg_channel!=NULL){
            if ($reg_channel == 'web'){
                $this->db->where('member_login_web <> ""');
            }
            if ($reg_channel == 'android'){
                $this->db->where('member_login_apk <> ""');
            }
            if ($reg_channel == 'ios'){
                $this->db->where('member_login_ipa <> ""');
            }

        }

        $query  = $this->db->get();
        $result = $query->result_array();

        return $result;
    }


    function ads_count_all(){
        $this->db->from('_ads');
        $this->db->where('ads_status','active');
        return $this->db->count_all_results();
    }

    function content_download_count_all(){
        $this->db->from('_content_download');
        return $this->db->count_all_results();
    }

    function content_elearning_count_all(){
        $this->db->from('_content');
        $this->db->where('section_id','18');
        return $this->db->count_all_results();
    }


    function get_new_member(){
        $this->db->select('_member.*,_member_level.mlevel_id,_member_level.mlevel_name,_group.group_id,_group.group_name');
        $this->db->from('_member');
        $this->db->join('_member_level','_member.mlevel_id=_member_level.mlevel_id','LEFT');
        $this->db->join('_group','_member.group_id=_group.group_id','LEFT');
        $this->db->order_by('member_create_date','DESC');
        $this->db->limit(10);

        $query  = $this->db->get();
        $result = $query->result_array();

        if($query->num_rows() > 0){ return $result; } else { return FALSE; }
    }


}
