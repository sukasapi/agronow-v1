<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Member_model member_model
 * @property Category_model category_model
 * @property Forum_model forum_model
 */
class Forum extends MX_Controller {
	private $section_id = 19;

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'culture_model', 'classroom_model', 'forum_model', 'member_model']);
        $this->member_id = $this->session->userdata('member_id');
    }

	function index(){
        $this->category_model->catRoot = 0;
        $categories = $this->category_model->select_category('byRoot',$this->section_id);
        $data = [];
        foreach ($categories as $cat){
            $data[] = [
                'cat_id'    => $cat['cat_id'],
                'cat_name'  => $cat['cat_name'],
                'cat_image' => URL_MEDIA_IMAGE.''.$cat['cat_image']
            ];
        }
		$this->data['data'] = $data;
		$this->data['title'] = 'Forum';
		$this->page = 'forum/index';
		$this->menu = 'learning';
		$this->customcss = array('select2');
		$this->customjs = array('select2');
		$this->generate_layout();
	}

	function sub($id){
	    if ($id==166){
	        $recData =[
	            'memberId'=> $this->member_id
            ];
	        $categories = $this->member_model->select_member_category('byMemberId',$recData,4);
	        if (count($categories) != 4){
	            redirect(site_url('learning/forum/select_category'));
            }
        } else {
            $this->category_model->catParent = $id;
            $categories = $this->category_model->select_category('byParent',$this->section_id);
        }
        foreach ($categories as $k=>$v){
            if($v['cat_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$v['cat_image'])){
                $categories[$k]['cat_image'] = URL_MEDIA_IMAGE.$v['cat_image'];
            } else {
                $categories[$k]['cat_image'] = PATH_ASSETS.'icon/home_profile_bg_pp_silver.png';
            }
        }
        $this->data['data'] = $categories;
        $this->data['title'] = 'Forum';
		$this->page = 'forum/sub';
		$this->menu = 'learning';
		$this->customcss = array('select2');
		$this->customjs = array('select2','forum_suggest');
		$this->generate_layout();
	}

	function select_category(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('categories[]', 'Category', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $this->member_model->recData['memberId'] = $this->member_id;
            $member = $this->member_model->select_member('byId');
            if (in_array($member['member_desc'], ['all','0',''])){
                $this->category_model->catParent = 166; // cat_id untuk profesional //
                $cat_available = $this->category_model->select_category('allByCatParent', $this->section_id);
            } else {
                $this->category_model->catName = $member['member_desc'];
                $cat = $this->category_model->select_category('byName', $this->section_id);
                $this->category_model->catParent = $cat['cat_id'];
                $cat_available = $this->category_model->select_category('byParent', $this->section_id);
            }
            $this->data['title'] = 'Select Category';
            $this->data['options'] = $cat_available;
            $this->page = 'forum/select_category';
            $this->menu = 'learning';
            $this->customjs = array('select_categories');
            $this->generate_layout();
        }else{
            $cats = $this->input->post('categories');
            $this->db->trans_start();
            foreach ($cats as $cat){
                $this->member_model->insert_member_category($this->member_id, $cat);
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === false){
                redirect($_SERVER['REQUEST_URI'], 'refresh');
            } else {
                redirect(site_url('learning/forum/sub/166'));
            }
        }
    }

	function listing($page=1){
        $this->category_model->catParent = 165; // community based
        $community = $this->category_model->select_category('byParent',$this->section_id);
        $recData =[
            'memberId'=> $this->member_id
        ];
        $profesional = $this->member_model->select_member_category('byMemberId',$recData,4);
        $category = [
            [
                'cat_name'  => 'Community Based',
                'sub_cat'   => $community
            ],
            [
                'cat_name'  => 'Profesional',
                'sub_cat'   => $profesional
            ]

        ];
//		$cat_parent = $this->category_model->select_category('byRoot',$this->section_id);
//		foreach($cat_parent as $c){
//			$tmp['cat_id'] = $c['cat_id'];
//			$tmp['cat_name'] = $c['cat_name'];
//			$this->category_model->catParent = $c['cat_id'];
//			$tmp['sub_cat'] = $this->category_model->select_category('byParent',$this->section_id);
//			$category[] = $tmp;
//		}

		$page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->forum_model->beginRec = $limit*$page-$limit;
		$this->forum_model->endRec = $limit;

		$cat_id = $this->input->get('cat_id');
		$this->data['cat_id'] = $cat_id;
        $this->category_model->catId = $cat_id;
        $cat = $this->category_model->select_category('byId',$this->section_id);
        $this->data['cat_name'] = $cat['cat_name'];
        $dataContent = $this->forum_model->select_forum("select",$cat_id,"");

//		$this->data['cat_name'] = $this->category_model->get_cat_name($this->input->get('cat_id'));
//		if($this->category_model->get_cat_name($this->input->get('cat_id'))=='&mdash;'){
//			$this->data['cat_name'] = 'Semua Kategori';
//			$dataContent = $this->forum_model->select_forum("select");
//		}else{
//			$dataContent = $this->forum_model->select_forum("select",$this->data['cat_id'],"");
//		}

        $data = [];
        $result = [];
        for($i=0;$i<count($dataContent);$i++){
            if(isset($dataContent[$i])){
                $data['id'] 	= $dataContent[$i]['forum_id'];
                $data['title'] 	= $dataContent[$i]['forum_name'];
                $data['sticky'] = $dataContent[$i]['forum_sticky']?true:false;
                $data['group'] 	= $dataContent[$i]['group_name'];
				$data['member'] = $dataContent[$i]['member_name'];
				$data['date'] 	= $this->function_api->date_indo($dataContent[$i]['forum_create_date'],"datetime");
				$data['participan']	= count($this->forum_model->list_user_forum($dataContent[$i]['forum_id']));
				$this->forum_model->recData['forumId'] = $dataContent[$i]['forum_id'];
				$data['comment'] = $this->forum_model->select_forum_chat("count");
                if($dataContent[$i]['member_image']!=""){
                    $data['member_image'] = $dataContent[$i]['member_image'];
                } else {
                    $data['member_image'] = base_url('assets/img/avatar.png');
                }
                array_push($result,$data);
            }
		}

		$this->data['category'] = $category;
		$this->data['data'] = $result;
		$this->data['title'] = 'Forum';
		$this->data['page'] = $page;
		$this->page = 'forum/listing';
		$this->menu = 'learning';
		$this->customcss = array('select2');
		$this->customjs = array('select2');
		$this->generate_layout();
	}

	function detail($forum_id=NULL){
		$this->forum_model->recData['forumId'] = $forum_id;
		$content = $this->forum_model->select_forum("byId");
        if ($content->num_rows()!=1){
            show_404();
		}
		$content = $content->result_array()[0];

//		$data['id'] 	= $content['forum_id'];
//		$data['title'] 	= $content['forum_name'];
//		$data['cat_id'] = $content['cat_id'];
//		$data['group'] 	= $content['group_name'];
//		$data['desc'] 	= $content['forum_desc'];
//		$data['member'] = $content['member_name'];
//		$data['date'] 	= $this->function_api->date_indo($content['forum_create_date'],"datetime");
//		$data['participan']	= count($this->forum_model->list_user_forum($content['forum_id']));
//		$this->forum_model->recData['forumId'] = $content['forum_id'];
//		$data['comment'] = $this->forum_model->select_forum_chat("count");
//		$data['list_comment'] = $this->forum_model->select_forum_chat();

        if($content['member_image'] ==""){
            $content['member_image'] = base_url('assets/img/avatar.png');
        }
        $content['date']        = $this->function_api->date_indo($content['forum_create_date'],"datetime");
        $content['participant'] = count($this->forum_model->list_user_forum($content['forum_id']));
        $this->forum_model->recData['forumId'] = $content['forum_id'];
        $content['chat_count']  = $this->forum_model->select_forum_chat("count");

		$this->customjs = array('content','forum_chat');
		$this->data['forum'] = $content;
		$this->data['title'] = 'Detail Forum';
		$this->page = 'forum/detail';
		$this->menu = 'learning';
		$this->generate_layout();
	}

//	function post_comment($forum_id=NULL){
//        $member_id = $this->session->userdata('member_id');
//        $comment_text = $this->input->post("comment_text");
//        if (!$forum_id) show_404();
//        $referred_from = $this->input->post('current_url');
//        if (!$comment_text){
//            redirect($referred_from, 'refresh');
//        }
//        $recData['forumId'] = $forum_id;
//        $recData['userId'] = '0';
//        $recData['memberId'] = $member_id;
//        $recData['fcDesc'] = $comment_text;
//		$recData['fcImage'] = '';
//		$recData['fcStatus'] = 'active';
//        $this->forum_model->insert_forum_chat($recData);
//        redirect($referred_from, 'refresh');
//	}
	
	function tambah(){
//		$cat_parent = $this->category_model->select_category('byRoot',$this->section_id);
//		foreach($cat_parent as $c){
//			$tmp['cat_id'] = $c['cat_id'];
//			$tmp['cat_name'] = $c['cat_name'];
//			$this->category_model->catParent = $c['cat_id'];
//			$tmp['sub_cat'] = $this->category_model->select_category('byParent',$this->section_id);
//			$category[] = $tmp;
//		}

        $this->category_model->catParent = 165; // community based
        $community = $this->category_model->select_category('byParent',$this->section_id);
        $recData =[
            'memberId'=> $this->member_id
        ];
        $profesional = $this->member_model->select_member_category('byMemberId',$recData,4);
        $category = [
            [
                'cat_name'  => 'Community Based',
                'sub_cat'   => $community
            ],
            [
                'cat_name'  => 'Profesional',
                'sub_cat'   => $profesional
            ]

        ];
		
		$this->data['category'] = $category;
		$this->data['title'] = 'Forum';
		$this->page = 'forum/tambah';
		$this->menu = 'learning';
		$this->customcss = array('select2');
		$this->customjs = array('select2');
		$this->generate_layout();
	}

	function insert(){
		$this->load->library('form_validation');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
		$this->form_validation->set_rules('topik', 'Topik', 'required|trim');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');

		if($this->form_validation->run() == FALSE){
			$error = implode(" ",$this->form_validation->error_array());
			$this->session->set_flashdata('item', $error);
			redirect('learning/forum/tambah');
        }else{
			$recData['catId'] = $this->input->post("kategori");
			$recData['userId'] = '0';
			$recData['memberId'] = $this->session->userdata('member_id');
			$recData['forumName'] = $this->input->post("topik");
			$recData['forumAlias'] = str_replace(' ', '-', $this->input->post("deskripsi"));
			$recData['forumDesc'] = $this->input->post("deskripsi");
			$recData['forumSticky'] = '0';
			$recData['forumStatus'] = 'open';
			$recData['forumCloseDate'] = '';
			$recData['forumCloseBy'] = '';
			$recData['forumCloseReason'] = '';
			$this->forum_model->insert_forum($recData);
			redirect('learning/forum/listing?cat_id='.$recData['catId']);
		}
	}

	function ajax_get_chats(){
        $forum_id   = $this->input->get('forum_id');
        $page       = $this->input->get('page');
        $limit      = 5;
        $this->forum_model->recData['forumId'] = $forum_id;
        $this->forum_model->beginRec = $limit*$page-$limit;
        $this->forum_model->endRec = $limit+1;
        $res_comments   = $this->forum_model->select_forum_chat();
        $chats       = [];
        $c_count        = count($res_comments);
        $n = 0;
        foreach ($res_comments as $c){
            $n++;
            $dc = [
                'fc_id'         => $c['fc_id'],
                'fc_desc'       => $c['fc_desc'],
                'fc_create_date'=> $c['fc_create_date'],
                'member_id'     => $c['user_id'],
                'member_name'   => $c['member_name'],
            ];
            if($c['member_image']!=""){
                $dc['member_image'] = $c['member_image'];
            } else {
                $dc['member_image'] = base_url('assets/img/avatar.png');
            }
            $dc['comment_time']  = $this->function_api->waktu_lalu($c['fc_create_date']);
            array_push($chats, $dc);
            if ($n == $limit) break;
        }
        $data   = [
            'chat'  => $chats,
            'next_page' => $c_count > $limit?$page+1:0,
            'prev_page' => $page>1?$page-1:0
        ];
        echo json_encode($data);
    }

    function ajax_post_chat(){
        $forum_id = $this->input->post('forum_id');
        $fc_desc = $this->input->post("chat_text");
        $recData['forumId'] = $forum_id;
        $recData['userId'] = '0';
        $recData['memberId'] = $this->member_id;
        $recData['fcDesc'] = $fc_desc;
        $recData['fcImage'] = '';
        $recData['fcStatus'] = 'active';
        $res = $this->forum_model->insert_forum_chat($recData);
        echo json_encode($res);
    }

    function ajax_category_suggest(){
        $fs_name = $this->input->post('category');
        $recData = ['memberId'=>$this->member_id, 'fsName'=>$fs_name];
        $res = $this->forum_model->insert_forum_suggest($recData);
        echo json_encode($res);
    }
}
