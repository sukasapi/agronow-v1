<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('classroomProgressModuleMateri')){
    function classroomProgressModuleMateri($crm_step_json,$cr_id,$module_index){
        $ci = &get_instance();
        $ci->load->model('classroom_model');

        $classroom = $ci->classroom_model->get($cr_id);
        if (!$classroom){
            return NULL;
        }

        $module = array();
        if($classroom['cr_module']){

            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $classroom['cr_module']);
            $cr_module = json_decode($json_cr_module,TRUE);

            if (isset($cr_module['Module'])){
                $module = $cr_module['Module'];
            }
        }


        $crm_step_arr = json_decode($crm_step_json,TRUE);
        if (!isset($crm_step_arr['MP'][$module_index]['Materi'])){
            return NULL;
        }

        $result = array();
        $step_module_materi = $crm_step_arr['MP'][$module_index]['Materi'];
        foreach ($step_module_materi as $k => $v){

            // 2 = Read
            if ($v==2){

                $data = array(
                    'MateriNo'      => $k+1,
                    'MateriName'    => isset($module[$module_index]['Materi'][$k]['ContentName']) ? $module[$module_index]['Materi'][$k]['ContentName'] : NULL,
                    'MateriType'    => isset($module[$module_index]['Materi'][$k]['Type']) ? $module[$module_index]['Materi'][$k]['Type'] : NULL,
                    'MateriMedia'   => isset($module[$module_index]['Materi'][$k]['Media']) ? $module[$module_index]['Materi'][$k]['Media'] : NULL,
                    'MateriRead'    => isset($crm_step_arr['MP'][$module_index]['MateriRead'][$k]) ? $crm_step_arr['MP'][$module_index]['MateriRead'][$k] : NULL
                );
                $result[] = $data;

            }

        }

        return $result;


    }
}


 

if ( !function_exists('classroomProgressModulePercent')){
    function classroomProgressModulePercent($crm_step_json,$module_index,$cr_has_learning_point){

        $total_item = 2;    // learning point/evaluasi dan feedback
        $achieve_item = 0;

        $crm_step_arr = json_decode($crm_step_json,TRUE);
        if (isset($crm_step_arr['MP'][$module_index]['Materi'])){

            $step_module_materi = $crm_step_arr['MP'][$module_index]['Materi'];
            foreach ($step_module_materi as $k => $v){

                $total_item = $total_item + 1;

                // 2 = Read
                if ($v==2){
                    $achieve_item = $achieve_item + 1;
                }
            }
        }


        if (isset($crm_step_arr['MP'][$module_index]['FbStatus'])){

            // 2 = Submited
            if ($crm_step_arr['MP'][$module_index]['FbStatus']==2){
                $achieve_item = $achieve_item + 1;
            }
        }


        if ($cr_has_learning_point){
            if (isset($crm_step_arr['MP'][$module_index]['LearningPoint']['status'])){

                // 2 = Submited
                if ($crm_step_arr['MP'][$module_index]['LearningPoint']['status']==2){
                    $achieve_item = $achieve_item + 1;
                }
            }
        }else{
            if (isset($crm_step_arr['MP'][$module_index]['EvaStatus'])){

                // 2 = Submited
                if ($crm_step_arr['MP'][$module_index]['EvaStatus']==2){
                    $achieve_item = $achieve_item + 1;
                }
            }
        }


        if ($achieve_item!=0){
            return $achieve_item/$total_item*100;
        }else{
            return "0";
        }


    }
}


if ( !function_exists('is_classroom_editable')){
    function is_classroom_editable($cr_id){

        $ci = &get_instance();
        $ci->load->model('classroom_model');

        $classroom = $ci->classroom_model->get($cr_id);

        $editable = TRUE;
        if(!has_access('classroom.edit',FALSE)){
            if ( has_access('classroom.edit.own',FALSE)){
                if(user_id() != $classroom['id_petugas']){
                    $editable = FALSE;
                }
            }else{
                $editable = FALSE;
            }
        }

        return $editable;

    }
}

if ( !function_exists('memberstat')){
    function memberstat($m_id=null,$cr_id=null){
        $CI = &get_instance();
        $where=array("cr_id"=>$cr_id,"member_id"=>$m_id);
        $CI->db->select('member_status');
        $CI->db->from('_classroom_member');
        $CI->db->where($where);
        $query = $CI->db->get()->result();
        if(count((array)$query) > 0){
            return $query;
        }else{
            return array();
        }
    }
}


function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}


if ( !function_exists('PinGenerator')){
    function PinGenerator($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
        }
        return $token;
    }
}