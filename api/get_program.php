<?php
// exit;
error_reporting(E_ALL & ~E_NOTICE);

if($_SERVER['HTTP_HOST']=='localhost') {
	$host	 = "localhost";
	$userSQL = "root";
	$passSQL = "";
	$db = "agronow_pa";
} else {
	$host	 = "localhost";
	$userSQL = "u579638955_usrlearning";
	$passSQL = "XpPa6n$4";
	$db = "u579638955_dblearning";
}

$con = mysqli_connect($host, $userSQL, $passSQL, $db);
$result=array();

$act=$_POST['act'];
$progid=$_POST['param'];
if($act =="" || $progid ==""){
	$result['status'] = "1";
	$result['data'] = array();
	$result['message'] = "Data Not Retrieved";
}else{
	switch($act){
		case 'program_member':
			//$arrData = array();
			$sql = "select cm.cr_id as idkelas,cm.member_id as member , m.member_name as namamember,cr.cr_name as namaprogram,m.member_nip as NIP "; 
			$sql .="FROM _classroom_member as cm ";
			$sql .="JOIN _member as m ON m.member_id=cm.member_id  ";
			$sql .=" JOIN _classroom as cr ON cr.cr_id=cm.cr_id ";
			$sql .="where cm.cr_id='".$progid."' order by cm.cr_id";
	
			$res = mysqli_query($con,$sql);
			while($row = mysqli_fetch_object($res)) {
				$arrData[$row->cr_id]['member'][]=$row->member;
				$arrData[$row->cr_id]['nama'][]=$row->namamember;
				$arrData[$row->cr_id]['NIP'][]=$row->NIP;
			}
			
		break;
		case 'get':
			if($progid=="all"){
				//$arrData = array();
				$sql = "select cm.cr_id as idkelas,cm.member_id as member,cr_name as namakelas , m.member_name as namamember,cr.cr_name as namaprogram,m.member_nip as NIP "; 
				$sql .="FROM _classroom_member as cm ";
				$sql .="JOIN _member as m ON m.member_id=cm.member_id  ";
				$sql .=" JOIN _classroom as cr ON cr.cr_id=cm.cr_id ";
				$sql .="order by cm.cr_id";
		
				$res = mysqli_query($con,$sql);
				while($row = mysqli_fetch_object($res)) {
					$arrData[$row->idkelas]['member'][]=$row->member;
					$arrData[$row->idkelas]['nama'][]=$row->namamember;
					$arrData[$row->idkelas]['NIP'][]=$row->NIP;
				}
				
			}else{
				//$arrData = array();
				$sql = "select cm.cr_id as idkelas,cm.member_id as member,cr.cr_name as namakelas , m.member_name as namamember,cr.cr_name as namaprogram,m.member_nip as NIP "; 
				$sql .="FROM _classroom_member as cm ";
				$sql .="JOIN _member as m ON m.member_id=cm.member_id  ";
				$sql .=" JOIN _classroom as cr ON cr.cr_id=cm.cr_id ";
				$sql .="where cm.cr_id='".$progid."' order by cm.cr_id";
		
				$res = mysqli_query($con,$sql);
				while($row = mysqli_fetch_object($res)) {
					$arrData[$row->namakelas]['member'][]=$row->member;
					$arrData[$row->namakelas]['nama'][]=$row->namamember;
					$arrData[$row->namakelas]['NIP'][]=$row->NIP;
				}
				
			}
		break;
		default:
			$arrData = array();
		break;
	}
	$result['status'] = "1";
	$result['data'] = $arrData;
	$result['message'] = "Data Successfully Retrieved";
}


/*
$allowed_token = "ARG6B466585-qJACs1J0apruOOJCg";
$allowed_user = "SIGMA_SOLUSI";

$cur_token = $_SERVER['HTTP_TOKEN'];
$cur_user = $_SERVER['HTTP_USER_ACCESS'];

*/

header('Content-Type:application/json');
echo json_encode($result);
?>