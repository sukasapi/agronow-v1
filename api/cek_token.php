<?php
// exit;
error_reporting(E_ALL & ~E_NOTICE);

if($_SERVER['HTTP_HOST']=='localhost') {
	$host	 = "localhost";
	$userSQL = "root";
	$passSQL = "";
	$db = "zdell_agronow";
} else {
	$host	 = "localhost";
	$userSQL = "u579638955_usrlearning";
	$passSQL = "XpPa6n$4";
	$db = "u579638955_dblearning";
}

$con = mysqli_connect($host, $userSQL, $passSQL, $db);

$allowed_token = "TKN23096293";
$allowed_user = "SIGMA_SOLUSI";

$cur_token = $_SERVER['HTTP_TOKEN'];
$cur_user = $_SERVER['HTTP_USER_ACCESS'];

$result = array();
if($cur_token!=$allowed_token || $cur_user!=$allowed_user) {
	$result['status'] = "0";
	$result['message'] = "Unauthorized";
} else {
	define("MFA_LIFETIME",300); // 5*60
	
	$app = $_GET['app'];
	$id_user = (int) $_GET['id'];
	$token = (int) $_GET['token'];
	
	if($app=="enroll") {	
		// delete token yg kadaluarsa
		$sql = "delete from _mfa where tgl_request<=TIMESTAMPADD(SECOND,-".MFA_LIFETIME.",now())";
		mysqli_query($con,$sql);
		
		// token masih berlaku?
		$sql = "select id, id_user from _mfa where app_target='enroll' and id_user='".$id_user."' and token='".$token."' and now()<=TIMESTAMPADD(SECOND,".MFA_LIFETIME.",tgl_request)";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_object($res);
		$id_token = $row->id;
		$id_user = $row->id_user;
		if(empty($id_token)) {
			$status = '0';
			$data = '';
			$message = 'Token tidak ditemukan/sudah kadaluarsa. ';
		} else {
			// ambil data admin ybs
			$sql = "select * from _user where user_id='".$id_user."' and user_status='active' ";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_object($res);
			$num = mysqli_num_rows($res);
			if($num<=0) {
				$status = '0';
				$data = '';
				$message = 'Data Not Found.';
			} else {
				$level = '';
				if($row->user_level_id=="1") $level = 'super_admin';
				else if($row->user_level_id=="3") $level = 'admin_portal';
				else $level = 'unknown';
				
				$arrD = array();
				$arrD['user_id'] = $row->user_id;
				$arrD['user_name'] = $row->user_name;
				$arrD['user_group'] = $row->user_code;
				$arrD['user_email'] = $row->user_email;
				$arrD['level'] = $level;
				
				$status = '1';
				$data = $arrD;
				$message = 'Data Found.';
			}
		}
	} else {
		$status = '0';
		$data = '';
		$message = 'unknown app';
	}
	
	$result['status'] = $status;
	$result['data'] = $data;
	$result['message'] = $message;
}

header('Content-Type:application/json');
echo json_encode($result);
?>