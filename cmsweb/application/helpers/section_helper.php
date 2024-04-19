<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getSiteUrlBySection')){
    function getSiteUrlBySection($section_id){
        $ci = &get_instance();

        // Content
        $sections = array(
            '13'  =>  'article',
            '12'  =>  'news',
            '42'  =>  'bod_share',
            '34'  =>  'ceo_notes',
            '35'  =>  'digital_library',
            '36'  =>  'digital_sop',
            '18'  =>  'knowledge_sharing',   //E-Learning
            '38'  =>  'kamus',
            '31'  =>  'knowledge_sharing',
            '29'  =>  'learning_room',
            '22'  =>  'announcement',
            '27'  =>  'qr_content',
            '28'  =>  'reading_room',
        );


        if (isset($sections[$section_id])){
            return site_url($sections[$section_id]).'/';
        }else{
            return site_url();
        }
    }
}



if ( !function_exists('getKlienAll')){
    function getKlienAll(){
        $ci = &get_instance();
        $ci->load->model('klien_model');

        $get_klien = $ci->klien_model->get_all();
        if ($get_klien){
            $result = $get_klien['data'];
        }else{
            $result = NULL;
        }

        return $result;

    }
}

if ( !function_exists('getKlienBySectionData')){
    function getKlienBySectionData($section_id,$data_id,$source, $opt=NULL){
        $ci = &get_instance();
        $ci->load->model('section_klien_model');

        $kliens = $opt=='render_nama_klien'? NULL : [];
        $get_klien = $ci->section_klien_model->get_by_section_data($section_id,$data_id,$source);

        if ($get_klien){
            foreach ($get_klien as $k => $v){

                if ($opt == 'nama_klien'){
                    array_push($kliens,$v['nama_klien']);
                }elseif($opt == 'render_nama_klien'){

                    $k_end = sizeof($get_klien)-1;
                    if ($k == $k_end){
                        $kliens .= $v['nama_klien'];
                    }else{
                        $kliens .= $v['nama_klien'].', ';
                    }

                }else{
                    array_push($kliens,$v['id_klien']);
                }

            }
        }

        return $kliens;

    }
}


if ( !function_exists('getKlienByMember')){
    function getKlienByMember($member_id, $opt=NULL){
        $ci = &get_instance();
        $ci->load->model('section_klien_model');

        $kliens = $opt=='render_nama_klien'? NULL : NULL;
        $get_klien = $ci->section_klien_model->get_by_member($member_id);

        if ($get_klien){
            if ($opt == 'render_nama_klien'){
                $kliens = $get_klien['nama_klien'];
            } else{
                $kliens = $get_klien['id_klien'];
            }
        }

        return $kliens;

    }
}


if ( !function_exists('insertKlienBySectionData')){
    function insertKlienBySectionData($section_id,$data_id,$id_klien,$source){
        $ci = &get_instance();
        $ci->load->model('section_klien_model');

        $data = [
            'section_id'    => $section_id,
            'data_id'    => $data_id,
            'id_klien'    => $id_klien,
            'source'    => $source
        ];
        $ci->section_klien_model->insert($data);

        return true;

    }
}

if ( !function_exists('updateKlienBySectionData')){
    function updateKlienBySectionData($section_id,$data_id,$id_kliens,$source){
        $ci = &get_instance();
        $ci->load->model('section_klien_model');

        $ci->section_klien_model->delete_by_section_data($section_id,$data_id,$source);
        if ($id_kliens){
            foreach ($id_kliens as $v){
                $data = [
                    'section_id'    => $section_id,
                    'data_id'    => $data_id,
                    'id_klien'    => $v,
                    'source'    => $source
                ];
                $ci->section_klien_model->insert($data);
            }
        }

        return true;

    }
}

if ( !function_exists('deleteKlienBySectionData')){
    function deleteKlienBySectionData($section_id,$data_id,$source){
        $ci = &get_instance();
        $ci->load->model('section_klien_model');

        $ci->section_klien_model->delete_by_section_data($section_id,$data_id,$source);

        return true;

    }
}


