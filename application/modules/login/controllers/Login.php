<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Member_model member_model
 * @property CI_Input input
 * @property CI_Form_validation form_validation
 * @property Function_api function_api
 * @property Group_model group_model
 */
class Login extends MX_Controller {
	function __construct(){
        parent::__construct();
        $this->load->library('function_api');
        $this->load->model(['member_model', 'group_model', 'media_model', 'forum_model', 'forumGroup_model']);
    }

	function index(){
        $this->load->view('login');
	}
	
	/*
	 * jika nambah opsi login jangan lupa diregistrasikan di file config/routes.php
	 */
	
	function login_agronow(){
        if(! $this->session->userdata('member_id')){
            // $groups = $this->group_model->select_group("active");
			
			$id_klien = "1";
			
			$sql = "select * from _group where group_status='active' and id_klien='".$id_klien."' and (aghris_company_code is null or aghris_company_code='') order by group_name";
			$res = $this->db->query($sql);
			$groups = $res->result_array();
			
			$data = [
                'groups' => $groups
            ];
            $this->load->view('login_agronow', $data);
        } else {
            redirect('home');
        }
	}
	
	function login_ipfi(){
        if(! $this->session->userdata('member_id')){
			$id_klien = "2";
			
            $sql = "select * from _group where group_status='active' and id_klien='".$id_klien."' order by group_name";
			$res = $this->db->query($sql);
			$groups = $res->result_array();
			
			$data = [
                'groups' => $groups
            ];
            $this->load->view('login_ipfi', $data);
        } else {
            redirect('home');
        }
	}
	
	function login_umum(){
        if(! $this->session->userdata('member_id')){
            // $groups = $this->group_model->select_group("active");
			
			$id_klien = "3";
			
			$sql = "select * from _group where group_status='active' and id_klien='".$id_klien."' order by group_name";
			$res = $this->db->query($sql);
			$groups = $res->result_array();
			
			$data = [
                'groups' => $groups
            ];
            $this->load->view('login_umum', $data);
        } else {
            redirect('home');
        }
	}

	function login_aghris(){
        if(! $this->session->userdata('member_id')){
            $this->load->view('login_aghris');
        } else {
            redirect('home');
        }
    }

    function authAghris(){
        $nik = $this->input->post('nik', TRUE);
        $password = $this->input->post('password',TRUE);
        if (!empty($nik) && !empty($password)){
            $url = "https://apis.holding-perkebunan.com/access/login?niksap={$nik}&password={$password}&status-login=1";
            $data = json_decode(file_get_contents($url), true);
            if ($data[0]['NIK_SAP'] == $nik){
                $this->session->set_userdata(['member_nip'=>$nik]);
                $this->member_model->recData['memberNip'] = $nik;
				/*
                $member = $this->member_model->select_member('byNip');
				if (!$member || empty($member['id_level_karyawan'])){
                    _save_member();
                    $member = $this->member_model->select_member('byNip');
				}
				*/
				
				_save_member();
                $member = $this->member_model->select_member('byNip');
				
				$this->group_model->recData['groupId'] = $member['group_id'];
				$arrG = $this->group_model->select_group("detail_group_klien_by_id_group");
				
				$data_session = array(
                    'device_token'  => '',
                    'member_id'     => $member['member_id'],
                    'member_name'   => $member['member_name'],
                    'member_nip'    => $member['member_nip'],
                    'member_email'  => $member['member_email'],
                    'member_phone'  => $member['member_phone'],
                    'member_level'  => $member['mlevel_id'],
                    'id_klien'  	=> $arrG['id_klien'],
					'member_group'  => $arrG['group_name'],
                    'group_id'      => $member['group_id'],
                    'member_image'  => $member['member_image'],
                    'member_bidang' => $member['member_desc'],
                    'ceo_notes_allow' => (intval($member['member_ceo'])==1) ? 1 : 0,
                    'bod_share_allow' => (intval($member['member_ceo'])==2) ? 1 : 0,
					'id_level_karyawan' => $member['id_level_karyawan'],
					'kategori_klien' => $arrG['kategori_klien'],
                );


                $this->session->set_userdata($data_session);
                redirect('home');
            } else {
                $this->session->set_flashdata('item', array('message' => $this->function_api->msg['22']));
            }
        } else {
            $this->session->set_flashdata('item', array('message' => $this->function_api->msg['no_username_pass']));
        }
        redirect('login/login_aghris');
    }

	function auth(){
		$group_id = $this->input->post('group_id',TRUE);
        $nip = $this->input->post('nip', TRUE);
        $password = md5($this->input->post('password',TRUE));
        if (!empty($group_id) && !empty($nip) && !empty($password)){
            $dataMember = $this->member_model->select_member_login($nip, $password, $group_id);
            if (!$dataMember){
                $this->session->set_flashdata('item', array('message' => $this->function_api->msg['22']));
            } elseif ($dataMember->member_status=='block'){
                $this->session->set_flashdata('item', array('message' => $this->function_api->msg['12']));
            } else {
                $token = $this->input->cookie('an_dtoken');
                if ($token){
                    $recData = ['memberId'=>$dataMember->member_id,'token'=>$token];
                    $member_dtoken = $this->member_model->select_member_device_token('byToken', $recData);
                    if ($member_dtoken){
                        if ($member_dtoken['is_active']!='Y'){
                            $this->member_model->update_member_device_token($dataMember->member_id, $token, 'Y');
                        }
                    } else {
                        $this->member_model->insert_member_device_token($dataMember->member_id, $token);
                    }
                }
				
				$this->group_model->recData['groupId'] = $dataMember->group_id;
				$arrG = $this->group_model->select_group("detail_group_klien_by_id_group");
				$kategori_klien = $arrG['kategori_klien'];
				
                $data_session = array( 
                    'device_token'  => $token,
                    'member_id'     => $dataMember->member_id,
                    'member_name'   => $dataMember->member_name,
                    'member_nip'    => $dataMember->member_nip,
                    'member_email'  => $dataMember->member_email,
                    'member_phone'  => $dataMember->member_phone,
                    'member_level'  => $dataMember->mlevel_id,
                    'member_group'  => $this->group_model->get_group_name($dataMember->group_id),
                    'group_id'      => $dataMember->group_id,
                    'member_image'  => $dataMember->member_image,
                    'member_bidang' => $dataMember->member_desc,
                    'ceo_notes_allow' => (intval($dataMember->member_ceo)==1) ? 1 : 0,
                    'bod_share_allow' => (intval($dataMember->member_ceo)==2) ? 1 : 0,
					'id_level_karyawan' => $dataMember->id_level_karyawan,
					'kategori_klien' => $kategori_klien,
                );
				
                $this->session->set_userdata($data_session);
				
				if($kategori_klien=="classroom_only") {
					redirect('learning/class_room');
				} else if($kategori_klien=="komplit") {
					redirect('home');
				} else {
					echo 'unknown kategori '.$kategori_klien;
					exit;
				}
            }
        } else {
            $this->session->set_flashdata('item', array('message' => $this->function_api->msg['no_username_pass']));
        }
		
		$kat_login = '';
		$url_back = 'login';
		if($_GET) {
			$kat_login = $_GET['k'];
		}
		
		if(!empty($kat_login)) {
			$url_back = 'login/login_'.$kat_login;
		}
		
		redirect($url_back);
	}

	function logout(){
        $token = $this->session->userdata('device_token');
        $member_id = $this->session->userdata('member_id');
        $this->member_model->update_member_device_token($member_id, $token, 'N');
        $this->session->sess_destroy();
		redirect('login');
	}

	function forgot(){
	    if ($this->session->userdata('member_id')) redirect('home');
		
		$kat_login = '';
		if($_GET) {
			$kat_login = $_GET['k'];
		}
		
		$url_back = site_url('login');
		$css_logo = 'class="centered"';
		$url_img1 = PATH_ASSETS.'icon/login_bg.png';
		$url_img2 = PATH_ASSETS.'icon/logo_white.png';
		
		if($kat_login=="agronow") {
			$url_back = site_url('login/login_agronow');
		}
		else if($kat_login=="ipfi") {
			$url_back = site_url('login/login_ipfi');
			$css_logo = 'style="position:absolute;top:2%;left:50%;transform: translateX(-50%);max-width:200px;"';
			$url_img1 = PATH_ASSETS.'icon/ipfi_login_bg.png';
			$url_img2 = PATH_ASSETS.'icon/ipfi_logo_white.png';
		}
		
		$data['css_logo'] = $css_logo;
		$data['url_img1'] = $url_img1;
		$data['url_img2'] = $url_img2;
		$data['kat_login'] = $kat_login;
		$data['url_back'] = $url_back;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        if ($this->form_validation->run() == FALSE){
            return $this->load->view('forgot',$data);
        }else{
            $email = $this->input->post('email');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('item', array('message' => 'Format alamat email tidak valid'));
                return $this->load->view('forgot',$data);
            }
            $this->member_model->recData['memberEmail'] = $email;
            $member = $this->member_model->select_member('byEmail');
            if (!$member){
                $this->session->set_flashdata('item', array('message' => 'Data tidak ditemukan'));
                return $this->load->view('forgot',$data);
            }
            if ($member['member_status']=='block'){
                $this->session->set_flashdata('item', array('message' => 'Status member tidak aktif'));
                return $this->load->view('forgot',$data);
            }

            $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charlength = strlen($chars);
            $string = '';
            for ($i = 0; $i < 8; $i++) {
                $string .= $chars[rand(0, $charlength - 1)];
            }
            $reset_token = $string;
            $reset_link = site_url('reset').'?e='.urlencode($member['member_email']).'&t='.$reset_token;
            // check latest reset request
            $reset = $this->member_model->select_member_reset($email);
            if ($reset){
                $this->member_model->update_member_reset($reset[0]['reset_id'], 1);
            }
            $insert_id = $this->member_model->insert_member_reset($member['member_id'], $member['member_email'], $reset_link, $reset_token);
            if (!$insert_id) exit('Permintaan reset gagal, silahkan ulangi.');

            $config = Array(
                    'protocol' => 'mail',
                    'smtp_host' => 'mail.creacle.co.id',
                    'smtp_port' => 465,
                    'smtp_user' => 'debug@creacle.co.id',
                    'smtp_pass' => 'd3bu991n9',
                    'mailtype' => 'html',
                    'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE,
                    'newline' => "\r\n"
                );

            $email = $member['member_email'];
            $this->load->library('email');
            $subject = 'Reset Password';
            $data = [
                'reset_link'      => $reset_link,
                'member_name'   => $member['member_name']
            ];
            $message = $this->load->view('mail_forgot_pass', $data, TRUE);
            $this->email->initialize($config);
//            $this->email->set_newline("\r\n");
            $this->email->from('debug@creacle.co.id', 'Agronow [no reply]');
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                $data = [
                    'status' => 'Berhasil',
                ];
                $this->session->set_flashdata('item', array(
                    'alert_class'   => 'alert alert-success mb-1',
                    'message'       => 'Silahkan periksa email anda'
                ));
                return $this->load->view('message_alert', $data);
            } else {
                $data = [
                    'status' => 'Gagal',
                ];
                $this->session->set_flashdata('item', array(
                    'alert_class'   => 'alert alert-danger mb-1',
                    'message'       => 'Permintaan request gagal'
                ));
                return $this->load->view('message_alert', $data);
            }
        }
    }

    function reset(){
	    if ($this->input->server('REQUEST_METHOD') === 'GET'){
            $email = $this->input->get('e');
            $token = $this->input->get('t');
            if (!$email || !$token){
                $data = [
                    'status' => 'Reset',
                ];
                $this->session->set_flashdata('item', array(
                    'alert_class'   => 'alert alert-danger mb-1',
                    'message'       => 'Format tautan salah'
                ));
                return $this->load->view('message_alert', $data);
            }
            $reset = $this->member_model->select_member_reset($email, $token);
            if (!$reset){
                $data = [
                    'status' => 'Reset',
                ];
                $this->session->set_flashdata('item', array(
                    'alert_class'   => 'alert alert-danger mb-1',
                    'message'       => 'Tautan tidak valid'
                ));
                return $this->load->view('message_alert', $data);
            }

            $now = time();
            $from = strtotime($reset[0]['reset_create_date']);

            // if timestamp more than 180 minutes
            if ($now - $from > 180 * 60){
                $data = [
                    'status' => 'Reset',
                ];
                $this->session->set_flashdata('item', array(
                    'alert_class'   => 'alert alert-danger mb-1',
                    'message'       => 'Tautan tidak berlaku'
                ));
                return $this->load->view('message_alert', $data);
            }
            $data = ['email' => $email, 'token' => $token];
            return $this->load->view('reset', $data);
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            if ($this->form_validation->run() == FALSE){
                $this->session->set_flashdata('item', array('message' => validation_errors()));
                return $this->load->view('reset');
            } else {
                $new_password = md5($this->input->post('password',TRUE));
                $email = $this->input->post('email');
                $token = $this->input->post('token');
                $reset = $this->member_model->select_member_reset($email, $token);
                if (!$reset){
                    redirect('forgot');
                }
                $this->member_model->recData['memberId'] = $reset[0]['member_id'];
                $this->member_model->update_member('byField', '', 'member_password', $new_password);
                $this->member_model->update_member_reset($reset[0]['reset_id'], 1);
                redirect('login');
            }
        }
    }
	
	function ttd_digital($id_classroom=NULL){
		$id_classroom = (int) $id_classroom;
		
		$this_page = site_url('ttd_digital/'.$id_classroom);
		
		$ttd_nama = "Mohammad Abdul Ghani";
		$ttd_jabatan = "Direktur Utama PTPN III (Persero)";
		
		$post = $this->input->post();
		if(!empty($post)) {
			$approve = $post['approve'];
			if($approve=="1") {
				$arrD = array();
				$arrD['ttd_nama'] = $ttd_nama;
				$arrD['ttd_jabatan'] = $ttd_jabatan;
				$arrD['tanggal'] = date("Y-m-d H:i:s");
				
				$sql = "update _classroom set cr_detail_ttd_digital='".json_encode($arrD)."', cr_status_ttd_digital='approved' where cr_id='".$id_classroom."' and cr_status_ttd_digital='pending' ";
				$this->db->query($sql);
				
				redirect($this_page);
				exit;
			}
		}
		
		// detail kelas
		$sql = "select cr_name, cr_status_ttd_digital, cr_detail_ttd_digital from _classroom where cr_id='".$id_classroom."' ";
		$res = $this->db->query($sql);
		$data = $res->result_array();
		$nama_kelas = $data[0]['cr_name'];
		$cr_status_ttd_digital = $data[0]['cr_status_ttd_digital'];
		$arr_ttd_digital = json_decode($data[0]['cr_detail_ttd_digital'],true);
		
		if(empty($cr_status_ttd_digital) || $cr_status_ttd_digital=="disable") {
			echo 'ttd digital dimatikan untuk classroom ini';
			exit;
		}
		
		if($cr_status_ttd_digital=="approved") {
			$img_status = PATH_ASSETS.'img/_status_approved.png';
			$ttd_nama = $arr_ttd_digital['ttd_nama'];
			$ttd_jabatan = $arr_ttd_digital['ttd_jabatan'];
		} else {
			$img_status = PATH_ASSETS.'img/_status_pending.png';
		}
		
		// daftar peserta
		$sql =
			"select m.member_name, m.member_nip, g.group_name
			from _classroom_member cm, _member m, _group g
			where cm.cr_id='".$id_classroom."' and cm.member_id=m.member_id and m.group_id=g.group_id
			order by LENGTH(g.group_name), g.group_name, m.member_name";
		$res = $this->db->query($sql);
		$data_peserta = $res->result_array();
		
		$data = [
			'ttd_nama' => $ttd_nama,
			'ttd_jabatan' => $ttd_jabatan,
			'nama_kelas' => $nama_kelas,
			'img_status' => $img_status,
			'cr_status_ttd_digital' => $cr_status_ttd_digital,
			'data_peserta' => $data_peserta
		];
		
		$data['form_action'] = $this_page;
		$this->load->view('ttd_digital', $data);
	}
}
