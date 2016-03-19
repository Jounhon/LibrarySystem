<?php
	require_once '../control/conn.php';	
	require_once '../control/mail.php';
	echo 'Verifying... please hold on ~';
    //$data=$_POST['data'];
	$token=$_GET['token'];
	$query=mysqli_query($link , 'SELECT * FROM `member` WHERE `token`="'.$token.'"');
	$rows=mysqli_num_rows($query);
    echo $token;
	if($rows!=0){
		$fetch=mysqli_fetch_assoc($query);
		if($fetch['active']=='0'){
			$query=mysqli_query($connection, 'UPDATE `member` SET `active`=1,`token`="" WHERE `token`="'.$token.'"');
			if(!$query) echo mysqli_error($conn);
			$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
			$query=mysqli_query($connection, 'UPDATE `message` SET `read`="'.$datetime.'" WHERE `action`="signup"');
			$query=mysqli_query($connection, 'INSERT INTO `message` (`account`,`title`,`content`,`send`,`action`) VALUES ("'.$fetch['account'].'","會員認證成功","恭喜你已正式啟用會員，現在開始你可以使用系統各項功能：預約書、查看借書紀錄等...。<br>希望您會喜歡，有甚麼意見或問題可以透過<a href=\"#\">意見回饋</a>給我們，管理員會盡快為您回復。","'.$datetime.'","verify")');
			sendMail('verify',$fetch['account'],$fetch['email'],$fetch['name']);
		}
	}
	header('Location: http://localhost:8888');
    exit;
?>