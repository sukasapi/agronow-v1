<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Member_model member_model
 * @property Content_model content_model
 * @property Inbox_model inbox_model
 */
class Account extends MX_Controller {
	function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['member_model', 'auth_model', 'group_model', 'media_model', 'forum_model', 'content_model', 'inbox_model']);
        $this->member_id = $this->session->userdata('member_id');
    }

	function index(){
		// cek dl kategori kliennya
		$kategori_klien = $this->session->userdata('kategori_klien');
		if($kategori_klien=="classroom_only") {
			redirect('learning/class_room');
			exit;
		}
		
        $this->member_model->recData['memberId'] = $this->member_id;
        $dataMember = $this->member_model->select_member("byId");
        $recData['mPoin']       = $dataMember['member_poin'];

        $recData['memberId'] = $this->member_id;
        $recData['interval']    = 'yearly';
        $recData['mpSection']   = 'CR';
        $cr_poin = $this->member_model->select_member_poin('sumBySection',$recData);
        $recData['mpSection']   = 'CC';
        $cc_poin = $this->member_model->select_member_poin('sumBySection',$recData);
        $recData['mpSection']   = 'KS';
        $ks_poin = $this->member_model->select_member_poin('sumBySection',$recData);
        $recData['mPoin']       = $dataMember['member_poin'];
        $level_poin = $this->member_model->select_member_poin_level('byPoin', $recData);

        $this->group_model->recData['groupId'] = $dataMember['group_id'];
        $group = $this->group_model->select_group('byId');

        $recData['userId'] = $this->member_id;
        $rank_global  = $this->member_model->select_rank("byUserId", $recData, 1);
        $recData['groupId'] = $dataMember['group_id'];

        // single page
        $this->content_model->recData['contentAlias'] = 'hubungi-kami';
        $contact_us = $this->content_model->select_content('byAlias');
        $this->content_model->recData['contentAlias'] = 'frequently-asked-questions';
        $faq = $this->content_model->select_content('byAlias');
        $this->content_model->recData['contentAlias'] = 'privacy-policy';
        $privacy_policy = $this->content_model->select_content('byAlias');

        $result = [
            'name'                      => $dataMember['member_name'],
            'nik'                       => $dataMember['member_nip'],
            'bidang'                    => $dataMember['member_desc'],
            'group'                     => $group['group_name'],
            'rank_global'               => $rank_global,
            'total_point'               => $dataMember['member_poin'],
            'total_saldo'               => $dataMember['member_saldo'],
            'classroom_point'           => $cr_poin,
            'knowledge_point'           => $ks_poin,
            'corporate_culture_point'   => $cc_poin,
            'badge_level'               => $level_poin,
            'contact_us'                => $contact_us,
            'faq'                       => $faq,
            'privacy_policy'            => $privacy_policy
        ];

		$result['member_image'] = validate_member_image($dataMember['member_image']);

        $bookmark_count = $this->member_model->select_bookmark("count");
        $inbox_count = $this->inbox_model->select_inbox("countUnread",$recData);
        $result['bookmark_count'] = $bookmark_count;
        $result['inbox_count']    = $inbox_count;

		$this->data['title'] = 'Account';
		$this->data['data'] = $result;
		$this->customjs = array('header_notification');
		$this->page = 'account';
		$this->menu = 'account';
		$this->generate_layout();
	}

	function my_bookmark($page=1){
        $this->member_model->recData['memberId'] = $this->member_id;
        $this->member_model->select_bookmark();

        $page = $page>=1?$page:1;
        $limit = 10;
        $this->member_model->beginRec = ($page - 1) * $limit;
        $this->member_model->endRec   = $limit;

        $dataContent = $this->member_model->select_bookmark("");
        $countContent = $this->member_model->select_bookmark("count");

        $result = [];
        if ($countContent>0){
            foreach ($dataContent as $dc){
                $data['id'] 	= $dc['content_id'];
                $data['title'] 	= $dc['content_name'];
                $data['date'] 	= $this->function_api->date_indo($dc['content_publish_date'],"dd FF YYYY");
                $data['viewed'] = $this->function_api->number($dc['content_hits']);
                $data['detail_url'] = base_url(getSectionPage($dc['section_id']).'/detail/'.$dc['content_id']);
                $data['section_id']     = $dc['section_id'];
                $data['section_name']   = strtoupper($dc['section_name']);
                array_push($result,$data);
            }
        }
		$this->data['title']    = 'My Bookmark';
        $this->data['page']     = $page;
        $this->data['data']     = $result;
		$this->page             = 'my_bookmark';
		$this->menu             = 'account';
		$this->generate_layout();
	}

	function setting(){
		$this->data['title'] = 'Setting';
		$this->page = 'setting';
		$this->menu = 'account';
		$this->generate_layout();
	}

    function search_bookmark($keyword='',$page=1){
        $this->content_model->recData['memberId'] = $this->member_id;
        $keyword = urldecode($keyword);
        if (!$keyword){
            $keyword = $this->input->post('keyword');
        }
        $page = $page>=1?$page:1;
        $limit = 10;
        $this->content_model->beginRec = ($page - 1) * $limit;
        $this->content_model->endRec = $limit;

        $dataContent = $this->content_model->search_content_bookmark($keyword,"");

        $result = [];
        foreach ($dataContent as $dc){
            $data['id'] 	= $dc['content_id'];
            $data['title'] 	= $dc['content_name'];
            $data['date'] 	= $this->function_api->date_indo($dc['content_publish_date'],"dd FF YYYY");
            $data['viewed'] = $this->function_api->number($dc['content_hits']);
            $data['detail_url'] = base_url(getSectionPage($dc['section_id']).'/detail/'.$dc['content_id']);
            $data['section_id']     = $dc['section_id'];
            $data['section_name']   = strtoupper($dc['section_name']);
            array_push($result,$data);
        }

        $this->data['title']    = 'My Bookmark';
        $this->data['page']     = $page;
        $this->data['data']     = $result;
        $this->data['keyword'] = $keyword;
        $this->page             = 'my_bookmark';
        $this->menu             = 'account';
        $this->generate_layout();
    }

    function contact_us(){
        $alias = 'hubungi-kami';
        $this->content_model->recData['contentAlias'] = $alias;
        $content = $this->content_model->select_content("byAlias");

        $this->data['title'] = 'Contact Us';
        $this->data['content'] = [
            'content_name'  => $content?$content['content_name']:$alias,
            'content_desc'  => $content?$content['content_desc']:'Content not found.<br>Content alias: <i>'.$alias.'</i>'
        ];
        $this->page = 'single_page';
        $this->menu = 'account';
        $this->generate_layout();
    }

    function faq(){
        $alias = 'frequently-asked-questions';
        $this->content_model->recData['contentAlias'] = $alias;
        $content = $this->content_model->select_content("byAlias");

        $this->data['title'] = 'FAQ';
        $this->data['content'] = [
            'content_name'  => $content?$content['content_name']:$alias,
            'content_desc'  => $content?$content['content_desc']:'Content not found.<br>Content alias: <i>'.$alias.'</i>'
        ];
        $this->page = 'single_page';
        $this->menu = 'account';
        $this->generate_layout();
    }

    function privacy_policy(){
        $alias = 'privacy-policy';
        $this->content_model->recData['contentAlias'] = $alias;
        $content = $this->content_model->select_content("byAlias");

        $this->data['title'] = 'Privacy Policy';
        $this->data['content'] = [
            'content_name'  => $content?$content['content_name']:$alias,
            'content_desc'  => $content?$content['content_desc']:'Content not found.<br>Content alias: <i>'.$alias.'</i>'
        ];
        $this->page = 'single_page';
        $this->menu = 'account';
        $this->generate_layout();
    }
}
