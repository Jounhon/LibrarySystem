<?php
	require_once '../control/conn.php';
	$id=$_POST['id'];
	$query=mysqli_query($link, 'SELECT * FROM `classification` WHERE `class_id`="'.$id.'"');
	$row=mysqli_num_rows($query);
	if($row>0){
		$array=array();
		while($res=mysqli_fetch_row($query)){
			$array[]=array('name' => $res[2], 'id' => $res[0]);
		}
		echo json_encode($array);
	}else echo "error";
?>