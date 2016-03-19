<?php
	require_once '../control/conn.php';
	$account=$_POST['account'];
	$array=array();
	if($_POST['action']=='menu'){
		$query=mysqli_query($link, 'SELECT COUNT(*) as count FROM `message` WHERE `read` IS NULL AND `delete` IS NULL AND `account`="'.$account.'"');
		$fetch=mysqli_fetch_assoc($query);
		$array[]=array('count'=>$fetch['count']);
		$query=mysqli_query($link, 'SELECT * FROM `message` WHERE account="'.$account.'" AND `delete` IS NULL ORDER BY `send` DESC LIMIT 5');
		while($res=mysqli_fetch_row($query)){
			if($res[5]=='') $read=false;
			else $read=true;
			$array[]=array('title'=>$res[2], 'content'=>$res[3], 'read'=>$read,'id'=>$res[0]);
		}	
	}
	
	echo json_encode($array);
?>