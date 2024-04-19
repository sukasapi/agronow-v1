<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Inbox_model inbox_model
 * @property Function_api function_api
 * @property Member_model member_model
 */
class Inbox extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['inbox_model', 'member_model']);
        $this->member_id = $this->session->userdata('member_id');
    }

	function index($page=1){
        $page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->inbox_model->beginRec = $limit*$page-$limit;
        $this->inbox_model->endRec = $limit;

        $recData['inboxFromId'] = $this->member_id;
        $dataParent = $this->inbox_model->select_inbox("getParentIdByMemberId",$recData);
        $countInbox = $this->inbox_model->select_inbox("countParentIdByMemberId",$recData);

        $countPage = ceil($countInbox/$limit);
        $result['current_page'] = $page;
        $result['count_page']   = $countPage;
        $result['inbox']        = [];
        if ($dataParent) {
            foreach ($dataParent as $dp) {
                $recData['parentId'] = $dp['parent_id'];
                $this->inbox_model->sort = 'DESC';
                $dataInbox = $this->inbox_model->select_inbox("byParentId", $recData, 1);
                $data = [
                    'id' => $dataInbox[0]['inbox_id'],
                    'parent_id' => $dataInbox[0]['parent_id'],
                    'title' => $dataInbox[0]['inbox_title'],
                    'desc' => $dataInbox[0]['inbox_desc'],
                    'detail_url' => site_url('account/inbox/detail/' . $dataInbox[0]['parent_id']),
                    'date' => $this->function_api->date_indo($dataInbox[0]['inbox_create_date'], 'datetime'),
                    'sender' => $dataInbox[0]['inbox_from'],
                    'member_read' => $dataInbox[0]['inbox_read_member'],
                    'member_read_date' => $this->function_api->date_indo($dataInbox[0]['inbox_read_member_date']),
                    'member_read_time' => substr($dataInbox[0]['inbox_read_member_date'], 11, 5),
                    'admin_read' => $dataInbox[0]['inbox_read_admin'],
                    'admin_read_date' => $this->function_api->date_indo($dataInbox[0]['inbox_read_admin_date']),
                    'admin_read_time' => substr($dataInbox[0]['inbox_read_admin_date'], 11, 5)
                ];
                array_push($result['inbox'], $data);
            }
        }
        $this->data['title']    = 'Inbox';
        $this->data['data']     = $result;
		$this->page             = 'inbox';
		$this->menu             = 'account';
		$this->generate_layout();
	}

	function add(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('account/inbox');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('inbox_title', 'Judul', 'required|trim');
        $this->form_validation->set_rules('inbox_desc', 'Desc', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $this->data['url_return']   = $url_return;
            $this->data['form_action']  = site_url('account/inbox/add');
            $this->data['title']        = 'Ask Something';
            $this->page                 = 'inbox_add';
            $this->menu                 = 'account';
            $this->generate_layout();
        }else {
            $recData['inboxTitle'] = $this->input->post('inbox_title');
            $recData['inboxDesc'] = $this->input->post('inbox_desc');
            $recData['inboxFrom'] = "member";
            $recData['inboxFromId'] = $this->member_id;
            $recData['inboxFile'] = "";
            $recData['parentId'] = $this->input->post('parent_id');
            $recData['inboxReadMember'] = "unread";
            $recData['inboxReadMemberDate'] = "";
            $recData['inboxReadAdmin'] = "unread";
            $recData['inboxReadAdminDate'] = "";
            $insert_id = $this->inbox_model->insert_inbox($recData);
            if ($this->input->post('parent_id')){
                $recData['inboxId'] = $insert_id;
            } else {
                $recData['inboxId'] = $recData['parentId'] = $insert_id;
            }
            $this->inbox_model->update_inbox('parentId', $recData);
            if ($insert_id){
                redirect(site_url('account/inbox/detail/').$recData['parentId']);
            } else {
                redirect($url_return);
            }
        }
	}

	function detail($parent_id){
        $recData['parentId'] = $parent_id;
        $dataInbox = $this->inbox_model->select_inbox("byParentId",$recData);
        $recData['inboxId'] = $parent_id;
        $this->inbox_model->update_inbox("memberRead",$recData);
        $result['inbox']        = [];
        $prev = '';
        $group_by_tgl = [];
        foreach ($dataInbox as $di){
            $this->member_model->recData['memberId'];
            $sender = $this->member_model->select_member('byId');
            if($sender && $sender['member_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$sender['member_image'])){
                $image = URL_MEDIA_IMAGE."/".$sender['member_image'];
            } else {
                $image = PATH_ASSETS.'img/avatar.png';
            }
            $curr = $this->function_api->convert_datetime($di['inbox_create_date']);
            $data   = [
                'id'                => $di['inbox_id'],
                'parent_id'         => $di['parent_id'],
                'title'             => $di['inbox_title'],
                'desc'              => $di['inbox_desc'],
                'day'               => $this->function_api->convert_datetime($di['inbox_create_date'], 'l, M d'),
                'date'              => $this->function_api->date_indo($di['inbox_create_date'], 'datetime'),
                'time'              => $this->function_api->convert_datetime($di['inbox_create_date'], 'H:i'),
                'sender'            => $di['inbox_from'],
                'sender_id'         => $di['inbox_from_id'],
                'sender_name'       => $sender?$sender['member_name']:'',
                'image'             => $image,
                'member_read'       => $di['inbox_read_member'],
                'member_read_date'  => $this->function_api->date_indo($di['inbox_read_member_date']),
                'member_read_time'  => substr($di['inbox_read_member_date'], 11, 5),
                'admin_read'        => $di['inbox_read_admin'],
                'admin_read_date'   => $this->function_api->date_indo($di['inbox_read_admin_date']),
                'admin_read_time'   => substr($di['inbox_read_admin_date'], 11,5)
            ];
            if ($prev != $curr){
                $prev = $curr;
                $group_by_tgl[$curr] = [];
            }
            array_push($group_by_tgl[$curr], $data);
        }
        $this->data['data']['inbox_title'] = $dataInbox[0]['inbox_title'];
        $this->data['title']= 'Inbox';
        $this->data['data']['inbox'] = $group_by_tgl;
        $this->data['data']['parent_id'] = $parent_id;
        $this->page = 'inbox_detail';
		$this->menu = 'account';
		$this->generate_layout_chat();
    }

    function search(){
        //TODO: inbox search
    }
}
