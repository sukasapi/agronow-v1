<?php
// $db = new mysqli("localhost","root","","dev_agronow", 3367);
// $db = new mysqli("dev.creacle.com","remoteacc","jeruklegi","dev_agronow");
$db = new mysqli("localhost","u8132670_agronow",",8T35}Cj-Wv5","u8132670_agronow"); // 185.237.144.74

// Check connection
if ($db->connect_errno) {
  echo "Failed to connect to MySQL: " . $db->connect_error;
  exit();
}

function formatSizeUnits($bytes){
	if ($bytes >= 1073741824)
	{
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576)
	{
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024)
	{
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	}
	elseif ($bytes > 1)
	{
		$bytes = $bytes . ' bytes';
	}
	elseif ($bytes == 1)
	{
		$bytes = $bytes . ' byte';
	}
	else
	{
		$bytes = '0 bytes';
	}

	return $bytes;
}

$like = 'data:audio/mp3;base64,';
$where = " WHERE";
$where .= " content_desc LIKE '%".$like."%'";
// $where .= " AND";
// $where .= " content_id = 5634";
// $where .= " LIMIT 10 OFFSET 0";

$query = "SELECT content_id, content_alias, section_id, content_name FROM _content".$where.';';

$result = $db->query($query);

$filepath = getcwd().'/media/audio/';

if($result->num_rows > 0){
	while ($data = $result->fetch_assoc()) {
		// $filename = $data['content_id'].'_'.$data['content_alias'].'_original';
		$filename = $data['content_id'].'_'.$data['content_alias'];
		// $filename_base64 = $data['content_id'].'_'.$data['content_alias'].'_base64';
		$filename_mp3 = $data['content_id'].'_'.$data['content_alias'].'.mp3';

		$query = "SELECT content_desc FROM _content WHERE content_id = ".$data['content_id'];

		$content = $db->query($query);
		while ($ct = $content->fetch_assoc()) {
			file_put_contents($filepath.$filename, $ct['content_desc']);
			$d = explode('data:audio/mp3;base64,', $ct['content_desc']);
			if(isset($d[1])){
				$d2 = explode('&quot;', $d[1]);
				unset($d);

				$base64 = $d2[0];
				unset($d2);

				$mp3 = base64_decode($base64);
				unset($base64);

				file_put_contents($filepath.$filename_mp3, $mp3);
				unset($mp3);
			}
		}

		$content->free();

		// add to table _media
		$filesize = formatSizeUnits(filesize($filepath.$filename_mp3));
		$query = "INSERT INTO _media(section_id, data_id, media_name, media_alias, media_desc, media_type, media_value, media_size, media_primary, media_status, media_create_date) VALUES ('".$data['section_id']."', '".$data['content_id']."', '".$data['content_name']."', '".$data['content_alias']."', 'download', 'audio', '".$filename_mp3."', '".$filesize."', '1', '1', '".date('Y-m-d H:i:s')."')";
		$db->query($query);

		// update content_desc
		$query = "UPDATE _content SET content_desc = '' WHERE content_id = ".$data['content_id'];
		$db->query($query);

		var_dump($data['content_id']);
	}
	echo 'Successfully convert '.$result->num_rows.' data';
	$result->free();
}else{
	echo 'Num Rows = '.$result->num_rows;
}
?>