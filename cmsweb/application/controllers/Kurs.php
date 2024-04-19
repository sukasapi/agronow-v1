<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kurs extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();

        $this->load->model(array(
            'kurs_model'
        ));
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->kurs_model->get_last();
        $data = array();
        foreach ($list as $k => $item) {
            $row = array();
            $row['currency']    = $k;
            $row['value']  = number_format($item['value'],2,',','.') ;
            $row['sell']  = number_format($item['sell'],2,',','.') ;
            $row['buy']  = number_format($item['buy'],2,',','.') ;


            $data[] = $row;
        }

        $output = array(
            "recordsTotal" => sizeof($list),
            "recordsFiltered" => sizeof($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_kurs($kurs_id){
        $get_kurs = $this->kurs_model->get($kurs_id);
        if ($get_kurs==FALSE){
            redirect(404);
        }else{
            return $get_kurs;
        }
    }

    function index(){
        has_access('kurs.view');

        $data['page_name']          = 'Kurs';
        $data['page_sub_name']      = 'List Kurs';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'kurs/kurs_list_view';
        $this->load->view('main_view',$data);
    }

    function get_data_cron(){
        file_get_contents("https://agronow.co.id/cronAction/getData");
        flash_notif_success('Data berhasil disimpan',site_url('kurs'));
    }

    /*function getKurs(){
        date_default_timezone_set("Asia/Jakarta");
        //exit;
        $url = file_get_contents('http://www.bi.go.id/en/moneter/informasi-kurs/transaksi-bi/Default.aspx');
        //echo $data;exit;
        $DOM = new DOMDocument();
        @$DOM->loadHTML($url);

        $dataTable = $DOM->getElementsByTagName('table');
        $data = explode("\r\n",$dataTable[5]->textContent);
        // print_r($data);
        $kurs = array();
        for($i=1;$i<count($data)-2;$i++){

            $row = trim($data[$i]);
            if (isset($row)) {
                $currency = substr($row,0,3);
                $sb = trim(str_replace($currency,"",$row));
                $arrSb = explode(".",$sb);
                if (isset($arrSb[0]) && isset($arrSb[1])) {
                    $value = $arrSb[0].".".substr($arrSb[1],0,2);
                    $sell = substr($arrSb[1],2,strlen($arrSb[1])).".".substr($arrSb[2],0,2);
                    $buy = substr($arrSb[2],2,strlen($arrSb[2])).".".substr($arrSb[3],0,2);
                    $kurs[$currency] = array("currency"=>$currency,"value"=>$value,"sell"=>$sell,"buy"=>$buy);
                    $i = $i+1;
                }
            }
        }

        return $kurs;
    }*/





}