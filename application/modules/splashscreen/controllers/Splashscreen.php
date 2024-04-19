<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Splashscreen extends MX_Controller {	
	function __construct(){
        parent::__construct();
        $this->load->model(['quotes_model','member_model','group_model']);
    }

	function index(){
		$this->load->view('splashscreen');
	}

    function get_quotes(){
        $quotes = $this->quotes_model->select_quotes("random",1);
        if(count($quotes) == 1){
            $data['quotes_text'] = $quotes[0]['quotes_text'];
            $data['quotes_author'] = $quotes[0]['quotes_author'];
        }else{
            $data['quotes_text'] = '';
            $data['quotes_author'] = '';
        }
        exit(json_encode($data));
    }
    
    function is_loggedin(){
	    $member_id = $this->session->userdata('member_id');
		if (empty($member_id)){
		    if ($this->_check_cookie()){
                $data['status'] = 'OK';
            } else {
                $data['status'] = 'FAIL';
            }
		}else{
            $data['status'] = 'OK';
        }
        exit(json_encode($data));
	}

	private function _check_cookie(){
        $member_nip = $this->input->cookie('nik_sap');
        if ($member_nip){
            $this->member_model->recData['memberNip'] = $member_nip;
            $dataMember = $this->member_model->select_member('byNip');
            if ($dataMember){
				$this->group_model->recData['groupId'] = $dataMember['group_id'];
				$arrG = $this->group_model->select_group("byId");
				
                $data_session = array(
                    'member_id'     => $dataMember['member_id'],
                    'member_name'   => $dataMember['member_name'],
                    'member_nip'    => $dataMember['member_nip'],
                    'member_email'  => $dataMember['member_email'],
                    'member_phone'  => $dataMember['member_phone'],
                    'member_level'  => $dataMember['mlevel_id'],
                    'id_klien'  	=> $arrG['id_klien'],
					'member_group'  => $arrG['group_name'],
					'aghris_company_code'  => $arrG['aghris_company_code'],
                    'group_id'      => $dataMember['group_id'],
                    'member_image'  => $dataMember['member_image'],
                    'member_bidang' => $dataMember['member_desc'],
                    'ceo_notes_allow' => (intval($dataMember['member_ceo'])==1) ? 1 : 0,
                    'bod_share_allow' => (intval($dataMember['member_ceo'])==2) ? 1 : 0,
					'id_level_karyawan' => $dataMember['id_level_karyawan'],
					'session_source' => 'splashscreen',
                );

                $this->session->set_userdata($data_session);
                return true;
            }
        }

        return false;
    }
}
