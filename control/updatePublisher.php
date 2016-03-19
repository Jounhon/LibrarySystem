<?php
	require_once '../control/conn.php';
	$id=$_POST['id'];
	$name=$_POST['name'];
	$addr=$_POST['addr'];
	$query=mysqli_query($link, 'UPDATE `publisher` SET `name`="'.$name.'",`address`="'.$addr.'" WHERE `id`="'.$id.'"');
?>