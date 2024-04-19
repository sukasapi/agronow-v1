<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Scrapper_model scrapper_model
 */
class Exchange_rate extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
		$this->load->model(['scrapper_model']);
    }

	function index(){
        $kurs = $this->scrapper_model->get_day_data('datakurs');
		$result = $kurs;
		$this->data['title'] = 'Exchange Rate';
		$this->page = 'exchange_rate';
		$this->menu = 'whatsnew';
		$this->data['data'] = $result;
		$this->data['date'] = date('d M Y');
		$this->generate_layout();
	}

//	function getKurs(){
//        $tgl = date("Y-m-d");
//        $url = 'https://www.bi.go.id/biwebservice/wskursbi.asmx/getSubKursLokal2?tgl='.$tgl;
//        $xmlDocument = file_get_contents($url);
//
//        echo '<b>'.$tgl.'</b><br/><br/>';
//
//        $xml = new XMLReader();
//        $xml->XML($xmlDocument);
//        $kurs = [];
//        while( $xml->read() ) {
//            if($xml->name == "Table") {
//                // echo "ID:" . $xml->getAttribute("diffgr:id") . "<br/>";
//                $node = new SimpleXMLElement($xml->readOuterXML());
//                $kurs[(string)$node->mts_subkurslokal] = [
//                    'value' => (string)$node->nil_subkurslokal,
//                    'sell'  => (string)$node->jual_subkurslokal,
//                    'buy'   => (string)$node->beli_subkurslokal
//                ];
//                $xml->next();
//            }
//        }
//        echo json_encode($kurs);
//    }
//
//    private function _get_kurs(){
//        $url = file_get_contents('https://www.bi.go.id/id/statistik/informasi-kurs/transaksi-bi/Default.aspx');
//        if (!$url) return NULL;
//        $DOM = new DOMDocument();
//        @$DOM->loadHTML($url);
//
//        $dataTable = $DOM->getElementsByTagName('table');
//        //  print_r($dataTable);
//        $data = explode("\r\n",$dataTable[1]->textContent);
//        // print_r($data);
//        $kurs = array();
//        for($i=10;$i<count($data)-2;$i++){
//            $idx = ($i -4)%6 ;
//            if($idx%6==0){
//                // print_r($data[$i]);
//                $data[$i] = trim($data[$i]);
//                $kurs[$data[$i]] = array();
//            }
//            else{
//                // print_r("sini 1");
//                if(in_array($idx,array("1","2","3"))){
//                    // print_r("sini 2");
//                    $dataIndex = trim($data[$i-$idx  ]);
//
//                    if($idx == 1) $kurs[$dataIndex]["value"] = trim($data[$i]);
//                    if($idx == 2) $kurs[$dataIndex]["sell"] = trim($data[$i]);
//                    if($idx == 3) $kurs[$dataIndex]["buy"] = trim($data[$i]);
//
//
//                    // $sell = substr($arrSb[1],2,strlen($arrSb[1])).".".substr($arrSb[2],0,2);
//                    // $buy = substr($arrSb[2],2,strlen($arrSb[2])).".".substr($arrSb[3],0,2);
//                    // $kurs[$currency] = array("value"=>$value,"sell"=>$sell,"buy"=>$buy);
//
//                    /*if($idx==1){$newData[$dataIndex]['Harga'] = trim($data[$i]);}
//                    if($idx==2){$newData[$dataIndex]['Persen'] = trim($data[$i]);}
//                    if($idx==4){$newData[$dataIndex]['Satuan'] = trim($data[$i]);}*/
//                }
//            }
//        }
//        return $kurs;
//    }
}
