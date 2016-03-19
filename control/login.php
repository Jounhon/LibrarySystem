<?php
require_once '../control/conn.php';

session_start();

function encrypt($string){
    return base64_encode(base64_encode(base64_encode($string)));
}
 
function decrypt($string){
    return base64_decode(base64_decode(base64_decode($string)));
}
    $connection = $link;
	$userAccount=$_POST['account'];
	$userPW=$_POST['pw'];
	$query=mysqli_query($connection, 'SELECT * FROM `member` WHERE account="'.$userAccount.'" AND password="'.$userPW.'"');
	$row=mysqli_num_rows($query);
	if($row>0){
		$fetch=mysqli_fetch_assoc($query);
		$_SESSION['userAccount']=$fetch['account'];
		$_SESSION['userAuthority']=$fetch['authority'];
		$_SESSION['userName']=$fetch['name'];
		$array=array("status"=>"success","authority"=>$fetch['authority']);
	}else $array=array("status"=>"error");
	echo json_encode($array);
?>