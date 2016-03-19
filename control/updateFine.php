<?php
	require_once '../control/conn.php';
	$account=$_POST['account'];
	$query=mysqli_query($link, 'UPDATE fine INNER JOIN log
				ON log.id=fine.logid 
				SET fine.payment=1 
				WHERE log.account="'.$account.'"');
	$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
	$query=mysqli_query($link, 'UPDATE log  INNER JOIN fine
				ON log.id=fine.logid 
				SET log.check_out="'.$datetime.'"
				WHERE log.account="'.$account.'"');
?>