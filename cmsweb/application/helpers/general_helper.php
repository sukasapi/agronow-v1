<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



function mime_to_ext($mime){
  $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
  $all_mimes = json_decode($all_mimes,true);
  foreach ($all_mimes as $key => $value) {
    if(array_search($mime,$value) !== false) return $key;
  }
  return false;
}

function arrayMonth(){
    $month = array(
        1   => 'Januari',
        2   => 'Februari',
        3   => 'Maret',
        4   => 'April',
        5   => 'Mei',
        6   => 'Juni',
        7   => 'Juli',
        8   => 'Agustus',
        9   => 'September',
        10   => 'Oktober',
        11   => 'November',
        12   => 'Desember',
    );
    return $month;
}


function formatFileSize($size)
{
	$units = array(' KB', ' MB', ' GB', ' TB', ' PB');
	for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
	return round($size, 2).$units[$i];
}

function formatCurrency($n, $precision = 3) {
    if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n);
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . ' jt';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . ' m';
    }

    return $n_format;
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



function flash_notif_success($message=NULL,$url_return,$redirect=TRUE){
	$CI = & get_instance();

	$flash_data['flash_msg']        = TRUE;
    $flash_data['flash_msg_type']   = "success";
    $flash_data['flash_msg_status'] = TRUE;
    if ($message==NULL){
        $flash_data['flash_msg_text']   = "Data Berhasil Disimpan";
    }else{
        $flash_data['flash_msg_text']   = $message;
    }

    $CI->session->set_flashdata($flash_data);

    if ($redirect==TRUE) {
    	redirect($url_return,'REFRESH');
    }
    
}

function flash_notif_failed($message=NULL,$url_return,$redirect=TRUE){
	$CI = & get_instance();

	$flash_data['flash_msg']      = TRUE;
    $flash_data['flash_msg_type'] = "danger";
    $flash_data['flash_msg_status'] = FALSE;
    if ($message==NULL){
        $flash_data['flash_msg_text']   = "Data Gagal Disimpan";
    }else{
        $flash_data['flash_msg_text']   = $message;
    }
    $CI->session->set_flashdata($flash_data);
    
    if ($redirect==TRUE) {
    	redirect($url_return,'REFRESH');
    }
}


function flash_notif_warning($message,$url_return,$redirect=TRUE){
    $CI = & get_instance();

    $flash_data['flash_msg']      = TRUE;
    $flash_data['flash_msg_type'] = "warning";
    $flash_data['flash_msg_status'] = FALSE;
    $flash_data['flash_msg_text'] = $message;
    $CI->session->set_flashdata($flash_data);

    if ($redirect==TRUE) {
        redirect($url_return,'REFRESH');
    }
}


function parseConfigReadable($config_id=NULL){
    $data = array(
        ""  => '-',
        '0'  => 'Tidak Bayar',
        '1'  => 'Perusahaan',
        '2'  => 'Pegawai',
    );

    if (isset($data[$config_id])){
        return $data[$config_id];
    }else{
        return FALSE;
    }

}

function parseDateRange($daterange){
    $a = explode('-',$daterange);

    $start = explode('/',$a[0]);
    $start = trim($start[2]).'-'.$start[1].'-'.$start[0];

    $end = explode('/',$a[1]);
    $end = trim($end[2]).'-'.$end[1].'-'.trim($end[0]);

    return array(
        'start' => $start,
        'end' => $end,
    );
}

function parseDate($date){
    if (!$date){
        return NuLL;
    }else{
        $new_date = $date;
        $new_date = str_replace('/', '-', $new_date);
        $new_date = date('Y-m-d', strtotime($new_date));
        return $new_date;
    }

}

function parseDateTime($date){
    if (!$date){
        return NuLL;
    }else{
        $new_date = explode(' ',$date);
        $new_date = explode('/',$new_date[0]);
        $new_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];

        $new_time = explode(' ',$date);
        $new_time = $new_time[1];

        $datetime = $new_date.' '.$new_time;
        $parse_datetime = date('Y-m-d H:i:s', strtotime($datetime));
        return $parse_datetime;
    }

}

function parseDateReadable($date){
    if (!$date){
        return NULL;
    }else{
        setlocale(LC_TIME, 'id_ID');
        return strftime("%d %B %Y",strtotime($date));
        date('d F Y', strtotime($date));
    }
}

function parseDateShortReadable($date){
    if (!$date){
        return NULL;
    }else{
        setlocale(LC_TIME, 'id_ID');
        return strftime("%d %b %Y",strtotime($date));
        date('d F Y', strtotime($date));
    }
}

function parseTimeReadable($time){
    if (!$time){
        return NULL;
    }else{
        return date('H:i', strtotime($time));
    }
}

function parseRupiah($var_number){
    if ($var_number==NULL){
        return "";
    }else{
        return "Rp ".number_format($var_number,0,',','.');
    }

}

function parseThousand($var_number){
    if ($var_number==NULL){
        return "";
    }else{
        return number_format($var_number,0,',','.');
    }

}

function parsePercent($var_number){
    if ($var_number==NULL){
        return "-";
    }else{
        return number_format($var_number,0,',','.')." %";
    }

}



function parseInputNull($input){
    if (isset($input)){
        $result = $input?$input:NULL;
    }else{
        $result = NULL;
    }
    return $result;
}

function generateOptYear($earliest_year=NULL,$latest_year=NULL,$default_null = NULL){
    if ($earliest_year==NULL){
        $earliest_year = 1980;
    }

    if ($latest_year==NULL){
        $latest_year = date('Y');
    }

    if ($default_null==NULL){
        $default_null = "Semua";
    }
    $year = array(''=>$default_null);
    foreach ( range( $latest_year, $earliest_year ) as $i ) {
        $year[$i]=$i;
    }
    return $year;
}


function formatFilenameSystem($filename_origin){

    $digits = 2;
    $random_number = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

    $filename_system  = preg_replace('/\s+/', '', $random_number.'_'.$filename_origin);

    $ext_pos = strrpos($filename_system, '.');
    if ($ext_pos){
        $ext = substr($filename_system, $ext_pos);
        $filename_system = substr($filename_system, 0, $ext_pos);
        $filename_system = preg_replace('/[^A-Za-z0-9\-]/', '', $filename_system);
        $filename_system = str_replace('.', '_', $filename_system).$ext;
    }

    return $filename_system;
}

function hitungNilaiEvaluasiLevel3($nilai_atasan,$bobot_atasan,$nilai_kolega,$bobot_kolega) {
	$nilai = (($nilai_atasan*$bobot_atasan) + ($nilai_kolega*$bobot_kolega))/($bobot_atasan+$bobot_kolega);
	return $nilai;
}

function nilaiEvaluasiLv3_Profil($nilai,$kategori,$output) {
	$arr = array();
	
	if($kategori=="k") {
		$arr[1]['label'] = 'Sangat Buruk';
		$arr[1]['desc'] = 'Karyawan tidak memiliki/sangat kurang memiliki pengetahuan';
		$arr[1]['nilai_min'] = 0.000;
		$arr[1]['nilai_max'] = 40.999;
		
		$arr[2]['label'] = 'Buruk';
		$arr[2]['desc'] = 'Karyawan menguasai sebagian dari seluruh pengetahuan yang dipersyaratkan';
		$arr[2]['nilai_min'] = 41.000;
		$arr[2]['nilai_max'] = 60.999;
		
		$arr[3]['label'] = 'Membutuhkan perbaikan';
		$arr[3]['desc'] = 'Karyawan membutuhkan tambahan latihan untuk meningkatkan penguasaan pengetahuan';
		$arr[3]['nilai_min'] = 61.000;
		$arr[3]['nilai_max'] = 75.999;
		
		$arr[4]['label'] = 'Baik';
		$arr[4]['desc'] = 'Karyawan menguasai pengetahuan, namun membutuhkan supervisi dan pendampingan untuk dapat mengaplikasikan pengetahuan dengan efektif';
		$arr[4]['nilai_min'] = 76.000;
		$arr[4]['nilai_max'] = 85.999;
		
		$arr[5]['label'] = 'Sangat Baik';
		$arr[5]['desc'] = 'Karyawan menguasai pengetahuan, mengaplikasikan dengan mahir, mampu mengajarkan kembali';
		$arr[5]['nilai_min'] = 86.000;
		$arr[5]['nilai_max'] = 100.00;
	} else if($kategori=="s") {
		$arr[1]['label'] = 'Sangat Buruk';
		$arr[1]['desc'] = 'Hasil pelatihan tidak tercermin dalam peningkatan atau penambahan ketrampilan karyawan';
		$arr[1]['nilai_min'] = 0.000;
		$arr[1]['nilai_max'] = 50.999;
		
		$arr[2]['label'] = 'Buruk';
		$arr[2]['desc'] = 'Karyawan mencoba menerapkan hasil pelatihan, namun belum efektif';
		$arr[2]['nilai_min'] = 51.000;
		$arr[2]['nilai_max'] = 70.999;
		
		$arr[3]['label'] = 'Membutuhkan perbaikan';
		$arr[3]['desc'] = 'Karyawan mampu mengapliksikan ketrampilan dengan efektif melaui bimbingan dan pendampingan';
		$arr[3]['nilai_min'] = 71.000;
		$arr[3]['nilai_max'] = 80.999;
		
		$arr[4]['label'] = 'Baik';
		$arr[4]['desc'] = 'Karyawan menggunakan ketrampilan yang didapatkan untuk memecahkan permasalahan secara mandiri dan memberikian bimbingan ketrampilan kepada orang lain';
		$arr[4]['nilai_min'] = 81.000;
		$arr[4]['nilai_max'] = 90.999;
		
		$arr[5]['label'] = 'Sangat Baik';
		$arr[5]['desc'] = 'Karyawan mengembangkan inovasi/pembaharuan melalui adaptasi dan pengembagan ketrampilan yang didapatkan dari pembelajaran yang diikuti';
		$arr[5]['nilai_min'] = 91.000;
		$arr[5]['nilai_max'] = 100.00;
	} else if($kategori=="a") {
		$arr[1]['label'] = 'n/a';
		$arr[1]['desc'] = 'n/a';
		$arr[1]['nilai_min'] = -1;
		$arr[1]['nilai_max'] = -1;
		
		$arr[2]['label'] = 'Sangat Buruk';
		$arr[2]['desc'] = 'Karyawan berpandangan bahwa pembelajaran/pengembangan diri bukanlah suatu hal yang bermanfaat, tidak memiliki keinginan mempelajari atau mendalami suatu bidang pengetahuan.';
		$arr[2]['nilai_min'] = 0.000;
		$arr[2]['nilai_max'] = 40.999;
		
		$arr[3]['label'] = 'Buruk';
		$arr[3]['desc'] = 'Karyawan melakukan pembelajaran dan menerapkan hasil pembelajaran dengan baik hanya ketika berada dibawah pengawasan dan bimbingan';
		$arr[3]['nilai_min'] = 41.000;
		$arr[3]['nilai_max'] = 60.999;
		
		$arr[4]['label'] = 'Baik';
		$arr[4]['desc'] = 'Karyawan bersikap konsisten, memiliki semangat dan kesadaran untuk menerapkan hasil pembelajaran serta meyakini bahwa hasil pembelajaran akan dapat memberikan manfaat yang bersar bagi dirinya dan perusahaan ';
		$arr[4]['nilai_min'] = 61.000;
		$arr[4]['nilai_max'] = 80.999;
		
		$arr[5]['label'] = 'Sangat Baik';
		$arr[5]['desc'] = 'Karyawan mendalami dan mengembangkan hasil pembelajaran yang diperoleh, untuk dapat meningkatkan manfaat yang lebih besar dalam penerapan hasil pembelajaran';
		$arr[5]['nilai_min'] = 81.000;
		$arr[5]['nilai_max'] = 100.00;
	} else if($kategori=="b") {
		$arr[1]['label'] = 'Sangat Buruk';
		$arr[1]['desc'] = 'Karyawan tidak menunjukan sasaran perubahan perilaku pasca pelatihan';
		$arr[1]['nilai_min'] = 0.000;
		$arr[1]['nilai_max'] = 50.999;
		
		$arr[2]['label'] = 'Buruk';
		$arr[2]['desc'] = 'Karyawan menujukan sasaran perubahan perilaku pasca pelatihan saat diinstruksikan/diingatkan';
		$arr[2]['nilai_min'] = 51.000;
		$arr[2]['nilai_max'] = 70.999;
		
		$arr[3]['label'] = 'Membutuhkan perbaikan';
		$arr[3]['desc'] = 'Karyawan menujukan perilaku minimal, pada situasi yang membutuhkan perilaku; atau masih memerlukan bimibingan/bantuan untuk menunjukan perilaku';
		$arr[3]['nilai_min'] = 71.000;
		$arr[3]['nilai_max'] = 80.999;
		
		$arr[4]['label'] = 'Baik';
		$arr[4]['desc'] = 'Karyawan menunjukan sasaran perubahan perilaku pasca pelatihan dengan konsisten dan mandiri';
		$arr[4]['nilai_min'] = 81.000;
		$arr[4]['nilai_max'] = 90.999;
		
		$arr[5]['label'] = 'Sangat Baik';
		$arr[5]['desc'] = 'Karyawan menunjukan sasaran perubahan perilaku pasca pelatihan dengan konsisten dan penuh komitmen bahkan pada kondisi sesulit apapun';
		$arr[5]['nilai_min'] = 91.000;
		$arr[5]['nilai_max'] = 100.00;
	} else if($kategori=="na") {
		$arr[1]['label'] = 'Sangat Buruk';
		$arr[1]['desc'] = 'Sangat Buruk';
		$arr[1]['nilai_min'] = 0.000;
		$arr[1]['nilai_max'] = 50.999;
		
		$arr[2]['label'] = 'Buruk';
		$arr[2]['desc'] = 'Buruk';
		$arr[2]['nilai_min'] = 51.000;
		$arr[2]['nilai_max'] = 70.999;
		
		$arr[3]['label'] = 'Membutuhkan perbaikan';
		$arr[3]['desc'] = 'Membutuhkan perbaikan';
		$arr[3]['nilai_min'] = 71.000;
		$arr[3]['nilai_max'] = 80.999;
		
		$arr[4]['label'] = 'Baik';
		$arr[4]['desc'] = 'Baik';
		$arr[4]['nilai_min'] = 81.000;
		$arr[4]['nilai_max'] = 90.999;
		
		$arr[5]['label'] = 'Sangat Baik';
		$arr[5]['desc'] = 'Sangat Baik';
		$arr[5]['nilai_min'] = 91.000;
		$arr[5]['nilai_max'] = 100.00;
	} else if($kategori=="k_gap") {
		$arr[1]['label'] = 'Sangat Rendah';
		$arr[1]['desc'] = 'Sangat Rendah';
		$arr[1]['nilai_min'] = -100.000;
		$arr[1]['nilai_max'] = 7.999;
		
		$arr[2]['label'] = 'Rendah';
		$arr[2]['desc'] = 'Rendah';
		$arr[2]['nilai_min'] = 8.000;
		$arr[2]['nilai_max'] = 15.999;
		
		$arr[3]['label'] = 'Cukup';
		$arr[3]['desc'] = 'Cukup';
		$arr[3]['nilai_min'] = 16.000;
		$arr[3]['nilai_max'] = 30.999;
		
		$arr[4]['label'] = 'Tinggi';
		$arr[4]['desc'] = 'Tinggi';
		$arr[4]['nilai_min'] = 31.000;
		$arr[4]['nilai_max'] = 60.999;
		
		$arr[5]['label'] = 'Sangat Tinggi';
		$arr[5]['desc'] = 'Sangat Tinggi';
		$arr[5]['nilai_min'] = 61.000;
		$arr[5]['nilai_max'] = 100.00;
	}
	
	$arr[1]['color'] = "#000000"; $arr[1]['color_txt'] = "#FFFFFF";
	$arr[2]['color'] = "#E63928"; $arr[2]['color_txt'] = "#FFFFFF";
	$arr[3]['color'] = "#FFC000"; $arr[3]['color_txt'] = "#000000";
	$arr[4]['color'] = "#2CB34C"; $arr[4]['color_txt'] = "#FFFFFF";
	$arr[5]['color'] = "#0071BC"; $arr[5]['color_txt'] = "#FFFFFF";
	
	if($output=="master") {
		if($arr[1]['nilai_min']!=-1) $add_label = ' ('.$arr[1]['nilai_min'].' sd '.$arr[1]['nilai_max'].')';
		$arr[1]['label_range'] = $arr[1]['label'].$add_label;
		if($arr[2]['nilai_min']!=-1) $add_label = ' ('.$arr[2]['nilai_min'].' sd '.$arr[2]['nilai_max'].')';
		$arr[2]['label_range'] = $arr[2]['label'].$add_label;
		if($arr[3]['nilai_min']!=-1) $add_label = ' ('.$arr[3]['nilai_min'].' sd '.$arr[3]['nilai_max'].')';
		$arr[3]['label_range'] = $arr[3]['label'].$add_label;
		if($arr[4]['nilai_min']!=-1) $add_label = ' ('.$arr[4]['nilai_min'].' sd '.$arr[4]['nilai_max'].')';
		$arr[4]['label_range'] = $arr[4]['label'].$add_label;
		if($arr[5]['nilai_min']!=-1) $add_label = ' ('.$arr[5]['nilai_min'].' sd '.$arr[5]['nilai_max'].')';
		$arr[5]['label_range'] = $arr[5]['label'].$add_label;
	} else {
		$range = "";
		foreach($arr as $key => $val) {
			$nilai_min = $val['nilai_min'];
			$nilai_max = $val['nilai_max'];
			
			if($nilai>=$nilai_min && $nilai<=$nilai_max) $range = $key;
		}
		
		$arr[$range]['dcolor'] = $arr[$range]['color'];
		$arr[$range]['drange'] = $range;
	}
	
	if($output=="master") {
		return $arr[$nilai];
	} else if($output=="label") {
		return $arr[$range]['label'];
	} else if($output=="desc") {
		return $arr[$range]['desc'];
	} else if($output=="range") {
		return $range;
	} else {
		return $arr[$range];
	}
}

function lw__daftar_kode_status() {
	$arr = array();
	
	$arr['10'] = 'diajukan_kary';
	$arr['20'] = 'diacc_sdm';
	$arr['30'] = 'diacc_sevp';
	$arr['-10'] = 'dibatalkan_kary';
	$arr['-20'] = 'ditolak_sdm';
	$arr['-30'] = 'ditolak_sevp';
	$arr['-40'] = 'batal_diselenggarakan';
	
	return $arr;
}

function lw_classroom_kategori_metode() {
	$arr = array();
	
	$arr['blended'] = 'Blended';
	$arr['offline'] = 'Offline';
	$arr['online'] = 'Online';
	
	return $arr;
}
?>