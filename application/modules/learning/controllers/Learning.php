<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Content_model content_model
 */
class Learning extends MX_Controller {
	public $title = 'Learning Room';
	public $menu = 'learning';

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'culture_model', 'classroom_model', 'forum_model', 'kompetensi_model', 'learning_wallet_model']);
    }

	public function index(){
		// cek dl kategori kliennya
		$kategori_klien = $this->session->userdata('kategori_klien');
		if($kategori_klien=="classroom_only") {
			redirect('learning/class_room');
			exit;
		}
		
		$memberId = $this->session->userdata('member_id');

		$this->data['title'] = $this->title;
		$this->page = 'learning';

		// get latest 
		
		// learning wallet
		$desc = "Teman Belajar Kita Semua";
		$this->data['data']['Learning Wallet'] = $desc;
		
		// Digital Library
		$this->section_model->sectionName = 'Digital Library';
		$section = $this->section_model->select_section('byName');
		$section_id = @$section['section_id'];

		$this->content_model->recData['sectionId'] = $section_id;
		$result = $this->content_model->select_content("publish","",1);
		if (isset($result[0])) {
			$desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
			$this->data['data']['Digital Library'] = $desc;
		}

		// Corporate Culture
		$result = $this->culture_model->get_lastest($memberId);
		if (isset($result[0])) {
			$desc = $result[0]['cr_name'].' <br>'.$this->function_api->date_indo($result[0]['cr_date_start'],"dd FF YYYY");
			$this->data['data']['Corporate Culture'] = $desc;
		}else{
			$this->data['data']['Corporate Culture'] = 'Tidak ada Corporate Culture yang aktif';
		}

		// Class Room
		$result = $this->classroom_model->get_lastest($memberId);
		if (isset($result[0])) {
			$desc = $result[0]['cr_name'].' <br>'.$this->function_api->date_indo($result[0]['cr_date_start'],"dd FF YYYY");
			$this->data['data']['Class Room'] = $desc;
		}else{
			$this->data['data']['Class Room'] = 'Tidak ada Class Room yang diikuti';
		}

		// Kompetensi
        $result = $this->kompetensi_model->get_lastest($memberId);
        if (isset($result[0])) {
			$desc = $result[0]['cr_name'].' <br>'.$this->function_api->date_indo($result[0]['cr_date_start'],"dd FF YYYY");
			$this->data['data']['Kompetensi'] = $desc;
		}else{
			$this->data['data']['Kompetensi'] = 'Tidak ada Kompetensi yang diikuti';
		}

		// Expert Directory
		$this->data['data']['Expert Directory'] = 'Konsultasi dengan para Expert disini'.'<br/>'.$this->function_api->date_indo(date('Y-m-d'),"dd FF YYYY");

		// Knowledge Sharing
//		$this->section_model->sectionName = 'Knowledge Management';
//		$section = $this->section_model->select_section('byName');
//		$section_id = @$section['section_id'];
        $section_id = 31;

		$this->content_model->recData['sectionId'] = $section_id;
		$result = $this->content_model->select_content("publish","",1);
		if (isset($result[0])) {
			$desc = $result[0]['content_name'].' <br>'.$this->function_api->date_indo($result[0]['content_publish_date'],"dd FF YYYY");
			$this->data['data']['Knowledge Management'] = $desc;
		}

		// forum
		$result = $this->forum_model->get_lastest();
		if (isset($result[0])) {
			$desc = $result[0]['forum_name'].' <br>'.$this->function_api->date_indo($result[0]['forum_create_date'],"dd FF YYYY");
			$this->data['data']['Forum'] = $desc;
		}

		$this->data['data']['Attendance'] = 'Scan QR Room Kehadiran';

		$this->data['data']['Individual Report'] = 'Laporan Learning Room anda';
		$this->data['data']['Glosarium'] = 'Glosarium/Kamus Istilah';

		$this->customjs = array('header_notification');
		$this->generate_layout();
	}

	function expert_directory(){
		$this->data['title'] = 'Expert Directory';
		$this->page = 'digital_library_mockup';
		$this->generate_layout();
	}

	function book(){
		$this->data['title'] = 'Book';
		$this->page = 'book_mockup';
		$this->generate_layout();
	}
}
