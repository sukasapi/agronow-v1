<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads extends CI_Controller {

    public function __construct(){
        parent::__construct();
        user_is_login();
        $this->load->model(array(
            'ads_model',
            'media_model',
            'group_model',
            'member_level_model',
            'klien_model',
        ));

        $this->section_id = 21;

    }


    function l_ajax(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $list = $this->ads_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row['ads_id']              = $item->ads_id;
            $row['ads_create_date']     = $item->ads_create_date?parseDateShortReadable($item->ads_create_date):NULL;
            $row['ads_create_time']     = $item->ads_create_date?parseTimeReadable($item->ads_create_date):NULL;
            $row['ads_date_start']    = $item->ads_start?parseDateShortReadable($item->ads_start):NULL;
            $row['ads_time_start']    = $item->ads_start?parseTimeReadable($item->ads_start):NULL;
            $row['ads_date_end']    = $item->ads_end?parseDateShortReadable($item->ads_end):NULL;
            $row['ads_time_end']    = $item->ads_end?parseTimeReadable($item->ads_end):NULL;
            $row['ads_sponsor']            = $item->ads_sponsor;
            $row['ads_link']            = prep_url($item->ads_link);
            $row['ads_status']  = $item->ads_status;
            $row['ads_position']  = $item->ads_position;
            $row['ads_image']  = $item->ads_image;

            $row['ads_order']  = $item->ads_order;

            $row['nama_klien'] = getKlienBySectionData($this->section_id,$item->ads_id,'content', 'render_nama_klien');

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ads_model->count_all(),
            "recordsFiltered" => $this->ads_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_ads($ads_id){
        $get_ads = $this->ads_model->get($ads_id);
        if ($get_ads==FALSE){
            redirect(404);
        }else{
            return $get_ads;
        }
    }

    function index(){
        has_access('ads.view');

        $data['form_opt_klien'][NULL] = NULL;

        $param_query = NULL;
        if (my_klien()){
            $param_query['filter_id'] = my_klien();
        }
        $get_klien = $this->klien_model->get_all(NULL,NULL,NULL,$param_query);
        if ($get_klien!=FALSE){
            foreach ($get_klien['data'] as $k => $v) {
                $data['form_opt_klien'][$v['id']] = $v['nama'];
            }
        }

        $data['page_name']          = 'Ads';
        $data['page_sub_name']      = 'List ads';
        $data['is_content_header']  = TRUE;
        $data['page']               = 'ads/ads_list_view';
        $this->load->view('main_view',$data);
    }

    function detail($ads_id=NULL){
        has_access('ads.view');

        $ads = $this->get_ads($ads_id);

        $data['ads']        = $ads;
        $data['ads']['klien']   = getKlienBySectionData($this->section_id,$ads_id,'content', 'render_nama_klien');

        $data['page_name']      = 'Ads';
        $data['page_sub_name']  = $ads['ads_sponsor'];
        $data['page'] = 'ads/ads_detail_view';
        $this->load->view('main_view',$data);
    }

    function create(){
        has_access('ads.create');

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('ads');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('ads_sponsor', 'Nama', 'required|trim');
        if (!my_klien()){
            $this->form_validation->set_rules('klien[]', 'Klien', 'required|trim');
        }

        if ($this->form_validation->run() == FALSE){

            $data['form_opt_cat'][NULL] = NULL;

            $data['klien'] = getKlienAll();

            $data['url_return']     = $url_return;
            $data['editable']       = FALSE;

            $data['page_name']      = 'Ads';
            $data['page_sub_name']  = 'Tambah';

            $data['form_action']    = site_url('ads/create');
            $data['page']           = 'ads/ads_form_create_view';

            $this->load->view('main_view',$data);

        }else{

            $post = $this->input->post();

            $data = array(
                'ads_sponsor'   => $post['ads_sponsor'],
                'ads_link'      => $post['ads_link'],
                'ads_image'     => " ",
                'ads_position'  => $post['ads_position'],
                'ads_status'    => $post['ads_status'],
                'ads_order'     => $post['ads_order'],
                'ads_start'     => isset($post['ads_start'])?parseDate($post['ads_start']):NULL,
                'ads_end'       => isset($post['ads_end'])?parseDate($post['ads_end']):NULL,
                'ads_create_date'   => date("Y-m-d H:i:s"),
            );

            $insert = $this->ads_model->insert($data);


            if ($insert==TRUE) {
                create_log($this->section_id,$insert,'Tambah',NULL);

                // Proses Klien
                if (my_klien()){
                    insertKlienBySectionData($this->section_id, $insert, my_klien(),'content');
                }else{
                    // Superadmin
                    if (isset($post['klien'])){
                        $kliens = $post['klien'];
                        if ($kliens){
                            foreach ($kliens as $id_klien){
                                if (is_numeric($id_klien) && $id_klien > 0){
                                    insertKlienBySectionData($this->section_id, $insert, $id_klien,'content');
                                }
                            }
                        }
                    }
                }

            }else{
                flash_notif_failed(NULL,$url_return,FALSE);
            }

            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

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

                $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $go_upload = $this->upload->data();

                    $update_ads = $this->ads_model->update(
                        array(
                            'ads_id'      => $insert,
                            'ads_image'   => $filename_system,
                        )
                    );

                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File


            if (!$notif) {
                flash_notif_success(NULL,site_url('ads/detail/').$insert);
            }else{
                $msg = '';
                foreach ($notif as $k => $v) {
                    $msg .= $v."<br>";
                }
                flash_notif_warning($msg,$url_return);
            }



        }
    }

    function edit($ads_id=NULL){
        has_access('ads.edit');

        $ads = $this->get_ads($ads_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('ads/detail/').$ads_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('ads_id', 'ID', 'required');
        $this->form_validation->set_rules('ads_sponsor', 'Nama', 'required|trim');
        if (!my_klien()){
            $this->form_validation->set_rules('klien[]', 'Klien', 'required|trim');
        }

        if ($this->form_validation->run() == FALSE){
            $data['request']            = $ads;
            $data['ads']            = $ads;
            $data['ads']['section_id'] = $this->section_id;
            $data['klien'] = getKlienAll();
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('ads/edit').'/'.$ads_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Ads";
            $data['page_sub_name']      = 'Edit Ads'.'<br><small>'.$ads['ads_sponsor'].'</small>';
            $data['page']               = 'ads/ads_form_edit_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();

            $data = array(
                'ads_id'   => $post['ads_id'],
                'ads_sponsor'   => $post['ads_sponsor'],
                'ads_link'      => $post['ads_link'],
                'ads_position'  => $post['ads_position'],
                'ads_status'    => $post['ads_status'],
                'ads_order'     => $post['ads_order'],
                'ads_start'     => isset($post['ads_start'])?parseDate($post['ads_start']):NULL,
                'ads_end'       => isset($post['ads_end'])?parseDate($post['ads_end']):NULL,
            );

            // Proses Klien
            // Superadmin
            if (!my_klien()){
                if (isset($post['klien'])){

                    $kliens = $post['klien'];
                    foreach ($kliens as $k => $v){
                        if ($v <= 0){
                            unset($kliens[$k]);
                        }
                    }

                }else{
                    $kliens = NULL;
                }
                updateKlienBySectionData($this->section_id,$ads_id,$kliens,'content');
            }


            $edit = $this->ads_model->update($data);

            if ($edit==TRUE) {
                create_log($this->section_id,$ads_id,'Edit',NULL);
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = "Data berhasil disimpan.";
                flash_notif_warning($msg,$url_return);
            }

        }
    }

    function edit_picture($ads_id=NULL){
        has_access('ads.edit');

        $ads = $this->get_ads($ads_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('ads/detail/').$ads_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('ads_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $ads['media_value'] = $ads['ads_image'];
            $data['request']            = $ads;
            $data['ads']            = $ads;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('ads/edit_picture').'/'.$ads_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Ads";
            $data['page_sub_name']      = 'Edit Gambar'.'<br><small>'.$ads['ads_sponsor'].'</small>';
            $data['page']               = 'ads/ads_form_edit_picture_view';
            $this->load->view('main_view',$data);
        }else{
            $post = $this->input->post();


            // Start Handle File
            $this->load->library('upload');
            if (isset($_FILES['file']['name'])){

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

                $config['allowed_types'] = 'jpg|png|jpeg|pdf';
                $config['max_size']      = '5000';

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')){

                    $go_upload = $this->upload->data();

                    $update_ads = $this->ads_model->update(
                        array(
                            'ads_id'      => $ads['ads_id'],
                            'ads_image'   => $filename_system,
                        )
                    );

                }else{
                    // GAGAL UPLOAD
                    $notif[] = $this->upload->display_errors();
                    $notif[] = "Upload file gagal. Silahkan cek kembali. Maksimal size 5 mb.";
                }

            }
            // End Handle File

            create_log($this->section_id,$ads_id,'Edit',NULL);
            if (!$notif) {
                flash_notif_success(NULL,$url_return);
            }else{
                $msg = '';
                foreach ($notif as $k => $v) {
                    $msg .= $v."<br>";
                }
                flash_notif_warning($msg,$url_return);
            }

        }
    }


    function delete($ads_id=NULL){
        has_access('ads.delete');

        $ads = $this->get_ads($ads_id);

        $url_return = $this->input->get('url_return');
        if(empty($url_return)){
            $url_return = site_url('ads/detail/').$ads_id;
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('ads_id', 'ID', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['ads']         = $ads;
            $data['request']            = $ads;
            $data['url_return']         = $url_return;
            $data['form_action']        = site_url('ads/delete').'/'.$ads_id;
            $data['editable']           = TRUE;
            $data['page_name']          = "Ads";
            $data['page_sub_name']      = 'Hapus Ads';
            $data['page']               = 'ads/ads_form_delete_view';
            $this->load->view('main_view',$data);
        }else{

            $delete = $this->ads_model->delete($ads_id,TRUE);
            if ($delete==TRUE) {
                create_log($this->section_id,$ads_id,'Hapus',NULL);
                $msg        = $ads['ads_sponsor']." telah dihapus.";
                $url_return = site_url('ads');
                flash_notif_warning($msg,$url_return);
            }else{
                $msg        = "Data gagal dihapus.";
                $url_return = site_url('ads/delete/'.$ads_id);
                flash_notif_failed($msg,$url_return);
            }

        }
    }





}