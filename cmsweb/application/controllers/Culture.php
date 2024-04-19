<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Culture extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'culture_model',
            'culture_member_model',
            'culture_soal_model',
            'category_model',
            'media_model',
            'group_model',
            'member_level_model',
        ));
        $select_tree = [];
        $this->section_id = 33;
    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->culture_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['cr_id']           = $item->cr_id;
            $row['cr_date_start']   = $item->cr_date_start?parseDateShortReadable($item->cr_date_start):NULL;
            $row['cr_date_end']     = $item->cr_date_end?parseDateShortReadable($item->cr_date_end):NULL;
            $row['cr_name']         = $item->cr_name;
            $row['cat_name']        = $item->cat_name;
            $row['cr_date_detail']  = $item->cr_date_detail;
            $row['cr_time_start']   = parseTimeReadable($item->cr_time_start);
            $row['cr_time_end']     = parseTimeReadable($item->cr_time_end);

            $user_count  = $this->culture_member_model->count_by_culture($item->cr_id);
            $row['user_count']       = $user_count?$user_count['total']:0;

            $now = date('Y-m-d');
            if ($now >= $item->cr_date_start && $now <= $item->cr_date_end){
                $row['cr_status']       = "Progress";
            }else{
                $row['cr_status']       = "Outgoing";
            }


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->culture_model->count_all(),
            "recordsFiltered" => $this->culture_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_culture($culture_id){
        $get_culture = $this->culture_model->get($culture_id);
        if ($get_culture==FALSE){
            redirect(404);
        }else{
            return $get_culture;
        }
    }

    private function printTree($tree, $r = 0, $p = null, $cat_selected) {
        foreach ($tree as $i => $t) {
            if ($cat_selected==$t['cat_id']){
                $is_selected = 'selected';
            }else{
                $is_selected = '';
            }
            $dash = ($t['cat_parent'] == 0) ? '' : str_repeat('- ', $r) .' ';

            $this->select_tree[] = '<option value="'.$t['cat_id'].'" '.$is_selected.'>'.$dash.$t['cat_name'].'</option>';
            //printf("\t<option value='%d' %s>%s%s</option>\n", $t['cat_id'],$is_selected, $dash, $t['cat_name']);
            if ($t['cat_parent'] == $p) {
                // reset $r
                $r = 0;
            }
            if (isset($t['child'])) {
                $this->printTree($t['child'], $r+1, $t['cat_parent'], $cat_selected);
            }
        }
    }

    function index(){
        has_access('culture.view');

        $data['section_id']     = $this->section_id;
        $data['page_name']          = 'Corporate Culture';
        $data['page_sub_name']      = 'List Corporate Culture';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'culture/culture_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($culture_id=NULL){
        has_access('culture.view');

        $culture = $this->get_culture($culture_id);

        $data['culture']        = $culture;
        $data['page_name']      = 'Culture';
        $data['page_sub_name']  = $culture['cr_name'];
        $data['page'] = 'culture/culture_detail_view';
        $data['submenu'] = 'culture/culture_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('account');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_name', 'Nama', 'required|trim');


        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id);
            $get_cat_parent = $this->input->get('parent');
            if ($this->input->get('parent')){
                $cat_selected = $get_cat_parent;
            }else{
                $cat_selected = NULL;
            }

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Culture';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('culture/create');
            $data['page']           = 'culture/culture_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
                'cr_desc'           => $post['cr_desc'],
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,
                'cr_time_start'     => isset($post['cr_time_start'])?$post['cr_time_start']:NULL,
                'cr_time_end'       => isset($post['cr_time_end'])?$post['cr_time_end']:NULL,
                'cr_has_certificate'           => $post['cr_has_certificate'],
                'cr_create_date'   => date("Y-m-d H:i:s"),
            );

            $extra_cr_prelearning = array(
                'Desc'      => NULL,
                'Alert'     => NULL,
                'Materi'    => array(),
            );
            $data['cr_prelearning'] = json_encode($extra_cr_prelearning);

            $extra_cr_pretest = array(
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'  => NULL,
                'Status'  => NULL,
                'GradeA'  => NULL,
                'GradeB'  => NULL,
                'GradeC'  => NULL,
                'Question'  => NULL,
            );
            $data['cr_pretest'] = json_encode($extra_cr_pretest);

            $extra_cr_module = array(
                'Desc'    => NULL,
                'Module'  => array(),
            );
            $data['cr_module'] = json_encode($extra_cr_module);

            $extra_cr_competency  = array(
                'ctStart'  => NULL,
                'ctEnd'    => NULL,
                'Desc'     => NULL,
                'TimeLimit' => NULL,
                'Attemp'    => NULL,
                'QuePerPage'=> NULL,
                'ReqPassed' => NULL,
                'Random'  => NULL,
                'Status'  => NULL,
                'GradeA'  => NULL,
                'GradeB'  => NULL,
                'GradeC'  => NULL,
                'Question'  => NULL,
            );
            $data['cr_competency'] = json_encode($extra_cr_competency);

            $extra_cr_certificate = array(
                'Logo'  => NULL,
                'Signature'  => NULL,
                'Text1'  => NULL,
                'Text2'  => NULL,
                'Text3'  => NULL,
                'Text4'  => NULL,
                'Text5'  => NULL,
                'Text6'  => NULL,
                'serType'  => NULL,
                'noStart'  => NULL,
                'noEnd'  => NULL,
                'noUsed' => NULL,
                'serCode'=> NULL,
            );
            $data['cr_certificate'] = json_encode($extra_cr_certificate);

            $extra_cr_feedback = array(
                'Desc'   => NULL,
                'Status' => NULL,
                'Type'   => array(),
                'Question'  => array(),
            );
            //$data['cr_feedback'] = json_encode($extra_cr_feedback);

            $insert = $this->culture_model->insert($data);
            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);
                $url_return = site_url('culture/detail/').$insert;
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function edit($culture_id=NULL){
        has_access('culture.edit');

        $culture = $this->get_culture($culture_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/detail/'.$culture_id);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');
        $this->form_validation->set_rules('cr_name', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = '-';
            $get_category = $this->category_model->get($culture['cat_id']);
            if ($get_category){
                $data['form_opt_cat'][$get_category['cat_id']] = $get_category['cat_name'];
            }

            /*Start Select Tree*/
            $data['opt_cat'] = $this->category_model->get_category_tree($this->section_id);
            $cat_selected = $culture['cat_id'];

            $this->select_tree[] = '<option value="" >-</option>';
            $this->printTree($data['opt_cat'],NULL,NULL,$cat_selected);
            $data['option_tree'] = $this->select_tree;
            /*End Select Tree*/

            $data['request']            = $culture;
            $data['culture']            = $culture;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('culture/edit').'/'.$culture_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Culture";
            $data['page_sub_name']      = 'Edit Culture';
            $data['page']               = 'culture/culture_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();
            $data = array(
                'cr_id'  => $post['cr_id']==NULL?NULL:$post['cr_id'],
                'cr_name'           => $post['cr_name'],
                'cat_id'            => $post['cat_id'],
                'cr_desc'           => $post['cr_desc'],
                'cr_date_start'     => isset($post['cr_date_start'])?parseDate($post['cr_date_start']):NULL,
                'cr_date_end'       => isset($post['cr_date_end'])?parseDate($post['cr_date_end']):NULL,
                'cr_time_start'     => isset($post['cr_time_start'])?$post['cr_time_start']:NULL,
                'cr_time_end'       => isset($post['cr_time_end'])?$post['cr_time_end']:NULL,
                'cr_has_certificate'           => $post['cr_has_certificate'],
            );


            $edit = $this->culture_model->update($data);
            if ($edit==TRUE) {
                create_log($this->section_id,$culture_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg        = "Tidak ada perubahan data.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function delete($culture_id=NULL){
        has_access('culture.delete');

        $culture = $this->get_culture($culture_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/detail/').$culture_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cr_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['culture']         = $culture;
            $data['request']            = $culture;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('culture/delete').'/'.$culture_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Culture";
            $data['page_sub_name']      = 'Hapus Culture';
            $data['page']               = 'culture/culture_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->culture_model->delete($culture_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$culture_id,'Hapus',NULL);
                $msg        = $culture['cr_name']." telah dihapus.";
                $url_return = site_url('culture');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('culture/delete/'.$culture_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }



    function pengumuman($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['culture']        = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/pengumuman/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('culture/pengumuman/'.$culture_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_pengumuman_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_lp' => $post['Desc'],
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Pengumunan');

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    function rencana($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['culture']        = $culture;
        $data['request']   = $culture;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/rencana/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('culture/rencana/'.$culture_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_rencana_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);


        }else{

            $post = $this->input->post();

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_rp' => $post['Desc'],
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Rencana');

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

        }
    }

    // Modul
    function module($culture_id=NULL){

        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'Desc'       => NULL,
                'Module'  => NULL,
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module;

            //print_r($data['module']);
            $data['form_action']    = site_url('culture/module/'.$culture_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_module_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc']       =  $post['Desc'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);


            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }



    }

    function module_update_tree($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module/').$culture_id;
        }

        if ($culture['cr_module']){
            $cr_module = $culture['cr_module'];
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        //print_r($extra_module);exit();

        $module_update = array();

        $post   = $this->input->post();
        $order = json_decode($post['order'],TRUE);

        foreach ($order as $k => $v){
            $module_update[$v] = $extra_module['Module'][$v];
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        //print_r($materi_update);exit();
        $arr_data['Module']  = $module_update;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $culture_id,
            'cr_module' => $json_data,
        );

        $update = $this->culture_model->update($data);


        // Update Urutan Hasil Modul yang telah disubmit
        $member = $this->culture_member_model->get_by_culture($culture_id);
        if ($member){
            foreach ($member as $k => $v){

                if ($v['crm_step']){

                    $jsonCrmStep = preg_replace('/[[:cntrl:]]/', '', $v['crm_step']);
                    $crm_step = json_decode($jsonCrmStep,TRUE);

                    if (isset($crm_step['MP'])){
                        $current_mp = $crm_step['MP'];

                        $updated_mp = array();
                        foreach ($order as $i => $j){

                            $updated_mp[$j] = $current_mp[$j];

                        }

                        $crm_step['MP'] = $updated_mp;

                        $json_data_crm_step = json_encode($crm_step, JSON_UNESCAPED_SLASHES);
                        $data_member = array(
                            'crm_id'    => $v['crm_id'],
                            'crm_step'  => $json_data_crm_step
                        );
                        //print_r($data_member);
                        $this->culture_member_model->update($data_member);

                    }

                }

            }
        }
        //exit();



        if ($update==TRUE) {
            create_log($this->section_id,$culture_id,'Edit','Modul - Urutan');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
        }

    }

    function module_add($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'ModuleName'    => NULL,
                'ModuleStart'  => NULL,
                'ModuleEnd'  => NULL,
            );

            $data['request']   = $extra_module;

            //print_r($data['module']);
            $data['form_action']    = site_url('culture/module_add/'.$culture_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_module_add_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();
            $new_module = array(
                'ModuleName'    => $post['ModuleName'],
                'ModuleStart'   => parseDate($post['ModuleStart']),
                'ModuleEnd'     => parseDate($post['ModuleEnd']),
                'Materi'        => array(),
                'Quiz'          => array(),
                'Evaluasi'      => array(
                    'Desc' => NULL,
                    'TimeLimit'  => NULL,
                    'Attemp'     => NULL,
                    'QuePerPage' => NULL,
                    'ReqPassed'  => NULL,
                    'Random'  => NULL,
                    'Status'  => 'non-active',
                    'GradeA'  => NULL,
                    'GradeB'  => NULL,
                    'GradeC'  => NULL,
                    'Question'=> NULL,
                ),
                'Feedback'      => array(
                    'Status'  => 'non-active',
                    'Desc'    => NULL,
                    'Type'    => array(),
                    'Question'=> array(),
                ),
            );

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][] = $new_module;

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Tambah','Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_edit($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'ModuleName'    => NULL,
                'ModuleStart'   => NULL,
                'ModuleEnd'     => NULL,
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index];

            //print_r($data['module']);
            $data['form_action']    = site_url('culture/module_edit/'.$culture_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_module_edit_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['ModuleName']  = $post['ModuleName'];
            $arr_data['Module'][$index]['ModuleStart'] = parseDate($post['ModuleStart']);
            $arr_data['Module'][$index]['ModuleEnd']   = parseDate($post['ModuleEnd']);

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_delete($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module/').$culture_id;
        }

        $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        unset($arr_data['Module'][$index]);

        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $culture_id,
            'cr_module' => $json_data,
        );

        $update = $this->culture_model->update($data);


        // Hapus Hasil Modul yang telah disubmit
        /*$member = $this->culture_member_model->get_by_culture($culture_id);
        if ($member){
            foreach ($member as $k => $v){

                if ($v['crm_step']){

                    $jsonCrmStep = preg_replace('/[[:cntrl:]]/', '', $v['crm_step']);
                    $crm_step = json_decode($jsonCrmStep,TRUE);

                    if (isset($crm_step['MP'])){
                        $current_mp = $crm_step['MP'];

                        unset($crm_step['MP'][$index]);

                        $json_data_crm_step = json_encode($crm_step, JSON_UNESCAPED_SLASHES);
                        $data_member = array(
                            'crm_id'    => $v['crm_id'],
                            'crm_step'  => $json_data_crm_step
                        );
                        //print_r($data_member);
                        $this->culture_member_model->update($data_member);

                    }

                }

            }
        }*/
        //exit();


        if ($update==TRUE) {
            create_log($this->section_id,$culture_id,'Hapus','Modul');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
        }
    }


    // Modul Materi
    function module_materi($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module/').$culture_id;
        }


        if ($culture['cr_module']){
            $cr_module = $culture['cr_module'];
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $data['request']   = $extra_module['Module'][$index];
        $data['module_id'] = $index;

        //print_r($data['module']);
        $data['page_name']      = 'Corporate Culture';
        $data['page_sub_name']  = $culture['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
        $data['page'] = 'culture/culture_module_materi_view';
        $data['submenu'] = 'culture/culture_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function module_materi_update_tree($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module_materi/').$culture_id.'/'.$index;
        }

        if ($culture['cr_module']){
            $cr_module = $culture['cr_module'];
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
            $extra_module = json_decode($json_cr_module,TRUE);
        }


        $materi   = $extra_module['Module'][$index]['Materi'];
        $materi_update = array();

        $post   = $this->input->post();
        $order = json_decode($post['order'],TRUE);

        foreach ($order as $k => $v){
            $materi_update[$v] = $materi[$v];
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        //print_r($materi_update);exit();
        $arr_data['Module'][$index]['Materi']  = $materi_update;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $culture_id,
            'cr_module' => $json_data,
        );

        $update = $this->culture_model->update($data);
        if ($update==TRUE) {
            create_log($this->section_id,$culture_id,'Edit','Materi Modul - Urutan');
            flash_notif_success(NULL,$url_return);
        }else{
            flash_notif_failed('Tidak ada perubahan data.',$url_return);
        }

    }

    function module_materi_add($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module_materi/').$culture_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_materi = array(
                'ContentName'    => NULL,
                'Type'   => NULL,
                'Media'     => NULL,
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_materi;

            //print_r($data['module']);
            $data['form_action']    = site_url('culture/module_materi_add/'.$culture_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Culture';
            $data['page_sub_name']  = $culture['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'culture/culture_module_materi_add_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $media = isset($post['Media'])?$post['Media']:'';

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;

                $file_folder = $post['Type']=='document'?'document':'video';
                $full_folder = $upload_folder.$file_folder;


                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                if ($post['Type']=='document'){
                    $config['allowed_types'] = 'xlsx|xls|doc|docx|ppt|pptx|txt|pdf';
                }else{
                    $config['allowed_types'] = 'mpeg|mpg|mp4|mpe|qt|mov|avi';
                }

                $config['max_size']      = '50000';

                //print_r($config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $media = $filename_system;

                    $go_upload = $this->upload->data();
                    $data_media = array(
                        'section_id'    => 30,
                        'data_id'       => 0,
                        'media_name'    => $filename_origin,
                        'media_alias'   => slugify($filename_origin),
                        'media_desc'    => '',
                        'media_type'    => $post['Type']=='document'?'document':'video',
                        'media_value'   => $filename_system,
                        'media_size'    => formatFileSize($go_upload['file_size']),
                        'media_primary' => '1',
                        'media_status'  => '1',
                        'media_create_date' => date('Y-m-d H:i:s')
                    );

                    $this->load->model('media_model');


                    $insert_media = $this->media_model->insert($data_media);

                    if ($insert_media==FALSE) {
                        //GAGAL INSERT FILE
                        $notif[] = "File Uploaded, but insert data to database failed.";

                    }

                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    //$notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

                //print_r($notif);

            }
            // End Handle File

            $materi = array(
                'Type'  => $post['Type']=='youtube'?'video':$post['Type'],
                'ContentName'  => $post['ContentName'],
                'Media'  => $media,
            );
            $arr_data['Module'][$index]['Materi'][]  = $materi;

            //print_r($arr_data);exit();
            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'     => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Tambah','Materi Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_materi_add_digital_library($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);

        $post   = $this->input->post();
        $content_id = $post['id'];

        $this->load->model('content_model');
        $content = $this->content_model->get($content_id,35);
        if (!$content){
            echo json_encode(['status'=>400,'message'=>'Konten tidak ditemukan']);exit();
        }


        $get_media_document = $this->media_model->get_by_section_data_file_only(35,$content_id);
        //print_r($get_media_document);
        $type   = $get_media_document?$get_media_document['media_type']:NULL;
        $media  = $get_media_document?$get_media_document['media_value']:NULL;

        if (empty($media)){
            echo json_encode(['status'=>400,'message'=>'Media tidak ditemukan']);exit();
        }


        $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
        $arr_data = json_decode($json_raw,TRUE);

        $materi = array(
            'Type'          => $type,
            'ContentId'     => $content['content_id'],
            'ContentName'   => $content['content_name'],
            'Media'         => $media,
        );
        $arr_data['Module'][$index]['Materi'][]  = $materi;

        //print_r($arr_data);exit();
        $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

        $data = array(
            'cr_id'     => $culture_id,
            'cr_module' => $json_data,
        );

        $update = $this->culture_model->update($data);
        if ($update==TRUE) {
            create_log($this->section_id,$culture_id,'Tambah','Materi Modul');
            echo json_encode(['status'=>200,'message'=>'Materi berhasil ditambahkan']);exit();
        }else{
            echo json_encode(['status'=>400,'message'=>'Materi gagal ditambahkan']);exit();
        }

    }

    function module_materi_delete($culture_id=NULL,$index,$materi_id){
        $culture = $this->get_culture($culture_id);
        if ($culture['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $existing_materi = $extra_module['Module'][$index]['Materi'];
        unset($existing_materi[$materi_id]);

        //print_r($existing_materi);exit();

        $extra_module['Module'][$index]['Materi'] =  $existing_materi;
        $json_data = json_encode($extra_module, JSON_UNESCAPED_SLASHES);

        //print_r($extra_module);

        $data = array(
            'cr_id' => $culture_id,
            'cr_module' => $json_data,
        );
        //print_r($data);exit();
        $this->culture_model->update($data);
        create_log($this->section_id,$culture_id,'Hapus','Materi Modul');
        redirect(site_url('culture/module_materi/').$culture_id.'/'.$index);
    }

    // Modul Evaluasi
    function module_evaluasi($culture_id=NULL,$index){
        //$index = $index;
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module_evaluasi/').$culture_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_pretest = array(
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'     => NULL,
                'Status'     => NULL,
                'GradeA'     => NULL,
                'GradeB'     => NULL,
                'GradeC'     => NULL,
                'Question'   => NULL,
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index]['Evaluasi'];

            $get_soal = NULL;
            $param_query['filter_status'] = 'publish';
            $param_query['filter_ids'] = array();
            if (isset($extra_module['Module'][$index]['Evaluasi']['Question'])){
                if (!empty($extra_module['Module'][$index]['Evaluasi']['Question'])){
                    $param_query['filter_ids'] = explode(',',$extra_module['Module'][$index]['Evaluasi']['Question']);
                }
            }

            if (empty($param_query['filter_ids'])){
                $get_soal = NULL;
            }else{
                $get_soal = $this->culture_soal_model->get_all(NULL,NULL,NULL,$param_query);
                if ($get_soal){
                    $get_soal = $get_soal['data'];
                }
            }

            $data['soal'] = $get_soal;

            //print_r($data['module']);
            $data['form_action']    = site_url('culture/module_evaluasi/'.$culture_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Culture';
            $data['page_sub_name']  = $culture['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'culture/culture_module_evaluasi_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Evaluasi']['Desc']       =  $post['Desc'];
            $arr_data['Module'][$index]['Evaluasi']['TimeLimit']  = $post['TimeLimitMinute'].':'.$post['TimeLimitSecond'];
            $arr_data['Module'][$index]['Evaluasi']['Attemp']     =  $post['Attemp'];
            $arr_data['Module'][$index]['Evaluasi']['QuePerPage'] =  $post['QuePerPage'];
            $arr_data['Module'][$index]['Evaluasi']['ReqPassed']  =  $post['ReqPassed'];
            $arr_data['Module'][$index]['Evaluasi']['Random']     =  $post['Random'];
            $arr_data['Module'][$index]['Evaluasi']['Status']     =  $post['Status'];


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Evaluasi Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_evaluasi_add_soal($culture_id=NULL,$index){
        $culture = $this->get_culture($culture_id);
        if ($culture['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $existing_question = explode(',',$extra_module['Module'][$index]['Evaluasi']['Question']);

        $post = $this->input->post();
        $new_question = $post['crs_ids'];

        $question = array_unique (array_merge ($existing_question, $new_question));

        $extra_module['Module'][$index]['Evaluasi']['Question'] =  implode(',',$question);
        $json_data = json_encode($extra_module, JSON_UNESCAPED_SLASHES);

        //print_r($extra_module);exit();

        $data = array(
            'cr_id' => $culture_id,
            'cr_module' => $json_data,
        );

        $update = $this->culture_model->update($data);
        create_log($this->section_id,$culture_id,'Tambah','Soal Evaluasi Modul');
        echo json_encode(
            array('succ'=>sizeof($new_question))
        );
    }

    function module_evaluasi_remove_soal($culture_id=NULL,$index,$question_id=NULL){
        $culture = $this->get_culture($culture_id);
        if ($culture['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $extra_module = json_decode($json_cr_module,TRUE);
        }

        $existing_question = explode(',',$extra_module['Module'][$index]['Evaluasi']['Question']);

        if (($key = array_search($question_id, $existing_question)) !== false) {
            unset($existing_question[$key]);
        }


        $extra_module['Module'][$index]['Evaluasi']['Question'] =  implode(',',$existing_question);
        $json_data = json_encode($extra_module, JSON_UNESCAPED_SLASHES);

        //print_r($extra_module);

        $data = array(
            'cr_id' => $culture_id,
            'cr_module' => $json_data,
        );
        //print_r($data);exit();
        $this->culture_model->update($data);
        create_log($this->section_id,$culture_id,'Hapus','Soal Evaluasi Modul');
        redirect(site_url('culture/module_evaluasi/').$culture_id.'/'.$index.'#soal');
    }


    // Modul Feedback
    function module_feedback($culture_id=NULL,$index){
        //$index = $index;
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module_feedback/').$culture_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_module = array(
                'Desc'     => NULL,
                'Status'   => NULL,
                'Type'     => [],
                'Question' => [],
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_module['Module'][$index]['Feedback'];
            $data['module_id']   = $index;

            //print_r($data['module']);
            $data['form_action']    = site_url('culture/module_feedback/'.$culture_id.'/'.$index);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'culture/culture_module_feedback_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Feedback']['Desc']       =  $post['Desc'];
            $arr_data['Module'][$index]['Feedback']['Status']     =  $post['Status'];


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Feedback');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }
    }

    function module_feedback_add($culture_id=NULL,$index){

        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module_feedback/').$culture_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Type'      => NULL,
                'Question'  => NULL,
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']   = $extra_question;
            $data['module_id']   = $index;

            //print_r($data['question']);
            $data['form_action']    = site_url('culture/module_feedback_add/'.$culture_id.'/'.$index);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'culture/culture_module_feedback_add_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Feedback']['Type'][] = $post['Type'];
            $arr_data['Module'][$index]['Feedback']['Question'][] = $post['Question'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'       => $post['cr_id'],
                'cr_module' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Tambah','Pertanyaan Feedback Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function module_feedback_edit($culture_id=NULL,$index,$question_id){

        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/module_feedback/').$culture_id.'/'.$index;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_question = array(
                'Question'  => NULL,
                'Type'     => NULL,
            );

            if ($culture['cr_module']){
                $cr_module = $culture['cr_module'];
                $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $cr_module);
                $extra_module = json_decode($json_cr_module,TRUE);
            }

            $data['request']        = $extra_module['Module'][$index]['Feedback'];
            $data['module_id']      = $index;
            $data['question_id']    = $question_id;

            //print_r($data['question']);
            $data['form_action']    = site_url('culture/module_feedback_edit/'.$culture_id.'/'.$index.'/'.$question_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'].'<br><small>Modul : '.$extra_module['Module'][$index]['ModuleName'].'</small>';
            $data['page'] = 'culture/culture_module_feedback_edit_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Module'][$index]['Feedback']['Question'][$question_id] = $post['Question'];
            $arr_data['Module'][$index]['Feedback']['Type'][$question_id] = $post['Type'];

            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id'         => $post['cr_id'],
                'cr_module'   => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Pertanyaan Feedback Modul');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }

    }

    function module_feedback_delete($culture_id=NULL,$index,$question_id){
        $culture = $this->get_culture($culture_id);
        if ($culture['cr_module']){
            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $extra_data = json_decode($json_cr_module,TRUE);
        }

        $existing_data = $extra_data;
        unset($existing_data['Module'][$index]['Feedback']['Question'][$question_id]);
        unset($existing_data['Module'][$index]['Feedback']['Type'][$question_id]);

        //print_r($existing_data);exit();

        $extra_data =  $existing_data;
        $json_data = json_encode($extra_data, JSON_UNESCAPED_SLASHES);

        //print_r($extra_data);

        $data = array(
            'cr_id' => $culture_id,
            'cr_module' => $json_data,
        );
        //print_r($data);exit();
        $this->culture_model->update($data);
        create_log($this->section_id,$culture_id,'Hapus','Pertanyaan Feedback Modul');
        flash_notif_success('Data berhasil dihapus',NULL,FALSE);
        redirect(site_url('culture/module_feedback/').$culture_id.'/'.$index);
    }


    // Kompetensi
    function competency($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['culture']        = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/competency/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_competency = array(
                'ctStart'    => NULL,
                'ctEnd'      => NULL,
                'Desc'       => NULL,
                'TimeLimit'  => NULL,
                'Attemp'     => NULL,
                'QuePerPage' => NULL,
                'ReqPassed'  => NULL,
                'Random'     => NULL,
                'Status'     => NULL,
                'GradeA'     => NULL,
                'GradeB'     => NULL,
                'GradeC'     => NULL,
                'GradeD'     => NULL,
                'Question'   => NULL,
            );

            if ($culture['cr_competency']){
                $cr_competency = $culture['cr_competency'];
                $json_cr_competency = preg_replace('/[[:cntrl:]]/', '', $cr_competency);
                $extra_competency = json_decode($json_cr_competency,TRUE);
            }

            $data['request']   = $extra_competency;

            $get_soal = NULL;
            $param_query['filter_status'] = 'publish';
            $param_query['filter_ids'] = array();
            if (isset($extra_competency['Question'])){
                if (!empty($extra_competency['Question'])){
                    $param_query['filter_ids'] = explode(',',$extra_competency['Question']);
                }
            }

            if (empty($param_query['filter_ids'])){
                $get_soal = NULL;
            }else{
                $get_soal = $this->culture_soal_model->get_all(NULL,NULL,NULL,$param_query);
                if ($get_soal){
                    $get_soal = $get_soal['data'];
                }
            }

            $data['soal'] = $get_soal;

            //print_r($data['request']);
            $data['form_action']    = site_url('culture/competency/'.$culture_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_competency_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_competency']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Desc']       =  $post['Desc'];
            $arr_data['TimeLimit']  = $post['TimeLimitMinute'].':'.$post['TimeLimitSecond'];

            $arr_data['Attemp']     =  NULL; // Dihilangkan
            $arr_data['QuePerPage'] =  $post['QuePerPage'];
            $arr_data['ReqPassed']  =  NULL; // Dihilangkan
            $arr_data['Random']     =  $post['Random'];
            $arr_data['GradeA']     =  $post['GradeA'];
            $arr_data['GradeB']     =  $post['GradeB'];
            $arr_data['GradeC']     =  $post['GradeC'];
            $arr_data['GradeD']     =  $post['GradeD'];

            $arr_data['ctStart']    =  parseDate($post['ctStart']);
            $arr_data['ctEnd']      =  parseDate($post['ctEnd']);
            $arr_data['Status']     =  "";


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_competency' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Competency Test');
                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed(NULL,$url_return);
            }

        }


    }

    function competency_add_soal($culture_id=NULL){
        $culture = $this->get_culture($culture_id);
        if ($culture['cr_competency']){
            $json_cr_competency = preg_replace('/[[:cntrl:]]/', '', $culture['cr_competency']);
            $extra_competency = json_decode($json_cr_competency,TRUE);
        }

        $existing_question = explode(',',$extra_competency['Question']);

        $post = $this->input->post();
        $new_question = $post['crs_ids'];

        $question = array_unique (array_merge ($existing_question, $new_question));

        $extra_competency['Question'] =  implode(',',$question);
        $json_data = json_encode($extra_competency, JSON_UNESCAPED_SLASHES);

        //print_r($extra_competency);

        $data = array(
            'cr_id' => $culture_id,
            'cr_competency' => $json_data,
        );

        $update = $this->culture_model->update($data);
        create_log($this->section_id,$culture_id,'Tambah','Soal Competency Test');
        echo json_encode(
            array('succ'=>sizeof($new_question))
        );
    }

    function competency_remove_soal($culture_id=NULL,$question_id=NULL){
        $culture = $this->get_culture($culture_id);
        if ($culture['cr_competency']){
            $json_cr_competency = preg_replace('/[[:cntrl:]]/', '', $culture['cr_competency']);
            $extra_competency = json_decode($json_cr_competency,TRUE);
        }

        $existing_question = explode(',',$extra_competency['Question']);

        if (($key = array_search($question_id, $existing_question)) !== false) {
            unset($existing_question[$key]);
        }


        $extra_competency['Question'] =  implode(',',$existing_question);
        $json_data = json_encode($extra_competency, JSON_UNESCAPED_SLASHES);

        //print_r($extra_competency);

        $data = array(
            'cr_id' => $culture_id,
            'cr_competency' => $json_data,
        );
        $this->culture_model->update($data);
        create_log($this->section_id,$culture_id,'Hapus','Soal Competency Test');
        redirect(site_url('culture/competency/'.$culture_id.'#soal'));
    }


    // Sertifikat
    function certificate($culture_id=NULL){

        $culture = $this->get_culture($culture_id);

        $data['culture']        = $culture;
        $data['request']   = $culture;


        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture/certificate/').$culture_id;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){
            $extra_certificate = array(
                'Logo'      => NULL,
                'Signature' => NULL,
                'Text1'     => NULL,
                'Text2'     => NULL,
                'Text3'     => NULL,
                'Text4'     => NULL,
                'Text5'     => NULL,
                'Text6'     => NULL,
                'serType'   => NULL,
                'noStart'   => NULL,
                'noEnd'     => NULL,
                'noUsed'    => NULL,
                'serCode'   => NULL,
            );

            if ($culture['cr_certificate']){
                $cr_certificate = $culture['cr_certificate'];
                $json_cr_certificate = preg_replace('/[[:cntrl:]]/', '', $cr_certificate);
                $extra_certificate = json_decode($json_cr_certificate,TRUE);
            }

            $data['request']   = $extra_certificate;

            //print_r($data['request']);
            $data['form_action']    = site_url('culture/certificate/'.$culture_id);
            $data['editable']       = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page']    = 'culture/culture_certificate_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);
        }else{
            $post   = $this->input->post();

            $json_raw = preg_replace('/[[:cntrl:]]/', '', $culture['cr_certificate']);
            $arr_data = json_decode($json_raw,TRUE);

            $arr_data['Text1']       =  $post['Text1'];
            $arr_data['Text2']       =  $post['Text2'];
            $arr_data['Text3']       =  $post['Text3'];
            $arr_data['Text4']       =  $post['Text4'];
            $arr_data['Text5']       =  $post['Text5'];
            $arr_data['Text6']       =  $post['Text6'];


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['Logo']['name'])){

                $_FILES['file']['name'] = $_FILES['Logo']['name'];
                $_FILES['file']['type'] = $_FILES['Logo']['type'];
                $_FILES['file']['tmp_name'] = $_FILES['Logo']['tmp_name'];
                $_FILES['file']['error'] = $_FILES['Logo']['error'];
                $_FILES['file']['size'] = $_FILES['Logo']['size'];

                // Config File Name
                $filename_origin  = $_FILES['file']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;
                $file_folder = 'image';
                $full_folder = $upload_folder.$file_folder;

                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                $config['allowed_types'] = 'png';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){
                    $arr_data['Logo']       =  $filename_system;
                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['Signature']['name'])){

                $_FILES['file']['name'] = $_FILES['Signature']['name'];
                $_FILES['file']['type'] = $_FILES['Signature']['type'];
                $_FILES['file']['tmp_name'] = $_FILES['Signature']['tmp_name'];
                $_FILES['file']['error'] = $_FILES['Signature']['error'];
                $_FILES['file']['size'] = $_FILES['Signature']['size'];

                // Config File Name
                $filename_origin  = $_FILES['Signature']['name'];
                $filename_system = formatFilenameSystem($filename_origin);

                $config['file_name']     = $filename_system;

                // Config Folder
                $upload_folder = UPLOAD_FOLDER;
                $file_folder = 'image';
                $full_folder = $upload_folder.$file_folder;

                $config['upload_path'] = $full_folder; //path folder
                if(!is_dir($full_folder)){
                    mkdir($full_folder,0777);
                }

                $config['allowed_types'] = 'png';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){
                    $arr_data['Signature']       =  $filename_system;
                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File


            $json_data = json_encode($arr_data, JSON_UNESCAPED_SLASHES);

            $data = array(
                'cr_id' => $post['cr_id'],
                'cr_certificate' => $json_data,
            );

            $update = $this->culture_model->update($data);
            if ($update==TRUE) {
                create_log($this->section_id,$culture_id,'Edit','Sertifikat');

                flash_notif_success(NULL,$url_return);
            }else{
                flash_notif_failed('Tidak ada perubahan data.',$url_return);
            }

        }



    }


    // Member
    function member($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['member']         = $this->culture_member_model->get_by_culture($culture_id);
        $data['member_count']   = $this->culture_member_model->count_by_culture($culture_id);
        //print_r($data);
        $data['culture']      = $culture;
        $data['page_name']      = 'Corporate Culture';
        $data['page_sub_name']  = $culture['cr_name'];
        $data['page'] = 'culture/culture_member_view';
        $data['submenu'] = 'culture/culture_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function member_add($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['culture'] = $culture;
        $data['request']   = $culture;

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('culture');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cr_id', 'ID', 'required|trim');

        if ($this->form_validation->run() == FALSE){

            $data['form_action']    = site_url('culture/member_add/'.$culture_id);
            $data['editable']      = TRUE;
            $data['page_name']      = 'Corporate Culture';
            $data['page_sub_name']  = $culture['cr_name'];
            $data['page'] = 'culture/culture_member_add_view';
            $data['submenu'] = 'culture/culture_detail_submenu_view';
            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $member_ids = $post['member_ids'];
            $culture_id = $post['cr_id'];

            foreach ($member_ids as $v){
                $member_id = $v;
                $get_member = $this->culture_member_model->get_by_culture_member($culture_id,$member_id);
                if ($get_member){
                    // Member Exist Then Skip

                }else{
                    $data = array(
                        'cr_id'     => $culture_id,
                        'member_id' => $member_id,
                    );
                    $insert_member = $this->culture_member_model->insert($data);
                }
            }
            create_log($this->section_id,$culture_id,'Tambah','Member');
            $url_return = site_url('culture/member/').$culture_id;
            flash_notif_success(NULL,$url_return);

        }
    }

    function member_add_picker($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $post = $this->input->post();
        $member_ids = $post['member_ids'];

        foreach ($member_ids as $v){
            $member_id = $v;
            $get_member = $this->culture_member_model->get_by_culture_member($culture_id,$member_id);
            if ($get_member){
                // Member Exist Then Skip

            }else{
                $data = array(
                    'cr_id'     => $culture_id,
                    'member_id' => $member_id,
                );
                $this->culture_member_model->insert($data);
            }
        }
        create_log($this->section_id,$culture_id,'Tambah','Member');
        echo json_encode(
            array('succ'=>sizeof($member_ids))
        );
    }

    function member_remove($culture_id=NULL,$crm_id=NULL){
        $culture = $this->get_culture($culture_id);
        $this->culture_member_model->delete($crm_id);
        create_log($this->section_id,$culture_id,'Hapus','Member');
        redirect(site_url('culture/member/'.$culture_id));
    }


    // PROGRESS PESERTA
    function progress_member($culture_id=NULL){
        $culture = $this->get_culture($culture_id);

        $data['member']         = $this->culture_member_model->get_by_culture($culture_id);
        $data['member_count']   = $this->culture_member_model->count_by_culture($culture_id);
        //print_r($data);
        $data['culture']      = $culture;
        $data['page_name']      = 'Corporate Culture';
        $data['page_sub_name']  = $culture['cr_name'];
        $data['page'] = 'culture/culture_progress_member_view';
        $data['submenu'] = 'culture/culture_detail_submenu_view';
        $this->load->view('main_view',$data);
    }

    function progress_member_excel($culture_id=NULL){

        $culture = $this->get_culture($culture_id);

        $member = $this->culture_member_model->get_by_culture($culture_id);
        $member_count = $this->culture_member_model->count_by_culture($culture_id);
        //print_r($member);exit();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $alpha = $this->createColumnsArray('ZZ');

        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'No');

        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('B1', 'Nama');

        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('C1', 'NIP');

        $sheet->mergeCells('D1:D2');
        $sheet->setCellValue('D1', 'Group');

        $sheet->mergeCells('E1:H1');
        $sheet->setCellValue('E1', 'Pre Test');

        $sheet->setCellValue('E2', 'Tanggal');
        $sheet->setCellValue('F2', 'Jml Soal');
        $sheet->setCellValue('G2', 'Benar');
        $sheet->setCellValue('H2', 'Salah');


        $module = array();
        if($culture['cr_module']){

            $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
            $cr_module = json_decode($json_cr_module,TRUE);

            if (isset($cr_module['Module'])){
                $module = $cr_module['Module'];
            }
        }

        $cur_alpha = 8;
        $col_length_eva = 4;
        $col_length_fb = 3;
        $module_no = 1;
        foreach ($module as $k => $v){

            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_eva-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Evaluasi Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Jml Soal');
            $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Benar');
            $sheet->setCellValue($alpha[$cur_alpha+3].'2', 'Salah');

            $cur_alpha = $cur_alpha + $col_length_eva;

            $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+$col_length_fb-1].'1');
            $sheet->setCellValue($alpha[$cur_alpha].'1', 'Feedback Modul '.$module_no);

            $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
            $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Nilai');
            $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Komentar');

            $cur_alpha = $cur_alpha + $col_length_fb;

            $module_no++;
        }

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha+3].'1');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Competency Test');

        $sheet->setCellValue($alpha[$cur_alpha].'2', 'Tanggal');
        $sheet->setCellValue($alpha[$cur_alpha+1].'2', 'Jml Soal');
        $sheet->setCellValue($alpha[$cur_alpha+2].'2', 'Benar');
        $sheet->setCellValue($alpha[$cur_alpha+3].'2', 'Salah');


        $cur_alpha = $cur_alpha + 4;

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha].'2');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Grade');

        $cur_alpha = $cur_alpha + 1;

        $sheet->mergeCells($alpha[$cur_alpha].'1:'.$alpha[$cur_alpha].'2');
        $sheet->setCellValue($alpha[$cur_alpha].'1', 'Nilai Akhir');


        $no = 1;
        $x = 3;


        if ($member){

            foreach($member as $row) {

                $result = json_decode($row['crm_step'],TRUE);

                if (isset($result['PT']['ptScore']) AND $result['PT']['ptScore']){
                    $ptScore = explode('-',$result['PT']['ptScore']);
                }else{
                    $ptScore = array('','','','');
                }

                if (isset($result['CT']['ctScore']) AND $result['CT']['ctScore']){
                    $ctScore = explode('-',$result['CT']['ctScore']);
                }else{
                    $ctScore = array('','','','');
                }


                $sheet->setCellValue('A'.$x, $no++);
                $sheet->setCellValue('B'.$x, $row['member_name']);
                $sheet->setCellValue('C'.$x, (string)$row['member_nip']);
                $sheet->setCellValue('D'.$x, $row['group_name']);

                $sheet->setCellValue('E'.$x, isset($result['PT']['ptDate']) ? ($result['PT']['ptDate'] ? date('d/m/Y H:i',strtotime($result['PT']['ptDate'])) : '' ) :'');
                $sheet->setCellValue('F'.$x, $ptScore[1] );
                $sheet->setCellValue('G'.$x, $ptScore[2] );
                $sheet->setCellValue('H'.$x, $ptScore[3] );

                $cur_alpha = 8;
                $module = array();
                if($culture['cr_module']){

                    $json_cr_module = preg_replace('/[[:cntrl:]]/', '', $culture['cr_module']);
                    $cr_module = json_decode($json_cr_module,TRUE);

                    if (isset($cr_module['Module'])){
                        $module = $cr_module['Module'];
                    }
                }



                if ($module){
                    foreach ($module as $k => $v){
                        $sheet->setCellValue($alpha[$cur_alpha].$x, isset($result['MP'][$k]['EvaDate']) ? ($result['MP'][$k]['EvaDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['EvaDate'])) : '' ) :'');

                        if (isset($result['MP'][$k]['EvaScore']) AND $result['MP'][$k]['EvaScore']){
                            $EvaScore = explode('-',$result['MP'][$k]['EvaScore']);
                        }else{
                            $EvaScore = array('','','','');
                        }

                        $sheet->setCellValue($alpha[$cur_alpha+1].$x, $EvaScore[1] );
                        $sheet->setCellValue($alpha[$cur_alpha+2].$x, $EvaScore[2] );
                        $sheet->setCellValue($alpha[$cur_alpha+3].$x, $EvaScore[3] );

                        $sheet->setCellValue($alpha[$cur_alpha+4].$x, isset($result['MP'][$k]['FbDate']) ? ($result['MP'][$k]['FbDate'] ? date('d/m/Y H:i',strtotime($result['MP'][$k]['FbDate'])) : '' ) :''  );
                        $sheet->setCellValue($alpha[$cur_alpha+5].$x, isset($result['MP'][$k]['FbDesc']) ? $result['MP'][$k]['FbDesc'] : '' );
                        $sheet->setCellValue($alpha[$cur_alpha+6].$x, '' );

                        $cur_alpha = $cur_alpha + 7;

                    }
                }


                $sheet->setCellValue($alpha[$cur_alpha].$x, isset($result['CT']['ctDate']) ? ($result['CT']['ctDate'] ? date('d/m/Y H:i',strtotime($result['CT']['ctDate'])) : '' ) :'');
                $sheet->setCellValue($alpha[$cur_alpha+1].$x, $ctScore[1] );
                $sheet->setCellValue($alpha[$cur_alpha+2].$x, $ctScore[2] );
                $sheet->setCellValue($alpha[$cur_alpha+3].$x, $ctScore[3] );

                $sheet->setCellValue($alpha[$cur_alpha+4].$x, isset($result['RESULT']) ? $result['RESULT'] : '' );
                $sheet->setCellValue($alpha[$cur_alpha+5].$x, $ctScore[2] ? str_replace('.',',',number_format($ctScore[2]/$ctScore[1]*100,1))  : '' );

                $x++;
            }

        }


        create_log($this->section_id,$culture_id,'Export','Progress Member');
        $writer = new Xlsx($spreadsheet);
        $filename = 'Progress_member_culture_'.slugify($culture['cr_name']);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    private function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

}