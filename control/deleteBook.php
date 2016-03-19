<?php
	require_once '../control/conn.php';
	if(!isset($_POST['action'])){
		echo $_POST['isbn'];
		$isbn=$_POST['isbn'];
		$query=mysqli_query($link, 'DELETE FROM `book` WHERE `isbn`="'.$isbn.'"');
		$query=mysqli_query($link, 'DELETE FROM `book_author` WHERE `isbn`="'.$isbn.'"');
		$query=mysqli_query($link, 'DELETE FROM `book_copy` WHERE `isbn`="'.$isbn.'"');
	}
	else if($_POST['action']=='copyDelete'){
		$code=$_POST['code'];
		$query=mysqli_query($link, 'DELETE FROM `book_copy` WHERE `code`="'.$code.'"');
		echo 'success'	;
	}
	
?>