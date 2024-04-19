<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('aghris_group_id_by_code')) {
    function aghris_group_id_by_code($code){

        $ci = &get_instance();
        $ci->load->model(array('group_model'));
		
		$get_group = $ci->group_model->select_group_by_code($code);
		$group_id = $get_group['group_id'];
		
		/*
        $get_group = $ci->group_model->get_all();

        $group_id_by_code = array();
        foreach ($get_group['data'] as $k => $v){
            $group_id_by_code[$v['aghris_company_code']] = $v['group_id'];
        }

        $group_id_by_code = [
            'N001'  => 1,
            'N002'  => 2,
            'N003'  => 3,
            'N006'  => 6,
            'N007'  => 7,
            'N008'  => 8,
            'N00D'  => 4,
            'N00E'  => 5,
            'N00I'  => 9,
            'N010'  => 10,
            'N011'  => 11,
            'N012'  => 12,
            'N013'  => 13,
            'N014'  => 14
        ];
		*/

        return isset($group_id) ? $group_id : NULL;
    }
}

if (!function_exists('aghris_group_name_by_code')) {
    function aghris_group_name_by_code($code){

        $ci = &get_instance();
        $ci->load->model(array('group_model'));
		
		$get_group = $ci->group_model->select_group_by_code($code);
		$group_name = $get_group['group_name'];
		
		/*
        $get_group = $ci->group_model->get_all();
		
        $group_id_by_code = array();
        foreach ($get_group['data'] as $k => $v){
            $group_id_by_code[$v['aghris_company_code']] = $v['group_name'];
        }
		
		$group_id_by_code = [
            'N001'  => 'PTPN 1',
            'N002'  => 'PTPN 2',
            'N003'  => 'PTPN 3',
            'N006'  => 'PTPN 6',
            'N007'  => 'PTPN 7',
            'N008'  => 'PTPN 8',
            'N00D'  => 'PTPN 4',
            'N00E'  => 'PTPN 5',
            'N00I'  => 'PTPN 9',
            'N010'  => 'PTPN 10',
            'N011'  => 'PTPN 11',
            'N012'  => 'PTPN 12',
            'N013'  => 'PTPN 13',
            'N014'  => 'PTPN 14',
        ];
		*/
		
        return isset($group_name) ? $group_name : '';
    }
}


if (!function_exists('aghris_search_by_nik')) {
    function aghris_search_by_nik($nik=NULL){
        if ($nik==NULL){
            return FALSE;
        }

        $url = "https://apis.holding-perkebunan.com/agronow/employee_bynik?nik=".urlencode($nik);
        $data = json_decode(file_get_contents($url), true);
        if (!$data && !$data['employee']){
            return FALSE;
        }else{
            return $data['employee'];
        }

    }
}


if (!function_exists('aghris_search_by_name')) {
    function aghris_search_by_name($name=NULL){
        if ($name==NULL){
            return FALSE;
        }

        $url = "https://apis.holding-perkebunan.com/agronow/employee_bynama?keyword=".urlencode($name);
        $data = json_decode(file_get_contents($url), true);
        if (!$data && !$data['employee']){
            return FALSE;
        }else{
            return $data['employee'];
        }

    }
}


if (!function_exists('aghris_search_by_jabatan')) {
    function aghris_search_by_jabatan($jabatan=NULL){
        if ($jabatan==NULL){
            return FALSE;
        }

        $url = "https://apis.holding-perkebunan.com/agronow/employee_byjabatan?keyword=".urlencode($jabatan);
        $data = json_decode(file_get_contents($url), true);
        if (!$data && !$data['employee']){
            return FALSE;
        }else{
            return $data['employee'];
        }

    }
}

if (!function_exists('aghris_search_by_nohp')) {
    function aghris_search_by_nohp($nohp=NULL){
        if ($nohp==NULL){
            return FALSE;
        }

        $url = "https://apis.holding-perkebunan.com/agronow/employee_bynohp?nohp=".urlencode($nohp);
        $data = json_decode(file_get_contents($url), true);
        if (!$data && !$data['employee']){
            return FALSE;
        }else{
            return $data['employee'];
        }

    }
}

