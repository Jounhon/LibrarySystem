<?php
	session_start();
	require_once '../control/conn.php';
	require_once '../control/mail.php';
	$data=$_POST['data'];
	$host="127.0.0.1";
    $port=3306;
    $socket="";
    $user="admin";
    $password="1234";
    $dbname="library_system";
    $connection = mysqli_connect($host, $user, $password, $dbname);

	if(checkExit($data['account'])){
		mysqli_query($connection, 'start transaction');
		$query=mysqli_query($connection, 'INSERT INTO `member`(`name`,`password`,`email`,`account`,`token`)
			VALUES("'.$data['name'].'","'.$data['password'].'","'.$data['email'].'","'.$data['account'].'","'.md5($data['account']).'")');
		if($query){
			$query=mysqli_query($connection, 'SELECT * FROM `member` WHERE `account`="'.$data['account'].'" AND `password`="'.$data['password'].'"');
			if($query){
				$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
				$fetch=mysqli_fetch_assoc($query);
				$message_query=mysqli_query($connection, 'INSERT INTO `message` (`account`,`title`,`content`,`send`,`action`) VALUES ("'.$fetch['account'].'","註冊會員認證通知","恭喜你成為圖書館系統的會員，請去您註冊的信箱認證啟用會員。","'.$datetime.'","signup")');
				if($message_query){
					mysqli_query($connection, 'COMMIT');
					$_SESSION['userAccount']=$fetch['account'];
					$_SESSION['userAuthority']=$fetch['authority'];
					$_SESSION['userName']=$fetch['name'];
					sendMail('signup',$fetch['account'],$fetch['email'],$fetch['name']);	
				}else{
					mysqli_query($connection, 'ROLLBACK');
					echo mysqli_error($conn);
				}
			}else{
				mysqli_query($connection, 'ROLLBACK');
				echo mysqli_error($conn);
			}
		}
		else{
			mysqli_query($connection, 'ROLLBACK');
			echo mysqli_error($conn);
		}
	}
	else echo 'errorOfAccount';
	

	function checkExit($account){
		$data=$_POST['data'];
    	$host="127.0.0.1";
        $port=3306;
        $socket="";
        $user="admin";
        $password="1234";
        $dbname="library_system";
        $connection = mysqli_connect($host, $user, $password, $dbname);
	    $query=mysqli_query($connection, 'SELECT * FROM `member` WHERE `account`="'.$account.'"');
		$count=mysqli_num_rows($query);
		if($count>0) return false;
		else return true;
	}
?>