<?php
	require_once '../control/conn.php';
	$action=$_POST['action'];
	$array=array();
	if($action=='checkio'){
		$account=$_POST['account'];
		$query=mysqli_query($link, 'SELECT book.title, log.check_in
				FROM `log` INNER JOIN `book_copy` bc 
					ON log.code=bc.code
					    INNER JOIN `book`
					   		ON bc.isbn=book.isbn
				WHERE log.check_out IS NULL AND log.hold IS NULL AND log.account="'.$account.'"
				ORDER BY log.id DESC');
		while($res=mysqli_fetch_row($query)){
			$array[]=array('title' => $res[0], 'time' => $res[1]);
		}
		
	}
	else if($action=='memberlog'){
		$datetime= $_POST['datetime'];
		$account= $_POST['account'];
		if($datetime=='null'){
			$query=mysqli_query($link, 'SELECT book.title, log.check_in, log.check_out, log.hold
				FROM `log` INNER JOIN `book_copy` bc 
					ON log.code=bc.code
					    INNER JOIN `book`
					    	ON bc.isbn=book.isbn
				WHERE log.account = "'.$account.'" AND log.hold_cancel IS NULL
				ORDER BY log.id DESC');
		}	
		else{
			$query=mysqli_query($link, 'SELECT book.title, log.check_in, log.check_out, log.hold
				FROM `log` INNER JOIN `book_copy` bc 
					ON log.code=bc.code
					    INNER JOIN `book`
							ON bc.isbn=book.isbn
				WHERE log.account = "'.$account.'"
					AND (log.check_in >="'.$datetime.'"
						or log.check_out >="'.$datetime.'"
                        or log.hold >="'.$datetime.'" )  AND log.hold_cancel IS NULL
				ORDER BY log.id DESC');
		}
		if($query){
			while($res=mysqli_fetch_row($query)){
				$array[]=array('title' => $res[0], 'in' => $res[1], 'out' => $res[2], 'hold' => $res[3]);
			}
		}
	}else if($action=='booklog'){
		$isbn=$_POST['isbn'];
		$query=mysqli_query($link, 'SELECT bc.number, log.account, log.check_in, log.check_out, log.hold
				FROM `log` INNER JOIN `book_copy` bc 
					ON log.code=bc.code
					    INNER JOIN `book`
							ON book.isbn=bc.isbn
				WHERE bc.isbn="'.$isbn.'"
				ORDER BY log.id DESC');
		if($query){
			while($res=mysqli_fetch_row($query)){
				$array[]=array('copy' => $res[0], 'account' => $res[1], 'in' => $res[2], 'out' => $res[3], 'hold' => $res[4]);
			}
		}
	}
	echo json_encode($array);
?>