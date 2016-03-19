<?php
	require_once '../control/conn.php';
	require_once '../control/mail.php';
	$connection=$link;
	
	function is_fine($id){
		$query=mysqli_query($connection, "SELECT * FROM `fine` WHERE `logid`='".$id."'");
		$rows=mysqli_num_rows($query);
		if($rows==0) return false;
		else return true;
	}
	function dealMail($task,$array){
		for($i=0;$i<count($task);$i++){
			if($task[$i]['action']=="cancel") $log="Reserve Cancel Mail were Sended DONE to accout ";
			else if($task[$i]['action']=='overdue') $log="Book overdue Mail were Sended DONE to accout ";
			else if($task[$i]['action']=='fine') $log="Fine Update Mail were Sended DONE to accout ";
			$array[]=array("status"=>$task[$i]['action'],"log"=>$log.$task[$i]['account']);
			sendMail($task[$i]['action'],$task[$i]['content'],$task[$i]['mail'],$task[$i]['name']);
		}
		return $array;
	}

	$mail_mission=array();
	$array=array();
	$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
	/** reserve overdue**/
	mysqli_query($connection, 'start transaction');
	$query=mysqli_query($connection, 'SELECT `id` FROM `log` WHERE `check_in` IS NULL AND `hold_cancel` IS NULL AND `due` < curdate()');
	if($query){
		while($res=mysqli_fetch_row($query)){
			$update=mysqli_query($connection, 'UPDATE `log` SET `hold_cancel`="'.$datetime.'" WHERE `id`="'.$res[0].'"');
			if($update){				
				$array[]=array("status"=>"success","log"=>"Reserve Cancel is DONE to log.Id ".$res[0]);
				
				$account=mysqli_query($connection, 'SELECT m.email,m.account FROM `member` m INNER JOIN log ON log.account=m.account WHERE log.id="'.$res[0].'"');
				$fetch_account=mysqli_fetch_assoc($account);
				$book=mysqli_query($connection, 'SELECT b.title FROM `book` b INNER JOIN `book_copy` bc ON bc.isbn=b.isbn INNER JOIN log ON log.code=bc.code WHERE log.id="'.$res[0].'"');
				$fetch_book=mysqli_fetch_assoc($book);
				$mail_mission[]=array("action"=>"cancel","account"=>$fetch_account['account'],"mail"=>$fetch_account['email'],"name"=>$fetch_book['title'],"content"=>"");
				$message=mysqli_query($connection, 'INSERT INTO `message`(`account`,`title`,`content`,`send`,`action`) VALUES ("'.$fetch_account['account'].'","'.$fetch_book['title'].'預約取消通知","您預約的<b>'.$fetch_book['title'].'</b>已經超過3天卻尚未借書，為保障他人權益，系統已自動幫您取消。<br>如果有甚麼問題可以透過<a href=\'#\'>意見回饋</a>給我們，管理員會盡快為您回復。</a>","'.$datetime.'","cancel")');
			}else{
				mysqli_query($connection, 'ROLLBACK');
				$array[]=array("status"=>"error","log"=>mysqli_error($conn));
			}
		}
		mysqli_query($connection, 'COMMIT');
	}
	else{
		mysqli_query($connection, 'ROLLBACK');
		$array[]=array("status"=>"error","log"=>mysqli_error($conn));
	}
	$array[]=array("status"=>"done","log"=>"Reserve Overdue Check is DONE!");


	/** book overdue**/
	mysqli_query($connection, 'start transaction');
	$query=mysqli_query($connection, 'SELECT fine.id,DATEDIFF(CURDATE(),log.due)-30 as days FROM `fine` INNER JOIN `log` ON log.id=fine.logid WHERE `payment`=0');
	if($query){
		while($res=mysqli_fetch_row($query)){
			$fine=$res[1]*5;
			$update=mysqli_query($connection, 'UPDATE `fine` SET `days`="'.$res[1].'",`fine`="'.$fine.'" WHERE `id`="'.$res[0].'"');
			if($update){
				$array[]=array("status"=>"success","log"=>"Fine Update is DONE to fine.Id ".$res[0]);
			}else{
				mysqli_query($connection, 'ROLLBACK');
				$array[]=array("status"=>"error","log"=>mysqli_error($conn));
			}
		}
		mysqli_query($connection, 'COMMIT');
	}
	else{
		mysqli_query($connection, 'ROLLBACK');
		$array[]=array("status"=>"error","log"=>mysqli_error($conn));
	}
	$array[]=array("status"=>"done","log"=>"Fine Update is DONE!");


	/** fine update**/
	mysqli_query($connection, 'start transaction');
	$query=mysqli_query($connection, 'SELECT `id`,DATEDIFF(CURDATE(),due)-30 as days FROM `log` 
		WHERE `check_in` IS NOT NULL 
			AND `hold` IS NULL 
			AND `check_out` IS NULL 
			AND `due`<CURDATE()');
	if($query){
		while($res=mysqli_fetch_row($query)){
			$fine=$res[1]*5;
			if(!is_fine($res[0])){
				$insert=mysqli_query($connection, 'INSERT INTO `fine`(`logid`,`days`,`fine`) VALUES ("'.$res[0].'","'.$res[1].'","'.$fine.'")');
				if($insert){
					$array[]=array("status"=>"success","log"=>"Book Overdue is DONE to log.Id ".$res[0]);

					$account=mysqli_query($connection, 'SELECT m.email,m.account FROM `member` m INNER JOIN log ON log.account=m.account WHERE log.id="'.$res[0].'"');
					$fetch_account=mysqli_fetch_assoc($account);
					$book=mysqli_query($connection, 'SELECT b.title FROM `book` b INNER JOIN `book_copy` bc ON bc.isbn=b.isbn INNER JOIN log ON log.code=bc.code WHERE log.id="'.$res[0].'"');
					$fetch_book=mysqli_fetch_assoc($book);
					$mail_mission[]=array("action"=>"overdue","account"=>"","mail"=>$fetch_account['email'],"name"=>$fetch_book['title'],"content"=>"");					
					$message=mysqli_query($connection, 'INSERT INTO `message`(`account`,`title`,`content`,`send`,`action`) VALUES ("'.$fetch_account['account'].'","'.$fetch_book['title'].'逾期通知","您借的<b>'.$fetch_book['title'].'</b>已經超過30天<br>親愛的系統來提醒您~每逾期一天是$5，如果超過30天未還或是遺失、損毀，依其定價的3倍計價賠現。<br>如果有甚麼問題可以透過<a href=\'#\'>意見回饋</a>給我們，管理員會盡快為您回復。</a>","'.$datetime.'","overdue")');
				}
				else{
					mysqli_query($connection, 'ROLLBACK');
					$array[]=array("status"=>"error","log"=>mysqli_error($conn));
				}
			}
		}
		mysqli_query($connection, 'COMMIT');
	}
	else{
		mysqli_query($connection, 'ROLLBACK');
		$array[]=array("status"=>"error","log"=>mysqli_error($conn));
	}
	$array[]=array("status"=>"done","log"=>"Book Overdue Check is DONE!");

	/** total fine **/
	//$date=date("Y-m-d H:i:s",mktime(date('H')+8));
	$date=date_create($datetime);
	$query=mysqli_query($connection, 'SELECT COUNT(*) AS count, SUM(fine.fine) as total, m.account, m.email 
		FROM `fine` 
			INNER JOIN `log`
				ON log.id=fine.logid
			INNER JOIN `member` m
				ON m.account=log.account
		WHERE `payment`=0 GROUP BY log.account');
	while($res=mysqli_fetch_row($query)){
		$content="以下是您的罰金明細表，共".$res[0]."筆罰鍰－".$res[1]."元整，請盡快將罰金繳清。<br><strong>否則系統將會禁止您的預約功能或借書權力。</strong><br><table class='table table-condensed'><thead><th>Book</th><th>Check in</th><th>Check out</th><th>Due</th><th>Days</th><th>Fine</th></thead><tbody>";
		$fine_list=mysqli_query($connection, 'SELECT b.title,l.check_in,l.check_out,l.due,f.days,f.fine 
			FROM `fine` f
				INNER JOIN `log` l
					ON l.account="'.$res[2].'" 
				INNER JOIN book_copy bc
					ON l.code=bc.code
				INNER JOIN book b
					ON b.isbn=bc.isbn
			WHERE f.payment=0 AND l.id=f.logid');
		while($list=mysqli_fetch_row($fine_list)){
			$ci = date_create($list[1]);
			$co = date_create($list[2]);
			$content.="<tr class='warning'><td>".$list[0]."</td><td>".date_format($ci, 'Y-m-d')."</td><td>".date_format($co, 'Y-m-d')."</td><td>".$list[3]."</td><td>".$list[4]." days</td><td>$".$list[5]."</td></tr>";
		}
		$content.="<tr class='danger'><td colspan='4'></td><td><strong>Total</strong></td><td><span class='glyphicon glyphicon-usd'></span><strong>".$res[1]."</strong></td></tr></tbody></table>";
		$message=mysqli_query($connection, 'INSERT INTO `message`(`account`,`title`,`content`,`send`,`action`) 
			VALUES ("'.$res[2].'","'.date_format($date, 'Y-m-d').'罰金通知","'.$content.'","'.$datetime.'","fine")');
		$mail_mission[]=array("action"=>"fine","account"=>$res[2],"mail"=>$res[3],"name"=>$content,"content"=>date_format($date, 'Y-m-d')."罰金通知");
	}



	$logs=dealMail($mail_mission,$array);
	echo json_encode($logs) ;	
	//echo json_encode($array);
?>