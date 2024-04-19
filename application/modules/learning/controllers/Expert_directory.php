<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Category_model category_model
 * @property Expert_model expert_model
 * @property Member_model member_model
 * @property Function_api function_api
 * @property CI_Input input
 * @property CI_Form_validation form_validation
 */
class Expert_directory extends MX_Controller {

	public function __construct(){
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
		}
		$this->load->library('function_api');
        $this->load->model(['category_model', 'section_model', 'content_model', 'media_model', 'expert_model', 'member_model']);

        $this->member_id = $this->session->userdata('member_id');
	}

	function index(){
//	    $category = $this->category_model->select_category('',37);
        $category = $this->expert_model->select_category_with_expert_count();
	    $this->data['data'] = $category;
		$this->page = 'expert_directory/index';
		$this->data['title'] = 'Expert Directory';
		$this->menu = 'learning';

		$this->generate_layout();
	}

	function list_expert($cat_id, $page=1){
        $page = $page>=1?$page:1;
        $limit = 10; // content per page
        $this->expert_model->beginRec = $limit*$page-$limit;
        $this->expert_model->endRec = $limit;
        $data = [];
        $this->category_model->catId = $cat_id;
        $category = $this->category_model->select_category('byId', 37);
		$this->data['data'] = $data;
		$this->data['category'] = [
		    'cat_name'  => $category?$category['cat_name']:'',
            'cat_id'    => $cat_id
        ];
		$this->page = 'expert_directory/list_expert';
		$this->data['title'] = 'List Expert';
		$this->menu = 'learning';
        $this->customjs = array('list_of_expert_search');
		$this->generate_layout();
	}

	function detail_expert($em_id){
	    $this->expert_model->recData['emId'] = $em_id;
        $expert = $this->expert_model->select_expert_member('byId');
        if (!$expert) show_404();
        $this->member_model->recData['memberId'] = $expert['member_id'];
        $member = $this->member_model->select_member('byId');
        $data_exp = json_decode($expert['em_experience'], true);
        $expert_data = [
            'em_id'         => $expert['em_id'],
            'cat_id'        => $expert['cat_id'],
            'em_name'       => $expert['em_name'],
            'profile'       => $expert['em_profil'],
            'institution'   => '',
            'title'         => '',
            'experience'    => json_decode($expert['em_experience'], true),
            'education'     => json_decode($expert['em_education'], true),
            'qualification' => json_decode($expert['em_qualification'], true),
            'member_image'  => '',
            'is_current'    => $expert['member_id']==$this->member_id?true:false
        ];
        if($member['member_image']!=""){
            $expert_data['member_image'] = $member['member_image'];
        } else {
            $expert_data['member_image'] = base_url('assets/img/avatar.png');
        }
        foreach ($data_exp as $dx){
            if ($dx['isDefault'] == 1){
                $expert_data['institution'] = $dx['institution'];
                $expert_data['title']       = $dx['title'];
            }
        }
        $this->data['data']     = $expert_data;
		$this->page             = 'expert_directory/detail_expert';
		$this->data['title']    = 'Detail Expert';
		$this->menu             = 'learning';
		$this->generate_layout();
	}

    function list_chat($em_id, $page=1){
	    $this->expert_model->recData['emId'] = $em_id;
        $page = $page>=1?$page:1;
        $limit = 10; // content per page
        $expert_member = $this->expert_model->select_expert_member('byId');
        $this->expert_model->beginRec = $limit*$page-$limit;
        $this->expert_model->endRec = $limit;
        $chats = $this->expert_model->select_expert();
        $chat_list = [];
        foreach ($chats as $chat){
            $this->member_model->recData['memberId'] = $chat['member_id'];
            $member = $this->member_model->select_member('byId');

            $this->expert_model->recData['expertId'] = $chat['expert_id'];
            $this->expert_model->recData['memberId'] = $this->member_id;
            $latest_read_id = $this->expert_model->get_latest_read_chat_id();
            $unread = $this->expert_model->get_count_unread_chat($latest_read_id);
            $data_chat = [
                'expert_id'             => $chat['expert_id'],
                'cat_id'                => $chat['cat_id'],
                'em_id'                 => $chat['em_id'],
                'expert_name'           => $chat['expert_name'],
                'member_id'             => $chat['member_id'],
                'member_name'           => $member['member_name'],
                'member_image'          => '',
                'unread'                => $unread,
//                'update_date'           => $chat['expert_update_date']?$this->function_api->date_indo($chat['expert_update_date']):'-',
                'detail_url'            => site_url('learning/expert_directory/chat/'.$chat['expert_id'])
            ];
            if($member['member_image']!=""){
                $data_chat['member_image'] = $member['member_image'];
            } else {
                $data_chat['member_image'] = base_url('assets/img/avatar.png');
            }
            array_push($chat_list, $data_chat);
        }

        $data = [
            'expert_member' => [
                'em_id'         => $em_id,
                'member_id'     => $expert_member['member_id'],
                'is_current'    => $this->member_id==$expert_member['member_id']?true:false
            ],
            'chats' => $chat_list
        ];
        $this->page         = 'expert_directory/list_chat';
        $this->data['title']= 'Chat Expert';
        $this->data['data'] = $data;
        $this->menu         = 'learning';
        $this->generate_layout();
    }

	function chat($expert_id){
	    $this->expert_model->recData['expertId'] = $expert_id;
	    $expert = $this->expert_model->select_expert('byId');

	    // expert member detail
        $this->expert_model->recData['emId'] = $expert['em_id'];
	    $expert_member = $this->expert_model->select_expert_member('byId');
        if($expert_member['em_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$expert_member['em_image'])){
            $em_image = URL_MEDIA_IMAGE."/".$expert_member['em_image'];
        } else {
            $em_image = base_url('assets/img/avatar.png');
        }

        // member (sender) detail
        $this->member_model->recData['memberId'] = $expert['member_id'];
        $sender = $this->member_model->select_member('byId');
        if($sender['member_image']!=""){
            $member_image = $sender['member_image'];
        } else {
            $member_image = base_url('assets/img/avatar.png');
        }

        $data = [
            'expert_id' => $expert_id,
            'expert_name'=> $expert['expert_name'],
            'expert_create_date' => $expert['expert_create_date'],
            'expert_member' => [
                'em_id'     => $expert['em_id'],
                'em_name'   => $expert_member['em_name'],
                'em_image'  => $em_image,
                'is_current'=> $this->member_id==$expert_member['member_id']?true:false
            ],
            'chat_starter'=> [
                'member_id'     => $expert['member_id'],
                'member_name'   => $sender['member_name'],
                'member_image'  => $member_image
            ],
        ];

        $this->page             = 'expert_directory/chat';
        $this->footer           = 'chat_expert';
        $this->customjs = ['expert_chat'];

        $this->data['title']    = 'Chat Expert';
		$this->data['data']     = $data;
		$this->menu             = 'learning';
		$this->generate_layout();
	}

	function new_chat($em_id){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('learning/expert_directory/list_chat/'.$em_id);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('expert_name', 'Name', 'required|trim');
        $this->form_validation->set_rules('expert_desc', 'Desc', 'required|trim');
        if ($this->form_validation->run() == FALSE){
            $this->data['url_return']   = $url_return;
            $this->data['form_action']  = site_url('learning/expert_directory/new_chat/'.$em_id);
            $this->data['title']        = 'Ask Something';
            $this->page                 = 'expert_directory/new_chat';
            $this->menu                 = 'learning';
            $this->generate_layout();
        }else {
            $this->expert_model->recData['emId'] = $em_id;
            $expert_member = $this->expert_model->select_expert_member('byId');

            // memastikan pembuat chat bukan expert member
            if ($expert_member['member_id']==$this->member_id) redirect($url_return);

            $recData['catId']       = $expert_member['cat_id'];
            $recData['emConcern']   = '';
            $recData['emId']        = $em_id;
            $recData['memberId']    = $this->member_id;
            $recData['expertName']  = $this->input->post('expert_name', TRUE);
            $recData['expertAlias'] = $this->function_api->generate_alias($this->input->post('expert_name'));
            $recData['expertDesc']  = $this->input->post('expert_desc', TRUE);
            $recData['expertSticky']= '0';
            $recData['expertStatus']= 'open';
            $recData['expertCloseDate']     ='';
            $recData['expertCloseBy']       ='';
            $recData['expertCloseReason']   ='';
            $expert_id = $this->expert_model->insert_expert($recData);
            if ($expert_id){
                $recData['expertId']    = $expert_id;
                $recData['emId']        = $em_id;
                $recData['member_id']   = $this->member_id;
                $recData['ecDesc']      = $recData['expertDesc'];
                $recData['ecImage']     = $this->input->post('image');
                $recData['ecStatus']    = 'active';
                $this->expert_model->insert_expert_chat($recData);
                redirect(site_url('learning/expert_directory/chat/').$expert_id);
            } else {
                redirect($url_return);
            }
        }
    }

	function add_chat($expert_id){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('learning/expert_directory/chat/'.$expert_id);
        }

        // expert detail
        $this->expert_model->recData['expertId'] = $expert_id;
        $expert = $this->expert_model->select_expert("byId");

        // expert member detail
        $this->expert_model->recData['emId'] = $expert['em_id'];
        $expert_member = $this->expert_model->select_expert_member('byId');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('em_id', 'Expert Member ID', 'required');
        if ($_FILES["chat_image"]['name']==''){
            echo 'set rule desc';
            $this->form_validation->set_rules('desc', 'Desc', 'required');
        }
        if ($this->form_validation->run() == FALSE){
            redirect($url_return);
        }else {
            $filename = '';
            if(isset($_FILES["chat_image"]) && $_FILES["chat_image"]['name']!=""){
                $name = $_FILES["chat_image"]["name"];
                $arrName = explode(".",$name);
                $ext = end($arrName);
                $filename = 'chat_'.$expert_id.'_'.uniqid().'.'.$ext;
                $config['upload_path']          = MEDIA_IMAGE_PATH;
                $config['allowed_types']        = 'gif|jpg|png';
                $config['file_name']            = $filename;
                $config['overwrite']			= true;
                $config['max_size']             = 1024; // 1MB
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;
                $this->load->library('upload', $config);
                $this->upload->do_upload('chat_image');
            }

            $recData['expertId']    = $expert_id;
            $recData['emId']        = $this->input->post('em_id');
            $recData['memberId']    = $this->member_id;
            $recData['ecDesc']      = $this->input->post('desc', TRUE);
            $recData['ecImage']     = $filename;
            $recData['ecStatus']    = 'active';
            $insert_id = $this->expert_model->insert_expert_chat($recData);
            if ($insert_id){
                // update latest read id ke chat yang baru dikirim
                $this->expert_model->recData['memberId'] = $this->member_id;
                $this->expert_model->update_latest_read_chat_id($insert_id);
                if ($this->member_id != $expert_member['member_id']){
                    // Send push notification
                    $this->load->library('fcm');
                    $tData = ['memberId'=>$expert_member['member_id']];
                    $dtoken = $this->member_model->select_member_device_token('byMemberId', $tData, 'Y');
                    $tokens = [];
                    foreach ($dtoken as $t){
                        array_push($tokens, $t['device_token']);
                    }
                    $this->fcm->setTitle($expert['expert_name']);
                    $desc = $this->session->userdata('member_name').': '.$recData['ecDesc'];
                    $this->fcm->setBody($desc);
                    $payload = array(
                        'page' => site_url('learning/expert_directory/chat/').$expert_id
                    );
                    $this->fcm->setData($payload);
                    $this->fcm->sendMultiple($tokens);
                    // End of push notification
                }

                redirect(site_url('learning/expert_directory/chat/').$expert_id);
            } else {
                redirect($url_return);
            }
        }
    }

    function search(){
        $this->page = 'expert_directory/search';
        $this->data['title'] = 'Search Expert';
        $this->customjs = array('expert_directory_search');
        $this->menu = 'learning';
        $this->generate_layout();
    }

    function ajax_search(){
	    $result = $this->expert_model->select_expert_member('search','',10);
	    $data   = [];
	    foreach ($result as $r){
	        $tmp['em_id']       = $r['em_id'];
	        $tmp['member_id']   = $r['member_id'];
	        $tmp['em_name']     = $r['em_name'];
	        $tmp['em_concern']  = $r['em_concern'];
	        $tmp['institution'] = '';
	        $tmp['title'] = '';
            $data_exp = json_decode($r['em_experience'], true);
            foreach ($data_exp as $dx){
                if ($dx['isDefault'] == 1){
                    $tmp['institution'] = $dx['institution'];
                    $tmp['title']       = $dx['title'];
                }
            }
            if($r['em_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$r['em_image'])){
                $tmp['em_image'] = URL_MEDIA_IMAGE."/".$r['em_image'];
            } else {
                $tmp['em_image'] = base_url('assets/img/avatar.png');
            }
            $tmp['member_city'] = $r['member_city'];
            $tmp['detail_url']  = site_url('learning/expert_directory/detail_expert/').$r['em_id'];
	        array_push($data, $tmp);
        }
	    echo json_encode($data);
    }

    function ajax_fetch_chat(){
	    $expert_id = $this->input->get('expert_id');
	    $page = $this->input->get('page');
        $page = $page>=1?$page:1;
        $limit = 100; // content per page
        $this->expert_model->beginRec = $limit*$page-$limit;
        $this->expert_model->endRec = $limit;

        $this->expert_model->recData['expertId'] = $expert_id;
        $chat_data = $this->expert_model->select_expert_chat('withDetail');
        $expert = $this->expert_model->select_expert('byId');

        // expert member detail
        $this->expert_model->recData['emId'] = $expert['em_id'];
        $expert_member = $this->expert_model->select_expert_member('byId');
        if($expert_member['em_image']!="" && file_exists(MEDIA_IMAGE_PATH."/".$expert_member['em_image'])){
            $em_image = URL_MEDIA_IMAGE."/".$expert_member['em_image'];
        } else {
            $em_image = base_url('assets/img/avatar.png');
        }

        // member (sender) detail
        $this->member_model->recData['memberId'] = $expert['member_id'];
        $sender = $this->member_model->select_member('byId');
        if($sender['member_image']!=""){
            $member_image = $sender['member_image'];
        } else {
            $member_image = base_url('assets/img/avatar.png');
        }

        $chats = [];
        $to_latest_read_id = 0; // id untuk disimpan sebagai chat yg terakhir dibaca
        foreach ($chat_data as $cd){
            if ($cd['ec_image']) $cd['ec_image'] = site_url(MEDIA_IMAGE_PATH . $cd['ec_image']);
            if ($cd['member_id'] == $this->member_id){
                $cd['member_status'] = 'current';
            } elseif ($cd['member_id'] == $expert_member['member_id']) {
                $cd['member_status'] = 'expert';
                $cd['member_name'] = $expert_member['em_name'];
            } elseif ($cd['member_id'] == $sender['member_id']){
                $cd['member_status'] = 'starter';
            } else {
                $cd['member_status'] = 'user';
            }
            $cd['divider_date'] = $this->function_api->convert_datetime($cd['ec_create_date'], 'l, M d');
            $chats[] = $cd;
            $to_latest_read_id = $cd['ec_id'];
        }

        // dapatkan id chat terakhir dibaca/didapatkan
        $this->expert_model->recData['memberId'] = $this->member_id;
        $latest_read_id = $this->expert_model->get_latest_read_chat_id();

        // set id chat terakhir dibaca/didapatkan
        if ($to_latest_read_id > $latest_read_id){
            $this->expert_model->update_latest_read_chat_id($to_latest_read_id);
        }
        $data = [
            'expert_id' => $expert_id,
            'expert_name'=> $expert['expert_name'],
            'expert_member' => [
                'em_id'     => $expert['em_id'],
                'em_name'   => $expert_member['em_name'],
                'em_image'  => $em_image,
                'is_current'=> $this->member_id==$expert_member['member_id']?true:false
            ],
            'chats'          => $chats,
            'latest_read_id' => $latest_read_id,
            'chat_starter'=> [
                'member_id'     => $expert['member_id'],
                'member_name'   => $sender['member_name'],
                'member_image'  => $member_image
            ],
        ];

        echo json_encode($data);
    }
}
