<?php
	require_once '../control/conn.php';
	$account=$_POST['account'];
	$value=$_POST['a'];
	$query=mysqli_query($Link, 'UPDATE `member` SET authority="'.$value.'" WHERE account="'.$account.'"');

?>