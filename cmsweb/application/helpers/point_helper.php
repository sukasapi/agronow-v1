<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('validate_point_trx'))
{
    function validate_point_trx($member_id,$source,$type,$amount)
    {
        $ci = &get_instance();
        $ci->load->model('member_model');

        $get_member = $ci->member_model->get($member_id);
        if ($get_member==FALSE){
            $result = array(
                'status' => FALSE,
                'msg'    => 'Member could not be found'
            );
            return $result;
        }

        $arr_source = array('balance','frozen');
        if (!in_array($source,$arr_source)){
            $result = array(
                'status' => FALSE,
                'msg'    => 'Source could not be found. [balance,frozen]'
            );
            return $result;
        }

        $arr_type = array('in','out');
        if (!in_array($type,$arr_type)){
            $result = array(
                'status' => FALSE,
                'msg'    => 'Type could not be found. [in,out]'
            );
            return $result;
        }

        if (!is_numeric($amount)){
            $result = array(
                'status' => FALSE,
                'msg'    => 'Amount should be numeric'
            );
            return $result;
        }


        if ($source=='balance'){
            $member_point = $get_member['point_balance'];
            if ($type=='out'){
                if ($amount > $member_point){
                    $result = array(
                        'status' => FALSE,
                        'msg'    => 'Insufficient point balance'
                    );

                }else{
                    $result = array(
                        'status' => TRUE,
                        'msg'    => 'Valid point balance'
                    );
                }
                return $result;
            }
        }elseif ($source=='frozen'){
            $member_point = $get_member['point_frozen_balance'];
            if ($type=='out'){
                if ($amount > $member_point){
                    $result = array(
                        'status' => FALSE,
                        'msg'    => 'Insufficient point frozen balance'
                    );

                }else{
                    $result = array(
                        'status' => TRUE,
                        'msg'    => 'Valid point frozen balance'
                    );
                }
                return $result;
            }
        }




    }
}





