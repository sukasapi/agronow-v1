<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 16/02/22
 * Time: 22:37
 * @property Scrapper_model scrapper_model
 */

class CronAction extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(['scrapper_model']);
    }

    function getData(){
        $this->_getKurs();
        $this->_getCommodity();
    }

    private function _getKurs(){
        $kurs = $this->scrapper_model->get_day_data('datakurs');
        if (!$kurs){
            $tgl = date("Y-m-d");
            $url = 'https://www.bi.go.id/biwebservice/wskursbi.asmx/getSubKursLokal2?tgl='.$tgl;
            $xmlDocument = file_get_contents($url);
            $xml = new XMLReader();
            $xml->XML($xmlDocument);
            $kurs = [];
            while( $xml->read() ) {
                if($xml->name == "Table") {
                    // echo "ID:" . $xml->getAttribute("diffgr:id") . "<br/>";
                    $node = new SimpleXMLElement($xml->readOuterXML());
                    $kurs[str_replace(' ','',(string)$node->mts_subkurslokal)] = [
                        'value' => (string)$node->nil_subkurslokal,
                        'sell'  => (string)$node->jual_subkurslokal,
                        'buy'   => (string)$node->beli_subkurslokal
                    ];
                    $xml->next();
                }
            }
			if(empty($kurs)) {
				echo "empty data";
			} else {
				$this->scrapper_model->insert_today_data('datakurs', $kurs);
				echo "New kurs data: $tgl";
			}
        } else {
            echo "Data already renewed";
        }
    }

    public function getNeat(){
        getdabs();
        //echo "tes";
    }
    private function _getCommodity(){
        $commodity = $this->scrapper_model->get_day_data('commodity');
        if (!$commodity){
            $base = 'USD';
            $symbol = 'CPO,RUBBER,COFFEE,SUGAR,RICE,WHEAT,CORN,COTTON';
            $url = 'https://www.commodities-api.com/api/latest?access_key=hj87orm7a06022bwssi49379vbshzg3yer9gppk6bvg4qf16drf0youhferi&base='.$base.'&symbols='.$symbol;
            $json = file_get_contents($url);
            $arr = json_decode($json,true);
            $data = [];
            foreach($arr['data']['rates'] as $key => $val) {
                if($key==$base) continue;
                $harga = number_format(1/$val,2,',','.');
                $data[$key]=[
                    $harga,
                    $arr['data']['base'].' '.$arr['data']['unit']
                ];
            }
            $this->scrapper_model->insert_today_data('commodity', $data);
            echo "New commodity data: ".date('Y-m-d');
        } else {
            echo "Data commodity already renewed";
        }
    }

}