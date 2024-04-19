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
	$userSQL = "lppexternal_uagrn";
	$passSQL = "KKJ3AbnzltIxofg";
	$db = "lppexternal_dbagronow";
}

$con = mysqli_connect($host, $userSQL, $passSQL, $db);
$result=array();
if(isset($_POST['act']) && $_POST['act']!=''){
    $act=$_POST['act'];
    switch($act){
        case '':

        break;
        default:

        break;
    }
}else{

}


header('Content-Type:application/json');
echo json_encode($result);
?>