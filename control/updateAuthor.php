<?php
	require_once '../control/conn.php';
	$id=$_POST['id'];
	$name=$_POST['name'];
	$org=$_POST['org'];
	$query=mysql_query($link, 'UPDATE `author` SET `name`="'.$name.'",`organization`="'.$org.'" WHERE `id`="'.$id.'"');
?>