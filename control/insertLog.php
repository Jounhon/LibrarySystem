<?php
	require_once '../control/conn.php';
	if($_POST['action']=='checkio'){
		$bookCode=$_POST['bookCode'];
		$account=$_POST['account'];
		$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
		$due=date('Y-m-d', strtotime("+30 days"));
		$array=array();
		if(check_book($bookCode)){
			if(!if_check_in($account,$bookCode)){
				$due=false;
				$fine_query=mysqli_query($link, 'SELECT COUNT(*) as count FROM `fine` INNER JOIN log ON log.id=fine.logid WHERE fine.payment=0 AND log.account="'.$account.'"');
				$fetch=mysqli_fetch_assoc($fine_query);
				if($fetch['count']==0){
					$query=mysqli_query($link, 'INSERT INTO `log`(`account`,`code`,`check_in`,`due`) VALUES("'.$account.'","'.$bookCode.'","'.$datetime.'","'.$due.'")');
					if($query){
						$update=mysqli_query($link, 'UPDATE `book_copy` SET `status`="on-loan" WHERE `code`="'.$bookCode.'"');
						$get_book=mysqli_query($link, 'SELECT book.title FROM `book_copy` cb INNER JOIN `book` ON cb.isbn=book.isbn WHERE cb.code="'.$bookCode.'"');
						$fetch_book=mysqli_fetch_assoc($get_book);
						$message=mysqli_query($link, 'INSERT INTO `message`(`account`,`title`,`content`,`send`,`action`) VALUES ("'.$account.'","借書通知","您已於<b>'.$datetime.'</b>借了<b>'.$fetch_book['title'].'</b>。<br>若無誤請忽略此訊息，有問題可洽客服。","'.$datetime.'","checkin")');
						if($update){
							$array[]=array('status'=>'success');
						}else{
							$array[]=array('status'=>'error','error'=>mysqli_error($conn));
						}
					}
				}
				else $array[]=array('status'=>'ban');
			}
			else{
				$query=mysqli_query($link, 'UPDATE `log` SET `check_out`="'.$datetime.'" WHERE `account`="'.$account.'" AND `code`="'.$bookCode.'" AND `check_out` IS NULL');
				if($query){
					$update=mysqli_query($link, 'UPDATE `book_copy` SET `status`="on-shelf" WHERE `code`="'.$bookCode.'"');
					$get_book=mysqli_query($link, 'SELECT book.title,book.isbn FROM `book_copy` cb INNER JOIN `book` ON cb.isbn=book.isbn WHERE cb.code="'.$bookCode.'"');
					$fetch_book=mysqli_fetch_assoc($get_book);
					$message=mysqli_query($link, 'INSERT INTO `message`(`account`,`title`,`content`,`send`,`action`) VALUES ("'.$account.'","給<b>'.$fetch_book['title'].'</b>評分吧","您已於<b>'.$datetime.'</b>已還：<b>'.$fetch_book['title'].'</b>。<br>給這本書評個分吧。<br><input id=\'input-21b\' value=\'0\' type=\'number\' class=\'rating\' min=0 max=5 step=0.1 data-size=\'xs\' data-show-clear=\'false\' data-default-caption=\'{rating}\' data-star-captions=\'{}\'><br><textarea class=\'form-control\' rows=\'5\' id=\'comment\' placeholder=\'comment\'></textarea><br>匿名? <input type=\'checkbox\' class=\'bootstrapToggle\' data-toggle=\'toggle\' data-on=\'Yes\' data-off=\'No\' data-onstyle=\'success\' data-style=\'slow\'>     <button class=\'btn btn-primary btn-sm\' dataISBN=\''.$fetch_book['isbn'].'\'>Send</button>","'.$datetime.'","checkout")');
					if(!$message) echo mysqli_error($conn);
					if($update){
						$array[]=array('status'=>'success');
					}else{
						$array[]=array('status'=>'error','error'=>mysqli_error($conn));
					}
				}else echo mysqli_error($conn);
			}
		}
		echo json_encode($array);
	}
	else if($_POST['action']=='reserve'){
		$account=$_POST['account'];
		$code=$_POST['code'];
		$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
		$due=date('Y-m-d', strtotime("+3 days"));
		$array=array();
		mysqli_query($link, 'start transaction');
		if(!check_reserve($code,$account)){
			$query=mysqli_query($link, 'INSERT INTO `log`(`account`,`code`,`hold`,`due`) VALUES ("'.$account.'","'.$code.'","'.$datetime.'","'.$due.'")');
			if($query){
				$copy_query=mysqli_query($link, 'SELECT `status` FROM `book_copy` WHERE `code`="'.$code.'"');
				$fetch=mysqli_fetch_assoc($copy_query);
				switch($fetch['status']){
					case 'on-shelf';
						$status='on-hold';
						$color='#428bca';
					break;
					case 'on-loan';
						$status='on-loan&on-hold';
						$color='#f0ad4e';
					break;
				}
				$update=mysqli_query($link, 'UPDATE `book_copy` SET `status`="'.$status.'" WHERE `code`="'.$code.'"');
			}else mysqli_query($link, 'ROLLBACK');
		}
		else{
			$query=mysqli_query($link, 'UPDATE `log` SET `hold_cancel`="'.$datetime.'" WHERE `account`="'.$account.'" AND `code`="'.$code.'" AND `hold` IS NOT NULL AND `hold_cancel` IS NULL');
			if($query){
				$copy_query=mysqli_query($link, 'SELECT `status` FROM `book_copy` WHERE `code`="'.$code.'"');
				$fetch=mysqli_fetch_assoc($copy_query);
				switch($fetch['status']){
					case 'on-hold';
						$status='on-shelf';
						$color='#5cb85c';
					break;
					case 'on-loan&on-hold';
						$status='on-loan';
						$color='#d9534f';
					break;
				}
				$update=mysqli_query($link, 'UPDATE `book_copy` SET `status`="'.$status.'" WHERE `code`="'.$code.'"');
			}else mysqli_query($link, 'ROLLBACK');
		}
		if($update){
			mysqli_query($link, 'COMMIT');
			echo 'success';
		}
		else{
			echo 'error';
			echo mysqli_error($conn);
			mysqli_query($link, 'ROLLBACK');
		}
	}
	
	function check_book($book){
		$query=mysqli_query($link, 'SELECT * FROM `book_copy` WHERE `code`="'.$book.'"');
		$row=mysqli_num_rows($query);
		if($row==1) return true;
		else return false;
	}
	function if_check_in($acc,$book){
		$query=mysqli_query($link, 'SELECT * FROM `log` WHERE `account`="'.$acc.'" AND `code`="'.$book.'" AND `hold` is null AND `check_in` is not null AND `check_out` is null');
		$row=mysqli_num_rows($query);
		if($row==1) return true;
		else return false;
	}
	function check_reserve($code,$acc){
		$query=mysqli_query($link, 'SELECT * FROM `log` WHERE `account`="'.$acc.'" AND `code`="'.$code.'" AND `hold` IS NOT NULL AND `hold_cancel` IS NULL');
		$row=mysqli_num_rows($query);
		if($row==0) return false;
		else return true;
	}
?>