<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Inbox_model inbox_model
 * @property Function_api function_api
 * @property Member_model member_model
 */
class Leaderboard extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('member_name'))){
            redirect('login');
        }
        $this->load->library('function_api');
        $this->load->model(['member_model', 'group_model']);
        $this->member_id = $this->session->userdata('member_id');
    }

	function index(){
        // member
        $this->member_model->recData['memberId'] = $this->member_id;
        $dataMember = $this->member_model->select_member("byId");

        // leaderboard
        // all time
        $recData['userId'] = $this->member_id;
        $lb_global    = $this->member_model->select_rank("", $recData, 10);
        $in_lb = false;
        $all_time_data = [];
        foreach ($lb_global as $lb){
            $user = [
                'member_id'     => $lb['member_id'],
                'member_name'   => $lb['member_name'],
                'member_image'  => '',
                'member_poin'   => $lb['member_poin'],
                'rank'          => $lb['member_rank']
            ];
			if($lb['member_image']!="" && $lb['member_image'] != "#"){
				if (filter_var($lb['member_image'], FILTER_VALIDATE_URL)) {
					$user['member_image'] = $lb['member_image'];
				} else {
					$user['member_image'] = URL_MEDIA_IMAGE."/".$lb['member_image'];
				}
			} else {
                $user['member_image'] = base_url('assets/img/avatar.png');
            }
            if ($user['member_id']==$this->member_id){
                $in_lb = true;
            }
            array_push($all_time_data, $user);
        }
        if (!$in_lb){
            $recData['memberId'] = $this->member_id;
            $user_rank  = $this->member_model->select_rank("byUserId", $recData, 1);
            $user = [
                'member_id'     => $dataMember['member_id'],
                'member_name'   => $dataMember['member_name'],
                'member_image'  => '',
                'member_poin'   => $dataMember['member_poin'],
                'rank'          => $user_rank
            ];
			if($dataMember['member_image']!="" && $dataMember['member_image'] != "#"){
				if (filter_var($dataMember['member_image'], FILTER_VALIDATE_URL) === FALSE) {
					$user['member_image'] = URL_MEDIA_IMAGE."/".$dataMember['member_image'];
				} else {
					$user['member_image'] = $dataMember['member_image'];
				}
			} else {
                $user['member_image'] = base_url('assets/img/avatar.png');
            }
            array_push($all_time_data, $user);
        }

        // this month
        $recData['memberId'] = '';
        $lb_group= $this->member_model->select_rank("thisMonth", $recData, 10);
        $in_lb = false;
        $this_month_data = [];
		foreach ($lb_group as $lb){
			$user = [
                'member_id'     => $lb['member_id'],
                'member_name'   => $lb['member_name'],
                'member_poin'   => $lb['poin'],
                'rank'          => $lb['member_rank']
            ];
			if($lb['member_image']!="" && $lb['member_image'] != "#"){
				$user['member_image'] = validate_member_image($lb['member_image']);
			} else {
                $user['member_image'] = base_url('assets/img/avatar.png');
            }
            if ($user['member_id']==$this->member_id){
                $in_lb = true;
            }
            array_push($this_month_data, $user);
        }
        if (!$in_lb){
            $recData['memberId'] = $this->member_id;
            $user_rank= $this->member_model->select_rank("thisMonth", $recData,1);
            $user = [
                'member_id'     => $dataMember['member_id'],
                'member_name'   => $dataMember['member_name'],
                'member_poin'   => $user_rank?$user_rank[0]['poin']:'-',
                'rank'          => $user_rank?$user_rank[0]['member_rank']:'-'
            ];
            if($dataMember['member_image']!="" && $dataMember['member_image'] != "#"){
				$user['member_image'] = validate_member_image($dataMember['member_image']);
            } else {
                $user['member_image'] = base_url('assets/img/avatar.png');
            }
            array_push($this_month_data, $user);
        }

        // group
        $recData['groupId'] = $dataMember['group_id'];
        $recData['memberId'] = '';
        $lb_group     = $this->member_model->select_rank("byGroup", $recData, 10);
        $in_lb = false;
        $group_data = [];
        foreach ($lb_group as $lb){
            $user = [
                'member_id'     => $lb['member_id'],
                'member_name'   => $lb['member_name'],
                'member_image'  => '',
                'member_poin'   => $lb['member_poin'],
                'rank'          => $lb['member_rank']
            ];
            if($lb['member_image']!="" && $lb['member_image'] != "#"){
				if (filter_var($lb['member_image'], FILTER_VALIDATE_URL)) {
					$user['member_image'] = $lb['member_image'];
				} else {
					$user['member_image'] = URL_MEDIA_IMAGE."/".$lb['member_image'];
				}
            } else {
                $user['member_image'] = base_url('assets/img/avatar.png');
            }
            if ($user['member_id']==$this->member_id){
                $in_lb = true;
            }
            array_push($group_data, $user);
        }
        if (!$in_lb){
            $recData['memberId'] = $this->member_id;
            $user_rank= $this->member_model->select_rank("byGroup", $recData,1);
            $user = [
                'member_id'     => $dataMember['member_id'],
                'member_name'   => $dataMember['member_name'],
                'member_image'  => '',
                'member_poin'   => $dataMember['member_poin'],
                'rank'          => $user_rank?$user_rank[0]['rank']:'-'
            ];
            if($dataMember['member_image']!="" && $dataMember['member_image'] != "#"){
				if (filter_var($dataMember['member_image'], FILTER_VALIDATE_URL)){
					$user['member_image'] = $dataMember['member_image'];
				} else {
					$user['member_image'] = URL_MEDIA_IMAGE."/".$dataMember['member_image'];
				}
            } else {
                $user['member_image'] = base_url('assets/img/avatar.png');
            }
            array_push($group_data, $user);
        }

        $this->group_model->recData['groupId'] = $dataMember['group_id'];
        $group = $this->group_model->select_group('byId');

        $this->data['data']['group'] = $group;
        $this->data['data']['leaderboard'] = [
            'all_time'      => $all_time_data,
            'this_month'    => $this_month_data,
            'group'         => $group_data
            ];
        $this->data['title']    = 'Leaderboard';
		$this->page             = 'leaderboard';
		$this->menu             = 'home';
		$this->generate_layout();
	}
}
