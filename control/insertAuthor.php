<?php
	require_once '../control/conn.php';
	$name=$_POST['name'];
	$org=$_POST['org'];
	if(checkExit($name)){
		$query=mysqli_query($link, 'INSERT INTO `author`(`name`,`organization`) VALUES("'.$name.'","'.$org.'")');
		if($query) echo 'success';
		else echo 'error';	
	}
	else echo 'error';
	

	function checkExit($name){
		$query=mysqli_query($link, 'SELECT * FROM `author` WHERE `name`="'.$name.'"');
		$count=mysqli_num_rows($query);
		if($count>0) return false;
		else return true;
	}
?>