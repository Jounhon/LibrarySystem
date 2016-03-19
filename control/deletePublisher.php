<?php
	require_once '../control/conn.php';
	$id=$_POST['id'];
	$query=mysqli_query($link, 'DELETE FROM `publisher` WHERE `id`="'.$id.'"');
?>