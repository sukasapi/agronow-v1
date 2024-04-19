<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getFundRequest')){
    function getFundRequest($fund_request_id){
        $ci = &get_instance();
        $ci->load->model('fund_request_model');

        $get_fund_request = $ci->fund_request_model->get($fund_request_id);
        if ($get_fund_request==FALSE){
            redirect(404);
        }else{
            return $get_fund_request;
        }
    }
}

if ( !function_exists('getFundRequestLine')){
    function getFundRequestLine($fund_request_id){
        $ci = &get_instance();
        $ci->load->model('fund_request_line_model');

        $get_fund_request_line = $ci->fund_request_line_model->get_by_fund_request($fund_request_id);
        return $get_fund_request_line;
    }
}

if ( !function_exists('getFundRequestFile')){
    function getFundRequestFile($fund_request_id){
        $ci = &get_instance();
        $ci->load->model('fund_request_file_model');

        $get_fund_request_file = $ci->fund_request_file_model->get_by_fund_request($fund_request_id);
        return $get_fund_request_file;
    }
}

if ( !function_exists('getFundRequestPayment')){
    function getFundRequestPayment($fund_request_id){
        $ci = &get_instance();
        $ci->load->model('payment_model');

        $get_payment = $ci->payment_model->get_by_fund_request($fund_request_id);
        return $get_payment;
    }
}

if ( !function_exists('totalAmountFundRequestPayment')){
    function totalAmountFundRequestPayment($fund_request_id){
        $ci = &get_instance();
        $ci->load->model('payment_model');

        $get_payment = $ci->payment_model->total_amount_by_fund_request($fund_request_id);
        return $get_payment;
    }
}