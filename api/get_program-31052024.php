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
	$userSQL = "lppexternal_uagrn";
	$passSQL = "KKJ3AbnzltIxofg";
	$db = "lppexternal_dbagronow";
}

$con = mysqli_connect($host, $userSQL, $passSQL, $db);
if($con){
    
    $act=$_GET['act'];
    $progid=$_GET['param'];
    if($act =="" || $progid ==""){
    	$result['status'] = "0";
    	$result['data'] = array();
    	$result['message'] = "Data Not Retrieved";
    }else{
        $act=$_GET['act'];
	    $progid=$_GET['param'];
	    $arrData=array();
	    switch ($act){
		case "get":
			if($progid=="all"){
			    //panggil semua
				$sql = "select cm.cr_id as idkelas,cm.member_id as member,
						cr.cr_name as namakelas , cr.cr_date_start as mulai,cr.cr_date_end as selesai, 
						m.member_name as namamember,cr.cr_name as namaprogram,cr.id_lw_classroom as wallet,m.member_nip as NIP,
						g.group_name as entitas "; 
				$sql .="FROM _classroom_member as cm ";
				$sql .="LEFT JOIN _member as m ON m.member_id=cm.member_id  ";
				$sql .="LEFT JOIN _group as g ON g.group_id=m.group_id ";
				$sql .="LEFT JOIN _classroom as cr ON cr.cr_id=cm.cr_id ";
				$sql .="WHERE cm.is_pk='0' ";
				$sql .=" order by cm.cr_id";
				$res = mysqli_query($con,$sql);
				while($row = mysqli_fetch_object($res)) {
				    ///cek jika ada learning wallet ambil kodenya
		          if($row->wallet!=0 || $row->wallet !=""){
		              ///cek jika ada learning wallet ambil kodenya
		              $idwallet=$row->wallet;
		          $sql2 ="SELECT * FROM _learning_wallet_classroom WHERE id='".$row->wallet."' limit 1";
		            $res2 = mysqli_query($con,$sql2);
		            $dtwallet = mysqli_fetch_array($res2);
		            $wallet=$dtwallet['kode'];
		            //print_r($dtwallet);
		          }else{
		              $wallet="-";
		          }
		        
					$arrData['wallet']=$wallet;
					$arrData['mulai']=date('d-m-Y',strtotime($row->mulai));
					$arrData['selesai']=date('d-m-Y',strtotime($row->selesai));
					$arrData['namaagro']=$row->namakelas;
					$arrData['member'][]=$row->member;
					$arrData['nama'][]=$row->namamember;
					$arrData['NIP'][]=$row->NIP;
				}
			}else{
				$sql = "select cm.cr_id as idkelas,cm.member_id as member,
						cr.cr_name as namakelas , cr.cr_date_start as mulai,cr.cr_date_end as selesai, 
						m.member_name as namamember,cr.cr_name as namaprogram,cr.id_lw_classroom as wallet,
						m.member_nip as NIP, g.group_name as entitas "; 
				$sql .="FROM _classroom_member as cm ";
				$sql .="LEFT JOIN _member as m ON m.member_id=cm.member_id ";
				$sql .="LEFT JOIN _group as g ON g.group_id=m.group_id ";
				$sql .="LEFT JOIN _classroom as cr ON cr.cr_id=cm.cr_id ";
				$sql .="where cm.cr_id='".$progid."' AND cm.is_pk='0' order by cm.cr_id limit 1";
		
		         
				$res = mysqli_query($con,$sql);
				while($row = mysqli_fetch_object($res)) {
				    ///cek jika ada learning wallet ambil kodenya
		          if($row->wallet!=0 || $row->wallet !=""){
		              ///cek jika ada learning wallet ambil kodenya
		              $idwallet=$row->wallet;
		          $sql2 ="SELECT * FROM _learning_wallet_classroom WHERE id='".$row->wallet."' limit 1";
		            $res2 = mysqli_query($con,$sql2);
		            $dtwallet = mysqli_fetch_array($res2);
		            $wallet=$dtwallet['kode'];
		            //print_r($dtwallet);
		          }else{
		              $wallet="-";
		          }
		        
					$arrData['wallet']=$wallet;
					$arrData['mulai']=date('d-m-Y',strtotime($row->mulai));
					$arrData['selesai']=date('d-m-Y',strtotime($row->selesai));
					$arrData['namaagro']=$row->namakelas;
					$arrData['member'][]=$row->member;
					$arrData['nama'][]=$row->namamember;
					$arrData['NIP'][]=$row->NIP;
					$arrData['entitas'][]=$row->entitas;
				}
			}
		break;
		default:
				$arrData=array("act"=>"none","parameter"=>"none");
		break;
		
	}
        
        $result['status'] = "1";
    	$result['data'] = $arrData;
    	$result['message'] = "Source Connected";
    }

    
    
	
}else{
    $result['status'] = "0";
	$result['data'] = array();
	$result['message'] = "Database Source not Connected";
}

			
header('Content-Type:application/json');
echo json_encode($result);
?>	