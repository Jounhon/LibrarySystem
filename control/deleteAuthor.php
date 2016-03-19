<?php
	require_once '../control/conn.php';
	$id=$_POST['id'];
	$query=mysqli_query($link, 'DELETE FROM `author` WHERE `id`="'.$id.'"');
?>