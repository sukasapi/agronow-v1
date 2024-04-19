<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Classroom_scan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        //user_is_login();
        $this->load->model(array(
            'classroom_model',
            'classroom_member_model',
            'classroom_attendance_model',
            'category_model',
            'classroom_soal_model',
            'member_model',
            'media_model',
            'member_level_model',
        ));
        $this->section_id = 30;
    }


    // Absensi

    function index(){
        //print_r($data);

        // $data['member']         = $this->classroom_attendance_model->get_all_today();
		
		$data['form_action']      = site_url('classroom_scan/attendance_scan_input_ajax');

        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = 'Absensi';
        $data['page'] = 'classroom_scan/classroom_attendance_scan_view';
        $this->load->view('main_classroom_scan_view',$data);
    }

    function attendance_scan_input_ajax(){

        // Cek Valid
        $barcode_json_b64 = $this->input->post('barcode');
        $barcode_json = base64_decode($barcode_json_b64);
        $barcode = json_decode($barcode_json,TRUE);
        if (!isset($barcode['cr_id']) OR !isset($barcode['member_id'])){
            echo json_encode(['status'    => 400, 'message'   => 'Invalid']);
            exit();
        }

        $member_id = $barcode['member_id'];
        $cr_id = $barcode['cr_id'];

        // Cek Member
        $get_member = $this->member_model->get($member_id);
        if (!$get_member){
            echo json_encode(['status'    => 400, 'message'   => 'Member Not Found']);
            exit();
        }

        // Cek Classroom
        $get_classroom = $this->classroom_model->get($cr_id);
        if (!$get_classroom){
            echo json_encode(['status'    => 400, 'message'   => 'Classroom Not Found']);
            exit();
        }

        // Cek Classroom Member
        $get_classroom_member = $this->classroom_member_model->get_by_classroom_member($cr_id,$member_id);
        if (!$get_classroom_member){
            echo json_encode(['status'    => 400, 'message'   => 'Member Not Found in Classroom']);
            exit();
        }

        // Cek Sesi
        $cr_session = [
            [
                'id'    => 1,
                'start' => '00:00',
                'end'   => '13:00'
            ],
            [
                'id'    => 2,
                'start' => '13:01',
                'end'   => '23:59'
            ]
        ];

        $date_now    = date('Y-m-d');
        $time_now    = date('H:i');

        foreach ($cr_session as $k => $v){
            if (strtotime($time_now) >= strtotime($v['start']) && strtotime($time_now) <= strtotime($v['end'])){
                $session_index = $k;
            }
        }

        $attend_today = array();
        $get_attendance = $this->classroom_attendance_model->get_by_classroom_member($cr_id,$member_id);
        if ($get_attendance){
            foreach ($get_attendance as $v){
                // Get Attend Today
                if ($date_now==date('Y-m-d',strtotime($v['cra_create_date']))){
                    $attend_today[] = $v['cra_create_date'];
                }
            }

            foreach ($attend_today as $v){
                // Cek sudah absen di sesi ini
                $sess_time_start = $cr_session[$session_index]['start'];
                $sess_time_end   = $cr_session[$session_index]['end'];
                if (strtotime($v) >= strtotime($sess_time_start) && strtotime($v) <= strtotime($sess_time_end)){
                    echo json_encode(['status'    => 400, 'message'   => 'Member already attend in this session']);
                    exit();
                }
            }
        }



        $data = array(
            'cr_id' => $cr_id,
            'member_id' => $member_id,
            'cra_channel' => 'cms',
            'cra_create_date' => date('Y-m-d H:i:s')
        );

        $insert_attendance = $this->classroom_attendance_model->insert($data);
        if ($insert_attendance){
            $cra = $this->classroom_attendance_model->get($insert_attendance);
            echo json_encode([
                'status'    => 200,
                'message'   => 'Success',
                'data'      => $cra
            ]);
            exit();
        }else{
            echo json_encode([
                'status'    => 400, 'message'   => 'DB Error',
            ]);
            exit();
        }

    }

    function attendance_json(){
        $get_member = $this->classroom_attendance_model->get_all_today();

        $result = [];
        if ($get_member){
            foreach ($get_member as $k => $v){
				$arrD = explode(' ',$v['cra_create_date']);
				
				$time = $arrD['1'];
				$arr = explode(':', $time);
				if (count($arr) === 3) {
					$s = $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
				}
				// 46859 => 13:00:59 (base on $cr_session)
				$kategori = ($s<=46859)? 'masuk' : 'pulang';
				
				
                $result['data'][] = array(
                    'member_id'       => $v['member_id'],
					'kategori'       => $kategori,
                    'member_name'     => $v['member_name'],
                    'group_name'      => $v['group_name'],
                    'member_nip'      => $v['member_nip'],
					'cr_name'         => '['.$v['cr_id'].'] '.$v['cr_name'],
                    'cra_create_date' => $v['cra_create_date'] // parseDateShortReadable($v['cra_create_date']).', '.parseTimeReadable($v['cra_create_date'])
                );
            }
        } else {
			$result = array();
			$result['data'] = '';
		}

        echo json_encode($result);
    }

	/* -- evaluasi level 3 start -- */
	
	function progress_evaluasi_lv3($tahun_evaluasi=null){
		// $tahun_evaluasi = (int) $tahun_evaluasi;
		
		$addSql = "";
		
		$group_id = 0;
		$key = "";
		$access_key_ok = false;
		$is_show_all = false;
		
		$post = $this->input->post();
		if(!empty($post)) {
			$group_id = (int) $post['group_id'];
			$key = $post['key'];
		}
		
		$sqlT = "select count(p.id_penilai) as jumlah from _classroom_evaluasi_lv3_header h, _classroom_evaluasi_lv3_pairing p where h.tahun_evaluasi='".$tahun_evaluasi."' and h.cr_id=p.cr_id";
		$resT = $this->db->query($sqlT);
		$rowT = $resT->result_array();
		$total_data = $rowT[0]['jumlah'];
		
		// get semua group
		$sqlG = "select group_id, group_name, access_key_tmp from _group where group_status='active' order by length(group_name), group_name ";
		$resG = $this->db->query($sqlG);
		$rowG = $resG->result_array();
		
		// cek akses key
		if(!empty($key)) {
			$sqlG2 = "select group_id from _group where group_status='active' and access_key_tmp='".$key."' ";
			$resG2 = $this->db->query($sqlG2);
			$rowG2 = $resG2->result_array();
			$tmp_id = @$rowG2[0]['group_id'];
			
			if(empty($group_id) && $tmp_id=="15") { // kl access key milik Holding dan group kosong maka tampilkan semua data
				$is_show_all = true;
				$access_key_ok = true;
			} else if($group_id==$tmp_id) {
				$is_show_all = false;
				$access_key_ok = true;
			}
		}
		
		if($is_show_all==false) {
			$addSql .= " and g.group_id='".$group_id."' ";
		}
		
		if($access_key_ok!=true) {
			$addSql .= " and 1=2 ";
		}
		
		// yg belum selesai menilai siapa saja?
		$sql =
			"select 
				g.group_name, 
				c.cr_name, 
				p.status_penilai, p.id_penilai, p.id_dinilai, p.progress, 
				h.tanggal_selesai
			 from _classroom_evaluasi_lv3_pairing p, _classroom c, _classroom_evaluasi_lv3_header h, _member m, _group g 
			 where 
				h.tahun_evaluasi='".$tahun_evaluasi."' and
				c.cr_id=h.cr_id and c.cr_id=p.cr_id and p.id_penilai=m.member_id and g.group_id=m.group_id and p.progress<100 ".$addSql."
			 order by g.group_name, p.id_penilai, c.cr_name ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		
		$data['rowG'] = $rowG;
		$data['row'] = $row;
		$data['tahun_evaluasi'] = $tahun_evaluasi;
		$data['is_show_all'] = $is_show_all;
		$data['total_data'] = $total_data;
		
		$data['request']['group_id'] = $group_id;
		$data['request']['key'] = $key;
		$data['request']['access_key_ok'] = $access_key_ok;
		
		$data['form_action']    = site_url('classroom_scan/progress_evaluasi_lv3/'.$tahun_evaluasi);
        $data['page_name']      = 'Class Room';
        $data['page_sub_name']  = 'Progress Evaluasi Pelatihan Level 3 Tahun '.$tahun_evaluasi;
        $data['page'] = 'classroom_scan/classroom_evaluasi_lv3_progress_view';
        $this->load->view('main_classroom_scan_view',$data);
    }
	
	/* -- evaluasi level 3 end -- */

}