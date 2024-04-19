<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 03/03/21
 * Time: 11:03
 */

if ( !function_exists('check_login')){
    function check_login(){
        $ci = &get_instance();
        $member_id = $ci->session->userdata('member_id');
        if (empty($member_id)){
            if (_check_cookie()){
                return true;
            } else {
                redirect('login');
            }
        } else {
            return true;
        }
    }
}

function _group_id_by_code($code,$personnel_area_code){
    $ci = &get_instance();
	$ci->load->model(array('group_model'));
	
	if($code=="N003" && $personnel_area_code=="1HOL") {
		$group_id_by_code = '15';
	} else {
		$ci->group_model->recData['aghris_company_code'] = $code;
		$get_group = $ci->group_model->select_group("by_aghris_company_code");
		$group_id_by_code = $get_group['group_id'];
	}
	
	if(empty($group_id_by_code)) {
		$group_id_by_code = '34'; // aghris unknown code
	}
	
    return $group_id_by_code;
}

function _check_cookie(){
    $ci = &get_instance();
    $ci->load->model(['member_model','group_model','jabatan_model']);
    $member_nip = $ci->input->cookie('nik_sap');
    if ($member_nip){
        $ci->member_model->recData['memberNip'] = $member_nip;
		$member = $ci->member_model->select_member('byNip');
		if (!$member){
			_save_member();
		} else {
			/*
			if(empty($member['id_level_karyawan'])) {
				_save_member();
			}
			*/
			
			_save_member();
			_save_session($member_nip);
            return true;
		}
    }
    return false;
}

function _save_session($nip){
    $ci = &get_instance();
    $ci->load->model(['member_model','group_model','jabatan_model']);
    $ci->member_model->recData['memberNip'] = $nip;
    $dataMember = $ci->member_model->select_member('byNip');
    if ($dataMember){
		
		$ci->group_model->recData['groupId'] = $dataMember['group_id'];
		$arrG = $ci->group_model->select_group("detail_group_klien_by_id_group");
		
        $data_session = array(
            'member_id'     => $dataMember['member_id'],
            'member_name'   => $dataMember['member_name'],
            'member_nip'    => $dataMember['member_nip'],
            'member_email'  => $dataMember['member_email'],
            'member_phone'  => $dataMember['member_phone'],
            'member_level'  => $dataMember['mlevel_id'],
            'id_klien'  	=> $arrG['id_klien'],
			'member_group'  => $arrG['group_name'],
            'group_id'      => $dataMember['group_id'],
            'member_image'  => $dataMember['member_image'],
            'member_bidang' => $dataMember['member_desc'],
            'ceo_notes_allow' => (intval($dataMember['member_ceo'])==1) ? 1 : 0,
            'bod_share_allow' => (intval($dataMember['member_ceo'])==2) ? 1 : 0,
			'id_level_karyawan' => $dataMember['id_level_karyawan'],
			'kategori_klien' => $arrG['kategori_klien'],
        );

        $ci->session->set_userdata($data_session);
    }
}

function _save_member(){
    $ci = &get_instance();
    $ci->load->model(['member_model','group_model','jabatan_model','member_level_karyawan_model']);
    $member_nip = $ci->session->userdata('member_nip');
    if (!$member_nip){
        $member_nip = $ci->input->cookie('nik_sap');
    }
    if (!$member_nip) return false;
    // $url = 'https://apis.holding-perkebunan.com/aghris/employee_bynik?nik='.$member_nip;
	$url = 'https://apis.holding-perkebunan.com/agronow/employee_bynik?nik='.$member_nip;
    $data = json_decode(file_get_contents($url), true);
    if (!$data || !$data['employee']) return false;
	
    foreach ($data['employee'] as $member){
		// cek groupny dl
		$group_id = _group_id_by_code($member['company_code'],$member['personnel_area_code']);
		if(empty($group_id)) continue;
		
		$jabatan = $ci->jabatan_model->select_jabatan_by_code($member['position_code']);
        $jabatan_id = $jabatan?$jabatan->jabatan_id:null;
        if (!$jabatan){
            $jb_data = [
                'jabatan_name'  => $member['job_descr']?$member['job_descr']:'-',
                'jabatan_code'  => $member['position_code'],
                'jabatan_level' => 9
            ];
            $jabatan_id = $ci->jabatan_model->insert_jabatan($jb_data);
        }
		
		$data = [
            'group_id'      => $group_id,
            'jabatan_id'    => $jabatan_id,
            'mlevel_id'     => $jabatan?$jabatan->jabatan_level:6,
            'member_name'   => $member['employee_name'],
            'member_nip'    => $member['nik_sap'],
            'member_token'  => $member['token'],
            'member_jabatan'=> is_null($member['job_descr'])?'':$member['job_descr'],
            'member_email'  => $member['email'],
            'member_kel_jabatan'=> is_null($member['position_descr'])?'':$member['position_descr'],
            'member_image'  => $member['employee_foto'],
            'member_unit_kerja' => is_null($member['personnel_area_descr'])?'':$member['personnel_area_descr'],
            'member_gender' => ($member['jenis_kelamin']=='1')?'Pria':'Wanita',
            'member_birth_place' => $member['birth_place'],
            'member_birth_date' => $member['birth_date'],
            'member_phone'  => $member['phone'],
            'member_address'=> is_null($member['address'])?'':$member['address'],
            'member_city'   => is_null($member['city'])?'':$member['city'],
            'member_province'=> is_null($member['province'])?'':$member['province'],
            'member_postcode'=> is_null($member['postcode'])?'':$member['postcode'],
            'member_ceo'    => $member['ceo_code'],
            'member_create_date'=> $member['create_date'],
			'id_level_karyawan' => $ci->member_level_karyawan_model->getIDLevelKaryawan(1,$member['bod_minus'])
        ];
        $ci->member_model->recData['memberNip']=$member['nik_sap'];
        $update = $ci->member_model->update_member_api($data, $member['nik_sap']);
        if (!$update){
            $db_member = $ci->member_model->select_member('byNip');
            if (!$db_member){
                $data['member_nip']     = $member['nik_sap'];
                $data['member_poin']    = 0;
                $data['member_saldo']   = 0;
                $ci->member_model->insert_member_api($data);
                _check_cookie();
            }
        }
    }

    $url = 'https://apis.holding-perkebunan.com/aghris/employee_token_fb_bynik?nik='.$member_nip;
    $data = json_decode(file_get_contents($url), true);
    if (!$data && !$data['employee']) return;
    foreach ($data['employee'] as $member){
        $ci->member_model->recData['memberNip'] = $member_nip;
        $db_member = $ci->member_model->select_member('byNip');

        $tData['token'] = $member['token'];
        $tData['memberId'] = $db_member['member_id'];
        $d_token = $ci->member_model->select_member_device_token('byToken', $tData, 'Y');
        if (!$d_token){
            $ci->member_model->insert_member_device_token($db_member['member_id'], $member['token']);
        }
    }
}

if ( !function_exists('fetchAndUpdateMember')){
    function fetchAndUpdateMember(){
        $ci = &get_instance();
        $last_update = $ci->session->userdata('last_update');
        if ($last_update != date('Y-m-d')){
            _save_member();
            $ci->session->set_userdata('last_update', date('Y-m-d'));
        }
    }
}

if ( !function_exists('validate_member_image')){
    function validate_member_image($image_url){
        if ($image_url && $image_url != '#'){
            $has_url = stristr($image_url, 'https://') || stristr($image_url, 'http://') || false;
            if (!$has_url){
                if (file_exists(MEDIA_IMAGE_PATH.$image_url)){
                    $image_url = URL_MEDIA_IMAGE.$image_url;
//                    $image_url = MEDIA_IMAGE_PATH.$image_url;
                } else {
                    $image_url = PATH_ASSETS.'img/avatar.png';
                }
            }
        } else {
            $image_url = PATH_ASSETS.'img/avatar.png';
        }
        return $image_url;
    }
}