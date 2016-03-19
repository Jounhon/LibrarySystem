<?php
	require_once '../control/conn.php';
	$publisher=$_POST['publisher'];
	$query=mysqli_query($link, 'SELECT * FROM `publisher` WHERE `name`="'.$publisher.'"');
	$row=mysqli_num_rows($query);
	if($row==1){
		echo "success";
	}else{
		echo "error";
	}
?>