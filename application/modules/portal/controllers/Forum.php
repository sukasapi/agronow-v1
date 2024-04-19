<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @property ForumGroup_model ForumGroup_model
 * @property Category_model category_model
 * @property Function_api function_api
 */

class Forum extends MX_Controller {
	private $section_id = 25;
	protected $member_id, $group_id;

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'culture_model', 'classroom_model', 'ForumGroup_model']);
        $this->member_id = $this->session->userdata('member_id');
        $this->group_id = $this->session->userdata('group_id');
    }

	function index(){
        if (!$this->group_id){
            $this->load->model('member_model');
            $groupId = $this->member_model->get_group_byid($this->member_id);
            if ($groupId){
                $this->session->set_userdata('group_id', $groupId);
                $this->group_id = $groupId;
            } else {
                redirect(site_url('login'));
            }
        };
        $this->data['data'] = $this->category_model->select_category('parent0',$this->section_id, $this->group_id);
		$this->data['title'] = 'Forum';
		$this->page = 'forum/index';
		$this->menu = 'portal';
		$this->customcss = array('select2');
		$this->customjs = array('select2');
		$this->generate_layout();
	}

	private function _build_broadcrumbs($cat_id){
        $this->category_model->catId = $cat_id;
        $category = $this->category_model->select_category('byId');
        $parent_id = ($category['cat_parent'] && $category['cat_parent'] != '0')?$category['cat_parent']:false;
        $breadcrumbs = [];
        if ($category) $breadcrumbs[] = $category;
        while ($parent_id){
            $this->category_model->catId = $parent_id;
            $d_parent = $this->category_model->select_category('byId');
            if ($d_parent) {
                array_unshift($breadcrumbs,$d_parent);
                $parent_id = ($d_parent['cat_parent'] && $d_parent['cat_parent'] != '0')?$d_parent['cat_parent']:false;
            } else {
                $parent_id = false;
            }
        }
        return $breadcrumbs;
    }

	function sub(){
        $cat_id = $this->input->get('cat_id');
        $page = $this->input->get('page');
		$this->category_model->catParent = $cat_id;
        $categories = $this->category_model->select_category('byParent',$this->section_id, $this->group_id);
        $breadcrumbs = $this->_build_broadcrumbs($cat_id);

        $this->data['categories'] = $categories;

        $page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->ForumGroup_model->beginRec = $limit*$page-$limit;
        $this->ForumGroup_model->endRec = $limit;
        $this->ForumGroup_model->recData['groupId'] = $this->group_id;

        $dataContent = $this->ForumGroup_model->select_forum("",$cat_id,"");
        $contents = [];
        foreach ($dataContent as $dc){
            $tmp['id'] 	= $dc['forum_id'];
            $tmp['title'] 	= $dc['forum_name'];
            $tmp['group'] 	= $dc['group_name'];
            $tmp['member'] = $dc['member_name'];
            $tmp['member_image'] = $dc['member_image']?:site_url('assets/img/avatar.png');
            $tmp['sticky'] = $dc['forum_sticky']=="1"?true:false;
            $tmp['date'] 	= $this->function_api->date_indo($dc['forum_create_date'],"datetime");
            $tmp['participant']	= count($this->ForumGroup_model->list_user_forum($dc['forum_id'],$dc['group_id']));
            $this->ForumGroup_model->recData['forumId'] = $dc['forum_id'];
            $tmp['comment'] = $this->ForumGroup_model->select_forum_chat("count");
            array_push($contents,$tmp);
        }


        $this->data['contents'] = $contents;
        $this->data['breadcrumbs'] = $breadcrumbs;
        $this->data['cat_id'] = $cat_id;
		$this->data['title'] = 'Forum';
        $this->data['page'] = $page;
		$this->page = 'forum/sub';
		$this->menu = 'portal';
		$this->customcss = array('select2');
		$this->customjs = array('select2');
		$this->generate_layout();
	}

	function search($page=1){
        $cat_id = $this->input->post('cat_id');
        $keyword = $this->input->post('keyword');
        if (!$keyword){
            redirect(site_url('portal/forum/sub?cat_id='.$cat_id));
        }
        $page = $page>=1?$page:1;
        $limit = 10; // content per page

        $this->ForumGroup_model->beginRec = $limit*$page-$limit;
        $this->ForumGroup_model->endRec = $limit;
        $this->ForumGroup_model->recData['groupId'] = $this->group_id;
        $dataContent = $this->ForumGroup_model->search_forum_group($keyword, '', $this->group_id);
        $contents = [];
        foreach ($dataContent as $dc){
            $tmp['id'] 	= $dc['forum_id'];
            $tmp['title'] 	= $dc['forum_name'];
            $tmp['member'] = $dc['member_name'];
            $tmp['member_image'] = $dc['member_image']?:site_url('assets/img/avatar.png');
            $tmp['sticky'] = $dc['forum_sticky']=="1"?true:false;
            $tmp['date'] 	= $this->function_api->date_indo($dc['forum_create_date'],"datetime");
            $tmp['participant']	= count($this->ForumGroup_model->list_user_forum($dc['forum_id'],$dc['group_id']));
            $this->ForumGroup_model->recData['forumId'] = $dc['forum_id'];
            $tmp['comment'] = $this->ForumGroup_model->select_forum_chat("count");
            array_push($contents,$tmp);
        }
        $this->data['contents'] = $contents;
        $this->data['breadcrumbs'] = [];
        $this->data['categories'] = [];
        $this->data['keyword'] = $keyword;
        $this->data['cat_id'] = $cat_id;
        $this->data['title'] = 'Forum';
        $this->data['page'] = $page;
        $this->page = 'forum/sub';
        $this->menu = 'portal';
        $this->customcss = array('select2');
        $this->customjs = array('select2');
        $this->generate_layout();
    }


//	function listing($page=1){
//        $page = $page>=1?$page:1;
//        $limit = 10; // content per page
//        $this->ForumGroup_model->beginRec = $limit*$page-$limit;
//        $this->ForumGroup_model->endRec = $limit;
//
//        $cat_id = $this->input->get('cat_id');
//        $this->category_model->catParent = $cat_id;
//        $cat_parent = $this->category_model->select_category('parent0',$this->section_id, $this->group_id);
//        $category = [];
//        $dataContent = [];
//        $this->ForumGroup_model->recData['groupId'] = $this->group_id;
//        foreach($cat_parent as $c){
//            $tmp['cat_id'] = $c['cat_id'];
//            $tmp['cat_name'] = $c['cat_name'];
//            $this->category_model->catParent = $c['cat_id'];
//            $tmp['sub_cat'] = $this->category_model->select_category('byParent',$this->section_id);
//            $category[] = $tmp;
//            $content = $this->ForumGroup_model->select_forum("",$c['cat_id'],"");
//            if ($content) $dataContent[] = $content[0];
//        }
//
//		$this->data['cat_id'] = $cat_id;
//
//        $this->category_model->catId = $cat_id;
//		$cat = $this->category_model->select_category('byId', $this->section_id);
//        $this->data['cat_name'] = $cat?$cat['cat_name']:'Semua Kategori';
//
//
//        $data = [];
//        $result = [];
//        for($i=0;$i<count($dataContent);$i++){
//            if(isset($dataContent[$i])){
//                $data['id'] 	= $dataContent[$i]['forum_id'];
//				$data['title'] 	= $dataContent[$i]['forum_name'];
//				$data['group'] 	= $dataContent[$i]['group_name'];
//				$data['member'] = $dataContent[$i]['member_name'];
//				$data['sticky'] = $dataContent[$i]['forum_sticky']=="1"?true:false;
//				$data['date'] 	= $this->function_api->date_indo($dataContent[$i]['forum_create_date'],"datetime");
//				$data['participan']	= count($this->ForumGroup_model->list_user_forum($dataContent[$i]['forum_id'],$dataContent[$i]['group_id']));
//				$this->ForumGroup_model->recData['forumId'] = $dataContent[$i]['forum_id'];
//				$data['comment'] = $this->ForumGroup_model->select_forum_chat("count");
//                array_push($result,$data);
//            }
//		}
//
//        $this->data['selected_cat_id'] = $cat_id?$cat_id:0;
//		$this->data['category'] = $category;
//		$this->data['data'] = $result;
//		$this->data['title'] = 'Forum';
//		$this->data['page'] = $page;
//		$this->page = 'forum/listing';
//		$this->menu = 'portal';
//		$this->customcss = array('select2');
//		$this->customjs = array('select2');
//		$this->generate_layout();
//	}

	function detail($forum_id=NULL){
		$this->ForumGroup_model->recData['forumId'] = $forum_id;
		$content = $this->ForumGroup_model->select_forum("byId");
        if (!$content){
            show_404();
		}

        if ($content['group_id']!=$this->group_id){
            show_404();
        }

		$data['id'] 	= $content['forum_id'];
		$data['title'] 	= $content['forum_name'];
		$data['cat_id'] = $content['cat_id'];
		$data['group'] 	= $content['group_name'];
		$data['desc'] 	= $content['forum_desc'];
		$data['member'] = $content['member_name'];
		$data['date'] 	= $this->function_api->date_indo($content['forum_create_date'],"datetime");
		$data['participant']	= count($this->ForumGroup_model->list_user_forum($content['forum_id'],$content['group_id']));
		$this->ForumGroup_model->recData['forumId'] = $content['forum_id'];
		$this->ForumGroup_model->recData['groupId'] = $this->session->userdata('group_id');
		$page = $this->input->get('page');
		$page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->ForumGroup_model->beginRec = $limit*$page-$limit;
		$this->ForumGroup_model->endRec = $limit;
		$data['comment'] = $this->ForumGroup_model->select_forum_chat("count");
		$data['list_comment'] = $this->ForumGroup_model->select_forum_chat("all");

        $this->customjs = array('content','forum_comments');
		$this->data['content'] = $data;
		$this->data['title'] = 'Detail Forum';
		$this->page = 'forum/detail';
		$this->menu = 'portal';
		$this->generate_layout();
	}

	function post_comment($forum_id=NULL){
        $member_id = $this->session->userdata('member_id');
        $comment_text = $this->input->post("comment_text", TRUE);
        if (!$forum_id) show_404();
        $referred_from = $this->input->post('current_url');
        if (!$comment_text){
            redirect($referred_from, 'refresh');
        }
        $recData['forumId'] = $forum_id;
        $recData['userId'] = '0';
		$recData['memberId'] = $member_id;
		$recData['groupId'] = $this->session->userdata('group_id');
        $recData['fcDesc'] = $comment_text;
		$recData['fcImage'] = '';
		$recData['fcStatus'] = 'active';
        $this->ForumGroup_model->insert_forum_chat($recData);
        redirect($referred_from, 'refresh');
	}
	
	function tambah(){
        $cat_id = $this->input->get('cat_id');
        $category = $this->category_model->select_category('',$this->section_id, $this->group_id);
        $hierarchy = $this->function_api->convertToHierarchy($category,'cat_id', 'cat_parent', 'child');
        $this->data['selected_cat_id'] = $cat_id?$cat_id:0;
		$this->data['category'] = $hierarchy;
		$this->data['title'] = 'Forum';
		$this->page = 'forum/tambah';
		$this->menu = 'portal';
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
			redirect('portal/forum/tambah');
        }else{
			$recData['catId'] = $this->input->post("kategori");
			$recData['userId'] = '0';
			$recData['memberId'] = $this->session->userdata('member_id');
			$recData['groupId'] = $this->session->userdata('group_id');
			$recData['forumName'] = $this->input->post("topik");
			$recData['forumAlias'] = str_replace(' ', '-', $this->input->post("deskripsi"));
			$recData['forumDesc'] = $this->input->post("deskripsi");
			$recData['forumSticky'] = '0';
			$recData['forumStatus'] = 'open';
			$recData['forumCloseDate'] = '';
			$recData['forumCloseBy'] = '';
			$recData['forumCloseReason'] = '';
			$this->ForumGroup_model->insert_forum($recData);
			redirect('portal/forum/sub?cat_id='.$this->input->post("kategori"));
		}
	}
}
