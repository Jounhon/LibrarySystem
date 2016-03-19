<?php
	require_once '../control/conn.php';
	$id=$_POST['id'];
	$query=mysqli_query($link, 'SELECT * FROM `classification` WHERE `id`="'.$id.'"');
	$row=mysqli_num_rows($query);
	if($row>0){
		$fetch=mysqli_fetch_assoc($query);
		echo $fetch['sub_class'];
	}else echo "error";
?>