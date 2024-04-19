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

$allowed_token = "ARG6B466585-qJACs1J0apruOOJCg";
$allowed_user = "SIGMA_SOLUSI";

$cur_token = $_SERVER['HTTP_TOKEN'];
$cur_user = $_SERVER['HTTP_USER_ACCESS'];

$result = array();
if($cur_token!=$allowed_token || $cur_user!=$allowed_user) {
	$result['status'] = "0";
	$result['message'] = "Unauthorized";
} else {
	$arrData = array();
	$sql = "select * from _group where group_status='active' order by group_id";
	$res = mysqli_query($con,$sql);
	while($row = mysqli_fetch_object($res)) {
		$arrData[$row->group_id]['group_id'] = $row->group_id;
		$arrData[$row->group_id]['group_name'] = $row->group_name;
		$arrData[$row->group_id]['aghris_company_code'] = $row->aghris_company_code;
	}
	$result['status'] = "1";
	$result['data'] = $arrData;
	$result['message'] = "Data Successfully Retrieved";
}

header('Content-Type:application/json');
echo json_encode($result);
?>