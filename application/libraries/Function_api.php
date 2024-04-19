<?php
/**
 * Created by PhpStorm.
 * User: silenceangel
 * Date: 08/08/20
 * Time: 12:06
 */

class Function_api
{
    var $msg = [
        'no_username_pass'  => 'Masukkan nip dan password',
        'create_token_failed'   => 'Gagal generate token, silahkan ulangi',
        '00' => 'Berhasil',
        '01' => 'Transaksi sedang diproses',

        '10' => 'Error login member',
        '11' => 'Member belum login',
        '12' => 'Status Member tidak aktif',
        '13' => 'Method tidak dikenal',
        '14' => 'Service tidak diijinkan',
        '15' => 'Kesalahan parameter',
        '16' => 'Format data tidak valid',
        '17' => 'Sesi login anda telah habis. Silahkan login kembali',
        '18' => 'Channel tidak dikenal',

        '19' => 'Email member sudah terdaftar',
        '20' => 'NIP member sudah terdaftar',
        '21' => 'Password lama tidak sesuai',
        '22' => 'Kesalahan NIP, password, atau group ID',
        '23' => 'NIP maksimal 25 karakter',
        '29' => 'Data tidak ditemukan',

        '31' => 'Data sudah dalam bookmark',
        '32' => 'Data tidak ada dalam bookmark',
        '33' => 'Keyword pencarian kosong',
        '34' => 'Kesalahan section',
        '35' => 'Nama kategori wajib diisi',
        '36' => 'Gambar kategori wajib diisi',
        '37' => 'Duplikat nama kategori',

        '40' => 'Kategori belum dipilih',
        '41' => 'Judul wajib diisi',
        '42' => 'Keterangan wajib diisi',
        '43' => 'Dokumen wajib diisi',
        '44' => 'Gambar 1 wajib diisi',
        '45' => 'ID konten wajib diisi',
        '46' => 'Status konten wajib diisi',
        '47' => 'Kesalahan status konten',

        '50' => 'Pelatihan belum dimulai',
        '51' => 'Pelatihan sudah selesai',
        '52' => 'Anda sudah pernah melakukan absensi',
        '53' => 'Pelatihan hari ini belum dimulai',
        '54' => 'Pelatihan hari ini sudah selesai',
        '55' => 'Hari ini tidak ada pelatihan',
        '56' => 'Anda tidak/belum terdaftar dalam pelatihan',

        '60' => 'Data Corporate Culture Tidak ditemukan',

        '99' => 'Maaf, untuk melakukan registrasi silahkan menghubungi administrator.',
    ];


    function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }

    function parseurl($var){
        $result = (isset($var)) ? $var : "";
        if(strpos($var,"?")>0)
            $result = substr($var,0,strpos($var,"?"));

        if(strpos($var,"#")>0)
            $result = substr($var,0,strpos($var,"#"));

        return $result;
    }

    function current_url()
    {
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $link .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        return $link;
    }

    function direct_host()
    {

        if (($_SERVER['HTTP_HOST'] != "localhost") && (strpos($_SERVER['HTTP_HOST'], "www.") === false)) {
            header("Location:" . current_url());
            exit;
        }
    }

    function generate_alias($str)
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        $plink = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $plink = str_replace(" &amp; ", " ", $plink);
        $plink = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $plink);
        $plink = strtolower(trim($plink, '-'));
        $plink = preg_replace("/[\/_| -]+/", '-', $plink);
        return $plink;
    }

    function parse_alphanumeric($str)
    {
        $str = strtolower($str);
        setlocale(LC_ALL, 'en_US.UTF8');
        //$plink = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $plink = preg_replace("/[^a-zA-Z0-9\/_| -\s]/", '', $str);
        $plink = strtolower(trim($plink, ' '));
        $plink = preg_replace("/[\/_| -]+/", ' ', $plink);
        return $plink;
    }

    function realias($var)
    {
        $result = str_replace(".html", "", htmlspecialchars($var));
        $result = str_replace(".php", "", $result);
        $result = str_replace("&", "&amp", $result);
        $result = str_replace("'", null, $result);
        $result = str_replace('"', null, $result);
        $result = str_replace('.', "", $result);
        return $result;
    }

    function security($var)
    {
        $result = "";
        if(isset($var)){
            $result = str_replace("'", '&apos;', trim($var));
            $result = str_replace('"', '&quot;', trim($result));
            //$result = htmlentities($result);
            $result = str_replace("&nbsp;"," ",$result);
        }
        return $result;
    }

    function secure($var)
    {
        return md5(md5($var));
    }

    function pagename($var)
    {
        $result = strtolower(str_replace("'", "", str_replace(" ", "-", $var)));
        return $result;
    }

    function securePost($param)
    {
        if (isset($_POST[$param])) {
            if (is_array($_POST[$param])) {
                foreach ($_POST[$param] as $key => $value) {
                    $_POST[$param][$key] = htmlspecialchars(str_replace("&amp;nbsp;", " ", trim($_POST[$param][$key])));
                }
                return $_POST[$param];
            } elseif (isset($_POST[$param])) {
                $getPost = str_replace("&amp;nbsp;", " ", $_POST[$param]);
                return htmlspecialchars(trim($_POST[$param]), ENT_QUOTES, "UTF-8");
            } else {
                return false;
            }
        }
        return false;
    }

    function className($var)
    {
        $result = str_replace(" ", "", ucwords($var));
        $result = str_replace("-", "", ucwords($result));
        return $result;
    }

    function tablename($var)
    {
        $result = str_replace("'", "", $var);
        $result = str_replace(" ", "_", $result);
        $result = strtolower($result);
        return $result;
    }

    function shortdesc($var, $word)
    {
        $shortdesc = "";
        if (isset($var) && $var != "") {
            $data = explode(" ", strip_tags(html_entity_decode($var), "<p><span><strong><blockquote>"));
            $count = count($data);
            for ($i = 0; $i < $word; $i++) {
                $shortdesc .= $data[$i] . " ";
                if ($i == $count - 1)
                    break;
            }
        }

        $shortdesc = strrpos($shortdesc, "<") > strrpos($shortdesc, ">") ? rtrim(substr($shortdesc, 0, strrpos($shortdesc, "<"))) : rtrim($shortdesc);
        return $this->closetags($shortdesc);
    }

    function excerpt($text, $chars=200)
    {
        $shortdesc = "";
        if (isset($text) && $text != "") {
            $data = strip_tags($text);
            $data = preg_replace("/&#?[a-z0-9]{2,8};/i","",$data );
            $shortdesc = substr($data, 0, $chars);
        }
        return $shortdesc;
    }

    function closetags($html)
    {
        preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
        $openedtags = $result[1];

        preg_match_all("#</([a-z]+)>#iU", $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);

        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= "</" . $openedtags[$i] . ">";
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }

    function success_notif($var)
    {
        if (isset($var)) {
            return click_hide('successtext') . '<div class="successtext">' . $var . '</div>';
        }
    }

    function error_notif($var)
    {
        if (isset($var)) {
            return click_hide('errortext') . '<div class="errortext">' . $var . '</div>';
        }
    }

    function error_notif2($var)
    {
        if (isset($var)) {
            return click_hide('errortex2t') . '<div class="errortext2">' . $var . '</div>';
        }
    }

    function warning_notif($var)
    {
        if (isset($var)) {
            echo '<div class="warningtext">' . $var . '</div>';
        }
    }

    function info_notif($var)
    {
        if (isset($var)) {
            echo click_hide('info') . '<div class="info">' . $var . '</div>';
        }
    }

    function error_field(&$var)
    {
        if (isset($var)) {
            echo '<div class="errorfield">' . $var . '</div>';
        }
    }

    function error_form($var)
    {
        if (isset($var)) {
            echo '<div class="errorform">' . $var . '</div>';
        }
    }

    function error_class(&$var)
    {
        if (isset($var)) {
            echo ' error';
        }
    }

    function text_value(&$var1, $var2)
    {
        if (isset($var1) && $var1 != "") {
            return $var1;
        } else {
            return $var2;
        }
    }

    function select_value(&$var1, $var2, $var3)
    {
        if (isset($var1) && $var1 != "") {
            if ($var1 == $var3) {
                return ' selected="selected" ';
            }
        } else {
            if ($var2 == $var3) {
                return ' selected="selected" ';
            }
        }
    }

    function check_value(&$var1, $var2, $var3)
    {
        if (isset($var1) && $var1 != "") {
            if ($var1 == $var3) {
                return ' checked="checked" ';
            }
        } else {
            if ($var2 == $var3) {
                return ' checked="checked" ';
            }
        }
    }

    function check_value_multi($var1 = array(), $var2 = array(), $var3)
    {
        if (count($var1) > 0) {
            if (in_array(trim($var3), $var1)) {
                return ' checked="checked"';
            }
        } elseif (count($var2) > 0) {
            if (in_array(trim($var3), $var2)) {
                return ' checked="checked"';
            }
        }
    }

//NEW FUNCTION
// --------------------------------------------------------------------
    function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * Get the value from a form
     *
     * Permits you to repopulate a form field with the value it was submitted
     * with, or, if that value doesn't exist, with the default
     *
     * @access	public
     * @param	string	the field name
     * @param	string
     * @return	void
     */
    function set_value($field = '', $default = '')
    {
        if (!isset($_POST[$field])) {
            return $default;
        }

        // If the data is an array output them one at a time.
        //     E.g: form_input('name[]', set_value('name[]');
        if (is_array($_POST[$field])) {
            return array_shift($_POST[$field]);
        }

        return $_POST[$field];
    }

    function set_value2($field = '', $default = '')
    {
        if (!isset($_POST[$field])) {
            return $default;
        }

        // If the data is an array output them one at a time.
        //     E.g: form_input('name[]', set_value('name[]');
        if (is_array($_POST[$field])) {
            return array_shift($_POST[$field]);
        }

        return $_POST[$field];
    }

// --------------------------------------------------------------------
    /**
     * Set Select
     *
     * Enables pull-down lists to be set to the value the user
     * selected in the event of an error
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	string
     */
    function set_select($field = '', $value = '', $default = FALSE)
    {

        if (!isset($_POST[$field])) {
            if ($default === TRUE) {
                return ' selected="selected"';
            } elseif ($default == $value) {
                return ' selected="selected"';
            } elseif (is_array($default) && in_array($value, $default)) {
                return ' selected="selected"';
            }
            return '';
        }

        $field = $_POST[$field];

        if (is_array($field)) {
            if (!in_array($value, $field)) {
                return '';
            }
        } else {
            if (($field == '' OR $value == '') OR ($field != $value)) {
                return '';
            }
        }

        return ' selected="selected"';
    }

// --------------------------------------------------------------------
    /**
     * Set Checkbox
     *
     * Enables checkboxes to be set to the value the user
     * selected in the event of an error
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	string
     */
    function set_checkbox($field = '', $value = '', $default = FALSE)
    {

        if (!isset($_POST[$field])) {
            if ($default === TRUE) {
                return ' checked="checked"';
            } elseif ($default !== FALSE) {
                return ' checked="checked"';
            } elseif (is_array($default) && in_array($value, $default)) {
                return ' checked="checked"';
            }
            return '';
        }

        $field = $_POST[$field];

        if (is_array($field)) {
            if (!in_array($value, $field)) {
                return '';
            }
        } else {
            if (($field == '' OR $value == '') OR ($field != $value)) {
                return '';
            }
        }

        return ' checked="checked"';
    }

// --------------------------------------------------------------------
    /**
     * Set Checkbox
     *
     * Enables checkboxes to be set to the value the user
     * selected in the event of an error
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	string
     */
    function set_radio($field = '', $value = '', $default = FALSE)
    {

        if (!is_array($field) && !isset($_POST[$field])) {
            if ($default === TRUE) {
                return ' checked="checked"';
            } elseif ($default !== FALSE && $value == $default) {
                return ' checked="checked"';
            } elseif (is_array($default) && in_array($value, $default)) {
                return ' checked="checked"';
            }
            return '';
        }

        if (is_array($field)) {
            if (!in_array($value, $field)) {
                return '';
            }
        } else {
            $field = $_POST[$field];
            if (($field == '' OR $value == '') OR ($field != $value)) {
                return '';
            }
        }
        return ' checked="checked"';
    }

// --------------------------------------------------------------------
    function detail($content)
    {
        echo SITE_HOST . "/info/" . $content;
    }

    function direct_root()
    {
        header("Location:" . SITE_HOST);
        exit;
    }

    function direct_root_member()
    {
        header("Location:" . MEMBER_HOST);
        exit;
    }

    function direct_root_admin()
    {
        header("Location:" . ADMIN_HOST);
        exit;
    }

    function direct($success = "", $error = "", $post = "")
    {
        if (isset($success) && $success != "") {
            $_SESSION['MsgText'][$post] = success_notif($success);
        }
        if (isset($error) && $error != "") {
            $_SESSION['MsgText'][$post] = error_notif($error);
        }
        header("Location:" . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function cek_status($arg)
    {
        if ($arg) {
            $stat = "Active";
        } else {
            $stat = "Blocked";
        }
        return $stat;
    }

    function set_status($arg)
    {
        if ($arg) {
            $stat = "block";
        } else {
            $stat = "unblock";
        }
        return $stat;
    }

    function status_approve($arg)
    {
        if ($arg == "1") {
            $stat = '<span class="green t11px">APPROVED</span>';
        } else {
            $stat = '<span class="red t11px">UN-APPROVED</span>';
            ;
        }
        return $stat;
    }

    function get_status_icon($arg)
    {
        if ($arg == '0') {
            $stat = "<img src='" . ADMIN_TEMPLINK . "/images/draft.png' alt='Draft' Title='Draft' />";
        } elseif ($arg == '1') {
            $stat = "<img src='" . ADMIN_TEMPLINK . "/images/active.png' alt='Active' title='Publish' />";
        } elseif ($arg == '2') {
            $stat = "<img src='" . ADMIN_TEMPLINK . "/images/delete.png' alt='Trash' title='Trash' />";
        }
        return $stat;
    }

    function status_name($var)
    {
        $stat = "";
        if ($var == "1") {
            $stat = "Active";
        }
        if ($var == "0") {
            $stat = "Non-Active";
        }
        return $stat;
    }

    function label_status($status,$class=""){
        $label = "";
        $status = strtolower($status);
        $statusName = $status;
        $style = ($class!="") ? $class : "label-mini";
        if($status=="0") $statusName = "non-active";
        if($status=="1") $statusName = "active";
        if($status=="publish" || $status=="active" || $status=="1" || $status=="open")
            $label = '<span class="label label-success '.$style.'">'.$statusName.'</span>';
        if($status=="non-active" || $status=="0" || $status=="pending")
            $label = '<span class="label label-warning '.$style.'">'.$statusName.'</span>';
        if($status=="block" || $status=="close" || $status=="expired")
            $label = '<span class="label label-danger '.$style.'">'.$statusName.'</span>';
        if($status=="draft")
            $label = '<span class="label label-inverse '.$style.'">'.$statusName.'</span>';
        return $label;
    }

    function product_status($label){
        $showLabel = "";
        $labelName = ucwords(str_replace("-"," ",$label));
        if($label=="open") $showLabel = '<span class="label label-info">'.$labelName.'</span>';
        if($label=="close") $showLabel = '<span class="label label-danger">Gangguan</span>';

        return $showLabel;
    }//@febri 23-07-2016

    function highlight_status($status){
        $label = "";
        $status = strtolower($status);
        $statusName = $status;
        if($status=="0") $statusName = "no";
        if($status=="1") $statusName = "yes";
        if($status=="1")
            $label = '<span class="label label-success label-mini">'.$statusName.'</span>';
        if($status=="0")
            $label = '<span class="label label-warning label-mini">'.$statusName.'</span>';
        return $label;
    }

    function yes_no($var)
    {
        if ($var == "1") {
            $stat = "Yes";
        }
        if ($var == "0") {
            $stat = "No";
        }
        return $stat;
    }

    function null_to_dash($data)
    {
        foreach ($data as &$value) {
            if ($value == "" || $value == NULL) {
                $value = "-";
            }
        }
        return $data;
    }

    function dollar($data)
    {
        return number_format($data, '2', '.', ',');
    }

    function rupiah($data)
    {
        return number_format($data, '2', ',', '.');
    }

    function number($data)
    {
        return number_format($data, '0', ',', '.');
    }

    function now()
    {
        return date('Y-m-d H:i:s');
    }

    function click_hide($opt)
    {
        return "
        <script type='text/javascript'>
        jQuery(document).ready(function(){
            jQuery('." . $opt . "').click(function() {
              jQuery('." . $opt . "').fadeOut('slow', function() {
              });
            });
        });
        </script>
        ";
    }

    function p($var, $exit = FALSE)
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        if ($exit) {
            exit;
        }
    }

    function recurse_array($values)
    {
        $content = '';
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    $content.=$key . "<br />" . recurse_array($value);
                } else {
                    $content.= $key . " = " . $value . "<br />";
                }
            }
        }
        return $content;
    }

    function arrDays($opt = "")
    {
        if ($opt == "") {
            $arrDays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        } elseif ($opt == "id") {
            $arrDays = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        }
        return $arrDays;
    }

    function arrMonths($opt = "")
    {
        if ($opt == "") {
            $arrMonths = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        } else if ($opt == "id") {
            $arrMonths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        }
        return $arrMonths;
    }

    function get_date_id($date)
    {
        $value = array("date" => "", "month" => "", "year" => "");
        if ($date != "") {
            $monthId = $this->arrMonths("id");
            $value['date'] = (substr($date, 8, 2));
            $value['month'] = $monthId[intval(substr($date, 5, 2)) - 1];
            $value['year'] = substr($date, 0, 4);
        }
        return $value;
    }

    function arrWords($text = "")
    {
        $text = generate_alias($text);
        $arrText = explode("-", strtolower($text));
        $count = count($arrText);
        $value = array();
        $key = 0;
        $first = 0;

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $len = $count - $i;
                $next = 1;
                $start = 0;
                while ($next == 1) {
                    $value[$key] = "";
                    for ($j = 0; $j < $len; $j++) {
                        $value[$key] .= $arrText[$start] . " ";
                        $start++;
                    }
                    $value[$key] = trim($value[$key]);
                    $last = $arrText[$len - 1];
                    if ($start == $count) {
                        $next = 0;
                    }
                    $key++;
                    $first = $first + 1;
                    $start = $first;
                }
                $first = 0;
                $start = 0;
            }
        }
        return $value;
    }

    function get_mime_type($file)
    {
        $mime_types = array(
            "pdf" => "application/pdf"
        , "exe" => "application/octet-stream"
        , "zip" => "application/zip"
        , "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        , "doc" => "application/msword"
        , "xls" => "application/vnd.ms-excel"
        , "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        , "ppt" => "application/vnd.ms-powerpoint"
        , "gif" => "image/gif"
        , "png" => "image/png"
        , "jpeg" => "image/jpg"
        , "jpg" => "image/jpg"
        , "mp3" => "audio/mpeg"
        , "wav" => "audio/x-wav"
        , "mpeg" => "video/mpeg"
        , "mpg" => "video/mpeg"
        , "mpe" => "video/mpeg"
        , "mov" => "video/quicktime"
        , "avi" => "video/x-msvideo"
        , "3gp" => "video/3gpp"
        , "css" => "text/css"
        , "jsc" => "application/javascript"
        , "js" => "application/javascript"
        , "php" => "text/html"
        , "htm" => "text/html"
        , "html" => "text/html"
        );
        $extension = strtolower(end(explode('.', $file)));
        $mimeType = $mime_types[$extension];
        if ($extension == "doc" || $extension == "docx") {
            $icon = "data-word.png";
        } elseif ($extension == "xls" || $extension == "xlsx") {
            $icon = "data-excel.png";
        } elseif ($extension == "pdf") {
            $icon = "data-pdf.png";
        }
        //return $mime_types[$extension];
        return $icon;
    }

    function gotoScroll($id)
    {
        echo '<script>
            $(document).ready(function(){
                $("html,body").animate({scrollTop: $("#' . $id . '").offset().top},"slow");
            });
        </script>';
    }

    function isValidMethodPost($param)
    {
        $valid = FALSE;
        if (isset($_POST[$param]) && $_POST[$param] != "" && $_SERVER['REQUEST_METHOD'] == "POST") {
            $valid = TRUE;
        }
        return $valid;
    }

    function sanitize($data)
    {
        return str_replace("+", " ", $data);
    }

    function securePostSearch($param)
    {
        if (isset($_POST[$param])) {
            $getPost = $_POST[$param] != "" ? trim($_POST[$param]) : $_POST[$param];
            $getPost = str_replace(" ", "+", $getPost);
            return htmlspecialchars($getPost, ENT_QUOTES, "UTF-8");
        } else {
            return false;
        }
    }

    function unlink_image($param)
    {
        if (is_array($param) && count($param) > 0) {
            foreach ($param as $img) {
                if (file_exists(UPLOADS_PATH . "/" . $img)) {
                    unlink(UPLOADS_PATH . "/" . $img);
                }
            }
        } else {
            if (file_exists(UPLOADS_PATH . "/" . $img)) {
                unlink(UPLOADS_PATH . "/" . $img);
            }
        }
    }

//HTML TAG HELPER

    /**
     * Image HTML Tag
     * Generates an <img /> element
     */
    function img_tag($src, $attr = array(), $thumb = FALSE)
    {
        $img = '<img';

        $dataStr = explode('|', $src);
        $src = $dataStr[0];
        //THUMB or DEFAULT
        $path = IMAGES_HOST;
        if ($thumb) {
            $path = IMAGES_THUMB;
        }

        //Is image exists?
//    return IMAGES_PATH . "/" . $src;exit;
        if (!file_exists(IMAGES_PATH . "/" . $src)) {
            $src = 'no_image.jpg';
            $dataStr[1] = 'No Image';
        }

        //images
        $img .= ' src="' . $path . '/' . $src . '"';

        //is alt attr exists?
        if (isset($dataStr[1])) {
            $img .= ' alt="' . $dataStr[1] . '"';
        }

        //is another attr exists?
        if (is_array($attr) && count($attr) > 0) {
            foreach ($attr as $key => $value) {
                $img .= ' ' . $key . '="' . $value . '"';
            }
        }

        $img .= '/>';

        return $img;
    }

    /**
     * Comment
     */
    function echo_session(&$data)
    {
        if (isset($data)) {
            echo $data;
        }
    }

    function array_short($a, $b){
        return $a['menu_order'] - $b['menu_order'];
    }

    function arrayToObject($d)
    {
        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return (object) array_map(__FUNCTION__, $d);
        } else {
            // Return object
            return $d;
        }
    }

    function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }

    /**
     * get image tag
     */
    function get_img($data)
    {

        $doc = new DOMDocument();
        @$doc->loadHTML($data);

        $tags = $doc->getElementsByTagName('img')->item(0);
        if ($tags) {
            return $tags;
        }
        return FALSE;
    }

    function lang($data = NULL)
    {
        $setting = Setting::get_instance();
        if (is_string($data)) {
            $result = search_array($setting->language, 'lang_key', $data);
            if (isset($result) && count($result) > 0) {
//            $resultq = array_map('reset', $result);
                return $result['text_' . $_SESSION['language']];
            } else {
                return $data . '_' . $_SESSION['language'];
            }
        }
        return 'Key ' . $data . ' is not defined.';
//    if (is_array($data)) {
//        foreach ($array as $key => $value) {
//            return $data[$key . '_' . $_SESSION['language']];
//        }
//        $key = array_keys($data);
//    }
    }

// search array for specific key = value
    function search_array($array, $key, $value)
    {
        $return = array();
        foreach ($array as $k => $subarray) {
            if (isset($subarray[$key]) && $subarray[$key] == $value) {
//      $return[$k] = $subarray; //RESULT WITH INDEX ASSOCIATED
                $return = $subarray;   //RESULT WITHOUT INDEX ASSOCIATED
                return $return;
            }
        }
    }

    /**
     * Parse HTML Element
     */
    function parse_html($html = '', $tag = '', $attr = '', $type = 'string')
    {
        $dom = new DOMDocument();
        if ($type != 'string') {
            $html = file_get_contents($html);
        }
        @$dom->loadHTML($html);

        $a = $dom->getElementsByTagName($tag);
        return $a->item(0)->getAttribute($attr);
    }

    /**
     * parse_youtube_url
     */
    function parse_youtube_url($url, $return = '', $width = '', $height = '', $rel = 0)
    {
        $urls = parse_url($url);

        //url is http://youtu.be/xxxx
        if ($urls['host'] == 'youtu.be') {
            $id = ltrim($urls['path'], '/');
        }
        //url is http://www.youtube.com/embed/xxxx
        elseif (strpos($urls['path'], 'embed') !== FALSE) {
            $dt = explode('/', $urls['path']);
            $id = end($dt);
        }
        //url is xxxx only
        else if (strpos($url, '/') === false) {
            $id = $url;
        }
        //http://www.youtube.com/watch?feature=player_embedded&v=m-t4pcO99gI
        //url is http://www.youtube.com/watch?v=xxxx
        else {
            parse_str($urls['query']);
            if (!empty($feature)) {
                $id = end(explode('v=', $urls['query']));
            }
        }
        //return embed iframe
        if ($return == 'embed') {
            return '</pre><iframe src="http://www.youtube.com/embed/' . $id . '?rel=' . $rel . '" frameborder="0" width="' . ($width ? $width : 560) . '" height="' . ($height ? $height : 349) . '"></iframe><pre>';
        }
        //return normal thumb
        else if ($return == 'thumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/default.jpg';
        }
        //return hqthumb
        else if ($return == 'hqthumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        }
        // else return id
        else {
            return $id;
        }
    }

    function hi(){
        $jam = intval(date('H'));
        if($jam>=5 && $jam<=11){ $hi = "Pagi";}
        elseif($jam>11 && $jam<=15){ $hi = "Siang";}
        elseif($jam>15 && $jam<=19){ $hi = "Sore";}
        else{$hi="Malam";}
        return $hi;
    }

    function show_notif($var=array()){
        $notif="";
        if(isset($var)){
            if($var['status']=="0"){
                $notif='
			<div class="alert alert-block alert-danger fade in">
				<button type="button" class="close close-sm" data-dismiss="alert">
					<i class="fa fa-times"></i>
				</button>
				<i class="fa fa-warning"></i> '.$var['text'].'
			</div>';
            }
            if($var['status']=="1"){
                $notif='
			<div class="alert alert-block alert-success fade in">
				<button type="button" class="close close-sm" data-dismiss="alert">
					<i class="fa fa-times"></i>
				</button>
				<i class="fa fa-check"></i> '.$var['text'].'
			</div>';
            }
        }
        return $notif;
    }

    function show_error_msg($error){
        if(isset($error)){
            echo '<div style="margin-top:5px;"><code>'.$error.'</code></div>';
        }
    }


    function date_indo($data,$format=""){
        if(substr($data,0,10)=="0000-00-00"){
            $newDate = "-";
        }
        else{
            $arrMonth = $this->arrMonths("id");
            $day = substr($data,8,2);
            $month = $arrMonth[substr($data,5,2)-1];
            $year = substr($data,0,4);
            $hour = substr($data,11,2);
            $minute= substr($data,14,2);
            $second = substr($data,17,2);

            $newDate = $day." ".substr($month,0,3)." ".$year;
            if($format=="dd FF YYYY"){
                $newDate = $day." ".$month." ".$year;
            }
            elseif($format=="datetime"){
                $newDate = $day." ".substr($month,0,3)." ".$year." ".$hour.":".$minute;
            }
            elseif($format=="dd-mm-YYYY"){
                $newDate = $day."-".substr($data,5,2)."-".$year;
            }
        }
        return $newDate;
    }

    function color_progress($var){
        if($var<=35){ $bg = " background:#dd514c;"; }
        elseif($var>35 && $var <=70){ $bg = " background:#faa732;"; }
        else{ $bg = " background:#4bb1cf;";}
        return $bg;
    }

    function get_size($size){
        if($size>1000000){
            $sizeNew = round(($size/1000000),2)." MB";
        }
        else{
            $sizeNew = round(($size/1000),2)." KB";
        }
        return $sizeNew;
    }

    function get_size_number($size){
        if($size>1000000000){
            $sizeNew = round(($size/1000000000),1)." B";
        }
        elseif($size>1000000){
            $sizeNew = round(($size/1000000),1)." M";
        }
        elseif($size>1000){
            $sizeNew = round(($size/1000),1)." K";
        }
        else{
            $sizeNew = $size;
        }
        return $sizeNew;
    }


    function save_xml_file($filename="", $xml=""){
        if($filename == "")
            $filename = $filename;
        if($xml == "")
            $xml = $xml;

        $fp = @fopen($filename, "w");
        if($fp) {
            @fputs($fp,$xml);
            $result = true;
        }
        else 	$result = false;
        @fclose($fp);

        return $result;
    }


    function save_json_file($filename="", $json=""){

        $fp = @fopen($filename, "w");
        if($fp) {
            @fputs($fp,$json);
            $result = true;
        }
        else 	$result = false;
        @fclose($fp);

        return $result;
    }



    function check_gateway($ip=""){
        $connected = @fsockopen($ip, 80, $errno, $errstr, 3); //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = 1; //action when connected
            fclose($connected);
        }else{
            $is_conn = 0; //action in connection failure
        }
        return $is_conn;
    }

    function resize_image($imageFile,$width,$height,$name){
        list($w, $h) = getimagesize($_FILES[$imageFile]['tmp_name']);

        $ratio = max($width/$w, $height/$h);
        $h = ceil($height / $ratio);
        $x = ($w - $width / $ratio) / 2;
        $w = ceil($width / $ratio);

        $path = MEDIA_IMAGE_PATH."/".$name;
        $imgString = file_get_contents($_FILES[$imageFile]['tmp_name']);

        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);

        switch ($_FILES[$imageFile]['type']) {
            case 'image/jpeg':
                imagejpeg($tmp, $path, 100);
                break;
            case 'image/png':
                imagepng($tmp, $path, 0);
                break;
            case 'image/gif':
                imagegif($tmp, $path);
                break;
            default:
                exit;
                break;
        }
        return $path;

        imagedestroy($image);
        imagedestroy($tmp);
    }

    function get_icon_section($section=""){
        $icon="";
        switch ($section) {
            case "" :
                $icon = '<i class="fa fa-home"></i>';
                break;
            case "customer" :
                $icon = '<i class="fa fa-users"></i>';
                break;
            case "user" :
                $icon = '<i class="fa fa-user"></i>';
                break;
            case "setting" :
                $icon = '<i class="fa fa-cogs"></i>';
                break;

            case "product" :
                $icon = '<i class="fa fa-th"></i>';
                break;

            case "contact" :
                $icon = '<i class="fa fa-envelope"></i>';
                break;
            case "pages" :
                $icon = '<i class="fa fa-file"></i>';
                break;
            case "report" :
                $icon = '<i class="fa fa-bar-chart-o"></i>';
                break;
            case "notification" :
                $icon = '<i class="fa fa-bullhorn"></i>';
                break;
            default:
                $icon = '<i class="fa fa-file"></i>';
                break;
        }
        return $icon;
    }

    function get_age($date1,$date2){
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        return "<strong>".$years."</strong> Tahun, <strong>".$months."</strong> Bulan";

    }

    function Terbilang($x)
    {
        $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($x < 12)
            return " " . $abil[$x];
        elseif ($x < 20)
            return Terbilang($x - 10) . " belas";
        elseif ($x < 100)
            return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
        elseif ($x < 200)
            return " seratus" . Terbilang($x - 100);
        elseif ($x < 1000)
            return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
        elseif ($x < 2000)
            return " seribu" . Terbilang($x - 1000);
        elseif ($x < 1000000)
            return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
        elseif ($x < 1000000000)
            return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
    }


    function trx_status($opt="",$status=""){
        $label = $status;
        switch($opt){
            case "transaction":
                if($status=="success") $label = "Sukses";
                if($status=="pending") $label = "Sedang Diproses";
                if($status=="failed") $label = "Gagal";
                break;

            case "deposit":
                if($status=="new") $label = "Menunggu Pembayaran";
                if($status=="pending") $label = "Sedang diproses";
                if($status=="success") $label = "Sukses";
                if($status=="cancel") $label = "Dibatalkan";
                if($status=="failed") $label = "Gagal";
                break;

            default:
                $label = $status;
                break;
        }
        return $label;
    }

    function class_form_error($field){
        global $error;
        if(isset($error[$field])){
            echo 'has-error';
        }
    }

    function label_error_form($field){
        global $error;
        if(isset($error[$field])){
            echo '<small class="text-danger">'.$error[$field].'</small>';
        }
    }

    function time_format($time){
        $y = date('Y',strtotime($time));
        $m = date('m',strtotime($time));
        $d = date('d',strtotime($time));
        $h = date('H',strtotime($time));
        $i = date('i',strtotime($time));
        $s = date('s',strtotime($time));

        if($y==date('Y')){
            if($m==date('m') && $d==date('d')){
                $result = date('g:i a',strtotime($time));
            }
            else{
                $result = date('d M',strtotime($time));
            }
        }
        else{
            $result = date('d M y',strtotime($time));
        }
        return $result;
    }


    function reverse_alias($alias){
        $repl = str_replace("-"," ",$alias);
        $words = ucwords($repl);
        return $words;
    }

    function Romawi($n){
        $hasil = "";
        $iromawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X",20=>"XX",30=>"XXX",40=>"XL",50=>"L",
            60=>"LX",70=>"LXX",80=>"LXXX",90=>"XC",100=>"C",200=>"CC",300=>"CCC",400=>"CD",500=>"D",
            600=>"DC",700=>"DCC",800=>"DCCC",900=>"CM",1000=>"M",2000=>"MM",3000=>"MMM");
        if(array_key_exists($n,$iromawi)){
            $hasil = $iromawi[$n];
        }elseif($n >= 11 && $n <= 99){
            $i = $n % 10;
            $hasil = $iromawi[$n-$i] . Romawi($n % 10);
        }elseif($n >= 101 && $n <= 999){
            $i = $n % 100;
            $hasil = $iromawi[$n-$i] . Romawi($n % 100);
        }else{
            $i = $n % 1000;
            $hasil = $iromawi[$n-$i] . Romawi($n % 1000);
        }
        return $hasil;
    }



    function waktu_lalu($timestamp)
    {
        $selisih = time() - strtotime($timestamp) ;

        $detik = $selisih ;
        $menit = round($selisih / 60 );
        $jam = round($selisih / 3600 );
        $hari = round($selisih / 86400 );
        $minggu = round($selisih / 604800 );
        $bulan = round($selisih / 2419200 );
        $tahun = round($selisih / 29030400 );

        if($detik ==0){
            $waktu = 'baru saja';
        }
        elseif ($detik <= 60) {
            $waktu = $detik.' detik lalu';
        } elseif ($menit <= 60) {
            $waktu = $menit.' menit lalu';
        } elseif ($jam <= 24) {
            $waktu = $jam.' jam lalu';
        } elseif ($hari <= 7) {
            $waktu = $hari.' hari lalu';
        } else{
            $waktu = $this->date_indo($timestamp);
        }

        return $waktu;
    }

    function date_range($startDate, $endDate, $format = "Y-m-d"){
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day');

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }

        return $range;
    }

    function stepcr($step){
        if(!isset($_SESSION['Training'] ['Status']))  $_SESSION['Training'] ['Status']="";
        if($step==4){
            if($_SESSION['Training'] ['Status']=="passed"){
                $btn = 'class="btn btn-success btn-block" disabled';
            }
            elseif($_SESSION['Training'] ['Status']=="failed"){
                $btn = 'class="btn btn-danger btn-block" disabled';
            }
            elseif($step==$_SESSION['StepCR']){
                $btn = 'class="btn btn-primary btn-block"';
            }
            else{
                $btn = 'class="btn btn-inverse btn-block" disabled';
            }
        }
        else{
            if($step<$_SESSION['StepCR']){
                $btn = 'class="btn btn-success btn-block"';
            }
            elseif($step==$_SESSION['StepCR']){
                $btn = 'class="btn btn-primary btn-block"';
            }
            elseif($_SESSION['StepCR']==4){
                $btn = 'class="btn btn-primary btn-block" ';
            }
            else{
                $btn = 'class="btn btn-inverse btn-block" disabled';
            }
        }


        return $btn;
    }

    function stepcricon($step){
        if(!isset($_SESSION['Training'] ['Status']))  $_SESSION['Training'] ['Status']="";
        if($step==4){
            if($_SESSION['Training'] ['Status']=="passed"){
                $icon = '<i class="fa fa-check"></i>';
            }
            elseif($_SESSION['Training'] ['Status']=="failed"){
                $icon = '<i class="fa fa-minus"></i>';
            }
            elseif($_SESSION['StepCR']==4){
                $icon = '<i class="fa fa-hourglass-2"></i>';
            }
            else{
                $icon = '<i class="fa fa-minus"></i>';
            }
        }
        else{
            if($step<$_SESSION['StepCR']){
                $icon = '<i class="fa fa-check"></i>';
            }
            elseif($step==$_SESSION['StepCR']){
                $icon = '<i class="fa fa-hourglass-2"></i>';
            }
            else{
                $icon = '<i class="fa fa-minus"></i>';
            }
        }
        return $icon;
    }

    function slugify($string, $replace = array(), $delimiter = '-') {
        // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
        if (!extension_loaded('iconv')) {
            throw new Exception('iconv module not loaded');
        }
        // Save the old locale and set the new locale to UTF-8
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        if (!empty($replace)) {
            $clean = str_replace((array) $replace, ' ', $clean);
        }
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        // Revert back to the old locale
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }

    function convert_datetime($datetime, $format='Y-m-d'){
        $str_date = strtotime($datetime);
        $new_format = date($format, $str_date);
        return $new_format;
    }

    function convertToHierarchy($results, $idField='id', $parentIdField='parent', $childrenField='menu') {
        $hierarchy = array(); // -- Stores the final data

        $itemReferences = array(); // -- temporary array, storing references to all items in a single-dimention

        foreach ( $results as $item ) {
            $id       = $item[$idField];
            $parentId = $item[$parentIdField];

            if (isset($itemReferences[$parentId])) { // parent exists
                $itemReferences[$parentId][$childrenField][$id] = $item; // assign item to parent
                $itemReferences[$id] =& $itemReferences[$parentId][$childrenField][$id]; // reference parent's item in single-dimentional array
            } elseif (!$parentId || !isset($hierarchy[$parentId])) { // -- parent Id empty or does not exist. Add it to the root
                $hierarchy[$id] = $item;
                $itemReferences[$id] =& $hierarchy[$id];
            }
        }

        unset($results, $item, $id, $parentId);

        // -- Run through the root one more time. If any child got added before it's parent, fix it.
        foreach ( $hierarchy as $id => &$item ) {
            $parentId = $item[$parentIdField];

            if ( isset($itemReferences[$parentId] ) ) { // -- parent DOES exist
                $itemReferences[$parentId][$childrenField][$id] = $item; // -- assign it to the parent's list of children
                unset($hierarchy[$id]); // -- remove it from the root of the hierarchy
            }
        }

        unset($itemReferences, $id, $item, $parentId);

        return $hierarchy;
    }
}