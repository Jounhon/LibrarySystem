<?php
	require_once '../control/conn.php';
	$key=$_POST['key'];
	$query=mysqli_query($link, 'SELECT * FROM `publisher` WHERE `name` LIKE "%'.$key.'%"');
	$row=mysqli_num_rows($query);
	if($row>0){
		while($res=mysqli_fetch_row($query)){
			$array[]=array('name' => $res[1]);
		}
	}else{
		$array[]=array('name'=>"查無此作者");
	}
	echo json_encode($array);
?>