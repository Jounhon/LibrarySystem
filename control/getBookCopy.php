<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])||$_SESSION['userAuthority']=='1') echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
	$isbn=$_POST['isbn'];
	$query=mysqli_query($link, 'SELECT `code`,`number`,`status` FROM `book_copy` WHERE `isbn`="'.$isbn.'"');
	$array=array();
	while($res=mysqli_fetch_row($query)){
		$array[]=array('code' => $res[0], 'copy' => $res[1], 'status' => $res[2]);
	}
	echo json_encode($array);
?>
