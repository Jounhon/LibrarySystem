<?php
	require_once '../control/conn.php';
	$account=$_POST['account'];
	$pw=$_POST['pw'];
	$query=mysqli_query($link, 'SELECT * FROM `member` WHERE `account`="'.$account.'" AND `password`="'.$pw.'"');
	$row=mysqli_num_rows($query);
	if($row==1){
		echo "success";
	}else{
		echo "error";
	}
?>