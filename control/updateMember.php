<?php
	session_start();
	require_once '../control/conn.php';
	$data=$_POST['data'];
	$query=mysqli_query($link, 'UPDATE `member` SET `name`="'.$data['name'].'",`email`="'.$data['email'].'",`address`="'.$data['addr'].'",`password`="'.$data['npw'].'" WHERE `account`="'.$data['account'].'"');
	if($query) echo 'success';
	else echo 'error';	
?>