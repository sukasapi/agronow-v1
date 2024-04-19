<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Classroom_model classroom_model
 * @property Content_model content_model
 */
class Individual_report extends MX_Controller {
    public $title = 'Individual Report';
    public $menu = 'learning';

    public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }

        $this->data['title'] = $this->title;
        $this->load->library('function_api');

        $this->load->model(['member_model', 'classroom_model', 'culture_model', 'content_model']);
    }

    public function index(){
        $memberId = $this->session->userdata('member_id');

        $this->member_model->recData['memberId'] = $memberId;
        $this->data['memberName'] = $this->member_model->select_member("nameById");

        $this->classroom_model->recData['memberId'] = $memberId;
        $classrooms = $this->classroom_model->select_classroom("byMemberId");
        $this->data['dataClassroom'] = $this->classroom_model->new_individual_report($memberId); // hanya menampilkan yg sudah mengisi feedback penyelenggaraan

        $this->culture_model->recData['memberId'] = $memberId;
        $this->data['dataCulture'] = $this->culture_model->select_culture("byMemberId");

        $this->content_model->recData['sectionId'] = 31;
        $this->content_model->recData['memberId'] = $memberId;
        $data_ks = [];
        foreach ($classrooms as $cr){
            if ($cr['content_id']){
                $this->content_model->recData['contentId'] = $cr['content_id'];
                $content = $this->content_model->select_content('byId');
                if ($content){
                    $content['classroom_name'] = $cr['cr_name'];
                    $data_ks[] = $content;
                }
            }
        }
//        $this->data['dataKS'] = $this->content_model->select_content("sharing");
        $this->data['dataKS'] = $data_ks;
		
		// data evaluasi level 3
		$sql =
			"select c.cr_id, c.cr_name, c.cr_date_start, c.cr_date_end, r.member_id, r.nilai_pre_test, r.nilai_post_test
			 from _classroom c, _classroom_evaluasi_lv3_rekap r, _classroom_evaluasi_lv3_header h
			 where c.cr_id=r.cr_id and c.cr_id=h.cr_id and h.status='1' and r.member_id='".$memberId."'
			 order by c.cr_name";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$this->data['dataEvaluasiLv3'] = $row;
		
		// css untuk penyesuaian menu yg tampil sesuai jenis klien
		$dcssT1 = 'lined';
		$dcssT2 = '';
		$kategori_klien = $this->session->userdata('kategori_klien');
		if($kategori_klien=="classroom_only") {
			$dcssT1 = '';
			$dcssT2 = 'd-none';
		}
		$this->data['dcssT1'] = $dcssT1;
		$this->data['dcssT2'] = $dcssT2;

        $this->page = 'individual_report/index';
        
        $this->generate_layout();
    }

    public function show_certificate(){
        if($this->input->get('doc') === NULL) redirect(base_url('learning/individual_report'));

        $doc = base64_decode($this->input->get('doc'));
		$dfile = base_url().SERTIFIKAT_PATH.basename($doc).'?v='.uniqid('');
		
		$menu_kanan_atas =
			'<div class="right">
				<a href="'.$dfile.'" class="headerButton" download>
					unduh&nbsp;<ion-icon name="download-outline"></ion-icon>
				</a>
			</div>';
        
        $this->data['dfile'] = $dfile;
		$this->data['menu_kanan_atas'] = $menu_kanan_atas;
		
		$this->page = 'individual_report/doc';

        $this->generate_layout();
    }
}