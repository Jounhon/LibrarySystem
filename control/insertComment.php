<?php
	require_once '../control/conn.php';
	$data=$_POST['data'];
	mysqli_query($link, 'start transaction');
	
	$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
	if($data['anonymous']=='true') $anonymous=1;
	else $anonymous=0;
	$query=mysqli_query($link, 'INSERT INTO `comment`(`isbn`,`account`,`rate`,`comment`,`date`,`anonymous`) 
		VALUES ("'.$data['isbn'].'","'.$data['account'].'","'.$data['rate'].'","'.$data['comment'].'","'.$datetime.'",'.$anonymous.')');
	if(!$query){
		echo mysqli_error($conn);
		mysqli_query($link, "ROLLBACK");
	}
	$query=mysqli_query($link, 'UPDATE `message` SET `content`="<b>已評過分了。</b>" WHERE `id`="'.$data['msgId'].'"');
	if(!$query){
		echo mysqli_error($conn);
		mysqli_query($link, "ROLLBACK");
	}
	mysqli_query($link, "COMMIT");

?>