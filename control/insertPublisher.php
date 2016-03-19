<?php
	require_once '../control/conn.php';
	$name=$_POST['name'];
	$addr=$_POST['addr'];
	if(checkExit($name)){
		$query=mysqli_query($link, 'INSERT INTO `publisher`(`name`,`address`) VALUES("'.$name.'","'.$addr.'")');
		if($query) echo 'success';	
		else echo 'error';
	}
	else echo 'error';

	function checkExit($name){
		$query=mysqli_query($link, 'SELECT * FROM `publisher` WHERE `name`="'.$name.'"');
		$count=mysqli_num_rows($query);
		if($count>0) return false;
		else return true;
	}
?>