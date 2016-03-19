<?php
	require_once '../control/conn.php';
	$author=$_POST['author'];
	$query=mysqli_query($link, 'SELECT * FROM `author` WHERE `name`="'.$author.'"');
	$row=mysqli_num_rows($query);
	if($row==1){
		echo "success";
	}else{
		echo "error";
	}
?>