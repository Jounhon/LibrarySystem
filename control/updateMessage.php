<?php
	require_once '../control/conn.php';
	$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
	if($_POST['action']=='read'){
		$id=$_POST['id'];
		$query=mysqli_query($link, 'UPDATE `message` SET `read`="'.$datetime.'" WHERE `id`="'.$id.'"');
	}
	else if($_POST['action']=='delete'){
		$ids=$_POST['ids'];
		$id = explode(",", $ids);
		for($i=0; $i<count($id);$i++){
			$query=mysqli_query($link, 'UPDATE `message` SET `delete`="'.$datetime.'" WHERE `id`="'.$id[$i].'"');
		}
	}
?>