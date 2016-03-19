<?php
	require_once '../control/conn.php';

	$userId=$_POST['uid'];
	$query=mysqli_query($link, 'SELECT * FROM `member` WHERE idmember="'.$userId.'"');
	$row=mysqli_num_rows($query);
	if($row>0){
		$fetch=mysqli_fetch_assoc($query);
		$data=array('name' => $fetch['name'], 'addr' => $fetch['address'], 'authority' => $fetch['authority']);
		echo json_encode($data);
	}else echo "error";
?>